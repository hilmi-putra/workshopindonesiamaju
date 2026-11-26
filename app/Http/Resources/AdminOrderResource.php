<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            
            // Monetary
            'subtotal' => $this->subtotal,
            'shipping_fee' => $this->shipping_fee,
            'insurance_fee' => $this->insurance_fee,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'grand_total' => $this->grand_total,
            
            // Shipping & Method
            'shipping_method' => $this->shipping_method,
            'payment_method' => $this->payment_method ?? ($this->payment ? $this->payment->payment_method : null),
            'awb_number' => $this->awb_number,
            'notes' => $this->notes,
            'pickup_code' => $this->pickup_code ?? $this->invoice_number,
            'picked_up_at' => $this->picked_up_at,
            'completed_at' => $this->completed_at,
            
            // Dates
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relations
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'phone' => $this->user?->phone,
            ],
            'shipping_address' => $this->whenLoaded('shippingAddress'),
            'payment' => $this->whenLoaded('payment', function () {
                if (!$this->payment) return null;
                return [
                    'id' => $this->payment->id,
                    'payment_method' => $this->payment->payment_method,
                    'payment_provider' => $this->payment->payment_provider,
                    'status' => $this->payment->status,
                    'payment_status' => $this->payment->payment_status,
                    'transaction_number' => $this->payment->transaction_number,
                    'transaction_id' => $this->payment->transaction_id,
                    'amount' => $this->payment->amount,
                    'payment_date' => $this->payment->payment_date,
                    'paid_at' => $this->payment->paid_at,
                    'expired_at' => $this->payment->expired_at,
                    'payment_reference' => $this->payment->payment_reference,
                    'confirmed_by' => $this->payment->confirmed_by,
                    'confirmed_by_user' => $this->payment->confirmedByUser ? [
                        'id' => $this->payment->confirmedByUser->id,
                        'name' => $this->payment->confirmedByUser->name,
                    ] : null,
                ];
            }),
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'book_id' => $item->book_id,
                        'book_title' => $item->book ? $item->book->title : 'Unknown',
                        'book_sku' => $item->book ? ($item->book->sku ?? $item->book->isbn ?? '-') : '-',
                        'book_isbn' => $item->book ? $item->book->isbn : null,
                        'book_cover' => $item->book ? $item->book->cover_image : null,
                        'format' => $item->format ?? 'Fisik',
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total ?? ($item->price * $item->quantity),
                    ];
                });
            }),
            'timeline' => $this->whenLoaded('timeline', function () {
                return $this->timeline->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'status' => $log->status,
                        'description' => $log->description,
                        'actor_name' => $log->actor_name ?? ($log->user ? $log->user->name : 'Sistem'),
                        'action' => $log->action,
                        'old_value' => $log->old_value,
                        'new_value' => $log->new_value,
                        'created_at' => $log->created_at,
                    ];
                });
            }),
            'shipment' => $this->whenLoaded('shipment'),
            'refund_request' => $this->whenLoaded('refundRequest'),
        ];
    }
}
