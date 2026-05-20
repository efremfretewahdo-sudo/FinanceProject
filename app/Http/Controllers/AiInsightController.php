<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiInsightController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate(['period' => 'required|in:weekly,monthly,yearly']);

        $user   = Auth::user();
        $period = $request->period;

        $query = $user->transactions()->with('category');

        if ($period === 'weekly') {
            $query->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()]);
            $periodLabel = 'ናይዛ ሰሙን';
        } elseif ($period === 'monthly') {
            $query->whereMonth('transaction_date', now()->month)->whereYear('transaction_date', now()->year);
            $periodLabel = 'ናይዚ ወርሒ';
        } else {
            $query->whereYear('transaction_date', now()->year);
            $periodLabel = 'ናይዚ ዓመት';
        }

        $transactions = $query->get();
        $income       = (float) $transactions->where('type', 'income')->sum('amount');
        $expenses     = (float) $transactions->where('type', 'expense')->sum('amount');
        $net          = $income - $expenses;
        $count        = $transactions->count();
        $incomeCount  = $transactions->where('type', 'income')->count();
        $expenseCount = $transactions->where('type', 'expense')->count();
        $savingsRate  = $income > 0 ? round(($net / $income) * 100, 1) : 0;
        $expenseRatio = $income > 0 ? round(($expenses / $income) * 100, 1) : 0;

        // Category breakdown (top 3 expense categories)
        $categoryBreakdown = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(fn($g) => [
                'name'    => $g->first()->category?->name ?? 'ዘይተመደበ',
                'total'   => (float) $g->sum('amount'),
                'count'   => $g->count(),
                'percent' => $expenses > 0 ? round(($g->sum('amount') / $expenses) * 100, 1) : 0,
            ])
            ->sortByDesc('total')
            ->values()
            ->take(3)
            ->toArray();

        $topCategory = $categoryBreakdown[0] ?? null;

        // Average transaction value
        $avgIncome  = $incomeCount  > 0 ? round($income  / $incomeCount,  2) : 0;
        $avgExpense = $expenseCount > 0 ? round($expenses / $expenseCount, 2) : 0;

        // Health score (0–100)
        $healthScore = $this->calcHealthScore($savingsRate, $expenseRatio, $count);

        $report = $this->buildReport(
            $periodLabel, $period, $income, $expenses, $net,
            $count, $incomeCount, $expenseCount,
            $savingsRate, $expenseRatio,
            $avgIncome, $avgExpense,
            $categoryBreakdown, $topCategory,
            $healthScore
        );

        return response()->json([
            'report'   => $report,
            'period'   => $period,
            'income'   => $income,
            'expenses' => $expenses,
            'net'      => $net,
        ]);
    }

    private function calcHealthScore(float $savingsRate, float $expenseRatio, int $txCount): int
    {
        $score = 50;
        if ($savingsRate >= 30) $score += 30;
        elseif ($savingsRate >= 20) $score += 22;
        elseif ($savingsRate >= 10) $score += 12;
        elseif ($savingsRate >= 0)  $score += 2;
        else $score -= 20;

        if ($expenseRatio <= 60) $score += 15;
        elseif ($expenseRatio <= 80) $score += 7;
        elseif ($expenseRatio <= 100) $score += 0;
        else $score -= 15;

        if ($txCount >= 5) $score += 5;

        return max(0, min(100, $score));
    }

    private function buildReport(
        string $label, string $period,
        float $income, float $expenses, float $net,
        int $count, int $incomeCount, int $expenseCount,
        float $savingsRate, float $expenseRatio,
        float $avgIncome, float $avgExpense,
        array $categories, ?array $topCat,
        int $healthScore
    ): string {
        if ($income === 0.0 && $expenses === 0.0) {
            return "═══════════════════════════════════════════\n" .
                   "   📊 {$label} ናይ ፋይናንስ ጸብጻብ — ADAM44 AI\n" .
                   "═══════════════════════════════════════════\n\n" .
                   "ኣብ {$label} ዝርዝር ዝተመዝገቡ ምንቅስቓሳት ገንዘብ የለዉን።\n\n" .
                   "💡 ምኽሪ፦ ናይ ኣታዊኻ ከምኡ'ውን ወጻኢኻ ምዝጋብ ጀምር ምእንቲ ዝርዝርን ዓሙቕ ትንተናን ክትረክብ።";
        }

        $fIncome   = number_format($income,   2);
        $fExpenses = number_format($expenses,  2);
        $fNet      = number_format(abs($net),  2);
        $fAvgInc   = number_format($avgIncome,  2);
        $fAvgExp   = number_format($avgExpense, 2);

        $netLabel  = $net >= 0 ? 'ትርፊ (Surplus)' : 'ጉድለት (Deficit)';
        $netEmoji  = $net >= 0 ? '✅' : '⛔';

        // Health score bar
        $filled  = (int) round($healthScore / 5);
        $empty   = 20 - $filled;
        $bar     = str_repeat('█', $filled) . str_repeat('░', $empty);
        $hEmoji  = $healthScore >= 75 ? '🟢' : ($healthScore >= 50 ? '🟡' : '🔴');
        $hLabel  = $healthScore >= 75 ? 'ጽቡቕ ኩነታት'
                 : ($healthScore >= 50 ? 'ማእከላይ ኩነታት' : 'ዘጠንቅቕ ኩነታት');

        // Savings assessment
        $savingsText = match(true) {
            $savingsRate >= 30 => "ዕቑባኻ ካብ ኣታዊኻ {$savingsRate}% ኣብጺሕካ ኣለኻ — እዚ ኣዝዩ ዝነኣድ ስራሕ እዩ። ኣብ ናይ ወፍሪ ወይ ድሕነት ፈንድ ናብ ምስጓም ምሕሳብ ግቡእ እዩ።",
            $savingsRate >= 20 => "ናይ ቁጠባ ሬሾኻ {$savingsRate}% ብ ዝምርቅ ርኢቶ ጽቡቕ ደረጃ ኣብ ምብጻሕ ኢኻ። ቀጺልካ ናብ 30% ናይ ምብጻሕ ዕቑብ ሸቶ ሓዝ።",
            $savingsRate >= 10 => "ናይ ቁጠባ ሬሾኻ {$savingsRate}% ይምዘዝ — ማእከላይ ደረጃ ኣሎካ። ዘይኣድላዪ ወጻኢታት ምቕናስ ናብ ዝበለጸ ኩነታት ክወስደካ ይኽእል።",
            $savingsRate >= 0  => "ናይ ቁጠባ ሬሾኻ {$savingsRate}% ዳርጋ ዜሮ ክኸውን ቀሪቡ — ቅልጡፍ ስጉምቲ ምውሳድ የድሊ። ወጻኢታትካ ምምርማር ግቡእ እዩ።",
            default            => "ወጻኢኻ ካብ ኣታዊኻ ዝበልጽ ኣሎ — ናይ ሕሳብ ጸበባ (financial deficit) ኣለካ። ብዛዕባ ዕዳ ምቁጽጻርን ወጻኢ ምቕናስን ብዝምልከት ቅልጡፍ ስጉምቲ ምውሳድ ኣዝዩ ኣድላዪ እዩ።",
        };

        // Spending pattern
        $spendText = match(true) {
            $expenseRatio <= 50 => "ወጻኢኻ ካብ ኣታዊኻ ዝተሓተ {$expenseRatio}% ጥራሕ እዩ — ናይ ቁጠባ ሓይሊ ምስ ዝለዓለ ናይ ወፍሪ ዕድላት ዘተሓሕዞ ድሕነት ኣሎካ።",
            $expenseRatio <= 70 => "ወጻኢኻ ናይ ኣታዊኻ {$expenseRatio}% ዩ — ዝቐርጸ ምሕደራ ኣሎካ፣ ተቐማጢ ቁጠባ ናብ ምዕባይ ምሕሳብ ክትጅምር ትኽእል ኢኻ።",
            $expenseRatio <= 90 => "ወጻኢኻ ናይ ኣታዊኻ {$expenseRatio}% ስለ ዝምዝምዝ ናይ ቁጠባ ቦታ ጸቢቡ ኣሎ። ዘይኣድላዪ ወጻኢ ምቕናስ ቅኑዕ ስጉምቲ እዩ።",
            $expenseRatio <= 100 => "ወጻኢኻ {$expenseRatio}% ናይ ኣታዊኻ ዩ — ሕጸን ክሳብ ምብጻሕ ቀሪቡ። ናይ ምምሕያሽ ስጉምቲ ሕጂ ምውሳድ ኣዝዩ ኣድላዪ እዩ።",
            default              => "ወጻኢኻ ካብ ኣታዊኻ ዝበልጽ ({$expenseRatio}%) ኣሎ — ናይ ሕሳብ ጸበባ ኣብ ናይ ምቁጽጻር ደረጃ ኣሎ። ቅልጡፍ ስጉምቲ ምውሳድ ወሳኒ እዩ።",
        };

        // Top recommendation
        $advice = match(true) {
            $savingsRate >= 30 => "ቅድሚ ዝተረፈ ናይ ቁጠባ ገንዘብ ናብ ናይ ወፍሪ ሸቶ (investment) ምምሕዛዝ ምሕሳብ ጀምር። ናይ ህጹጽ ሓደጋ ፈንድ (emergency fund) ዝወሓደ ናይ 3–6 ወርሒ ወጻኢ ዝኸውን ምህናጽ ኣዝዩ ኣድላዪ እዩ።",
            $savingsRate >= 10 => "ናይ ምዱብ ወጻኢ ፕላን (budget) ስራሕ — ናይ ኩሉ ወጻኢ ዓይነት ዝርዝር ብምስራሕ ዘይኣድላዪ ወጻኢ ምቕናስ ዕዉት ኩን። 50/30/20 ሕጊ ምጥቃም ሕሰብ (50% ናይ ኣድላዪ ወጻኢ, 30% ድሌት, 20% ቁጠባ)።",
            $savingsRate >= 0  => "ናይ ህጹጽ ሓደጋ ፈንድ ዕቑር ምህናጽ ኣዝዩ ኣድላዪ — ዝወሓደ $500 ዝኸውን ካብ ሕጂ ምቁጥቃጥ ጀምር። ዘይኣድላዪ ወጻኢ ምቕናስን ናይ ኣታዊ ዕድላት ምርካብን ሕሰብ።",
            default            => "ሕጂ ወጻኢኻ ካብ ኣታዊኻ ዝበልጽ ስለ ዘሎ ቅልጡፍ ስጉምቲ ምውሳድ ይሓሸካ። ዘይኣድላዪ ወጻኢ ሙሉእ ምቁጽጻር፡ ናይ ዕዳ ምቁጽጻር ፕላን ምስርሓት፡ ናይ ተወሳኺ ኣታዊ ምንጪ ምርካብ ዝደለዩ ስጉምቲ እዮም።",
        };

        // Category section
        $catSection = '';
        if (!empty($categories)) {
            $catSection = "\n────────────────────────────────────\n";
            $catSection .= "📂 **ናይ ወጻኢ ዓይነታት ምምቃል (Expense Breakdown):**\n\n";
            foreach ($categories as $i => $cat) {
                $rank = ['🥇', '🥈', '🥉'][$i] ?? '  ';
                $catSection .= sprintf("   %s %-20s \$%s  (%s%%)\n",
                    $rank,
                    $cat['name'],
                    number_format($cat['total'], 2),
                    $cat['percent']
                );
            }
        }

        $dateStr = now()->format('d F Y');

        return <<<REPORT
╔═══════════════════════════════════════════╗
║   📊 {$label} ናይ ፋይናንስ ትንተና — ADAM44 AI   ║
╚═══════════════════════════════════════════╝
   ዕለት፦ {$dateStr}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
💰 ናይ ኣታዊ ጠቕላላ (Total Income):    \${$fIncome}
💸 ናይ ወጻኢ ጠቕላላ (Total Expenses):  \${$fExpenses}
{$netEmoji} ጠቕላሊ {$netLabel}:           \${$fNet}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

📊 **ናይ ፋይናንስ ጥዕና ነጥቢ (Financial Health Score):**

   {$hEmoji} {$bar} {$healthScore}/100
   ኩነታት፦ {$hLabel}

────────────────────────────────────
📈 **ናይ ቁጠባ ትንተና (Savings Analysis):**

   ናይ ቁጠባ ሬሾ (Savings Rate):    {$savingsRate}%
   ናይ ወጻኢ ሬሾ (Expense Ratio):   {$expenseRatio}%
   ናይ ኣታዊ ምንቅስቓስ ቁጽሪ:         {$incomeCount} ምዝጋብ
   ናይ ወጻኢ ምንቅስቓስ ቁጽሪ:         {$expenseCount} ምዝጋብ
   ማእከላይ ናይ ኣታዊ መጠን:          \${$fAvgInc}
   ማእከላይ ናይ ወጻኢ መጠን:          \${$fAvgExp}
{$catSection}
────────────────────────────────────
📋 **ዓሙቕ ትንተና ቁጠባ (Savings Deep Dive):**

{$savingsText}

────────────────────────────────────
💳 **ናይ ወጻኢ ቅርጺ ትንተና (Spending Pattern):**

{$spendText}

────────────────────────────────────
🎯 **ናይ ADAM44 AI ምኽሪ (Strategic Recommendation):**

{$advice}

────────────────────────────────────
📌 **ናይ ቀጻሊ ስጉምቲ ዕቅዲ (Action Plan):**

   1. ናይ ኩሉ ወጻኢ ዝርዝር ምስርሓትን ምምርማርን ቀጽሎ
   2. ናይ ዕዮ ግዜ ብጸብጻብ ምቁጽጻር ልምዲ ሓዝ
   3. ናይ ወርሒ ዕቑብ ሸቶ ቅድሚ ዝኾነ ወጻኢ ምቅማጥ
   4. ዘይኣድላዪ ምምልላስ ወጻኢ ምፍላጥን ምቕናስን
   5. ናይ ወፍሪ ዕድላት ብምርምር ናብ ዝለዓለ ዕቑብ ምስጓም

╔═══════════════════════════════════════════╗
║  🤖 ብ ADAM44 AI ዝቐረበ ናይ ፋይናንስ ምኽሪ    ║
║     ዓመት {$dateStr} — Unity Manager Pro    ║
╚═══════════════════════════════════════════╝
REPORT;
    }
}
