#!/usr/bin/env bash
# =============================================================================
#  ADAM44 — Production MySQL Backup Script
#  File   : /var/www/FinanceProject/scripts/backup-db.sh
#  Runs   : Every 6 hours via cron (see crontab section at bottom of file)
#  Stores : /var/www/backups/YYYY-MM-DD/adam44_YYYYMMDD_HHMMSS.sql.gz
#  Keeps  : Last 28 files (7 days × 4 per day), then auto-purges older ones
#  Log    : /var/www/backups/backup.log
# =============================================================================

set -euo pipefail

# ── Configuration ─────────────────────────────────────────────────────────────

PROJECT_DIR="/var/www/FinanceProject"
BACKUP_ROOT="/var/www/backups"
LOG_FILE="${BACKUP_ROOT}/backup.log"
MAX_BACKUPS=28          # 7 days × 4 backups/day
BACKUP_NAME="adam44"

# ── Helpers ───────────────────────────────────────────────────────────────────

ts()  { date '+%Y-%m-%d %H:%M:%S'; }
log() { echo "[$(ts)] $*" | tee -a "$LOG_FILE"; }

die() {
    log "ERROR: $*"
    # Optional: send an alert email if 'mail' is installed
    if command -v mail &>/dev/null; then
        echo "ADAM44 backup FAILED at $(ts): $*" \
            | mail -s "🚨 ADAM44 DB Backup FAILED" "efremfretewahdo@gmail.com" 2>/dev/null || true
    fi
    exit 1
}

# ── Parse credentials from .env (never hard-code passwords) ──────────────────

get_env() {
    # Safely reads KEY=value lines from .env; handles quoted and unquoted values
    local key="$1"
    local val
    val=$(grep -E "^${key}=" "${PROJECT_DIR}/.env" 2>/dev/null \
          | head -1 \
          | cut -d'=' -f2- \
          | sed "s/^['\"]//;s/['\"]$//")
    echo "$val"
}

DB_HOST="${DB_HOST_OVERRIDE:-$(get_env DB_HOST)}"
DB_PORT="${DB_PORT_OVERRIDE:-$(get_env DB_PORT)}"
DB_DATABASE="$(get_env DB_DATABASE)"
DB_USERNAME="$(get_env DB_USERNAME)"
DB_PASSWORD="$(get_env DB_PASSWORD)"

DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"

# ── Validate credentials were found ──────────────────────────────────────────

[[ -z "$DB_DATABASE" ]] && die "DB_DATABASE not found in .env"
[[ -z "$DB_USERNAME" ]] && die "DB_USERNAME not found in .env"
# DB_PASSWORD can legitimately be empty on some dev setups, so we don't check it

# ── Prepare directories ───────────────────────────────────────────────────────

DATE_DIR="${BACKUP_ROOT}/$(date '+%Y-%m-%d')"
mkdir -p "$DATE_DIR"
chmod 700 "$BACKUP_ROOT"          # only root/backup user can read the dumps
chmod 700 "$DATE_DIR"

# ── Build file path ───────────────────────────────────────────────────────────

TIMESTAMP="$(date '+%Y%m%d_%H%M%S')"
BACKUP_FILE="${DATE_DIR}/${BACKUP_NAME}_${TIMESTAMP}.sql.gz"
CHECKSUM_FILE="${BACKUP_FILE}.sha256"

log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
log "Starting backup → ${BACKUP_FILE}"
log "Database : ${DB_DATABASE}  Host: ${DB_HOST}:${DB_PORT}"

# ── Write a temporary MySQL credentials file (avoids password in process list) ─

MYCNF="$(mktemp /tmp/.my_XXXXXX.cnf)"
chmod 600 "$MYCNF"
cat > "$MYCNF" << EOF
[client]
host=${DB_HOST}
port=${DB_PORT}
user=${DB_USERNAME}
password=${DB_PASSWORD}
EOF

# Always remove the temp credentials file on exit (even on error)
trap 'rm -f "$MYCNF"' EXIT

# ── Run mysqldump ──────────────────────────────────────────────────────────────

mysqldump \
    --defaults-extra-file="$MYCNF" \
    --single-transaction \
    --routines \
    --triggers \
    --events \
    --set-gtid-purged=OFF \
    --column-statistics=0 \
    --hex-blob \
    --no-tablespaces \
    "${DB_DATABASE}" \
    | gzip -9 > "${BACKUP_FILE}" \
    || die "mysqldump failed for database '${DB_DATABASE}'"

# ── Verify the file is non-empty and valid gzip ───────────────────────────────

FILESIZE=$(stat -c%s "${BACKUP_FILE}" 2>/dev/null || echo 0)
[[ "$FILESIZE" -lt 512 ]] && die "Backup file is suspiciously small (${FILESIZE} bytes) — aborting"

gzip -t "${BACKUP_FILE}" 2>/dev/null \
    || die "gzip integrity check failed on ${BACKUP_FILE}"

# ── Generate SHA-256 checksum (used to verify restores) ──────────────────────

sha256sum "${BACKUP_FILE}" > "${CHECKSUM_FILE}"
log "Checksum : $(cat ${CHECKSUM_FILE})"

# ── Print size ─────────────────────────────────────────────────────────────────

HUMAN_SIZE=$(du -sh "${BACKUP_FILE}" | cut -f1)
log "Size     : ${HUMAN_SIZE}"

# ── Rotate old backups — keep only the most recent MAX_BACKUPS files ──────────

log "Rotating — keeping last ${MAX_BACKUPS} backups..."

# Find all .sql.gz files sorted oldest-first; delete any beyond the limit
TOTAL=$(find "$BACKUP_ROOT" -name "*.sql.gz" | wc -l)
if [[ "$TOTAL" -gt "$MAX_BACKUPS" ]]; then
    TO_DELETE=$(( TOTAL - MAX_BACKUPS ))
    find "$BACKUP_ROOT" -name "*.sql.gz" -printf '%T+ %p\n' \
        | sort \
        | head -n "$TO_DELETE" \
        | awk '{print $2}' \
        | while read -r OLD_FILE; do
            rm -f "$OLD_FILE" "${OLD_FILE}.sha256"
            log "Deleted  : ${OLD_FILE}"
          done
fi

# Remove empty date directories
find "$BACKUP_ROOT" -mindepth 1 -maxdepth 1 -type d -empty -delete 2>/dev/null || true

# ── Done ──────────────────────────────────────────────────────────────────────

log "SUCCESS  : Backup complete → ${BACKUP_FILE}"
log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Exit cleanly (trap will remove the credentials temp file)
exit 0

# =============================================================================
#  CRON INSTALLATION (run as root on the VPS):
#
#    chmod +x /var/www/FinanceProject/scripts/backup-db.sh
#    mkdir -p /var/www/backups && chmod 700 /var/www/backups
#
#    crontab -e    # (as root)
#
#  Add this line — runs at 00:00, 06:00, 12:00, 18:00 every day:
#    0 */6 * * * /var/www/FinanceProject/scripts/backup-db.sh >> /var/www/backups/backup.log 2>&1
#
#  Verify it was added:
#    crontab -l
#
#  Monitor the log after the first run:
#    tail -f /var/www/backups/backup.log
# =============================================================================
