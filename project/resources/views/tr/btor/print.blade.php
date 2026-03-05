@extends('tr.btor.layouts.print-layout')

@section('title', 'BTOR - ' . ($kegiatan->programOutcomeOutputActivity?->nama ?? 'Report'))

@push('print-styles')
    <style>
        /* --- VIEW-SPECIFIC STYLES (print.blade.php) --- */
        /* Note: Base print layout is handled by style.blade.php */

        .print-container {
            max-width: 21cm; /* A4 Width */
            background-color: white;
            padding: 0;
        }

        /* HEADINGS */
        h1, h2, h3, h4, h5 {
            font-family: 'Tahoma', sans-serif;
            color: #000;
        }

        .section-title {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 15pt;
            margin-bottom: 5pt;
            text-transform: none;
            border: none; /* Remove any default web borders */
        }

        /* TABLES */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        /* Data Tables (Borders) */
        .table-bordered th, .table-bordered td {
            border: 1px solid #000 !important; /* Matches DOCX Border Size */
            padding: 4px;
            vertical-align: middle;
        }

        /* GREEN HEADERS (Crucial for Look) */
        .table-bordered thead th, 
        .table-bordered th {
            background-color: #385623 !important; /* Matches DOCX Green */
            color: #FFFFFF !important;
            font-weight: bold;
            text-align: center;
        }

        /* Info Tables (No Borders or specific styling) */
        .table-print td {
            padding: 2px 4px;
            vertical-align: top;
            border: none;
        }

        /* FOOTER STYLE */
        .report-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #0F7001; /* Matches DOCX Footer Green */
            border-top: 3px double #000; /* Mimics thinThickMediumGap */
            padding-top: 10px;
            page-break-inside: avoid;
        }
        .report-footer strong {
            color: #0D654D;
            font-weight: bold;
        }
        .report-footer p {
            margin: 2px 0;
        }

        /* UTILS */
        .text-center { text-align: center; }
        .page-break { page-break-after: always; }
        .content-box { text-align: justify; }
        
        /* Fix HTML content spacing */
        .content-box p { margin: 0 0 5px 0; }
        .content-box ul { margin: 0 0 5px 20px; padding: 0; }
    </style>
@endpush

@section('content')
<div class="print-container">
    {{-- Basic Information Table --}}
    <div class="section">
        {{-- Metadata Table (No Borders) --}}
        <div style="border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0; margin-bottom: 20px;">
            <table class="table-print">
                <tr>
                    <td width="25%"><strong>{{ __('btor.departemen') }}</strong></td>
                    <td width="2%">:</td>
                    <td>{{ __('btor.program') }}</td>
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
                {{-- REVIEW: Added Sektor Kegiatan field to print layout --}}
                <tr>
                    <td><strong>{{ __('btor.sektor_kegiatan') }}</strong></td>
                    <td>:</td>
                    <td>
                        {{ $kegiatan->sektor?->pluck('nama')->filter()->implode(', ') ?: '-' }}
                    </td>
                </tr>
                {{-- REVIEW: Added Fase Pelaporan field --}}
                <tr>
                    <td><strong>{{ __('btor.fase_pelaporan') }}</strong></td>
                    <td>:</td>
                    <td>
                        {{ $kegiatan->fasepelaporan ?: '-' }}
                    </td>
                </tr>
                {{-- NEW: Added Program Goals (Target & Indicator) --}}
                {{-- @php
                    $programGoal = $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->goal;
                @endphp
                @if($programGoal)
                <tr>
                    <td><strong>{{ __('btor.program_target') }}</strong></td>
                    <td>:</td>
                    <td>{{ $programGoal->target ?: '-' }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('btor.program_indicator') }}</strong></td>
                    <td>:</td>
                    <td>{{ $programGoal->indikator ?: '-' }}</td>
                </tr>
                @endif --}}
            </table>
        </div>
    </div>

    {{-- 1. Latar Belakang Kegiatan --}}
    <div class="section">
        <h4 class="section-title">A. {{ __('btor.latar_belakang_kegiatan') }}</h4>
        <div class="content-box">
           {!! $kegiatan->deskripsilatarbelakang ?? '-' !!}
        </div>
    </div>

    {{-- 2. Tujuan Kegiatan --}}
    <div class="section">
        <h4 class="section-title">B. {{ __('btor.tujuan_kegiatan') }}</h4>
        <div class="content-box">
            {!! $kegiatan->deskripsitujuan ?? '-' !!}
        </div>
    </div>

    {{-- 3. Detail Kegiatan --}}
    <div class="section">
        <h4 class="section-title">C. {{ __('btor.detail_kegiatan') }}</h4>

        <table class="table-print" style="margin-bottom: 5px;">
            <tr>
                <td width="25%"><strong>{{ __('btor.tanggal_mulai') }}</strong></td>
                <td width="2%">:</td>
                <td>
                    @if($kegiatan->tanggalmulai)
                        {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        @if($kegiatan->tanggalselesai && $kegiatan->tanggalmulai != $kegiatan->tanggalselesai)
                            - {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        @endif
                        ({{ $kegiatan->getDurationInDays() }} {{ __('btor.hari') }})
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.tempat') }}</strong></td>
                <td>:</td>
                <td>
                    <ul style="margin: 0; padding-left: 15px;">
                        @forelse($kegiatan->lokasi as $lok)
                            <li>{{ $lok->lokasi }}, {{ $lok->desa?->nama }}, {{ $lok->desa?->kecamatan?->nama }}</li>
                        @empty
                            <li>-</li>
                        @endforelse
                    </ul>
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('cruds.partner.title') }} / Partner</strong></td>
                <td>:</td>
                <td>
                    {{ $kegiatan->mitra->map(fn($m) => $m->nama)->filter()->implode(', ') ?: '-' }}
                </td>
            </tr>
        </table>

        {{-- Location Table (Assuming partial uses table-bordered) --}}
        @if($kegiatan->lokasi && count($kegiatan->lokasi) > 0)
            <div style="font-weight: bold; margin-top: 10px;">{{ __('btor.tabel_lokasi') }}</div>
            @include('tr.btor.partials.location')
        @endif
        
    </div>

    {{-- 4. Hasil Kegiatan --}}
    <div class="section">
        <h4 class="section-title">D. {{ __('btor.hasil.label') }}</h4>

        {{-- Dynamic Activity Content --}}
        @include('tr.btor.partials.hasil-dinamis-print', ['kegiatan' => $kegiatan])

        {{-- 4a. Jumlah Partisipan --}}
        <div style="font-weight: bold; margin-bottom: 5px;">a. {{ __('btor.partisipan_disagregat') }}</div>
        {{-- REVIEW: Removed "Silakan mengisi tabel berikut" placeholder --}}

        @if($kegiatan->penerimamanfaattotal > 0)
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th style="width: 20%;">{{ __('btor.penerima_manfaat') }}</th>
                        <th style="width: 15%;">{{ __('btor.perempuan') }}</th>
                        <th style="width: 15%;">{{ __('btor.laki_laki') }}</th>
                        <th style="width: 15%;">{{ __('btor.lainnya') }}</th>
                        <th style="width: 15%;">{{ __('btor.sub_total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ __('btor.umur_25_59') }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasaperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasalakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasalainnya ?? 0 }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasatotal }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('btor.umur_60_ke_atas') }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansiaperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansialakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansialainnya ?? 0 }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansiatotal }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('btor.umur_18_24') }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajaperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajalakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajalainnya ?? 0 }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajatotal }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('btor.umur_18_kebawah') }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanakperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaklakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaklainnya ?? 0 }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaktotal }}</td>
                    </tr>
                    <tr style="font-weight: bold; background-color: #f2f2f2;">
                        <td>{{ strtoupper(__('btor.grand_total')) }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatperempuantotal }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlakilakitotal }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlainnyatotal ?? 0}}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaattotal }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="font-weight: bold; margin: 10px 0 5px 0;">{{ __('btor.table_kelompok_khusus') }}</div>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th style="width: 20%;">{{ __('btor.penerima_manfaat') }}</th>
                        <th style="width: 15%;">{{ __('btor.perempuan') }}</th>
                        <th style="width: 15%;">{{ __('btor.laki_laki') }}</th>
                        <th style="width: 15%;">{{ __('btor.lainnya') }}</th>
                        <th style="width: 15%;">{{ __('btor.sub_total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ __('btor.penyandang_disabilitas') }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdisabilitasperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdisabilitaslakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdisabilitaslainnya ?? 0 }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdisabilitastotal }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('btor.non_disabilitas') }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatnondisabilitasperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatnondisabilitaslakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatnondisabilitaslainnya ?? 0 }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatnondisabilitastotal }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('btor.kelompok_marjinal_lainnya') }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatmarjinalperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatmarjinallakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatmarjinallainnya ?? 0 }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatmarjinaltotal }}</td>
                    </tr>
                    <tr style="font-weight: bold; background-color: #f2f2f2;">
                        <td>{{ strtoupper(__('btor.grand_total')) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlainnyatotal ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>{{ __('btor.no_data_participants') }}</p>
        @endif

        {{-- 4b. Hasil Pertemuan --}}
        <div style="font-weight: bold; margin: 10px 0 5px 0;">b. {{ __('cruds.kegiatan.description.deskripsikeluaran') }}</div>
        <div class="content-box">
            {!! $kegiatan->deskripsikeluaran ?? '-' !!}
        </div>
    </div>

    {{-- E. Tantangan dan Solusi --}}
    <div class="section">
        <h4 class="section-title">E. {{ __('btor.tantangan_solusi') }}</h4>
        
        @php
            // Extract kendala (challenges) from all 11 jenis kegiatan types
            $kendala = $kegiatan->assessment?->assessmentkendala
                    ?? $kegiatan->sosialisasi?->sosialisasikendala
                    ?? $kegiatan->pelatihan?->pelatihankendala
                    ?? $kegiatan->pembelanjaan?->pembelanjaankendala
                    ?? $kegiatan->pengembangan?->pengembangankendala
                    ?? $kegiatan->kampanye?->kampanyekendala
                    ?? $kegiatan->monitoring?->monitoringkendala
                    ?? $kegiatan->kunjungan?->kunjungankendala
                    ?? $kegiatan->konsultasi?->konsultasikendala
                    ?? $kegiatan->pemetaan?->pemetaankendala
                    ?? $kegiatan->lainnya?->lainnyakendala
                    ?? null;
        @endphp

        <div class="content-box">
            {!! $kendala ?? __('btor.no_data_tantang_solusi') !!}
        </div>
    </div>

    {{-- F. Isu yang Perlu Diperhatikan --}}
    <div class="section">
        <h4 class="section-title">F. {{ __('btor.hasil.assessmentisu') }}</h4>
        
        @php
            // Extract isu (issues) from all 11 jenis kegiatan types
            $isu = $kegiatan->assessment?->assessmentisu
                ?? $kegiatan->sosialisasi?->sosialisasiisu
                ?? $kegiatan->pelatihan?->pelatihanisu
                ?? $kegiatan->pembelanjaan?->pembelanjaanisu
                ?? $kegiatan->pengembangan?->pengembanganisu
                ?? $kegiatan->kampanye?->kampanyeisu
                ?? $kegiatan->pemetaan?->pemetaanisu
                ?? $kegiatan->monitoring?->monitoringisu
                ?? $kegiatan->kunjungan?->kunjunganisu
                ?? $kegiatan->konsultasi?->konsultasiisu
                ?? $kegiatan->lainnya?->lainnyaisu
                ?? null;
        @endphp

        <div class="content-box">
            {!! $isu ?? __('global.no_results') !!}
        </div>
    </div>

    {{-- G. Pembelajaran --}}
    <div class="section">
        <h4 class="section-title">G. {{ __('btor.hasil.assessmentpembelajaran') }}</h4>
        
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

            {!! $pembelajaran ?? __('btor.no_data_pembelajaran') !!}
        </div>
    </div>

    {{-- H. Dokumen --}}
    <div class="section">
        <h4 class="section-title">H. {{ __('btor.dokumen_pendukung') }}</h4>
        @include('tr.btor.partials.dokumen')
    </div>

    {{-- I. Catatan Penulis --}}
    <div class="section">
        <h4 class="section-title">I. {{ __('btor.catatan_penulis_laporan') }}</h4>
        <div class="content-box">
            {!! $kegiatan->catatan_penulis ?? '-' !!}
        </div>
    </div>

    {{-- J. Indikasi Perubahan --}}
    <div class="section">
        <h4 class="section-title">J. {{ __('btor.indikasi_perubahan') }}</h4>
        <div class="content-box">
            {!! $kegiatan->indikasi_perubahan ?? '-' !!}
        </div>
    </div>

    {{-- Footer --}}
    {{-- <div class="report-footer">
        <p><strong>Yayasan IDEP Selaras Alam</strong></p>
        <p>Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia</p>
        <p>Telp/Fax +62-361-908-2983 / +62-812 4658 5137</p>
        <p>Dihasilkan pada: {{ date('d-m-Y H:i:s') }}</p>
    </div> --}}
</div>
@endsection