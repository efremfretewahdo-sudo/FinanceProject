import { Ionicons } from '@expo/vector-icons';
import { Platform, ScrollView, StyleSheet, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';

// ---------------------------------------------------------------------------
// Mock data
// ---------------------------------------------------------------------------

const MONTHLY_SUMMARY = { income: 4040.0, expenses: 2286.21, savings: 1753.79, savingsPct: 43.4 };

type CategoryStat = {
  label: string;
  amount: number;
  pct: number;
  color: string;
  icon: React.ComponentProps<typeof Ionicons>['name'];
};

const CATEGORIES: CategoryStat[] = [
  { label: 'Food & Dining',  amount: 87.42,  pct: 38, color: '#F59E0B', icon: 'cart-outline'     },
  { label: 'Entertainment',  amount: 15.99,  pct: 7,  color: '#EF4444', icon: 'tv-outline'        },
  { label: 'Transport',      amount: 12.5,   pct: 5,  color: '#8B5CF6', icon: 'car-outline'       },
  { label: 'Shopping',       amount: 134.99, pct: 59, color: '#3B82F6', icon: 'cube-outline'      },
  { label: 'Utilities',      amount: 94.3,   pct: 41, color: '#EC4899', icon: 'flash-outline'     },
];

type MonthBar = { month: string; income: number; expenses: number };

const MONTHLY_BARS: MonthBar[] = [
  { month: 'Jan', income: 3800, expenses: 2100 },
  { month: 'Feb', income: 4100, expenses: 2400 },
  { month: 'Mar', income: 3600, expenses: 1900 },
  { month: 'Apr', income: 4800, expenses: 2900 },
  { month: 'May', income: 4040, expenses: 2286 },
];

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function formatUSD(n: number): string {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(n);
}

const MAX_BAR = Math.max(...MONTHLY_BARS.map(b => b.income));
const BAR_HEIGHT = 120;

// ---------------------------------------------------------------------------
// Sub-components
// ---------------------------------------------------------------------------

function SummaryCard() {
  const theme = useTheme();
  return (
    <ThemedView type="backgroundElement" style={summary.card}>
      <View style={summary.row}>
        <SummaryItem label="Income" value={formatUSD(MONTHLY_SUMMARY.income)} color="#10B981" icon="arrow-down-circle-outline" />
        <View style={[summary.divider, { backgroundColor: theme.backgroundSelected }]} />
        <SummaryItem label="Expenses" value={formatUSD(MONTHLY_SUMMARY.expenses)} color="#EF4444" icon="arrow-up-circle-outline" />
        <View style={[summary.divider, { backgroundColor: theme.backgroundSelected }]} />
        <SummaryItem label="Saved" value={formatUSD(MONTHLY_SUMMARY.savings)} color="#208AEF" icon="wallet-outline" />
      </View>
      <View style={[summary.savingsBar, { backgroundColor: theme.backgroundSelected }]}>
        <View style={[summary.savingsFill, { width: `${MONTHLY_SUMMARY.savingsPct}%` as any }]} />
      </View>
      <ThemedText type="small" themeColor="textSecondary" style={summary.savingsLabel}>
        {MONTHLY_SUMMARY.savingsPct}% of income saved this month
      </ThemedText>
    </ThemedView>
  );
}

function SummaryItem({ label, value, color, icon }: { label: string; value: string; color: string; icon: React.ComponentProps<typeof Ionicons>['name'] }) {
  return (
    <View style={summary.item}>
      <Ionicons name={icon} size={18} color={color} />
      <ThemedText style={[summary.value, { color }]}>{value}</ThemedText>
      <ThemedText type="small" themeColor="textSecondary">{label}</ThemedText>
    </View>
  );
}

const summary = StyleSheet.create({
  card: { borderRadius: 18, padding: Spacing.three, gap: Spacing.two },
  row: { flexDirection: 'row', alignItems: 'center' },
  item: { flex: 1, alignItems: 'center', gap: 4 },
  divider: { width: StyleSheet.hairlineWidth, height: 48 },
  value: { fontSize: 14, fontWeight: '700' },
  savingsBar: { height: 6, borderRadius: 3, overflow: 'hidden' },
  savingsFill: { height: '100%', backgroundColor: '#208AEF', borderRadius: 3 },
  savingsLabel: { textAlign: 'center' },
});

// ---------------------------------------------------------------------------

function BarChart() {
  const theme = useTheme();
  return (
    <ThemedView type="backgroundElement" style={chart.card}>
      <View style={chart.legend}>
        <View style={chart.legendItem}>
          <View style={[chart.legendDot, { backgroundColor: '#10B981' }]} />
          <ThemedText type="small" themeColor="textSecondary">Income</ThemedText>
        </View>
        <View style={chart.legendItem}>
          <View style={[chart.legendDot, { backgroundColor: '#EF4444' }]} />
          <ThemedText type="small" themeColor="textSecondary">Expenses</ThemedText>
        </View>
      </View>
      <View style={chart.bars}>
        {MONTHLY_BARS.map(bar => {
          const incomeH = (bar.income / MAX_BAR) * BAR_HEIGHT;
          const expH = (bar.expenses / MAX_BAR) * BAR_HEIGHT;
          return (
            <View key={bar.month} style={chart.barGroup}>
              <View style={[chart.barWrap, { height: BAR_HEIGHT }]}>
                <View style={[chart.bar, { height: incomeH, backgroundColor: '#10B981' }]} />
                <View style={[chart.bar, { height: expH, backgroundColor: '#EF4444' }]} />
              </View>
              <ThemedText type="small" themeColor="textSecondary" style={chart.barLabel}>{bar.month}</ThemedText>
            </View>
          );
        })}
      </View>
    </ThemedView>
  );
}

const chart = StyleSheet.create({
  card: { borderRadius: 18, padding: Spacing.three, gap: Spacing.two + 4 },
  legend: { flexDirection: 'row', gap: Spacing.three },
  legendItem: { flexDirection: 'row', alignItems: 'center', gap: Spacing.one },
  legendDot: { width: 8, height: 8, borderRadius: 4 },
  bars: { flexDirection: 'row', alignItems: 'flex-end', justifyContent: 'space-between' },
  barGroup: { alignItems: 'center', gap: 6, flex: 1 },
  barWrap: { flexDirection: 'row', alignItems: 'flex-end', gap: 3 },
  bar: { width: 12, borderRadius: 4 },
  barLabel: { fontSize: 11 },
});

// ---------------------------------------------------------------------------

function CategoryRow({ item }: { item: CategoryStat }) {
  const theme = useTheme();
  return (
    <View style={[cat.row, { borderBottomColor: theme.backgroundSelected }]}>
      <View style={[cat.icon, { backgroundColor: item.color + '18' }]}>
        <Ionicons name={item.icon} size={16} color={item.color} />
      </View>
      <View style={cat.info}>
        <View style={cat.labelRow}>
          <ThemedText style={cat.label}>{item.label}</ThemedText>
          <ThemedText style={cat.amount}>{formatUSD(item.amount)}</ThemedText>
        </View>
        <View style={[cat.track, { backgroundColor: theme.backgroundSelected }]}>
          <View style={[cat.fill, { width: `${item.pct}%` as any, backgroundColor: item.color }]} />
        </View>
      </View>
    </View>
  );
}

const cat = StyleSheet.create({
  row: { flexDirection: 'row', alignItems: 'center', gap: Spacing.two + 4, paddingVertical: Spacing.two + 4, borderBottomWidth: StyleSheet.hairlineWidth },
  icon: { width: 38, height: 38, borderRadius: 11, alignItems: 'center', justifyContent: 'center' },
  info: { flex: 1, gap: 6 },
  labelRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  label: { fontSize: 13, fontWeight: '600' },
  amount: { fontSize: 13, fontWeight: '700' },
  track: { height: 4, borderRadius: 2, overflow: 'hidden' },
  fill: { height: '100%', borderRadius: 2 },
});

// ---------------------------------------------------------------------------
// Screen
// ---------------------------------------------------------------------------

export default function AnalyticsScreen() {
  const webTopPad = Platform.OS === 'web' ? 64 : 0;

  return (
    <ThemedView style={styles.screen}>
      <SafeAreaView edges={['top']} style={styles.safe}>
        <ScrollView
          contentContainerStyle={[
            styles.scroll,
            { paddingTop: webTopPad + Spacing.three, paddingBottom: Spacing.six },
          ]}
          showsVerticalScrollIndicator={false}>

          <View style={styles.pageHeader}>
            <ThemedText style={styles.pageTitle}>Analytics</ThemedText>
            <ThemedText type="small" themeColor="textSecondary">May 2026</ThemedText>
          </View>

          <View style={styles.section}>
            <ThemedText style={styles.sectionTitle}>Monthly Overview</ThemedText>
            <SummaryCard />
          </View>

          <View style={styles.section}>
            <ThemedText style={styles.sectionTitle}>Income vs Expenses</ThemedText>
            <BarChart />
          </View>

          <View style={styles.section}>
            <ThemedText style={styles.sectionTitle}>Spending by Category</ThemedText>
            <ThemedView type="backgroundElement" style={styles.catCard}>
              {CATEGORIES.map(item => (
                <CategoryRow key={item.label} item={item} />
              ))}
            </ThemedView>
          </View>

        </ScrollView>
      </SafeAreaView>
    </ThemedView>
  );
}

const styles = StyleSheet.create({
  screen: { flex: 1 },
  safe: { flex: 1 },
  scroll: {
    paddingHorizontal: Spacing.four,
    gap: Spacing.four,
    maxWidth: MaxContentWidth,
    alignSelf: 'center',
    width: '100%',
  },
  pageHeader: { gap: 2 },
  pageTitle: { fontSize: 28, fontWeight: '700', lineHeight: 34 },
  section: { gap: Spacing.two },
  sectionTitle: { fontSize: 16, fontWeight: '700' },
  catCard: { borderRadius: 18, overflow: 'hidden', paddingHorizontal: Spacing.three },
});
