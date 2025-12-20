{{-- Customizable PDF Header --}}
{{-- 
    Variables available:
    - $kegiatan: The kegiatan record
    - $headerConfig: Optional array with custom settings
        - logo: path to logo image
        - organization: organization name
        - department: department name
        - showReportInfo: boolean to show/hide report info
--}}

@php
    $config = $headerConfig ?? [];
    $organization = $config['organization'] ?? 'IDEP Foundation';
    $department = $config['department'] ?? 'Program';
    $showReportInfo = $config['showReportInfo'] ?? true;
    $logoPath = $config['logo'] ?? public_path('images/logo-idep.png');
@endphp

<div class="pdf-header">
    <table class="pdf-header-table">
        <tr>
            <td style="width: 15%;">
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="Logo" class="header-logo" style="max-width: 70px; height: auto;">
                @else
                    <div style="width: 70px; height: 70px; border: 1px solid #ccc; text-align: center; line-height: 70px; font-size: 8pt;">LOGO</div>
                @endif
            </td>
            <td style="width: 70%;" class="header-title">
                <h1>{{ $organization }}</h1>
                <h2>{{ $department }} Department</h2>
            </td>
            <td style="width: 15%;" class="header-info">
                @if($showReportInfo)
                    <strong>Report ID:</strong> {{ $kegiatan->id ?? '-' }}<br>
                    <strong>Date:</strong> {{ now()->format('d/m/Y') }}
                @endif
            </td>
        </tr>
    </table>
</div>

<div class="report-main-title">
    <h2>BACK TO OFFICE REPORT</h2>
    <h3>(BTOR)</h3>
</div>
