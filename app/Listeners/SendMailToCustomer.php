<?php

namespace App\Listeners;

use App\Events\PlaceOrderSuccess;
use App\Mail\MailToCustomer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailToCustomer
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
        Mail::to('nguyenlyhuuphucwork@gmail.com')->send(new MailToCustomer($order));
    }
}
