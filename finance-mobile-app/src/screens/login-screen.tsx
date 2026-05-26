/**
 * LoginScreen
 *
 * Handles four sequential views driven by AuthContext status:
 *
 *  1. credentials  — Email + password form (first launch or explicit sign-out)
 *  2. pin_setup    — Create a 6-digit backup PIN after a successful credential login
 *  3. pin_confirm  — Confirm the chosen PIN to prevent typos
 *  4. biometric    — Biometric prompt for returning users; falls back to PIN entry
 *  5. pin_auth     — PIN authentication when biometric is unavailable or denied
 */

import { useCallback, useEffect, useRef, useState } from 'react';
import {
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
  Pressable,
  StyleSheet,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import { PinEntry } from '@/components/pin-entry';
import { ThemedText } from '@/components/themed-text';
import { ThemedView } from '@/components/themed-view';
import { Colors, Spacing } from '@/constants/theme';
import { useAuth } from '@/contexts/auth-context';
import { useTheme } from '@/hooks/use-theme';

// ---------------------------------------------------------------------------
// Types
// ---------------------------------------------------------------------------

type LocalView = 'credentials' | 'biometric' | 'pin_auth' | 'pin_setup' | 'pin_confirm';

// ---------------------------------------------------------------------------
// Main component
// ---------------------------------------------------------------------------

export function LoginScreen() {
  const { status, biometric, loginWithCredentials, setupPin, authenticateWithBiometric, authenticateWithPin } =
    useAuth();

  const theme = useTheme();

  // Derive the initial local view from auth context status.
  // On web, biometric is never available (isAvailable: false from the web stub),
  // so fall back to pin_auth rather than showing the biometric prompt.
  const initialView = (): LocalView => {
    if (status === 'biometric_prompt') {
      return biometric?.isAvailable ? 'biometric' : 'pin_auth';
    }
    if (status === 'pin_setup') return 'pin_setup';
    return 'credentials';
  };

  const [view, setView] = useState<LocalView>(initialView);

  // PIN collection state
  const [pin, setPin] = useState('');
  const [confirmPin, setConfirmPin] = useState('');
  const [pinForSetup, setPinForSetup] = useState('');

  // Credential form state
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);

  // Feedback state
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState('');

  const passwordRef = useRef<TextInput>(null);

  // Sync when context status changes (e.g. after credential login → pin_setup)
  useEffect(() => {
    if (status === 'pin_setup' && view !== 'pin_setup' && view !== 'pin_confirm') {
      setView('pin_setup');
    }
    if (status === 'biometric_prompt' && view === 'credentials') {
      // Only advance to the biometric view when hardware is actually present
      setView(biometric?.isAvailable ? 'biometric' : 'pin_auth');
    }
  }, [status]);

  // Auto-trigger biometric when the biometric view mounts
  useEffect(() => {
    if (view === 'biometric' && biometric?.isAvailable) {
      triggerBiometric();
    }
  }, [view]);

  // ---------------------------------------------------------------------------
  // Handlers
  // ---------------------------------------------------------------------------

  async function triggerBiometric() {
    setError('');
    const success = await authenticateWithBiometric('Verify your identity to access FinanceApp');
    if (!success) {
      // Leave the user on the biometric view; they can tap the button or switch to PIN
    }
  }

  async function handleCredentialLogin() {
    if (!email.trim() || !password) {
      setError('Please enter your email and password.');
      return;
    }
    setBusy(true);
    setError('');
    try {
      await loginWithCredentials(email.trim(), password);
      // Status will transition to pin_setup — the useEffect above handles the view switch
    } catch {
      setError('Sign-in failed. Check your credentials and try again.');
    } finally {
      setBusy(false);
    }
  }

  function handlePinSetupComplete(entered: string) {
    setPinForSetup(entered);
    setPin('');
    setView('pin_confirm');
  }

  async function handlePinConfirmComplete(entered: string) {
    if (entered !== pinForSetup) {
      setConfirmPin('');
      setError('PINs do not match. Please try again.');
      return;
    }
    setError('');
    setBusy(true);
    try {
      await setupPin(entered);
      // AuthContext dispatches PIN_SAVED → status becomes 'authenticated'
    } finally {
      setBusy(false);
    }
  }

  async function handlePinAuthComplete(entered: string) {
    setError('');
    const valid = await authenticateWithPin(entered);
    if (!valid) {
      setPin('');
      setError('Incorrect PIN. Please try again.');
    }
  }

  // ---------------------------------------------------------------------------
  // Render helpers
  // ---------------------------------------------------------------------------

  const renderCredentials = useCallback(
    () => (
      <KeyboardAvoidingView
        style={styles.form}
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}>
        <ThemedText type="subtitle" style={styles.formTitle}>
          Sign in
        </ThemedText>
        <ThemedText type="small" themeColor="textSecondary" style={styles.formSubtitle}>
          Enter your email and password to continue.
        </ThemedText>

        {/* Email */}
        <View style={styles.fieldWrapper}>
          <ThemedText type="small" style={styles.label}>
            Email
          </ThemedText>
          <TextInput
            style={[
              styles.input,
              { color: theme.text, borderColor: theme.backgroundSelected, backgroundColor: theme.backgroundElement },
            ]}
            placeholder="you@example.com"
            placeholderTextColor={theme.textSecondary}
            value={email}
            onChangeText={t => { setEmail(t); setError(''); }}
            keyboardType="email-address"
            autoCapitalize="none"
            autoCorrect={false}
            returnKeyType="next"
            onSubmitEditing={() => passwordRef.current?.focus()}
          />
        </View>

        {/* Password */}
        <View style={styles.fieldWrapper}>
          <ThemedText type="small" style={styles.label}>
            Password
          </ThemedText>
          <View style={styles.passwordRow}>
            <TextInput
              ref={passwordRef}
              style={[
                styles.input,
                styles.passwordInput,
                { color: theme.text, borderColor: theme.backgroundSelected, backgroundColor: theme.backgroundElement },
              ]}
              placeholder="••••••••"
              placeholderTextColor={theme.textSecondary}
              value={password}
              onChangeText={t => { setPassword(t); setError(''); }}
              secureTextEntry={!showPassword}
              returnKeyType="done"
              onSubmitEditing={handleCredentialLogin}
            />
            <TouchableOpacity
              style={styles.eyeBtn}
              onPress={() => setShowPassword(v => !v)}
              hitSlop={8}>
              <ThemedText type="small" themeColor="textSecondary">
                {showPassword ? 'Hide' : 'Show'}
              </ThemedText>
            </TouchableOpacity>
          </View>
        </View>

        {error ? <ThemedText style={styles.errorText}>{error}</ThemedText> : null}

        <TouchableOpacity
          style={[styles.primaryBtn, busy && styles.primaryBtnDisabled]}
          onPress={handleCredentialLogin}
          disabled={busy}
          activeOpacity={0.8}>
          {busy ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <ThemedText style={styles.primaryBtnLabel}>Sign In</ThemedText>
          )}
        </TouchableOpacity>
      </KeyboardAvoidingView>
    ),
    [email, password, showPassword, busy, error, theme]
  );

  const renderBiometric = useCallback(
    () => (
      <View style={styles.biometricContainer}>
        {/* Icon — platform-appropriate symbol */}
        <View style={[styles.biometricIcon, { backgroundColor: theme.backgroundElement }]}>
          <ThemedText style={styles.biometricEmoji}>
            {biometric?.label === 'Face ID' ? '🪪' : '👆'}
          </ThemedText>
        </View>

        <ThemedText type="subtitle" style={styles.center}>
          Welcome back
        </ThemedText>
        <ThemedText type="small" themeColor="textSecondary" style={styles.center}>
          Use {biometric?.label ?? 'biometrics'} to access your account securely.
        </ThemedText>

        {error ? <ThemedText style={styles.errorText}>{error}</ThemedText> : null}

        {/* Primary biometric button */}
        {biometric?.isAvailable ? (
          <TouchableOpacity
            style={[styles.primaryBtn, busy && styles.primaryBtnDisabled]}
            onPress={triggerBiometric}
            disabled={busy}
            activeOpacity={0.8}>
            {busy ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <ThemedText style={styles.primaryBtnLabel}>
                Use {biometric.label}
              </ThemedText>
            )}
          </TouchableOpacity>
        ) : (
          <ThemedText type="small" themeColor="textSecondary" style={styles.center}>
            Biometric authentication is not available on this device.
          </ThemedText>
        )}

        {/* Divider */}
        <View style={styles.dividerRow}>
          <View style={[styles.dividerLine, { backgroundColor: theme.backgroundSelected }]} />
          <ThemedText type="small" themeColor="textSecondary" style={styles.dividerLabel}>
            or
          </ThemedText>
          <View style={[styles.dividerLine, { backgroundColor: theme.backgroundSelected }]} />
        </View>

        {/* PIN fallback */}
        <TouchableOpacity onPress={() => { setError(''); setPin(''); setView('pin_auth'); }}>
          <ThemedText type="linkPrimary" style={styles.center}>
            Use PIN instead
          </ThemedText>
        </TouchableOpacity>

        {/* Password sign-in escape hatch */}
        <Pressable onPress={() => { setError(''); setView('credentials'); }}>
          <ThemedText type="small" themeColor="textSecondary" style={styles.center}>
            Sign in with password
          </ThemedText>
        </Pressable>
      </View>
    ),
    [biometric, busy, error, theme]
  );

  const renderPinAuth = useCallback(
    () => (
      <View style={styles.pinContainer}>
        <PinEntry
          title="Enter PIN"
          subtitle="Enter your 6-digit PIN to access your account."
          error={error}
          value={pin}
          onChange={next => { setPin(next); setError(''); }}
          onComplete={handlePinAuthComplete}
        />

        {biometric?.isAvailable ? (
          <TouchableOpacity
            style={styles.textLink}
            onPress={() => { setError(''); setPin(''); setView('biometric'); }}>
            <ThemedText type="linkPrimary" style={styles.center}>
              Use {biometric.label} instead
            </ThemedText>
          </TouchableOpacity>
        ) : null}

        <Pressable onPress={() => { setError(''); setPin(''); setView('credentials'); }}>
          <ThemedText type="small" themeColor="textSecondary" style={styles.center}>
            Sign in with password
          </ThemedText>
        </Pressable>
      </View>
    ),
    [biometric, pin, error]
  );

  const renderPinSetup = useCallback(
    () => (
      <View style={styles.pinContainer}>
        <PinEntry
          title="Create PIN"
          subtitle="Set a 6-digit PIN as a secure backup when biometrics are unavailable."
          error={error}
          value={pin}
          onChange={next => { setPin(next); setError(''); }}
          onComplete={handlePinSetupComplete}
        />
      </View>
    ),
    [pin, error]
  );

  const renderPinConfirm = useCallback(
    () => (
      <View style={styles.pinContainer}>
        {busy ? (
          <ActivityIndicator size="large" color={Colors.light.text} />
        ) : (
          <PinEntry
            title="Confirm PIN"
            subtitle="Re-enter your PIN to confirm."
            error={error}
            value={confirmPin}
            onChange={next => { setConfirmPin(next); setError(''); }}
            onComplete={handlePinConfirmComplete}
          />
        )}
      </View>
    ),
    [confirmPin, busy, error]
  );

  // ---------------------------------------------------------------------------
  // Root render
  // ---------------------------------------------------------------------------

  return (
    <ThemedView style={styles.screen}>
      <SafeAreaView style={styles.safe}>
        {/* Branding header — always visible */}
        <View style={styles.header}>
          <View style={[styles.logoMark, { backgroundColor: '#208AEF' }]}>
            <ThemedText style={styles.logoText}>F</ThemedText>
          </View>
          <ThemedText type="small" style={[styles.brandName, { color: '#208AEF' }]}>
            FinanceApp
          </ThemedText>
        </View>

        {/* Content area */}
        <View style={styles.content}>
          {view === 'credentials' && renderCredentials()}
          {view === 'biometric' && renderBiometric()}
          {view === 'pin_auth' && renderPinAuth()}
          {view === 'pin_setup' && renderPinSetup()}
          {view === 'pin_confirm' && renderPinConfirm()}
        </View>
      </SafeAreaView>
    </ThemedView>
  );
}

// ---------------------------------------------------------------------------
// Styles
// ---------------------------------------------------------------------------

const BRAND_BLUE = '#208AEF';

const styles = StyleSheet.create({
  screen: {
    flex: 1,
  },
  safe: {
    flex: 1,
    paddingHorizontal: Spacing.four,
    gap: Spacing.four,
  },
  header: {
    alignItems: 'center',
    paddingTop: Spacing.five,
    gap: Spacing.two,
  },
  logoMark: {
    width: 64,
    height: 64,
    borderRadius: 18,
    alignItems: 'center',
    justifyContent: 'center',
  },
  logoText: {
    fontSize: 36,
    fontWeight: '700',
    color: '#fff',
    lineHeight: 44,
  },
  brandName: {
    fontWeight: '700',
    letterSpacing: 0.5,
  },
  content: {
    flex: 1,
    justifyContent: 'center',
  },

  // ── Credentials form ─────────────────────────────────────────────────────
  form: {
    gap: Spacing.three,
  },
  formTitle: {
    textAlign: 'center',
  },
  formSubtitle: {
    textAlign: 'center',
    marginBottom: Spacing.two,
  },
  fieldWrapper: {
    gap: Spacing.one,
  },
  label: {
    fontWeight: '600',
  },
  input: {
    height: 52,
    borderWidth: 1,
    borderRadius: 12,
    paddingHorizontal: Spacing.three,
    fontSize: 16,
  },
  passwordRow: {
    position: 'relative',
  },
  passwordInput: {
    paddingRight: 64,
  },
  eyeBtn: {
    position: 'absolute',
    right: Spacing.three,
    top: 0,
    bottom: 0,
    justifyContent: 'center',
  },

  // ── Biometric view ────────────────────────────────────────────────────────
  biometricContainer: {
    alignItems: 'center',
    gap: Spacing.three,
  },
  biometricIcon: {
    width: 100,
    height: 100,
    borderRadius: 28,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: Spacing.two,
  },
  biometricEmoji: {
    fontSize: 52,
    lineHeight: 60,
  },

  // ── PIN views ─────────────────────────────────────────────────────────────
  pinContainer: {
    alignItems: 'center',
    gap: Spacing.four,
  },

  // ── Shared ────────────────────────────────────────────────────────────────
  primaryBtn: {
    height: 54,
    borderRadius: 14,
    backgroundColor: BRAND_BLUE,
    alignItems: 'center',
    justifyContent: 'center',
    alignSelf: 'stretch',
    marginTop: Spacing.two,
  },
  primaryBtnDisabled: {
    opacity: 0.55,
  },
  primaryBtnLabel: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
  errorText: {
    color: '#FF3B30',
    fontSize: 14,
    textAlign: 'center',
  },
  dividerRow: {
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'stretch',
    gap: Spacing.two,
  },
  dividerLine: {
    flex: 1,
    height: 1,
  },
  dividerLabel: {
    paddingHorizontal: Spacing.one,
  },
  center: {
    textAlign: 'center',
  },
  textLink: {
    paddingVertical: Spacing.one,
  },
});
