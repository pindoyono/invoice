<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            padding: 15mm 10mm 15mm 10mm;
        }

        .container {
            width: 100%;
            padding: 0;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            pointer-events: none;
            opacity: 0.1;
        }

        .watermark img {
            width: 400px;
            height: auto;
        }

        /* Header */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .logo-cell {
            width: 90px;
            vertical-align: top;
            padding-right: 10px;
        }

        .logo-img {
            width: 80px;
            height: auto;
        }

        .company-info-cell {
            vertical-align: top;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .company-details {
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }

        .invoice-title-cell {
            text-align: right;
            vertical-align: top;
            width: 150px;
        }

        .invoice-title {
            font-size: 46px;
            font-weight: bold;
            color: #4338ca;
            letter-spacing: 1px;
        }

        /* Bill To & Invoice Details Section */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .bill-to-cell {
            width: 50%;
            vertical-align: top;
        }

        .bill-to-label {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .bill-to-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 2px;
        }

        .bill-to-details {
            font-size: 13px;
            color: #333;
            line-height: 1.4;
        }

        .invoice-details-cell {
            width: 50%;
            vertical-align: top;
        }

        .invoice-details-inner {
            width: 100%;
        }

        .invoice-details-inner td {
            font-size: 13px;
            padding: 2px 0;
        }

        .invoice-details-inner .label {
            font-weight: bold;
            color: #333;
            width: 35%;
        }

        .invoice-details-inner .value {
            text-align: right;
            color: #333;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table thead th {
            background-color: #4338ca;
            color: white;
            padding: 8px 10px;
            font-size: 14px;
            font-weight: 600;
            text-align: left;
        }

        .items-table thead th.center {
            text-align: center;
        }

        .items-table thead th.right {
            text-align: right;
        }

        .items-table tbody td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 13px;
            vertical-align: top;
        }

        .items-table tbody td.center {
            text-align: center;
        }

        .items-table tbody td.right {
            text-align: right;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .item-subdesc {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }

        /* Payment & Total Section */
        .payment-total-table {
            width: 100%;
            margin-bottom: 25px;
        }

        .payment-cell {
            width: 50%;
            vertical-align: top;
        }

        .payment-title {
            font-size: 14px;
            font-weight: bold;
            color: #4338ca;
            margin-bottom: 5px;
        }

        .payment-details {
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }

        .total-cell {
            width: 50%;
            vertical-align: top;
        }

        .total-box {
            width: 100%;
            border-collapse: collapse;
        }

        .total-box .total-row td {
            background-color: #4338ca;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
        }

        .total-box .total-row td:first-child {
            text-align: left;
        }

        .total-box .total-row td:last-child {
            text-align: right;
        }

        .total-box .subtotal-row td {
            padding: 6px 15px;
            font-size: 13px;
            border-bottom: 1px solid #e0e0e0;
        }

        .total-box .subtotal-row td:last-child {
            text-align: right;
        }

        /* Footer Section */
        .footer-table {
            width: 100%;
            margin-top: 40px;
        }

        .footer-left {
            width: 55%;
            vertical-align: top;
        }

        .footer-right {
            width: 45%;
            vertical-align: top;
            text-align: right;
        }

        .terms-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .terms-content {
            font-size: 12px;
            color: #555;
            line-height: 1.5;
        }

        .signature-area {
            text-align: center;
            display: inline-block;
        }

        .footer-logo {
            width: 80px;
            height: auto;
            margin-bottom: 5px;
        }

        .signature-img {
            width: 150px;
            height: auto;
        }

        .qr-area {
            margin-top: 15px;
            text-align: right;
        }

        .qr-code {
            width: 50px;
            height: 50px;
        }

        .my-invoice-text {
            font-size: 12px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="watermark">
        @if ($settings && $settings->logo)
            @php
                $wmPath = public_path('storage/' . $settings->logo);
                $wmData = '';
                if (file_exists($wmPath)) {
                    $wmContent = file_get_contents($wmPath);
                    $wmMime = mime_content_type($wmPath) ?: 'image/png';
                    $wmData = 'data:' . $wmMime . ';base64,' . base64_encode($wmContent);
                }
            @endphp
            @if ($wmData)
                <img src="{{ $wmData }}" alt="Watermark">
            @endif
        @endif
    </div>
    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if ($settings && $settings->logo)
                        @php
                            $logoPath = public_path('storage/' . $settings->logo);
                            $logoData = '';
                            if (file_exists($logoPath)) {
                                $logoContent = file_get_contents($logoPath);
                                $logoMime = mime_content_type($logoPath) ?: 'image/png';
                                $logoData = 'data:' . $logoMime . ';base64,' . base64_encode($logoContent);
                            }
                        @endphp
                        @if ($logoData)
                            <img src="{{ $logoData }}" class="logo-img" alt="Logo">
                        @endif
                    @endif
                </td>
                <td class="company-info-cell">
                    <div class="company-name">{{ $settings->company_name ?? 'Company Name' }}</div>
                    <div class="company-details">
                        {!! nl2br(e($settings->address ?? '')) !!}
                        @if ($settings && $settings->phone)
                            <br>{{ $settings->phone }}
                        @endif
                        @if ($settings && $settings->email)
                            <br>{{ $settings->email }}
                        @endif
                        @if ($settings && $settings->website)
                            <br>{{ $settings->website }}
                        @endif
                    </div>
                </td>
                <td class="invoice-title-cell">
                    <div class="invoice-title">INVOICE</div>
                </td>
            </tr>
        </table>

        <!-- Bill To & Invoice Details -->
        <table class="info-table">
            <tr>
                <td class="bill-to-cell">
                    <div class="bill-to-label">BILL TO</div>
                    <div class="bill-to-name">{{ $invoice->customer->name }}</div>
                    <div class="bill-to-details">
                        {!! nl2br(e($invoice->customer->address ?? '')) !!}
                        @if ($invoice->customer->phone)
                            <br>{{ $invoice->customer->phone }}
                        @endif
                        @if ($invoice->customer->email)
                            <br>{{ $invoice->customer->email }}
                        @endif
                    </div>
                </td>
                <td class="invoice-details-cell">
                    <table class="invoice-details-inner">
                        <tr>
                            <td class="label">INVOICE #</td>
                            <td class="value">{{ $invoice->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="label">DATE</td>
                            <td class="value">{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">DUE DATE</td>
                            <td class="value">{{ $invoice->due_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">P.O. #</td>
                            <td class="value">{{ $invoice->po_number ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Description</th>
                    <th class="center" style="width: 12%;">QTY</th>
                    <th class="center" style="width: 19%;">Price</th>
                    <th class="right" style="width: 19%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->description }}</div>
                            @if ($item->notes)
                                <div class="item-subdesc">{{ $item->notes }}</div>
                            @endif
                        </td>
                        <td class="center">{{ number_format($item->quantity, 0) }}</td>
                        <td class="center">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="right">Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Payment Method & Total -->
        <table class="payment-total-table">
            <tr>
                <td class="payment-cell">
                    <div class="payment-title">Payment Method</div>
                    <div class="payment-details">
                        @if ($settings && $settings->bank_name)
                            Bank : {{ $settings->bank_name }}<br>
                        @endif
                        @if ($settings && $settings->bank_account_number)
                            No.Rekening : {{ $settings->bank_account_number }}<br>
                        @endif
                        @if ($settings && $settings->bank_account_name)
                            Nama : {{ $settings->bank_account_name }}
                        @endif
                    </div>
                </td>
                <td class="total-cell">
                    <table class="total-box">
                        @if ($invoice->tax_amount > 0 || $invoice->discount > 0)
                            <tr class="subtotal-row">
                                <td>Subtotal</td>
                                <td>Rp{{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if ($invoice->tax_amount > 0)
                            <tr class="subtotal-row">
                                <td>Tax ({{ number_format($invoice->tax_rate, 0) }}%)</td>
                                <td>Rp{{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if ($invoice->discount > 0)
                            <tr class="subtotal-row">
                                <td>Discount</td>
                                <td>- Rp{{ number_format($invoice->discount, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr class="total-row">
                            <td>TOTAL</td>
                            <td>Rp{{ number_format($invoice->total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Footer - Terms & Signature -->
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <div class="terms-title">Terms & Conditions</div>
                    <div class="terms-content">
                        @if ($settings && $settings->terms_conditions)
                            {!! nl2br(e($settings->terms_conditions)) !!}
                        @else
                            - Harap melunasi tagihan sebelum tanggal jatuh tempo<br>
                            - Harga sudah termasuk semua pajak yang berlaku<br>
                            - Barang tetap menjadi milik penjual sampai pembayaran penuh diterima.
                        @endif
                    </div>
                </td>
                <td class="footer-right">
                    <div class="signature-area">
                        @if ($settings && $settings->signature)
                            @php
                                $sigPath = public_path('storage/' . $settings->signature);
                                $sigData = '';
                                if (file_exists($sigPath)) {
                                    $sigContent = file_get_contents($sigPath);
                                    $sigMime = mime_content_type($sigPath) ?: 'image/png';
                                    $sigData = 'data:' . $sigMime . ';base64,' . base64_encode($sigContent);
                                }
                            @endphp
                            @if ($sigData)
                                <img src="{{ $sigData }}" class="signature-img" alt="Signature">
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
