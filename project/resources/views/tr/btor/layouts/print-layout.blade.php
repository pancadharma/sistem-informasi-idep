<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    
    <title>@yield('title', 'BTOR Print')</title>

    {{-- Include Custom Styles --}}
    @include('tr.btor.custom.style')
    @stack('print-styles')
</head>
<body>
    <div class="print-controls no-print">
        <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Print</button>
        <button onclick="window.close()" class="btn-close"><i class="fas fa-times"></i> Close</button>
    </div>

    {{-- 1. FIXED HEADER (Repeats on every page) --}}
    <div class="fixed-header">
        <div class="text-center">
            @if(file_exists(public_path('images/uploads/header.png')))
                <img src="{{ asset('images/uploads/header.png') }}" style="height: 38px; width: auto;">
            @else
                <h2 style="font-size: 14pt; font-weight: bold; margin: 0; color: #000;">YAYASAN IDEP</h2>
            @endif
        </div>
    </div>



    {{-- 3. MAIN CONTENT (Scrolls) --}}
    {{-- We wrap content in a table logic to ensure margins work nicely if needed, 
         but mostly padding handles it --}}
    <div class="content-wrapper">
        @yield('content')
    </div>
    {{-- 2. FIXED FOOTER (Repeats on every page) --}}
    <div class="fixed-footer">
        <div class="report-footer-content">
            <p><strong>Yayasan IDEP Selaras Alam</strong></p>
            <p>Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia</p>
            <p>Telp/Fax +62-361-908-2983 / +62-812 4658 5137</p>
            <p>Dihasilkan pada: {{ date('d-m-Y H:i:s') }}</p>
        </div>
    </div>
    @stack('print-scripts')
</body>
</html>