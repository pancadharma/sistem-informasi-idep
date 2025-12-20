<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'BTOR Report')</title>
    <style>
        /* PDF-specific CSS optimized for DomPDF */
        @page {
            size: A4 portrait;
            margin: 2cm 1.5cm 2.5cm 1.5cm; /* top, right, bottom, left */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #000;
            background: white;
        }

        /* PDF Container */
        .pdf-container {
            width: 100%;
            max-width: 100%;
            padding: 0;
        }

        /* Header with Logo */
        .pdf-header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .pdf-header-table {
            width: 100%;
            border: none;
        }

        .pdf-header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }

        .header-logo {
            width: 80px;
            height: auto;
        }

        .header-title {
            text-align: center;
        }

        .header-title h1 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1px;
        }

        .header-title h2 {
            font-size: 12pt;
            font-weight: normal;
            margin: 5px 0 0 0;
        }

        .header-info {
            text-align: right;
            font-size: 9pt;
        }

        /* Main Title */
        .report-main-title {
            text-align: center;
            margin: 15px 0;
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }

        .report-main-title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .report-main-title h3 {
            font-size: 12pt;
            margin: 3px 0 0 0;
        }

        /* Section Styles */
        .section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            border-bottom: 1.5px solid #333;
            padding-bottom: 3px;
            background-color: #f0f0f0;
            padding: 5px;
        }

        .section-number {
            display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 2px 8px;
            margin-right: 8px;
            font-weight: bold;
        }

        /* Basic Info Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
            font-size: 10pt;
            border: none;
        }

        .info-table .label {
            width: 25%;
            font-weight: bold;
        }

        .info-table .separator {
            width: 3%;
            text-align: center;
        }

        .info-table .value {
            width: 72%;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
            font-size: 9pt;
            word-wrap: break-word;
        }

        .data-table th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }

        .data-table .text-center {
            text-align: center;
        }

        .data-table .text-right {
            text-align: right;
        }

        .data-table .total-row {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        /* Content Box */
        .content-box {
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #fafafa;
            margin-bottom: 10px;
            min-height: 40px;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 10px 20px;
            border: none;
        }

        .signature-box {
            padding: 10px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 150px;
            margin: 20px 0 5px 0;
        }

        /* PDF Footer */
        .pdf-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            border-top: 1px solid #ccc;
            padding-top: 8px;
            font-size: 8pt;
            color: #666;
        }

        .pdf-footer-table {
            width: 100%;
            border: none;
        }

        .pdf-footer-table td {
            border: none;
            padding: 0;
        }

        /* Page Numbers */
        .page-number:after {
            content: counter(page);
        }

        /* Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }

        /* Page Break */
        .page-break {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }

        /* List Styles */
        ul, ol {
            margin: 5px 0;
            padding-left: 20px;
        }

        li {
            margin-bottom: 3px;
        }

        /* Horizontal Rule */
        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 10px 0;
        }

        hr.thick {
            border-top-width: 2px;
        }

        /* Small text */
        small {
            font-size: 8pt;
        }

        em {
            font-style: italic;
        }

        strong {
            font-weight: bold;
        }
    </style>
    @stack('pdf-styles')
</head>
<body>
    @yield('header')
    
    @yield('content')
    
    @yield('footer')
</body>
</html>
