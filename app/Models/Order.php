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
        'delivery_fee',         // <--- IDINAGDAG
        'phone_number',         // <--- IDINAGDAG
        'house_no',
        'street',
        'barangay',
        'city',
        'province',
        'postal_code',
        'delivery_date',        // <--- IDINAGDAG
        'delivery_time',        // <--- IDINAGDAG
        'status',
        'payment_method',
        'payment_status',
        'order_type',           // <--- IDINAGDAG (para sa Custom Order)
        'custom_message',       // <--- IDINAGDAG (para sa Custom Order details)
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