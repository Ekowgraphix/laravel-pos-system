<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlaced extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Confirmation #' . $this->order->order_code)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for your order!')
            ->line("Order Number: **#{$this->order->order_code}**")
            ->line("Total Amount: **{$this->order->totalPrice} MMK**")
            ->line("Quantity: **{$this->order->count}** items")
            ->action('View Order', url('/user/customerOrders/' . $this->order->order_code))
            ->line('We will notify you once your order is processed.');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_code' => $this->order->order_code,
            'total_price' => $this->order->totalPrice,
            'quantity' => $this->order->count,
            'status' => $this->order->status,
        ];
    }
}
