import * as SecureStore from 'expo-secure-store';

export const STORAGE_KEYS = {
  AUTH_TOKEN: 'finance_auth_token',
  USER_PIN: 'finance_user_pin',
  USER_EMAIL: 'finance_user_email',
  USER_NAME: 'finance_user_name',
  PIN_ENABLED: 'finance_pin_enabled',
} as const;

export async function saveAuthToken(token: string): Promise<void> {
  await SecureStore.setItemAsync(STORAGE_KEYS.AUTH_TOKEN, token);
}

export async function getAuthToken(): Promise<string | null> {
  return SecureStore.getItemAsync(STORAGE_KEYS.AUTH_TOKEN);
}

export async function deleteAuthToken(): Promise<void> {
  await SecureStore.deleteItemAsync(STORAGE_KEYS.AUTH_TOKEN);
}

export async function saveUserPin(pin: string): Promise<void> {
  await SecureStore.setItemAsync(STORAGE_KEYS.USER_PIN, pin);
  await SecureStore.setItemAsync(STORAGE_KEYS.PIN_ENABLED, 'true');
}

export async function verifyPin(pin: string): Promise<boolean> {
  const stored = await SecureStore.getItemAsync(STORAGE_KEYS.USER_PIN);
  return stored !== null && stored === pin;
}

export async function isPinEnabled(): Promise<boolean> {
  return (await SecureStore.getItemAsync(STORAGE_KEYS.PIN_ENABLED)) === 'true';
}

export async function saveUserEmail(email: string): Promise<void> {
  await SecureStore.setItemAsync(STORAGE_KEYS.USER_EMAIL, email);
}

export async function getUserEmail(): Promise<string | null> {
  return SecureStore.getItemAsync(STORAGE_KEYS.USER_EMAIL);
}

export async function saveUserName(name: string): Promise<void> {
  await SecureStore.setItemAsync(STORAGE_KEYS.USER_NAME, name);
}

export async function getUserName(): Promise<string | null> {
  return SecureStore.getItemAsync(STORAGE_KEYS.USER_NAME);
}

export async function clearAllSecureData(): Promise<void> {
  await Promise.all(
    Object.values(STORAGE_KEYS).map(key => SecureStore.deleteItemAsync(key))
  );
}
