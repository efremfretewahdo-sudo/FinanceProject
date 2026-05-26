/**
 * Authenticated Axios instance.
 *
 * Every request automatically receives:
 *   • The base URL from src/config/api.ts
 *   • Authorization: Bearer <token>  (read from SecureStore / localStorage)
 *
 * Every failed response is normalised into a plain Error so callers never
 * need to inspect AxiosError internals.
 */

import axios from 'axios';
import type { AxiosError } from 'axios';
import { Platform } from 'react-native';

import { WEB_BASE_URL, NATIVE_BASE_URL } from '@/config/api';
import { getAuthToken } from '@/services/secure-storage';

const BASE_URL: string = Platform.select({
  web:     WEB_BASE_URL,
  default: NATIVE_BASE_URL,
})!;

export const apiClient = axios.create({
  baseURL: BASE_URL,
  timeout: 15_000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// ── Request interceptor ───────────────────────────────────────────────────────
// Reads the stored token and attaches it as a Bearer header on every request.
// Because SecureStore.getItemAsync is async, we use an async interceptor here.

apiClient.interceptors.request.use(async config => {
  const token = await getAuthToken();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// ── Response interceptor ──────────────────────────────────────────────────────
// Two failure scenarios:
//   1. No response at all → network / DNS / firewall issue
//   2. HTTP error status  → Laravel returned 4xx / 5xx

apiClient.interceptors.response.use(
  response => response,
  (error: AxiosError<{ message?: string }>) => {
    if (!error.response) {
      // The request never reached the server (wrong IP, Laravel not running, etc.)
      throw new Error(
        'Cannot reach the server.\n\n' +
          '• Run: php artisan serve --host=0.0.0.0 --port=8000\n' +
          '• Set API_BASE_URL to your machine\'s LAN IP, not localhost\n' +
          `• Current URL: ${BASE_URL}`
      );
    }

    // Laravel returns { message: "..." } on 401 / 422 / 500
    const message =
      error.response.data?.message ?? `Server error (${error.response.status})`;
    throw new Error(message);
  }
);
