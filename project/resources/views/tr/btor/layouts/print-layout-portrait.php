<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', 'BTOR Prints')</title>

    @include('tr.btor.custom.style')
</head>

<body>
    @if(isset($showButtons) && $showButtons)
    <div class="print-controls">
        <button onclick="window.print()" class="btn-print">
            Print
        </button>
        <button onclick="window.close()" class="btn-close">
            Close
        </button>
    </div>
    @endif

    @yield('content')
</body>

</html>
