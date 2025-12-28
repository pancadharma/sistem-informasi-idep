<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {{-- Bootstrap CSS (local) --}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    {{-- Font Awesome (local) --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <title>@yield('title', 'BTOR Print')</title>
    @include('tr.btor.custom.style')
    @stack('print-styles')
</head>
<body>
    {{-- Print Controls (hidden when printing) --}}
    <div class="print-controls no-print">
        <button onclick="window.print()" class="btn-print">
            Print
        </button>
        <button onclick="window.close()" class="btn-close">
            Close
        </button>
    </div>

    @yield('content')

    @stack('print-scripts')
</body>
</html>
