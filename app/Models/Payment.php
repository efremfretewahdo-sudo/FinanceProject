<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['user_id','member_id','payer_name','amount','payment_date','payment_method','reference','status','notes'];
    protected $casts = ['payment_date' => 'date', 'amount' => 'decimal:2'];

    public function user(): BelongsTo   { return $this->belongsTo(User::class); }
    public function member(): BelongsTo { return $this->belongsTo(Member::class); }

    /**
     * Keep the transactions table in sync with this payment.
     * Creates/updates a transaction when paid, deletes it when unpaid/removed.
     */
    public function syncTransaction(): void
    {
        if ($this->status !== 'paid') {
            Transaction::where('source_type', 'payment')
                       ->where('source_id', $this->id)
                       ->delete();
            return;
        }

        Transaction::updateOrCreate(
            ['source_type' => 'payment', 'source_id' => $this->id],
            [
                'user_id'          => $this->user_id,
                'category_id'      => null,
                'title'            => 'ክፍሊት — ' . $this->payer_name,
                'amount'           => $this->amount,
                'type'             => 'income',
                'description'      => $this->notes ?? ('Member payment: ' . $this->payer_name),
                'transaction_date' => $this->payment_date,
                'source_type'      => 'payment',
                'source_id'        => $this->id,
            ]
        );
    }

    public function removeTransaction(): void
    {
        Transaction::where('source_type', 'payment')
                   ->where('source_id', $this->id)
                   ->delete();
    }
}
