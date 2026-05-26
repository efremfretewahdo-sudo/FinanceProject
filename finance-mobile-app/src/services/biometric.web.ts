/**
 * Web stub for the biometric service.
 *
 * Metro resolves this file on the web platform instead of biometric.ts,
 * preventing expo-local-authentication (a native-only module) from being
 * bundled or called in a browser context.
 *
 * All capability checks return isAvailable: false so the rest of the app
 * gracefully degrades to the PIN / password path on web.
 */

// Mirrors the shape of BiometricCapability from biometric.ts.
// AuthenticationType is a numeric enum; an empty number[] is compatible.
export type BiometricCapability = {
  isAvailable: boolean;
  hasHardware: boolean;
  isEnrolled: boolean;
  supportedTypes: number[];
  label: string;
};

export type BiometricResult = {
  success: boolean;
  error?: string;
};

export async function checkBiometricCapability(): Promise<BiometricCapability> {
  return {
    isAvailable: false,
    hasHardware: false,
    isEnrolled: false,
    supportedTypes: [],
    label: 'Biometrics',
  };
}

export async function authenticate(_promptMessage: string): Promise<BiometricResult> {
  return { success: false, error: 'Biometric authentication is not supported in a web browser.' };
}
