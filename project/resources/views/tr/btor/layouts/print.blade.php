<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BTOR Print')</title>

    <style>
        @page {
            size: A4;
            margin: 2cm;
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
        }

        .print-container {
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
            padding: 20px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .report-header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-header h3 {
            font-size: 14pt;
            font-weight: bold;
        }

        hr {
            border: none;
            border-top: 2px solid #000;
            margin: 10px 0;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .subsection {
            margin-bottom: 15px;
        }

        .subsection h5 {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .table-print {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table-print td {
            padding: 5px;
            vertical-align: top;
        }

        .table-bordered {
            width: 100%;
            border-collapse: collapse;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table-bordered th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .content-box {
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        .signature-box {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box p {
            margin-bottom: 60px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }
        }
    </style>

    @stack('print-styles')
</head>
<body>
    @yield('content')
</body>
</html>
