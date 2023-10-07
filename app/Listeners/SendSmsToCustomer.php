<?php

namespace App\Listeners;

use App\Events\PlaceOrderSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsToCustomer
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
        $phoneNumber = '+84'.$user->phone;

        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $client = new \Twilio\Rest\Client($sid, $token);
        
        // Use the Client to make requests to the Twilio REST API
        $client->messages->create(
            // The number you'd like to send the message to
            '+84352405575',
            [
                // A Twilio phone number you purchased at https://console.twilio.com
                'from' => env('TWILIO_PHONE_NUMBER'),
                // The body of the text message you'd like to send
                'body' => sprintf('Thanks for your purchase ! %s : %s', $order->id, $order->total)
            ]
        );
    }
}
