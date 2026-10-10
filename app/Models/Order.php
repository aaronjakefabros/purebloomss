<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'user_id', 
    'total_amount', 
    'status', 
    'customer_email', 
    'delivery_method',    // <-- Idagdag
    'delivery_fee',       // <-- Idagdag
    'delivery_date',      
    'delivery_time',      
    'delivery_address',   
    'phone_number',       
    'messenger_link',
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