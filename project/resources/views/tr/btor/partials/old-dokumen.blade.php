{{-- 8. Dokumen Pendukung --}}
<div class="section">
    <h4 class="section-title">Dokumen Pendukung</h4>
    <p style="font-size: 9pt; font-style: italic; margin-bottom: 10px;">
        Silakan centang dokumen pendukung yang disertakan dalam BTOR ini.
    </p>

    @php
        $dokumen = $kegiatan->getDokumenPendukung();
        $media = $kegiatan->getMediaPendukung();
        $hasDokumen = $dokumen && $dokumen->count() > 0;
        $hasMedia = $media && $media->count() > 0;
    @endphp

    <ul style="list-style-type: none; padding-left: 0; font-size: 9pt; margin-bottom: 15px;">
        <li>{{ $hasDokumen ? '☑' : '☐' }} Daftar Hadir</li>
        <li>{{ $hasMedia ? '☑' : '☐' }} Foto & Video</li>
        <li>{{ $hasDokumen ? '☑' : '☐' }} Notulensi dan Rencana Tindak Lanjut (RTL)</li>
        <li>☐ Evaluasi Harian</li>
        <li>☐ Hasil Skoring Pre-Test dan Post-Test</li>
        <li>☐ Lainnya: _______________________</li>
    </ul>

    @if($hasDokumen || $hasMedia)
        <div style="margin-top: 15px; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
            <p style="font-weight: bold; margin-bottom: 8px;">Dokumen dan Media Terlampir:</p>

            @if($hasDokumen)
                <div style="margin-bottom: 10px;">
                    <p style="font-weight: bold; font-size: 9pt; margin-bottom: 5px;">Dokumen:</p>
                    <ol style="font-size: 8pt; margin-left: 20px;">
                        @foreach($dokumen as $doc)
                            <li style="margin-bottom: 3px;">
                                <a href="{{ $doc->getUrl() }}" style="color: #0056b3; text-decoration: none;">
                                    {{ $doc->file_name }}
                                </a>
                                <span style="color: #666;">({{ $doc->human_readable_size }})</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

            @if($hasMedia)
                <div>
                    <p style="font-weight: bold; font-size: 9pt; margin-bottom: 5px;">Media Pendukung (Foto/Video):</p>
                    <ol style="font-size: 8pt; margin-left: 20px;">
                        @foreach($media as $item)
                            <li style="margin-bottom: 3px;">
                                <a href="{{ $item->getUrl() }}" style="color: #0056b3; text-decoration: none;">
                                    {{ $item->file_name }}
                                </a>
                                <span style="color: #666;">({{ $item->human_readable_size }})</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
        </div>
    @else
        <p style="font-size: 9pt; color: #666;"><em>Tidak ada dokumen atau media pendukung yang dilampirkan.</em></p>
    @endif
</div>
