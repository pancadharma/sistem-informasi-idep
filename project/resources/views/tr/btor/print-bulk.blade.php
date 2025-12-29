@extends('tr.btor.layouts.print-layout')

@section('title', 'BTOR Bulk Print - ' . count($kegiatanList) . ' Reports')

@push('print-styles')
    <style>
        /* --- COPY OF GLOBAL STYLES FROM SINGLE PRINT --- */
        @media print {
            @page {
                margin: 2.5cm;
                size: A4 portrait;
            }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .report-separator {
                display: none;
            }
            .page-break-before {
                page-break-before: always;
            }
        }

        body {
            font-family: 'Tahoma', sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            background: #fff;
        }

        .print-container {
            max-width: 21cm;
            margin: 0 auto;
            background: white;
            padding: 0;
        }

        /* HEADINGS & SECTIONS */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 15pt;
            margin-bottom: 5pt;
            text-transform: none;
        }

        /* TABLES */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        /* GREEN TABLE HEADERS */
        .table-bordered th, .table-bordered td {
            border: 1px solid #000 !important;
            padding: 4px;
            vertical-align: middle;
        }
        .table-bordered thead th, .table-bordered th {
            background-color: #385623 !important;
            color: #FFFFFF !important;
            font-weight: bold;
            text-align: center;
        }

        .table-print td {
            padding: 2px 4px;
            vertical-align: top;
            border: none;
        }

        /* FOOTER */
        .report-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #0F7001;
            border-top: 3px double #000;
            padding-top: 10px;
            page-break-inside: avoid;
        }
        .report-footer strong {
            color: #0D654D;
            font-weight: bold;
        }

        /* UTILS */
        .text-center { text-align: center; }
        .content-box p { margin: 0 0 5px 0; }
        
        /* BULK SPECIFIC */
        .report-separator {
            border: none;
            border-top: 3px dashed #ccc;
            margin: 40px 0;
        }
        .report-badge {
            background: #eee;
            padding: 5px 10px;
            font-size: 10pt;
            margin-bottom: 20px;
            display: inline-block;
            border: 1px solid #ccc;
        }
    </style>
@endpush

@section('content')
    @foreach($kegiatanList as $index => $item)
        @php
            $kegiatan = $item['kegiatan'];
            $viewPath = $item['viewPath']; // Used if you still want to include specific partials
        @endphp

        @if($index > 0)
            <div class="page-break-before"></div>
            <hr class="report-separator no-print">
        @endif

        <div class="print-container">
            {{-- Badge (Screen Only) --}}
            <div class="no-print report-badge">
                Report {{ $index + 1 }} of {{ count($kegiatanList) }}
            </div>

            {{-- HEADER --}}
            {{-- <div class="report-header text-center" style="margin-bottom: 20px;">
                @if(file_exists(public_path('images/uploads/header.png')))
                    <img src="{{ asset('images/uploads/header.png') }}" style="height: 38px; width: auto;">
                @else
                    <h2 style="font-size: 14pt; font-weight: bold; margin: 0;">YAYASAN IDEP</h2>
                @endif
            </div> --}}

            {{-- METADATA --}}
            <div class="section">
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
                            <td>{{ $kegiatan->kegiatan_penulis?->pluck('user.nama')->filter()->implode(', ') ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- A. Latar Belakang --}}
            <div class="section">
                <h4 class="section-title">A. {{ __('btor.latar_belakang_kegiatan') }}</h4>
                <div class="content-box">{!! $kegiatan->deskripsilatarbelakang ?? '-' !!}</div>
            </div>

            {{-- B. Tujuan --}}
            <div class="section">
                <h4 class="section-title">B. {{ __('btor.tujuan_kegiatan') }}</h4>
                <div class="content-box">{!! $kegiatan->deskripsitujuan ?? '-' !!}</div>
            </div>

            {{-- C. Detail --}}
            <div class="section">
                <h4 class="section-title">C. {{ __('btor.detail_kegiatan') }}</h4>
                <table class="table-print">
                    <tr>
                        <td width="25%"><strong>{{ __('btor.tanggal_mulai') }}</strong></td>
                        <td width="2%">:</td>
                        <td>
                            @if($kegiatan->tanggalmulai)
                                {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                @if($kegiatan->tanggalselesai && $kegiatan->tanggalmulai != $kegiatan->tanggalselesai)
                                    - {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('btor.tempat') }}</strong></td>
                        <td>:</td>
                        <td>{{ $kegiatan->lokasi->map(fn($l) => $l->lokasi)->filter()->implode(', ') ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('btor.pihak_yang_terlibat') }}</strong></td>
                        <td>:</td>
                        <td>{{ $kegiatan->mitra->map(fn($m) => $m->nama)->filter()->implode(', ') ?: '-' }}</td>
                    </tr>
                </table>

                @if($kegiatan->lokasi && count($kegiatan->lokasi) > 0)
                    <div style="font-weight: bold; margin-top: 10px;">Tabel Lokasi</div>
                    @include('tr.btor.partials.location')
                @endif
            </div>

            {{-- D. Hasil Kegiatan --}}
            <div class="section">
                <h4 class="section-title">D. {{ __('btor.hasil.label') }}</h4>
                <div style="font-weight: bold; margin-bottom: 5px;">a. {{ __('btor.partisipan_disagregat') }}</div>

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
                            {{-- Rows for beneficiaries (same logic as single) --}}
                            <tr>
                                <td>Dewasa (25-59 tahun)</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasaperempuan }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasalakilaki }}</td>
                                <td class="text-center">{{ (int)$kegiatan->penerimamanfaatdewasatotal }}</td>
                            </tr>
                            {{-- Add other rows (Lansia, Remaja, Anak) as needed... --}}
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

                <div style="font-weight: bold; margin: 10px 0 5px 0;">b. {{ __('btor.hasil_pertemuan') }}</div>
                <div class="content-box">{!! $kegiatan->deskripsikeluaran ?? '-' !!}</div>
            </div>

            {{-- E, F, G (Specific Data) --}}
            {{-- Note: For bulk, using the dynamic 'getSpecificKegiatanData' result passed from controller 
                 would be cleaner, but if using standard object: --}}
            
            <div class="section">
                <h4 class="section-title">E. {{ __('btor.tantangan_solusi') }}</h4>
                <div class="content-box">
                    @php
                        $kendala = $kegiatan->assessment?->assessmentkendala
                                ?? $kegiatan->pelatihan?->pelatihanisu
                                ?? $kegiatan->monitoring?->monitoringkendala
                                ?? null;
                    @endphp
                    {!! $kendala ?? 'Tidak ada data tantangan.' !!}
                </div>
            </div>

            <div class="section">
                <h4 class="section-title">F. Isu / Rekomendasi</h4>
                <div class="content-box">
                    {!! $kegiatan->assessment?->assessmentisu ?? 'Tidak ada data isu.' !!}
                </div>
            </div>

            <div class="section">
                <h4 class="section-title">G. Pembelajaran</h4>
                <div class="content-box">
                     {!! $kegiatan->assessment?->assessmentpembelajaran ?? 'Tidak ada data pembelajaran.' !!}
                </div>
            </div>

             {{-- H. Dokumen --}}
            <div class="section">
                <h4 class="section-title">H. Dokumen Pendukung</h4>
                @include('tr.btor.partials.dokumen')
            </div>

            {{-- FOOTER --}}
            {{-- <div class="report-footer">
                <p><strong>Yayasan IDEP Selaras Alam</strong></p>
                <p>Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia</p>
                <p>Telp/Fax +62-361-908-2983 / +62-812 4658 5137</p>
                <p>Dihasilkan pada: {{ date('d-m-Y H:i:s') }}</p>
            </div> --}}
        </div>
    @endforeach
@endsection