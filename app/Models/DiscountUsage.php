<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountUsage extends Model
{
    use HasFactory;

    protected $fillable = ['discount_code_id', 'user_id', 'order_id', 'discount_amount'];
    protected $casts = ['discount_amount' => 'decimal:2'];

    public function discountCode() { return $this->belongsTo(DiscountCode::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
}
