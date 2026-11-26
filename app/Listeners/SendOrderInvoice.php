<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Mail\OrderInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Exception;

class SendOrderInvoice implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderPaid $event): void
    {
        $order = $event->order;
        $order->load(['items.book', 'user', 'shippingAddress', 'payment']);

        try {
            $pdf = Pdf::loadView('pdf.invoice', ['order' => $order]);
            $pdfContent = $pdf->output();

            Mail::to($order->user->email)->send(new OrderInvoiceMail($order, $pdfContent));
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send Order Invoice Email: ' . $e->getMessage());
        }
    }
}
