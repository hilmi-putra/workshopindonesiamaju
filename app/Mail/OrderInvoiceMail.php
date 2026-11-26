<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    protected $pdfContent;

    public function __construct(Order $order, $pdfContent)
    {
        $this->order = $order;
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        return $this->subject('Invoice Pesanan Anda #' . $this->order->invoice_number . ' - MegaBookstore')
                    ->view('emails.order_invoice') // We will create this blade view
                    ->attachData($this->pdfContent, 'Invoice-' . $this->order->invoice_number . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
