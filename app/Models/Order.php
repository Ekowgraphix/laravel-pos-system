<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','user_id','status','reject_reason','order_code','count','totalPrice','payment_reference','payment_status','payment_error','paid_at'];

    /**
     * Get the product associated with the order
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who placed the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}