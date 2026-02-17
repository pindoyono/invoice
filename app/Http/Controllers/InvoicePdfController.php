<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function download(Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product']);
        $settings = CompanySetting::getSettings();

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'settings' => $settings,
        ]);

        $pdf->setPaper('A4', 'portrait');

        // Sanitize filename - remove "/" and "\" characters
        $filename = str_replace(['/', '\\'], '-', $invoice->invoice_number);

        return $pdf->download("Invoice-{$filename}.pdf");
    }

    public function stream(Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product']);
        $settings = CompanySetting::getSettings();

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'settings' => $settings,
        ]);

        $pdf->setPaper('A4', 'portrait');

        // Sanitize filename - remove "/" and "\" characters
        $filename = str_replace(['/', '\\'], '-', $invoice->invoice_number);

        return $pdf->stream("Invoice-{$filename}.pdf");
    }
}
