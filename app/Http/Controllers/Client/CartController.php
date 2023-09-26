<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart($productId){
        $product = Product::find($productId);
        $cart = session()->get('cart') ?? [];
        $imagesLink = is_null($product->image) || !file_exists('images/' . $product->image) ? 'https://phutungnhapkhauchinhhang.com/wp-content/uploads/2020/06/default-thumbnail.jpg' : asset('images/' . $product->image);
        $cart[$productId] = [
            'name' => $product->name,
            'price' => $product->price,
            'image' => $imagesLink,
            'qty' => ($cart[$productId]['qty'] ?? 0) + 1
        ];
        session()->put('cart', $cart);
        $total_price = $this->calculateTotalPrice($cart);
        $total_items = count($cart);

        return response()->json([
            'message' => 'Add product to cart success', 
            'total_price' => $total_price, 
            'total_items' => $total_items
        ]);
    }

    public function calculateTotalPrice($cart): float{
        $total = 0;
        foreach($cart as $item){
            $total += $item['price'] * $item['qty'];
        }
        return $total;
    }

    public function index(){
        $cart = session()->get('cart') ?? [];
        return view('client.pages.cart', ['cart' => $cart]);
    }

    public function deleteItem($productId){
        $cart = session()->get('cart', []);
        if(array_key_exists($productId, $cart)){
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
        return response()->json(['message' => 'Delete item success']);
    }

    public function updateItem($productId, $qty){
        $cart = session()->get('cart', []);
        if(array_key_exists($productId, $cart)){
            $cart[$productId]['qty'] = $qty;
            if(!$qty){
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }
        $total_price = $this->calculateTotalPrice($cart);
        $total_items = count($cart);
        return response()->json([
            'message' => 'Update item success',
            'total_price' => $total_price, 
            'total_items' => $total_items
        ]);
    }
}
