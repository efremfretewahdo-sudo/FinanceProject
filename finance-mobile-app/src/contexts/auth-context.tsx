import React, { createContext, useCallback, useContext, useEffect, useReducer } from 'react';
import { Platform } from 'react-native';

import { apiLogin } from '@/config/api';
import { apiClient } from '@/services/api-client';
import { authenticate, checkBiometricCapability, BiometricCapability } from '@/services/biometric';
import * as storage from '@/services/secure-storage';

// ---------------------------------------------------------------------------
// State machine
// ---------------------------------------------------------------------------

export type AuthStatus =
  | 'loading'           // reading SecureStore / localStorage on startup
  | 'unauthenticated'   // no stored session — show credentials form
  | 'biometric_prompt'  // session exists — challenge with biometric / PIN
  | 'pin_setup'         // first login succeeded, ask user to create a PIN
  | 'authenticated';    // fully verified, show the app

type AuthState = {
  status: AuthStatus;
  token: string | null;
  userEmail: string | null;
  userName: string | null;
  biometric: BiometricCapability | null;
};

type AuthAction =
  | {
      type: 'INIT';
      token: string | null;
      userEmail: string | null;
      userName: string | null;
      biometric: BiometricCapability;
      pinEnabled: boolean;
    }
  | { type: 'CREDENTIAL_LOGIN'; token: string; userEmail: string; userName: string }
  | { type: 'PIN_SAVED' }
  | { type: 'VERIFIED' }
  | { type: 'LOGOUT' };

function reducer(state: AuthState, action: AuthAction): AuthState {
  switch (action.type) {
    case 'INIT': {
      if (!action.token) {
        return {
          ...state,
          status: 'unauthenticated',
          token: null,
          userEmail: null,
          userName: null,
          biometric: action.biometric,
        };
      }
      const base = {
        token: action.token,
        userEmail: action.userEmail,
        userName: action.userName,
        biometric: action.biometric,
      };
      // On web: stored token is sufficient — skip biometric / PIN challenge
      if (Platform.OS === 'web') return { ...base, status: 'authenticated' };
      return { ...base, status: 'biometric_prompt' };
    }

    case 'CREDENTIAL_LOGIN': {
      const base = {
        ...state,
        token: action.token,
        userEmail: action.userEmail,
        userName: action.userName,
      };
      // On web: credentials alone are sufficient — skip PIN setup
      if (Platform.OS === 'web') return { ...base, status: 'authenticated' };
      return { ...base, status: 'pin_setup' };
    }

    case 'PIN_SAVED':
      return { ...state, status: 'authenticated' };

    case 'VERIFIED':
      return { ...state, status: 'authenticated' };

    case 'LOGOUT':
      return {
        status: 'unauthenticated',
        token: null,
        userEmail: null,
        userName: null,
        biometric: state.biometric,
      };

    default:
      return state;
  }
}

// ---------------------------------------------------------------------------
// Context
// ---------------------------------------------------------------------------

type AuthContextValue = {
  status: AuthStatus;
  token: string | null;
  userEmail: string | null;
  userName: string | null;
  biometric: BiometricCapability | null;
  /** Sign in with email + password. Throws on bad credentials. */
  loginWithCredentials: (email: string, password: string) => Promise<void>;
  /** Create the 6-digit backup PIN after a credential login. */
  setupPin: (pin: string) => Promise<void>;
  /** Attempt biometric verification. */
  authenticateWithBiometric: (reason?: string) => Promise<boolean>;
  /** Verify the backup PIN. */
  authenticateWithPin: (pin: string) => Promise<boolean>;
  /** Revoke the server token, clear all secure data, return to sign-in. */
  logout: () => Promise<void>;
  /**
   * Authorise a sensitive transaction.
   * Tries biometric first; returns false when unavailable so the caller
   * can fall back to a PIN sheet.
   */
  authorizeTransaction: (description: string) => Promise<boolean>;
};

const AuthContext = createContext<AuthContextValue | null>(null);

// ---------------------------------------------------------------------------
// Provider
// ---------------------------------------------------------------------------

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [state, dispatch] = useReducer(reducer, {
    status: 'loading',
    token: null,
    userEmail: null,
    userName: null,
    biometric: null,
  });

  // Boot: read persisted auth state in parallel
  useEffect(() => {
    (async () => {
      const [token, userEmail, userName, biometric, pinEnabled] = await Promise.all([
        storage.getAuthToken(),
        storage.getUserEmail(),
        storage.getUserName(),
        checkBiometricCapability(),
        storage.isPinEnabled(),
      ]);
      dispatch({ type: 'INIT', token, userEmail, userName, biometric, pinEnabled });
    })();
  }, []);

  const loginWithCredentials = useCallback(async (email: string, password: string) => {
    // apiLogin calls POST /api/v1/auth/login, unwraps the Laravel envelope,
    // and returns { token, user }. Throws a human-readable Error on failure.
    const { token, user } = await apiLogin(email, password);

    await Promise.all([
      storage.saveAuthToken(token),
      storage.saveUserEmail(user.email),
      storage.saveUserName(user.name),
    ]);

    dispatch({ type: 'CREDENTIAL_LOGIN', token, userEmail: user.email, userName: user.name });
  }, []);

  const setupPin = useCallback(async (pin: string) => {
    await storage.saveUserPin(pin);
    dispatch({ type: 'PIN_SAVED' });
  }, []);

  const authenticateWithBiometric = useCallback(
    async (reason = 'Verify your identity to continue'): Promise<boolean> => {
      const { success } = await authenticate(reason);
      if (success) dispatch({ type: 'VERIFIED' });
      return success;
    },
    []
  );

  const authenticateWithPin = useCallback(async (pin: string): Promise<boolean> => {
    const valid = await storage.verifyPin(pin);
    if (valid) dispatch({ type: 'VERIFIED' });
    return valid;
  }, []);

  const authorizeTransaction = useCallback(
    async (description: string): Promise<boolean> => {
      if (!state.biometric?.isAvailable) return false;
      const { success } = await authenticate(description);
      return success;
    },
    [state.biometric]
  );

  const logout = useCallback(async () => {
    // 1. Revoke the Sanctum token on the server so it can't be replayed.
    //    We fire-and-forget: a network error must never block the user from
    //    logging out of their own device.
    try {
      await apiClient.post('/auth/logout');
    } catch {
      // Silently ignored — token will expire naturally via Sanctum's expiry setting.
    }

    // 2. Wipe all locally stored credentials regardless of server response.
    await storage.clearAllSecureData();

    dispatch({ type: 'LOGOUT' });
  }, []);

  return (
    <AuthContext.Provider
      value={{
        ...state,
        loginWithCredentials,
        setupPin,
        authenticateWithBiometric,
        authenticateWithPin,
        logout,
        authorizeTransaction,
      }}>
      {children}
    </AuthContext.Provider>
  );
}

// ---------------------------------------------------------------------------
// Hook
// ---------------------------------------------------------------------------

export function useAuth(): AuthContextValue {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within <AuthProvider>');
  return ctx;
}
