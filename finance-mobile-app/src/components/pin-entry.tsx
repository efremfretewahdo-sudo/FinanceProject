import { StyleSheet, TouchableOpacity, View } from 'react-native';

import { Colors, Spacing } from '@/constants/theme';
import { useTheme } from '@/hooks/use-theme';
import { ThemedText } from './themed-text';

const PIN_LENGTH = 6;

// Keypad layout: 3-wide grid. Empty string = invisible spacer key.
const KEYS = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '', '0', '⌫'] as const;

type Props = {
  title: string;
  subtitle?: string;
  error?: string;
  onComplete: (pin: string) => void;
  /** Controlled value — pass '' to reset after a failed attempt */
  value: string;
  onChange: (next: string) => void;
};

export function PinEntry({ title, subtitle, error, onComplete, value, onChange }: Props) {
  const theme = useTheme();

  function handleKey(key: string) {
    if (key === '⌫') {
      onChange(value.slice(0, -1));
      return;
    }
    if (key === '' || value.length >= PIN_LENGTH) return;

    const next = value + key;
    onChange(next);

    if (next.length === PIN_LENGTH) {
      // Small delay so the last dot fills before the callback fires
      setTimeout(() => onComplete(next), 80);
    }
  }

  return (
    <View style={styles.root}>
      <ThemedText type="subtitle" style={styles.title}>
        {title}
      </ThemedText>
      {subtitle ? (
        <ThemedText type="small" themeColor="textSecondary" style={styles.center}>
          {subtitle}
        </ThemedText>
      ) : null}

      {/* Dot indicators */}
      <View style={styles.dots}>
        {Array.from({ length: PIN_LENGTH }).map((_, i) => (
          <View
            key={i}
            style={[
              styles.dot,
              { borderColor: theme.text },
              i < value.length && { backgroundColor: theme.text },
            ]}
          />
        ))}
      </View>

      {error ? (
        <ThemedText style={styles.error}>{error}</ThemedText>
      ) : (
        // Reserve space so layout doesn't shift
        <View style={styles.errorPlaceholder} />
      )}

      {/* Numeric keypad */}
      <View style={styles.pad}>
        {KEYS.map((key, i) => (
          <TouchableOpacity
            key={i}
            style={[
              styles.key,
              { backgroundColor: key === '' ? 'transparent' : theme.backgroundElement },
            ]}
            onPress={() => handleKey(key)}
            disabled={key === ''}
            activeOpacity={0.65}>
            <ThemedText style={styles.keyLabel}>{key}</ThemedText>
          </TouchableOpacity>
        ))}
      </View>
    </View>
  );
}

const KEY_SIZE = 88;
const KEY_GAP = Spacing.two;

const styles = StyleSheet.create({
  root: {
    alignItems: 'center',
    gap: Spacing.three,
  },
  title: {
    textAlign: 'center',
  },
  center: {
    textAlign: 'center',
  },
  dots: {
    flexDirection: 'row',
    gap: Spacing.three,
    marginVertical: Spacing.two,
  },
  dot: {
    width: 16,
    height: 16,
    borderRadius: 8,
    borderWidth: 2,
  },
  error: {
    color: '#FF3B30',
    fontSize: 14,
    textAlign: 'center',
  },
  errorPlaceholder: {
    height: 20,
  },
  pad: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    width: KEY_SIZE * 3 + KEY_GAP * 2,
    gap: KEY_GAP,
  },
  key: {
    width: KEY_SIZE,
    height: 68,
    borderRadius: 14,
    alignItems: 'center',
    justifyContent: 'center',
  },
  keyLabel: {
    fontSize: 26,
    fontWeight: '500',
    lineHeight: 32,
  },
});
