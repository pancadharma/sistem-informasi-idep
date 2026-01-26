@extends('layouts.app')

@section('subtitle', __('btor.btor_report_detail'))
@section('content_header_title') <strong>{{ __('btor.btor_report_detail') }}</strong>@endsection
@section('sub_breadcumb')<a href="{{ route('btor.index') }}" title="{{ __('btor.btor_report_list') }}"> {{ __('btor.btor_report_list') }}</a>@endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('btor.btor_report_detail') }}">{{ __('btor.btor_report_detail') }}</span>@endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
<div class="container-fluid">

    {{-- Action Buttons --}}
    <div class="row mb-3 no-print">
        <div class="col-12">
            <div class="btn-toolbar" role="toolbar">
                <div class="btn-group mr-2">
                    <a href="{{ route('btor.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('btor.back_to_list') }}
                    </a>
                </div>
                <div class="btn-group mr-2">
                    <a href="{{ route('btor.print', $kegiatan->id) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-print"></i> {{ __('btor.print_preview') }}
                    </a>
                </div>
                <div class="btn-group">
                    <a href="{{ route('btor.export.pdf', $kegiatan->id) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> {{ __('btor.export_pdf') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">

        {{-- Header --}}
        <div class="card-header text-white" style="background-color: #2f6c01">
            <h3 class="mb-0 text-center">{{ __('btor.btor') }}</h3>
            <p class="text-center mb-0"><small>Report ID: {{ $kegiatan->id }}</small></p>
        </div>

        <div class="card-body">

            {{-- Basic Information Table --}}
            <div class="mb-4">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td width="15%" class="table-light"><strong>{{ __('btor.departemen') }}</strong></td>
                            <td>Program</td>
                        </tr>
                        <tr>
                            <td class="table-light"><strong>{{ __('btor.program') }}</strong></td>
                            <td>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="table-light"><strong>{{ __('btor.nama_kegiatan') }}</strong></td>
                            <td>{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="table-light"><strong>{{ __('btor.kode_budget') }}</strong></td>
                            <td>{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="table-light"><strong>{{ __('btor.penulis_laporan') }}</strong></td>
                            <td>{{ $kegiatan->kegiatan_penulis?->pluck('user.nama')->filter()->implode(', ') ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="table-light"><strong>{{ __('btor.penulis_jabatan') }}</strong></td>
                            <td>{{ $kegiatan->kegiatan_penulis?->pluck('peran.nama')->filter()->implode(', ') ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr class="my-4">

            {{-- 1. Latar Belakang Kegiatan --}}
            <div class="section mb-4">
                <h4 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-info-circle text-primary"></i> {{ __('btor.latar_belakang_kegiatan') }}
                </h4>
                <div class="p-3 bg-light rounded" style="min-height: 100px;">
                    {!! $kegiatan->deskripsilatarbelakang ?? '<em class="text-muted"> ' . __('btor.no_background_activity') . '</em>' !!}
                </div>
            </div>

            {{-- 2. Tujuan Kegiatan --}}
            <div class="section mb-4">
                <h4 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-bullseye text-success"></i> {{ __('btor.tujuan_kegiatan') }}
                </h4>
                <div class="p-3 bg-light rounded" style="min-height: 100px;">
                    {!! $kegiatan->deskripsitujuan ?? '<em class="text-muted"> ' . __('btor.no_tujuan_activity') . '</em>' !!}
                </div>
            </div>

            {{-- 3. Detail Kegiatan --}}
            <div class="section mb-4">
                <h4 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-calendar-alt text-warning"></i> {{ __('btor.detail_kegiatan') }}
                </h4>

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="25%" class="table-light"><strong>{{ __('btor.tanggal_mulai') }}</strong></td>
                            <td>
                                @if($kegiatan->tanggalmulai && $kegiatan->tanggalselesai)
                                    {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    @if($kegiatan->tanggalmulai != $kegiatan->tanggalselesai)
                                        - {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    @endif
                                    <span class="badge badge-info ml-2">{{ $kegiatan->getDurationInDays() }} {{ __('btor.hari') }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="table-light"><strong>{{ __('btor.lokasi_kegiatan') }}</strong></td>
                            <td>
                                @if($kegiatan->lokasi?->count() > 0)
                                    @php
                                        $lokasiList = $kegiatan->lokasi->map(function($lok) {
                                            $parts = array_filter([
                                                $lok->lokasi,
                                                $lok->desa?->nama,
                                                $lok->desa?->kecamatan?->nama,
                                                $lok->desa?->kecamatan?->kabupaten?->nama
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
                            <td class="table-light"><strong>{{ __('btor.pihak_yang_terlibat') }}</strong></td>
                            <td>
                                @if($kegiatan->mitra?->count() > 0)
                                    <ul class="mb-0 pl-0" style="list-style-type: none;">
                                        @foreach($kegiatan->mitra as $mitra)
                                            <li>{{ $mitra->nama }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="table-light"><strong>{{ __('btor.sektor_kegiatan') }}</strong></td>
                            <td>
                                @if($kegiatan->sektor?->count() > 0)
                                    {{ $kegiatan->sektor->pluck('nama')->implode(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="table-light"><strong>Status</strong></td>
                            <td>
                                <span class="badge badge-{{ $kegiatan->status === 'completed' ? 'success' : ($kegiatan->status === 'ongoing' ? 'warning' : 'secondary') }} badge-lg">
                                    {{ strtoupper($kegiatan->status ?? 'DRAFT') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Activity Type Specific Content --}}
                <div class="mt-4">
                    <h5 class="mb-3">
                        <i class="fas fa-tasks text-info"></i> {{ __('btor.jenis_kegiatan') }}
                        <span class="badge badge-info">{{ $kegiatan->jenisKegiatan?->nama }}</span>
                    </h5>
                    @include($viewPath, ['kegiatan' => $kegiatan])
                </div>
            </div>

            {{-- 4. Hasil Kegiatan --}}
            <div class="section mb-4">
                <h4 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-chart-bar text-success"></i> {{ __('btor.hasil_kegiatan') }}
                </h4>

                {{-- 4a. Jumlah Partisipan --}}
                <h5 class="mb-3">{{ __('btor.jumlah_partisipan') }}</h5>

                <p class="text-muted mb-2"><em>{{ __('btor.table_partisipan') }}:</em></p>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>{{ __('btor.penerima_manfaat') }}</th>
                                <th class="text-center" width="15%">{{ __('btor.perempuan') }}</th>
                                <th class="text-center" width="15%">{{ __('btor.laki_laki') }}</th>
                                <th class="text-center" width="15%">{{ __('btor.lainnya') }}</th>
                                <th class="text-center" width="15%">{{ __('btor.sub_total') }}</th>
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
                                <td>{{ __('btor.anak') }} <em>( {{ __('btor.umur_18_kebawah') }} )</em></td>
                                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanakperempuan ?? 0) }}</td>
                                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanaklakilaki ?? 0) }}</td>
                                <td class="text-center">0</td>
                                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatanaktotal ?? 0) }}</strong></td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Grand Total</strong></td>
                                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                                <td class="text-center"><strong>0</strong></td>
                                <td class="text-center"><strong class="text-primary">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-muted mb-2 mt-3"><em>{{ __('btor.table_kelompok_khusus') }}:</em></p>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>{{ __('btor.penerima_manfaat') }}</th>
                                <th class="text-center" width="15%">{{ __('btor.perempuan') }}</th>
                                <th class="text-center" width="15%">{{ __('btor.laki_laki') }}</th>
                                <th class="text-center" width="15%">{{ __('btor.lainnya') }}</th>
                                <th class="text-center" width="15%">{{ __('btor.sub_total') }}</th>
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
                            <tr class="table-primary">
                                <td><strong>{{ __('btor.grand_total') }}</strong></td>
                                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                                <td class="text-center"><strong>0</strong></td>
                                <td class="text-center"><strong class="text-primary">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-users"></i>
                    <strong>{{ __('btor.total_keseluruhan') }}:</strong> {{ number_format($kegiatan->penerimamanfaattotal ?? 0) }} {{ __('btor.orang') }}
                    ({{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }} {{ __('btor.perempuan') }},
                    {{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }} {{ __('btor.laki_laki') }})
                </div>

                {{-- 4b. Hasil Pertemuan --}}
                <h5 class="mt-4 mb-3">b. {{ __('btor.hasil_pertemuan') }}</h5>
                <div class="p-3 bg-light rounded" style="min-height: 100px;">
                    {!! $kegiatan->deskripsikeluaran ?? '<em class="text-muted">Tidak ada data hasil pertemuan</em>' !!}
                </div>
            </div>

            {{-- 5. Tantangan dan Solusi --}}
            <div class="section mb-4">
                <h4 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Tantangan dan Solusi
                </h4>
                <p class="text-muted mb-3">
                    <em>Silahkan isi dan jabarkan tantangan yang ditemui selama menjalankan kegiatan, serta solusinya berdasarkan hasil evaluasi kegiatan (jika ada).</em>
                </p>

                @php
                    $hasKendala = $kegiatan->assessment?->assessmentkendala
                               || $kegiatan->pelatihan?->pelatihanisu
                               || $kegiatan->monitoring?->monitoringkendala
                               || $kegiatan->sosialisasi?->sosialisasikendala
                               || $kegiatan->kampanye?->kampanyekendala
                               || $kegiatan->konsultasi?->konsultasikendala
                               || $kegiatan->kunjungan?->kunjungankendala
                               || $kegiatan->pembelanjaan?->pembelanjaankendala
                               || $kegiatan->pengembangan?->pengembangankendala
                               || $kegiatan->pemetaan?->pemetaankendala
                               || $kegiatan->lainnya?->lainnyakendala;
                @endphp

                @if($hasKendala)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%" class="text-center">No.</th>
                                    <th width="47.5%">Tantangan</th>
                                    <th width="47.5%">Solusi yang Diambil Tim</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $kendala = $kegiatan->assessment?->assessmentkendala
                                            ?? $kegiatan->pelatihan?->pelatihanisu
                                            ?? $kegiatan->monitoring?->monitoringkendala
                                            ?? $kegiatan->sosialisasi?->sosialisasikendala
                                            ?? $kegiatan->kampanye?->kampanyekendala
                                            ?? $kegiatan->konsultasi?->konsultasikendala
                                            ?? $kegiatan->kunjungan?->kunjungankendala
                                            ?? $kegiatan->pembelanjaan?->pembelanjaankendala
                                            ?? $kegiatan->pengembangan?->pengembangankendala
                                            ?? $kegiatan->pemetaan?->pemetaankendala
                                            ?? $kegiatan->lainnya?->lainnyakendala
                                            ?? 'Tidak ada tantangan yang dicatat';

                                    $solusi = $kegiatan->assessment?->assessmentpembelajaran
                                            ?? $kegiatan->pelatihan?->pelatihanpembelajaran
                                            ?? $kegiatan->monitoring?->monitoringpembelajaran
                                            ?? $kegiatan->sosialisasi?->sosialisasipembelajaran
                                            ?? $kegiatan->kampanye?->kampanyepembelajaran
                                            ?? $kegiatan->konsultasi?->konsultasipembelajaran
                                            ?? $kegiatan->kunjungan?->kunjunganpembelajaran
                                            ?? $kegiatan->pembelanjaan?->pembelanjaanpembelajaran
                                            ?? $kegiatan->pengembangan?->pengembanganpembelajaran
                                            ?? $kegiatan->pemetaan?->pemetaanpembelajaran
                                            ?? $kegiatan->lainnya?->lainnyapembelajaran
                                            ?? '-';
                                @endphp
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>{!! $kendala !!}</td>
                                    <td>{!! $solusi !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-secondary">
                        <i class="fas fa-info-circle"></i>
                        Tidak ada data tantangan dan solusi yang tersedia.
                    </div>
                @endif
            </div>

            {{-- 6. Isu yang Perlu Diperhatikan & Rekomendasi --}}
            <div class="section mb-4">
                <h4 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-flag text-danger"></i> Isu yang Perlu Diperhatikan & Rekomendasi
                </h4>
                <p class="text-muted mb-3">
                    <em>Silahkan isi dan jabarkan isu-isu yang perlu diperhatikan (bersifat di luar kendali tim) selama menjalankan kegiatan, serta rekomendasi yang perlu diambil berdasarkan hasil evaluasi kegiatan (jika ada).</em>
                </p>

                @php
                    $isu = $kegiatan->assessment?->assessmentisu
                        ?? $kegiatan->pelatihan?->pelatihanisu
                        ?? $kegiatan->monitoring?->monitoringisu
                        ?? $kegiatan->sosialisasi?->sosialisasiisu
                        ?? $kegiatan->kampanye?->kampanyeisu
                        ?? $kegiatan->konsultasi?->konsultasiisu
                        ?? $kegiatan->kunjungan?->kunjunganisu
                        ?? $kegiatan->pembelanjaan?->pembelanjaanisu
                        ?? $kegiatan->pengembangan?->pengembanganisu
                        ?? $kegiatan->pemetaan?->pemetaanisu
                        ?? $kegiatan->lainnya?->lainnyaisu;

                    $rekomendasi = $kegiatan->assessment?->assessmentpembelajaran
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

                @if($isu)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%" class="text-center">No.</th>
                                    <th width="47.5%">Isu yang Perlu Diperhatikan</th>
                                    <th width="47.5%">Rekomendasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>{!! $isu !!}</td>
                                    <td>{!! $rekomendasi ?? '-' !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-secondary">
                        <i class="fas fa-info-circle"></i>
                        Tidak ada isu yang perlu diperhatikan.
                    </div>
                @endif
            </div>

            {{-- 7. Pembelajaran --}}
            <div class="section mb-4">
                <h4 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-lightbulb text-warning"></i> Pembelajaran
                </h4>
                <p class="text-muted mb-3">
                    <em>Silakan isi dan jabarkan pembelajaran apa yang bisa didapatkan selama proses pelaksanaan kegiatan. Bisa diambil dari hasil evaluasi internal tim dan form FRM yang diisi partisipan. Silakan fokus pada rekomendasi pembelajarannya.</em>
                </p>

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

                <div class="p-3 bg-light rounded" style="min-height: 100px;">
                    @if($pembelajaran)
                        {!! $pembelajaran !!}
                    @else
                        <em class="text-muted">Tidak ada data pembelajaran yang tersedia.</em>
                    @endif
                </div>
            </div>

            {{-- 8. Dokumen Pendukung --}}
            <div class="section mb-4">
                <h4 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-folder-open text-info"></i> Dokumen Pendukung
                </h4>
                <p class="text-muted mb-3">
                    <em>Dokumen dan media yang disertakan dalam BTOR ini.</em>
                </p>

                @php
                    $dokumen = $kegiatan->getDokumenPendukung();
                    $media = $kegiatan->getMediaPendukung();
                    $hasDokumen = $dokumen && $dokumen->count() > 0;
                    $hasMedia = $media && $media->count() > 0;
                @endphp

                {{-- Checklist --}}
                <div class="mb-3">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-{{ $hasDokumen ? 'check-square text-success' : 'square text-muted' }}"></i> Dokumen Pendukung</li>
                        <li><i class="fas fa-{{ $hasMedia ? 'check-square text-success' : 'square text-muted' }}"></i> Media Pendukung</li>
                        {{-- <li><i class="fas fa-{{ $hasDokumen ? 'check-square text-success' : 'square text-muted' }}"></i> Notulensi dan Rencana Tindak Lanjut (RTL)</li> --}}
                        {{-- <li><i class="fas fa-square text-muted"></i> Evaluasi Harian</li>
                        <li><i class="fas fa-square text-muted"></i> Hasil Skoring Pre-Test dan Post-Test</li>
                        <li><i class="fas fa-square text-muted"></i> Lainnya</li> --}}
                    </ul>
                </div>

                @if($hasDokumen || $hasMedia)

                    {{-- Dokumen Files --}}
                    @if($hasDokumen)
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-alt"></i> Dokumen
                                    <span class="badge badge-light float-right">{{ $dokumen->count() }}</span>
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($dokumen as $index => $doc)
                                        <a href="{{ $doc->getUrl() }}"
                                           target="_blank"
                                           class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        <i class="fas fa-file-{{ $doc->extension === 'pdf' ? 'pdf text-danger' : ($doc->extension === 'docx' || $doc->extension === 'doc' ? 'word text-primary' : ($doc->extension === 'xlsx' || $doc->extension === 'xls' ? 'excel text-success' : 'alt')) }}"></i>
                                                        <strong>{{ $doc->name }}</strong>
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ $doc->file_name }} •
                                                        {{ $doc->human_readable_size }} •
                                                        <i class="far fa-calendar"></i> {{ $doc->created_at->format('d M Y') }}
                                                    </small>
                                                </div>
                                                <div class="ml-3">
                                                    <span class="badge badge-secondary">{{ strtoupper($doc->extension) }}</span>
                                                    <i class="fas fa-external-link-alt ml-2 text-primary"></i>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Media Files List (Simple - No Thumbnails) --}}
                    @if($hasMedia)
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-images"></i> Media Pendukung (Foto & Video)
                                    <span class="badge badge-light float-right">{{ $media->count() }}</span>
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($media as $index => $item)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        @if(str_starts_with($item->mime_type, 'image/'))
                                                            <i class="fas fa-image text-info"></i>
                                                        @elseif(str_starts_with($item->mime_type, 'video/'))
                                                            <i class="fas fa-video text-danger"></i>
                                                        @else
                                                            <i class="fas fa-file text-secondary"></i>
                                                        @endif
                                                        <strong>{{ $item->name }}</strong>
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ $item->file_name }} •
                                                        {{ $item->human_readable_size }} •
                                                        @if(str_starts_with($item->mime_type, 'image/'))
                                                            <span class="badge badge-info badge-sm">IMAGE</span>
                                                        @elseif(str_starts_with($item->mime_type, 'video/'))
                                                            <span class="badge badge-danger badge-sm">VIDEO</span>
                                                        @else
                                                            <span class="badge badge-secondary badge-sm">{{ strtoupper($item->extension) }}</span>
                                                        @endif
                                                        •
                                                        <i class="far fa-calendar"></i> {{ $item->created_at->format('d M Y H:i') }}
                                                    </small>
                                                </div>

                                                <div class="ml-3">
                                                    <a href="{{ $item->getUrl() }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-primary mr-1"
                                                    title="Lihat">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                    <a href="{{ $item->getUrl() }}"
                                                    download
                                                    class="btn btn-sm btn-success"
                                                    title="Unduh">
                                                        <i class="fas fa-download"></i> Unduh
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Summary Stats --}}
                    <div class="alert alert-info">
                        <i class="fas fa-paperclip"></i>
                        <strong>Total:</strong>
                        {{ ($dokumen?->count() ?? 0) + ($media?->count() ?? 0) }} file terlampir
                        @if($hasDokumen)
                            ({{ $dokumen->count() }} dokumen{{ $hasMedia ? ', ' : '' }}
                        @endif
                        @if($hasMedia)
                            {{ $media->count() }} media)
                        @endif
                    </div>
                @else
                    <div class="alert alert-secondary">
                        <i class="fas fa-info-circle"></i>
                        Tidak ada dokumen atau media pendukung yang dilampirkan untuk kegiatan ini.
                    </div>
                @endif
            </div>

            {{-- 9. Catatan Penulis Laporan --}}
            <div class="section mb-4">
                <h4 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-sticky-note text-secondary"></i> Catatan Penulis Laporan
                </h4>
                <p class="text-muted mb-3">
                    <em>Silahkan tuliskan catatan spesifik dari penulis laporan jika ada informasi yang belum bisa tersampaikan melalui bagian-bagian laporan di atas.</em>
                </p>

                <div class="p-3 bg-light rounded" style="min-height: 80px;">
                    <p class="text-muted">-</p>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="card-footer bg-light text-muted">
            <div class="row">
                <div class="col-md-8">
                    <small>
                        <strong>IDEP Foundation</strong> | Back to Office Report (BTOR)<br>
                        <i class="fas fa-info-circle"></i> Untuk pertanyaan, silakan hubungi Program Department.
                    </small>
                </div>
                <div class="col-md-4 text-right">
                    <small>
                        Report ID: <strong>{{ $kegiatan->id }}</strong><br>
                        Generated: {{ now()->format('d M Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
    .list-group-item {
        transition: all 0.2s ease;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
        transform: translateX(3px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .card {
        transition: all 0.2s ease;
    }
    .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .section {
        animation: fadeIn 0.5s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    // Lightbox configuration
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': 'Gambar %1 dari %2',
        'fadeDuration': 300,
        'imageFadeDuration': 300
    });

    // Smooth scroll to sections
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
@endpush

