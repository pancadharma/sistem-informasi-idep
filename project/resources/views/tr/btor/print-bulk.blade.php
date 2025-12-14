@extends('tr.btor.layouts.print-layout')

@section('title', 'BTOR Bulk Print - ' . count($kegiatanList) . ' Reports')

@push('print-styles')
    <style>
        .page-break-before {
            page-break-before: always;
        }

        .report-separator {
            border: none;
            border-top: 3px dashed #333;
            margin: 30px 0;
        }

        .report-number-badge {
            background: #333;
            color: #fff;
            padding: 5px 15px;
            font-size: 12pt;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
        }

        @media print {
            .report-separator {
                display: none;
            }

            .report-number-badge {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    @foreach($kegiatanList as $index => $item)
        @php
            $kegiatan = $item['kegiatan'];
            $viewPath = $item['viewPath'];
        @endphp

        @if($index > 0)
            <div class="page-break-before"></div>
            <hr class="report-separator no-print">
        @endif

        <div class="report-number-badge no-print">
            Report {{ $index + 1 }} of {{ count($kegiatanList) }}
        </div>

        <div class="print-container">
            {{-- Header Section --}}
            <div class="report-header text-center">
                <h2>BACK TO OFFICE REPORT</h2>
                <h3>(BTOR)</h3>
            </div>

            {{-- Basic Information Table --}}
            <div class="section">
                <table class="table-print" style="font-size: 10pt; margin-bottom: 20px;">
                    <tr>
                        <td width="25%"><strong>Department</strong></td>
                        <td width="5%">:</td>
                        <td>Program</td>
                    </tr>
                    <tr>
                        <td><strong>Program</strong></td>
                        <td>:</td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Nama Kegiatan</strong></td>
                        <td>:</td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kode Budget</strong></td>
                        <td>:</td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Penulis Laporan</strong></td>
                        <td>:</td>
                        <td>
                            {{ $kegiatan->kegiatan_penulis?->pluck('user.nama')->filter()->implode(', ') ?: '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Jabatan</strong></td>
                        <td>:</td>
                        <td>
                            {{ $kegiatan->kegiatan_penulis?->pluck('peran.nama')->filter()->implode(', ') ?: '-' }}
                        </td>
                    </tr>
                </table>
            </div>

            <hr style="border: 1px solid #000; margin: 15px 0;">

            {{-- 1. Latar Belakang Kegiatan --}}
            <div class="section">
                <h4 class="section-title">Latar Belakang Kegiatan</h4>
                <div class="content-box">
                    {!! $kegiatan->deskripsilatarbelakang ?? '<em>Tidak ada data latar belakang</em>' !!}
                </div>
            </div>

            {{-- 2. Tujuan Kegiatan --}}
            <div class="section">
                <h4 class="section-title">Tujuan Kegiatan</h4>
                <div class="content-box">
                    {!! $kegiatan->deskripsitujuan ?? '<em>Tidak ada data tujuan</em>' !!}
                </div>
            </div>

            {{-- 3. Detail Kegiatan --}}
            <div class="section page-break">
                <h4 class="section-title">Detail Kegiatan</h4>

                <table class="table-print" style="font-size: 9pt; margin-bottom: 15px;">
                    <tr>
                        <td width="25%"><strong>Hari, Tanggal</strong></td>
                        <td width="5%">:</td>
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
                        <td><strong>Pihak yang Terlibat</strong></td>
                        <td>:</td>
                        <td>
                            @if($kegiatan->mitra?->count() > 0)
                                <ul style="margin: 5px 0; padding-left: 20px;">
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

                {{-- Activity Type Specific Content --}}
                <div class="mt-3">
                    <strong>Detail Kegiatan Spesifik:</strong>
                    @include($viewPath, ['kegiatan' => $kegiatan])
                </div>
            </div>

            {{-- 4. Hasil Kegiatan --}}
            <div class="section page-break">
                <h4 class="section-title">Hasil Kegiatan</h4>

                {{-- 4a. Jumlah Partisipan --}}
                <h5 style="font-size: 10pt; font-weight: bold; margin-bottom: 10px;">a. Jumlah Partisipan yang Terlibat dan
                    Disagregat</h5>

                <p style="font-size: 9pt; margin-bottom: 8px;"><em>Tabel Disagregasi Berdasarkan Usia dan Jenis Kelamin:</em>
                </p>

                <table class="table-bordered" style="font-size: 8pt; margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Penerima Manfaat</th>
                            <th style="width: 15%;" class="text-center">Perempuan</th>
                            <th style="width: 15%;" class="text-center">Laki-laki</th>
                            <th style="width: 15%;" class="text-center">Lainnya</th>
                            <th style="width: 15%;" class="text-center">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dewasa <em>(umur 25 sampai 59 tahun)</em></td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasaperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasalakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatdewasatotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Lansia <em>(umur 60 ke atas)</em></td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansiaperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansialakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatlansiatotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Remaja <em>(umur 18 - 24 tahun)</em></td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajaperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajalakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatremajatotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Anak <em>(umur 18 ke bawah)</em></td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanakperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanaklakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatanaktotal ?? 0) }}</strong></td>
                        </tr>
                        <tr class="table-active">
                            <td><strong>Grand Total</strong></td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                            <td class="text-center"><strong>0</strong></td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p style="font-size: 9pt; margin-bottom: 8px;"><em>Tabel Disagregasi Berdasarkan Kelompok Khusus:</em></p>

                <table class="table-bordered" style="font-size: 8pt; margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Penerima Manfaat</th>
                            <th style="width: 15%;" class="text-center">Perempuan</th>
                            <th style="width: 15%;" class="text-center">Laki-laki</th>
                            <th style="width: 15%;" class="text-center">Lainnya</th>
                            <th style="width: 15%;" class="text-center">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Penyandang Disabilitas</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitasperempuan ?? 0) }}
                            </td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatdisabilitastotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Non-disabilitas</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0) }}
                            </td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0) }}
                            </td>
                            <td class="text-center">0</td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatnondisabilitastotal ?? 0) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Kelompok Marjinal Lainnya</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinalperempuan ?? 0) }}</td>
                            <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinallakilaki ?? 0) }}</td>
                            <td class="text-center">0</td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatmarjinaltotal ?? 0) }}</strong></td>
                        </tr>
                        <tr class="table-active">
                            <td><strong>Grand Total</strong></td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                            <td class="text-center">
                                <strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                            <td class="text-center"><strong>0</strong></td>
                            <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong>
                            </td>
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
            @include('tr.btor.partials.dokumen')

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

            {{-- Signature Section --}}
            <div class="section" style="margin-top: 40px;">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="width: 50%; vertical-align: top; text-align: center;">
                            <div class="signature-box">
                                <p><strong>Disusun oleh:</strong></p>
                                <br><br><br>
                                <p>
                                    @if($kegiatan->kegiatan_penulis?->first())
                                        <strong><u>{{ $kegiatan->kegiatan_penulis->first()->user?->nama }}</u></strong><br>
                                        <em>{{ $kegiatan->kegiatan_penulis->first()->peran?->nama ?? 'Staff' }}</em>
                                    @else
                                        <strong><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong><br>
                                        <em>Penulis Laporan</em>
                                    @endif
                                </p>
                                <p style="margin-top: 5px;"><small>Tanggal:
                                        {{ now()->locale('id')->isoFormat('D MMMM Y') }}</small></p>
                            </div>
                        </td>
                        <td style="width: 50%; vertical-align: top; text-align: center;">
                            <div class="signature-box">
                                <p><strong>Disetujui oleh:</strong></p>
                                <br><br><br>
                                <p>
                                    @if($kegiatan->user)
                                        <strong><u>{{ $kegiatan->user->name }}</u></strong><br>
                                        <em>Program Coordinator</em>
                                    @else
                                        <strong><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong><br>
                                        <em>Supervisor</em>
                                    @endif
                                </p>
                                <p style="margin-top: 5px;"><small>Tanggal: _________________</small></p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Footer --}}
            <div class="report-footer">
                <table style="width: 100%; border: none; font-size: 8pt;">
                    <tr>
                        <td style="width: 70%;">
                            <strong>IDEP Foundation</strong> | Back to Office Report (BTOR)
                        </td>
                        <td style="width: 30%; text-align: right;">
                            Report ID: {{ $kegiatan->id }} | {{ now()->format('Y') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
@endsection