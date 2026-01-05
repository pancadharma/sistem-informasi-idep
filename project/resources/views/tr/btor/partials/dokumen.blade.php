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
                            <th width="30%">Nama File</th>
                            <th width="40%">Keterangan</th>
                            <th width="10%" class="text-center">Tipe</th>
                            <th width="10%" class="text-center">Ukuran</th>
                            <th width="10%" class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokumen as $doc)
                            <tr>
                                <td>
                                    <span class="no-print">
                                        <i class="fas fa-file-alt"></i>
                                    </span>
                                    {{ $doc->name }}
                                </td>
                                <td>{{ $doc->getCustomProperty('keterangan') ?? '-' }}</td>
                                <td class="text-center">{{ strtoupper($doc->extension) }}</td>
                                <td class="text-center">{{ $doc->human_readable_size }}</td>
                                <td class="no-print text-center">
                                    <a href="{{ $doc->getUrl() }}" target="_blank" class="btn btn-sm btn-link">
                                        <i class="fas fa-download"></i> Unduh
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
                            <th width="30%">Nama File</th>
                            <th width="40%">Keterangan</th>
                            <th width="10%" class="text-center">Tipe</th>
                            <th width="10%" class="text-center">Ukuran</th>
                            <th width="10%" class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($media as $item)
                            <tr>
                                <td class="media-item">
                                    <span class="print">
                                        <a href="{{ $item->getUrl() }}" target="_blank">
                                            <img src="{{ $item->getUrl() }}" alt="{{ $item->name }}" style="max-width: 250px; max-height: 250px;" class="media-item">
                                        </a>
                                    </span>
                                </td>
                                <td>{{ $item->getCustomProperty('keterangan') ?? '-' }}</td>
                                <td class="text-center">{{ strtoupper($item->extension) }}</td>
                                <td class="text-center">{{ $item->human_readable_size }}</td>
                                <td class="no-print text-center">
                                    <a href="{{ $item->getUrl() }}" target="_blank" class="btn btn-sm btn-link">
                                        <i class="fas fa-download"></i> Unduh
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