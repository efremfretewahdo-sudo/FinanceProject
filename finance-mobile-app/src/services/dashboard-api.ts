/**
 * Dashboard API service.
 *
 * In MOCK_MODE  → returns hardcoded data with simulated network latency.
 * In live mode  → GET /api/v1/dashboard using the authenticated Axios instance.
 *
 * Laravel endpoint: GET /api/v1/dashboard
 * Controller: app/Http/Controllers/Api/V1/DashboardController@index
 *
 * Raw Laravel JSON envelope:
 * {
 *   "status": "success",
 *   "data": {
 *     "balance":          24563.80,   ← all-time net (income − expenses)
 *     "monthly_income":    6240.00,   ← income in the current calendar month
 *     "monthly_expenses":  5652.60,   ← expenses in the current calendar month
 *     "total_members":     12,
 *     "transactions": [
 *       {
 *         "id": 1,
 *         "title": "Netflix",
 *         "amount": 15.99,            ← always positive on the server
 *         "type": "expense",          ← "income" | "expense"
 *         "description": null,
 *         "transaction_date": "2026-05-25",
 *         "source_type": null,
 *         "category": { "id": 2, "name": "Entertainment", "color": "#6366f1" }
 *       }
 *     ]
 *   }
 * }
 *
 * This service unwraps the envelope and maps field names so the rest of the
 * app continues to work with the DashboardData type unchanged.
 */

import { MOCK_MODE } from '@/config/api';
import { apiClient } from '@/services/api-client';

// ---------------------------------------------------------------------------
// Public types  (consumed by screens / components — do not rename)
// ---------------------------------------------------------------------------

export type DashboardBalance = {
  total: number;       // all-time net position (server: balance)
  change: number;      // net monthly P&L  (monthly_income − monthly_expenses)
  changePct: number;   // change as % of monthly_income (0 when no income)
  income: number;      // server: monthly_income
  expenses: number;    // server: monthly_expenses
};

export type DashboardTransaction = {
  id: number;
  name: string;        // server: title
  category: string;    // server: category.name (used to pick icon/colour)
  amount: number;      // negative = expense, positive = income (sign applied here)
  date: string;        // server: transaction_date  ("2026-05-25")
};

export type DashboardData = {
  balance: DashboardBalance;
  transactions: DashboardTransaction[];
};

// ---------------------------------------------------------------------------
// Internal type: raw shape the Laravel endpoint returns
// ---------------------------------------------------------------------------

type LaravelTransaction = {
  id: number;
  title: string;
  amount: number;                       // always positive on the server
  type: 'income' | 'expense';
  description: string | null;
  transaction_date: string;
  source_type: string | null;
  category: { id: number | null; name: string | null; color: string | null } | null;
};

type LaravelDashboardData = {
  balance: number;
  monthly_income: number;
  monthly_expenses: number;
  total_members: number;
  transactions: LaravelTransaction[];
};

type LaravelDashboardEnvelope = {
  status: string;
  data: LaravelDashboardData;
};

// ---------------------------------------------------------------------------
// Mock payload
// ---------------------------------------------------------------------------

const MOCK_DATA: DashboardData = {
  balance: {
    total:     24_563.8,
    change:    587.4,
    changePct: 2.4,
    income:    6_240.0,
    expenses:  5_652.6,
  },
  transactions: [
    { id: 1, name: 'Netflix',        category: 'Entertainment', amount:  -15.99, date: 'Today' },
    { id: 2, name: 'Monthly Salary', category: 'Income',        amount: 3200.00, date: 'Mon'   },
    { id: 3, name: 'Grocery Store',  category: 'Food',          amount:  -87.42, date: 'Sun'   },
    { id: 4, name: 'Uber',           category: 'Transport',     amount:  -12.50, date: 'Sat'   },
    { id: 5, name: 'Amazon',         category: 'Shopping',      amount: -134.99, date: 'Fri'   },
    { id: 6, name: 'Freelance',      category: 'Income',        amount:  840.00, date: 'Thu'   },
    { id: 7, name: 'Electric Bill',  category: 'Utilities',     amount:  -94.30, date: 'Wed'   },
  ],
};

// ---------------------------------------------------------------------------
// Response mapper
// ---------------------------------------------------------------------------

/**
 * Converts the raw Laravel payload into the DashboardData shape the app uses.
 *
 * Key transformations:
 *  • balance fields are renamed and a changePct is derived
 *  • transaction.title  → name
 *  • transaction.amount is negated for expense rows (server always sends > 0)
 *  • transaction.category.name → category string (falls back to 'General')
 */
function mapLaravelResponse(raw: LaravelDashboardData): DashboardData {
  const { monthly_income, monthly_expenses } = raw;
  const monthlyNet = monthly_income - monthly_expenses;
  const changePct  = monthly_income > 0
    ? parseFloat(((monthlyNet / monthly_income) * 100).toFixed(1))
    : 0;

  const balance: DashboardBalance = {
    total:     raw.balance,
    change:    parseFloat(monthlyNet.toFixed(2)),
    changePct,
    income:    monthly_income,
    expenses:  monthly_expenses,
  };

  const transactions: DashboardTransaction[] = raw.transactions.map(tx => ({
    id:       tx.id,
    name:     tx.title,
    category: tx.category?.name ?? 'General',
    // Server always sends a positive amount; negate expenses so the UI can
    // render them in red without knowing the "type" field.
    amount:   tx.type === 'expense' ? -Math.abs(tx.amount) : Math.abs(tx.amount),
    date:     tx.transaction_date,
  }));

  return { balance, transactions };
}

// ---------------------------------------------------------------------------
// Public API
// ---------------------------------------------------------------------------

/**
 * Fetch dashboard summary for the authenticated user.
 *
 * The Bearer token is attached automatically by the Axios request interceptor
 * in api-client.ts — no manual header setup required here.
 */
export async function apiGetDashboard(): Promise<DashboardData> {
  if (MOCK_MODE) {
    await new Promise<void>(resolve => setTimeout(resolve, 400));
    return MOCK_DATA;
  }

  // apiClient.baseURL = API_BASE_URL = "http://<host>/api/v1"
  // So this resolves to:  GET /api/v1/dashboard
  const { data: envelope } = await apiClient.get<LaravelDashboardEnvelope>('/dashboard');

  return mapLaravelResponse(envelope.data);
}
