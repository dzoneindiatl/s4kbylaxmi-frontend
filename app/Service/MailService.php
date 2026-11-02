<?php

namespace App\Service;

use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailService
{
    public function orderAccepted(Order $order, array $checkoutData): void
    {
        $user = User::find($order->user_id);
        if (!$user) {
            Log::warning('Order notification was not sent because its customer was not found.', [
                'order_id' => $order->id,
            ]);
            return;
        }

        $productIds = collect($checkoutData)->pluck('product_id')->filter()->unique();
        $products = Product::whereIn('id', $productIds)->get(['id', 'name'])->keyBy('id');
        $items = collect($checkoutData)->map(function ($item) use ($products) {
            $productId = $item['product_id'] ?? $item['productId'] ?? null;

            return [
                'name' => $products->get($productId)?->name ?? 'Product',
                'quantity' => (int) ($item['quantity'] ?? 1),
                'total' => (float) ($item['sellingPrice'] ?? $item['selling_price'] ?? $item['item_total'] ?? 0)
                    * (isset($item['sellingPrice']) || isset($item['selling_price']) ? (int) ($item['quantity'] ?? 1) : 1),
            ];
        });

        $this->sendOrderNotification('accepted', $user, $order, $items, 'Accepted');
    }

    public function orderCancelled(?User $user, iterable $orderItems): void
    {
        $this->sendOrderNotification('cancelled', $user, $this->orderFromItems($orderItems), $orderItems, 'Cancelled');
    }

    public function orderReturnRequested(iterable $orderItems, ?User $user): void
    {
        $this->sendOrderNotification('return-requested', $user, $this->orderFromItems($orderItems), $orderItems, 'Return requested');
    }

    private function orderFromItems(iterable $orderItems): ?Order
    {
        foreach ($orderItems as $item) {
            if ($item->relationLoaded('order') && $item->order) {
                return $item->order;
            }
            if (!empty($item->order_id)) {
                return Order::find($item->order_id);
            }
        }

        return null;
    }

    private function sendOrderNotification(
        string $templateSlug,
        ?User $user,
        ?Order $order,
        iterable $items,
        string $status
    ): void {
        if (!$user || !$user->email) {
            Log::warning('Order notification was not sent because the customer has no email address.', [
                'order_id' => $order?->id,
                'template' => $templateSlug,
            ]);
            return;
        }

        $template = EmailTemplate::where('slug', $templateSlug)->first();
        if (!$template) {
            Log::warning('Order notification email template was not found.', [
                'order_id' => $order?->id,
                'template' => $templateSlug,
            ]);
            return;
        }

        $rows = $this->itemRows($items);
        $orderDetails = '<table style="width:100%;border-collapse:collapse"><thead><tr>'
            . '<th style="text-align:left;padding:8px;border-bottom:1px solid #ddd">Product</th>'
            . '<th style="text-align:right;padding:8px;border-bottom:1px solid #ddd">Qty</th>'
            . '<th style="text-align:right;padding:8px;border-bottom:1px solid #ddd">Amount</th>'
            . '</tr></thead><tbody>';
        foreach ($rows as $item) {
            $orderDetails .= '<tr><td style="padding:8px;border-bottom:1px solid #ddd">'
                . e($item['name']) . '</td><td style="text-align:right;padding:8px;border-bottom:1px solid #ddd">'
                . (int) $item['quantity'] . '</td><td style="text-align:right;padding:8px;border-bottom:1px solid #ddd">₹'
                . number_format((float) $item['total'], 2) . '</td></tr>';
        }
        $orderDetails .= '</tbody></table>';

        $replacements = [
            '{CUSTOMER_NAME}' => e($user->name ?: 'Customer'),
            '{ORDER_ID}' => e($order?->order_number ?? ''),
            '{ORDER_STATUS}' => e($status),
            '{ORDER_DETAILS}' => $orderDetails,
            '{REFUND_SECTION}' => '',
        ];
        $subject = str_replace(array_keys($replacements), array_values($replacements), $template->subject);
        $body = str_replace(array_keys($replacements), array_values($replacements), $template->body);

        try {
            Mail::html($body, function ($message) use ($user, $subject) {
                $message->to($user->email)->subject($subject);
            });
        } catch (TransportExceptionInterface $exception) {
            Log::error('Order notification could not be delivered.', [
                'order_id' => $order?->id,
                'template' => $templateSlug,
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    private function itemRows(iterable $items): Collection
    {
        return collect($items)->map(function ($item) {
            if (is_array($item)) {
                return [
                    'name' => $item['name'] ?? $item['product_name'] ?? 'Product',
                    'quantity' => (int) ($item['quantity'] ?? 1),
                    'total' => (float) ($item['item_total'] ?? $item['total'] ?? $item['sellingPrice'] ?? 0),
                ];
            }

            return [
                'name' => $item->product->name ?? 'Product',
                'quantity' => (int) ($item->quantity ?? 1),
                'total' => (float) ($item->item_total ?? $item->total ?? 0),
            ];
        });
    }
}
