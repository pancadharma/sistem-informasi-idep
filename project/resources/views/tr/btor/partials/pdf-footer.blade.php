{{-- Customizable PDF Footer --}}
{{-- 
    Variables available:
    - $kegiatan: The kegiatan record
    - $footerConfig: Optional array with custom settings
        - organization: organization name
        - showPageNumber: boolean to show/hide page number
        - customText: custom footer text
--}}

@php
    $config = $footerConfig ?? [];
    $organization = $config['organization'] ?? 'IDEP Foundation';
    $showPageNumber = $config['showPageNumber'] ?? true;
    $customText = $config['customText'] ?? 'Back to Office Report (BTOR)';
@endphp

<div class="pdf-footer">
    <table class="pdf-footer-table">
        <tr>
            <td style="width: 40%; text-align: left;">
                <strong>{{ $organization }}</strong> | {{ $customText }}
            </td>
            <td style="width: 30%; text-align: center;">
                @if(isset($kegiatan))
                    Report ID: {{ $kegiatan->id }}
                @endif
            </td>
            <td style="width: 30%; text-align: right;">
                @if($showPageNumber)
                    {{ now()->format('Y') }} | Page <span class="page-number"></span>
                @else
                    {{ now()->format('Y') }}
                @endif
            </td>
        </tr>
    </table>
</div>
