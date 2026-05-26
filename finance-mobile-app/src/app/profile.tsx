import { Ionicons } from '@expo/vector-icons';
import { Platform, ScrollView, StyleSheet, TouchableOpacity, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { MaxContentWidth, Spacing } from '@/constants/theme';
import { useAuth } from '@/contexts/auth-context';
import { useTheme } from '@/hooks/use-theme';

// ---------------------------------------------------------------------------
// Types
// ---------------------------------------------------------------------------

type SettingsItem = {
  label: string;
  icon: React.ComponentProps<typeof Ionicons>['name'];
  color: string;
  value?: string;
  danger?: boolean;
};

type SettingsSection = {
  title: string;
  items: SettingsItem[];
};

// ---------------------------------------------------------------------------
// Settings data
// ---------------------------------------------------------------------------

const SETTINGS: SettingsSection[] = [
  {
    title: 'Account',
    items: [
      { label: 'Personal Information', icon: 'person-outline',         color: '#208AEF' },
      { label: 'Linked Bank Accounts', icon: 'business-outline',       color: '#10B981' },
      { label: 'Notifications',        icon: 'notifications-outline',  color: '#F59E0B' },
    ],
  },
  {
    title: 'Security',
    items: [
      { label: 'Biometric Login',      icon: 'finger-print-outline',   color: '#8B5CF6' },
      { label: 'Change PIN',           icon: 'keypad-outline',         color: '#3B82F6' },
      { label: 'Two-Factor Auth',      icon: 'shield-checkmark-outline', color: '#10B981', value: 'On' },
    ],
  },
  {
    title: 'Preferences',
    items: [
      { label: 'Currency',             icon: 'cash-outline',           color: '#F59E0B', value: 'USD' },
      { label: 'Language',             icon: 'language-outline',       color: '#EC4899', value: 'English' },
      { label: 'Appearance',           icon: 'color-palette-outline',  color: '#8B5CF6' },
    ],
  },
  {
    title: 'Support',
    items: [
      { label: 'Help Center',          icon: 'help-circle-outline',    color: '#3B82F6' },
      { label: 'Privacy Policy',       icon: 'lock-closed-outline',    color: '#6B7280' },
      { label: 'Terms of Service',     icon: 'document-text-outline',  color: '#6B7280' },
    ],
  },
];

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function initials(name: string | null, email: string | null): string {
  if (name) {
    const parts = name.trim().split(/\s+/);
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return parts[0][0].toUpperCase();
  }
  if (email) return email.charAt(0).toUpperCase();
  return 'U';
}

function displayName(name: string | null, email: string | null): string {
  if (name) return name;
  if (!email) return 'User';
  return email.split('@')[0].replace(/[._-]+/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
}

const BRAND_BLUE = '#208AEF';

// ---------------------------------------------------------------------------
// Sub-components
// ---------------------------------------------------------------------------

function ProfileHeader() {
  const { userEmail, userName } = useAuth();
  const name = displayName(userName, userEmail);
  const abbr = initials(userName, userEmail);

  return (
    <View style={header.root}>
      <View style={header.avatarWrap}>
        <View style={[header.avatar, { backgroundColor: BRAND_BLUE }]}>
          <ThemedText style={header.avatarText}>{abbr}</ThemedText>
        </View>
        <TouchableOpacity style={header.editBadge} activeOpacity={0.7}>
          <Ionicons name="camera-outline" size={13} color="#fff" />
        </TouchableOpacity>
      </View>
      <ThemedText style={header.name}>{name}</ThemedText>
      {userEmail ? (
        <ThemedText type="small" themeColor="textSecondary">{userEmail}</ThemedText>
      ) : null}
      <View style={header.badgeRow}>
        <View style={header.badge}>
          <Ionicons name="shield-checkmark-outline" size={12} color={BRAND_BLUE} />
          <ThemedText style={header.badgeText}>Verified Account</ThemedText>
        </View>
      </View>
    </View>
  );
}

const header = StyleSheet.create({
  root: { alignItems: 'center', gap: Spacing.one + 2 },
  avatarWrap: { position: 'relative', marginBottom: 4 },
  avatar: {
    width: 80,
    height: 80,
    borderRadius: 24,
    alignItems: 'center',
    justifyContent: 'center',
  },
  avatarText: { color: '#fff', fontSize: 30, fontWeight: '700', lineHeight: 36 },
  editBadge: {
    position: 'absolute',
    bottom: -4,
    right: -4,
    width: 26,
    height: 26,
    borderRadius: 13,
    backgroundColor: BRAND_BLUE,
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 2,
    borderColor: '#fff',
  },
  name: { fontSize: 22, fontWeight: '700', lineHeight: 28 },
  badgeRow: { flexDirection: 'row', gap: Spacing.one },
  badge: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    backgroundColor: BRAND_BLUE + '15',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 20,
  },
  badgeText: { color: BRAND_BLUE, fontSize: 11, fontWeight: '700' },
});

// ---------------------------------------------------------------------------

function SettingsRow({ item, isLast }: { item: SettingsItem; isLast: boolean }) {
  const theme = useTheme();
  return (
    <TouchableOpacity
      style={[row.root, !isLast && { borderBottomWidth: StyleSheet.hairlineWidth, borderBottomColor: theme.backgroundSelected }]}
      activeOpacity={0.65}>
      <View style={[row.icon, { backgroundColor: item.color + '18' }]}>
        <Ionicons name={item.icon} size={17} color={item.danger ? '#EF4444' : item.color} />
      </View>
      <ThemedText style={[row.label, item.danger && { color: '#EF4444' }]}>{item.label}</ThemedText>
      <View style={row.right}>
        {item.value ? (
          <ThemedText type="small" themeColor="textSecondary" style={row.value}>{item.value}</ThemedText>
        ) : null}
        <Ionicons name="chevron-forward-outline" size={14} color={theme.textSecondary} />
      </View>
    </TouchableOpacity>
  );
}

const row = StyleSheet.create({
  root: { flexDirection: 'row', alignItems: 'center', gap: Spacing.three, paddingVertical: Spacing.two + 4 },
  icon: { width: 36, height: 36, borderRadius: 10, alignItems: 'center', justifyContent: 'center' },
  label: { flex: 1, fontSize: 14, fontWeight: '500' },
  right: { flexDirection: 'row', alignItems: 'center', gap: 6 },
  value: { fontSize: 12 },
});

// ---------------------------------------------------------------------------

function SignOutButton() {
  const { logout } = useAuth();
  return (
    <TouchableOpacity style={signout.btn} activeOpacity={0.75} onPress={logout}>
      <Ionicons name="log-out-outline" size={18} color="#EF4444" />
      <ThemedText style={signout.label}>Sign Out</ThemedText>
    </TouchableOpacity>
  );
}

const signout = StyleSheet.create({
  btn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: Spacing.two,
    paddingVertical: Spacing.three,
    borderRadius: 16,
    borderWidth: StyleSheet.hairlineWidth,
    borderColor: '#EF4444',
  },
  label: { color: '#EF4444', fontSize: 15, fontWeight: '700' },
});

// ---------------------------------------------------------------------------
// Screen
// ---------------------------------------------------------------------------

export default function ProfileScreen() {
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

          <ProfileHeader />

          {SETTINGS.map(section => (
            <View key={section.title} style={styles.section}>
              <ThemedText style={styles.sectionTitle}>{section.title}</ThemedText>
              <ThemedView type="backgroundElement" style={styles.settingsCard}>
                {section.items.map((item, idx) => (
                  <SettingsRow key={item.label} item={item} isLast={idx === section.items.length - 1} />
                ))}
              </ThemedView>
            </View>
          ))}

          <SignOutButton />

          <ThemedText type="small" themeColor="textSecondary" style={styles.version}>
            FinanceApp v1.0.0
          </ThemedText>

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
  section: { gap: Spacing.two },
  sectionTitle: { fontSize: 13, fontWeight: '700', textTransform: 'uppercase', letterSpacing: 0.8, opacity: 0.5 },
  settingsCard: { borderRadius: 18, paddingHorizontal: Spacing.three, overflow: 'hidden' },
  version: { textAlign: 'center', paddingBottom: Spacing.two },
});
