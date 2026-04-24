<!DOCTYPE html>
<html>
<head>
    <title>BACK TO OFFICE REPORT - {{ $kegiatan->nama }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
            margin: 0.5cm 1cm;
        }
        .header-img {
            text-align: left;
            margin-bottom: 20px;
        }
        .header-img img {
            width: 100%;
            max-width: 600px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .meta-table td {
            vertical-align: top;
            padding: 2px 5px;
        }
        .meta-table .label {
            width: 150px;
        }
        .meta-table .colon {
            width: 10px;
        }
        .section-header {
            font-weight: bold;
            border-bottom: 2px solid #000;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 2px;
        }
        .content {
            margin-bottom: 20px;
        }
        .detail-table {
            width: 100%;
            margin-left: 20px;
        }
        .detail-table td {
            vertical-align: top;
            padding: 2px 5px;
        }
        .detail-table .label {
            width: 130px;
        }
        .detail-table .colon {
            width: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            border-top: 3px double #000;
            padding-top: 5px;
            font-size: 8pt;
        }
        .footer .company {
            color: #008000;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .footer .address {
            color: #008000;
            margin-bottom: 2px;
        }
        .footer .social {
            color: #0000FF;
        }
        .footer .social a {
            color: #0000FF;
            text-decoration: none;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
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
        <div class="header-img">
            <img src="{{ public_path('images/uploads/header.png') }}" alt="Header">
        </div>
        
        <div class="header-title" style="text-align: center;"><h2>BACK TO OFFICE REPORT</h2></div>
        <div class="content">
            <div class="section-title">
                <div class="informasi-dasar">
                    <div class="header-info">
                        <p><strong>Department :</strong> Program</p>
                        <p><strong>Program :</strong> {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program->nama ?? 'N/A' }}</p>
                        <p><strong>Nama Kegiatan :</strong> {{ $kegiatan->programOutcomeOutputActivity?->nama ?? 'N/A' }}</p>
                        <p><strong>Kode budget :</strong> {{ $kegiatan->programOutcomeOutputActivity?->kode ?? 'N/A' }}</p>
                        <p><strong>Penulis laporan :</strong> {{ $kegiatan->kegiatan_penulis->map(fn($p) => $p->user->nama)->join(', ') }}</p>
                        <p><strong>Jabatan :</strong> {{ $kegiatan->kegiatan_penulis->map(fn($p) => $p->peran->nama)->join(', ') }}</p>
                    </div>
                </div>
                
                            {{-- <table>
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
                            </table> --}}
            </div>

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
    <div class="footer">
        <div class="company">Yayasan IDEP Selaras Alam</div>
        <div class="address">Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia | Telp/Fax +62-361-908-2983 / +61-812 4658 5137</div>
        <div class="social">
            <a href="http://bit.ly/2vcX5My">IDEP on FACEBOOK</a> | 
            <a href="http://bit.ly/2uDFEDh">IDEP on TWITTER</a> | 
            <a href="http://www.ideptraining.com">INFO ABOUT IDEP TRAININGS</a> | 
            <a href="http://bit.ly/2u3n6sw">IDEP on YouTube</a>
        </div>
    </div>
</body>
</html>
