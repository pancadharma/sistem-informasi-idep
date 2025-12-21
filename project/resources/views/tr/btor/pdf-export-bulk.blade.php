@extends('tr.btor.layouts.pdf-layout')

@section('title', 'BTOR Bulk Export - ' . count($kegiatanList) . ' Reports')

@push('pdf-styles')
<style>
    .page-break-before {
        page-break-before: always;
    }
    
    .report-divider {
        margin: 30px 0;
        page-break-before: always;
    }
</style>
@endpush

@section('header')
    {{-- Header will be included per report --}}
@endsection

@section('content')
@foreach($kegiatanList as $index => $item)
    @php
        $kegiatan = $item['kegiatan'];
        $viewPath = $item['viewPath'];
    @endphp

    @if($index > 0)
        <div class="report-divider"></div>
    @endif

    {{-- Individual Report Header --}}
    @include('tr.btor.partials.pdf-header', [
        'kegiatan' => $kegiatan,
        'headerConfig' => $headerConfig ?? []
    ])

    <div class="pdf-container">

        {{-- Basic Information Section --}}
        <div class="section">
            <table class="info-table">
                <tr>
                    <td class="label">Department</td>
                    <td class="separator">:</td>
                    <td class="value">Program</td>
                </tr>
                <tr>
                    <td class="label">Program</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Kegiatan</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Kode Budget</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Penulis Laporan</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $kegiatan->kegiatan_penulis?->pluck('user.nama')->filter()->implode(', ') ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $kegiatan->kegiatan_penulis?->pluck('peran.nama')->filter()->implode(', ') ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <hr class="thick">

        {{-- 1. Latar Belakang Kegiatan --}}
        <div class="section">
            <div class="section-title">
                <span class="section-number">1</span> Latar Belakang Kegiatan
            </div>
            <div class="content-box">
                {!! $kegiatan->deskripsilatarbelakang ?? '<em>Tidak ada data latar belakang</em>' !!}
            </div>
        </div>

        {{-- 2. Tujuan Kegiatan --}}
        <div class="section">
            <div class="section-title">
                <span class="section-number">2</span> Tujuan Kegiatan
            </div>
            <div class="content-box">
                {!! $kegiatan->deskripsitujuan ?? '<em>Tidak ada data tujuan</em>' !!}
            </div>
        </div>

        {{-- 3. Detail Kegiatan --}}
        <div class="section">
            <div class="section-title">
                <span class="section-number">3</span> Detail Kegiatan
            </div>

            <table class="info-table">
                <tr>
                    <td class="label">Hari, Tanggal</td>
                    <td class="separator">:</td>
                    <td class="value">
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
                    <td class="label">Pihak yang Terlibat</td>
                    <td class="separator">:</td>
                    <td class="value">
                        @if($kegiatan->mitra?->count() > 0)
                            <ul style="margin: 0; padding-left: 20px;">
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

            @include('tr.btor.partials.location')

            <div class="mt-3">
                <strong>Detail Kegiatan Spesifik:</strong>
                @include($viewPath, ['kegiatan' => $kegiatan])
            </div>
        </div>

        {{-- 4. Hasil Kegiatan --}}
        <div class="section">
            <div class="section-title">
                <span class="section-number">4</span> Hasil Kegiatan
            </div>

            <p class="mb-2"><strong>a. Jumlah Partisipan yang Terlibat dan Disagregat</strong></p>
            
            <p style="font-size: 9pt; margin-bottom: 8px;"><em>Tabel Disagregasi Berdasarkan Usia dan Jenis Kelamin:</em></p>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Penerima Manfaat</th>
                        <th style="width: 15%;">Perempuan</th>
                        <th style="width: 15%;">Laki-laki</th>
                        <th style="width: 15%;">Lainnya</th>
                        <th style="width: 15%;">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dewasa <em>(umur 25 sampai 59 tahun)</em></td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasaperempuan ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasalakilaki ?? 0) }}</td>
                        <td class="text-center">0</td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatdewasatotal ?? 0) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Lansia <em>(umur 60 ke atas)</em></td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansiaperempuan ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansialakilaki ?? 0) }}</td>
                        <td class="text-center">0</td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlansiatotal ?? 0) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Remaja <em>(umur 18 - 24 tahun)</em></td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajaperempuan ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajalakilaki ?? 0) }}</td>
                        <td class="text-center">0</td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatremajatotal ?? 0) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Anak <em>(umur 18 ke bawah)</em></td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanakperempuan ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanaklakilaki ?? 0) }}</td>
                        <td class="text-center">0</td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatanaktotal ?? 0) }}</strong></td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Grand Total</strong></td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                        <td class="text-center"><strong>0</strong></td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <p style="font-size: 9pt; margin-bottom: 8px;"><em>Tabel Disagregasi Berdasarkan Kelompok Khusus:</em></p>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Penerima Manfaat</th>
                        <th style="width: 15%;">Perempuan</th>
                        <th style="width: 15%;">Laki-laki</th>
                        <th style="width: 15%;">Lainnya</th>
                        <th style="width: 15%;">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Penyandang Disabilitas</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitasperempuan ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0) }}</td>
                        <td class="text-center">0</td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatdisabilitastotal ?? 0) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Non-disabilitas</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0) }}</td>
                        <td class="text-center">0</td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatnondisabilitastotal ?? 0) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Kelompok Marjinal Lainnya</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinalperempuan ?? 0) }}</td>
                        <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinallakilaki ?? 0) }}</td>
                        <td class="text-center">0</td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatmarjinaltotal ?? 0) }}</strong></td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Grand Total</strong></td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                        <td class="text-center"><strong>0</strong></td>
                        <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <p class="mb-2 mt-3"><strong>b. Hasil Pertemuan</strong></p>
            <div class="content-box">
                {!! $kegiatan->deskripsikeluaran ?? '<em>Tidak ada data hasil pertemuan</em>' !!}
            </div>
        </div>

        {{-- 5. Tantangan dan Solusi --}}
        <div class="section">
            <div class="section-title">
                <span class="section-number">5</span> Tantangan dan Solusi
            </div>

            @if($kegiatan->assessment?->assessmentkendala || $kegiatan->pelatihan?->pelatihanisu)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">No.</th>
                            <th style="width: 46%;">Tantangan</th>
                            <th style="width: 46%;">Solusi yang Diambil Tim</th>
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
            <div class="section-title">
                <span class="section-number">6</span> Isu yang Perlu Diperhatikan & Rekomendasi
            </div>

            @php
                $isu = $kegiatan->assessment?->assessmentisu
                    ?? $kegiatan->pelatihan?->pelatihanisu
                    ?? $kegiatan->monitoring?->monitoringisu
                    ?? null;
            @endphp

            @if($isu)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">No.</th>
                            <th style="width: 46%;">Isu yang Perlu Diperhatikan</th>
                            <th style="width: 46%;">Rekomendasi</th>
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
        <div class="section">
            <div class="section-title">
                <span class="section-number">7</span> Pembelajaran
            </div>

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
        @include('tr.btor.partials.dokumen')

        {{-- 9. Catatan Penulis Laporan --}}
        <div class="section">
            <div class="section-title">
                <span class="section-number">9</span> Catatan Penulis Laporan
            </div>
            <div class="content-box" style="min-height: 60px;">
                <p>-</p>
            </div>
        </div>

        {{-- Signature Section --}}
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-box">
                            <p><strong>Disusun oleh:</strong></p>
                            <div class="signature-line"></div>
                            <p>
                                @if($kegiatan->kegiatan_penulis?->first())
                                    <strong>{{ $kegiatan->kegiatan_penulis->first()->user?->nama }}</strong><br>
                                    <em>{{ $kegiatan->kegiatan_penulis->first()->peran?->nama ?? 'Staff' }}</em>
                                @else
                                    <strong>_____________________</strong><br>
                                    <em>Penulis Laporan</em>
                                @endif
                            </p>
                            <p><small>Tanggal: {{ now()->locale('id')->isoFormat('D MMMM Y') }}</small></p>
                        </div>
                    </td>
                    <td>
                        <div class="signature-box">
                            <p><strong>Disetujui oleh:</strong></p>
                            <div class="signature-line"></div>
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
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Individual Report Footer --}}
        @include('tr.btor.partials.pdf-footer', [
            'kegiatan' => $kegiatan,
            'footerConfig' => $footerConfig ?? []
        ])

    </div>
@endforeach
@endsection
