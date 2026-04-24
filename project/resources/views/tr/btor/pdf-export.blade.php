@extends('tr.btor.layouts.pdf-layout')

@section('title', 'BTOR - ' . $kegiatan->programOutcomeOutputActivity?->nama)


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
<div class="print-container">
    <header>
        <img src="{{ public_path('images/uploads/header.png') }}" alt="IDEP Header">
    </header>
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

    <hr style="border: 1px solid #000; margin: 15px 0;">

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
                <td><strong>{{ __('btor.tempat') }}</strong></td>
                <td>:</td>
                <td>
                    @if($kegiatan->lokasi?->count() > 0)
                        @php
                            $lokasiList = $kegiatan->lokasi->map(function($lok) {
                                $parts = array_filter([
                                    $lok->lokasi,
                                ]);
                                return implode(', ', $parts);
                            });
                        @endphp
                        {{ $lokasiList->implode('; ') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>{{ __('btor.pihak_yang_terlibat')}}</strong></td>
                <td>:</td>
                <td>
                    @if($kegiatan->mitra?->count() > 0)
                        <ul style="list-style-type: none;">
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
            <strong>{{ __('btor.jenis_kegiatan') }}</strong>
            @include($viewPath, ['kegiatan' => $kegiatan])
        </div>
    </div>

    {{-- 4. Hasil Kegiatan --}}
    <div class="section page-break">
        <h4 class="section-title">{{ __('btor.hasil.label') }}</h4>

        {{-- 4a. Jumlah Partisipan --}}
        <h5 style="font-size: 10pt; font-weight: bold; margin-bottom: 10px;">a. {{ __('btor.partisipan_disagregat') }} </h5>

        <p style="font-size: 9pt; margin-bottom: 8px;"><em>{{ __('btor.table_partisipan') }}:</em></p>

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
                    <td>{{ __('btor.dewasa') }} <em>({{ __('btor.umur_25_59') }})</em></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasaperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasalakilaki ?? 0) }}</td>
                    <td class="text-center">0</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatdewasatotal ?? 0) }}</strong></td>
                </tr>
                <tr>
                    <td>{{ __('btor.lansia') }} <em>({{ __('btor.umur_60') }})</em></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansiaperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansialakilaki ?? 0) }}</td>
                    <td class="text-center">0</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlansiatotal ?? 0) }}</strong></td>
                </tr>
                <tr>
                    <td>{{ __('btor.remaja') }} <em>({{ __('btor.umur_18_24') }})</em></td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajaperempuan ?? 0) }}</td>
                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajalakilaki ?? 0) }}</td>
                    <td class="text-center">0</td>
                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatremajatotal ?? 0) }}</strong></td>
                </tr>
                <tr>
                    <td>{{ __('btor.anak') }} <em>({{ __('btor.umur_18_kebawah') }})</em></td>
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

        <p style="font-size: 9pt; margin-bottom: 8px;"><em>
            {{ __('btor.table_kelompok_khusus') }}:</em></p>

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
        <h5 style="font-size: 10pt; font-weight: bold; margin: 15px 0 10px 0;">b. {{ __('cruds.kegiatan.description.deskripsikeluaran') }}</h5>
        <div class="content-box">
            {!! $kegiatan->deskripsikeluaran ?? '<em>Tidak ada data hasil pertemuan</em>' !!}
        </div>
    </div>

    {{-- 5. Tantangan dan Solusi --}}
    <div class="section page-break">
        <h4 class="section-title">{{ __('btor.tantangan_solusi') }}</h4>
        <p style="font-size: 9pt; font-style: italic; margin-bottom: 10px;">
            {{ __('btor.tantangan_solusi_ket') }}
        </p>

        @if($kegiatan->assessment?->assessmentkendala || $kegiatan->pelatihan?->pelatihanisu)
            <table class="table-bordered" style="font-size: 8pt;">
                <thead>
                    <tr>
                        <th style="width: 10%;" class="text-center">No.</th>
                        <th style="width: 45%;">{{ __('btor.tantangan') }}</th>
                        <th style="width: 45%;">{{ __('btor.solusi') }}</th>
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
            <p><em>.</em></p>
        @endif
    </div>

    {{-- 6. Isu yang Perlu Diperhatikan & Rekomendasi --}}
    <div class="section">
        <h4 class="section-title">Isu yang Perlu Diperhatikan & Rekomendasi</h4>
        <p style="font-size: 9pt; font-style: italic; margin-bottom: 10px;">
            Silahkan isi dan jabarkan isu-isu yang perlu diperhatikan (bersifat di luar kendali tim) selama menjalankan kegiatan, serta rekomendasi yang perlu diambil berdasarkan hasil evaluasi kegiatan (jika ada).
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
            Silahkan tuliskan catatan spesifik dari penulis laporan jika ada informasi yang belum bisa tersampaikan melalui bagian-bagian laporan di atas.
        </p>

        <div class="content-box" style="min-height: 80px;">
            <p>-</p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="report-footer">
        {{-- <div class="company">Yayasan IDEP Selaras Alam</div>
        <div class="address">Office & Demosite : Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia</div>
        <div class="address">Telp/Fax +62-361-908-2983 / +62-812 4658 5137</div> --}}
        <strong>Office</strong>: Br. Medahan, Desa Kemenuh, Sukawati, Gianyar 80582, Bali – Indonesia | Telp/Fax: +62-361 9082983 | www.idepfoundation.org
    </div>
</div>
@endsection
