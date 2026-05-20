<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnpaidItem extends Model
{
    protected $fillable = ['user_id','member_id','debtor_name','amount_due','due_date','description','status'];
    protected $casts = ['due_date' => 'date', 'amount_due' => 'decimal:2'];

    public function user(): BelongsTo   { return $this->belongsTo(User::class); }
    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
}
