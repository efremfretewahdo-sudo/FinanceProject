<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'google_id', 'avatar', 'email_verified_at', 'is_approved', 'plan_expires_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_approved'       => 'boolean',
            'plan_expires_at'   => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->email === env('ADMIN_EMAIL', 'efremfretewahdo@gmail.com');
    }

    public function isApproved(): bool
    {
        if ($this->isAdmin()) return true;
        if (!(bool) $this->is_approved) return false;
        if ($this->plan_expires_at && $this->plan_expires_at->isPast()) return false;
        return true;
    }

    public function isPlanExpired(): bool
    {
        return !$this->isAdmin() && $this->plan_expires_at && $this->plan_expires_at->isPast();
    }

    public function transactions(): HasMany { return $this->hasMany(Transaction::class); }
    public function categories(): HasMany   { return $this->hasMany(Category::class); }
    public function members(): HasMany      { return $this->hasMany(Member::class); }
    public function payments(): HasMany     { return $this->hasMany(Payment::class); }
    public function unpaidItems(): HasMany  { return $this->hasMany(UnpaidItem::class); }
    public function otherIncomes(): HasMany { return $this->hasMany(OtherIncome::class); }
}
