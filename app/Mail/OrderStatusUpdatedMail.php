<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $subjectText = $this->order->delivery_method === 'Pickup' 
            ? 'Pureblooms: Your Order #' . str_pad($this->order->id, 5, '0', STR_PAD_LEFT) . ' is Ready for Pickup!'
            : 'Pureblooms: Your Order #' . str_pad($this->order->id, 5, '0', STR_PAD_LEFT) . ' is Out for Delivery!';

        return $this->subject($subjectText)
                    ->view('emails.order-status-updated');
    }
}