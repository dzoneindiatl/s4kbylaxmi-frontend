<?php

namespace App\Http\Controllers\Front;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\ProductVariantCombination;
use App\Models\ProductVariantCombinationImage;
use App\Models\PaymentMethod;
use Session;
use Ixudra\Curl\Facades\Curl;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
class PaymentController extends Controller
{
    
    // public function checkout_callback(Request $request)
    // {
    //     $postData = $request->all();
    // //     $postData['razorpay_payment_id'] ='pay_QsqOyd3gZfndoW';
        
        
    // //     $api_url = env('RAZORPAY_DEFAULT_URL') . "payments/" . $postData['razorpay_payment_id'];
    // //     $order_info = $this->callApi($api_url, [], 'razorpay');
        
    // //   echo "<pre>"; print_r($order_info); die;
       
      
       
       
    //     $user_id = (Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : 0);
    //     if (!empty($postData)) {
    //         $api_url = env('RAZORPAY_DEFAULT_URL') . "payments/" . $postData['razorpay_payment_id'];
    //         $order_info = $this->callApi($api_url, [], 'razorpay');
    //         //echo "<pre>"; print_r($order_info); die;
            
    //         if (!empty($order_info)) {
    //             $order_id = $this->createOrder('razorpay', ($order_info['amount'] / 100), $order_info['order_id'], $order_info['status'], $order_info['notes']['coupon_id'], $order_info['notes']['coupon_discount'], Auth::user()->wallet_avl_balance, $order_info['notes']['shippingcharge']);
           
    //             if (!empty($order_id) && $order_info['status'] == 'captured') 
    //             {
    //                 // Retrieve the order using $order_id
    //                 $order = Order::find($order_id);
                
    //                 // Check if the order exists
    //                 if ($order) {
    //                     $razorpay_payment_id =  $order_info['id'];  // Assuming 'id' is the Razorpay payment ID
                
    //                     // Update the order's payment status and Razorpay payment ID
    //                     $order->payment_status = 'paid';
    //                     $order->razorpay_payment_id = $razorpay_payment_id;
                        
    //                     // Save the changes to the database
    //                     $order->save();
    //                 } else {
    //                     // Handle case where order is not found
    //                     // For example, you can log an error or return an error message
    //                     Log::error("Order not found: {$order_id}");
    //                 }
    //             }
    //         }
    //     }
        
    //     //Cart::where([['user_id','=',$user_id]])->delete();
    //     //return redirect()->route('user.order.confirm', encrypt($order_id))->with(['success' => 'Your order has been placed successfully.']);
    //     return redirect()->route('user.order.confirm', 'order_LvdXqEGm6ab9VZ')->with(['success' => 'Your order has been placed successfully.']);
    // }
    
    // public function processPayment(Request $request){
    //     $inputData = $request->all();
    //     // print_r($inputData);die;
    //     $checkoutData = session()->get('checkoutData') ?? [];
    //     $checkoutItemData = session()->get('checkoutItemData') ?? [];
    //     // print_r($checkoutData);die;
    //     if(empty($checkoutData['address_id'])){
    //         return redirect()->back()->with(['error' => 'Please select address before continuing']);  
    //     }
    //     if(!empty($inputData['paymentmethod'])){
    //         $checkoutData['payment_method'] = $inputData['paymentmethod'];
    //         session()->put('checkoutData');

    //         if(!empty($inputData['paymentmethod']) && $inputData['paymentmethod'] == 'cod'){
    //             // Process the order creation process
    //             $this->createOrderAndInvoice([],$checkoutData,$checkoutItemData);

    //             return redirect()->route('user.dashboard')->with('flash_notice','Order Placed Successfully');
    //         }else{
    //             $defaultPaymentGateway = PaymentMethod::where('is_active',1)->value('name');
    //             if(strtolower($defaultPaymentGateway) == 'paypal'){
    //                 // print_r($defaultPaymentGateway);die;
    //                 $redirectUrl = $this->paypalProcessPayment($checkoutData);
    //                 return redirect()->to($redirectUrl);
    //             }else if(strtolower($defaultPaymentGateway) == 'ccavenue'){
    //                 $this->ccProcessPayment($inputData);
    //             }else if(strtolower($defaultPaymentGateway) == 'phonepe'){
    //                 $this->phonepeProcessPayment($inputData);
    //             }else{
    //                 Log::error('No Default Payment Gateway');
    //                 return redirect()->back()->with(['error' => 'Invalid Payment gateway selected']);
    //             }
    //         }
    //     }else{
    //         return redirect()->back()->with('error','Invalid Payment gateway selected');
    //     }

    // }
    // public function ccProcessPayment($data = [])
    // {
    //     $input = [];
    //     $input['amount'] = $data['total'];
    //     $input['order_id'] = "123XSDDD456";
    //     $input['currency'] = getCurrentCurrency();
    //     $input['redirect_url'] = route('front-user.cc-response');
    //     $input['cancel_url'] = route('front-user.cc-response');
    //     $input['language'] = "EN";
    //     $input['merchant_id'] = env('CC_MERCHANT_ID');

    //     $merchant_data = "";

    //     $working_key = env('CC_WORKING_KEY'); 
    //     $access_code = env('CC_ACCESS_CODE'); 

    //     // $input['address_id'] = "some-custom-inputs"; // optional parameter
    //     // $input['sub_total'] = "some-custom-inputs"; // optional parameter
    //     // $input['coupon_name'] = "some-custom-inputs"; // optional parameter
    //     // $input['coupon_discount'] = "some-custom-inputs"; // optional parameter
    //     // $input['delivery'] = "some-custom-inputs"; // optional parameter
    //     foreach ($input as $key => $value) {
    //         $merchant_data .= $key . '=' . $value . '&';
    //     }

    //     $encrypted_data = $this->encryptCC($merchant_data, $working_key);
    //     $url = env('CC_URL') . '/transaction/transaction.do?command=initiateTransaction&encRequest=' . $encrypted_data . '&access_code=' . $access_code;

    //     return redirect($url);
    // }

    // public function paypalProcessPayment($data = []){
    //     // print_r('asdasd');die;  
    //     $provider = new PayPalClient;
    //     // $provider = PayPalClient::setProvider();
    //     $provider->getAccessToken();

    //     $provider->setCurrency('USD');
       
    //     $data = [
    //         "intent"              => "CAPTURE",
    //         "purchase_units"      => [
    //             [
    //                 "amount" => [
    //                     "value"         => number_format(convertOneCurrencyToAnother(getCurrentCurrency(),'USD',$data['total']), 2, '.', ''),
    //                     "currency_code" => 'USD',
    //                 ],
    //             ]
    //         ],
    //         "application_context" => [
    //             "cancel_url" => route('front-user.paypalFailed'),
    //             "return_url" => route('front-user.paypalSuccess'),
    //         ],
    //     ];
    //     $order = $provider->createOrder($data);
    //     // print_r($order);die;
    //     $url = $order['links'][1]['href'];
    //     return $url;
    // }

    // public function paypalFailed()
    // {
    //     dd('Your payment has been declend. The payment cancelation page goes here!');
    // }

    // public function paypalSuccess(Request $request)
    // {
    //     $provider = new PayPalClient();      // To use express checkout.
    //     $provider->getAccessToken();
    //     $token = $request->get('token');

    //     $orderInfo = $provider->showOrderDetails($token);
    //     $response = $provider->capturePaymentOrder($token);
    //     $transactionId = $response['id'] ?? '';
    //     $checkoutData = session()->get('checkoutData') ?? [];
    //     // print_r($checkoutData);die;
    //     $checkoutItemData = session()->get('checkoutItemData') ?? [];
    //     //Creating order
    //     $this->createOrderAndInvoice(['transaction_id' => $transactionId],$checkoutData,$checkoutItemData);
    //     return redirect()->route('user.dashboard')->with('flash_notice','Order Placed Successfully');
    // }
    // public function phonepeProcessPayment($data = [])
    // {
    //     $data = array (
    //         'merchantId' => 'M22CJRDZCESWK',
    //         'merchantTransactionId' => uniqid(),
    //         'merchantUserId' => 'MUID123',
    //         'amount' => $data['total'],
    //         'redirectUrl' => route('front-user.phonepeResponse'),
    //         'redirectMode' => 'POST',
    //         'callbackUrl' => route('front-user.phonepeResponse'),
    //         'mobileNumber' => '9999999999',
    //         'paymentInstrument' => 
    //         array (
    //         'type' => 'PAY_PAGE',
    //         ),
    //     );

    //     $encode = base64_encode(json_encode($data));

    //     $saltKey = '5bd76277-6683-4817-9443-952542a40ac3';
    //     $saltIndex = 1;

    //     $string = $encode.'/pg/v1/pay'.$saltKey;
    //     $sha256 = hash('sha256',$string);

    //     $finalXHeader = $sha256.'###'.$saltIndex;

    //     $url = "https://api.phonepe.com/apis/hermes/pg/v1/pay";

    //     $response = Curl::to($url)
    //             ->withHeader('Content-Type:application/json')
    //             ->withHeader('X-VERIFY:'.$finalXHeader)
    //             ->withData(json_encode(['request' => $encode]))
    //             ->post();

    //     $rData = json_decode($response);

    //     return redirect()->to($rData->data->instrumentResponse->redirectInfo->url);
    // }

    // public function phonepeResponse(Request $request)
    // {
    //     $input = $request->all();

    //     $saltKey = '5bd76277-6683-4817-9443-952542a40ac3';
    //     $saltIndex = 1;

    //     $finalXHeader = hash('sha256','/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'].$saltKey).'###'.$saltIndex;

    //     $response = Curl::to('https://api.phonepe.com/apis/hermes/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'])
    //             ->withHeader('Content-Type:application/json')
    //             ->withHeader('accept:application/json')
    //             ->withHeader('X-VERIFY:'.$finalXHeader)
    //             ->withHeader('X-MERCHANT-ID:'.$input['transactionId'])
    //             ->get();

    //     dd(json_decode($response));
    // }


    // public function ccResponse(Request $request)
    // {
    //     try {
    //         $workingKey = config('cc-avenue.working_key'); //Working Key should be provided here.
    //         $encResponse = $_POST["encResp"];

    //         $rcvdString = $this->decryptCC($encResponse, $workingKey);        //Crypto Decryption used as per the specified working key.
    //         $order_status = "";
    //         $decryptValues = explode('&', $rcvdString);
    //         $dataSize = sizeof($decryptValues);
    //     } catch (Exception $e) {
    //         Log::error($e);
    //         return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
    //     }
    // }

    // public function encryptCC($plainText, $key)
    // {
    //     $key = $this->hextobin(md5($key));
    //     $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    //     $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
    //     $encryptedText = bin2hex($openMode);
    //     return $encryptedText;
    // }

    // public function decryptCC($encryptedText, $key)
    // {
    //     $key = $this->hextobin(md5($key));
    //     $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    //     $encryptedText = $this->hextobin($encryptedText);
    //     $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
    //     return $decryptedText;
    // }

    // public function pkcs5_padCC($plainText, $blockSize)
    // {
    //     $pad = $blockSize - (strlen($plainText) % $blockSize);
    //     return $plainText . str_repeat(chr($pad), $pad);
    // }

    // public function hextobin($hexString)
    // {
    //     $length = strlen($hexString);
    //     $binString = "";
    //     $count = 0;
    //     while ($count < $length) {
    //         $subString = substr($hexString, $count, 2);
    //         $packedString = pack("H*", $subString);
    //         if ($count == 0) {
    //             $binString = $packedString;
    //         } else {
    //             $binString .= $packedString;
    //         }

    //         $count += 2;
    //     }
    //     return $binString;
    // }




}
