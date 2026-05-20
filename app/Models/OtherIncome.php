<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherIncome extends Model
{
    protected $fillable = ['user_id','source','amount','income_date','description','category'];
    protected $casts = ['income_date' => 'date', 'amount' => 'decimal:2'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    /**
     * Mirror this other-income entry in the transactions table.
     */
    public function syncTransaction(): void
    {
        Transaction::updateOrCreate(
            ['source_type' => 'other_income', 'source_id' => $this->id],
            [
                'user_id'          => $this->user_id,
                'category_id'      => null,
                'title'            => 'ካልእ ኣታዊ — ' . $this->source,
                'amount'           => $this->amount,
                'type'             => 'income',
                'description'      => $this->description ?? $this->category,
                'transaction_date' => $this->income_date,
                'source_type'      => 'other_income',
                'source_id'        => $this->id,
            ]
        );
    }

    public function removeTransaction(): void
    {
        Transaction::where('source_type', 'other_income')
                   ->where('source_id', $this->id)
                   ->delete();
    }
}
