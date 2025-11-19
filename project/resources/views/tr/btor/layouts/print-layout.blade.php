<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
