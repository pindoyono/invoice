<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;


use Illuminate\Database\Eloquent\Model;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Create the invoice first (without PO number)
        $invoice = static::getModel()::create($data);

        // Get the first invoice item (assume one product per invoice for PO number logic)
        $firstItem = $invoice->items()->first();
        $productCode = null;
        if ($firstItem && $firstItem->product) {
            $productCode = $firstItem->product->code;
        }

        // Get customer code
        $customerCode = $invoice->customer ? $invoice->customer->code : null;

        // Get month from invoice_date
        $month = $invoice->invoice_date ? $invoice->invoice_date->format('m') : now()->format('m');

        // Find the last PO number for this product/customer/month
        $basePo = ($productCode ?: 'VPS') . '-' . ($customerCode ?: 'CUST') . '-' . $month;
        $lastPo = static::getModel()::where('po_number', 'like', $basePo . '%')->orderByDesc('id')->first();
        $nextNumber = 1;
        if ($lastPo && preg_match('/(\\d{3})$/', $lastPo->po_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $poNumber = $basePo . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $invoice->po_number = $poNumber;
        $invoice->save();

        return $invoice;
    }
}
