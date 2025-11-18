<style>
    /* Print-specific CSS */
    @page {
        size: A4 landscape; /* Changed to landscape for wider tables */
        margin: 1.5cm 1cm;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'DejaVu Sans', 'Arial', sans-serif; /* DejaVu Sans has better UTF-8 support */
        font-size: 9pt;
        line-height: 1.4;
        color: #000;
        background: white;
    }

    .print-container {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        padding: 10px;
    }

    /* Header Styles */
    .report-header {
        margin-bottom: 15px;
        border-bottom: 2px solid #000;
        padding-bottom: 8px;
    }

    .report-header h2 {
        font-size: 16pt;
        font-weight: bold;
        text-align: center;
        margin-bottom: 3px;
        text-transform: uppercase;
    }

    .report-header h3 {
        font-size: 14pt;
        font-weight: bold;
        text-align: center;
        margin-bottom: 8px;
    }

    .report-info {
        margin-bottom: 15px;
    }

    /* Section Styles */
    .section {
        margin-bottom: 15px;
        page-break-inside: avoid;
    }

    .section-title {
        font-size: 11pt;
        font-weight: bold;
        margin-bottom: 8px;
        text-transform: uppercase;
        border-bottom: 2px solid #333;
        padding-bottom: 3px;
    }

    .subsection {
        margin-bottom: 12px;
        margin-left: 10px;
    }

    .subsection h5 {
        font-size: 10pt;
        font-weight: bold;
        margin-bottom: 5px;
    }

    /* Table Styles */
    .table-print {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        table-layout: auto; /* Allow table to adjust column width */
    }

    .table-print td {
        padding: 3px 5px;
        vertical-align: top;
        border: none;
        font-size: 9pt;
        word-wrap: break-word;
    }

    .table-bordered {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        table-layout: fixed; /* Fixed layout for better control */
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #333;
        padding: 5px;
        text-align: left;
        font-size: 8pt;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .table-bordered th {
        background-color: #e0e0e0;
        font-weight: bold;
        text-align: center;
    }

    .table-bordered thead th {
        background-color: #d0d0d0;
    }

    /* Responsive Table - Adjust for landscape */
    .table-responsive-print {
        width: 100%;
        overflow: visible;
    }

    .table-sm td,
    .table-sm th {
        padding: 3px;
        font-size: 7pt;
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
        padding: 8px;
        border: 1px solid #ccc;
        background-color: #fafafa;
        margin-bottom: 8px;
        min-height: 40px;
        word-wrap: break-word;
    }

    /* Alert Boxes */
    .alert {
        padding: 8px 12px;
        margin-bottom: 10px;
        border: 1px solid transparent;
        border-radius: 3px;
        font-size: 9pt;
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
        margin-bottom: 12px;
        page-break-inside: avoid;
    }

    .card-header {
        background-color: #f0f0f0;
        padding: 8px 12px;
        border-bottom: 1px solid #333;
        font-weight: bold;
        font-size: 10pt;
    }

    .card-body {
        padding: 12px;
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 3px 6px;
        font-size: 8pt;
        font-weight: bold;
        border-radius: 2px;
        border: 1px solid #333;
    }

    .badge-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .badge-warning {
        background-color: #fff3cd;
        color: #856404;
        border-color: #ffeaa7;
    }

    .badge-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border-color: #bee5eb;
    }

    .badge-secondary {
        background-color: #e2e3e5;
        color: #383d41;
        border-color: #d6d8db;
    }

    .badge-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .badge-lg {
        padding: 5px 10px;
        font-size: 9pt;
    }

    /* Signature Section */
    .signature-section {
        margin-top: 30px;
        page-break-inside: avoid;
    }

    .signature-box {
        padding: 12px;
        text-align: center;
    }

    .signature-box p {
        margin-bottom: 8px;
    }

    .signature-box strong {
        font-size: 10pt;
    }

    .signature-box em {
        font-size: 9pt;
        color: #666;
    }

    /* Report Footer */
    .report-footer {
        margin-top: 20px;
        padding-top: 12px;
        border-top: 1px solid #ccc;
        font-size: 8pt;
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
        width: 100%;
        margin-bottom: 8px;
        display: table;
    }

    .row::after {
        content: "";
        display: table;
        clear: both;
    }

    .col-md-6 {
        width: 48%;
        float: left;
        padding: 0 1%;
    }

    .col-md-12 {
        width: 100%;
    }

    /* Utilities */
    .mb-0 { margin-bottom: 0; }
    .mb-1 { margin-bottom: 3px; }
    .mb-2 { margin-bottom: 5px; }
    .mb-3 { margin-bottom: 8px; }
    .mb-4 { margin-bottom: 12px; }
    .mt-2 { margin-top: 5px; }
    .mt-3 { margin-top: 8px; }
    .mt-4 { margin-top: 12px; }
    .mt-5 { margin-top: 15px; }
    .pt-3 { padding-top: 8px; }
    .pb-2 { padding-bottom: 5px; }

    strong {
        font-weight: bold;
    }

    em {
        font-style: italic;
    }

    small {
        font-size: 8pt;
    }

    .text-muted {
        color: #666;
    }

    .text-primary {
        color: #0056b3;
        font-weight: bold;
    }

    .text-white {
        color: #fff;
    }

    .border-top {
        border-top: 1px solid #ccc;
    }

    .border-bottom {
        border-bottom: 1px solid #ccc;
    }

    /* Print Button Styles */
    .print-controls {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
        background: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .btn-print {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        margin-right: 5px;
        font-family: Arial, sans-serif;
    }

    .btn-print:hover {
        background-color: #0056b3;
    }

    .btn-close {
        padding: 10px 20px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        font-family: Arial, sans-serif;
    }

    .btn-close:hover {
        background-color: #545b62;
    }

    /* Hide elements when printing */
    @media print {
        body {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        .no-print,
        .print-controls {
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

        /* Ensure tables don't break */
        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }
    }

    /* Word Wrap for Long Content */
    td, th, p, div {
        word-wrap: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    /* Prevent overflow */
    * {
        max-width: 100%;
    }
    .print-controls,
    .no-print {
        display: none !important;
    }

    /* Override for screen view only */
    @media screen {
        .print-controls {
            display: block !important;
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
    }

    /* Hide for PDF generation and print */
    @media print {
        .print-controls {
            display: none !important;
        }
    }
</style>
