<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Events\OrderBooked;
use App\Mail\OrderNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderEmails
{

    public function __construct()
    {
        //
    }

    public function handle(OrderBooked $event): void
    {
        $order = $event->order;

        // Step 1: Log when listener is triggered
        Log::info('SendOrderEmails Listener triggered for Order ID: '.$order->id);

        // Step 2: Log order_by / user info
        if ($order->user) {
            Log::info('User email: '.$order->user->email);
        } else {
            Log::warning('User not found or order_by is null for Order ID: '.$order->id);
        }

        // Step 3: Mail to user
        try {
            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)->send(new OrderNotification($order, 'user'));
                Log::info('Mail sent to user: '.$order->user->email);
            }
        } catch (\Exception $e) {
            Log::error('Failed sending mail to user: '.$e->getMessage());
        }

        // Step 4: Mail to admin
        try {
            Mail::to('muhammadawais05152@gmail.com')->send(new OrderNotification($order, 'admin'));
            Log::info('Mail sent to admin');
        } catch (\Exception $e) {
            Log::error('Failed sending mail to admin: '.$e->getMessage());
        }
    }
}
