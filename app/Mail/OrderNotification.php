<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class OrderNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public $type;

    public function __construct($order, $type)
    {
        $this->order = $order;
        $this->type = $type;
    }

    public function build()
    {
        return $this->subject($this->type == 'user' ? 'Your Order Confirmation' : 'New Order Placed')
            ->html(
                View::make('emails.orders.notification', [
                    'order' => $this->order,
                    'type' => $this->type
                ])->render()
            );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
