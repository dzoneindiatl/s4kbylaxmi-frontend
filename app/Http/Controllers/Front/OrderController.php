<?php

namespace App\Http\Controllers\Front;

use Exception;
use Session, Auth;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\WalletHistory;
use App\Models\InvoiceSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ProductVariantCombination;
use App\Models\{ProductGraphics, VariantValue, OrderStatusHistory, OrderCancellation, RefundRequest};
use App\Service\MailService; 
class OrderController extends Controller
{
    public $mailService; 
    public function __construct(MailService $mailService){

        $this->mailService = $mailService ; 

    }
    public function orderDetails(Request $request, $orderId)
    {
        $orderDetails = Order::with(['billingAddress', 'shippingAddress'])->where('order_number', $orderId)->firstOrFail();
        $allStatuses = OrderStatus::orderBy('step')->where('active', 1)->get();
        $paymentMode = match ($orderDetails->payment_method) {
            'cod' => 'Cash On Delivery',
            'paypal', 'razorpay' => 'Pay Online',
            default => 'Use Vasvi Wallet',
        };

        $orderItems = OrderItem::with(['product', 'order', 'productGraphics', 'statusHistoriesItem'])
            ->whereHas('order', function ($query) use ($orderId) {
                $query->where('order_number', $orderId);
            })->get();

        $orderItemsCancelled= OrderItem::with(['product', 'order','productGraphics','statusHistoriesItem'])
        ->whereIn('order_items.status', ['cancelled','cancelled_by_customer'])
        ->whereHas('order', function ($query) use ($orderId) {
            $query->where('order_number', $orderId);
        })->get();
       
        $orderItemsDelivered= OrderItem::with(['product', 'order','productGraphics','statusHistoriesItem'])
        ->where('order_items.status', 'delivered')
        ->whereHas('order', function ($query) use ($orderId) {
            $query->where('order_number', $orderId);
        })->get();

        $orderItemsRefunded= OrderItem::with(['product', 'order','productGraphics','statusHistoriesItem'])
        ->where('order_items.status', 'refunded')
        ->whereHas('order', function ($query) use ($orderId) {
            $query->where('order_number', $orderId);
        })->get();

        $html = view('front.modules.dashboard.order-details', compact('orderDetails', 'orderItems','orderItemsCancelled','orderItemsDelivered','orderItemsRefunded', 'paymentMode', 'allStatuses'))->render();

        return response()->json([
            'status' => true,
            'html' => $html,
        ]);
    }


    public function submitCancelOrder(Request $request)
    {
        $request->validate([
            'cancel_reason'   => 'required',
            'reason_details'  => 'required|string',
            'other_reason'    => 'required_if:cancel_reason,Other|string|nullable',
            'order_number'    => 'required|exists:orders,order_number',
        ]);

        $user_id = Auth::guard('customer')->user()->id;
        $order = Order::where("order_number", $request->order_number)->first();
        $orderitem = OrderItem::where("id", $request->order_item_id)->first();


        $reason = $request->cancel_reason === 'Other' ? $request->other_reason : $request->cancel_reason;

        try {
            DB::beginTransaction();

            // Create order cancellation record
            OrderCancellation::create([
                'order_id' => $order->id,
                'user_id'  => $user_id,
                'reason'   => $reason,
                'details'  => $request->reason_details,
                'order_item_id' => $request->order_item_id,
            ]);

            // Create order status history record
            $orderStatus = new OrderStatusHistory;
            $orderStatus->user_id = $user_id;
            $orderStatus->order_id     = $order->id;
            $orderStatus->order_status_id = 11; // 'cancelled' status ID
            $orderStatus->save();

            $order->status = 11;
            $order->save();
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Order cancellation submitted successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function submitRefundRequest(Request $request)
    {
        // $request->validate([
        //     'refund_reason' => 'required|string',
        //     'account_number' => 'required_if:refund_mode,account|nullable',
        //     'confirm_account_number' => 'required_with:account_number|same:account_number|nullable',
        //     'ifsc_code' => 'required_if:refund_mode,account|nullable',
        //     'account_type' => 'required_if:refund_mode,account|nullable|in:Saving,Current',
        //     'bank_name' => 'required_if:refund_mode,account|nullable',
        // ]);
        $user_id    = Auth::guard('customer')->user()->id;
        $order      = Order::where("order_number", $request->order_number)->first();
        $orderitem = OrderItem::whereIn("id", $request->order_item_id)->first();
        RefundRequest::create([
            'user_id'           => $user_id,
            "order_id"          => $order->id,
            'order_item_id'     => $orderitem->id,
            'refund_reason'     => $request->refund_reason,
            'refund_details'    => $request->refund_details,
            'account_number'    => $request->account_number,
            'ifsc_code'         => $request->ifsc_code,
            'account_type'      => $request->account_type,
            'bank_name'         => $request->bank_name,
            'refund_mode'       => $request->refund_type,
        ]);
        // Create order status history record
        $status = getOrderStatuss(0, 'return-requested');
        $orderStatus = new OrderStatusHistory;
        $orderStatus->user_id           = $user_id;
        $orderStatus->order_id          = $order->id;
        $orderStatus->order_item_id     = $orderitem->id;
        $orderStatus->order_status_id   = 8;
        $orderStatus->order_status   = 'return-requested';
        $orderStatus->save();

        $orderitem->status   = 'return-requested';
        $orderitem->order_status_id   = 8;
        $orderitem->save();

        $userData = Auth::guard('customer')->user(); 
        $orderItemData = OrderItem::with('order')->where('order_id',$order->id)->get(); 

        $this->mailService->orderReturnRequested($orderItemData,$userData); 
        return redirect()->back()->with('success',"Your request is generated"); 
        // return response()->json(['message' => 'Refund request submitted successfully']);
    }

    public function orderSuccess(Request $request, $orderNumber)
    {
        $order = Order::where('id', decrypt($orderNumber))->firstOrFail();
        $walletamount = WalletHistory::where('transaction_id', $order->order_number)->first();
        return view('front.modules.dashboard.order-success', compact('order', 'walletamount'));
    }


    public function generateNewInvoice(Request $request)
    {
        $order = Order::where('orders.id', $request->id)->leftJoin('users', 'users.id', 'orders.user_id')->select('orders.*', 'users.name as user_name', 'users.email as user_email')->first();
        $checkout_data = OrderItem::with(['product', 'itemTax'])->where('order_id', $request->id)->get();
        $GSTIN = config('Site.GSTIN');
        $currency = Currency::where('currency_code', $order->currency_code)->value('symbol');
        $supplySetting = InvoiceSetting::with(['country', 'state', 'city'])
            ->where('is_active', 1)
            ->first();
        $image = 'https://vasvi.in/uploads/settings/AUG2025/1755156000-settings.png';


        $randonNum = rand(1000, 9999);
        $pdf = PDF::loadView('invoices.order_new_invoice', ['checkout_data' => $checkout_data, 'order' => $order, 'currency' => $currency, 'GSTIN' => $GSTIN, 'supplySetting' => $supplySetting, 'image' => $image]);
        // $path = Config('constant.ORDER_INVOICE_ROOT_PATH') . $request->id . $randonNum . "_invoice.pdf";
        // $invoiceDirectory = Config('constant.ORDER_INVOICE_ROOT_PATH');
        // if (!file_exists($invoiceDirectory)) {
        //     mkdir($invoiceDirectory, 0755, true);
        // }
        // $html = $pdf->getDomPDF()->outputHtml();
        // prx($html);
        $filename = $request->id . "_invoice.pdf";
        return $pdf->stream($filename);
    }

    public function generateItemsInvoice(Request $request)
    {

        $order = Order::where('orders.id', $request->id)->leftJoin('users', 'users.id', 'orders.user_id')->select('orders.*', 'users.name as user_name', 'users.email as user_email')->first();
        $checkout_data = OrderItem::with(['product', 'itemTax'])->whereIn('id', $request->ids)->get();
        $GSTIN = config('Site.GSTIN');
        $currency = Currency::where('currency_code', $order->currency_code)->value('symbol');
        $supplySetting = InvoiceSetting::with(['country', 'state', 'city'])
            ->where('is_active', 1)
            ->first();

        $randonNum = rand(1000, 9999);
        $pdf = PDF::loadView('invoices.order_new_invoice', ['checkout_data' => $checkout_data, 'order' => $order, 'currency' => $currency, 'GSTIN' => $GSTIN, 'supplySetting' => $supplySetting]);
        //$path = Config('constant.ORDER_INVOICE_ROOT_PATH') . $request->id . $randonNum . "_invoice.pdf";
        //$invoiceDirectory = Config('constant.ORDER_INVOICE_ROOT_PATH');
        //if (!file_exists($invoiceDirectory)) {
        //   mkdir($invoiceDirectory, 0755, true);
        //}
        //$filename = $request->id . "_invoice.pdf";
        //return $pdf->stream($filename);

        $pdfContent = $pdf->download()->getOriginalContent();
        return response()->json([
            'file' => base64_encode($pdfContent),
            'filename' => $request->id . "_invoice.pdf"
        ]);
    }
}
