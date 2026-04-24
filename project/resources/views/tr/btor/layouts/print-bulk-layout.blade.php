<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
    <title>@yield('title', 'BTOR Bulk Print')</title>

    {{-- Include Custom Styles --}}
    @include('tr.btor.custom.style')
    
    <style>
        /* Bulk-specific styles */
        @media screen {
            .report-wrapper {
                margin-bottom: 40px;
                padding-bottom: 40px;
                border-bottom: 3px dashed #ccc;
            }
            .report-wrapper:last-child {
                border-bottom: none;
            }
        }

        .print-body-row td {
            padding-top: 10px!important;
        }
        
        .report-badge {
            background: #eee;
            padding: 5px 10px;
            font-size: 10pt;
            margin-bottom: 20px;
            display: inline-block;
            border: 1px solid #ccc;
        }

        /* Page number via CSS counter - works in Chrome/Edge/Firefox print */
        @page {
            @bottom-center {
                content: counter(page) " / " counter(pages);
                font-size: 8pt;
                color: #526d4e;
            }
        }

        /* Screen-only page number shown inside tfoot */
        .page-number-display {
            text-align: center;
            font-size: 8pt;
            color: #526d4e;
            margin-bottom: 4px;
        }

        @media print {
            /* Hide the screen-only page number — the @page counter takes over */
            .page-number-display { display: none; }
        }
    </style>
    
    @stack('print-styles')
</head>
<body>
    {{-- Print Controls (Screen Only) --}}
    <div class="print-controls no-print">
        <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Print All</button>
        <button onclick="window.close()" class="btn-close"><i class="fas fa-times"></i> Close</button>
    </div>

    {{-- TABLE-BASED LAYOUT --}}
    <table class="print-wrapper">
        {{-- REPEATING HEADER --}}
        <thead>
            <tr class="print-header-row">
                <th>
                    <div class="print-header-content">
                        @if(file_exists(public_path('images/uploads/header.png')))
                            <img src="/images/uploads/header.png" alt="IDEP Header" style="height: 38px; width: auto; display: block;">
                        @else
                            <h2>YAYASAN IDEP</h2>
                        @endif
                    </div>
                </th>
            </tr>
        </thead>

        {{-- REPEATING FOOTER --}}
        <tfoot>
            <tr class="print-footer-row">
                <td>
                    <div class="print-footer-content" style="margin-top: 20px; margin-bottom: 20px">
                        <div class="page-number-display" id="pageNumDisplay">— &nbsp; —</div>
                        <strong>Office</strong>: Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia | Telp/Fax: +62-361 9082983 | www.idepfoundation.org
                    </div>
                </td>
            </tr>
        </tfoot>

        {{-- MAIN CONTENT --}}
        <tbody>
            <tr class="print-body-row">
                <td>
                    @yield('content')
                </td>
            </tr>
        </tbody>
    </table>

    @stack('print-scripts')
</body>
</html>
