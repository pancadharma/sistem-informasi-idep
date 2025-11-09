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
                <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('kegiatan.index') }}`"
                    title="{{ __('global.back') }}">
                    <i class="fa fa-arrow-left"></i>
                </button>
            </div>
        </div>

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
                                {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}
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

        <!-- Activity Metrics Section -->
        <div class="card-body border-top">
            <div class="col-md-12">
                <h5 class="text-success"><i class="fas fa-tasks me-2"></i>{{ __('Activity Metrics') }}</h5>
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

        <!-- Continue with rest of the view... -->
        <!-- (Demographic table, Documents section, etc.) -->

    </div>

@stop
