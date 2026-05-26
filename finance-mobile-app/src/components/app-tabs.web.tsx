import { Ionicons } from '@expo/vector-icons';
import { Tabs, TabList, TabSlot, TabTrigger, TabTriggerSlotProps } from 'expo-router/ui';
import { Pressable, StyleSheet, useColorScheme, View } from 'react-native';

import { ThemedText } from './themed-text';
import { ThemedView } from './themed-view';
import { Colors, MaxContentWidth, Spacing } from '@/constants/theme';

// ---------------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------------

const BRAND_BLUE = '#208AEF';

type IconName = React.ComponentProps<typeof Ionicons>['name'];

// ---------------------------------------------------------------------------
// Tab definitions
// ---------------------------------------------------------------------------

const TABS = [
  { name: 'home',      href: '/' as const,          label: 'Home',      icon: 'home-outline' as IconName,        activeIcon: 'home' as IconName        },
  { name: 'analytics', href: '/analytics' as const,  label: 'Analytics', icon: 'stats-chart-outline' as IconName, activeIcon: 'stats-chart' as IconName  },
  { name: 'cards',     href: '/cards' as const,       label: 'Cards',     icon: 'wallet-outline' as IconName,      activeIcon: 'wallet' as IconName       },
  { name: 'profile',   href: '/profile' as const,     label: 'Profile',   icon: 'person-outline' as IconName,      activeIcon: 'person' as IconName       },
];

// ---------------------------------------------------------------------------
// Root
// ---------------------------------------------------------------------------

export default function AppTabs() {
  return (
    <Tabs>
      {/* Page content */}
      <TabSlot style={styles.slot} />

      {/* Top navigation bar */}
      <TabList asChild>
        <NavBar>
          {TABS.map(tab => (
            <TabTrigger key={tab.name} name={tab.name} href={tab.href} asChild>
              <NavButton icon={tab.icon} activeIcon={tab.activeIcon}>
                {tab.label}
              </NavButton>
            </TabTrigger>
          ))}
        </NavBar>
      </TabList>
    </Tabs>
  );
}

// ---------------------------------------------------------------------------
// NavBar wrapper
// ---------------------------------------------------------------------------

function NavBar({ children, ...props }: { children: React.ReactNode; [key: string]: any }) {
  const scheme = useColorScheme();
  const colors = Colors[scheme === 'unspecified' || !scheme ? 'light' : scheme];

  return (
    <View
      {...props}
      style={[
        styles.navBarOuter,
        { backgroundColor: colors.background, borderBottomColor: colors.backgroundElement },
      ]}>
      <View style={styles.navBarInner}>
        {/* Brand */}
        <View style={styles.brand}>
          <View style={styles.brandMark}>
            <ThemedText style={styles.brandLetter}>F</ThemedText>
          </View>
          <ThemedText type="small" style={[styles.brandName, { color: BRAND_BLUE }]}>
            FinanceApp
          </ThemedText>
        </View>

        {/* Tabs */}
        <View style={styles.tabRow}>{children}</View>
      </View>
    </View>
  );
}

// ---------------------------------------------------------------------------
// NavButton — forwarded from TabTrigger via asChild
// ---------------------------------------------------------------------------

type NavButtonProps = TabTriggerSlotProps & {
  icon: IconName;
  activeIcon: IconName;
  children: string;
};

function NavButton({ children, isFocused, icon, activeIcon, ...props }: NavButtonProps) {
  const scheme = useColorScheme();
  const colors = Colors[scheme === 'unspecified' || !scheme ? 'light' : scheme];
  const iconColor = isFocused ? BRAND_BLUE : colors.textSecondary;

  return (
    <Pressable
      {...props}
      style={({ pressed }) => [styles.navBtn, pressed && styles.navBtnPressed]}>
      <ThemedView
        type={isFocused ? 'backgroundElement' : 'background'}
        style={[styles.navBtnInner, isFocused && styles.navBtnActive]}>
        <Ionicons
          name={isFocused ? activeIcon : icon}
          size={16}
          color={iconColor}
        />
        <ThemedText
          type="small"
          style={{ color: iconColor, fontWeight: isFocused ? '700' : '500' }}>
          {children}
        </ThemedText>
      </ThemedView>
    </Pressable>
  );
}

// ---------------------------------------------------------------------------
// Styles
// ---------------------------------------------------------------------------

const styles = StyleSheet.create({
  slot: {
    height: '100%',
  },

  // ── Nav bar ─────────────────────────────────────────────────────────────────
  navBarOuter: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    borderBottomWidth: StyleSheet.hairlineWidth,
    paddingHorizontal: Spacing.four,
    paddingVertical: Spacing.two,
    // subtle bottom shadow
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.06,
    shadowRadius: 8,
  },
  navBarInner: {
    flexDirection: 'row',
    alignItems: 'center',
    maxWidth: MaxContentWidth,
    alignSelf: 'center',
    width: '100%',
    gap: Spacing.three,
  },

  // ── Brand ────────────────────────────────────────────────────────────────────
  brand: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: Spacing.one,
    marginRight: 'auto',
  },
  brandMark: {
    width: 28,
    height: 28,
    borderRadius: 8,
    backgroundColor: BRAND_BLUE,
    alignItems: 'center',
    justifyContent: 'center',
  },
  brandLetter: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '700',
    lineHeight: 20,
  },
  brandName: {
    fontWeight: '700',
    letterSpacing: 0.3,
  },

  // ── Tab row / buttons ─────────────────────────────────────────────────────────
  tabRow: {
    flexDirection: 'row',
    gap: Spacing.one,
  },
  navBtn: {
    borderRadius: 10,
    overflow: 'hidden',
  },
  navBtnPressed: {
    opacity: 0.7,
  },
  navBtnInner: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    paddingHorizontal: Spacing.three,
    paddingVertical: Spacing.one + 2,
    borderRadius: 10,
  },
  navBtnActive: {
    // ThemedView handles background; extra visual weight via border
    borderWidth: StyleSheet.hairlineWidth,
    borderColor: 'rgba(32, 138, 239, 0.25)',
  },
});
