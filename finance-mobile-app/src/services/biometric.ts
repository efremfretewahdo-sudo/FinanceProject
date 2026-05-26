import * as LocalAuthentication from 'expo-local-authentication';
import { Platform } from 'react-native';

export type BiometricCapability = {
  isAvailable: boolean;
  hasHardware: boolean;
  isEnrolled: boolean;
  supportedTypes: LocalAuthentication.AuthenticationType[];
  label: string;
};

export type BiometricResult = {
  success: boolean;
  error?: string;
};

export async function checkBiometricCapability(): Promise<BiometricCapability> {
  if (Platform.OS === 'web') {
    return {
      isAvailable: false,
      hasHardware: false,
      isEnrolled: false,
      supportedTypes: [],
      label: 'Biometrics',
    };
  }

  const [hasHardware, isEnrolled, supportedTypes] = await Promise.all([
    LocalAuthentication.hasHardwareAsync(),
    LocalAuthentication.isEnrolledAsync(),
    LocalAuthentication.supportedAuthenticationTypesAsync(),
  ]);

  return {
    hasHardware,
    isEnrolled,
    isAvailable: hasHardware && isEnrolled,
    supportedTypes,
    label: resolveBiometricLabel(supportedTypes),
  };
}

function resolveBiometricLabel(types: LocalAuthentication.AuthenticationType[]): string {
  const { AuthenticationType } = LocalAuthentication;
  if (types.includes(AuthenticationType.FACIAL_RECOGNITION)) return 'Face ID';
  if (types.includes(AuthenticationType.FINGERPRINT)) return 'Fingerprint';
  if (types.includes(AuthenticationType.IRIS)) return 'Iris Recognition';
  return 'Biometrics';
}

export async function authenticate(promptMessage: string): Promise<BiometricResult> {
  try {
    const result = await LocalAuthentication.authenticateAsync({
      promptMessage,
      fallbackLabel: 'Use PIN',
      cancelLabel: 'Cancel',
      // We manage the PIN fallback ourselves so native fallback is disabled
      disableDeviceFallback: true,
    });

    if (result.success) return { success: true };

    return {
      success: false,
      error: result.error === 'user_cancel' ? undefined : result.error,
    };
  } catch (err) {
    return { success: false, error: String(err) };
  }
}
