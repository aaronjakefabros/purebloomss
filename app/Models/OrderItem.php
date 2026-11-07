<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 
        'product_id', 
        'quantity', 
        'price',
        'color',
        'addons'
    ];

    public function product()
    {
        // withDefault() prevents errors if the product was deleted
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}