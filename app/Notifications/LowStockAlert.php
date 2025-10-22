<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockAlert extends Notification
{
    use Queueable;

    protected $product;
    protected $alertType;

    public function __construct(Product $product, $alertType = 'low_stock')
    {
        $this->product = $product;
        $this->alertType = $alertType;
    }

    public function via($notifiable)
    {
        $channels = ['database'];
        
        $settings = $notifiable->notificationSettings;
        if ($settings && $settings->email_low_stock) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail($notifiable)
    {
        $subject = match($this->alertType) {
            'out_of_stock' => '🚨 Product Out of Stock',
            'expiring_soon' => '⚠️ Product Expiring Soon',
            'expired' => '❌ Product Expired',
            default => '⚠️ Low Stock Alert',
        };

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("Product: **{$this->product->name}**")
            ->line("Current Stock: **{$this->product->count}** units")
            ->when($this->alertType === 'low_stock', function ($mail) {
                return $mail->line("Threshold: **{$this->product->low_stock_threshold}** units");
            })
            ->when($this->product->expiry_date, function ($mail) {
                return $mail->line("Expiry Date: **{$this->product->expiry_date->format('Y-m-d')}**");
            })
            ->action('View Product', url('/admin/product/list'))
            ->line('Please take action to restock this item.');
    }

    public function toArray($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_stock' => $this->product->count,
            'threshold' => $this->product->low_stock_threshold,
            'alert_type' => $this->alertType,
            'expiry_date' => $this->product->expiry_date,
        ];
    }
}
