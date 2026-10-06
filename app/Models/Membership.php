<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_name',
        'price',
        'status',
        'payment_method',
        'razorpay_order_id',
        'razorpay_payment_id',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'price' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->starts_at !== null 
            && ($this->starts_at->isPast() || $this->starts_at->isToday())
            && $this->expires_at !== null 
            && $this->expires_at->isFuture();
    }
}
