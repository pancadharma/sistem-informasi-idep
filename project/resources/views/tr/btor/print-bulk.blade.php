@extends('tr.btor.layouts.print-bulk-layout')

@section('title', 'BTOR Bulk Print - ' . count($kegiatanList) . ' Reports')

@php
    $reportCount = count($kegiatanList);
@endphp

@section('content')
    @foreach($kegiatanList as $index => $item)
        @php
            $kegiatan = $item['kegiatan'];
            $viewPath = $item['viewPath'];
        @endphp
        <div class="print-header" style="padding: 2px 4px;">
            <div class="print-h2">
                BACK TO OFFICE
            </div>
            <div class="print-h3">
                | REPORT |
            </div>
            @isset($kegiatan->tanggalmulai)
                <div class="print-h3">
                    {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale(app()->getLocale())->isoFormat('Y') }}
                </div>
            @endisset
        </div>
        <div class="report-wrapper">
            {{-- Badge (Screen Only) --}}
            {{-- <div class="no-print report-badge">
                Report {{ $index + 1 }} of {{ count($kegiatanList) }}
            </div> --}}

            <div class="print-container">
                {{-- METADATA --}}
                <div class="section">
                    <div style="border-top: 0px solid #000; border-bottom: 1px solid #000; padding: 5px 0; margin-bottom: 20px;">
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
                                <td>{{ $kegiatan->kegiatan_penulis?->pluck('user.nama')->filter()->implode(', ') ?: __('btor.no_writer_activity') }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('btor.penulis_jabatan') }}</strong></td>
                                <td>:</td>
                                <td>{{ $kegiatan->kegiatan_penulis?->pluck('peran.nama')->filter()->implode(', ') ?: '-' }}</td>
                            </tr>
                            {{-- <tr>
                                <td><strong>{{ __('btor.sektor_kegiatan') }}</strong></td>
                                <td>:</td>
                                <td>{{ $kegiatan->sektor?->pluck('nama')->filter()->implode(', ') ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('btor.fase_pelaporan') }}</strong></td>
                                <td>:</td>
                                <td>{{ $kegiatan->fasepelaporan ?: '-' }}</td>
                            </tr> --}}
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

                {{-- C. Detail Kegiatan --}}
                <div class="section">
                    <h4 class="section-title">C. {{ __('btor.detail_kegiatan') }}</h4>
                    <table class="table-print" style="margin-bottom: 5px;">
                        <tr>
                            <td width="25%"><strong>{{ __('btor.tanggal_mulai') }}</strong></td>
                            <td width="2%">:</td>
                            <td>
                                @if($kegiatan->tanggalmulai)
                                    {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale(app()->getLocale())->isoFormat('dddd, D MMMM Y') }}
                                    @if($kegiatan->tanggalselesai && $kegiatan->tanggalmulai != $kegiatan->tanggalselesai)
                                        - {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale(app()->getLocale())->isoFormat('dddd, D MMMM Y') }}
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
                            <td>{{ $kegiatan->mitra->map(fn($m) => $m->nama)->filter()->implode(', ') ?: '-' }}</td>
                        </tr>
                    </table>

                    @if($kegiatan->lokasi && count($kegiatan->lokasi) > 0)
                        <div style="font-weight: bold; margin-top: 10px;">{{ __('btor.tabel_lokasi') }}</div>
                        @include('tr.btor.partials.location')
                    @endif
                </div>

                {{-- D. Hasil Kegiatan --}}
                <div class="section">
                    <h4 class="section-title">D. {{ __('btor.hasil.label') }}</h4>

                    {{-- Dynamic Activity Content --}}
                    @include('tr.btor.partials.hasil-dinamis-print', ['kegiatan' => $kegiatan])

                    {{-- 4a. Jumlah Partisipan --}}
                    <div style="font-weight: bold; margin-bottom: 5px;">a. {{ __('btor.partisipan_disagregat') }}</div>

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
                                    <td class="text-center">{{ (int)$kegiatan->penerimamanfaatlainnyatotal ?? 0 }}</td>
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
                    <div class="content-box">{!! $kegiatan->deskripsikeluaran ?? '-' !!}</div>
                </div>

                {{-- E. Tantangan dan Solusi --}}
                <div class="section">
                    <h4 class="section-title">E. {{ __('btor.tantangan_solusi') }}</h4>
                    @php
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
                    <div class="content-box">{!! $kendala ?? __('btor.no_data_tantang_solusi') !!}</div>
                </div>

                {{-- F. Isu yang Perlu Diperhatikan --}}
                <div class="section">
                    <h4 class="section-title">F. {{ __('btor.hasil.assessmentisu') }}</h4>
                    @php
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
                    <div class="content-box">{!! $isu ?? __('global.no_results') !!}</div>
                </div>

                {{-- G. Pembelajaran --}}
                <div class="section">
                    <h4 class="section-title">G. {{ __('btor.hasil.assessmentpembelajaran') }}</h4>
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
                    <div class="content-box">{!! $pembelajaran ?? __('btor.no_data_pembelajaran') !!}</div>
                </div>

                {{-- H. Dokumen & Media Pendukung --}}
                <div class="section">
                    <h4 class="section-title">H. {{ __('btor.dokumen_pendukung') }}</h4>
                    @include('tr.btor.partials.dokumen')
                </div>

                {{-- I. Catatan Penulis --}}
                <div class="section">
                    <h4 class="section-title">I. {{ __('btor.catatan_penulis_laporan') }}</h4>
                    <div class="content-box">{!! $kegiatan->catatan_penulis ?? '-' !!}</div>
                </div>

                {{-- J. Indikasi Perubahan --}}
                <div class="section">
                    <h4 class="section-title">J. {{ __('btor.indikasi_perubahan') }}</h4>
                    <div class="content-box">{!! $kegiatan->indikasi_perubahan ?? '-' !!}</div>
                </div>

            </div>

            {{-- Page break after each report except the last --}}
            @if($index < count($kegiatanList) - 1)
                <div class="page-break-after"></div>
                <hr class="report-separator no-print">
            @endif
        </div>
    @endforeach
@endsection