{{-- resources/views/tr/btor/partials/dokumen.blade.php --}}
<div class="section">
    {{-- Only show title if there is content --}}
    @php
        $dokumen = $kegiatan->getDokumenPendukung();
        $media = $kegiatan->getMediaPendukung();
    @endphp

    @if(($dokumen && $dokumen->count() > 0) || ($media && $media->count() > 0))
        
        {{-- Dokumen Pendukung --}}
        @if($dokumen && $dokumen->count() > 0)
            <div class="mb-4">
                <h5 class="section-title" style="margin-top: 10px; font-size: 10pt;">Dokumen Pendukung ({{ $dokumen->count() }})</h5>
                <table class="table-bordered" style="width: 100%; font-size: 9pt;">
                    <thead>
                        <tr>
                            <th width="85%">Keterangan</th>
                            <th width="15%" class="text-center">LINK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokumen as $doc)
                            <tr>
                                <td>{{ $doc->getCustomProperty('keterangan') ?? $doc->name }}</td>
                                <td class="text-center">
                                    <a href="{{ $doc->getUrl() }}" target="_blank" class="btn btn-sm btn-link font-weight-bold p-0">
                                        LINK <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Media Pendukung --}}
        @if($media && $media->count() > 0)
            <div class="mb-4">
                <h5 class="section-title" style="margin-top: 10px; font-size: 10pt;">Media Pendukung ({{ $media->count() }})</h5>
                <table class="table-bordered" style="width: 100%; font-size: 9pt;">
                    <thead>
                        <tr>
                            <th width="85%">Keterangan</th>
                            <th width="15%" class="text-center">LINK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($media as $item)
                            <tr>
                                <td>
                                    @if(str_starts_with($item->mime_type, 'image/'))
                                        <div class="mb-2 d-none no-print" style="display: none !important;">
                                            <a href="{{ $item->getUrl() }}" target="_blank">
                                                <img src="{{ $item->getUrl() }}" alt="{{ $item->name }}" style="max-width: 250px; max-height: 250px;" class="media-item">
                                            </a>
                                        </div>
                                    @endif
                                    <div>{{ $item->getCustomProperty('keterangan') ?? $item->name }}</div>
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ $item->getUrl() }}" target="_blank" class="btn btn-sm btn-link font-weight-bold p-0">
                                        LINK <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    @else
        <p><em>Tidak ada dokumen atau media pendukung.</em></p>
    @endif
</div>