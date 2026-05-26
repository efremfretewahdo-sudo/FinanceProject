/**
 * Web implementation of the secure-storage service.
 *
 * Metro automatically resolves this file instead of secure-storage.ts when
 * bundling for the web platform, so expo-secure-store (which is native-only
 * and throws ERR_SECURE_STORE_UNAVAILABLE on web) is never imported.
 *
 * SECURITY NOTE: localStorage is unencrypted and readable by any JavaScript
 * running on the same origin. This implementation is intentionally limited to
 * development / web-preview use. Do not ship a production web build that stores
 * real auth tokens here without adding proper encryption or a cookie-based
 * session strategy.
 */

const NS = 'finance_secure::'; // namespace prefix — avoids key collisions

function lsGet(key: string): string | null {
  try {
    return localStorage.getItem(NS + key);
  } catch {
    return null; // private-browsing mode can deny localStorage access
  }
}

function lsSet(key: string, value: string): void {
  try {
    localStorage.setItem(NS + key, value);
  } catch {
    console.warn('[secure-storage.web] localStorage write failed (storage quota or private mode)');
  }
}

function lsRemove(key: string): void {
  try {
    localStorage.removeItem(NS + key);
  } catch {
    // ignore
  }
}

// ---------------------------------------------------------------------------
// Public API — mirrors secure-storage.ts exactly
// ---------------------------------------------------------------------------

export const STORAGE_KEYS = {
  AUTH_TOKEN:  'finance_auth_token',
  USER_PIN:    'finance_user_pin',
  USER_EMAIL:  'finance_user_email',
  USER_NAME:   'finance_user_name',
  PIN_ENABLED: 'finance_pin_enabled',
} as const;

export async function saveAuthToken(token: string): Promise<void> {
  lsSet(STORAGE_KEYS.AUTH_TOKEN, token);
}

export async function getAuthToken(): Promise<string | null> {
  return lsGet(STORAGE_KEYS.AUTH_TOKEN);
}

export async function deleteAuthToken(): Promise<void> {
  lsRemove(STORAGE_KEYS.AUTH_TOKEN);
}

export async function saveUserPin(pin: string): Promise<void> {
  lsSet(STORAGE_KEYS.USER_PIN, pin);
  lsSet(STORAGE_KEYS.PIN_ENABLED, 'true');
}

export async function verifyPin(pin: string): Promise<boolean> {
  const stored = lsGet(STORAGE_KEYS.USER_PIN);
  return stored !== null && stored === pin;
}

export async function isPinEnabled(): Promise<boolean> {
  return lsGet(STORAGE_KEYS.PIN_ENABLED) === 'true';
}

export async function saveUserEmail(email: string): Promise<void> {
  lsSet(STORAGE_KEYS.USER_EMAIL, email);
}

export async function getUserEmail(): Promise<string | null> {
  return lsGet(STORAGE_KEYS.USER_EMAIL);
}

export async function saveUserName(name: string): Promise<void> {
  lsSet(STORAGE_KEYS.USER_NAME, name);
}

export async function getUserName(): Promise<string | null> {
  return lsGet(STORAGE_KEYS.USER_NAME);
}

export async function clearAllSecureData(): Promise<void> {
  Object.values(STORAGE_KEYS).forEach(lsRemove);
}
