<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'BTOR Export')</title>
    <style>
        /* Print-specific CSS - Inline for PDF */
        @page {
            size: A4 portrait;
            margin: 1.5cm 1cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #000;
            background: white;
        }

        /* Header image in content - centered */
        header {
            text-align: center;
            margin-bottom: 15px;
        }

        header img {
            max-width: 100%;
            max-height: 80px;
        }

        .print-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0;
        }

        .report-header {
            margin-bottom: 15px;
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

        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            padding-bottom: 3px;
        }

        .subsection {
            font-size: 10pt;
            font-weight: bold;
        }

        .subsection h5 {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .table-print {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: auto;
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
            table-layout: fixed;
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

        .table-active {
            background-color: #e8e8e8 !important;
            font-weight: bold;
        }

        .content-box {
            margin-bottom: 8px;
            min-height: 40px;
            word-wrap: break-word;
            text-align: justify;
        }

        /* Footer in content - shows at end of report */
        .report-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 2px double #008000;
            font-size: 8pt;
            text-align: center;
        }

        .report-footer .company {
            font-weight: bold;
            color: #008000;
            margin-bottom: 2px;
        }

        .report-footer .address {
            color: #008000;
            font-size: 7pt;
        }

        .page-break {
            page-break-before: always;
        }

        .page-break-after {
            page-break-after: always;
        }

        .text-muted {
            color: #666;
        }

        .text-center {
            text-align: center !important;
        }

        ul {
            margin-left: 20px;
            margin-bottom: 10px;
            list-style-type: none;
        }

        li {
            margin-bottom: 5px;
        }

        hr {
            border: 1px solid #000;
            margin: 15px 0;
        }

        .no-print {
            display: none !important;
        }

        .print-controls {
            display: none !important;
        }

        .alert-info {
            background-color: #d9edf7;
            border-color: #bce8f1;
            color: #31708f;
            padding: 8px 12px;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        .mt-3 {
            margin-top: 15px;
        }

        strong {
            font-weight: bold;
        }

        em {
            font-style: italic;
        }
    </style>
    @stack('print-styles')
</head>
<body>
    {{-- Main Content --}}
    @yield('content')
</body>
</html>
