<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Return the authenticated user's financial summary and recent transactions.
     *
     * Endpoint : GET /api/v1/dashboard
     * Middleware: auth:sanctum, api.approved, throttle:api
     *
     * Response shape:
     * {
     *   "status": "success",
     *   "data": {
     *     "balance":           float,   // all-time net position (income − expenses)
     *     "monthly_income":    float,   // income recorded in the current calendar month
     *     "monthly_expenses":  float,   // expenses recorded in the current calendar month
     *     "total_members":     int,     // member count owned by this user
     *     "transactions": [            // 10 most-recent transactions (newest first)
     *       {
     *         "id":               int,
     *         "title":            string,
     *         "amount":           float,
     *         "type":             "income" | "expense",
     *         "description":      string | null,
     *         "transaction_date": "YYYY-MM-DD",
     *         "source_type":      string | null,
     *         "category": {
     *           "id":    int | null,
     *           "name":  string | null,
     *           "color": string | null
     *         }
     *       }
     *     ]
     *   }
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $now  = now();

        // ── Aggregate figures ─────────────────────────────────────────────

        // All-time balance: total income minus total expenses across all time.
        $totalIncome  = (float) $user->transactions()
                            ->where('type', 'income')
                            ->sum('amount');

        $totalExpense = (float) $user->transactions()
                            ->where('type', 'expense')
                            ->sum('amount');

        $balance = round($totalIncome - $totalExpense, 2);

        // Current-month income
        $monthlyIncome = (float) $user->transactions()
                            ->where('type', 'income')
                            ->whereYear('transaction_date',  $now->year)
                            ->whereMonth('transaction_date', $now->month)
                            ->sum('amount');

        // Current-month expenses
        $monthlyExpenses = (float) $user->transactions()
                            ->where('type', 'expense')
                            ->whereYear('transaction_date',  $now->year)
                            ->whereMonth('transaction_date', $now->month)
                            ->sum('amount');

        // Member count scoped to this user
        $totalMembers = Member::where('user_id', $user->id)->count();

        // ── Recent transactions ───────────────────────────────────────────
        // Eager-load category to avoid N+1 queries.
        // Scoped to the authenticated user — no cross-user data leakage possible.

        $transactions = $user->transactions()
            ->with('category:id,name,color')
            ->latest('transaction_date')
            ->take(10)
            ->get()
            ->map(fn ($tx) => [
                'id'               => $tx->id,
                'title'            => $tx->title,
                'amount'           => (float) $tx->amount,
                'type'             => $tx->type,
                'description'      => $tx->description,
                'transaction_date' => $tx->transaction_date->toDateString(),
                'source_type'      => $tx->source_type,
                'category'         => [
                    'id'    => $tx->category?->id,
                    'name'  => $tx->category?->name,
                    'color' => $tx->category?->color,
                ],
            ]);

        // ── Response ──────────────────────────────────────────────────────

        return response()->json([
            'status' => 'success',
            'data'   => [
                'balance'          => $balance,
                'monthly_income'   => round($monthlyIncome,  2),
                'monthly_expenses' => round($monthlyExpenses, 2),
                'total_members'    => $totalMembers,
                'transactions'     => $transactions,
            ],
        ], 200);
    }
}
