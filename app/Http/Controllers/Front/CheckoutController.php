<?php

namespace App\Http\Controllers\Front;

use Exception;
use Carbon\Carbon;
use Session, Auth;
use App\Models\Cart;
use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\State;
use Razorpay\Api\Api;
use App\Models\Coupon;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;

use App\Models\Currency;
use App\Models\Pincodes;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use App\Helpers\EmailHelper;
use App\Models\OrderItemTax;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\WalletHistory;
use App\Models\InvoiceSetting;
use App\Mail\orderSuccessEmail;
use Barryvdh\DomPDF\Facade\Pdf;
use FontLib\TrueType\Collection;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\ProductVariantCombination;
use App\Models\ProductVariantCombinationImage;
use App\Models\{OrderNotifications, OrderAddress, CouponUse};
use App\Service\MailService;

class CheckoutController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function getUserAddress(Request $request, $addressId)
    {

        $address = UserAddress::where('id', $addressId)->first();


        if (!$address) {
            return response()->json(['error' => 'Address not found.'], 404);
        }

        $name = $address->name;
        $parts = explode(' ', $name, 2);
        $firstname = $parts[0];
        $lastname = isset($parts[1]) ? $parts[1] : '';

        return response()->json([
            'country' => $address->country_id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'address' => $address->address,
            'landmark' => $address->landmark,
            'state' => $address->state_id,
            'city' => $address->city_id,
            'city_name' => $address->city->name ?? '', // if you're storing city name
            'pinCode' => $address->postal_code,
            'phone' => $address->phone_number,
            //'address_place_type' => ($address->address_type ==1 ? 'Home' : ($address->address_type ==2? 'Office': 'Others') ),
            'address_place_type' => $address->address_type,
            'address_id' => $addressId,
        ]);

        //echo "<pre>@@".$addressId; print_r($userAddresses); exit;
    }

    public function saveAddress(Request $request)
    {
        $postData = $request->all();
        $user = Auth::guard('customer')->user();
        $address = new UserAddress();
        $address->type = $postData['address_type'];
        $address->user_id = $user->id;
        $address->name = $postData['firstname'] . ' ' . $postData['lastname'];
        $address->email = $user->email;
        $address->phone_number = $postData['phone'];
        $address->alternate_number = $postData['phone'];
        $address->country_id = $postData['country'];
        $address->state_id = $postData['state'];
        $address->city_id = $postData['city'];
        $address->postal_code = $postData['pinCode'];
        $address->landmark = $postData['addressSecond'];
        $address->address = $postData['address'];
        $address->address_type = $postData['address_place_type'];
        $address->save();


        return response()->json([
            'success' => true,
            'message' => 'Your address has been successfully saved.',
            'redirect_url' => url('/checkout')
        ]);
    }

    public function updateAddress(Request $request)
    {
        $user = Auth::guard('customer')->user();

        $postData = $request->all();
        if(isset($postData['addressId']) && !empty($postData['addressId'])){
            $address = UserAddress::where('id', $postData['addressId'])->firstOrFail();
        } else {
            $address = new UserAddress();
        }
        $address->name = $postData['firstname'] . ' ' . $postData['lastname'];
        $address->type = $postData['type'];
        $address->user_id = $user->id;
        $address->email = $user->email;
        $address->phone_number = $postData['phone'];
        $address->alternate_number = $postData['phone'];
        $address->country_id = $postData['country'];
        $address->state_id = $postData['state'];
        $address->city_id = $postData['city'];
        $address->postal_code = $postData['pinCode'];
        $address->landmark = $postData['landmark'] ?? null;
        $address->address = $postData['address'];
        $address->address_type = $postData['address_place_type'];
        $address->save();

        return response()->json(['success' => true, 'message' => 'Address updated successfully.']);
    }

    public function placeOrder(Request $request)
    {
        $resp = [];
        info("-----------request-all--------",[$request->all()]); 
        // Backend Order validation 
        $login_user_id =  Auth::guard('customer')->user()->id;
        $userAvailableWalletAmount = $this->getuserWallet($login_user_id);

        $codMaxLimit = 12000;
        $invoiceSetting = InvoiceSetting::where('prefix', 'site')->first();
        if(!empty($invoiceSetting)){
            $codMaxLimit = $invoiceSetting->cash_on_limit;
        }

        $validation_error = '';
        if(count($request->cartItems)==0){
            $validation_error .= " </br> You should be alteast add 1 item in cart";
        } 
        if($request->sub_total < 1 && $request->payment_mode != 'wallet'){
            $validation_error .= " </br>Your order amount should be greater than zero";
        }
        
        if($request->wallet_amount > $userAvailableWalletAmount){
            $validation_error .= " </br> You dont have exact amount in wallet";
        } 
        if($request->payment_mode =='cod' && $request->sub_total > $codMaxLimit){
            $validation_error .= " </br> You have reached maximum amount for COD order.";
        } 

        if(!empty($request->cartItems)){
            foreach($request->cartItems as $cart){
                $productId = $cart['product_id'];
                $quantity = $cart['quantity'];

                $product = Product::where('id', $productId)->first();
                if(!empty($product)){
                    if($product->in_stock == 0){
                        $validation_error .= ' </br> You have some out of stock product into order items';
                    }

                    $product_valient_sku = '';
                    if(!empty($product_sku = $product->sku)){
                        $product_valient_sku .= strtolower($product_sku = $product->sku);
                    }
                    if(!empty($request->cartItems['selectedVariants']) && !empty($request->cartItems['selectedVariants']['colour'])){
                        $product_valient_sku .= '_'. strtolower($request->cartItems['selectedVariants']['colour']);
                    }
                    if(!empty($request->cartItems['selectedVariants']) && !empty($request->cartItems['selectedVariants']['size'])){
                        $product_valient_sku .= '_'. strtolower($request->cartItems['selectedVariants']['size']);
                    }
                    $qtyVarient = ProductVariantCombination::where('product_id', $productId)->where('sku', $product_valient_sku)->select('qty')->first();
                    if(!empty($qtyVarient)){
                        if($qtyVarient->qty==0){
                            $validation_error .= ' </br> You have some out of stock product varient into order items';
                        } else if($qtyVarient->qty < $quantity){
                            $validation_error .= ' </br> Product does not have suffcient quantity.';
                        }
                    }
                    if(!empty($product->max_selling_units) && $product->max_selling_units!=0 &&  $quantity > $product->max_selling_units){
                        $validation_error .= ' </br> You have reached maximum quantity for product.';
                    }

                } else {
                    $validation_error .= ' </br> Your order product does not activated';
                }
            }
        }

        // Apply Coupon VAlidation 
        if(!empty($request->coupon_id)){
            $couponDetail = Coupon::where('id', $request->coupon_id)->where('is_active', 1)->first();
            if(empty($couponDetail)){
                    $validation_error .= ' </br> Your coupon does not activated';
            } else {
                $couponStatus = $this->applyCoupon($couponDetail->coupon_code, $request->sub_total, $request->cart_items);
                if($couponStatus->original['status']==false){
                    $validation_error .=   " </br> " . $couponStatus->original['message'];
                }
                // Apply Coupon VAlidation 
            }
        }

        if (!empty($validation_error)) {
            $responseError['success'] = false;
            $responseError['data'] = [];
            $responseError['product_order_id'] = false;
            $responseError['message'] = $validation_error;
            return response()->json($responseError);
        }
        // Backend Order validation 

        

        $ip_address = $request->ip(); 
        if ($request->payment_mode == 'cod') {
            $order_id = $this->createOrder(
                $ip_address,
                'cod',
                $request->sub_total,
                'unpaid',
                $request->coupon_id,
                $request->coupon_discount,
                0,
                $request->shippingcharge,
                $request->billing_id,
                $request->shipping_id,
                $request->cartItems
            );
            $resp['success'] = true;
            $resp['message'] = "Success";
            $resp['url'] = route('front-order.success', ['order_id' => encrypt($order_id)]);
            return response()->json($resp);
        } elseif ($request->payment_mode == 'razorpay') {
            $cartNotes = $request->cartItems;
            session()->put('cart_items', $cartNotes);
            $post_fields = [
                "amount" => $request->sub_total * 100,
                "currency" => "INR",
                "receipt" => "Receipt",
                "partial_payment" => false,
                "first_payment_min_amount" => 200,
                "notes" => [
                    "coupon_id"         => $request->coupon_id,
                    "coupon_discount"   => $request->coupon_discount,
                    "shippingcharge"    => $request->shippingcharge,
                    "billing_id"        => $request->billing_id,
                    "shipping_id"       => $request->shipping_id,
                    "wallet_amount"   => $request->wallet_amount,
                ]
            ];

            $order_url = env('RAZORPAY_DEFAULT_URL') . "orders";
            $order = $this->callApi($order_url, $post_fields, 'razorpay');
            if (isset($order['error'])) {
                $resp['success'] = false;
                $resp['data'] = [];
                $resp['product_order_id'] = false;
                $resp['message'] = $order['error']['description'];
            } else {
                $resp['success'] = true;
                $resp['data'] = $order;
                $resp['product_order_id'] = false;
                $resp['message'] = "Success";
            }

            return response()->json($resp);
        } elseif ($request->payment_mode == 'wallet') {
            $order_number = $this->random_strings(8);

            $order_id = $this->createOrder($ip_address,$request->payment_mode, $request->wallet_amount, 'received', $request->coupon_id, $request->coupon_discount, $request->wallet_amount, $request->shippingcharge, $request->billing_id, $request->shipping_id, $request->cartItems);
            Order::find($order_id)->update([
                'payment_status'      => 'paid',
            ]);
            $resp['success'] = true;
            $resp['url'] = route('front-order.success', ['order_id' => encrypt($order_id)]);
            $resp['message'] = "Success";
            return response()->json($resp);
        }
    }

    public function checkout_callback(Request $request)
    {
        $razorpayOrderId = $request->razorpay_order_id;
        $ip_address = $request->ip(); 
        // Call Razorpay to get all payments for this order
        $paymentsResponse = $this->callApi(
            "https://api.razorpay.com/v1/orders/{$razorpayOrderId}/payments",
            [],
            'razorpay'
        );

        Log::info('Payment Response');
        Log::info($paymentsResponse);

        $latestPayment = $paymentsResponse['items'][0];
        if ($latestPayment['status'] === 'authorized' && !$latestPayment['captured']) {
            $paymentsResponse = $this->callApi(
                "https://api.razorpay.com/v1/payments/{$latestPayment['id']}/capture",
                [
                    'amount' => $latestPayment['amount'],
                    'currency' => $latestPayment['currency']
                ],
                'razorpay',
                'POST'
            );
        }

        if (!empty($paymentsResponse['items'])) {
            // Filter only successful payments
            $successfulPayments = array_filter($paymentsResponse['items'], function ($payment) {
                return $payment['status'] === 'captured' && $payment['captured'] === true;
            });
            // Get the latest successful payment
            $latestPayment = null;
            if (!empty($successfulPayments)) {
                usort($successfulPayments, function ($a, $b) {
                    return $b['created_at'] <=> $a['created_at'];
                });
                $latestPayment = $successfulPayments[0];
            }

            if ($latestPayment) {
                $wAmount = $latestPayment['notes']['wallet_amount'] ?? 0;
                $payAmount = $latestPayment['amount'] / 100;
                $totalAmt = $payAmount + $wAmount;
                // Create the order in Laravel
                $order_id = $this->createOrder(
                    $ip_address,
                    'razorpay',
                    $totalAmt,
                    'paid',
                    $latestPayment['notes']['coupon_id'] ?? null,
                    $latestPayment['notes']['coupon_discount'] ?? 0,
                    $wAmount,
                    $latestPayment['notes']['shippingcharge'] ?? 0,
                    $latestPayment['notes']['billing_id'] ?? null,
                    $latestPayment['notes']['shipping_id'] ?? null,
                    session()->get('cart_items')
                );


                // Save payment ID
                Order::find($order_id)->update([
                    'razorpay_payment_id' => $latestPayment['id'],
                    'payment_status'      => 'paid',
                ]);

                session()->forget('cart_items');

                return redirect()->route('front-order.success', ['order_id' => encrypt($order_id)]);
            }
        }

        return redirect()->back();
    }


    public function createOrder($ip_address,$payment_method, $amount, $order_status, $coupon_id, $coupon_discount, $wallet_amount = 0, $shippingcharge, $billingId, $shippingId, $checkout_data)
    {

        $debitFromWallet = 0;
        $user_id =  Auth::guard('customer')->user()->id;
        $customerName = Auth::guard('customer')->user()->name ?? 'Customer';
        $customerEmail = Auth::guard('customer')->user()->email ?? 'Customer';

        $shipping_id = $billing_id = null;
        $shippingItems = [];
        $total_discount = 0;

        if (isset($billingId)) {
            $billing_id = $billingId;

            $billAddress = UserAddress::getShippingAddress('billing', $billing_id);
        }
        if (isset($shippingId)) {
            $shipping_id = $shippingId;
            $shipAddress = UserAddress::getShippingAddress('shipping', $shipping_id);
        }

        $taxPrice = 0;
        foreach ($checkout_data as $item) {
            $taxPrice += $item['tax_price'];
        }

        $order = new Order;

        $discount = 0;

        $coupon = Coupon::find($coupon_id);
        $couponCategoryId = 0;
        $couponSubCategoryIds = null;

        if (!empty($coupon)) {
            $couponCategoryId = $coupon->category_id ?? 0; // 0 for all
            $couponSubCategoryIds = !empty($coupon->sub_categories) ? array_filter(json_decode($coupon->sub_categories, true)) : null;
            $couponChildCategoryIds = !empty($coupon->child_category) ? array_filter(json_decode($coupon->child_category, true)) : null;
            $order->coupon_name = $coupon->coupon_code;
            $order->coupon_discount = round($coupon_discount, 2);
            $discount  = round($coupon_discount, 2);
        }

        // $order->order_number = $this->random_strings(8);
        $order->order_number = generateOrderNumber();
        $ordernumber = $order->order_number;
        $order->user_id = $user_id;
        $order->billing_address = json_encode($billAddress);
        $order->shipping_address = json_encode($shipAddress);
        $order->shippingcharge = floor($shippingcharge);
        //$order->sub_total = floor($amount + $discount - ($taxPrice + $coupon_discount)); 
        $order->sub_total = floor($amount + $discount);

        $order->total = floor($amount);
        $order->payment_method = $payment_method;
        $order->payment_status = $order_status;
        $order->payment_status = 'pending';
        $order->ip_address = $ip_address;
        $order->save();

        $this->mailService->orderAccepted($order,$checkout_data);

        $order_id = $order->id;
        if (!empty($coupon)) {
            CouponUse::create([
                'user_id' => $user_id,
                'coupon_id' => $coupon_id,
                'order_id' => $order->id,
            ]);
            if ($coupon->available_coupons) {
                $coupon->decrement('available_coupons');
                $coupon->save();
            }
        }

        $productIds = collect($checkout_data ?? [])->pluck('productId')?->toArray();
        $productQty = collect($checkout_data ?? [])->pluck('quantity', 'productId')?->toArray();

        if (!empty($productIds)) {
            $allProducts = Product::query()
                ->whereIn('id', $productIds)
                ->select('id', 'main_category_id', 'main_sub_category_id')->get()
                ->map(function ($product) {
                    return $product->only(['id', 'main_category_id', 'main_sub_category_id']);
                })->keyBy('id')->toArray();
        } else {
            $allProducts = [];
        }


        // check eligibile items quantity for coupon/discount
        $couponAppliedQty = 0;
        foreach ($checkout_data as $item) {
            $applyDiscount = false;
            $productCategoryId = $allProducts[$item['product_id']]['main_category_id'] ?? 0;
            $productSubCategoryId = $allProducts[$item['product_id']]['main_sub_category_id'] ?? 0;
            $productChildCategoryId = $allProducts[$item['product_id']]['main_child_category_id'] ?? 0;
            if ($couponCategoryId == 0) {
                $applyDiscount = true;
            } elseif ($productCategoryId == $couponCategoryId) {
                if (empty($couponSubCategoryIds)) {
                    $applyDiscount = true;
                } elseif (in_array($productSubCategoryId, $couponSubCategoryIds)) {
                    if(empty($couponChildCategoryIds)){
                        $applyDiscount = true;
                    } elseif (in_array($productChildCategoryId, $couponChildCategoryIds)){
                        $applyDiscount = true;
                    }
                }
            }
            if ($applyDiscount) {
                $couponAppliedQty += $productQty[$item['product_id']] ?? 0;
            }
        }

        foreach ($checkout_data as $item) {

            $applyDiscount = false;
            $productCategoryId = $allProducts[$item['product_id']]['main_category_id'] ?? 0;
            $productSubCategoryId = $allProducts[$item['product_id']]['main_sub_category_id'] ?? 0;
            if ($couponCategoryId == 0) {
                $applyDiscount = true;
            } elseif ($productCategoryId == $couponCategoryId) {
                if (empty($couponSubCategoryIds)) {
                    $applyDiscount = true;
                } elseif (in_array($productSubCategoryId, $couponSubCategoryIds)) {
                    if(empty($couponChildCategoryIds)){
                        $applyDiscount = true;
                    } elseif (in_array($productChildCategoryId, $couponChildCategoryIds)){
                        $applyDiscount = true;
                    }
                }
            }
            $discountAmt = ($applyDiscount && $couponAppliedQty) ? round($coupon_discount / $couponAppliedQty, 2) : 0;
            $total_discount = $discountAmt * $item['quantity'];

            $order_items = new OrderItem;
            $order_items->order_id = $order_id;
            $order_items->product_id = $item['product_id'];
            $order_items->qty = $item['quantity'];
            $order_items->mrp = $item['price'];
            $order_items->selling_price = $item['sellingPrice'];
            $order_items->discount_amount = $total_discount;
            $order_items->tax_amount = $item['tax_price'];
            $order_items->sub_total = (($item['quantity'] * $item['sellingPrice']) - ($item['tax_price'] +  $total_discount));
            $order_items->total = (($item['quantity'] * $item['sellingPrice']) - $total_discount);
            $order_items->combination =  json_encode($item['selectedVariants']);
            $order_items->order_status_id = 1; // Pending
            $order_items->status = 'pending';
            $order_items->save();

            $order_items_tax = new OrderItemTax;
            // $order_items_tax->order_item_id = $order_id;
            $order_items_tax->order_item_id = $order_items->id;
            $order_items_tax->category_tax_id = $item['tax_id'];
            $order_items_tax->tax_val = (isset($item['tax_rate']) && !empty($item['tax_rate'])) ? $item['tax_rate'] : "";
            $order_items_tax->tax_price = $item['tax_price'];
            $order_items_tax->save();

            $orderStatusHistory = new OrderStatusHistory();
            $orderStatusHistory->user_id = $user_id;
            $orderStatusHistory->order_id = $order_id;
            $orderStatusHistory->order_status_id = 2;
            $orderStatusHistory->order_item_id = $order_items->id;
            $orderStatusHistory->order_status = 'pending';
            $orderStatusHistory->save();

            $orderitemvari = json_decode($order_items->combination, true);
            $valueIds = [];

            foreach ($orderitemvari as $variantName => $variantValue) {

                $valueId = DB::table('variant_values')
                    ->where('name', $variantValue)
                    ->value('id');
                $valueIds[] = $valueId;
            }
            $combinations = json_encode($valueIds);

            $productids = $order_items->product_id;
            $productcomb = ProductVariantCombination::where('combination_id', $combinations)->where('product_id', $productids)->first();
            if ($productcomb) {
                $productcomb->qty = $productcomb->qty - $order_items->qty;
                $productcomb->save();
                $order_items->product_variant_combination_id = $productcomb->id; // Pending
                $order_items->save();
            }
            $product = Product::where('id', $productids)->first();
            $product->qty = $product->qty - $order_items->qty;
            $product->save();
        }

        if ($wallet_amount > 0) {
            $walletHistory = new WalletHistory();
            $walletHistory->user_id = $user_id;
            $walletHistory->amount = $wallet_amount;
            // $walletHistory->amount = 5.00;
            $walletHistory->type = 'debit';
            $walletHistory->transaction_id = $ordernumber;
            $walletHistory->save();

            $userwallet = User::where('id', $user_id)->first();
            $userwallet->wallet_avl_balance = $userwallet->wallet_avl_balance - $wallet_amount;
            $userwallet->save();
        }

        // Notification bell
        $orderNotifications = new OrderNotifications();
        $orderNotifications->user_id = '1';
        $orderNotifications->order_id = $order_id;
        $orderNotifications->title = 'New Order Created';
        $orderNotifications->message = 'A new order has been placed with Order ID: ' . $order_id;
        $orderNotifications->save();

        Cart::where('user_id', $user_id)->delete();

        //Generating Invoice
        $invoicePath = $this->generateInvoiceAmit($checkout_data, $order_id);

        Order::where('id', $order_id)->update(['invoice_path' => $invoicePath]);

        $orderDetails = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">
                            <tr>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Total Price</th>
                                <th>Combination</th>
                            </tr>';

        foreach ($checkout_data as $item) {
            $applyDiscount = false;
            $productCategoryId = $allProducts[$item['product_id']]['main_category_id'] ?? 0;
            $productSubCategoryId = $allProducts[$item['product_id']]['main_sub_category_id'] ?? 0;
            if ($couponCategoryId == 0) {
                $applyDiscount = true;
            } elseif ($productCategoryId == $couponCategoryId) {
                if (empty($couponSubCategoryIds)) {
                    $applyDiscount = true;
                } elseif (in_array($productSubCategoryId, $couponSubCategoryIds)) {
                    $applyDiscount = true;
                }
            }
            $discountAmt = ($applyDiscount && $couponAppliedQty) ? round($coupon_discount / $couponAppliedQty, 2) : 0;
            $total_discount = $discountAmt * $item['quantity'];
            $variants = [];
            // foreach ($item['selectedVariants'] as $key => $val) {
            //     $variants[] = ucfirst($key) . ': ' . $val;
            // }
            // $orderDetails .= '<tr>
            //                     <td><img src="' . $item['image'] . '" width="90" alt="' . $item['name'] . '"></td>
            //                     <td>' . $item['name'] . '</td>
            //                     <td>' . $item['quantity'] . '</td>
            //                     <td>' . (($item['quantity'] * $item['sellingPrice']) - $total_discount) . '</td>
            //                    <td>' . implode(', ', $variants) . '</td>
            //                 </tr>';
        }

        // $orderDetails .= '</table>';


        // 3. Placeholder Data
        // $data = [
        //     'CUSTOMER_NAME' => $customerName,
        //     'ORDER_ID'      => $ordernumber,
        //     'ORDER_DETAILS' => $orderDetails,
        // ];

        // 4. Get Processed Template
        // $template = EmailHelper::getProcessedTemplate('order-success', $data);

        // 5. Send Email
        // Mail::to($customerEmail)->send(new orderSuccessEmail($template['subject'], $template['body']));

        return $order_id;
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response()->json(['error' => $e->getMessage()], 500); // Return error response
        // }
    }

    public function generateInvoiceAmit($checkout_data, $order_id)
    {

        return '';
        $productIds = array_column($checkout_data, 'productId');
        $skus = Product::whereIn('id', $productIds)->pluck('sku', 'id'); // returns [id => sku]
        $hsns = Product::whereIn('id', $productIds)->pluck('hsn', 'id'); // returns [id => sku]
        foreach ($checkout_data as &$item) {
            $productId = $item['productId'];
            $item['sku'] = $skus[$productId] ?? null; // null if productId not found
            $item['hsn'] = $hsns[$productId] ?? null; // null if productId not found
        }
        $order = Order::where('orders.id', $order_id)->leftJoin('users', 'users.id', 'orders.user_id')->select('orders.*', 'users.name as user_name', 'users.email as user_email')->first();
        $GSTIN = config('Site.GSTIN');
        $currency = Currency::where('currency_code', $order->currency_code)->value('symbol');
        $supplySetting = InvoiceSetting::with(['country', 'state', 'city'])
            ->where('is_active', 1)
            ->first();

        $randonNum = rand(1000, 9999);
        $pdf = PDF::loadView('invoices.order_invoice_amit', ['checkout_data' => $checkout_data, 'order' => $order, 'currency' => $currency, 'GSTIN' => $GSTIN, 'supplySetting' => $supplySetting]);
        $path = Config('constant.ORDER_INVOICE_ROOT_PATH') . $order_id . $randonNum . "_invoice.pdf";
        $invoiceDirectory = Config('constant.ORDER_INVOICE_ROOT_PATH');
        if (!file_exists($invoiceDirectory)) {
            mkdir($invoiceDirectory, 0755, true);
        }
        $pdf->save($path);
        return $path;
    }




    public function getShippingData(Request $request)
    {
        $addressId = $request->shippingId;
        $totalAmountcart = $request->totalAmount;

        $address = UserAddress::getShippingAddress('shipping', $addressId);
        $pincode = $address['shipping_pincode'];

        if(!empty($request->productData)){
            $productIds = array_column($request->productData, 'productId');
            $product = Product::whereIn('id', $productIds)->select('weight', 'weight_type')->get();
            $totalWeight = 0;
            foreach ($product as $products) {
                if(!empty($products->weight)){
                    $weight = @$products->weight;
                    if ($products->weight_type == 'kg') {
                        $weight = @$products->weight * 1000;
                    }
                    $totalWeight += @$weight;
                }
            }
        }

        $productweight = $totalWeight ?? 250;
        $shippingPrice =  getShippingCharge($pincode, $productweight, $totalAmountcart);
        return response()->json([
            'success' => true,
            'message' => 'Shipping Price fetched successfully.',
            'shippingPrice' => $shippingPrice,
            'data' => [
                $pincode,
                $productweight,
                $totalAmountcart
            ]
        ]);
    }

    public function getLocationByPincode($pincode)
    {
        $pin = Pincodes::where('pincode', $pincode)->first();

        if (!$pin) {
            return response()->json(['error' => 'Invalid pincode'], 404);
        }

        // get related data by ID
        $city = City::find($pin->city_id);
        $state = State::find($pin->state_id);
        $country = Country::find($pin->country_id);

        return response()->json([
            'city_id' => $city->id ?? null,
            'city_name' => $city->name ?? $pin->district ?? '',
            'state_id' => $state->id ?? null,
            'state_name' => $state->name ?? $pin->statename ?? '',
            'country_id' => $country->id ?? null,
            'country_name' => $country->name ?? '',
        ]);
    }

    public function getuserWallet($userId)
    {
        $userwallet = 0;
        $userwalletArr = User::where('id', $userId)->first();
        if(!empty($userwalletArr)){
            $userwallet = $userwalletArr->wallet_avl_balance;
        }
        return $userwallet;
    }

    public function applyCoupon($coupon_code=null, $cart_total=null, $cart_items=null)
    {
        $userId =  Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;

        $coupon = Coupon::where('coupon_code', $coupon_code)
            ->where('is_active', 1)
            ->where(function ($query) {
                $query->where('end_date', '>=', Carbon::now())
                    ->orwhereNull('end_date');
            })
            ->where(function ($query) use ($userId) {
                $query->where('start_date', '<=', Carbon::now())
                    ->orwhereNull('start_date');
            })->with('coupon_user')
            ->first();
        if (!$coupon) {
            return response()->json(['status' => false, 'message' => 'Invalid or inactive coupon.']);
        }
        $checkCategory = $coupon->category_id;
        $checkSubCat   = !empty($coupon->sub_categories) ? array_filter(json_decode($coupon->sub_categories, true)) : null;
        if ($checkCategory || $checkSubCat) {
            $productIds = collect($cart_items)
                ->pluck('productId')
                ->unique()
                ->values()
                ->toArray();

            $products = Product::whereIn('id', $productIds)
                // ->where('is_public', 1)
                ->where('draf', 0)
                ->get(['id', 'main_category_id', 'main_sub_category_id']);

            if ($products->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'Cart contains no valid public products.']);
            }

            $productCategoryIds    = $products->pluck('main_category_id')->unique()->toArray();
            $productSubCategoryIds = $products->pluck('main_sub_category_id')->unique()->toArray();

            // Category check
            if ($checkCategory && !in_array($coupon->category_id, $productCategoryIds)) {
                return response()->json(['status' => false, 'message' => 'Coupon is not valid for the selected product category.']);
            }

            // Subcategory check           
            if ($checkSubCat) {
                $allowedSubCats = is_array($coupon->sub_categories)
                    ? $coupon->sub_categories
                    : array_filter(json_decode($coupon->sub_categories, true));

                $intersect = array_intersect($allowedSubCats, $productSubCategoryIds);

                if (empty($intersect)) {
                    return response()->json(['status' => false, 'message' => 'Coupon is not valid for the selected subcategories.']);
                }
            }
        }

        if ($coupon->user_type && $coupon->user_type !== 'all' && $userId) {
            if (!$userId) {
                return response()->json(['status' => false, 'message' => 'Login required to apply this coupon.']);
            }

            $userOrderCount = Order::where('user_id', $userId)->count();

            if ($coupon->user_type === 'new' && $userOrderCount > 0) {
                return response()->json(['status' => false, 'message' => 'This coupon is only valid for new users.']);
            }

            if ($coupon->user_type === 'existing' && $userOrderCount === 0) {
                return response()->json(['status' => false, 'message' => 'This coupon is only valid for existing users.']);
            }
        }
        if ($userId && $coupon->per_user_avalibity > 0) {
            $perUserUsed = CouponUse::where('user_id', $userId)->where('coupon_id', $coupon->id)->count();
            if ($coupon->per_user_avalibity <= $perUserUsed) {
                return response()->json(['status' => false, 'message' => 'Coupon limit reached.']);
            }
        }
        // Date validity check
        $today = now()->toDateString();
        $formattedDate = Carbon::parse($coupon->start_date)->format('Y-m-d');
        $formattedEnd = Carbon::parse($coupon->end_date)->format('Y-m-d');
        if ($coupon->start_date && $formattedDate > $today) {
            return response()->json(['status' => false, 'message' => 'Coupon not started yet.']);
        }

        if ($coupon->end_date && $formattedEnd < $today) {
            return response()->json(['status' => false, 'message' => 'Coupon expired.']);
        }

        // Limit check
        if (!$coupon->is_unlimited && $coupon->available_coupons <= 0) {
            return response()->json(['status' => false, 'message' => 'Coupon limit reached.']);
        }

        // Private user check
        if ($coupon->coupon_type === 'private') {
            if (!$userId) {
                return response()->json(['status' => false, 'message' => 'Login required to apply this coupon.']);
            }
            $couponUsers = $coupon?->coupon_user?->pluck('user_id')?->toArray() ?? [];
            if (!in_array($userId, $couponUsers)) {
                return response()->json(['status' => false, 'message' => 'You are not allowed to use this private coupon.']);
            }
            /* if (!$request->user_id || !$coupon->customers->contains($request->user_id)) {
                return response()->json(['status' => false, 'message' => 'You are not allowed to use this coupon.']);
            } */
        }


        // Min cart value check
        if ($coupon->min_cart_value && $cart_total < $coupon->min_cart_value) {
            return response()->json(['status' => false, 'message' => 'Cart total is below the minimum required for this coupon.']);
        }

        // Discount calculation
        $discount = 0;
        $discount = $coupon->discount_type === 'flat'
            ? $coupon->discount_value
            : ($cart_total * $coupon->discount_value) / 100;

        // Apply min and max caps (if set)
        if ($coupon->max_discount) {
            $discount = min($discount, $coupon->max_discount);
        }
        if ($coupon->min_discount) {
            $discount = max($discount, $coupon->min_discount);
        }

        return response()->json([
            'status'   => true,
            'message'  => 'Coupon applied successfully.',
            'discount' => round($discount, 2),
            'coupon'   => $coupon->only('id', 'coupon_code', 'discount_type', 'discount_value'),
        ]);
    }

    public function orderCancelled(Request $request){       
        $request->validate([
            'orderId' => 'required|integer|exists:orders,id',
        ]);
 
        $orderStatusArray = ['Shipped','out-for-delivery','in-transit','Delivered'];
        $order = OrderItem::with('order')->where('order_id', $request->orderId)->firstOrFail();
        if(in_array($order->order->status,$orderStatusArray)){
            return response()->json([
                'status'=>true,
                'message'=>'Your Order is in '.$order->status.' So You can not cancel this order Now'
            ]);
        }
        else{
            if($order->order->payment_method == "cod" && $order->order->payment_status == "unpaid"){
                Order::where('id',$request->orderId)->update([
                    'status'=>'cancelled'
                ]);
 
                OrderItem::where('order_id',$request->orderId)->update([
                    'status'=>'cancelled'
                ]);
                $userData = User::where('id',$order->order->user_id)->first();
                $orderData = OrderItem::with('order')->where('order_id', $request->orderId)->get();
                info("----userData-----",["UserData:",$userData, "orderData:",$orderData]);
                $this->mailService->orderCancelled($userData,$orderData);
                return response()->json([
                    'status'=>true,
                    'message'=>"Your Order is successfully Cancelled"
                ]);
            }
            else{
                if(($order->order->payment_method == "wallet" || $order->order->payment_method == "razorpay") && $order->order->payment_status == "paid"){
 
                    $userData = User::where('id',$order->order->user_id)->first();
                    $newAmount = $order->order->total + $userData->wallet_avl_balance;
                   
                    User::where('id',$userData->id)->update([
                        'wallet_avl_balance'=>$newAmount
                    ]);
                     $orderData = OrderItem::with('order')->where('order_id', $request->orderId)->get();
                    $this->mailService->orderCancelled($userData,$orderData);
                   
                    $history = new WalletHistory ;
                    $history->user_id = $userData->id;
                    $history->order_id = $order->order->id;
                    $history->amount = $order->total;  
                    $history->gst_amount = 0.00 ;
                    $history->type = "credit";
                    $history->transaction_id = null;
                    $history->save();
 
                    DB::Transaction(function() use ($order){
                        Order::where('id',$order->order->id)->update([
                            'status'=>'cancelled'
                        ]);
 
                        OrderItem::where('order_id',$order->order->id)->update([
                            'status'=>'cancelled'
                        ]);
                    });
                }
               
            }
        }
    }
}
