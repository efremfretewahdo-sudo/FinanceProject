import { DarkTheme, DefaultTheme, ThemeProvider } from 'expo-router';
import { useColorScheme } from 'react-native';

import AppTabs from '@/components/app-tabs';
import { AuthProvider } from '@/contexts/auth-context';

export default function RootLayout() {
  const colorScheme = useColorScheme();

  return (
    <ThemeProvider value={colorScheme === 'dark' ? DarkTheme : DefaultTheme}>
      {/* AuthProvider is kept so profile/other screens that call useAuth() don't crash.
          The login gate is bypassed — the app opens directly to the home tab. */}
      <AuthProvider>
        <AppTabs />
      </AuthProvider>
    </ThemeProvider>
  );
}
