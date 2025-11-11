@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.kegiatan.label'))
@section('content_header_title')
    <button type="button" class="btn btn-secondary btn-sm print-btn"
        title="{{ __('global.print') . ' ' . __('cruds.kegiatan.label') }}">
        <i class="fa fa-print"></i>
    </button>
@endsection
@section('sub_breadcumb', __('cruds.kegiatan.list'))

@section('content_body')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h1 class="card-title">
                {{ __('BACK TO OFFICE REPORT') }}
                <span class="text-primary">
                    {{ $kegiatan->programOutcomeOutputActivity->kode ?? ''}}
                </span>
                {{ $kegiatan->programOutcomeOutputActivity->nama ?? '' }}
            </h1>
            <div class="card-tools">
                <button type="button" class="btn" onclick="window.location.href=`{{ route('kegiatan.index') }}`"
                    title="{{ __('global.back') }}">
                    <i class="fa fa-arrow-left"></i>
                </button>
            </div>
        </div>
        <!-- Activity Metrics Section -->
        <div class="card-body border-top">
            <div class="col-md-12">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h3 class="mb-0 text-primary">{{ $kegiatan->lokasi?->count() ?? 0 }}</h3>
                            <small>{{ __('Locations') }}</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h3 class="mb-0 text-success">{{ $kegiatan->mitra?->count() ?? 0 }}</h3>
                            <small>{{ __('Partners') }}</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h3 class="mb-0 text-warning">{{ $kegiatan->penerimamanfaattotal ?? 0 }}</h3>
                            <small>{{ __('Beneficiaries') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End Activity Metrics Section -->
        <div class="card-body m-0 p-0">
            <div class="details">
                <table class="table datatable table-sm mb-0 table-hover">
                    <tbody>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.program_kode') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->kode ?? '-' }}
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.program_nama') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                <a href="{{ route('program.show', $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->id) }}"
                                target="_blank">
                                {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}
                                </a>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.kode') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.nama') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.penulis.laporan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @if($kegiatan->datapenulis && $kegiatan->datapenulis->count() > 0)
                                    {{ $kegiatan->datapenulis->pluck('nama')->filter()->implode(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.penulis.jabatan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @if($kegiatan->datapenulis && $kegiatan->datapenulis->count() > 0)
                                    {{ $kegiatan->datapenulis->map(fn($p) => $p->kegiatanPeran?->nama)->filter()->implode(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $kegiatan->jenisKegiatan?->nama ?? '-' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.sektor_kegiatan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @if($kegiatan->sektor && $kegiatan->sektor->count() > 0)
                                    @foreach ($kegiatan->sektor as $value)
                                        <span class="badge bg-warning text-dark me-1">{{ $value->nama }}</span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.fase_pelaporan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $kegiatan->fasepelaporan ?? '-' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.tanggalmulai') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @if($kegiatan->tanggalmulai)
                                    {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d-m-Y') }}
                                    ({{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->diffForHumans() }})
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.tanggalselesai') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @if($kegiatan->tanggalselesai)
                                    {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d-m-Y') }}
                                    ({{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->diffForHumans() }})
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.durasi') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $durationInDays ?? 0 }} {{ __('cruds.kegiatan.days') }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.nama_mitra') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @if($kegiatan->mitra && $kegiatan->mitra->count() > 0)
                                    {{ $kegiatan->mitra->pluck('nama')->implode(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.status') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                <span class="badge badge-{{ $kegiatan->status === 'completed' ? 'success' : ($kegiatan->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($kegiatan->status ?? 'draft') }}
                                </span>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.tempat') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @if($kegiatan->lokasi && $kegiatan->lokasi->count() > 0)
                                    @php
                                        $kabupatenNames = $kegiatan->lokasi
                                            ->filter(fn($lok) => $lok->desa?->kecamatan?->kabupaten?->nama)
                                            ->unique(fn($lok) => $lok->desa->kecamatan->kabupaten->id)
                                            ->pluck('desa.kecamatan.kabupaten.nama')
                                            ->implode(', ');

                                        $provinsiName = $kegiatan->lokasi->first()?->desa?->kecamatan?->kabupaten?->provinsi?->nama ?? '';
                                    @endphp
                                    {{ $kabupatenNames }}@if($provinsiName), {{ $provinsiName }}@endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle bg-info">
                            <th colspan="3" class="align-middle text-white">
                                {{ __('Program Hierarchy & Progress') }} <i class="fas fa-sitemap"></i>
                            </th>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('Program Outcome Target') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                <strong>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->target ?? '-' }}</strong>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('Program Outcome Output Target') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                <strong>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->target ?? '-' }}</strong>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('Activity Target') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                <strong>{{ $kegiatan->programOutcomeOutputActivity?->target ?? '-' }}</strong>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('Program Goals') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @php
                                    $program = $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program;
                                    $goal = $program?->goal ?? null;
                                @endphp
                                @if($goal)
                                    <li class="badge bg-info me-1">{{ $goal->deskripsi ?? '-' }}</li>
                                    <li class="badge bg-success me-1">{{ $goal->indikator ?? '-' }}</li>
                                    <li class="badge bg-warning me-1">{{ $goal->target ?? '-' }}</li>
                                @else
                                    <span class="text-muted">{{ __('No goals defined') }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table datatable table-sm mb-0 table-hover">
                    <tbody>
                        <tr class="align-middle bg-success">
                            <th colspan="3" class="align-middle">
                                {{ __('global.details') . ' ' . __('cruds.kegiatan.tempat') }} <i class="fas fa-map-marker-alt"></i>
                            </th>
                        </tr>
                    </tbody>
                </table>

                <table class="table datatable table-sm mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="tb-header mr-0 pr-0 align-middle col-3">Nama Tempat</th>
                            <th class="tb-header mr-0 pr-0 align-middle col-3">Longitude</th>
                            <th class="tb-header mr-0 pr-0 align-middle col-3">Latitude</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatan->lokasi as $lokasi)
                            <tr class="align-middle">
                                <td class="tb-header mr-0 pr-0 align-middle">
                                    @if ($lokasi->lat && $lokasi->long)
                                        <a href="https://www.google.com/maps?q={{ $lokasi->lat }},{{ $lokasi->long }}" target="_blank">
                                            {{ ucwords(strtolower($lokasi->lokasi ?? 'Lihat Di Peta')) }}
                                        </a>
                                    @else
                                        {{ $lokasi->lokasi ?? '—' }}
                                    @endif
                                </td>
                                <td class="tb-header mr-0 pr-0 align-middle">{{ $lokasi->long ?? '—' }}</td>
                                <td class="tb-header mr-0 pr-0 align-middle">{{ $lokasi->lat ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr class="align-middle">
                                <td colspan="3" class="text-center text-muted">Tidak ada data lokasi tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-body">
            <!-- deskripsi kegiatan -->
            <div class="form-group row">
                <div class="col-sm col-md col-lg self-center">
                    <label for="deskripsilatarbelakang" class="input-group">
                        {{ __('cruds.kegiatan.description.latar_belakang') }}
                        <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                            title="{{ __('cruds.kegiatan.description.latar_belakang_helper') }}"></i>
                    </label>
                    {!! $kegiatan->deskripsilatarbelakang ?? '' !!}
                </div>
            </div>
            <!-- tujuan kegiatan -->
            <div class="form-group row">
                <div class="col-sm col-md col-lg self-center">
                    <label for="deskripsitujuan" class="mb-0 input-group">
                        {{ __('cruds.kegiatan.description.tujuan') }}
                        <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                            title="{{ __('cruds.kegiatan.description.tujuan_helper') }}"></i>
                    </label>
                    {!! $kegiatan->deskripsitujuan ?? '' !!}
                </div>
            </div>
            <!-- siapa deskripsi keluaran kegiatan -->
            <div class="form-group row">
                <div class="col-sm col-md col-lg self-center">
                    <label for="deskripsikeluaran" class="mb-0 input-group">
                        {{ __('cruds.kegiatan.description.deskripsikeluaran') }}
                        <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                            title="{{ __('cruds.kegiatan.description.keluaran_helper') }}"></i>
                    </label>
                    {!! $kegiatan->deskripsikeluaran ?? '' !!}
                </div>
            </div>
            <!-- Peserta yang terlibat -->
            <div class="form-group row mb-0">
                <div class="col-sm col-md col-lg self-center">
                    <label class="self-center input-group">
                        {{ __('cruds.kegiatan.peserta.label') }}
                        <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                            title="{{ __('cruds.kegiatan.peserta.helper') }}"></i>
                    </label>
                </div>
            </div>

            <!-- Jumlah Peserta Kegiatan -->
            {{-- Tabel Jumlah Peserta Kegiatan --}}
            <div class="form-group row">
                <div class="col-sm col-md col-lg self-center">
                    <div class="card-body table-responsive p-0">
                        <table id="peserta_kegiatan_summary" class="table table-sm table-borderless mb-0 table-custom"
                            width="100%">
                            <!-- jumlah peserta kegiatan -->
                            <thead style="background-color: #11bd7e !important">
                                <tr class="align-middle text-center text-nowrap">
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.peserta') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.wanita') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.pria') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--dewasa row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.dewasa') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatdewasaperempuan"
                                            name="penerimamanfaatdewasaperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdewasaperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatdewasalakilaki"
                                            name="penerimamanfaatdewasalakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdewasalakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatdewasatotal"
                                            name="penerimamanfaatdewasatotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdewasatotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--lansia row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.lansia') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatlansiaperempuan"
                                            name="penerimamanfaatlansiaperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlansiaperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatlansialakilaki"
                                            name="penerimamanfaatlansialakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlansialakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatlansiatotal"
                                            name="penerimamanfaatlansiatotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlansiatotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--remaja row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.remaja') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatremajaperempuan"
                                            name="penerimamanfaatremajaperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatremajaperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatremajalakilaki"
                                            name="penerimamanfaatremajalakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatremajalakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatperempuantotal"
                                            name="penerimamanfaatperempuantotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatremajatotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--anak-anak row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.anak') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatanakperempuan"
                                            name="penerimamanfaatanakperempuan"
                                            class="calculate form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatanakperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatanaklakilaki"
                                            name="penerimamanfaatanaklakilaki"
                                            class="calculate form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatanaklakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatanaktotal"
                                            name="penerimamanfaatanaktotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatanaktotal ?? 0 }}">
                                    </td>
                                </tr>
                                <tr class="align-middle text-center text-nowrap">
                                    <th class="pl-1 text-left text-sm">{{ __('cruds.kegiatan.peserta.total') }}</th>
                                    <th class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatperempuantotal"
                                            name="penerimamanfaatperempuantotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}">
                                    </th>
                                    <th class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatlakilakitotal"
                                            name="penerimamanfaatlakilakitotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}">
                                    </th>
                                    <th class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaattotal"
                                            name="penerimamanfaattotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaattotal ?? 0 }}">
                                    </th>
                                </tr>
                            </tbody>
                            <!--jumlah peserta disabilitas -->
                            <tfoot>
                                <!--<th style="background-color: #6111bd !important table-dark">-->
                                <tr class="align-middle text-center text-nowrap"
                                    style="background-color: #6111bd !important">
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.peserta') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.wanita') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.pria') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.total') }}</th>
                                </tr>
                                <!--</th>-->
                                <!--disabilitas row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.disabilitas') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatdisabilitasperempuan"
                                            name="penerimamanfaatdisabilitasperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdisabilitasperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatdisabilitaslakilaki"
                                            name="penerimamanfaatdisabilitaslakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatdisabilitastotal"
                                            name="penerimamanfaatdisabilitastotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdisabilitastotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--non disabilitias  row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.non_disabilitas') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatnondisabilitasperempuan"
                                            name="penerimamanfaatnondisabilitasperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatnondisabilitaslakilaki"
                                            name="penerimamanfaatnondisabilitaslakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatnondisabilitastotal"
                                            name="penerimamanfaatnondisabilitastotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatnondisabilitastotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--marjinal row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.marjinal_lain') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatmarjinalperempuan"
                                            name="penerimamanfaatmarjinalperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatmarjinalperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatmarjinallakilaki"
                                            name="penerimamanfaatmarjinallakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatmarjinallakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatmarjinaltotal"
                                            name="penerimamanfaatmarjinaltotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatmarjinaltotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--total beneficiaries difabel-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.total') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="total_beneficiaries_perempuan"
                                            name="penerimamanfaatperempuantotal"
                                            class="calculate form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="total_beneficiaries_lakilaki"
                                            name="penerimamanfaatlakilakitotal"
                                            class="calculate form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="beneficiaries_difable_total"
                                            name="penerimamanfaattotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaattotal ?? 0 }}">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

        </div>

        <!-- Related Documents & Files Section -->
        <div class="card-body border-top">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="text-info mb-0"><i class="fas fa-folder-open me-2"></i>{{ __('Related Documents & Files') }}</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm active" id="documents-tab" onclick="showTab('documents')">
                        <i class="fas fa-file-alt me-1"></i>{{ __('Documents') }}
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="media-tab" onclick="showTab('media')">
                        <i class="fas fa-images me-1"></i>{{ __('Media') }}
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="uploadDocument('dokumen_pendukung')">
                        <i class="fas fa-plus me-1"></i>{{ __('Upload Document') }}
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="uploadDocument('media_pendukung')">
                        <i class="fas fa-plus me-1"></i>{{ __('Upload Media') }}
                    </button>
                </div>
            </div>

            <!-- Documents Section -->
            <div id="documents-content" class="tab-content">
                @if($kegiatan->getMedia('dokumen_pendukung') && $kegiatan->getMedia('dokumen_pendukung')->count() > 0)
                    <div class="row g-3">
                        @foreach($kegiatan->getMedia('dokumen_pendukung') as $media)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card file-card h-100 shadow-sm hover-shadow transition-all">
                                    <div class="card-body p-3">
                                        <div class="file-icon text-center mb-3">
                                            @if(strstr($media->mime_type, "image/"))
                                                <img src="{{ $media->getUrl('thumb') }}" class="img-fluid rounded" alt="{{ $media->getCustomProperty('keterangan') ?? $media->name }}" style="max-height: 120px; object-fit: cover;">
                                            @elseif(strstr($media->mime_type, "pdf"))
                                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                            @elseif(strstr($media->mime_type, "word"))
                                                <i class="fas fa-file-word fa-4x text-primary"></i>
                                            @elseif(strstr($media->mime_type, "excel") || strstr($media->mime_type, "spreadsheet"))
                                                <i class="fas fa-file-excel fa-4x text-success"></i>
                                            @elseif(strstr($media->mime_type, "powerpoint"))
                                                <i class="fas fa-file-powerpoint fa-4x text-warning"></i>
                                            @else
                                                <i class="fas fa-file fa-4x text-secondary"></i>
                                            @endif
                                        </div>
                                        <h6 class="card-title text-truncate" title="{{ $media->getCustomProperty('keterangan') ?? $media->name }}">
                                            {{ Str::limit($media->getCustomProperty('keterangan') ?? $media->name, 25) }}
                                        </h6>
                                        <div class="file-meta">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-calendar me-1"></i>{{ $media->created_at->format('d M Y') }}
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-weight me-1"></i>{{ $media->human_readable_size }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 p-2">
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="previewFile('{{ $media->getUrl() }}', '{{ $media->mime_type }}')" title="{{ __('Preview') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ $media->getUrl() }}" class="btn btn-outline-success btn-sm" download="{{ $media->getCustomProperty('keterangan') ?? $media->name }}" title="{{ __('Download') }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteFile('{{ $media->id }}')" title="{{ __('Delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                        <h6 class="text-muted">{{ __('No supporting documents uploaded yet') }}</h6>
                        <p class="text-muted small">{{ __('Upload documents to support this activity') }}</p>
                        <button class="btn btn-primary" onclick="uploadDocument('dokumen_pendukung')">
                            <i class="fas fa-plus me-2"></i>{{ __('Upload Document') }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- Media Section -->
            <div id="media-content" class="tab-content" style="display: none;">
                @if($kegiatan->getMedia('media_pendukung') && $kegiatan->getMedia('media_pendukung')->count() > 0)
                    <div class="row g-3">
                        @foreach($kegiatan->getMedia('media_pendukung') as $media)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card file-card h-100 shadow-sm hover-shadow transition-all">
                                    <div class="card-body p-3">
                                        <div class="file-icon text-center mb-3">
                                            @if(strstr($media->mime_type, "image/"))
                                                <img src="{{ $media->getUrl('thumb') }}" class="img-fluid rounded" alt="{{ $media->getCustomProperty('keterangan') ?? $media->name }}" style="max-height: 120px; object-fit: cover;">
                                            @elseif(strstr($media->mime_type, "video/"))
                                                <i class="fas fa-file-video fa-4x text-warning"></i>
                                            @elseif(strstr($media->mime_type, "audio/"))
                                                <i class="fas fa-file-audio fa-4x text-info"></i>
                                            @else
                                                <i class="fas fa-file fa-4x text-secondary"></i>
                                            @endif
                                        </div>
                                        <h6 class="card-title text-truncate" title="{{ $media->getCustomProperty('keterangan') ?? $media->name }}">
                                            {{ Str::limit($media->getCustomProperty('keterangan') ?? $media->name, 25) }}
                                        </h6>
                                        <div class="file-meta">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-calendar me-1"></i>{{ $media->created_at->format('d M Y') }}
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-weight me-1"></i>{{ $media->human_readable_size }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 p-2">
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="previewFile('{{ $media->getUrl() }}', '{{ $media->mime_type }}')" title="{{ __('Preview') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ $media->getUrl() }}" class="btn btn-outline-success btn-sm" download="{{ $media->getCustomProperty('keterangan') ?? $media->name }}" title="{{ __('Download') }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteFile('{{ $media->id }}')" title="{{ __('Delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-images fa-4x text-muted mb-3"></i>
                        <h6 class="text-muted">{{ __('No supporting media uploaded yet') }}</h6>
                        <p class="text-muted small">{{ __('Upload photos, videos, or other media files') }}</p>
                        <button class="btn btn-primary" onclick="uploadDocument('media_pendukung')">
                            <i class="fas fa-plus me-2"></i>{{ __('Upload Media') }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- If no files at all -->
            @if($kegiatan->getMedia('dokumen_pendukung')->count() == 0 && $kegiatan->getMedia('media_pendukung')->count() == 0)
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                    <h6 class="text-muted">{{ __('No files uploaded yet') }}</h6>
                    <p class="text-muted small">{{ __('Upload documents and media to support this activity') }}</p>
                    <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-primary" onclick="uploadDocument('dokumen_pendukung')">
                        <i class="fas fa-plus me-2"></i>{{ __('Upload Document') }}
                    </button>
                    <button class="btn btn-info" onclick="uploadDocument('media_pendukung')">
                        <i class="fas fa-plus me-2"></i>{{ __('Upload Media') }}
                    </button>
                  </div>
                </div>
            @endif

            <div class="mt-3">
                <button class="btn btn-outline-primary btn-sm" onclick="uploadDocument('dokumen_pendukung')">
                    <i class="fas fa-plus me-1"></i>{{ __('Upload Document') }}
                </button>
                <button class="btn btn-outline-info btn-sm" onclick="uploadDocument('media_pendukung')">
                    <i class="fas fa-plus me-1"></i>{{ __('Upload Media') }}
                </button>
            </div>
        </div>

        <!-- Related Activities Section -->
        <div class="card-body border-top">
            <h5 class="mb-3"><i class="me-2"></i>{{ __('Related Activities') }}</h5>

            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-primary">{{ __('Activities in Same Program') }}</h6>
                    @php
                        $program = optional($kegiatan->activity)->program_outcome_output ?
                                 optional($kegiatan->activity->program_outcome_output)->program_outcome ?
                                 optional($kegiatan->activity->program_outcome_output->program_outcome)->program : null : null;
                        $relatedActivities = $program ? $program->kegiatan()->where('trkegiatan.id', '!=', $kegiatan->id)->limit(3)->get() : collect();
                    @endphp
                    @if($relatedActivities && $relatedActivities->count() > 0)
                        <div class="list-group">
                            @foreach($relatedActivities as $related)
                                <a href="{{ route('kegiatan.show', $related->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ optional($related->activity)->nama ?? 'N/A' }}</h6>
                                        <small>{{ optional($related->tanggalmulai)->format('M Y') ?? 'N/A' }}</small>
                                    </div>
                                    <p class="mb-1">{{ optional($related->jenisKegiatan)->nama ?? 'N/A' }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">{{ __('No other activities in this program') }}</p>
                    @endif
                </div>

                <div class="col-md-12">
                    <h6 class="text-success">{{ __('Activities by Same Partners') }}</h6>
                    @if(optional($kegiatan->mitra)->count() > 0)
                        <div class="list-group">
                            @foreach($kegiatan->mitra->take(3) as $mitra)
                                @php
                                    $partnerActivities = $mitra->kegiatan()->where('trkegiatan.id', '!=', $kegiatan->id)->limit(2)->get();
                                @endphp
                                @if($partnerActivities && $partnerActivities->count() > 0)
                                    @foreach($partnerActivities as $related)
                                        <a href="{{ route('kegiatan.show', $related->id) }}" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ optional($related->activity)->nama ?? ' ' }}</h6>
                                                <small>{{ optional($related->tanggalmulai)->format('M Y') ?? '-' }}</small>
                                            </div>
                                            <p class="mb-1">{{ optional($related->jenisKegiatan)->nama ?? '*' }}</p>
                                        </a>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">{{ __('No activities by same partners') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @include('tr.kegiatan.custom.css')
@endpush

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

    <script>
        function uploadDocument(collection) {
            const isDocument = collection === 'dokumen_pendukung';
            const title = isDocument ? '{{ __('Upload Document') }}' : '{{ __('Upload Media') }}';
            const accept = isDocument ? '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt' : '.jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.mp3,.wav';
            const placeholder = isDocument ? '{{ __('Document Name') }}' : '{{ __('Media Name') }}';

            Swal.fire({
                title: title,
                html: `
                    <input type="file" id="documentFile" class="form-control mb-3" accept="${accept}">
                    <input type="text" id="documentName" class="form-control" placeholder="${placeholder}">
                `,
                showCancelButton: true,
                confirmButtonText: '{{ __('Upload') }}',
                cancelButtonText: '{{ __('Cancel') }}',
                preConfirm: () => {
                    const file = document.getElementById('documentFile').files[0];
                    const name = document.getElementById('documentName').value;

                    if (!file) {
                        Swal.showValidationMessage('{{ __('Please select a file') }}');
                        return false;
                    }

                    if (!name) {
                        Swal.showValidationMessage(isDocument ? '{{ __('Please enter document name') }}' : '{{ __('Please enter media name') }}');
                        return false;
                    }

                    return { file, name };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('file', result.value.file);
                    formData.append('name', result.value.name);
                    formData.append('collection', collection);
                    formData.append('kegiatan_id', {{ $kegiatan->id }});

                    fetch('{{ route('kegiatan.upload-document') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const successMessage = isDocument ? '{{ __('Document uploaded successfully') }}' : '{{ __('Media uploaded successfully') }}';
                            Swal.fire('{{ __('Success') }}', successMessage, 'success')
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            Swal.fire('{{ __('Error') }}', data.message || '{{ __('Upload failed') }}', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('{{ __('Error') }}', '{{ __('Upload failed') }}', 'error');
                    });
                }
            });
        }

        // Tab switching functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.getElementById('documents-content').style.display = 'none';
            document.getElementById('media-content').style.display = 'none';

            // Remove active class from all tabs
            document.getElementById('documents-tab').classList.remove('active');
            document.getElementById('media-tab').classList.remove('active');

            // Show selected tab content
            document.getElementById(tabName + '-content').style.display = 'block';
            document.getElementById(tabName + '-tab').classList.add('active');
        }

        // File preview functionality
        function previewFile(url, mimeType) {
            if (mimeType.startsWith('image/')) {
                Swal.fire({
                    title: '{{ __('Image Preview') }}',
                    html: `<img src="${url}" class="img-fluid" style="max-width: 100%; height: auto;">`,
                    width: '80%',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            } else if (mimeType === 'application/pdf') {
                Swal.fire({
                    title: '{{ __('PDF Preview') }}',
                    html: `<iframe src="${url}" style="width: 100%; height: 500px; border: none;"></iframe>`,
                    width: '80%',
                    height: '600px',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            } else {
                // For other file types, open in new tab
                window.open(url, '_blank');
            }
        }

        // File delete functionality
        function deleteFile(mediaId) {
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: "{{ __('You won\'t be able to revert this!') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('Yes, delete it!') }}',
                cancelButtonText: '{{ __('Cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make AJAX request to delete file
                    fetch(`/kegiatan/media/${mediaId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('{{ __('Deleted!') }}', '{{ __('File has been deleted.') }}', 'success');
                            // Reload the page to refresh the file list
                            location.reload();
                        } else {
                            Swal.fire('{{ __('Error!') }}', data.message || '{{ __('Failed to delete file.') }}', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('{{ __('Error!') }}', '{{ __('Failed to delete file.') }}', 'error');
                    });
                }
            });
        }

        // Add hover effects for file cards
        document.addEventListener('DOMContentLoaded', function() {
            const fileCards = document.querySelectorAll('.file-card');
            fileCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 0.125rem 0.25rem rgba(0,0,0,0.075)';
                });
            });

            // Print functionality
            const printBtn = document.querySelector('.print-btn');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    window.print();
                });
            }
        });
    </script>
@endpush
