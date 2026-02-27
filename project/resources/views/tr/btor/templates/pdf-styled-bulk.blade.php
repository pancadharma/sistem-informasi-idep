<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>BTOR Bulk Export - {{ count($kegiatanList) }} Reports</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 1cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #000;
            background: white;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }

        .print-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

        .report-header {
            margin-bottom: 15px;
        }

        .report-header h2 {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center !important;
        }

        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            padding-bottom: 3px;
        }

        .table-print {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: auto;
        }

        .table-print td {
            padding: 3px 5px;
            vertical-align: top;
            border: none;
            font-size: 9pt;
            word-wrap: break-word;
        }

        .table-bordered {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
            font-size: 8pt;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .table-bordered th {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }

        .table-bordered thead th {
            background-color: #d0d0d0;
        }

        .table-active {
            background-color: #e8e8e8 !important;
            font-weight: bold;
        }

        .content-box {
            margin-bottom: 8px;
            min-height: 40px;
            word-wrap: break-word;
            text-align: justify;
        }

        .report-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            text-align: center;
            color: #666;
        }

        .page-break {
            page-break-before: always;
        }

        .page-break-after {
            page-break-after: always;
        }

        .text-muted {
            color: #666;
        }

        .subsection {
            font-size: 10pt;
            font-weight: bold;
        }

        ul {
            margin-left: 20px;
            margin-bottom: 10px;
        }

        li {
            margin-bottom: 5px;
        }

        hr {
            border: 1px solid #000;
            margin: 15px 0;
        }

        .location-section {
            margin-bottom: 15px;
        }

        .alert-info {
            background-color: #d9edf7;
            border-color: #bce8f1;
            color: #31708f;
            padding: 8px 12px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    @foreach($kegiatanList as $index => $item)
        @php
            $kegiatan = $item['kegiatan'];
            $viewPath = $item['viewPath'];
        @endphp

        <div class="print-container">

            {{-- Header Section --}}
            <div class="report-header text-center">
                <h2>{{ __('btor.btor') }}</h2>
            </div>

            {{-- Basic Information Table --}}
            <div class="section">
                <table class="table-print" style="font-size: 10pt; margin-bottom: 20px;">
                    <tr>
                        <td width="20%"><strong>{{ __('btor.departemen') }}</strong></td>
                        <td width="1%">:</td>
                        <td>Program</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('btor.program') }}</strong></td>
                        <td>:</td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('btor.nama_kegiatan') }}</strong></td>
                        <td>:</td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('btor.kode_budget') }}</strong></td>
                        <td>:</td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('btor.penulis_laporan') }}</strong></td>
                        <td>:</td>
                        <td>
                            {{ $kegiatan->kegiatan_penulis?->pluck('user.nama')->filter()->implode(', ') ?: __('btor.no_writer_activity') }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('btor.penulis_jabatan') }}</strong></td>
                        <td>:</td>
                        <td>
                            {{ $kegiatan->kegiatan_penulis?->pluck('peran.nama')->filter()->implode(', ') ?: '-' }}
                        </td>
                    </tr>
                </table>
            </div>

            <hr>

            {{-- 1. Latar Belakang Kegiatan --}}
            <div class="section">
                <h4 class="section-title">{{ __('btor.latar_belakang_kegiatan') }}</h4>
                <div class="content-box">
                   {!! $kegiatan->deskripsilatarbelakang ?? '<em class="text-muted"> ' . __('btor.no_background_activity') . '</em>' !!}
                </div>
            </div>

            {{-- 2. Tujuan Kegiatan --}}
            <div class="section">
                <h4 class="section-title">{{ __('btor.tujuan_kegiatan') }}</h4>
                <div class="content-box">
                    {!! $kegiatan->deskripsitujuan ?? '<em class="text-muted"> ' . __('btor.no_tujuan_activity') . '</em>' !!}
                </div>
            </div>

            {{-- 3. Detail Kegiatan --}}
            <div class="section">
                <h4 class="section-title">{{ __('btor.detail_kegiatan') }}</h4>

                <table class="table-print" style="font-size: 9pt; margin-bottom: 5px;">
                    <tr>
                        <td width="20%"><strong>{{ __('btor.tanggal_mulai') }}</strong></td>
                        <td width="1%">:</td>
                        <td>
                            @if($kegiatan->tanggalmulai && $kegiatan->tanggalselesai)
                                {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                @if($kegiatan->tanggalmulai != $kegiatan->tanggalselesai)
                                    - {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                @endif
                                ({{ $kegiatan->getDurationInDays() }} hari)
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('btor.pihak_yang_terlibat') }}</strong></td>
                        <td>:</td>
                        <td>
                            @if($kegiatan->mitra?->count() > 0)
                                <ul style="list-style-type: none; margin: 0; padding: 0;">
                                    @foreach($kegiatan->mitra as $mitra)
                                        <li>{{ $mitra->nama }}</li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>

                {{-- Location Table --}}
                <div class="location-section">
                    @if($kegiatan->lokasi?->count() > 0)
                        <table class="table-bordered" style="font-size: 8pt; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 18%;">{{ __('btor.lokasi') }}</th>
                                    <th style="width: 15%;">{{ __('btor.desa') }}</th>
                                    <th style="width: 15%;">{{ __('btor.kecamatan') }}</th>
                                    <th style="width: 17%;">{{ __('btor.kabupaten') }}</th>
                                    <th style="width: 15%;">{{ __('btor.provinsi') }}</th>
                                    <th style="width: 15%;">{{ __('btor.koordinat') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kegiatan->lokasi as $locIndex => $lokasi)
                                    <tr>
                                        <td class="text-center">{{ $locIndex + 1 }}</td>
                                        <td>{{ $lokasi->lokasi ?? '—' }}</td>
                                        <td>{{ $lokasi->desa?->nama ?? '-' }}</td>
                                        <td>{{ $lokasi->desa?->kecamatan?->nama ?? '-' }}</td>
                                        <td>{{ $lokasi->desa?->kecamatan?->kabupaten?->nama ?? '-' }}</td>
                                        <td>{{ $lokasi->desa?->kecamatan?->kabupaten?->provinsi?->nama ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($lokasi->lat && $lokasi->long)
                                                {{ number_format($lokasi->lat, 6) }},{{ number_format($lokasi->long, 6) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Summary --}}
                        <div style="margin-top: 10px; padding: 8px; background-color: #f0f0f0; border: 1px solid #ccc; font-size: 9pt;">
                            <strong>Location Summary:</strong>
                            @php
                                $provinces = $kegiatan->lokasi
                                    ->pluck('desa.kecamatan.kabupaten.provinsi.nama')
                                    ->filter()
                                    ->unique()
                                    ->values();

                                $districts = $kegiatan->lokasi
                                    ->pluck('desa.kecamatan.kabupaten.nama')
                                    ->filter()
                                    ->unique()
                                    ->values();
                            @endphp

                            {{ $kegiatan->lokasi->count() }} location(s) in
                            {{ $districts->count() }} district(s) across
                            {{ $provinces->count() }} province(s)

                            @if($provinces->count() > 0)
                                <br>
                                <small>Provinces: {{ $provinces->implode(', ') }}</small>
                            @endif
                        </div>
                    @else
                        <p><em>No location data available for this activity.</em></p>
                    @endif
                </div>

                {{-- Activity Type Specific Content --}}
                <div style="margin-top: 10px;">
                    <strong>{{ __('btor.detail_kegiatan_spesifik') }}:</strong>
                    @if($viewPath && View::exists($viewPath))
                        @include($viewPath, ['kegiatan' => $kegiatan])
                    @endif
                </div>
            </div>

            {{-- 4. Hasil Kegiatan --}}
            <div class="section page-break">
                <h4 class="section-title">{{ __('btor.hasil_kegiatan') }}</h4>

                {{-- 4a. Jumlah Partisipan --}}
                <h5 style="font-size: 10pt; font-weight: bold; margin-bottom: 10px;">{{ __('btor.jumlah_partisipan') }}</h5>

                <p style="font-size: 9pt; margin-bottom: 8px;"><em>{{ __('btor.tabel_disagregasi') }}</em></p>

                <table class="table-bordered" style="font-size: 8pt; margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th style="width: 40%;">{{ __('btor.penerima_manfaat') }}</th>
                            <th style="width: 15%;" class="text-center">{{ __('btor.perempuan') }}</th>
                            <th style="width: 15%;" class="text-center">{{ __('btor.laki_laki') }}</th>
                            <th style="width: 15%;" class="text-center">{{ __('btor.lainnya') }}</th>
                            <th style="width: 15%;" class="text-center">{{ __('btor.sub_total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ __('btor.dewasa') }} <em>( {{ __('btor.umur_25_59') }} )</em></td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasaperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasalakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatdewasatotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>{{ __('btor.lansia') }} <em>( {{ __('btor.umur_60_ke_atas') }} )</em></td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansiaperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansialakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlansiatotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>{{ __('btor.remaja') }} <em>( {{ __('btor.umur_18_24') }} )</em></td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajaperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajalakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatremajatotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>{{ __('btor.anak') }} <em>( {{ __('btor.umur_18_ke_bawah') }} )</em></td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanakperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanaklakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatanaktotal ?? 0) }}</strong></td>
                        </tr>
                        <tr class="table-active">
                            <td><strong>{{ __('btor.grand_total') }}</strong></td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                            <td class="text-center"><strong>0</strong></td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
                        </tr>
                    </tbody>
                </table>

                <p style="font-size: 9pt; margin-bottom: 8px;"><em>{{ __('btor.table_kelompok_khusus') }}:</em></p>

                <table class="table-bordered" style="font-size: 8pt; margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th style="width: 40%;">{{ __('btor.penerima_manfaat') }}</th>
                            <th style="width: 15%;" class="text-center">{{ __('btor.perempuan') }}</th>
                            <th style="width: 15%;" class="text-center">{{ __('btor.laki_laki') }}</th>
                            <th style="width: 15%;" class="text-center">{{ __('btor.lainnya') }}</th>
                            <th style="width: 15%;" class="text-center">{{ __('btor.sub_total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ __('btor.penyandang_disabilitas') }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitasperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatdisabilitastotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>{{ __('btor.non_disabilitas') }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatnondisabilitastotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>{{ __('btor.kelompok_marjinal_lainnya') }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinalperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinallakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatmarjinaltotal ?? 0) }}</strong></td>
                        </tr>
                        <tr class="table-active">
                            <td><strong>{{ __('btor.grand_total') }}</strong></td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                            <td class="text-center"><strong>0</strong></td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
                        </tr>
                    </tbody>
                </table>

                {{-- 4b. Hasil Pertemuan --}}
                <h5 style="font-size: 10pt; font-weight: bold; margin: 15px 0 10px 0;">b. Hasil Pertemuan</h5>
                <div class="content-box">
                    {!! $kegiatan->deskripsikeluaran ?? '<em>Tidak ada data hasil pertemuan</em>' !!}
                </div>
            </div>

            {{-- 5. Tantangan dan Solusi --}}
            <div class="section page-break">
                <h4 class="section-title">Tantangan dan Solusi</h4>
                <p style="font-size: 9pt; font-style: italic; margin-bottom: 10px;">
                    Silahkan isi dan jabarkan tantangan yang ditemui selama menjalankan kegiatan, serta solusinya berdasarkan
                    hasil evaluasi kegiatan (jika ada).
                </p>

                @if($kegiatan->assessment?->assessmentkendala || $kegiatan->pelatihan?->pelatihanisu)
                    <table class="table-bordered" style="font-size: 8pt;">
                        <thead>
                            <tr>
                                <th style="width: 10%;" class="text-center">No.</th>
                                <th style="width: 45%;">Tantangan</th>
                                <th style="width: 45%;">Solusi yang Diambil Tim</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kendala = $kegiatan->assessment?->assessmentkendala
                                    ?? $kegiatan->pelatihan?->pelatihanisu
                                    ?? $kegiatan->monitoring?->monitoringkendala
                                    ?? 'Tidak ada tantangan yang dicatat';
                                $solusi = $kegiatan->assessment?->assessmentpembelajaran
                                    ?? $kegiatan->pelatihan?->pelatihanpembelajaran
                                    ?? $kegiatan->monitoring?->monitoringpembelajaran
                                    ?? '-';
                            @endphp
                            <tr>
                                <td class="text-center">1</td>
                                <td>{!! $kendala !!}</td>
                                <td>{!! $solusi !!}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p><em>Tidak ada data tantangan dan solusi yang tersedia.</em></p>
                @endif
            </div>

            {{-- 6. Isu yang Perlu Diperhatikan & Rekomendasi --}}
            <div class="section">
                <h4 class="section-title">Isu yang Perlu Diperhatikan & Rekomendasi</h4>
                <p style="font-size: 9pt; font-style: italic; margin-bottom: 10px;">
                    Silahkan isi dan jabarkan isu-isu yang perlu diperhatikan (bersifat di luar kendali tim) selama menjalankan
                    kegiatan, serta rekomendasi yang perlu diambil berdasarkan hasil evaluasi kegiatan (jika ada).
                </p>

                @php
                    $isu = $kegiatan->assessment?->assessmentisu
                        ?? $kegiatan->pelatihan?->pelatihanisu
                        ?? $kegiatan->monitoring?->monitoringisu
                        ?? null;
                @endphp

                @if($isu)
                    <table class="table-bordered" style="font-size: 8pt;">
                        <thead>
                            <tr>
                                <th style="width: 10%;" class="text-center">No.</th>
                                <th style="width: 45%;">Isu yang Perlu Diperhatikan</th>
                                <th style="width: 45%;">Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
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
                    <p><em>Tidak ada isu yang perlu diperhatikan.</em></p>
                @endif
            </div>

            {{-- 7. Pembelajaran --}}
            <div class="section page-break">
                <h4 class="section-title">Pembelajaran</h4>
                <p style="font-size: 9pt; font-style: italic; margin-bottom: 10px;">
                    Silakan isi dan jabarkan pembelajaran apa yang bisa didapatkan selama proses pelaksanaan kegiatan.
                    Bisa diambil dari hasil evaluasi internal tim dan form FRM yang diisi partisipan.
                    Silakan fokus pada rekomendasi pembelajarannya.
                </p>

                <div class="content-box">
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
                        <em>Tidak ada data pembelajaran yang tersedia.</em>
                    @endif
                </div>
            </div>

            {{-- 8. Dokumen Pendukung --}}
            <div class="section">
                <h4 class="section-title">Media & Dokumen Pendukung</h4>
                @php
                    $dokumen = $kegiatan->getDokumenPendukung();
                    $media = $kegiatan->getMediaPendukung();
                @endphp

                @if(($dokumen && $dokumen->count() > 0) || ($media && $media->count() > 0))
                    @if($dokumen && $dokumen->count() > 0)
                        <div style="margin-bottom: 15px;">
                            <h5 class="subsection">Dokumen ({{ $dokumen->count() }})</h5>
                            <table class="table-bordered">
                                <thead>
                                    <tr>
                                        <th width="35%">Nama File</th>
                                        <th width="45%">Caption</th>
                                        <th width="10%">Tipe</th>
                                        <th width="10%">Ukuran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dokumen as $doc)
                                        <tr>
                                            <td>{{ $doc->name }}</td>
                                            <td>{{ $doc->getCustomProperty('keterangan') ?? $doc->name }}</td>
                                            <td>{{ strtoupper($doc->extension) }}</td>
                                            <td>{{ $doc->human_readable_size }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($media && $media->count() > 0)
                        <div style="margin-bottom: 15px;">
                            <h5 class="subsection">Media Pendukung ({{ $media->count() }})</h5>
                            <table class="table-bordered">
                                <thead>
                                    <tr>
                                        <th width="35%">Nama File</th>
                                        <th width="45%">Caption</th>
                                        <th width="10%">Tipe</th>
                                        <th width="10%">Ukuran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($media as $mediaItem)
                                        <tr>
                                            <td>{{ $mediaItem->name }}</td>
                                            <td>{{ $mediaItem->getCustomProperty('keterangan') ?? $mediaItem->name }}</td>
                                            <td>{{ strtoupper($mediaItem->extension) }}</td>
                                            <td>{{ $mediaItem->human_readable_size }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @else
                    <div class="alert-info">
                        Tidak ada dokumen atau media pendukung yang dilampirkan untuk kegiatan ini.
                    </div>
                @endif
            </div>

            {{-- 9. Catatan Penulis Laporan --}}
            <div class="section">
                <h4 class="section-title">Catatan Penulis Laporan</h4>
                <p style="font-size: 9pt; font-style: italic; margin-bottom: 10px;">
                    Silahkan tuliskan catatan spesifik dari penulis laporan jika ada informasi yang belum bisa tersampaikan
                    melalui bagian-bagian laporan di atas.
                </p>

                <div class="content-box" style="min-height: 80px;">
                    <p>-</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="report-footer">
                <p><strong>Yayasan IDEP Selaras Alam</strong></p>
                <p>Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia</p>
                <p>Telp/Fax +62-361-908-2983 / +62-812 4658 5137</p>
            </div>
        </div>

        @if(!$loop->last)
            <div class="page-break-after"></div>
        @endif
    @endforeach
</body>
</html>