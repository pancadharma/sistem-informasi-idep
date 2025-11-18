<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'BTOR Print')</title>

    <style>
        /* Print-specific CSS */
        @page {
            size: A4;
            margin: 2cm 1.5cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            background: white;
        }

        .print-container {
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
            padding: 10px;
        }

        /* Header Styles */
        .report-header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .report-header h2 {
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .report-header h3 {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .report-info {
            margin-bottom: 20px;
        }

        /* Section Styles */
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }

        .subsection {
            margin-bottom: 15px;
            margin-left: 15px;
        }

        .subsection h5 {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

        /* Table Styles */
        .table-print {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table-print td {
            padding: 5px 8px;
            vertical-align: top;
            border: none;
        }

        .table-print td:first-child {
            font-weight: normal;
        }

        .table-bordered {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }

        .table-bordered th {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }

        .table-bordered thead th {
            background-color: #d0d0d0;
        }

        .table-sm td,
        .table-sm th {
            padding: 5px;
            font-size: 9pt;
        }

        /* Table Utilities */
        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .table-secondary {
            background-color: #f5f5f5 !important;
            font-weight: bold;
        }

        .table-active {
            background-color: #e8e8e8 !important;
            font-weight: bold;
        }

        /* Content Box */
        .content-box {
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #fafafa;
            margin-bottom: 10px;
            min-height: 50px;
        }

        /* Alert Boxes */
        .alert {
            padding: 10px 15px;
            margin-bottom: 15px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-info {
            background-color: #d9edf7;
            border-color: #bce8f1;
            color: #31708f;
        }

        .alert-warning {
            background-color: #fcf8e3;
            border-color: #faebcc;
            color: #8a6d3b;
        }

        .alert-secondary {
            background-color: #e7e7e7;
            border-color: #ddd;
            color: #333;
        }

        /* Card Styles */
        .card {
            border: 1px solid #333;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .card-header {
            background-color: #f0f0f0;
            padding: 10px 15px;
            border-bottom: 1px solid #333;
            font-weight: bold;
        }

        .card-body {
            padding: 15px;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 9pt;
            font-weight: bold;
            border-radius: 3px;
            border: 1px solid #333;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .badge-lg {
            padding: 6px 12px;
            font-size: 10pt;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            padding: 15px;
            text-align: center;
        }

        .signature-box p {
            margin-bottom: 10px;
        }

        .signature-box strong {
            font-size: 11pt;
        }

        .signature-box em {
            font-size: 10pt;
            color: #666;
        }

        /* Report Footer */
        .report-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            font-size: 9pt;
            color: #666;
        }

        /* Page Break Utilities */
        .page-break {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }

        /* Row and Column Grid */
        .row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .col-md-6 {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
            vertical-align: top;
        }

        .col-md-12 {
            width: 100%;
        }

        /* Utilities */
        .mb-2 { margin-bottom: 5px; }
        .mb-3 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 15px; }
        .mt-2 { margin-top: 5px; }
        .mt-3 { margin-top: 10px; }
        .mt-4 { margin-top: 15px; }
        .mt-5 { margin-top: 20px; }
        .pt-3 { padding-top: 10px; }
        .pb-2 { padding-bottom: 5px; }

        strong {
            font-weight: bold;
        }

        em {
            font-style: italic;
        }

        small {
            font-size: 9pt;
        }

        .text-muted {
            color: #666;
        }

        .text-primary {
            color: #0056b3;
            font-weight: bold;
        }

        .border-top {
            border-top: 1px solid #ccc;
        }

        .border-bottom {
            border-bottom: 1px solid #ccc;
        }

        /* Print-specific rules */
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-before: always;
            }

            .no-break {
                page-break-inside: avoid;
            }

            a {
                text-decoration: none;
                color: inherit;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000 !important;
            }
        }

        /* Hide print button when printing */
        @media print {
            .print-button {
                display: none;
            }
        }

        /* Link Styles */
        a {
            color: #0056b3;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>

    @stack('print-styles')
</head>
<body>
    {{-- Print Button (hidden when printing) --}}
    <div class="print-button no-print" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            <span style="margin-right: 5px;">🖨️</span> Print / Save as PDF
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 5px;">
            ✕ Close
        </button>
    </div>

    @yield('content')

    @stack('print-scripts')
</body>
</html>
