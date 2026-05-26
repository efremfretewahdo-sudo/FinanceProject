import { Ionicons } from '@expo/vector-icons';
import { Slot, usePathname, useRouter } from 'expo-router';
import { StyleSheet, TouchableOpacity, View } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';

import { useTheme } from '@/hooks/use-theme';

// ---------------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------------

const BRAND_BLUE = '#208AEF';
const TAB_HEIGHT = 56; // icon pill + label

type IconName = React.ComponentProps<typeof Ionicons>['name'];

type TabDef = {
  href: string;
  label: string;
  icon: IconName;
  activeIcon: IconName;
};

const TABS: TabDef[] = [
  { href: '/',          label: 'Home',      icon: 'home-outline',         activeIcon: 'home'         },
  { href: '/analytics', label: 'Analytics', icon: 'stats-chart-outline',  activeIcon: 'stats-chart'  },
  { href: '/cards',     label: 'Cards',     icon: 'wallet-outline',       activeIcon: 'wallet'       },
  { href: '/ai-chat',   label: 'AI',        icon: 'hardware-chip-outline', activeIcon: 'hardware-chip' },
  { href: '/profile',   label: 'Profile',   icon: 'person-outline',       activeIcon: 'person'       },
];

// ---------------------------------------------------------------------------
// Component
// ---------------------------------------------------------------------------

export default function AppTabs() {
  const theme = useTheme();
  const router = useRouter();
  const pathname = usePathname();
  const insets = useSafeAreaInsets();

  // Home route can appear as '/' or '/index' depending on the navigation state
  function isActive(tab: TabDef): boolean {
    if (tab.href === '/') {
      return pathname === '/' || pathname === '/index' || pathname === '';
    }
    return pathname.startsWith(tab.href);
  }

  const safeBottom = Math.max(insets.bottom, 8);

  return (
    <View style={[styles.root, { backgroundColor: theme.background }]}>
      {/* ── Current route renders here ── */}
      <View style={styles.content}>
        <Slot />
      </View>

      {/* ── Bottom navigation bar ── */}
      <View
        style={[
          styles.bar,
          {
            backgroundColor: theme.background,
            borderTopColor: theme.backgroundSelected,
            paddingBottom: safeBottom,
            height: TAB_HEIGHT + safeBottom,
          },
        ]}>
        {TABS.map(tab => {
          const active = isActive(tab);
          return (
            <TouchableOpacity
              key={tab.href}
              style={styles.tabBtn}
              onPress={() => router.push(tab.href as any)}
              activeOpacity={0.75}>
              {/* Pill highlight behind active icon */}
              <View style={[styles.pill, active && { backgroundColor: BRAND_BLUE + '1A' }]}>
                <Ionicons
                  name={active ? tab.activeIcon : tab.icon}
                  size={22}
                  color={active ? BRAND_BLUE : theme.textSecondary}
                />
              </View>
              <TabLabel active={active} color={active ? BRAND_BLUE : theme.textSecondary}>
                {tab.label}
              </TabLabel>
            </TouchableOpacity>
          );
        })}
      </View>
    </View>
  );
}

// ---------------------------------------------------------------------------
// Tab label — inline to avoid importing ThemedText (avoids a circular dep issue)
// ---------------------------------------------------------------------------

import { Text } from 'react-native';

function TabLabel({
  children,
  active,
  color,
}: {
  children: string;
  active: boolean;
  color: string;
}) {
  return (
    <Text style={[styles.label, { color, fontWeight: active ? '700' : '500' }]}>
      {children}
    </Text>
  );
}

// ---------------------------------------------------------------------------
// Styles
// ---------------------------------------------------------------------------

const styles = StyleSheet.create({
  root: {
    flex: 1,
  },
  content: {
    flex: 1,
  },
  bar: {
    flexDirection: 'row',
    borderTopWidth: StyleSheet.hairlineWidth,
    paddingTop: 8,
    // Subtle top shadow
    shadowColor: '#000',
    shadowOffset: { width: 0, height: -3 },
    shadowOpacity: 0.06,
    shadowRadius: 8,
    elevation: 16,
  },
  tabBtn: {
    flex: 1,
    alignItems: 'center',
    gap: 2,
  },
  pill: {
    width: 52,
    height: 32,
    borderRadius: 16,
    alignItems: 'center',
    justifyContent: 'center',
  },
  label: {
    fontSize: 10,
    letterSpacing: 0.2,
  },
});
