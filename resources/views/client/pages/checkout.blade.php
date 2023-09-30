@extends('client.layout.master')

@section('content')
    <section class="checkout spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6><span class="icon_tag_alt"></span> Have a coupon? <a href="#">Click here</a> to enter your code
                    </h6>
                </div>
            </div>
            <div class="checkout__form">
                <h4>Billing Details</h4>
                <form method="post" action="{{ route('place-order') }}">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="checkout__input">
                                        <p>Name<span>*</span></p>
                                        <input disabled value="{{ Auth::user()->name }}" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Address<span>*</span></p>
                                <input name="address" type="text" placeholder="Street Address"
                                    class="checkout__input__add">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Phone<span>*</span></p>
                                        <input name="phone" value="{{ Auth::user()->phone }}" type="text">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Email<span>*</span></p>
                                        <input disabled value="{{ Auth::user()->email }}" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="checkout__input">
                                <p>Order notes<span>*</span></p>
                                <input name="note" type="text"
                                    placeholder="Notes about your order, e.g. special notes for delivery.">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4>Your Order</h4>
                                <div class="checkout__order__products">Products <span>Total</span></div>
                                <ul>
                                    @php $total = 0 @endphp
                                    @foreach ($cart as $item)
                                        @php $total += $item['price'] * $item['qty'] @endphp
                                        <li>{{ $item['name'] }}
                                            <span>${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="checkout__order__subtotal">Subtotal
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                                <div class="checkout__order__total">Total
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                                <div class="checkout__input__checkbox">
                                    Cash On Delivery
                                    <input name="payment_method" type="radio" id="cod" value="cod">
                                    <span class="cod"></span>
                                </div>
                                <div class="checkout__input__checkbox">
                                    VNPay
                                    <input name="payment_method" type="radio" id="vnpay" value="vnpay">
                                    <span class="vnpay"></span>
                                </div>
                                <button type="submit" class="site-btn">PLACE ORDER</button>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </section>
@endsection
