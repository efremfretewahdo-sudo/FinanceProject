import { Ionicons } from '@expo/vector-icons';
import { useCallback, useEffect, useRef, useState } from 'react';
import {
  Animated,
  Dimensions,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import { apiGetDashboard, DashboardData, DashboardTransaction } from '@/services/dashboard-api';

const { width } = Dimensions.get('window');

// ---------------------------------------------------------------------------
// Design tokens
// ---------------------------------------------------------------------------

const NAVY  = '#051A3A';
const TEAL  = '#26E6A5';
const BLUE  = '#007AFF';
const GREEN = '#00C48C';
const RED   = '#FF4D4F';
const CARD_WIDTH = width > 450 ? 400 : width;

type IconName = React.ComponentProps<typeof Ionicons>['name'];

// ---------------------------------------------------------------------------
// Category → icon / colour map
// Covers mock categories + common real-world categories from Laravel
// ---------------------------------------------------------------------------

type CategoryStyle = { icon: IconName; color: string; bg: string };

const CATEGORY_MAP: Record<string, CategoryStyle> = {
  Entertainment: { icon: 'film-outline',        color: '#8B5CF6', bg: '#F3F0FF' },
  Income:        { icon: 'cash-outline',         color: '#00C48C', bg: '#EDFBF5' },
  Salary:        { icon: 'briefcase-outline',    color: '#00C48C', bg: '#EDFBF5' },
  Food:          { icon: 'restaurant-outline',   color: '#F59E0B', bg: '#FFFBEB' },
  Transport:     { icon: 'car-outline',          color: '#3B82F6', bg: '#EFF6FF' },
  Shopping:      { icon: 'bag-outline',          color: '#EC4899', bg: '#FDF2F8' },
  Utilities:     { icon: 'flash-outline',        color: '#6B7280', bg: '#F9FAFB' },
  Healthcare:    { icon: 'medkit-outline',       color: '#EF4444', bg: '#FEF2F2' },
  Education:     { icon: 'book-outline',         color: '#0EA5E9', bg: '#F0F9FF' },
  Travel:        { icon: 'airplane-outline',     color: '#F97316', bg: '#FFF7ED' },
  Subscriptions: { icon: 'repeat-outline',       color: '#6366F1', bg: '#EEF2FF' },
  General:       { icon: 'receipt-outline',      color: '#9CA3AF', bg: '#F3F4F6' },
};

const FALLBACK_STYLE: CategoryStyle = {
  icon: 'ellipse-outline', color: '#9CA3AF', bg: '#F3F4F6',
};

function getCategoryStyle(category: string): CategoryStyle {
  return CATEGORY_MAP[category] ?? FALLBACK_STYLE;
}

// ---------------------------------------------------------------------------
// Formatting helpers
// ---------------------------------------------------------------------------

function fmtInt(n: number): string {
  return Math.floor(Math.abs(n)).toLocaleString('en-US');
}

function fmtCents(n: number): string {
  return Math.abs(n).toFixed(2).split('.')[1];
}

function fmtCompact(n: number): string {
  return new Intl.NumberFormat('en-US', {
    style: 'currency', currency: 'USD', maximumFractionDigits: 0,
  }).format(Math.abs(n));
}

function fmtAmount(amount: number): string {
  const abs = new Intl.NumberFormat('en-US', {
    style: 'currency', currency: 'USD',
  }).format(Math.abs(amount));
  return amount >= 0 ? `+${abs}` : `-${abs}`;
}

/**
 * Formats a transaction date string.
 *  - Mock data sends short labels ("Today", "Mon") — pass through unchanged.
 *  - Real API sends ISO dates ("2026-05-25") — format to "Monday, May 25".
 */
function fmtTxDate(dateStr: string): string {
  if (!/^\d{4}-\d{2}-\d{2}/.test(dateStr)) return dateStr; // already a label
  const d = new Date(`${dateStr}T00:00:00`);                // force local midnight
  const diffDays = Math.floor((Date.now() - d.getTime()) / 86_400_000);
  if (diffDays === 0) return 'Today';
  if (diffDays === 1) return 'Yesterday';
  return d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
}

// ---------------------------------------------------------------------------
// Account data derived from API
// ---------------------------------------------------------------------------

type Account = {
  id: string; flag: string; name: string;
  balance: string; cents: string; currency: string; primary?: boolean;
};

function buildAccounts(data: DashboardData): Account[] {
  return [
    { id: '1', flag: '🇧🇭', name: 'Main Account',      balance: fmtInt(data.balance.total),    cents: fmtCents(data.balance.total),    currency: 'USD', primary: true },
    { id: '2', flag: '🇺🇸', name: 'Monthly Income',    balance: fmtInt(data.balance.income),   cents: fmtCents(data.balance.income),   currency: 'USD' },
    { id: '3', flag: '🇪🇺', name: 'Monthly Expenses',  balance: fmtInt(data.balance.expenses), cents: fmtCents(data.balance.expenses), currency: 'USD' },
  ];
}

// ---------------------------------------------------------------------------
// Shimmer skeleton
// ---------------------------------------------------------------------------

function useShimmer() {
  const anim = useRef(new Animated.Value(0.4)).current;
  useEffect(() => {
    const loop = Animated.loop(
      Animated.sequence([
        Animated.timing(anim, { toValue: 1,   duration: 750, useNativeDriver: true }),
        Animated.timing(anim, { toValue: 0.4, duration: 750, useNativeDriver: true }),
      ])
    );
    loop.start();
    return () => loop.stop();
  }, [anim]);
  return anim;
}

function SkeletonBox({ w, h, r = 8, opacity, style }: {
  w: number | string; h: number; r?: number;
  opacity: Animated.Value; style?: object;
}) {
  return (
    <Animated.View
      style={[{ width: w as any, height: h, borderRadius: r, backgroundColor: '#E8ECF0', opacity }, style]}
    />
  );
}

function LoadingCard({ primary, opacity }: { primary?: boolean; opacity: Animated.Value }) {
  return (
    <View style={[styles.card, primary ? styles.primaryCard : styles.secondaryCard]}>
      <View style={styles.cardHeader}>
        <View style={styles.flagRow}>
          <SkeletonBox w={28} h={28} r={14} opacity={opacity} />
          <SkeletonBox w={140} h={14} opacity={opacity} style={{ marginLeft: 8 }} />
        </View>
      </View>
      <SkeletonBox w={primary ? '70%' : '50%'} h={primary ? 32 : 24} opacity={opacity} style={{ marginTop: 4 }} />
      {primary && (
        <View style={[styles.actionRow, { marginTop: 16 }]}>
          <SkeletonBox w={110} h={32} r={20} opacity={opacity} />
          <SkeletonBox w={110} h={32} r={20} opacity={opacity} />
        </View>
      )}
    </View>
  );
}

/** Skeleton for a single transaction row */
function LoadingTxRow({ opacity }: { opacity: Animated.Value }) {
  return (
    <View style={tx.row}>
      <SkeletonBox w={44} h={44} r={13} opacity={opacity} />
      <View style={tx.mid}>
        <SkeletonBox w={'65%'} h={14} opacity={opacity} />
        <SkeletonBox w={'45%'} h={11} opacity={opacity} style={{ marginTop: 6 }} />
      </View>
      <View style={tx.right}>
        <SkeletonBox w={70} h={14} opacity={opacity} />
        <SkeletonBox w={48} h={18} r={6} opacity={opacity} style={{ marginTop: 6 }} />
      </View>
    </View>
  );
}

// ---------------------------------------------------------------------------
// Screen
// ---------------------------------------------------------------------------

export default function FinanceDashboard() {
  const [data,     setData]     = useState<DashboardData | null>(null);
  const [loading,  setLoading]  = useState(true);
  const [error,    setError]    = useState<string | null>(null);
  const [fetchKey, setFetchKey] = useState(0);
  const [activeTab, setActiveTab] = useState<'accounts' | 'savings'>('accounts');

  const shimmer = useShimmer();

  const retry = useCallback(() => {
    setLoading(true);
    setError(null);
    setFetchKey(k => k + 1);
  }, []);

  useEffect(() => {
    let cancelled = false;
    (async () => {
      try {
        const result = await apiGetDashboard();
        if (!cancelled) setData(result);
      } catch (e) {
        if (!cancelled)
          setError(e instanceof Error ? e.message : 'Failed to load dashboard.');
      } finally {
        if (!cancelled) setLoading(false);
      }
    })();
    return () => { cancelled = true; };
  }, [fetchKey]);

  const accounts = data ? buildAccounts(data) : [];

  return (
    <SafeAreaView edges={['top']} style={styles.safe}>
      <View style={styles.outer}>
        <ScrollView
          style={styles.scroll}
          contentContainerStyle={styles.scrollContent}
          showsVerticalScrollIndicator={false}>

          {/* ── Brand header ── */}
          <View style={styles.brandHeader}>
            <Text style={styles.brandLogo}>ila</Text>
          </View>

          {/* ── Segmented control ── */}
          <View style={styles.segmentRow}>
            {(['accounts', 'savings'] as const).map(tab => (
              <TouchableOpacity
                key={tab}
                style={[styles.segment, activeTab === tab && styles.segmentActive]}
                onPress={() => setActiveTab(tab)}
                activeOpacity={0.85}>
                <Text style={activeTab === tab ? styles.segmentActiveText : styles.segmentInactiveText}>
                  {tab.charAt(0).toUpperCase() + tab.slice(1)}
                </Text>
              </TouchableOpacity>
            ))}
          </View>

          {/* ── Summary strip ── */}
          {data && (
            <SummaryStrip
              income={data.balance.income}
              expenses={data.balance.expenses}
              changePct={data.balance.changePct}
            />
          )}

          {/* ══════════════════════════════════════════════════════════════════
              ACCOUNT CARDS
          ══════════════════════════════════════════════════════════════════ */}
          <View style={styles.list}>

            {loading && (
              <>
                <LoadingCard primary opacity={shimmer} />
                <LoadingCard opacity={shimmer} />
                <LoadingCard opacity={shimmer} />
              </>
            )}

            {!loading && error && (
              <View style={styles.errorBox}>
                <Ionicons name="cloud-offline-outline" size={40} color="#bbb" />
                <Text style={styles.errorTitle}>Could not load accounts</Text>
                <Text style={styles.errorBody}>{error}</Text>
                <TouchableOpacity style={styles.retryBtn} onPress={retry} activeOpacity={0.8}>
                  <Ionicons name="refresh-outline" size={16} color="#fff" />
                  <Text style={styles.retryText}>Try Again</Text>
                </TouchableOpacity>
              </View>
            )}

            {!loading && !error && accounts.map(account =>
              account.primary
                ? <PrimaryCard  key={account.id} account={account} />
                : <SecondaryCard key={account.id} account={account} />
            )}

            {!loading && !error && (
              <TouchableOpacity style={styles.addCard} activeOpacity={0.7}>
                <View style={styles.addCircle}>
                  <Ionicons name="add" size={24} color="#aaa" />
                </View>
                <Text style={styles.addText}>Add new account</Text>
              </TouchableOpacity>
            )}

          </View>

          {/* ══════════════════════════════════════════════════════════════════
              RECENT TRANSACTIONS
          ══════════════════════════════════════════════════════════════════ */}
          <View style={styles.txSection}>

            {/* Section header */}
            <View style={styles.txHeader}>
              <View>
                <Text style={styles.txTitle}>Recent Transactions</Text>
                <Text style={styles.txSubtitle}>ናይ ቀረባ ምንቅስቓሳት</Text>
              </View>
              <TouchableOpacity activeOpacity={0.7}>
                <Text style={styles.viewAll}>View all →</Text>
              </TouchableOpacity>
            </View>

            {/* Skeleton rows while loading */}
            {loading && [1, 2, 3, 4, 5].map(n => (
              <LoadingTxRow key={n} opacity={shimmer} />
            ))}

            {/* Real transaction rows */}
            {!loading && !error && data && data.transactions.length > 0 && (
              <View style={tx.list}>
                {data.transactions.map((item, index) => (
                  <TransactionRow
                    key={item.id}
                    item={item}
                    isLast={index === data.transactions.length - 1}
                  />
                ))}
              </View>
            )}

            {/* Empty state */}
            {!loading && !error && data && data.transactions.length === 0 && (
              <View style={styles.emptyTx}>
                <Ionicons name="receipt-outline" size={36} color="#ccc" />
                <Text style={styles.emptyTxText}>No transactions yet</Text>
              </View>
            )}

          </View>

        </ScrollView>
      </View>
    </SafeAreaView>
  );
}

// ---------------------------------------------------------------------------
// Transaction row
// ---------------------------------------------------------------------------

function TransactionRow({
  item, isLast,
}: { item: DashboardTransaction; isLast: boolean }) {
  const { icon, color, bg } = getCategoryStyle(item.category);
  const isIncome  = item.amount >= 0;
  const amountStr = fmtAmount(item.amount);
  const dateStr   = fmtTxDate(item.date);

  return (
    <TouchableOpacity
      style={[tx.row, !isLast && tx.rowBorder]}
      activeOpacity={0.65}>

      {/* Icon */}
      <View style={[tx.iconBox, { backgroundColor: bg }]}>
        <Ionicons name={icon} size={18} color={color} />
      </View>

      {/* Description + meta */}
      <View style={tx.mid}>
        <Text style={tx.name} numberOfLines={1}>{item.name}</Text>
        <Text style={tx.meta}>{item.category}  ·  {dateStr}</Text>
      </View>

      {/* Amount + type badge */}
      <View style={tx.right}>
        <Text style={[tx.amount, { color: isIncome ? GREEN : RED }]}>
          {amountStr}
        </Text>
        <View style={[tx.badge, { backgroundColor: isIncome ? '#EDFBF5' : '#FEF2F2' }]}>
          <Text style={[tx.badgeText, { color: isIncome ? GREEN : RED }]}>
            {isIncome ? 'Income' : 'Expense'}
          </Text>
        </View>
      </View>

    </TouchableOpacity>
  );
}

const tx = StyleSheet.create({
  list:       { borderRadius: 14, borderWidth: 1, borderColor: '#F0F4FA', overflow: 'hidden' },
  row: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 14,
    paddingHorizontal: 16,
    backgroundColor: '#fff',
    gap: 12,
  },
  rowBorder:  { borderBottomWidth: StyleSheet.hairlineWidth, borderBottomColor: '#F0F4FA' },
  iconBox: {
    width: 44, height: 44, borderRadius: 13,
    alignItems: 'center', justifyContent: 'center', flexShrink: 0,
  },
  mid:        { flex: 1, gap: 4 },
  name:       { fontSize: 14, fontWeight: '600', color: '#1A1A2E' },
  meta:       { fontSize: 12, color: '#9CA3AF' },
  right:      { alignItems: 'flex-end', gap: 5 },
  amount:     { fontSize: 14, fontWeight: '700' },
  badge: {
    paddingHorizontal: 8, paddingVertical: 3,
    borderRadius: 6,
  },
  badgeText:  { fontSize: 10, fontWeight: '700' },
});

// ---------------------------------------------------------------------------
// Account sub-components
// ---------------------------------------------------------------------------

function SummaryStrip({ income, expenses, changePct }: {
  income: number; expenses: number; changePct: number;
}) {
  const positive = changePct >= 0;
  return (
    <View style={strip.row}>
      <View style={strip.item}>
        <Ionicons name="arrow-down-circle" size={16} color={GREEN} />
        <Text style={strip.label}>Income</Text>
        <Text style={[strip.value, { color: GREEN }]}>{fmtCompact(income)}</Text>
      </View>
      <View style={strip.divider} />
      <View style={strip.item}>
        <Ionicons name="arrow-up-circle" size={16} color={RED} />
        <Text style={strip.label}>Expenses</Text>
        <Text style={[strip.value, { color: RED }]}>{fmtCompact(expenses)}</Text>
      </View>
      <View style={strip.divider} />
      <View style={strip.item}>
        <Ionicons name={positive ? 'trending-up' : 'trending-down'} size={16} color={positive ? GREEN : RED} />
        <Text style={strip.label}>Net</Text>
        <Text style={[strip.value, { color: positive ? GREEN : RED }]}>
          {positive ? '+' : ''}{changePct}%
        </Text>
      </View>
    </View>
  );
}

const strip = StyleSheet.create({
  row: {
    flexDirection: 'row',
    marginHorizontal: 20, marginTop: 16,
    paddingVertical: 12, paddingHorizontal: 16,
    backgroundColor: '#F6F9FF',
    borderRadius: 12, borderWidth: 1, borderColor: '#E8EEF8',
  },
  item:    { flex: 1, alignItems: 'center', gap: 3 },
  divider: { width: 1, backgroundColor: '#E0E8F0', marginVertical: 2 },
  label:   { fontSize: 11, color: '#888', fontWeight: '500' },
  value:   { fontSize: 13, fontWeight: '700' },
});

function PrimaryCard({ account }: { account: Account }) {
  return (
    <View style={[styles.card, styles.primaryCard]}>
      <CardHeader account={account} />
      <Text style={styles.primaryBalance}>
        {account.balance}.<Text style={styles.primaryCents}>{account.cents}</Text>
      </Text>
      <Text style={styles.primaryCurrency}>{account.currency}</Text>
      <View style={styles.actionRow}>
        <TouchableOpacity style={styles.outlineBtn} activeOpacity={0.75}>
          <Text style={styles.outlineBtnText}>Transfer</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.solidBtn} activeOpacity={0.75}>
          <Ionicons name="add-circle" size={15} color="#fff" />
          <Text style={styles.solidBtnText}>Add Money</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

function SecondaryCard({ account }: { account: Account }) {
  return (
    <View style={[styles.card, styles.secondaryCard]}>
      <CardHeader account={account} />
      <Text style={styles.secondaryBalance}>
        {account.balance}.<Text style={styles.secondaryCents}>{account.cents}</Text>
        {'  '}<Text style={styles.secondaryCurrency}>{account.currency}</Text>
      </Text>
    </View>
  );
}

function CardHeader({ account }: { account: Account }) {
  return (
    <View style={styles.cardHeader}>
      <View style={styles.flagRow}>
        <Text style={styles.flag}>{account.flag}</Text>
        <Text style={styles.accountName}>{account.name}</Text>
      </View>
      <Ionicons name="chevron-forward" size={18} color="#bbb" />
    </View>
  );
}

// ---------------------------------------------------------------------------
// Styles
// ---------------------------------------------------------------------------

const styles = StyleSheet.create({
  safe:          { flex: 1, backgroundColor: NAVY },
  outer:         { flex: 1, backgroundColor: '#f8f9fa', alignItems: 'center' },
  scroll:        { width: CARD_WIDTH, backgroundColor: '#fff' },
  scrollContent: { paddingBottom: 56 },

  // ── Brand header ──────────────────────────────────────────────────────────
  brandHeader: {
    backgroundColor: NAVY, height: 72,
    justifyContent: 'center', alignItems: 'center',
  },
  brandLogo: { color: TEAL, fontSize: 30, fontWeight: 'bold', letterSpacing: 2 },

  // ── Segmented control ─────────────────────────────────────────────────────
  segmentRow: {
    flexDirection: 'row', backgroundColor: '#F0F2F5',
    marginHorizontal: 20, marginTop: 20,
    borderRadius: 10, padding: 4,
  },
  segment:            { flex: 1, paddingVertical: 10, alignItems: 'center', borderRadius: 8 },
  segmentActive:      { backgroundColor: '#fff', elevation: 2, shadowColor: '#000', shadowOffset: { width: 0, height: 1 }, shadowOpacity: 0.1, shadowRadius: 2 },
  segmentActiveText:  { fontWeight: 'bold', color: NAVY, fontSize: 15 },
  segmentInactiveText:{ color: '#888', fontSize: 15 },

  // ── Account card list ─────────────────────────────────────────────────────
  list: { paddingHorizontal: 20, paddingTop: 20, gap: 14 },
  card: {
    backgroundColor: '#fff', borderRadius: 14, padding: 18,
    borderWidth: 1, borderColor: '#EBEFF5',
    elevation: 3, shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.06, shadowRadius: 5,
  },
  primaryCard:   { borderColor: '#DAEAFF' },
  secondaryCard: { borderColor: '#D6FFF0', borderWidth: 1.5 },
  cardHeader:    { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12 },
  flagRow:       { flexDirection: 'row', alignItems: 'center', gap: 8 },
  flag:          { fontSize: 22 },
  accountName:   { fontSize: 14, fontWeight: '600', color: '#333', flexShrink: 1 },

  primaryBalance:  { fontSize: 28, fontWeight: 'bold', color: NAVY, lineHeight: 34 },
  primaryCents:    { fontSize: 18, color: '#666' },
  primaryCurrency: { fontSize: 13, color: '#888', fontWeight: '500', marginTop: 2, marginBottom: 14 },

  actionRow:      { flexDirection: 'row', gap: 10 },
  outlineBtn:     { borderWidth: 1.5, borderColor: BLUE, borderRadius: 20, paddingHorizontal: 16, paddingVertical: 7 },
  outlineBtnText: { color: BLUE, fontSize: 13, fontWeight: '600' },
  solidBtn:       { backgroundColor: BLUE, borderRadius: 20, paddingHorizontal: 16, paddingVertical: 7, flexDirection: 'row', alignItems: 'center', gap: 5 },
  solidBtnText:   { color: '#fff', fontSize: 13, fontWeight: '600' },

  secondaryBalance:  { fontSize: 22, fontWeight: 'bold', color: NAVY },
  secondaryCents:    { fontSize: 15, color: '#777' },
  secondaryCurrency: { fontSize: 13, color: '#888', fontWeight: '400' },

  addCard:   { alignItems: 'center', justifyContent: 'center', paddingVertical: 28, borderStyle: 'dashed', borderWidth: 1.5, borderColor: '#ccc', borderRadius: 14 },
  addCircle: { width: 44, height: 44, borderRadius: 22, backgroundColor: '#f0f0f0', alignItems: 'center', justifyContent: 'center', marginBottom: 8 },
  addText:   { color: '#999', fontSize: 14, fontWeight: '500' },

  // ── Error ─────────────────────────────────────────────────────────────────
  errorBox:   { alignItems: 'center', padding: 28, gap: 8, borderWidth: 1, borderColor: '#FFE0E0', borderRadius: 14, backgroundColor: '#FFF8F8' },
  errorTitle: { fontSize: 15, fontWeight: '700', color: '#333', marginTop: 4 },
  errorBody:  { fontSize: 13, color: '#888', textAlign: 'center', lineHeight: 20 },
  retryBtn:   { marginTop: 8, flexDirection: 'row', alignItems: 'center', gap: 6, backgroundColor: BLUE, paddingHorizontal: 20, paddingVertical: 9, borderRadius: 20 },
  retryText:  { color: '#fff', fontWeight: '700', fontSize: 13 },

  // ── Transaction section ───────────────────────────────────────────────────
  txSection: { marginTop: 28, paddingHorizontal: 20, paddingBottom: 8 },
  txHeader: {
    flexDirection: 'row', justifyContent: 'space-between',
    alignItems: 'flex-start', marginBottom: 16,
  },
  txTitle:    { fontSize: 17, fontWeight: '700', color: NAVY },
  txSubtitle: { fontSize: 11, color: '#9CA3AF', marginTop: 2 },
  viewAll:    { fontSize: 13, color: BLUE, fontWeight: '600', paddingTop: 2 },

  // ── Empty transactions ────────────────────────────────────────────────────
  emptyTx:     { alignItems: 'center', paddingVertical: 36, gap: 10 },
  emptyTxText: { fontSize: 14, color: '#bbb', fontWeight: '500' },
});
