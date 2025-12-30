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
        
        .report-badge {
            background: #eee;
            padding: 5px 10px;
            font-size: 10pt;
            margin-bottom: 20px;
            display: inline-block;
            border: 1px solid #ccc;
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
                            <img src="{{ asset('images/uploads/header.png') }}" alt="Header">
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
                    <div class="print-footer-content">
                        <p><strong>Yayasan IDEP Selaras Alam</strong></p>
                        <p>Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia</p>
                        <p>Telp/Fax +62-361-908-2983 / +62-812 4658 5137</p>
                        <p>Dihasilkan pada: {{ date('d-m-Y H:i:s') }}</p>
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
