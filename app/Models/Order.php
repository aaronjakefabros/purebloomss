<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $fillable = [
    'user_id',
    'customer_name',
    'customer_email',
    'total_amount',
    'delivery_method',
    
    'house_no',
    'street',
    'barangay',
    'city',
    'province',
    'postal_code',
    'status',
    'payment_method',
    'payment_status',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}