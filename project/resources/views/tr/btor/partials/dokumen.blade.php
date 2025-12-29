<div class="section">
    <h4 class="section-title">Media & Dokumen Pendukung</h4>
    @php
        $dokumen = $kegiatan->getDokumenPendukung();
        $media = $kegiatan->getMediaPendukung();
    @endphp

    @if(($dokumen && $dokumen->count() > 0) || ($media && $media->count() > 0))

        {{-- Dokumen Pendukung --}}
        @if($dokumen && $dokumen->count() > 0)
            <div class="mb-4">
                <h5 class="subsection"><i class="fas fa-file-alt"></i> Dokumen ({{ $dokumen->count() }})</h5>
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th width="25%">Nama File</th>
                            <th width="35%">Caption</th>
                            <th width="5%">Tipe</th>
                            <th width="5%" class="text-center">Ukuran</th>
                            <th width="5%" class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokumen as $index => $doc)
                            <tr>
                                <td class="pl-2">
                                    <i class="fas fa-file-{{ $doc->extension === 'pdf' ? 'pdf text-danger' : ($doc->extension === 'docx' || $doc->extension === 'doc' ? 'word text-primary' : ($doc->extension === 'xlsx' || $doc->extension === 'xls' ? 'excel text-success' : 'alt')) }}"></i>
                                    {{ $doc->name }}
                                </td>
                                <td>
                                    {{ $doc->getCustomProperty('keterangan') ?? $doc->name }}
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ strtoupper($doc->extension) }}</span>
                                </td>
                                <td>{{ $doc->human_readable_size }}</td>
                                <td class="no-print text-center">
                                    <a href="{{ $doc->getUrl() }}"
                                    target="_blank"
                                    class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ $doc->getUrl() }}"
                                    download
                                    class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i>
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
                <h5 class="subsection"><i class="fas fa-images"></i> Media Pendukung ({{ $media->count() }})</h5>

                {{-- Grid View for Images --}}
                {{-- use table format --}}
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th width="25%">Nama File</th>
                            <th width="35%">Caption</th>
                            <th width="5%">Tipe</th>
                            <th width="5%" class="text-center">Ukuran</th>
                            <th width="5%" class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($media as $index => $item)
                            <tr>
                                <td class="pl-2">
                                    <i class="fas fa-{{ $item->extension === 'jpg' || $item->extension === 'jpeg' || $item->extension === 'png' || $item->extension === 'gif' ? 'image' : 'video' }}"></i>
                                    {{ $item->name }}
                                </td>
                                <td> {{ $item->getCustomProperty('keterangan') ?? $item->name }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ strtoupper($item->extension) }}</span>
                                </td>
                                <td>{{ $item->human_readable_size }}</td>
                                <td class="no-print">
                                    <a href="{{ $item->getUrl() }}"
                                    target="_blank"
                                    class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ $item->getUrl() }}"
                                    download
                                    class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        @endif
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Tidak ada dokumen atau media pendukung yang dilampirkan untuk kegiatan ini.
        </div>
    @endif
</div>



