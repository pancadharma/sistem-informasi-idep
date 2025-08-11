    <div class="container">
        <div class="header">
            <h1>BACK TO OFFICE REPORT</h1>
            <p>Laporan Kegiatan</p>
        </div>

        <div class="content">
            <div class="section-title">Informasi Dasar</div>
            <table>
                <tr>
                    <th style="width: 30%;">Kode Program</th>
                    <td>{{ $kegiatan->activity?->program_outcome_output?->program_outcome?->program?->kode ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nama Program</th>
                    <td>{{ $kegiatan->activity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kode Kegiatan</th>
                    <td>{{ $kegiatan->activity?->kode ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nama Kegiatan</th>
                    <td>{{ $kegiatan->activity?->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Penulis Laporan</th>
                    <td>
                        @foreach ($kegiatan->datapenulis as $penulis)
                            {{ $penulis->nama ?? '' }}{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>
                        @foreach ($kegiatan->datapenulis as $penulis)
                            {{ $penulis->kegiatanPeran?->nama ?? '' }}{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>Jenis Kegiatan</th>
                    <td>{{ $kegiatan->jenisKegiatan?->nama ?? '' }}</td>
                </tr>
                <tr>
                    <th>Sektor</th>
                    <td>
                        @foreach ($kegiatan->sektor as $key => $value)
                            {{ $value->nama ?? '' }}{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>Fase Pelaporan</th>
                    <td>{{ $kegiatan->fasepelaporan ?? '' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Mulai</th>
                    <td>{{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d-m-Y') ?? '' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Selesai</th>
                    <td>{{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d-m-Y') ?? '' }}</td>
                </tr>
                <tr>
                    <th>Durasi</th>
                    <td>{{ $durationInDays ?? '-' }} hari</td>
                </tr>
                <tr>
                    <th>Mitra</th>
                    <td>
                        @foreach ($kegiatan->mitra as $partner)
                            {{ $partner->nama ?? '' }}{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $kegiatan->status ?? '' }}</td>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <td>
                        @if ($kegiatan->lokasi->isNotEmpty())
                            {{ $kegiatan->lokasi->unique('kabupaten_id')->pluck('desa.kecamatan.kabupaten.nama')->implode(', ') }}
                            @if ($kegiatan->lokasi->unique('provinsi_id')->count() == 1)
                                ,
                                {{ $kegiatan->lokasi->first()?->desa?->kecamatan?->kabupaten?->provinsi?->nama ?? '' }}
                            @endif
                        @endif
                    </td>
                </tr>
            </table>

            <div class="section-title">Hierarki Program</div>
            <table>
                <tr>
                    <th style="width: 30%;">Program Outcome</th>
                    <td>{{ $kegiatan->activity?->program_outcome_output?->program_outcome?->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Program Output</th>
                    <td>{{ $kegiatan->activity?->program_outcome_output?->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Target Kegiatan</th>
                    <td>
                        @if($kegiatan->activity?->target_reinstra)
                            {{ $kegiatan->activity?->target_reinstra?->target_value ?? 0 }} {{ $kegiatan->activity?->target_reinstra?->satuan?->nama ?? '' }}
                        @else
                            Tidak ada target
                        @endif
                    </td>
                </tr>
            </table>

            <div class="section-title">Detail Lokasi</div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Tempat</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kegiatan->lokasi as $lokasi)
                        <tr>
                            <td>
                                @if ($lokasi->lat && $lokasi->long)
                                    <a href="https://www.google.com/maps?q={{ $lokasi->lat }},{{ $lokasi->long }}"
                                        target="_blank">
                                        {{ ucwords(strtolower($lokasi->lokasi ?? 'Lihat Di Peta')) }}
                                    </a>
                                @else
                                    {{ $lokasi->lokasi ?? '—' }}
                                @endif
                            </td>
                            <td>{{ $lokasi->long ?? '—' }}</td>
                            <td>{{ $lokasi->lat ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center;">Tidak ada data lokasi tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="section-title">Ringkasan Peserta</div>
            <table>
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Wanita</th>
                        <th>Pria</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dewasa</td>
                        <td>{{ $kegiatan->penerimamanfaatdewasaperempuan ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatdewasalakilaki ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatdewasatotal ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Lansia</td>
                        <td>{{ $kegiatan->penerimamanfaatlansiaperempuan ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatlansialakilaki ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatlansiatotal ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Remaja</td>
                        <td>{{ $kegiatan->penerimamanfaatremajaperempuan ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatremajalakilaki ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatremajatotal ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Anak-anak</td>
                        <td>{{ $kegiatan->penerimamanfaatanakperempuan ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatanaklakilaki ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatanaktotal ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <th>{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}</th>
                        <th>{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}</th>
                        <th>{{ $kegiatan->penerimamanfaattotal ?? 0 }}</th>
                    </tr>
                </tbody>
            </table>

            <div class="section-title">Ringkasan Disabilitas</div>
            <table>
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Wanita</th>
                        <th>Pria</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Disabilitas</td>
                        <td>{{ $kegiatan->penerimamanfaatdisabilitasperempuan ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatdisabilitastotal ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Non-Disabilitas</td>
                        <td>{{ $kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0 }}</td>
                        <td>{{ $kegiatan->penerimamanfaatnondisabilitastotal ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="section-title">Deskripsi</div>
            <div class="description">
                <strong>Latar Belakang</strong>
                <div>{!! $kegiatan->deskripsilatarbelakang ?? '' !!}</div>
            </div>
            <div class="description">
                <strong>Tujuan</strong>
                <div>{!! $kegiatan->deskripsitujuan ?? '' !!}</div>
            </div>
            <div class="description">
                <strong>Keluaran</strong>
                <div>{!! $kegiatan->deskripsikeluaran ?? '' !!}</div>
            </div>

            @if ($kegiatan->jeniskegiatan_id == 1 && $kegiatan->assessment)
                @include('tr.kegiatan.export_assessment')
            @elseif ($kegiatan->jeniskegiatan_id == 2 && $kegiatan->sosialisasi)
                @include('tr.kegiatan.export_sosialisasi')
            @elseif ($kegiatan->jeniskegiatan_id == 3 && $kegiatan->pelatihan)
                @include('tr.kegiatan.export_pelatihan')
            @elseif ($kegiatan->jeniskegiatan_id == 4 && $kegiatan->pembelanjaan)
                @include('tr.kegiatan.export_pembelanjaan')
            @elseif ($kegiatan->jeniskegiatan_id == 5 && $kegiatan->pengembangan)
                @include('tr.kegiatan.export_pengembangan')
            @elseif ($kegiatan->jeniskegiatan_id == 6 && $kegiatan->kampanye)
                @include('tr.kegiatan.export_kampanye')
            @elseif ($kegiatan->jeniskegiatan_id == 7 && $kegiatan->pemetaan)
                @include('tr.kegiatan.export_pemetaan')
            @elseif ($kegiatan->jeniskegiatan_id == 8 && $kegiatan->monitoring)
                @include('tr.kegiatan.export_monitoring')
            @elseif ($kegiatan->jeniskegiatan_id == 9 && $kegiatan->kunjungan)
                @include('tr.kegiatan.export_kunjungan')
            @elseif ($kegiatan->jeniskegiatan_id == 10 && $kegiatan->konsultasi)
                @include('tr.kegiatan.export_konsultasi')
            @elseif ($kegiatan->jeniskegiatan_id == 11 && $kegiatan->lainnya)
                @include('tr.kegiatan.export_lainnya')
            @endif

            <div class="section-title">Dokumen Pendukung</div>
            @if($kegiatan->getMedia('dokumen_pendukung')->count() > 0)
                <ul>
                    @foreach($kegiatan->getMedia('dokumen_pendukung') as $media)
                        <li><a href="{{ $media->getUrl() }}" target="_blank">{{ $media->name }}</a> ({{ $media->human_readable_size }})</li>
                    @endforeach
                </ul>
            @else
                <p>Tidak ada dokumen pendukung.</p>
            @endif

            <div class="section-title">Media Pendukung</div>
            @if($kegiatan->getMedia('media_pendukung')->count() > 0)
                <ul>
                    @foreach($kegiatan->getMedia('media_pendukung') as $media)
                        <li><a href="{{ $media->getUrl() }}" target="_blank">{{ $media->name }}</a> ({{ $media->human_readable_size }})</li>
                    @endforeach
                </ul>
            @else
                <p>Tidak ada media pendukung.</p>
            @endif
        </div>
    </div>