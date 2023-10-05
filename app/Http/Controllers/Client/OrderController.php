<?php

namespace App\Http\Controllers\Client;

use App\Events\PlaceOrderSuccess;
use App\Http\Controllers\Controller;
use App\Mail\MailToAdmin;
use App\Mail\MailToCustomer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    public function placeOrder(Request $request){
        try{
            DB::beginTransaction();
            $order = new Order;
            $order->user_id = Auth::user()->id;
            $order->address = $request->address;
            $order->note = $request->note;
            $order->status = Order::STATUS_PENDING;
            $order->save();
          
            $cart = session()->get('cart', []);
            $total = 0;
            foreach($cart as $productId => $item){
                $orderItems = new OrderItem;
                $orderItems->order_id = $order->id;
                $orderItems->product_id = $productId;
                $orderItems->product_name = $item['name'];
                $orderItems->product_price = $item['price'];
                $orderItems->qty = $item['qty'];
                $orderItems->save();
                $total += $item['price'] * $item['qty'];
            }
           
            $order->subtotal = $total;
            $order->total = $total;
            $order->save(); //update id = 10

            //Eloquent - 1
            // $orderPaymentMethod = new OrderPaymentMethod;
            // $orderPaymentMethod->order_id  = $order->id;
            // $orderPaymentMethod->payment_provider = $request->payment_method;
            // $orderPaymentMethod->status = OrderPaymentMethod::STATUS_PENDING;
            // $orderPaymentMethod->total = $order->total; //$total
            // $orderPaymentMethod->save();

            //Eloquent - 2 - Mass Assignment
            $orderPaymentMethod = OrderPaymentMethod::create([
                'order_id' => $order->id,
                'payment_provider' => $request->payment_method,
                'status' => OrderPaymentMethod::STATUS_PENDING,
                'total' => $order->total,
                'note' => 'TESTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT'
            ]);

            //Update 1 eloquent
            $user = User::find(Auth::user()->id);
            $user->phone = $request->phone;
            $user->save();

            //Reset cart 
            session()->put('cart', []);            
            DB::commit();

            if($request->payment_method === 'vnpay'){
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                
                $vnp_TxnRef = $order->id; //Mã giao dịch thanh toán tham chiếu của merchant
                $vnp_Amount = $order->total; // Số tiền thanh toán
                $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
                $vnp_BankCode = 'VNBANK'; //Mã phương thức thanh toán
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán

                $vnp_TmnCode = 'PUEN5D41';
                $vnp_HashSecret = "HOTFMHEKKTGITZXOWUHWZRDRHVSUEXXG"; //Secret key
                $vnp_Returnurl = route('vnpay-callback');
                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

                $startTime = date("YmdHis");
                $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));

                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount* 100,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => "Thanh toan GD:". $vnp_TxnRef,
                    "vnp_OrderType" => "other",
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef,
                    "vnp_ExpireDate"=>$expire
                );

                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }

                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }

                // dd($vnp_Url);
                return Redirect::to($vnp_Url);
            }

            //Create event place order success
            event(new PlaceOrderSuccess($order, $user, $cart));
            
            return redirect()->route('home.index');
        }catch(\Exception $exception){
            DB::rollBack();
            dd($exception->getMessage());
        }
    }

    public function vnpayCallback(Request $request){
        $order = Order::find($request->vnp_TxnRef);
        if($request->vnp_ResponseCode === '00'){
            $order->status = Order::STATUS_SUCCESS;
            $order->save();

            $user = User::find($order->user_id);

            $cart = [];
            foreach($order->order_items as $item){
                $product = Product::find($item->product_id);
                $imagesLink = is_null($product->image) 
                || !file_exists('images/' . $product->image)
                ? 'https://phutungnhapkhauchinhhang.com/wp-content/uploads/2020/06/default-thumbnail.jpg' 
                : asset('images/' . $product->image);
                $cart[$item->product_id] = [
                    'name' => $item->product_name,
                    'price' => $item->product_price,
                    'image' => $imagesLink,
                    'qty' => $item->qty
                ];
            }

            $orderPaymentMethods = $order->order_payment_methods[0];
            $orderPaymentMethods->status = OrderPaymentMethod::STATUS_SUCCESS;
            $orderPaymentMethods->note = $request->vnp_OrderInfo;
            $orderPaymentMethods->total = $request->vnp_Amount;
            $orderPaymentMethods->save();  

            event(new PlaceOrderSuccess($order, $user, $cart));
            $message = 'OK';
        }else{
            $order->status = Order::STATUS_FAILED;
            $order->save();

            $orderPaymentMethods = $order->order_payment_methods[0];
            $orderPaymentMethods->status = 'failed';
            $orderPaymentMethods->note = 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần';
            $orderPaymentMethods->save();  
            $message = 'FAILED';
        }
         
        return redirect()->route('home.index')->with('message', $message);
    }
}
