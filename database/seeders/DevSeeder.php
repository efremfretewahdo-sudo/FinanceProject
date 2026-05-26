<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * DevSeeder — injects a known test user + sample transactions.
 *
 * Run with:
 *   php artisan db:seed --class=DevSeeder
 *
 * Safe to run multiple times — uses updateOrCreate so it will never
 * duplicate the user. Transactions are only seeded when the user
 * has none, so re-running won't double the data.
 *
 * NOTE: balance, monthly_income, and monthly_expenses are NOT columns
 * on the users table. They are computed live from the transactions table
 * by the DashboardController. The sample transactions seeded below are
 * what feeds those numbers to the mobile dashboard.
 */
class DevSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Create / update the test user ─────────────────────────────
        $user = User::updateOrCreate(
            ['email' => 'admin@finance.com'],
            [
                'name'              => 'Admin User',
                'password'          => 'password123', // cast('hashed') hashes this automatically
                'email_verified_at' => now(),
                'is_approved'       => true,
                // plan_expires_at left null → isApproved() returns true for null
            ]
        );

        $this->command->info("✓ User ready: {$user->email}  (id={$user->id})");

        // ── 2. Seed sample transactions (only if the account is empty) ────
        // These are what the dashboard reads to compute:
        //   balance         = SUM(income) − SUM(expense)  [all-time]
        //   monthly_income  = SUM(income)  WHERE month = current
        //   monthly_expenses= SUM(expense) WHERE month = current
        if ($user->transactions()->exists()) {
            $this->command->info('  ↳ transactions already exist — skipping sample data.');
            return;
        }

        $now   = now();
        $month = $now->format('Y-m');

        $rows = [
            // ── Current month income ──────────────────────────────────────
            [
                'title'            => 'Monthly Salary',
                'amount'           => 3200.00,
                'type'             => 'income',
                'description'      => 'November payroll deposit',
                'transaction_date' => "{$month}-01",
            ],
            [
                'title'            => 'Freelance Payment',
                'amount'           => 840.00,
                'type'             => 'income',
                'description'      => 'Logo design project',
                'transaction_date' => "{$month}-10",
            ],

            // ── Current month expenses ────────────────────────────────────
            [
                'title'            => 'Grocery Store',
                'amount'           => 87.42,
                'type'             => 'expense',
                'description'      => 'Weekly groceries',
                'transaction_date' => "{$month}-05",
            ],
            [
                'title'            => 'Netflix',
                'amount'           => 15.99,
                'type'             => 'expense',
                'description'      => 'Monthly subscription',
                'transaction_date' => "{$month}-08",
            ],
            [
                'title'            => 'Electric Bill',
                'amount'           => 94.30,
                'type'             => 'expense',
                'description'      => 'Utility payment',
                'transaction_date' => "{$month}-12",
            ],
            [
                'title'            => 'Uber',
                'amount'           => 12.50,
                'type'             => 'expense',
                'description'      => 'Airport ride',
                'transaction_date' => "{$month}-14",
            ],
            [
                'title'            => 'Amazon',
                'amount'           => 134.99,
                'type'             => 'expense',
                'description'      => 'Office supplies',
                'transaction_date' => "{$month}-16",
            ],

            // ── Previous month (contributes to all-time balance) ──────────
            [
                'title'            => 'Monthly Salary',
                'amount'           => 3200.00,
                'type'             => 'income',
                'description'      => 'October payroll deposit',
                'transaction_date' => $now->copy()->subMonth()->format('Y-m-15'),
            ],
            [
                'title'            => 'Rent',
                'amount'           => 950.00,
                'type'             => 'expense',
                'description'      => 'Monthly rent',
                'transaction_date' => $now->copy()->subMonth()->format('Y-m-01'),
            ],
        ];

        foreach ($rows as $row) {
            $user->transactions()->create(array_merge($row, [
                'category_id' => null,
                'source_type' => null,
                'source_id'   => null,
            ]));
        }

        // ── Summary printed to console ────────────────────────────────────
        $totalIncome   = $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpense  = $user->transactions()->where('type', 'expense')->sum('amount');
        $monthIncome   = $user->transactions()->where('type', 'income')
                              ->whereYear('transaction_date', $now->year)
                              ->whereMonth('transaction_date', $now->month)
                              ->sum('amount');
        $monthExpense  = $user->transactions()->where('type', 'expense')
                              ->whereYear('transaction_date', $now->year)
                              ->whereMonth('transaction_date', $now->month)
                              ->sum('amount');

        $this->command->info("  ↳ " . count($rows) . " transactions seeded.");
        $this->command->info("  ↳ All-time balance:    $" . number_format($totalIncome - $totalExpense, 2));
        $this->command->info("  ↳ Monthly income:      $" . number_format($monthIncome, 2));
        $this->command->info("  ↳ Monthly expenses:    $" . number_format($monthExpense, 2));
        $this->command->info('');
        $this->command->info('Login credentials for mobile app:');
        $this->command->info('  Email   : admin@finance.com');
        $this->command->info('  Password: password123');
    }
}
