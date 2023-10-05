<?php

namespace App\Listeners;

use App\Events\PlaceOrderSuccess;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MinusQtyProduct
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
        $cart = $event->cart;
        foreach($cart as $productId => $item){
            $product = Product::find($productId);
            $product->qty = $product->qty - $item['qty'];
            $product->save();
        }
    }
}
