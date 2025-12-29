{{-- resources/views/tr/btor/layouts/print-layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
    {{-- Base Styles --}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    
    <title>@yield('title', 'BTOR Print')</title>

    <style>
        /* BASE PRINT RESET */
        @media print {
            @page {
                margin: 2.5cm; /* Matches DOCX/PDF Standard */
                size: A4 portrait;
            }

            td.media-item {
                text-align: center !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: #fff !important;
                font-family: 'Tahoma', sans-serif !important;
                color: #000 !important;
            }

            /* Hide Buttons */
            .no-print, .print-controls {
                display: none !important;
            }
            
            /* Ensure Links look like text unless it's a map */
            a { text-decoration: none; color: #000; }
        }

        /* SCREEN CONTROLS */
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: #fff;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        
        .btn-print {
            background: #007bff; color: #fff; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;
        }
        .btn-close {
            background: #6c757d; color: #fff; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; margin-left: 5px;
        }
    </style>
    
    {{-- Stack for specific print styles (Green Headers, etc.) --}}
    @stack('print-styles')
</head>
<body>
    <div class="print-controls no-print">
        <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Print</button>
        <button onclick="window.close()" class="btn-close"><i class="fas fa-times"></i> Close</button>
    </div>

    @yield('content')

    @stack('print-scripts')
</body>
</html>