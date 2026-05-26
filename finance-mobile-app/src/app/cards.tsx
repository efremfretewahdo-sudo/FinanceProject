import { Ionicons } from '@expo/vector-icons';
import { Platform, ScrollView, StyleSheet, TouchableOpacity, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';

// ---------------------------------------------------------------------------
// Mock data
// ---------------------------------------------------------------------------

type Card = {
  id: number;
  label: string;
  last4: string;
  expiry: string;
  balance: number;
  color: [string, string];
  network: 'visa' | 'mastercard';
  frozen: boolean;
};

const CARDS: Card[] = [
  {
    id: 1,
    label: 'Main Account',
    last4: '4291',
    expiry: '08/28',
    balance: 24563.8,
    color: ['#208AEF', '#1261B0'],
    network: 'visa',
    frozen: false,
  },
  {
    id: 2,
    label: 'Savings',
    last4: '7734',
    expiry: '03/27',
    balance: 8120.5,
    color: ['#8B5CF6', '#5B21B6'],
    network: 'mastercard',
    frozen: false,
  },
  {
    id: 3,
    label: 'Business',
    last4: '9910',
    expiry: '11/26',
    balance: 3400.0,
    color: ['#374151', '#111827'],
    network: 'visa',
    frozen: true,
  },
];

type QuickCardAction = {
  label: string;
  icon: React.ComponentProps<typeof Ionicons>['name'];
  color: string;
};

const CARD_ACTIONS: QuickCardAction[] = [
  { label: 'Freeze',   icon: 'snow-outline',       color: '#3B82F6' },
  { label: 'Limit',    icon: 'speedometer-outline', color: '#F59E0B' },
  { label: 'Details',  icon: 'eye-outline',         color: '#8B5CF6' },
  { label: 'Block',    icon: 'ban-outline',         color: '#EF4444' },
];

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function formatUSD(n: number): string {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(n);
}

function maskNumber(last4: string): string {
  return `•••• •••• •••• ${last4}`;
}

// ---------------------------------------------------------------------------
// Sub-components
// ---------------------------------------------------------------------------

function CardTile({ card }: { card: Card }) {
  const [start, end] = card.color;
  return (
    <View style={[tile.root, { backgroundColor: start }]}>
      {/* Gradient overlay simulation */}
      <View style={[tile.overlay, { backgroundColor: end }]} />

      {/* Frozen badge */}
      {card.frozen && (
        <View style={tile.frozenBadge}>
          <Ionicons name="snow-outline" size={11} color="#fff" />
          <ThemedText style={tile.frozenText}>Frozen</ThemedText>
        </View>
      )}

      {/* Top row */}
      <View style={tile.topRow}>
        <ThemedText style={tile.cardLabel}>{card.label}</ThemedText>
        <Ionicons
          name={card.network === 'visa' ? 'card-outline' : 'card-outline'}
          size={20}
          color="rgba(255,255,255,0.8)"
        />
      </View>

      {/* Chip */}
      <View style={tile.chip}>
        <View style={tile.chipInner} />
      </View>

      {/* Card number */}
      <ThemedText style={tile.cardNumber}>{maskNumber(card.last4)}</ThemedText>

      {/* Bottom row */}
      <View style={tile.bottomRow}>
        <View>
          <ThemedText style={tile.metaLabel}>Balance</ThemedText>
          <ThemedText style={tile.metaValue}>{formatUSD(card.balance)}</ThemedText>
        </View>
        <View style={{ alignItems: 'flex-end' }}>
          <ThemedText style={tile.metaLabel}>Expires</ThemedText>
          <ThemedText style={tile.metaValue}>{card.expiry}</ThemedText>
        </View>
      </View>
    </View>
  );
}

const tile = StyleSheet.create({
  root: {
    borderRadius: 22,
    padding: Spacing.three,
    gap: Spacing.two,
    overflow: 'hidden',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.2,
    shadowRadius: 14,
    elevation: 8,
    minHeight: 190,
    justifyContent: 'space-between',
  },
  overlay: {
    ...StyleSheet.absoluteFill,
    opacity: 0.45,
    borderRadius: 22,
  },
  frozenBadge: {
    position: 'absolute',
    top: 14,
    right: 14,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    backgroundColor: 'rgba(59, 130, 246, 0.6)',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 20,
  },
  frozenText: { color: '#fff', fontSize: 10, fontWeight: '700' },
  topRow: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between' },
  cardLabel: { color: 'rgba(255,255,255,0.85)', fontSize: 13, fontWeight: '600' },
  chip: {
    width: 36,
    height: 26,
    borderRadius: 5,
    backgroundColor: 'rgba(255,215,0,0.7)',
    padding: 3,
    justifyContent: 'center',
  },
  chipInner: {
    flex: 1,
    borderRadius: 3,
    borderWidth: 1.5,
    borderColor: 'rgba(180,140,0,0.5)',
  },
  cardNumber: {
    color: '#fff',
    fontSize: 15,
    fontWeight: '600',
    letterSpacing: 1.5,
    fontVariant: ['tabular-nums'],
  },
  bottomRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'flex-end' },
  metaLabel: { color: 'rgba(255,255,255,0.6)', fontSize: 10, fontWeight: '500' },
  metaValue: { color: '#fff', fontSize: 14, fontWeight: '700' },
});

// ---------------------------------------------------------------------------

function CardActions() {
  const theme = useTheme();
  return (
    <ThemedView type="backgroundElement" style={actions.card}>
      {CARD_ACTIONS.map((action, i) => (
        <TouchableOpacity key={action.label} style={[actions.item, i < CARD_ACTIONS.length - 1 && { borderRightWidth: StyleSheet.hairlineWidth, borderRightColor: theme.backgroundSelected }]} activeOpacity={0.7}>
          <View style={[actions.circle, { backgroundColor: action.color + '18' }]}>
            <Ionicons name={action.icon} size={18} color={action.color} />
          </View>
          <ThemedText type="small" themeColor="textSecondary" style={actions.label}>{action.label}</ThemedText>
        </TouchableOpacity>
      ))}
    </ThemedView>
  );
}

const actions = StyleSheet.create({
  card: { borderRadius: 18, flexDirection: 'row', overflow: 'hidden' },
  item: { flex: 1, alignItems: 'center', gap: Spacing.one, paddingVertical: Spacing.two + 4 },
  circle: { width: 44, height: 44, borderRadius: 22, alignItems: 'center', justifyContent: 'center' },
  label: { fontSize: 11 },
});

// ---------------------------------------------------------------------------

function AddCardButton() {
  const theme = useTheme();
  return (
    <TouchableOpacity activeOpacity={0.75}>
      <ThemedView
        type="backgroundElement"
        style={[addBtn.root, { borderColor: theme.backgroundSelected }]}>
        <View style={addBtn.iconWrap}>
          <Ionicons name="add-outline" size={22} color="#208AEF" />
        </View>
        <View style={addBtn.text}>
          <ThemedText style={addBtn.title}>Add New Card</ThemedText>
          <ThemedText type="small" themeColor="textSecondary">Link a debit or credit card</ThemedText>
        </View>
        <Ionicons name="chevron-forward-outline" size={16} color={theme.textSecondary} />
      </ThemedView>
    </TouchableOpacity>
  );
}

const addBtn = StyleSheet.create({
  root: { borderRadius: 16, flexDirection: 'row', alignItems: 'center', gap: Spacing.three, padding: Spacing.three, borderWidth: StyleSheet.hairlineWidth },
  iconWrap: { width: 44, height: 44, borderRadius: 22, backgroundColor: '#208AEF18', alignItems: 'center', justifyContent: 'center' },
  text: { flex: 1, gap: 2 },
  title: { fontSize: 14, fontWeight: '600' },
});

// ---------------------------------------------------------------------------
// Screen
// ---------------------------------------------------------------------------

export default function CardsScreen() {
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
            <ThemedText style={styles.pageTitle}>My Cards</ThemedText>
            <ThemedText type="small" themeColor="textSecondary">{CARDS.length} cards</ThemedText>
          </View>

          {CARDS.map(card => (
            <View key={card.id} style={styles.cardWrap}>
              <CardTile card={card} />
            </View>
          ))}

          <View style={styles.section}>
            <ThemedText style={styles.sectionTitle}>Card Controls</ThemedText>
            <CardActions />
          </View>

          <View style={styles.section}>
            <ThemedText style={styles.sectionTitle}>Manage</ThemedText>
            <AddCardButton />
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
  cardWrap: {},
  section: { gap: Spacing.two },
  sectionTitle: { fontSize: 16, fontWeight: '700' },
});
