<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $productName = 'Product';
        if ($this->order->product) {
            $productName = $this->order->product->name;
        } elseif ($this->order->messmenu) {
            $productName = $this->order->messmenu->meal_name;
        }

        return [
            'order_id' => $this->order->id,
            'product_name' => $productName,
            'price' => $this->order->sub_total,
            'user_name' => $this->order->orderedBy ? $this->order->orderedBy->name : 'Guest',
            'message' => "Order #{$this->order->id}: {$productName} paid for $" . ($this->order->sub_total ?? 0),
        ];
    }
}
