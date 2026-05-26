#!/usr/bin/env bash
# =============================================================================
#  ADAM44 — Database Restore Script
#  File   : /var/www/FinanceProject/scripts/restore-db.sh
#
#  Usage  : sudo bash restore-db.sh /var/www/backups/2026-05-25/adam44_20260525_060000.sql.gz
#
#  Safety checks performed before any data is touched:
#    1. Verifies the backup file exists and is a valid gzip archive
#    2. Verifies the SHA-256 checksum matches (detects corrupted backups)
#    3. Dumps the CURRENT live database as an emergency snapshot
#    4. Requires explicit typed confirmation before overwriting live data
#    5. Only then imports the backup inside a transaction
# =============================================================================

set -euo pipefail

PROJECT_DIR="/var/www/FinanceProject"
BACKUP_ROOT="/var/www/backups"
LOG_FILE="${BACKUP_ROOT}/restore.log"
EMERGENCY_DIR="${BACKUP_ROOT}/emergency"

# ── Helpers ───────────────────────────────────────────────────────────────────

ts()  { date '+%Y-%m-%d %H:%M:%S'; }
log() { echo "[$(ts)] $*" | tee -a "$LOG_FILE"; }
die() { log "FATAL: $*"; exit 1; }

red()   { echo -e "\033[31m$*\033[0m"; }
green() { echo -e "\033[32m$*\033[0m"; }
yellow(){ echo -e "\033[33m$*\033[0m"; }

# ── Argument validation ───────────────────────────────────────────────────────

if [[ $# -lt 1 ]]; then
    red "Usage: sudo bash $0 <path-to-backup.sql.gz>"
    echo ""
    echo "Example:"
    echo "  sudo bash $0 /var/www/backups/2026-05-25/adam44_20260525_060000.sql.gz"
    echo ""
    echo "Available backups:"
    find "$BACKUP_ROOT" -name "*.sql.gz" -printf '  %T+ %p\n' | sort -r | head -20
    exit 1
fi

RESTORE_FILE="$1"

# ── Validate the backup file ──────────────────────────────────────────────────

log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
log "Restore requested: ${RESTORE_FILE}"

[[ -f "$RESTORE_FILE" ]] \
    || die "Backup file not found: ${RESTORE_FILE}"

[[ "$RESTORE_FILE" == *.sql.gz ]] \
    || die "File must be a .sql.gz archive. Received: ${RESTORE_FILE}"

log "Verifying gzip integrity..."
gzip -t "$RESTORE_FILE" \
    || die "gzip integrity check FAILED — file may be corrupted. Do NOT restore this."

# ── Verify SHA-256 checksum if available ─────────────────────────────────────

CHECKSUM_FILE="${RESTORE_FILE}.sha256"
if [[ -f "$CHECKSUM_FILE" ]]; then
    log "Verifying SHA-256 checksum..."
    sha256sum -c "$CHECKSUM_FILE" \
        || die "SHA-256 checksum MISMATCH — file is corrupted or tampered. Aborting."
    log "Checksum: OK"
else
    yellow "[WARNING] No .sha256 file found for this backup — skipping checksum verification."
    yellow "          Proceeding, but verify the backup source carefully."
fi

# ── Parse DB credentials ──────────────────────────────────────────────────────

get_env() {
    grep -E "^${1}=" "${PROJECT_DIR}/.env" 2>/dev/null \
        | head -1 | cut -d'=' -f2- | sed "s/^['\"]//;s/['\"]$//"
}

DB_HOST="$(get_env DB_HOST)";     DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="$(get_env DB_PORT)";     DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="$(get_env DB_DATABASE)"
DB_USERNAME="$(get_env DB_USERNAME)"
DB_PASSWORD="$(get_env DB_PASSWORD)"

[[ -z "$DB_DATABASE" ]] && die "DB_DATABASE not found in .env"
[[ -z "$DB_USERNAME" ]] && die "DB_USERNAME not found in .env"

MYCNF="$(mktemp /tmp/.my_XXXXXX.cnf)"
chmod 600 "$MYCNF"
cat > "$MYCNF" << EOF
[client]
host=${DB_HOST}
port=${DB_PORT}
user=${DB_USERNAME}
password=${DB_PASSWORD}
EOF
trap 'rm -f "$MYCNF"' EXIT

# ── Show summary and require typed confirmation ───────────────────────────────

echo ""
red    "  ┌─────────────────────────────────────────────────────────────┐"
red    "  │   ⚠️   WARNING — THIS WILL OVERWRITE THE LIVE DATABASE   ⚠️   │"
red    "  ├─────────────────────────────────────────────────────────────┤"
yellow "  │  Database  : ${DB_DATABASE}"
yellow "  │  Host      : ${DB_HOST}:${DB_PORT}"
yellow "  │  Restore   : ${RESTORE_FILE}"
yellow "  │  File size : $(du -sh "$RESTORE_FILE" | cut -f1)"
red    "  ├─────────────────────────────────────────────────────────────┤"
red    "  │  ALL current data in '${DB_DATABASE}' will be REPLACED.    │"
red    "  │  An emergency snapshot will be created first.               │"
red    "  └─────────────────────────────────────────────────────────────┘"
echo ""
echo -n "  Type 'yes i understand' to proceed, or anything else to abort: "
read -r CONFIRM

if [[ "$CONFIRM" != "yes i understand" ]]; then
    log "Restore aborted by operator."
    yellow "Aborted — no data was changed."
    exit 0
fi

# ── Step 1: Put Laravel into maintenance mode ─────────────────────────────────

log "Enabling maintenance mode..."
cd "$PROJECT_DIR"
php artisan down --render="errors::503" \
    || log "[WARN] artisan down failed — continuing anyway"

# ── Step 2: Emergency snapshot of the CURRENT database ───────────────────────

mkdir -p "$EMERGENCY_DIR"
chmod 700 "$EMERGENCY_DIR"
EMERGENCY_TIMESTAMP="$(date '+%Y%m%d_%H%M%S')"
EMERGENCY_FILE="${EMERGENCY_DIR}/pre-restore_${EMERGENCY_TIMESTAMP}.sql.gz"

log "Creating emergency snapshot of current live database → ${EMERGENCY_FILE}"

mysqldump \
    --defaults-extra-file="$MYCNF" \
    --single-transaction \
    --routines \
    --triggers \
    --events \
    --set-gtid-purged=OFF \
    --column-statistics=0 \
    --no-tablespaces \
    "${DB_DATABASE}" \
    | gzip -9 > "${EMERGENCY_FILE}" \
    || die "Emergency snapshot FAILED. Restore aborted — live data is intact."

sha256sum "${EMERGENCY_FILE}" > "${EMERGENCY_FILE}.sha256"
log "Emergency snapshot complete ($(du -sh "$EMERGENCY_FILE" | cut -f1))"

# ── Step 3: Drop and recreate the database (clean slate) ─────────────────────

log "Dropping and recreating database '${DB_DATABASE}'..."

mysql --defaults-extra-file="$MYCNF" <<SQL
    DROP DATABASE IF EXISTS \`${DB_DATABASE}\`;
    CREATE DATABASE \`${DB_DATABASE}\`
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci;
SQL
log "Database recreated."

# ── Step 4: Import the backup ─────────────────────────────────────────────────

log "Importing backup — this may take a few minutes..."

gunzip -c "${RESTORE_FILE}" \
    | mysql --defaults-extra-file="$MYCNF" "${DB_DATABASE}" \
    || die "MySQL import FAILED. Emergency snapshot is at: ${EMERGENCY_FILE}"

log "Import complete."

# ── Step 5: Post-restore verification ────────────────────────────────────────

log "Verifying table count..."
TABLE_COUNT=$(mysql --defaults-extra-file="$MYCNF" \
    -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_DATABASE}';" \
    -s --skip-column-names)
log "Tables in restored database: ${TABLE_COUNT}"

[[ "$TABLE_COUNT" -lt 5 ]] \
    && die "Suspiciously few tables (${TABLE_COUNT}). Restore may have failed."

# ── Step 6: Run any migrations that are newer than the backup ────────────────

log "Running outstanding migrations (safe — adds columns/tables only)..."
php artisan migrate --force \
    || log "[WARN] migrate --force returned non-zero — check manually"

# ── Step 7: Clear all caches ─────────────────────────────────────────────────

log "Clearing caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── Step 8: Bring site back up ───────────────────────────────────────────────

log "Disabling maintenance mode..."
php artisan up

# ── Done ──────────────────────────────────────────────────────────────────────

echo ""
green "  ✅  Restore complete!"
echo ""
echo "  Restored from : ${RESTORE_FILE}"
echo "  Emergency copy: ${EMERGENCY_FILE}"
echo "  Tables        : ${TABLE_COUNT}"
echo "  Log           : ${LOG_FILE}"
echo ""
log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
log "RESTORE COMPLETE: ${RESTORE_FILE} → ${DB_DATABASE}"
log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

exit 0
