<!DOCTYPE html>
<html>
<head>
    <title>Kegiatan Export</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #333;
        }
        .content {
            margin-bottom: 25px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .content th, .content td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        .content th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 10px;
        }
        .content td {
            font-size: 10px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 12px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            color: #000;
        }
        .description {
            margin-top: 10px;
            font-size: 10px;
            line-height: 1.3;
        }
        .table-header {
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .empty-cell {
            color: #666;
            font-style: italic;
        }
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .map-link {
            color: #0066cc;
            text-decoration: none;
        }
        .map-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
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
                    <td class="empty-cell">{{ $kegiatan->activity?->program_outcome_output?->program_outcome?->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Program Output</th>
                    <td class="empty-cell">{{ $kegiatan->activity?->program_outcome_output?->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Target Kegiatan</th>
                    <td>
                        @if($kegiatan->activity?->target_reinstra)
                            <span class="empty-cell">{{ $kegiatan->activity?->target_reinstra?->target_value ?? 0 }} {{ $kegiatan->activity?->target_reinstra?->satuan?->nama ?? '' }}</span>
                        @else
                            <span class="empty-cell">Tidak ada target</span>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="section-title">Detail Lokasi</div>
            <table>
                <thead>
                    <tr class="table-header">
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
                                       target="_blank"
                                       class="map-link">
                                        {{ ucwords(strtolower($lokasi->lokasi ?? 'Lihat Di Peta')) }}
                                    </a>
                                @else
                                    <span class="empty-cell">{{ $lokasi->lokasi ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="empty-cell">{{ $lokasi->long ?? '—' }}</td>
                            <td class="empty-cell">{{ $lokasi->lat ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; font-style: italic;">Tidak ada data lokasi tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="section-title">Ringkasan Peserta</div>
            <table>
                <thead>
                    <tr class="table-header">
                        <th>Peserta</th>
                        <th>Wanita</th>
                        <th>Pria</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dewasa</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatdewasaperempuan ?? 0 }}</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatdewasalakilaki ?? 0 }}</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatdewasatotal ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Lansia</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatlansiaperempuan ?? 0 }}</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatlansialakilaki ?? 0 }}</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatlansiatotal ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Remaja</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatremajaperempuan ?? 0 }}</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatremajalakilaki ?? 0 }}</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatremajatotal ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Anak-anak</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatanakperempuan ?? 0 }}</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatanaklakilaki ?? 0 }}</td>
                        <td class="empty-cell">{{ $kegiatan->penerimamanfaatanaktotal ?? 0 }}</td>
                    </tr>
                    <tr class="total-row">
                        <th>Total</th>
                        <th class="empty-cell">{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}</th>
                        <th class="empty-cell">{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}</th>
                        <th class="empty-cell">{{ $kegiatan->penerimamanfaattotal ?? 0 }}</th>
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
            <div class="description">
                <strong>Yang Dikaji</strong>
                <div>{!! $kegiatan->deskripsiyangdikaji ?? '' !!}</div>
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

            <div class="section-title">Tantangan dan Solusi</div>
            @php
                $kendala = $kegiatan->assessment?->assessmentkendala
                    ?? $kegiatan->pelatihan?->pelatihanisu
                    ?? $kegiatan->monitoring?->monitoringkendala
                    ?? null;
                $solusi = $kegiatan->assessment?->assessmentpembelajaran
                    ?? $kegiatan->pelatihan?->pelatihanpembelajaran
                    ?? $kegiatan->monitoring?->monitoringpembelajaran
                    ?? null;
            @endphp

            @if($kendala || $solusi)
                <table>
                    <thead>
                        <tr class="table-header">
                            <th style="width: 50%;">Tantangan</th>
                            <th style="width: 50%;">Solusi yang Diambil Tim</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{!! $kendala ?? 'Tidak ada data' !!}</td>
                            <td>{!! $solusi ?? 'Tidak ada data' !!}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <p class="empty-cell">Tidak ada data tantangan dan solusi yang tersedia.</p>
            @endif

            <div class="section-title">Isu yang Perlu Diperhatikan & Rekomendasi</div>
            @php
                $isu = $kegiatan->assessment?->assessmentisu
                    ?? $kegiatan->pelatihan?->pelatihanisu
                    ?? $kegiatan->monitoring?->monitoringisu
                    ?? null;
            @endphp
            @if($isu)
                <table>
                    <thead>
                        <tr class="table-header">
                            <th style="width: 50%;">Isu yang Perlu Diperhatikan</th>
                            <th style="width: 50%;">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{!! $isu !!}</td>
                            <td>
                                {!! $kegiatan->assessment?->assessmentpembelajaran
                                    ?? $kegiatan->pelatihan?->pelatihanpembelajaran
                                    ?? '-' !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @else
                <p class="empty-cell">Tidak ada isu yang perlu diperhatikan.</p>
            @endif

            <div class="section-title">Pembelajaran</div>
            <div class="description">
                @php
                    $pembelajaran = $kegiatan->assessment?->assessmentpembelajaran
                        ?? $kegiatan->pelatihan?->pelatihanpembelajaran
                        ?? $kegiatan->monitoring?->monitoringpembelajaran
                        ?? $kegiatan->sosialisasi?->sosialisasipembelajaran
                        ?? $kegiatan->kampanye?->kampanyepembelajaran
                        ?? $kegiatan->konsultasi?->konsultasipembelajaran
                        ?? $kegiatan->kunjungan?->kunjunganpembelajaran
                        ?? $kegiatan->pembelanjaan?->pembelanjaanpembelajaran
                        ?? $kegiatan->pengembangan?->pengembanganpembelajaran
                        ?? $kegiatan->pemetaan?->pemetaanpembelajaran
                        ?? $kegiatan->lainnya?->lainnyapembelajaran;
                @endphp
                @if($pembelajaran)
                    {!! $pembelajaran !!}
                @else
                    <span class="empty-cell">Tidak ada data pembelajaran yang tersedia.</span>
                @endif
            </div>

            {{-- <div class="section-title">Catatan Penulis Laporan</div>
            <div class="description" style="min-height: 50px; border: 1px solid #ddd; padding: 10px;">
                -
            </div> 
            <table style="width: 100%; border: none;">
                <tr style="border: none;">
                    <td style="width: 50%; border: none; text-align: center;">
                        <p><strong>Disusun oleh:</strong></p>
                        <br><br><br>
                        <p>
                            @if($kegiatan->datapenulis->first())
                                <strong>{{ $kegiatan->datapenulis->first()->nama }}</strong><br>
                                <em>{{ $kegiatan->datapenulis->first()->kegiatanPeran?->nama ?? 'Staff' }}</em>
                            @else
                                <strong>_____________________</strong><br>
                                <em>Penulis Laporan</em>
                            @endif
                        </p>
                        <p><small>Tanggal: {{ now()->locale('id')->isoFormat('D MMMM Y') }}</small></p>
                    </td>
                    <td style="width: 50%; border: none; text-align: center;">
                        <p><strong>Disetujui oleh:</strong></p>
                        <br><br><br>
                        <p>
                            @if($kegiatan->user)
                                <strong>{{ $kegiatan->user->name }}</strong><br>
                                <em>Program Coordinator</em>
                            @else
                                <strong>_____________________</strong><br>
                                <em>Supervisor</em>
                            @endif
                        </p>
                        <p><small>Tanggal: _________________</small></p>
                    </td>
                </tr>
            </table> --}}

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
</body>
</html>
