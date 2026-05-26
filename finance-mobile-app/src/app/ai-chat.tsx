/**
 * ትሕትና AI — Financial AI Assistant Chat Screen
 *
 * API contract (live mode):
 *   POST /api/v1/ai/chat
 *   Body:    { message: string }
 *   Response: { status: "success", data: { reply: string } }
 *
 * Toggle MOCK_MODE in src/config/api.ts to switch between
 * mock responses and the real Laravel AI endpoint.
 */

import { Ionicons } from '@expo/vector-icons';
import { useCallback, useEffect, useRef, useState } from 'react';
import {
  Animated,
  FlatList,
  KeyboardAvoidingView,
  Platform,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import { MOCK_MODE } from '@/config/api';
import { apiClient } from '@/services/api-client';

// ---------------------------------------------------------------------------
// Design tokens
// ---------------------------------------------------------------------------

const NAVY   = '#051A3A';
const TEAL   = '#26E6A5';
const BLUE   = '#007AFF';
const AI_BG  = '#F0F7FF';   // AI bubble background
const AI_BDR = '#DDE8FB';   // AI bubble border

// ---------------------------------------------------------------------------
// Types
// ---------------------------------------------------------------------------

type MessageRole = 'user' | 'ai';

type Message = {
  id: string;
  role: MessageRole;
  text: string;
  timestamp: Date;
};

// ---------------------------------------------------------------------------
// Mock AI knowledge base
// Each entry matches keywords against the user's message (case-insensitive).
// Entries are checked in order — first match wins.
// ---------------------------------------------------------------------------

const MOCK_KNOWLEDGE: Array<{ keywords: string[]; reply: string }> = [
  {
    keywords: ['hello', 'hi', 'hey', 'ሰላም', 'start', 'help'],
    reply:
      'ሰላም! Hello! 👋 I\'m ትሕትና AI, your personal financial assistant.\n\n' +
      'I can help you with:\n' +
      '• Account balances & net worth\n' +
      '• Transaction breakdowns\n' +
      '• Spending patterns & insights\n' +
      '• Income tracking & savings tips\n\n' +
      'What would you like to explore today?',
  },
  {
    keywords: ['balance', 'ሕሳብ', 'net worth', 'total'],
    reply:
      'Your current net balance is $24,563.80 💰\n\n' +
      'This month\'s summary:\n' +
      '  ↑ Income   $6,240.00\n' +
      '  ↓ Expenses $5,652.60\n' +
      '  ✦ Net      +$587.40 (+2.4%)\n\n' +
      'You\'re trending upward compared to last month. Keep it up!',
  },
  {
    keywords: ['expense', 'ወጻኢ', 'spending', 'spent', 'cost'],
    reply:
      'Your monthly expenses are $5,652.60 📊\n\n' +
      'Top categories this month:\n' +
      '  🛍️  Shopping      $134.99  (24%)\n' +
      '  🛒  Food           $87.42  (15%)\n' +
      '  ⚡  Utilities       $94.30  (17%)\n' +
      '  🚗  Transport       $12.50   (2%)\n' +
      '  🎬  Entertainment   $15.99   (3%)\n\n' +
      '💡 Tip: Reducing discretionary shopping could free up $50–$100/month.',
  },
  {
    keywords: ['income', 'እቶት', 'salary', 'earning', 'earn'],
    reply:
      'Your total income this month is $6,240.00 📈\n\n' +
      'Income sources:\n' +
      '  💼  Monthly Salary   $3,200.00\n' +
      '  💻  Freelance Work     $840.00\n' +
      '  📦  Other sources    $2,200.00\n\n' +
      'Your income covers all expenses with a healthy margin. Great diversification!',
  },
  {
    keywords: ['transaction', 'ምንቅስቓስ', 'recent', 'history', 'payment'],
    reply:
      'Here are your most recent transactions:\n\n' +
      '  ↑ Monthly Salary   +$3,200.00  Mon\n' +
      '  ↑ Freelance         +$840.00   Thu\n' +
      '  ↓ Amazon            -$134.99   Fri\n' +
      '  ↓ Electric Bill      -$94.30   Wed\n' +
      '  ↓ Grocery Store      -$87.42   Sun\n' +
      '  ↓ Netflix            -$15.99   Today\n' +
      '  ↓ Uber               -$12.50   Sat\n\n' +
      'View the full list in the Home tab.',
  },
  {
    keywords: ['save', 'saving', 'ቁጠባ', 'goal', 'budget'],
    reply:
      'You\'re currently saving 9.4% of your income ($587.40/month) 🎯\n\n' +
      'Personalized savings plan:\n' +
      '  → Reduce shopping by 20% → saves ~$27/month\n' +
      '  → Cancel unused subscriptions → saves ~$16/month\n' +
      '  → Meal-prep 2 days/week → saves ~$30/month\n\n' +
      'Target: reaching a 20% savings rate would grow your balance by $1,248/month. 💪',
  },
  {
    keywords: ['invest', 'stock', 'crypto', 'portfolio', 'return'],
    reply:
      'Investment planning starts with a strong savings foundation 📊\n\n' +
      'Based on your current balance ($24,563.80), a 3-fund strategy could be:\n' +
      '  30% → Emergency fund (keep liquid)\n' +
      '  40% → Low-cost index funds\n' +
      '  30% → Bonds / stable assets\n\n' +
      'ℹ️ Note: This is educational guidance, not financial advice. Always consult a licensed advisor.',
  },
  {
    keywords: ['card', 'credit', 'debit', 'limit'],
    reply:
      'Your linked cards are visible in the Cards tab 💳\n\n' +
      'Quick overview:\n' +
      '  🇧🇭 Main Account   $24,563.80  Active\n' +
      '  🇺🇸 USD Shopping    $2,000.25  Active\n' +
      '  🇪🇺 Euro Account      $220.00  Active\n\n' +
      'Head to the Cards tab to freeze, set limits, or add a new card.',
  },
];

function getMockReply(message: string): string {
  const lower = message.toLowerCase();
  const match = MOCK_KNOWLEDGE.find(entry =>
    entry.keywords.some(kw => lower.includes(kw))
  );
  return match?.reply ??
    'That\'s an interesting question! 🤔\n\n' +
    'I\'m best at answering questions about your finances. Try:\n' +
    '  • "What\'s my balance?"\n' +
    '  • "Show me my expenses"\n' +
    '  • "How can I save more?"\n' +
    '  • "Recent transactions"';
}

// ---------------------------------------------------------------------------
// AI API call
// ---------------------------------------------------------------------------

async function queryAI(message: string): Promise<string> {
  if (MOCK_MODE) {
    // Simulate a realistic thinking delay (800–1400 ms)
    await new Promise(r => setTimeout(r, 800 + Math.random() * 600));
    return getMockReply(message);
  }

  const { data } = await apiClient.post<{
    status: string;
    data: { reply: string };
  }>('/ai/chat', { message });

  return data.data.reply;
}

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

let _msgCounter = 0;
function newId(): string {
  return `msg-${Date.now()}-${++_msgCounter}`;
}

function fmtTime(date: Date): string {
  return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}

const WELCOME: Message = {
  id:        'welcome',
  role:      'ai',
  text:
    'ሰላም! Hello! 👋\n\nI\'m ትሕትና AI — your personal financial assistant.\n\n' +
    'Ask me anything about your balance, transactions, spending habits, or savings goals.',
  timestamp: new Date(),
};

const SUGGESTIONS = [
  "What's my balance?",
  'Show my expenses',
  'How can I save more?',
  'Recent transactions',
];

// ---------------------------------------------------------------------------
// Typing dots indicator
// ---------------------------------------------------------------------------

function TypingDots() {
  const d1 = useRef(new Animated.Value(0)).current;
  const d2 = useRef(new Animated.Value(0)).current;
  const d3 = useRef(new Animated.Value(0)).current;

  useEffect(() => {
    function bounce(dot: Animated.Value, delay: number) {
      return Animated.loop(
        Animated.sequence([
          Animated.delay(delay),
          Animated.timing(dot, { toValue: -5, duration: 280, useNativeDriver: true }),
          Animated.timing(dot, { toValue: 0,  duration: 280, useNativeDriver: true }),
          Animated.delay(400),
        ])
      );
    }
    const a1 = bounce(d1, 0);
    const a2 = bounce(d2, 140);
    const a3 = bounce(d3, 280);
    a1.start(); a2.start(); a3.start();
    return () => { a1.stop(); a2.stop(); a3.stop(); };
  }, [d1, d2, d3]);

  return (
    <View style={bubble.typingWrap}>
      <View style={bubble.aiAvatar}>
        <Ionicons name="hardware-chip" size={13} color={TEAL} />
      </View>
      <View style={[bubble.bubble, bubble.aiBubble, bubble.typingBubble]}>
        {[d1, d2, d3].map((d, i) => (
          <Animated.View
            key={i}
            style={[bubble.dot, { transform: [{ translateY: d }] }]}
          />
        ))}
      </View>
    </View>
  );
}

// ---------------------------------------------------------------------------
// Message bubble
// ---------------------------------------------------------------------------

function MessageBubble({ msg }: { msg: Message }) {
  const isUser = msg.role === 'user';
  return (
    <View style={[bubble.row, isUser ? bubble.rowUser : bubble.rowAI]}>
      {!isUser && (
        <View style={bubble.aiAvatar}>
          <Ionicons name="hardware-chip" size={13} color={TEAL} />
        </View>
      )}

      <View style={[bubble.bubble, isUser ? bubble.userBubble : bubble.aiBubble]}>
        {!isUser && (
          <Text style={bubble.aiLabel}>ትሕትና AI</Text>
        )}
        <Text style={[bubble.text, isUser ? bubble.userText : bubble.aiText]}>
          {msg.text}
        </Text>
        <Text style={[bubble.time, isUser ? bubble.userTime : bubble.aiTime]}>
          {fmtTime(msg.timestamp)}
        </Text>
      </View>

      {isUser && (
        <View style={bubble.userAvatar}>
          <Ionicons name="person" size={13} color="#fff" />
        </View>
      )}
    </View>
  );
}

const bubble = StyleSheet.create({
  row:       { flexDirection: 'row', alignItems: 'flex-end', marginBottom: 12, paddingHorizontal: 16 },
  rowUser:   { justifyContent: 'flex-end' },
  rowAI:     { justifyContent: 'flex-start' },

  aiAvatar:  { width: 28, height: 28, borderRadius: 14, backgroundColor: NAVY, alignItems: 'center', justifyContent: 'center', marginRight: 8, marginBottom: 2, flexShrink: 0 },
  userAvatar:{ width: 28, height: 28, borderRadius: 14, backgroundColor: BLUE, alignItems: 'center', justifyContent: 'center', marginLeft: 8, marginBottom: 2, flexShrink: 0 },

  bubble:    { maxWidth: '75%', borderRadius: 18, padding: 12 },
  aiBubble:  { backgroundColor: AI_BG, borderWidth: 1, borderColor: AI_BDR, borderBottomLeftRadius: 4 },
  userBubble:{ backgroundColor: NAVY, borderBottomRightRadius: 4 },

  aiLabel:   { fontSize: 10, fontWeight: '700', color: BLUE, marginBottom: 4, letterSpacing: 0.3 },
  text:      { fontSize: 14, lineHeight: 21 },
  aiText:    { color: '#1A1A2E' },
  userText:  { color: '#fff' },
  time:      { fontSize: 10, marginTop: 6 },
  aiTime:    { color: '#9CA3AF', textAlign: 'left' },
  userTime:  { color: 'rgba(255,255,255,0.55)', textAlign: 'right' },

  typingWrap:  { flexDirection: 'row', alignItems: 'flex-end', marginBottom: 12, paddingHorizontal: 16 },
  typingBubble:{ flexDirection: 'row', alignItems: 'center', gap: 5, paddingVertical: 14, paddingHorizontal: 16 },
  dot:         { width: 7, height: 7, borderRadius: 4, backgroundColor: '#94A3B8' },
});

// ---------------------------------------------------------------------------
// Suggestion chip
// ---------------------------------------------------------------------------

function SuggestionChip({ label, onPress }: { label: string; onPress: () => void }) {
  return (
    <TouchableOpacity style={chip.root} onPress={onPress} activeOpacity={0.7}>
      <Text style={chip.text}>{label}</Text>
    </TouchableOpacity>
  );
}

const chip = StyleSheet.create({
  root: {
    paddingHorizontal: 14, paddingVertical: 8,
    borderRadius: 20, borderWidth: 1.5, borderColor: BLUE,
    backgroundColor: '#EFF6FF', margin: 4,
  },
  text: { color: BLUE, fontSize: 13, fontWeight: '600' },
});

// ---------------------------------------------------------------------------
// Main screen
// ---------------------------------------------------------------------------

export default function AIChatScreen() {
  const [messages,  setMessages]  = useState<Message[]>([WELCOME]);
  const [input,     setInput]     = useState('');
  const [isTyping,  setIsTyping]  = useState(false);
  const flatRef = useRef<FlatList>(null);

  const scrollToBottom = useCallback(() => {
    // Small delay so FlatList finishes rendering before scrolling
    setTimeout(() => flatRef.current?.scrollToEnd({ animated: true }), 60);
  }, []);

  useEffect(() => {
    scrollToBottom();
  }, [messages, isTyping, scrollToBottom]);

  const send = useCallback(async (text: string) => {
    const trimmed = text.trim();
    if (!trimmed || isTyping) return;

    setInput('');

    const userMsg: Message = { id: newId(), role: 'user', text: trimmed, timestamp: new Date() };
    setMessages(prev => [...prev, userMsg]);
    setIsTyping(true);

    try {
      const reply = await queryAI(trimmed);
      const aiMsg: Message = { id: newId(), role: 'ai', text: reply, timestamp: new Date() };
      setMessages(prev => [...prev, aiMsg]);
    } catch (err) {
      const errMsg: Message = {
        id:        newId(),
        role:      'ai',
        text:      'ይቕሬታ! Sorry, I couldn\'t reach the server right now.\n\nMake sure Laravel is running and try again.',
        timestamp: new Date(),
      };
      setMessages(prev => [...prev, errMsg]);
    } finally {
      setIsTyping(false);
    }
  }, [isTyping]);

  const showSuggestions = messages.length === 1; // only the welcome message

  return (
    <SafeAreaView edges={['top']} style={styles.safe}>

      {/* ── Header ── */}
      <View style={styles.header}>
        <View style={styles.headerLeft}>
          <View style={styles.headerAvatar}>
            <Ionicons name="hardware-chip" size={18} color={TEAL} />
          </View>
          <View>
            <Text style={styles.headerTitle}>ትሕትና AI</Text>
            <Text style={styles.headerSub}>
              {isTyping ? 'ይሓስብ ኣሎ...' : 'Financial Assistant · Online'}
            </Text>
          </View>
        </View>
        <TouchableOpacity style={styles.headerBtn} activeOpacity={0.7}
          onPress={() => setMessages([WELCOME])}>
          <Ionicons name="refresh-outline" size={20} color="rgba(255,255,255,0.7)" />
        </TouchableOpacity>
      </View>

      {/* ── Messages + input ── */}
      <KeyboardAvoidingView
        style={styles.kav}
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        keyboardVerticalOffset={Platform.OS === 'ios' ? 0 : 0}>

        <FlatList
          ref={flatRef}
          data={messages}
          keyExtractor={m => m.id}
          renderItem={({ item }) => <MessageBubble msg={item} />}
          contentContainerStyle={styles.list}
          showsVerticalScrollIndicator={false}
          ListHeaderComponent={
            showSuggestions ? (
              <View style={styles.suggestionsWrap}>
                <Text style={styles.suggestionsLabel}>Quick questions</Text>
                <View style={styles.suggestionsRow}>
                  {SUGGESTIONS.map(s => (
                    <SuggestionChip key={s} label={s} onPress={() => send(s)} />
                  ))}
                </View>
              </View>
            ) : null
          }
          ListFooterComponent={isTyping ? <TypingDots /> : null}
        />

        {/* ── Input bar ── */}
        <View style={styles.inputBar}>
          <TextInput
            style={styles.input}
            value={input}
            onChangeText={setInput}
            placeholder="Ask ትሕትና AI anything..."
            placeholderTextColor="#9CA3AF"
            multiline
            maxLength={500}
            returnKeyType="send"
            onSubmitEditing={() => send(input)}
            blurOnSubmit
          />
          <TouchableOpacity
            style={[styles.sendBtn, (!input.trim() || isTyping) && styles.sendBtnDisabled]}
            onPress={() => send(input)}
            disabled={!input.trim() || isTyping}
            activeOpacity={0.8}>
            <Ionicons
              name={isTyping ? 'hourglass-outline' : 'arrow-up'}
              size={18}
              color="#fff"
            />
          </TouchableOpacity>
        </View>

      </KeyboardAvoidingView>

      {/* MOCK badge — removed once MOCK_MODE = false */}
      {MOCK_MODE && (
        <View style={styles.mockBadge}>
          <Ionicons name="flask-outline" size={10} color="#fff" />
          <Text style={styles.mockText}>MOCK MODE</Text>
        </View>
      )}

    </SafeAreaView>
  );
}

// ---------------------------------------------------------------------------
// Styles
// ---------------------------------------------------------------------------

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: NAVY },

  // ── Header ─────────────────────────────────────────────────────────────────
  header: {
    backgroundColor: NAVY,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    paddingVertical: 14,
    borderBottomWidth: StyleSheet.hairlineWidth,
    borderBottomColor: 'rgba(255,255,255,0.1)',
  },
  headerLeft:   { flexDirection: 'row', alignItems: 'center', gap: 10 },
  headerAvatar: { width: 38, height: 38, borderRadius: 19, backgroundColor: 'rgba(38,230,165,0.15)', borderWidth: 1.5, borderColor: TEAL, alignItems: 'center', justifyContent: 'center' },
  headerTitle:  { color: '#fff', fontSize: 16, fontWeight: '700', letterSpacing: 0.2 },
  headerSub:    { color: 'rgba(255,255,255,0.55)', fontSize: 11, marginTop: 1 },
  headerBtn:    { width: 36, height: 36, borderRadius: 18, backgroundColor: 'rgba(255,255,255,0.08)', alignItems: 'center', justifyContent: 'center' },

  // ── Body ───────────────────────────────────────────────────────────────────
  kav:  { flex: 1, backgroundColor: '#F8FAFF' },
  list: { paddingTop: 16, paddingBottom: 12 },

  // ── Suggestions ────────────────────────────────────────────────────────────
  suggestionsWrap:  { paddingHorizontal: 16, paddingBottom: 12 },
  suggestionsLabel: { fontSize: 11, color: '#9CA3AF', fontWeight: '600', marginBottom: 8, letterSpacing: 0.5, textTransform: 'uppercase' },
  suggestionsRow:   { flexDirection: 'row', flexWrap: 'wrap' },

  // ── Input bar ──────────────────────────────────────────────────────────────
  inputBar: {
    flexDirection: 'row',
    alignItems: 'flex-end',
    paddingHorizontal: 12,
    paddingVertical: 10,
    backgroundColor: '#fff',
    borderTopWidth: StyleSheet.hairlineWidth,
    borderTopColor: '#E5EAF2',
    gap: 8,
  },
  input: {
    flex: 1,
    minHeight: 44,
    maxHeight: 110,
    backgroundColor: '#F4F7FB',
    borderRadius: 22,
    paddingHorizontal: 16,
    paddingTop: Platform.OS === 'ios' ? 12 : 10,
    paddingBottom: Platform.OS === 'ios' ? 12 : 10,
    fontSize: 15,
    color: '#1A1A2E',
    borderWidth: 1,
    borderColor: '#E5EAF2',
  },
  sendBtn: {
    width: 44, height: 44, borderRadius: 22,
    backgroundColor: NAVY,
    alignItems: 'center', justifyContent: 'center',
    flexShrink: 0,
  },
  sendBtnDisabled: { backgroundColor: '#C5D0E0' },

  // ── Mock badge ─────────────────────────────────────────────────────────────
  mockBadge: {
    position: 'absolute', top: 72, right: 12,
    flexDirection: 'row', alignItems: 'center', gap: 4,
    backgroundColor: '#F59E0B', paddingHorizontal: 8, paddingVertical: 3,
    borderRadius: 10,
  },
  mockText: { color: '#fff', fontSize: 9, fontWeight: '800', letterSpacing: 0.5 },
});
