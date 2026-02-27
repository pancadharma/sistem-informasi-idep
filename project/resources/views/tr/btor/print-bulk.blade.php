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

        <div class="report-wrapper">
            {{-- Badge (Screen Only) --}}
            <div class="no-print report-badge">
                Report {{ $index + 1 }} of {{ count($kegiatanList) }}
            </div>

            <div class="print-container">
                {{-- METADATA --}}
                <div class="section">
                    <div style="border-top: 0px solid #000; border-bottom: 1px solid #000; padding: 5px 0; margin-bottom: 20px;">
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

                    <div style="font-weight: bold; margin: 10px 0 5px 0;">b. {{ __('cruds.kegiatan.description.deskripsikeluaran') }}</div>
                    <div class="content-box">{!! $kegiatan->deskripsikeluaran ?? '-' !!}</div>
                </div>

                {{-- E. Tantangan dan Solusi --}}
                <div class="section">
                    <h4 class="section-title">E. {{ __('btor.tantangan_solusi') }}</h4>
                    <div class="content-box">
                        @php
                            $kendala = $kegiatan->assessment?->assessmentkendala
                                    ?? $kegiatan->pelatihan?->pelatihanisu
                                    ?? $kegiatan->monitoring?->monitoringkendala
                                    ?? $kegiatan->sosialisasi?->sosialisasikendala
                                    ?? $kegiatan->lainnya?->lainnyakendala
                                    ?? null;
                        @endphp
                        {!! $kendala ?? 'Tidak ada data tantangan.' !!}
                    </div>
                </div>

                {{-- F. Isu / Rekomendasi --}}
                <div class="section">
                    <h4 class="section-title">F. Isu yang Perlu Diperhatikan / Rekomendasi</h4>
                    <div class="content-box">
                        @php
                            $isu = $kegiatan->assessment?->assessmentisu
                                ?? $kegiatan->pelatihan?->pelatihanisu
                                ?? $kegiatan->monitoring?->monitoringisu
                                ?? $kegiatan->lainnya?->lainnyaisu
                                ?? null;
                        @endphp
                        {!! $isu ?? 'Tidak ada data isu.' !!}
                    </div>
                </div>

                {{-- G. Pembelajaran --}}
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

                {{-- H. Dokumen --}}
                <div class="section">
                    <h4 class="section-title">H. Dokumen Pendukung</h4>
                    @include('tr.btor.partials.dokumen')
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