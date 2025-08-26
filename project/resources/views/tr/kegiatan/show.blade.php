@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.kegiatan.label'))
{{-- @section('content_header_title', __('global.details') . ' ' . __('cruds.kegiatan.label')) --}}
@section('content_header_title')
    <button type="button" class="btn btn-secondary btn-sm "
        title="{{ __('global.print') . ' ' . __('cruds.kegiatan.label') }}">
        <!-- icon fa print-->
        <i class="fa fa-print"></i>
    </button>
@endsection
@section('sub_breadcumb', __('cruds.kegiatan.list'))

@section('content_body')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h1 class="card-title">
                {{-- {{ $kegiatan->activity->kode }} | {{ $kegiatan->activity->nama }} --}}
                {{ __('BACK TO OFFICE REPORT') }}
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
                                {{ $kegiatan->activity->program_outcome_output->program_outcome->program->kode ?? '-' }}
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.program_nama') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                {{ $kegiatan->activity->program_outcome_output->program_outcome->program->nama ?? '-' }}
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.kode') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $kegiatan->activity->kode ?? '-' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.nama') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $kegiatan->activity->nama ?? '-' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.penulis.laporan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @foreach ($kegiatan->datapenulis as $penulis)
                                    {{ $penulis->nama ?? '' }},
                                @endforeach
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.penulis.jabatan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @foreach ($kegiatan->datapenulis as $penulis)
                                    {{ $penulis->kegiatanPeran->nama . ',' ?? '' }}
                                @endforeach
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $kegiatan->jenisKegiatan->nama ?? '' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.penulis.jabatan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @foreach ($kegiatan->sektor as $key => $value)
                                    {{ $value->nama . ',' ?? '' }}
                                @endforeach
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.fase_pelaporan') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $kegiatan->fasepelaporan ?? '' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.tanggalmulai') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d-m-Y') ?? '' }}
                                ({{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->diffForHumans() ?? '' }})
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.tanggalselesai') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d-m-Y') ?? '' }}
                                ({{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->diffForHumans() ?? '' }})
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.durasi') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $durationInDays ?? '-' }} {{ __('cruds.kegiatan.days') }}
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.basic.nama_mitra') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @foreach ($kegiatan->mitra as $partner)
                                    {{ $partner->nama . ',' ?? '' }}
                                @endforeach
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.status') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">{{ $kegiatan->status ?? '' }}</td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('cruds.kegiatan.tempat') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @if ($kegiatan->lokasi->isNotEmpty())
                                    {{ $kegiatan->lokasi->unique('kabupaten_id')->pluck('desa.kecamatan.kabupaten.nama')->implode(', ') }}
                                    @if ($kegiatan->lokasi->unique('provinsi_id')->count() == 1)
                                        ,
                                        {{ $kegiatan->lokasi->first()->desa->kecamatan->kabupaten->provinsi->nama ?? '' }}
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle bg-info">
                            <th colspan="3" class="align-middle text-white">
                                {{ __('Program Hierarchy & Progress') }} <i class="fas fa-sitemap"></i>
                            </th>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Program Hierarchy Details -->
                <table class="table datatable table-sm mb-0 table-hover">
                    <tbody>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('Program Outcome') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                <strong>{{ $kegiatan->activity->program_outcome_output->program_outcome->nama ?? '-' }}</strong>
                                @if($kegiatan->activity->program_outcome_output->program_outcome->target_progress)
                                    <div class="progress mt-1" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $kegiatan->activity->program_outcome_output->program_outcome->target_progress->progress_percentage ?? 0 }}%"
                                             aria-valuenow="{{ $kegiatan->activity->program_outcome_output->program_outcome->target_progress->progress_percentage ?? 0 }}"
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">{{ $kegiatan->activity->program_outcome_output->program_outcome->target_progress->progress_percentage ?? 0 }}% Complete</small>
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('Program Output') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                <strong>{{ $kegiatan->activity->program_outcome_output->nama ?? '-' }}</strong>
                                @if($kegiatan->activity->program_outcome_output->target_reinstra)
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            Target: {{ $kegiatan->activity->program_outcome_output->target_reinstra->target_value ?? 0 }} 
                                            {{ $kegiatan->activity->program_outcome_output->target_reinstra->satuan->nama ?? '' }}
                                        </small>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('Activity Target') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @if($kegiatan->activity->target_reinstra)
                                    <span class="badge bg-primary">{{ $kegiatan->activity->target_reinstra->target_value ?? 0 }} {{ $kegiatan->activity->target_reinstra->satuan->nama ?? '' }}</span>
                                @else
                                    <span class="text-muted">{{ __('No target set') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('Program Goals') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @php
                                    $program = optional($kegiatan->activity)->program_outcome_output ? 
                                             optional($kegiatan->activity->program_outcome_output)->program_outcome ? 
                                             optional($kegiatan->activity->program_outcome_output->program_outcome)->program : null : null;
                                    $goals = $program ? $program->goals : collect();
                                @endphp
                                @if($goals && $goals->count() > 0)
                                    @foreach($goals->take(3) as $goal)
                                        <span class="badge bg-info me-1">{{ $goal->nama }}</span>
                                    @endforeach
                                    @if($goals && $goals->count() > 3)
                                        <span class="badge bg-secondary">+{{ $goals->count() - 3 }} more</span>
                                    @endif
                                @else
                                    <span class="text-muted">{{ __('No goals defined') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <th class="align-middle w-25">{{ __('SDGs') }}</th>
                            <td class="text-center align-middle" style="width: 1%;">:</td>
                            <td class="align-middle w-50">
                                @php
                                    $program = optional($kegiatan->activity)->program_outcome_output ? 
                                             optional($kegiatan->activity->program_outcome_output)->program_outcome ? 
                                             optional($kegiatan->activity->program_outcome_output->program_outcome)->program : null : null;
                                    $sdgs = $program ? $program->kaitanSdg : collect();
                                @endphp
                                @if($sdgs && $sdgs->count() > 0)
                                    @foreach($sdgs as $sdg)
                                        <span class="badge bg-warning text-dark me-1">SDG {{ $sdg->kode }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">{{ __('No SDGs linked') }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <table class="table datatable table-sm mb-0 table-hover">
                    <tbody>
                        <tr class="align-middle bg-success">
                            <th colspan="3" class="align-middle">
                                {{ __('global.details') . ' ' . __('cruds.kegiatan.tempat') }} <i
                                    class="fas fa-map-marker-alt"></i>
                            </th>
                        </tr>
                    </tbody>
                </table>
                {{-- detail lokasi --}}
                <table class="table datatable table-sm mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="tb-header mr-0 pr-0 align-middle col-3" {{-- style="width: 20%;" --}}>Nama Tempat</th>
                            <th class="tb-header mr-0 pr-0 align-middle col-3" {{-- style="width: 20%;" --}}>Longitude</th>
                            <th class="tb-header mr-0 pr-0 align-middle col-3" {{-- style="width: 30%;" --}}>Latitude</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatan->lokasi as $lokasi)
                            <tr class="align-middle">
                                <td class="tb-header mr-0 pr-0 align-middle">
                                    @if ($lokasi->lat && $lokasi->long)
                                        <a href="https://www.google.com/maps?q={{ $lokasi->lat }},{{ $lokasi->long }}"
                                            target="_blank">
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
                                <td colspan="3" class="text-center text-muted">Tidak ada data lokasi tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Activity Progress & Details Section -->
        <div class="card-body border-top">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary"><i class="fas fa-chart-line me-2"></i>{{ __('Activity Progress') }}</h5>
                    <div class="mb-3">
                        @php
                            $progress = 0;
                            if($kegiatan->tanggalmulai && $kegiatan->tanggalselesai) {
                                $startDate = $kegiatan->tanggalmulai instanceof \Carbon\Carbon ? $kegiatan->tanggalmulai : \Carbon\Carbon::parse($kegiatan->tanggalmulai);
                                $endDate = $kegiatan->tanggalselesai instanceof \Carbon\Carbon ? $kegiatan->tanggalselesai : \Carbon\Carbon::parse($kegiatan->tanggalselesai);
                                $totalDays = $startDate->diffInDays($endDate);
                                if($totalDays > 0) {
                                    $elapsedDays = $startDate->diffInDays(now());
                                    if($endDate < now()) {
                                        $progress = 100;
                                    } else {
                                        $progress = max(0, min(100, ($elapsedDays / $totalDays) * 100));
                                    }
                                }
                            }
                        @endphp
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ __('Overall Progress') }}</span>
                            <span>{{ round($progress, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar {{ $progress >= 100 ? 'bg-success' : 'bg-primary' }}" 
                                 role="progressbar" 
                                 style="width: {{ round($progress, 1) }}%"></div>
                        </div>
                    </div>
                    
                    @if($kegiatan->assessment)
                        <div class="alert alert-info">
                            <h6 class="alert-heading">{{ __('Latest Assessment') }}</h6>
                            <p class="mb-0">{{ Str::limit($kegiatan->assessment->hasil_assessment, 150) }}</p>
                            <small class="text-muted">{{ optional($kegiatan->assessment->tanggal_assessment)->format('d M Y') }}</small>
                        </div>
                    @endif
                </div>
                
                <div class="col-md-6">
                    <h5 class="text-success"><i class="fas fa-tasks me-2"></i>{{ __('Activity Metrics') }}</h5>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h3 class="mb-0 text-primary">{{ optional($kegiatan->lokasi)->count() ?? 0 }}</h3>
                                <small>{{ __('Locations') }}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h3 class="mb-0 text-success">{{ optional($kegiatan->mitra)->count() ?? 0 }}</h3>
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
        
        <!-- Activity Timeline & History Section -->
        <div class="card-body border-top">
            <h5 class="text-secondary mb-3"><i class="fas fa-history me-2"></i>{{ __('Activity Timeline & History') }}</h5>
            
            <div class="timeline">
                <!-- Planning Phase -->
                <div class="time-label">
                    <span class="bg-info">{{ __('Planning Phase') }}</span>
                </div>
                <div>
                    <i class="fas fa-calendar-alt bg-blue"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> {{ optional($kegiatan->created_at)->format('d M Y H:i') ?? 'N/A' }}</span>
                        <h3 class="timeline-header">{{ __('Activity Created') }}</h3>
                        <div class="timeline-body">
                            {{ __('Activity was created and registered in the system') }}
                        </div>
                    </div>
                </div>
                
                <!-- Execution Phase -->
                <div class="time-label">
                    <span class="bg-success">{{ __('Execution Phase') }}</span>
                </div>
                <div>
                    <i class="fas fa-play bg-green"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> {{ optional($kegiatan->tanggalmulai)->format('d M Y') ?? 'N/A' }}</span>
                        <h3 class="timeline-header">{{ __('Activity Started') }}</h3>
                        <div class="timeline-body">
                            {{ __('Activity execution began') }}
                            @if($kegiatan->lokasi && $kegiatan->lokasi->count() > 0)
                                <br><strong>{{ __('Locations') }}:</strong> {{ $kegiatan->lokasi->pluck('lokasi')->implode(', ') }}
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Monitoring Events -->
                @if($kegiatan->monitoring && $kegiatan->monitoring->count() > 0)
                    @foreach($kegiatan->monitoring->take(3) as $monitoring)
                        <div>
                            <i class="fas fa-eye bg-warning"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ optional($monitoring->tanggal_monitoring)->format('d M Y') ?? 'N/A' }}</span>
                                <h3 class="timeline-header">{{ __('Monitoring Visit') }}</h3>
                                <div class="timeline-body">
                                    {{ Str::limit($monitoring->hasil_monitoring, 100) }}
                                    @if($monitoring->user)
                                        <br><small class="text-muted">{{ __('By') }}: {{ $monitoring->user->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                
                <!-- Completion -->
                <div>
                    <i class="fas fa-flag-checkered bg-red"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> {{ optional($kegiatan->tanggalselesai)->format('d M Y') ?? 'N/A' }}</span>
                        <h3 class="timeline-header">{{ __('Activity Completed') }}</h3>
                        <div class="timeline-body">
                            {{ __('Activity execution completed') }}
                            @if($kegiatan->penerimamanfaattotal > 0)
                                <br><strong>{{ __('Total Beneficiaries') }}:</strong> {{ $kegiatan->penerimamanfaattotal }}
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Assessment -->
                @if($kegiatan->assessment)
                    <div class="time-label">
                        <span class="bg-warning">{{ __('Assessment Phase') }}</span>
                    </div>
                    <div>
                        <i class="fas fa-clipboard-check bg-purple"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ optional($kegiatan->assessment->tanggal_assessment)->format('d M Y') ?? 'N/A' }}</span>
                            <h3 class="timeline-header">{{ __('Activity Assessment') }}</h3>
                            <div class="timeline-body">
                                {{ Str::limit($kegiatan->assessment->hasil_assessment, 150) }}
                                @if($kegiatan->assessment->user)
                                    <br><small class="text-muted">{{ __('Assessed by') }}: {{ $kegiatan->assessment->user->name }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Last Update -->
                <div>
                    <i class="fas fa-edit bg-gray"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> {{ optional($kegiatan->updated_at)->format('d M Y H:i') ?? 'N/A' }}</span>
                        <h3 class="timeline-header">{{ __('Last Updated') }}</h3>
                        <div class="timeline-body">
                            {{ __('Activity information was last updated') }}
                        </div>
                    </div>
                </div>
            </div>
            
            @if($kegiatan->monitoring && $kegiatan->monitoring->count() > 3)
                <div class="text-center mt-3">
                    <button class="btn btn-outline-info btn-sm" onclick="showAllMonitoring()">
                        <i class="fas fa-list me-1"></i>{{ __('View All Monitoring History') }}
                    </button>
                </div>
            @endif
        </div>
        
        <!-- Budget & Financial Information Section -->
        <div class="card-body border-top">
            <h5 class="text-warning mb-3"><i class="fas fa-dollar-sign me-2"></i>{{ __('Budget & Financial Information') }}</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0">{{ __('Budget Overview') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <th>{{ __('Total Budget') }}</th>
                                        <td class="text-end">
                                            @if($kegiatan->total_budget)
                                                <span class="fw-bold">{{ 'Rp ' . number_format($kegiatan->total_budget, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">{{ __('Not specified') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Budget Utilized') }}</th>
                                        <td class="text-end">
                                            @if($kegiatan->budget_utilized)
                                                <span class="text-primary">{{ 'Rp ' . number_format($kegiatan->budget_utilized, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">Rp 0</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Remaining Budget') }}</th>
                                        <td class="text-end">
                                            @if($kegiatan->total_budget && $kegiatan->budget_utilized)
                                                <span class="text-success">{{ 'Rp ' . number_format($kegiatan->total_budget - $kegiatan->budget_utilized, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">{{ __('Not specified') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            @if($kegiatan->total_budget > 0)
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ __('Budget Utilization') }}</span>
                                        <span>{{ round(($kegiatan->budget_utilized / $kegiatan->total_budget) * 100, 1) }}%</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-warning" 
                                             role="progressbar" 
                                             style="width: {{ round(($kegiatan->budget_utilized / $kegiatan->total_budget) * 100, 1) }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">{{ __('Funding Sources') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($kegiatan->funding_sources && $kegiatan->funding_sources->count() > 0)
                                <div class="list-group">
                                    @foreach($kegiatan->funding_sources->take(5) as $funding)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $funding->nama ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $funding->tipe ?? 'N/A' }}</small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">
                                                @if($funding->jumlah)
                                                    {{ 'Rp ' . number_format($funding->jumlah, 0, ',', '.') }}
                                                @else
                                                    {{ __('Not specified') }}
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                @if($kegiatan->funding_sources && $kegiatan->funding_sources->count() > 5)
                                    <div class="text-center mt-2">
                                        <small class="text-muted">{{ __('+ :count more sources', ['count' => $kegiatan->funding_sources->count() - 5]) }}</small>
                                    </div>
                                @endif
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p>{{ __('No funding sources specified') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Budget Breakdown -->
            @if($kegiatan->budget_breakdown && $kegiatan->budget_breakdown->count() > 0)
                <div class="mt-4">
                    <h6 class="text-info">{{ __('Budget Breakdown') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th class="text-end">{{ __('Amount') }}</th>
                                    <th class="text-end">{{ __('Percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kegiatan->budget_breakdown as $breakdown)
                                    <tr>
                                        <td>{{ $breakdown->kategori ?? 'N/A' }}</td>
                                        <td>{{ $breakdown->deskripsi ?? '-' }}</td>
                                        <td class="text-end">{{ 'Rp ' . number_format($breakdown->jumlah, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            @if($kegiatan->total_budget > 0)
                                                {{ round(($breakdown->jumlah / $kegiatan->total_budget) * 100, 1) }}%
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Stakeholders & Partners Detailed Section -->
        <div class="card-body border-top">
            <h5 class="text-success mb-3"><i class="fas fa-users me-2"></i>{{ __('Stakeholders & Partners' ) }}</h5>
            
            <div class="row">
                <!-- Implementation Partners -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">{{ __('Implementation Partners') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($kegiatan->mitra && $kegiatan->mitra->count() > 0)
                                <div class="accordion" id="partnersAccordion">
                                    @foreach($kegiatan->mitra as $index => $partner)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $index }}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                                                    <strong>{{ $partner->nama ?? 'N/A' }}</strong>
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#partnersAccordion">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>{{ __('Type') }}:</strong> {{ $partner->tipe_mitra ?? 'N/A' }}</p>
                                                            <p><strong>{{ __('Contact Person') }}:</strong> {{ $partner->kontak ?? 'N/A' }}</p>
                                                            <p><strong>{{ __('Phone') }}:</strong> {{ $partner->telepon ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>{{ __('Email') }}:</strong> {{ $partner->email ?? 'N/A' }}</p>
                                                            <p><strong>{{ __('Address') }}:</strong> {{ $partner->alamat ?? 'N/A' }}</p>
                                                            <p><strong>{{ __('Role') }}:</strong> {{ $partner->peran ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                    @if($partner->deskripsi)
                                                        <div class="mt-2">
                                                            <strong>{{ __('Description') }}:</strong>
                                                            <p class="mb-0">{{ $partner->deskripsi }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-handshake fa-2x mb-2"></i>
                                    <p>{{ __('No implementation partners specified') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Team Members -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">{{ __('Team Members') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($kegiatan->datapenulis && $kegiatan->datapenulis->count() > 0)
                                <div class="list-group">
                                    @foreach($kegiatan->datapenulis as $penulis)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $penulis->nama ?? 'N/A' }}</h6>
                                                <span class="badge bg-primary">{{ $penulis->pivot->peran->nama ?? 'N/A' }}</span>
                                            </div>
                                            <p class="mb-1">{{ $penulis->jabatan->nama ?? 'N/A' }}</p>
                                            <small class="text-muted">
                                                {{ $penulis->instansi ?? 'N/A' }}
                                                @if($penulis->email)
                                                    | {{ $penulis->email }}
                                                @endif
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-user-tie fa-2x mb-2"></i>
                                    <p>{{ __('No team members specified') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Beneficiary Demographics -->
            @if($kegiatan->penerimamanfaattotal > 0)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">{{ __('Beneficiary Demographics') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="border rounded p-3 mb-2">
                                            <h3 class="mb-0 text-info">{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}</h3>
                                            <small>{{ __('Female') }}</small>
                                            <div class="progress mt-1" style="height: 5px;">
                                                <div class="progress-bar bg-pink" role="progressbar" 
                                                     style="width: {{ $kegiatan->penerimamanfaattotal > 0 ? ($kegiatan->penerimamanfaatperempuantotal / $kegiatan->penerimamanfaattotal) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="border rounded p-3 mb-2">
                                            <h3 class="mb-0 text-primary">{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}</h3>
                                            <small>{{ __('Male') }}</small>
                                            <div class="progress mt-1" style="height: 5px;">
                                                <div class="progress-bar bg-blue" role="progressbar" 
                                                     style="width: {{ $kegiatan->penerimamanfaattotal > 0 ? ($kegiatan->penerimamanfaatlakilakitotal / $kegiatan->penerimamanfaattotal) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="border rounded p-3 mb-2">
                                            <h3 class="mb-0 text-warning">{{ $kegiatan->penerimamanfaatdisabilitastotal ?? 0 }}</h3>
                                            <small>{{ __('Disabled') }}</small>
                                            <div class="progress mt-1" style="height: 5px;">
                                                <div class="progress-bar bg-warning" role="progressbar" 
                                                     style="width: {{ $kegiatan->penerimamanfaattotal > 0 ? ($kegiatan->penerimamanfaatdisabilitastotal / $kegiatan->penerimamanfaattotal) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="border rounded p-3 mb-2">
                                            <h3 class="mb-0 text-success">{{ $kegiatan->penerimamanfaatmarjinaltotal ?? 0 }}</h3>
                                            <small>{{ __('Marginalized') }}</small>
                                            <div class="progress mt-1" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $kegiatan->penerimamanfaattotal > 0 ? ($kegiatan->penerimamanfaatmarjinaltotal / $kegiatan->penerimamanfaattotal) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Impact & Outcome Metrics Section -->
        <div class="card-body border-top">
            <h5 class="text-danger mb-3"><i class="fas fa-chart-bar me-2"></i>{{ __('Impact & Outcome Metrics') }}</h5>
            
            <div class="row">
                <!-- Key Performance Indicators -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0">{{ __('Key Performance Indicators') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($kegiatan->indicators && $kegiatan->indicators->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Indicator') }}</th>
                                                <th class="text-end">{{ __('Target') }}</th>
                                                <th class="text-end">{{ __('Actual') }}</th>
                                                <th class="text-end">{{ __('Achievement') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kegiatan->indicators as $indicator)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $indicator->nama ?? 'N/A' }}</strong>
                                                        <br><small class="text-muted">{{ $indicator->deskripsi ?? '-' }}</small>
                                                    </td>
                                                    <td class="text-end">{{ $indicator->target ?? 0 }}</td>
                                                    <td class="text-end">{{ $indicator->actual ?? 0 }}</td>
                                                    <td class="text-end">
                                                        @if($indicator->target > 0)
                                                            <span class="badge {{ ($indicator->actual / $indicator->target) >= 1 ? 'bg-success' : 'bg-warning' }}">
                                                                {{ round(($indicator->actual / $indicator->target) * 100, 1) }}%
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-tasks fa-2x mb-2"></i>
                                    <p>{{ __('No KPIs defined') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Outcome Assessment -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0">{{ __('Outcome Assessment') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($kegiatan->assessment)
                                <div class="mb-3">
                                    <h6>{{ __('Overall Rating') }}</h6>
                                    <div class="text-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star fa-2x {{ $i <= ($kegiatan->assessment->rating ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <div class="mt-2">
                                            <span class="badge bg-warning text-dark">{{ $kegiatan->assessment->rating ?? 0 }}/5</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>{{ __('Assessment Summary') }}</h6>
                                    <p>{{ $kegiatan->assessment->hasil_assessment ?? 'N/A' }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>{{ __('Key Findings') }}</h6>
                                    <ul class="mb-0">
                                        @if($kegiatan->assessment->findings)
                                            @foreach(explode("\n", $kegiatan->assessment->findings) as $finding)
                                                @if(trim($finding))
                                                    <li>{{ trim($finding) }}</li>
                                                @endif
                                            @endforeach
                                        @else
                                            <li class="text-muted">{{ __('No specific findings recorded') }}</li>
                                        @endif
                                    </ul>
                                </div>
                                
                                <div class="text-center">
                                    <small class="text-muted">
                                        {{ __('Assessed by') }}: {{ optional($kegiatan->assessment->user)->name ?? 'N/A' }}
                                        <br>
                                        {{ __('Date') }}: {{ optional($kegiatan->assessment->tanggal_assessment)->format('d M Y') ?? 'N/A' }}
                                    </small>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-clipboard-check fa-2x mb-2"></i>
                                    <p>{{ __('No assessment conducted yet') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Success Stories & Testimonials -->
            @if($kegiatan->testimonials && $kegiatan->testimonials->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">{{ __('Success Stories & Testimonials') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($kegiatan->testimonials->take(3) as $testimonial)
                                        <div class="col-md-4">
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-quote-left text-muted me-2"></i>
                                                        <h6 class="mb-0">{{ $testimonial->nama ?? 'Anonymous' }}</h6>
                                                    </div>
                                                    <p class="card-text">"{{ Str::limit($testimonial->konten, 150) }}"</p>
                                                    <small class="text-muted">
                                                        {{ $testimonial->peran ?? 'Beneficiary' }}
                                                        @if($testimonial->tanggal)
                                                            | {{ $testimonial->tanggal->format('M Y') }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($kegiatan->testimonials && $kegiatan->testimonials->count() > 3)
                                    <div class="text-center mt-2">
                                        <button class="btn btn-outline-success btn-sm" onclick="showAllTestimonials()">
                                            <i class="fas fa-book me-1"></i>{{ __('View All Stories') }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Lessons Learned -->
            @if($kegiatan->lessons_learned)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">{{ __('Lessons Learned') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-success">{{ __('What Worked Well') }}</h6>
                                        <ul>
                                            @foreach(explode("\n", $kegiatan->lessons_learned->positives ?? '') as $positive)
                                                @if(trim($positive))
                                                    <li>{{ trim($positive) }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-warning">{{ __('Areas for Improvement') }}</h6>
                                        <ul>
                                            @foreach(explode("\n", $kegiatan->lessons_learned->improvements ?? '') as $improvement)
                                                @if(trim($improvement))
                                                    <li>{{ trim($improvement) }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @if($kegiatan->lessons_learned->recommendations)
                                    <div class="mt-3">
                                        <h6 class="text-primary">{{ __('Recommendations for Future Activities') }}</h6>
                                        <p>{{ $kegiatan->lessons_learned->recommendations }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
            <h5 class="text-warning mb-3"><i class="fas fa-link me-2"></i>{{ __('Related Activities') }}</h5>
            
            <div class="row">
                <div class="col-md-6">
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
                
                <div class="col-md-6">
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
                                                <h6 class="mb-1">{{ optional($related->activity)->nama ?? 'N/A' }}</h6>
                                                <small>{{ optional($related->tanggalmulai)->format('M Y') ?? 'N/A' }}</small>
                                            </div>
                                            <p class="mb-1">{{ optional($related->jenisKegiatan)->nama ?? 'N/A' }}</p>
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

    <style>
        .kegiatan-table-data .tb-header {
            width: 20%;
        }

        .kegiatan-table-data .separator {
            width: 1%;
        }

        .kegiatan-table-data .tb-value {
            width: 79%;
        }

        /* Optional: If you want to disable Bootstrap's responsive table behavior for this specific table */
        .kegiatan-table-data {
            width: 100% !important;
            /* Force the table to take full width */
            table-layout: fixed;
            /* Crucial for fixed column widths */
        }
        
        /* Timeline Styles */
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #dee2e6;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }
        
        .timeline > div {
            position: relative;
            margin-right: 10px;
            margin-bottom: 15px;
        }
        
        .timeline > div > .fa,
        .timeline > div > .fas,
        .timeline > div > .far,
        .timeline > div > .fab {
            position: absolute;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            left: 18px;
            top: 0;
            padding: 9px 0;
            font-size: 12px;
            color: #fff;
        }
        
        .timeline > .time-label > span {
            font-weight: 600;
            color: #fff;
            font-size: 12px;
            padding: 5px 10px;
            display: inline-block;
            border-radius: 4px;
        }
        
        .timeline > div > .timeline-item {
            margin: 0 0 0 60px;
            background: #fff;
            color: #444;
            border-radius: 3px;
            padding: 15px;
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .timeline > div > .timeline-item > .time {
            color: #999;
            float: right;
            font-size: 12px;
        }
        
        .timeline > div > .timeline-item > .timeline-header {
            margin: 0 0 10px 0;
            border-bottom: 1px solid #f4f4f4;
            padding-bottom: 10px;
            font-size: 16px;
            line-height: 1.1;
        }
        
        .timeline > div > .timeline-item > .timeline-body {
            margin: 0;
            padding: 0;
        }
        
        .timeline > div > .timeline-item > .timeline-footer {
            background: #fff;
            padding: 10px;
            border-top: 1px solid #f4f4f4;
        }

        /* File Cards Styles */
        .file-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
        }
        
        .file-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .file-icon {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .file-icon img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        
        .file-card .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .file-meta {
            font-size: 0.8rem;
        }
        
        .file-card .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Tab Styles */
        .tab-content {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Empty State Styles */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }
        
        .empty-state i {
            opacity: 0.5;
            margin-bottom: 1rem;
        }
        
        .empty-state h6 {
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #adb5bd;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .file-card {
                margin-bottom: 1rem;
            }
            
            .file-icon {
                height: 100px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn-group .btn {
                margin-bottom: 0.25rem;
            }
        }
    </style>
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
    
    <script>
        function showAllMonitoring() {
            Swal.fire({
                title: '{{ __('All Monitoring History') }}',
                html: `
                    <div class="text-left">
                        @if($kegiatan->monitoring && $kegiatan->monitoring->count() > 0)
                            @foreach($kegiatan->monitoring as $monitoring)
                                <div class="border-bottom pb-3 mb-3">
                                    <h6>{{ optional($monitoring->tanggal_monitoring)->format('d M Y') }}</h6>
                                    <p class="mb-1">{{ $monitoring->hasil_monitoring }}</p>
                                    @if($monitoring->user)
                                        <small class="text-muted">{{ __('By') }}: {{ $monitoring->user->name }}</small>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">{{ __('No monitoring data available') }}</p>
                        @endif
                    </div>
                `,
                width: '600px',
                confirmButtonText: '{{ __('Close') }}'
            });
        }
        
        function showAllTestimonials() {
            Swal.fire({
                title: '{{ __('All Success Stories & Testimonials') }}',
                html: `
                    <div class="text-left">
                        @if($kegiatan->testimonials && $kegiatan->testimonials->count() > 0)
                            @foreach($kegiatan->testimonials as $testimonial)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-quote-left text-muted me-2"></i>
                                        <h6 class="mb-0">{{ $testimonial->nama ?? 'Anonymous' }}</h6>
                                    </div>
                                    <p class="mb-1">"{{ $testimonial->konten }}"</p>
                                    <small class="text-muted">
                                        {{ $testimonial->peran ?? 'Beneficiary' }}
                                        @if($testimonial->tanggal)
                                            | {{ $testimonial->tanggal->format('d M Y') }}
                                        @endif
                                    </small>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">{{ __('No testimonials available') }}</p>
                        @endif
                    </div>
                `,
                width: '600px',
                confirmButtonText: '{{ __('Close') }}'
            });
        }
        
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
        });
    </script>
@endpush
