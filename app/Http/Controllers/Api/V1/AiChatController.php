<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * POST /api/v1/ai/chat
 *
 * Request body:  { "message": "What is my balance?" }
 * Response:      { "status": "success", "data": { "reply": "..." } }
 *
 * The current implementation is a rule-based stub.
 * To connect a real LLM, replace the body of getReply() with an
 * HTTP call to OpenAI / Claude / Gemini and pass the user's
 * financial context as system-prompt data.
 */
class AiChatController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $user    = $request->user();
        $message = trim($request->string('message'));
        $reply   = $this->getReply($message, $user);

        return response()->json([
            'status' => 'success',
            'data'   => ['reply' => $reply],
        ]);
    }

    // -------------------------------------------------------------------------
    // Rule-based reply logic (replace with LLM call when ready)
    // -------------------------------------------------------------------------

    private function getReply(string $message, $user): string
    {
        $lower = strtolower($message);

        if ($this->matches($lower, ['hello', 'hi', 'hey', 'help', 'start'])) {
            return "Hello, {$user->name}! 👋 I'm ትሕትና AI, your personal financial assistant.\n\n"
                 . "I can help you with:\n"
                 . "• Account balance & net worth\n"
                 . "• Transaction history\n"
                 . "• Spending patterns & savings tips\n\n"
                 . "What would you like to explore today?";
        }

        if ($this->matches($lower, ['balance', 'net worth', 'total'])) {
            return $this->balanceReply($user);
        }

        if ($this->matches($lower, ['expense', 'spending', 'spent', 'cost'])) {
            return $this->expenseReply($user);
        }

        if ($this->matches($lower, ['income', 'salary', 'earning'])) {
            return $this->incomeReply($user);
        }

        if ($this->matches($lower, ['transaction', 'recent', 'history', 'payment'])) {
            return $this->transactionReply($user);
        }

        if ($this->matches($lower, ['save', 'saving', 'goal', 'budget'])) {
            return $this->savingsReply($user);
        }

        return "That's an interesting question! 🤔\n\n"
             . "I'm best at answering questions about your finances. Try:\n"
             . "  • \"What's my balance?\"\n"
             . "  • \"Show me my expenses\"\n"
             . "  • \"How can I save more?\"\n"
             . "  • \"Recent transactions\"";
    }

    private function matches(string $lower, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (str_contains($lower, $kw)) {
                return true;
            }
        }
        return false;
    }

    // -------------------------------------------------------------------------
    // Reply builders — pull live data from the database
    // -------------------------------------------------------------------------

    private function balanceReply($user): string
    {
        $allIncome   = $user->transactions()->where('type', 'income')->sum('amount');
        $allExpense  = $user->transactions()->where('type', 'expense')->sum('amount');
        $balance     = $allIncome - $allExpense;

        [$monthIncome, $monthExpense] = $this->currentMonthTotals($user);
        $net = $monthIncome - $monthExpense;
        $sign = $net >= 0 ? '+' : '';

        return "Your current net balance is \${$this->fmt($balance)} 💰\n\n"
             . "This month's summary:\n"
             . "  ↑ Income    \${$this->fmt($monthIncome)}\n"
             . "  ↓ Expenses  \${$this->fmt($monthExpense)}\n"
             . "  ✦ Net       {$sign}\${$this->fmt(abs($net))}\n\n"
             . ($net >= 0
                 ? "You're in the green this month. Keep it up!"
                 : "You're spending more than you earn this month. Try reviewing your expenses.");
    }

    private function expenseReply($user): string
    {
        [, $monthExpense] = $this->currentMonthTotals($user);

        $top = $user->transactions()
            ->where('type', 'expense')
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->orderByDesc('amount')
            ->limit(5)
            ->get(['title', 'amount']);

        $lines = $top->map(fn ($t) => "  • {$t->title}  \${$this->fmt($t->amount)}")->implode("\n");

        return "Your monthly expenses are \${$this->fmt($monthExpense)} 📊\n\n"
             . "Top expenses this month:\n{$lines}\n\n"
             . "💡 Review the Home tab for a full breakdown.";
    }

    private function incomeReply($user): string
    {
        [$monthIncome] = $this->currentMonthTotals($user);

        $sources = $user->transactions()
            ->where('type', 'income')
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->orderByDesc('amount')
            ->get(['title', 'amount']);

        $lines = $sources->map(fn ($t) => "  💼  {$t->title}  \${$this->fmt($t->amount)}")->implode("\n");

        return "Your total income this month is \${$this->fmt($monthIncome)} 📈\n\n"
             . "Income sources:\n{$lines}\n\n"
             . "Great job keeping multiple income streams!";
    }

    private function transactionReply($user): string
    {
        $recent = $user->transactions()
            ->orderByDesc('transaction_date')
            ->limit(7)
            ->get(['title', 'amount', 'type', 'transaction_date']);

        $lines = $recent->map(function ($t) {
            $arrow  = $t->type === 'income' ? '↑' : '↓';
            $sign   = $t->type === 'income' ? '+' : '-';
            $date   = \Carbon\Carbon::parse($t->transaction_date)->format('D');
            return "  {$arrow} {$t->title}  {$sign}\${$this->fmt($t->amount)}  {$date}";
        })->implode("\n");

        return "Here are your most recent transactions:\n\n{$lines}\n\n"
             . "View the full list in the Home tab.";
    }

    private function savingsReply($user): string
    {
        [$monthIncome, $monthExpense] = $this->currentMonthTotals($user);
        $saved = max(0, $monthIncome - $monthExpense);
        $pct   = $monthIncome > 0 ? round(($saved / $monthIncome) * 100, 1) : 0;

        return "You're currently saving {$pct}% of your income (\${$this->fmt($saved)}/month) 🎯\n\n"
             . "Quick wins to boost savings:\n"
             . "  → Review subscriptions (cancel unused ones)\n"
             . "  → Meal-prep 2 days/week → saves ~\$30/month\n"
             . "  → Set a weekly discretionary spending cap\n\n"
             . "Target: reaching a 20% savings rate would grow your balance significantly. 💪";
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function currentMonthTotals($user): array
    {
        $base = $user->transactions()
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month);

        return [
            (clone $base)->where('type', 'income')->sum('amount'),
            (clone $base)->where('type', 'expense')->sum('amount'),
        ];
    }

    private function fmt(float $amount): string
    {
        return number_format(abs($amount), 2);
    }
}
