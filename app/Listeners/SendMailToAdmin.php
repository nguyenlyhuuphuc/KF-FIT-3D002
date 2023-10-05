<?php

namespace App\Listeners;

use App\Events\PlaceOrderSuccess;
use App\Mail\MailToAdmin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailToAdmin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PlaceOrderSuccess $event): void
    {
        $order = $event->order;
        $user = $event->user;
        Mail::to(config('my-config.admin-email'))->send(new MailToAdmin($order, $user));
    }
}
