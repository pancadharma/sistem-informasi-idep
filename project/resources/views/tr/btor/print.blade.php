@extends('tr.btor.layouts.print-layout')

@section('title', 'BTOR - ' . ($kegiatan->programOutcomeOutputActivity?->nama ?? 'Report'))

@push('print-styles')
    <style>
        /* --- GLOBAL DOCX/PDF MIMIC STYLES --- */
        @media print {
            @page {
                margin: 2.5cm; /* Matches DOCX Margins */
                size: A4 portrait;
            }
            body {
                -webkit-print-color-adjust: exact !important; /* Forces Green Backgrounds to print */
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
        }

        body {
            font-family: 'Tahoma', sans-serif; /* Matches DOCX Font */
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            background: #fff;
        }

        .print-container {
            max-width: 21cm; /* A4 Width */
            margin: 0 auto;
            background: white;
            padding: 0; /* Padding handled by @page in print, or margin in screen */
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

    {{-- Header Section (Matches PDF Image Logic) --}}
    <div class="report-header text-center" style="margin-bottom: 20px;">
        @if(file_exists(public_path('images/uploads/header.png')))
            <img src="{{ asset('images/uploads/header.png') }}" style="height: 38px; width: auto;">
        @else
            <h2 style="font-size: 14pt; font-weight: bold; margin: 0;">YAYASAN IDEP</h2>
        @endif
    </div>

    {{-- Basic Information Table --}}
    <div class="section">
        {{-- Metadata Table (No Borders) --}}
        <div style="border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0; margin-bottom: 20px;">
            <table class="table-print">
                <tr>
                    <td width="25%"><strong>{{ __('btor.departemen') }}</strong></td>
                    <td width="2%">:</td>
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
                        ({{ $kegiatan->getDurationInDays() }} hari)
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.tempat') }}</strong></td>
                <td>:</td>
                <td>
                    {{ $kegiatan->lokasi->map(fn($l) => $l->lokasi)->filter()->implode(', ') ?: '-' }}
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.pihak_yang_terlibat')}}</strong></td>
                <td>:</td>
                <td>
                    {{ $kegiatan->mitra->map(fn($m) => $m->nama)->filter()->implode(', ') ?: '-' }}
                </td>
            </tr>
        </table>

        {{-- Location Table (Assuming partial uses table-bordered) --}}
        @if($kegiatan->lokasi && count($kegiatan->lokasi) > 0)
            <div style="font-weight: bold; margin-top: 10px;">Tabel Lokasi</div>
            @include('tr.btor.partials.location')
        @endif
        
        {{-- Activity Specifics --}}
        {{-- <div class="mt-3">
            @include($viewPath, ['kegiatan' => $kegiatan])
        </div> --}}
    </div>

    {{-- 4. Hasil Kegiatan --}}
    <div class="section">
        <h4 class="section-title">D. {{ __('btor.hasil.label') }}</h4>

        {{-- 4a. Jumlah Partisipan --}}
        <div style="font-weight: bold; margin-bottom: 5px;">a. {{ __('btor.partisipan_disagregat') }}</div>
        <p style="margin-bottom: 5px;">Silakan mengisi tabel berikut:</p>

        @if($kegiatan->penerimamanfaattotal > 0)
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th style="width: 40%;">{{ __('btor.penerima_manfaat') }}</th>
                        <th style="width: 15%;">{{ __('btor.perempuan') }}</th>
                        <th style="width: 15%;">{{ __('btor.laki_laki') }}</th>
                        <th style="width: 15%;">{{ __('btor.sub_total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dewasa (25-59 tahun)</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasaperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasalakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasatotal }}</td>
                    </tr>
                    <tr>
                        <td>Lansia (60+ tahun)</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansiaperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansialakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlansiatotal }}</td>
                    </tr>
                    <tr>
                        <td>Remaja (18-24 tahun)</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajaperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajalakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatremajatotal }}</td>
                    </tr>
                    <tr>
                        <td>Anak (< 18 tahun)</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanakperempuan }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaklakilaki }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatanaktotal }}</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>GRAND TOTAL</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatperempuantotal }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlakilakitotal }}</td>
                        <td class="text-center">{{ (int)$kegiatan->penerimamanfaattotal }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>Tidak ada data penerima manfaat.</p>
        @endif

        {{-- 4b. Hasil Pertemuan --}}
        <div style="font-weight: bold; margin: 10px 0 5px 0;">b. {{ __('btor.hasil_pertemuan') }}</div>
        <div class="content-box">
            {!! $kegiatan->deskripsikeluaran ?? '-' !!}
        </div>
    </div>

    {{-- 5. Tantangan dan Solusi --}}
    <div class="section">
        <h4 class="section-title">E. {{ __('btor.tantangan_solusi') }}</h4>
        
        @php
            $kendala = $kegiatan->assessment?->assessmentkendala
                    ?? $kegiatan->pelatihan?->pelatihanisu
                    ?? $kegiatan->monitoring?->monitoringkendala
                    ?? $kegiatan->sosialisasi?->sosialisasikendala
                    ?? $kegiatan->lainnya?->lainnyakendala
                    ?? null;
        @endphp

        <div class="content-box">
            {!! $kendala ?? 'Tidak ada data tantangan.' !!}
        </div>
    </div>

    {{-- 6. Isu yang Perlu Diperhatikan --}}
    <div class="section">
        <h4 class="section-title">F. Isu yang Perlu Diperhatikan / Rekomendasi</h4>
        
        @php
            $isu = $kegiatan->assessment?->assessmentisu
                ?? $kegiatan->pelatihan?->pelatihanisu
                ?? $kegiatan->monitoring?->monitoringisu
                ?? $kegiatan->lainnya?->lainnyaisu
                ?? null;
        @endphp

        <div class="content-box">
            {!! $isu ?? 'Tidak ada data isu.' !!}
        </div>
    </div>

    {{-- 7. Pembelajaran --}}
    <div class="section">
        <h4 class="section-title">G. Pembelajaran</h4>
        
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

            {!! $pembelajaran ?? 'Tidak ada data pembelajaran.' !!}
        </div>
    </div>

    {{-- 8. Dokumen --}}
    <div class="section">
        <h4 class="section-title">H. Dokumen Pendukung</h4>
        @include('tr.btor.partials.dokumen')
    </div>

    {{-- Footer --}}
    <div class="report-footer">
        <p><strong>Yayasan IDEP Selaras Alam</strong></p>
        <p>Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia</p>
        <p>Telp/Fax +62-361-908-2983 / +62-812 4658 5137</p>
        <p>Dihasilkan pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</div>
@endsection