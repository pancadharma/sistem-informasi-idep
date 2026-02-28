@extends('tr.btor.layouts.pdf-layout')

@section('title', 'Laporan Kegiatan - ' . ($kegiatan->programOutcomeOutputActivity?->nama ?? 'Jules View'))

@push('print-styles')
<style>
    .header-jules {
        background-color: #1a5c28;
        color: white;
        padding: 15px;
        margin-bottom: 20px;
        text-align: center;
    }
    .header-jules h2 {
        margin: 0;
        font-size: 18pt;
    }
    .activity-name {
        font-size: 16pt;
        font-weight: bold;
        color: #1a5c28;
        margin-bottom: 5px;
    }
    .program-info {
        background-color: #f8f9fa;
        border-left: 5px solid #28a745;
        padding: 10px;
        margin-bottom: 20px;
    }
    .section-header {
        border-bottom: 2px solid #1a5c28;
        padding-bottom: 5px;
        margin-top: 20px;
        margin-bottom: 10px;
        color: #1a5c28;
        font-size: 12pt;
        font-weight: bold;
    }
    .metrics-table {
        width: 100%;
        margin-bottom: 20px;
    }
    .metrics-table td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }
    .metric-value {
        font-size: 14pt;
        font-weight: bold;
        display: block;
    }
    .metric-label {
        font-size: 8pt;
        text-transform: uppercase;
        color: #666;
    }
</style>
@endpush

@section('content')
<div class="print-container">
    <div class="header-jules">
        <h2>BACK TO OFFICE REPORT</h2>
        <p>{{ strtoupper($kegiatan->status ?? 'DRAFT') }}</p>
    </div>

    <div class="report-header">
        <div class="activity-name">{{ $kegiatan->programOutcomeOutputActivity?->nama ?? 'Activity Name Not Set' }}</div>
        <div class="text-muted">Kode: {{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</div>
    </div>

    <div class="program-info">
        <strong>Program:</strong> {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}<br>
        <strong>Jenis Kegiatan:</strong> {{ $kegiatan->jenisKegiatan?->nama ?? '-' }}<br>
        <strong>Fase Pelaporan:</strong> {{ $kegiatan->fasepelaporan ?? '-' }}
    </div>

    <table class="metrics-table">
        <tr>
            <td>
                <span class="metric-value">{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</span>
                <span class="metric-label">Perempuan</span>
            </td>
            <td>
                <span class="metric-value">{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</span>
                <span class="metric-label">Laki-Laki</span>
            </td>
            <td style="background-color: #f0fdf4;">
                <span class="metric-value">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</span>
                <span class="metric-label">Total Partisipan</span>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-header">Partisipan Disagregat</div>
        <table class="table-bordered" style="text-align: center;">
            <thead>
                <tr>
                    <th style="text-align: left;">Kategori</th>
                    <th>Wanita</th>
                    <th>Pria</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: left;">Dewasa (25-59)</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatdewasaperempuan }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatdewasalakilaki }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatdewasatotal }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Lansia (60+)</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatlansiaperempuan }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatlansialakilaki }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatlansiatotal }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Remaja (18-24)</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatremajaperempuan }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatremajalakilaki }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatremajatotal }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Anak (&lt; 18)</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatanakperempuan }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatanaklakilaki }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatanaktotal }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td style="text-align: left;">TOTAL USIA</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatperempuantotal }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatlakilakitotal }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaattotal }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Disabilitas</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatdisabilitasperempuan }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatdisabilitaslakilaki }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatdisabilitastotal }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Marjinal Lainnya</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatmarjinalperempuan }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatmarjinallakilaki }}</td>
                    <td>{{ (int)$kegiatan->penerimamanfaatmarjinaltotal }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-header">1. {{ __('cruds.kegiatan.description.latar_belakang') }}</div>
        <div class="content-box">
            {!! $kegiatan->deskripsilatarbelakang ?? '-' !!}
        </div>
    </div>

    <div class="section">
        <div class="section-header">2. {{ __('cruds.kegiatan.description.tujuan') }}</div>
        <div class="content-box">
            {!! $kegiatan->deskripsitujuan ?? '-' !!}
        </div>
    </div>

    <div class="section">
        <div class="section-header">3. {{ __('btor.detail_kegiatan') }} & Hasil</div>
        <table class="table-print">
            <tr>
                <td width="30%"><strong>Waktu Pelaksanaan</strong></td>
                <td width="2%">:</td>
                <td>
                    @if($kegiatan->tanggalmulai)
                        {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        @if($kegiatan->tanggalselesai && $kegiatan->tanggalmulai != $kegiatan->tanggalselesai)
                            - {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        @endif
                        ({{ $durationInDays }} Hari)
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Lokasi</strong></td>
                <td>:</td>
                <td>
                    @forelse($kegiatan->lokasi as $lok)
                        {{ $lok->lokasi }}, {{ $lok->desa?->nama }}, {{ $lok->desa?->kecamatan?->nama }}<br>
                    @empty
                        -
                    @endforelse
                </td>
            </tr>
            <tr>
                <td><strong>Mitra/Pihak Terlibat</strong></td>
                <td>:</td>
                <td>
                    {{ $kegiatan->mitra->pluck('nama')->implode(', ') ?: '-' }}
                </td>
            </tr>
        </table>

        @include('tr.kegiatan.partials.dynamic_results_jules_pdf')
    </div>

    <div class="section">
        <div class="section-header">4. {{ __('cruds.kegiatan.description.deskripsikeluaran') }}</div>
        <div class="content-box">
            {!! $kegiatan->deskripsikeluaran ?? '-' !!}
        </div>
    </div>

    <div class="section" style="page-break-inside: avoid;">
        <table style="width: 100%; border: none;">
            <tr>
                <td width="50%" style="vertical-align: top; padding-right: 10px;">
                    <div class="section-header">{{ __('cruds.kegiatan.hasil.catatan_penulis') }}</div>
                    <div class="content-box small">
                        {!! $kegiatan->catatan_penulis ?? '-' !!}
                    </div>
                </td>
                <td width="50%" style="vertical-align: top; padding-left: 10px;">
                    <div class="section-header">{{ __('cruds.kegiatan.hasil.indikasi_perubahan') }}</div>
                    <div class="content-box small">
                        {!! $kegiatan->indikasi_perubahan ?? '-' !!}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-header">Tim Pelaksana & Penulis</div>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kegiatan->kegiatan_penulis as $penulis)
                    <tr>
                        <td>{{ $penulis->user?->nama ?? '-' }}</td>
                        <td>{{ $penulis->peran?->nama ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center">Tidak ada data penulis.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
