@extends('layouts.app')

@section('subtitle')
    {{ __('global.details') . ' ' . __('cruds.kegiatan.label') }} 
    {{  $kegiatan->programOutcomeOutputActivity?->nama ?? '' }}
@endsection

@section('content_header_title')
    <a href="{{ route('btor.print', $kegiatan->id) }}" class="btn btn-info" target="_blank">
        <i class="fas fa-print"></i> {{ __('btor.print_preview') }}
    </a>
@endsection
@section('sub_breadcumb')
    <a href="{{ route('kegiatan.index') }}">
        {{ __('cruds.kegiatan.list') }}
    </a>
@endsection

@section('content_body')

<div class="card card-outline card-primary mb-3">
    <div class="card-header d-flex align-items-center">
        <h1 class="card-title mb-0">
            {{ __('btor.report_detail') }}
        </h1>
        <div class="card-tools ml-auto">
            <button type="button" class="btn btn-sm btn-default" 
                onclick="window.location.href=`{{ route('kegiatan.index') }}`"
                title="{{ __('global.back') }}">
                <i class="fa fa-arrow-left"></i> {{ __('global.back') }}
            </button>
        </div>
    </div>
</div>

<div class="row">
    <!-- Right Section (Sidebar Sticky) -->
    <div class="col-12 col-lg-4 order-sm-1 order-lg-2">
        <div class="sticky-top" style="top: 80px; z-index: 1000;">
            {{-- Metrics Card --}}
            <div class="card shadow-sm border-0 mb-2 bg-info text-white" 
                style="background: linear-gradient(135deg, #00b7ff8a 0%, #0582a8d3 100%);">
                <div class="card-body p-2">
                    <h5 class="font-weight-bold mb-2 border-bottom border-white-50 pb-2 text-center">
                        <i class="fas fa-users mr-2"></i> {{ __('btor.participant_summary') }}
                    </h5>
                    <div class="row text-center mb-0">
                        <div class="col-6 mb-3">
                            <div class="bg-white-10 rounded p-2 border border-white-20 h-100 d-flex flex-column justify-content-center">
                                <h2 class="font-weight-bold mb-0 text-white">{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</h2>
                                <small class="text-uppercase small text-white-50">{{ __('btor.perempuan') }}</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-white-10 rounded p-2 border border-white-20 h-100 d-flex flex-column justify-content-center">
                                <h2 class="font-weight-bold mb-0 text-white">{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</h2>
                                <small class="text-uppercase small text-white-50">{{ __('btor.laki_laki') }}</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-white-20 rounded p-3 mt-1 shadow-sm border border-white-50">
                                <h1 class="font-weight-bold mb-0 text-white display-4">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</h1>
                                <small class="text-uppercase font-weight-bold text-white">{{ __('btor.total_participants') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penulis Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3 pb-2">
                    <i class="material-symbols-sharp text-info text-sm">
                        article_person
                    </i> 
                    {{ __('btor.penulis_laporan') }}
                </div>
                <div class="card-body pt-0 pb-3">
                    <div class="list-group list-group-flush">
                        @forelse($kegiatan->kegiatan_penulis as $penulis)
                            <div class="list-group-item px-0 py-2 border-0">
                                <div class="media align-items-center">
                                    <div class="bg-light rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center border" style="width:40px;height:40px;">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-0 font-weight-bold text-dark text-truncate" style="max-width: 180px;">{{ $penulis->user?->nama ?? '-' }}</h6>
                                        <span class="badge badge-light border text-muted mt-1">{{ $penulis->peran?->nama ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-2 text-muted small italic text-center bg-light rounded">{{ __('btor.no_data_writer') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sektor Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3 pb-2">
                    <i class="material-symbols-sharp text-info text-sm">
                        handshake
                    </i> 
                    {{ __('btor.sektor_kegiatan') }}
                </div>
                <div class="card-body pt-0 pb-3">
                    <div class="">
                        @php
                        // random color for each sector
                        $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
                        $color = $colors[array_rand($colors)];
                        @endphp

                        @forelse($kegiatan->sektor as $sektor)

                            <span class="badge bg-{{ $color }}">
                                {{ $sektor->nama ?? '-' }}
                            </span>
                        @empty
                            <span class="text-muted">-</span>
                        @endforelse                        
                    </div>
                </div>
            </div>

            {{-- Documents Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3 pb-2 d-flex align-items-center">
                    <div><i class="fas fa-file-alt mr-2 text-primary"></i> {{ __('btor.dokumen_pendukung') }}</div>
                    @php $docs = $kegiatan->getMedia('dokumen_pendukung'); @endphp
                    <span class="badge badge-primary badge-pill ml-auto">{{ $docs->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($docs as $doc)
                            <div class="list-group-item px-3 py-2 border-top border-bottom-0">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="flex-grow-1 text-truncate pe-auto" 
                                         style="cursor:pointer;"
                                         data-url="{{ $doc->getUrl() }}"
                                         data-mime="{{ $doc->mime_type }}"
                                         data-name="{{ $doc->custom_properties['keterangan'] ?? $doc->name }}"
                                         onclick="previewFileFromData(this)"
                                         title="{{ __('Preview/Download') . ' ' . ($doc->custom_properties['keterangan'] ?? $doc->name) }}">
                                        
                                        <i class="fas fa-file-{{ $doc->extension === 'pdf' ? 'pdf text-danger' : ($doc->extension === 'docx' || $doc->extension === 'doc' ? 'word text-primary' : ($doc->extension === 'xlsx' || $doc->extension === 'xls' ? 'excel text-success' : 'alt')) }} mr-2"></i>
                                        <span class="small font-weight-medium text-dark">{{ $doc->custom_properties['keterangan'] ?? $doc->name }}</span>
                                    </div>
                                    <div class="ml-2 text-right">
                                        <span class="badge badge-light border d-none d-md-inline-block mr-1">{{ strtoupper($doc->extension) }}</span>
                                        <a href="{{ $doc->getUrl() }}" download class="btn btn-xs btn-outline-secondary" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-3 py-3 text-muted small italic text-center bg-light mx-3 rounded mb-2 mt-2">{{ __('btor.no_data_docs') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Media Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3 pb-2 d-flex align-items-center">
                    <div><i class="fas fa-images mr-2 text-success"></i> {{ __('btor.media_pendukung') }}</div>
                    @php $mediaList = $kegiatan->getMedia('media_pendukung'); @endphp
                    <span class="badge badge-success badge-pill ml-auto">{{ $mediaList->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($mediaList as $item)
                            <div class="list-group-item px-3 py-2 border-top border-bottom-0">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="flex-grow-1 text-truncate pe-auto"
                                         style="cursor:pointer;"
                                         data-url="{{ $item->getUrl() }}"
                                         data-mime="{{ $item->mime_type }}"
                                         data-name="{{ $item->custom_properties['keterangan'] ?? $item->name }}"
                                         onclick="previewFileFromData(this)"
                                         title="{{ __('Preview') . ' ' . ($item->custom_properties['keterangan'] ?? $item->name) }}">
                                        
                                        @if(str_starts_with($item->mime_type, 'image/'))
                                            <i class="fas fa-image text-info mr-2"></i>
                                        @elseif(str_starts_with($item->mime_type, 'video/'))
                                            <i class="fas fa-video text-danger mr-2"></i>
                                        @else
                                            <i class="fas fa-play-circle text-success mr-2"></i>
                                        @endif
                                        
                                        <span class="small font-weight-medium text-dark">{{ $item->custom_properties['keterangan'] ?? $item->name }}</span>
                                    </div>
                                    <div class="ml-2 text-right">
                                        <a href="{{ $item->getUrl() }}" download class="btn btn-xs btn-outline-secondary" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-3 py-3 text-muted small italic text-center bg-light mx-3 rounded mb-2 mt-2">{{ __('btor.no_data_media') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Left Section (Main Content) -->
    <div class="col-12 col-lg-8 order-sm-2 order-lg-1">
        
        <!-- Header & Meta Information -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h2 class="text-dark font-weight-bold mb-1" data-toggle="tooltip" data-placement="left"
                            title="{{ __('btor.nama_kegiatan') }} : {{ $kegiatan->programOutcomeOutputActivity?->nama ?? 'Activity Name Not Set' }}">
                            {{ $kegiatan->programOutcomeOutputActivity?->nama ?? 'Activity Name Not Set' }}
                        </h2>
                        <p class="text-muted lead mb-0"> 
                            <span data-toggle="tooltip" data-placement="bottom"
                            title="{{ __('btor.kode_kegiatan') }} : {{ $kegiatan->programOutcomeOutputActivity?->kode ?? 'Kode Not Set' }}">
                                {{ $kegiatan->programOutcomeOutputActivity?->kode ?? 'Kode Not Set' }} 
                            </span>
                            <span data-toggle="tooltip" data-placement="bottom"
                            title="{{ __('btor.status') }} : {{ strtoupper($kegiatan->status ?? 'DRAFT') }}" 
                            class="badge {{ $kegiatan->status == 'approved' ? 'badge-success' : 'badge-warning' }} ml-2">
                                {{ strtoupper($kegiatan->status ?? 'DRAFT') }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <div class="p-3 bg-light rounded border-left border-success h-100">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-1">
                                <i class="fas fa-project-diagram mr-1"></i> {{ __('btor.program') }}</h6>
                            <span class="font-weight-bold text-dark small">
                                {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <div class="p-3 bg-light rounded border-left border-info h-100">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-1">
                                <i class="fas fa-layer-group mr-1"></i> {{ __('btor.jenis_kegiatan') }}</h6>
                            <span class="font-weight-bold text-dark small">
                                {{ $kegiatan->jenisKegiatan?->nama ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-3 bg-light rounded border-left border-warning h-100">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-1">
                                <i class="fas fa-flag-checkered mr-1"></i> {{ __('btor.fase_pelaporan') }}</h6>
                            <span class="h5 font-weight-bold text-dark mb-0">
                                {{ $kegiatan->fasepelaporan ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="text-uppercase text-muted font-weight-bold mt-2">
                            <i class="material-symbols-outlined text-danger text-sm">radar</i>
                            {{ __('btor.program_goals') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    {{-- Program Goals Card --}}
                    @php
                        $goal = $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->goal;
                    @endphp
                    @if($goal)


                    {{-- <div class="col-sm-12 col-md-6 col-lg-6 mb-3 mb-md-0">
                        <div class="p-3 bg-light rounded border-left border-success h-100">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-1">
                                <i class="material-symbols-outlined mr-2 text-warning">arrow_right</i> 
                                Target</h6>
                            <span class="font-weight-bold text-dark small">
                                {{ $goal->target ?? '-' }}
                            </span>
                        </div>
                    </div> --}}

                    <div class="col-sm-12 col-md-12 col-lg-12 mb-3">
                        <div class="p-3 bg-light rounded border-left border-success h-100">
                            {{-- <div class=""> --}}
                                <span class="text-muted font-weight-bold text-uppercase d-block mb-1" 
                                style="font-size: 0.75rem;">{{ __('cruds.program.outcome.target') }}</span>
                                <div class="p-2 bg-light rounded text-dark border-left border-danger">
                                    {{ $goal->target ?? '-' }}
                                </div>
                            {{-- </div> --}}
                            {{-- <div class="col-sm-6 col-md-6 col-lg-6 mb-3"> --}}
                                <span class="text-muted font-weight-bold text-uppercase d-block mb-1" 
                                style="font-size: 0.75rem;">{{ __('cruds.program.outcome.indicator') }}</span>
                                <div class="p-2 bg-light rounded text-dark border-left border-warning">
                                    {{ $goal->indikator ?? '-' }}
                                </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                    @else
                    <div class="col-12">
                        <div class="p-3 bg-light rounded border-left border-success h-100">
                            <p class="text-muted italic mb-0">
                                {{ __('btor.no_goals') }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>


            </div>
        </div>

        <!-- Konteks Kegiatan -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <h4 class="card-title font-weight-bold text-dark m-0">
                    <i class="fas fa-info-circle mr-2 text-primary"></i> 1. {{ __('cruds.kegiatan.description.latar_belakang') }}
                </h4>
            </div>
            <div class="card-body">
                <div class="rich-text-content">
                    {!! $kegiatan->deskripsilatarbelakang ?? '<em class="text-muted">' . __('global.no_results') . '</em>' !!}
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <h4 class="card-title font-weight-bold text-dark m-0">
                    <i class="fas fa-bullseye mr-2 text-danger"></i> 2. {{ __('cruds.kegiatan.description.tujuan') }}
                </h4>
            </div>
            <div class="card-body">
                <div class="rich-text-content">
                    {!! $kegiatan->deskripsitujuan ?? '<em class="text-muted">' . __('global.no_results') . '</em>' !!}
                </div>
            </div>
        </div>

        <!-- Pelaksanaan & Capaian -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <h4 class="card-title font-weight-bold text-dark m-0">
                    <i class="fas fa-list-alt mr-2 text-warning"></i> 3. {{ __('btor.activity_details_results') }}
                </h4>
            </div>
            <div class="card-body">
                {{-- Basic Table for detail --}}
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <td width="30%" class="text-muted"><strong><i class="far fa-clock mr-1"></i> {{ __('btor.execution_time') }}</strong></td>
                                <td width="2%">:</td>
                                <td>
                                    @if($kegiatan->tanggalmulai)
                                        <span class="font-weight-medium text-dark">
                                            {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale(app()->getLocale())->isoFormat('dddd, D MMMM Y') }}
                                        </span>
                                        @if($kegiatan->tanggalselesai && $kegiatan->tanggalmulai != $kegiatan->tanggalselesai)
                                            <span class="mx-1 text-muted">-</span>
                                            <span class="font-weight-medium text-dark">
                                                {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale(app()->getLocale())->isoFormat('dddd, D MMMM Y') }}
                                            </span>
                                        @endif
                                        <span class="ml-2 badge badge-light border text-muted font-weight-normal">{{ $durationInDays ?? 0 }} Hari</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong><i class="fas fa-map-marker-alt mr-1"></i> {{ __('btor.lokasi_kegiatan') }}</strong></td>
                                <td>:</td>
                                <td>
                                    @forelse($kegiatan->lokasi as $lok)
                                        <div class="mb-1 text-dark">
                                            {{ $lok->lokasi }}, {{ $lok->desa?->nama }}, {{ $lok->desa?->kecamatan?->nama }}
                                        </div>
                                    @empty
                                        <span class="text-muted">-</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>
                                    <i class="material-symbols-outlined mr-1 text-sm">partner_exchange</i> {{ __('btor.mitra_kegiatan') }}</strong></td>
                                <td>:</td>
                                <td>
                                    @forelse($kegiatan->mitra as $mitra)
                                        <span class="badge badge-light border mr-1 text-dark">
                                            {{ $mitra->nama }}
                                        </span>
                                    @empty
                                        <span class="text-muted">-</span>
                                    @endforelse
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Disaggregated Participant Table (BTOR Style) --}}
                <div class="mb-4">
                    <h5 class="font-weight-bold mb-3 text-dark border-bottom pb-2">
                        <i class="fas fa-users-cog mr-2 text-secondary"></i> 
                        {{ __('cruds.kegiatan.peserta.label') }} (Disaggregated)
                    </h5>
                    
                    <h6 class="text-sm font-weight-bold mt-2">{{ __('btor.age_based_participants') }}</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered table-hover bg-white mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="25%" class="align-middle">{{ __('cruds.kegiatan.peserta.peserta') }}</th>
                                    <th class="text-center align-middle" width="18%">{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                                    <th class="text-center align-middle" width="18%">{{ __('cruds.kegiatan.peserta.pria') }}</th>
                                    <th class="text-center align-middle" width="18%">Lainnya</th>
                                    <th class="text-center align-middle" width="21%">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ __('btor.adult_range') }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasaperempuan ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasalakilaki ?? 0) }}</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center font-weight-bold">{{ number_format($kegiatan->penerimamanfaatdewasatotal ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('btor.elderly_range') }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansiaperempuan ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansialakilaki ?? 0) }}</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center font-weight-bold">{{ number_format($kegiatan->penerimamanfaatlansiatotal ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('btor.youth_range') }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajaperempuan ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajalakilaki ?? 0) }}</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center font-weight-bold">{{ number_format($kegiatan->penerimamanfaatremajatotal ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('btor.child_range') }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanakperempuan ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanaklakilaki ?? 0) }}</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center font-weight-bold">{{ number_format($kegiatan->penerimamanfaatanaktotal ?? 0) }}</td>
                                </tr>
                                <tr class="table-primary">
                                    <td><strong>{{ __('btor.total_age') }}</strong></td>
                                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                                    <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                                    <td class="text-center"><strong>0</strong></td>
                                    <td class="text-center"><strong class="text-primary">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="text-sm font-weight-bold mt-2">{{ __('btor.special_groups') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover bg-white mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="25%" class="align-middle">{{ __('btor.penerima_manfaat') }}</th>
                                    <th class="text-center align-middle" width="18%">{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                                    <th class="text-center align-middle" width="18%">{{ __('cruds.kegiatan.peserta.pria') }}</th>
                                    <th class="text-center align-middle" width="18%">{{ __('btor.lainnya') }}</th>
                                    <th class="text-center align-middle" width="21%">{{ __('btor.sub_total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ __('cruds.kegiatan.peserta.disabilitas') }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitasperempuan ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0) }}</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center font-weight-bold">{{ number_format($kegiatan->penerimamanfaatdisabilitastotal ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('btor.non_disabilitas') }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0) }}</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center font-weight-bold">{{ number_format($kegiatan->penerimamanfaatnondisabilitastotal ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('cruds.kegiatan.peserta.marjinal_lain') }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinalperempuan ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinallakilaki ?? 0) }}</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center font-weight-bold">{{ number_format($kegiatan->penerimamanfaatmarjinaltotal ?? 0) }}</td>
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
                </div>

                {{-- Dynamic Content (Adopted from BTOR) --}}
                <div class="mt-4">
                    @include('tr.btor.partials.hasil-dinamis')
                </div>
            </div>
        </div>

        <!-- Hasil & Evaluasi -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <h4 class="card-title font-weight-bold text-dark m-0">
                    <i class="fas fa-file-signature mr-2 text-success"></i> 4. {{ __('cruds.kegiatan.description.deskripsikeluaran') }}
                </h4>
            </div>
            <div class="card-body">
                <div class="rich-text-content">
                    {!! $kegiatan->deskripsikeluaran ?? '<em class="text-muted">' . __('global.no_results') . '</em>' !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title font-weight-bold text-dark m-0">
                            <i class="fas fa-user-edit mr-2 text-secondary"></i> 5. {{ __('cruds.kegiatan.hasil.catatan_penulis') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="small text-dark">
                            {!! $kegiatan->catatan_penulis ?? '<em class="text-muted">-</em>' !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title font-weight-bold text-dark m-0">
                            <i class="fas fa-sync-alt mr-2 text-info"></i> 6. {{ __('cruds.kegiatan.hasil.indikasi_perubahan') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="small text-dark">
                            {!! $kegiatan->indikasi_perubahan ?? '<em class="text-muted">-</em>' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>

@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @include('tr.kegiatan.custom.css')
    <style>
        .bg-white-10 { background-color: rgba(255, 255, 255, 0.1); }
        .bg-white-20 { background-color: rgba(255, 255, 255, 0.2); }
        .border-white-20 { border-color: rgba(255, 255, 255, 0.2) !important; }
        .border-white-50 { border-color: rgba(255, 255, 255, 0.5) !important; }
        .text-pink { color: #e83e8c; }
        .border-top-thick { border-top: 2px solid #dee2e6 !important; }
        .rich-text-content img { max-width: 100%; height: auto; border-radius: 0.25rem; }
        .rich-text-content table { width: 100% !important; margin-bottom: 1rem; color: #212529; border: 1px solid #dee2e6; }
        .rich-text-content table th, .rich-text-content table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }
        .rich-text-content table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; }
        .sticky-top { transition: top 0.3s ease; }
    </style>
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
            const title = isDocument ? '{{ __('btor.dokumen_pendukung') }}' : '{{ __('btor.media_pendukung') }}';
            const accept = isDocument ? '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt' : '.jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.mp3,.wav';
            const placeholder = isDocument ? '{{ __('btor.dokumen') }} Name' : 'Media Name';

            Swal.fire({
                title: title,
                html: `
                    <input type="file" id="documentFile" class="form-control mb-3" accept="${accept}">
                    <input type="text" id="documentName" class="form-control" placeholder="${placeholder}">
                `,
                showCancelButton: true,
                confirmButtonText: '{{ __('global.submit') }}',
                cancelButtonText: '{{ __('global.cancel') }}',
                preConfirm: () => {
                    const file = document.getElementById('documentFile').files[0];
                    const name = document.getElementById('documentName').value;

                    if (!file) {
                        Swal.showValidationMessage('{{ __('validation.required', ['attribute' => 'file']) }}');
                        return false;
                    }

                    if (!name) {
                        Swal.showValidationMessage(isDocument ? '{{ __('validation.required', ['attribute' => 'document name']) }}' : '{{ __('validation.required', ['attribute' => 'media name']) }}');
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
                            const successMessage = isDocument ? '{{ __('global.create_success') }}' : '{{ __('global.create_success') }}';
                            Swal.fire('{{ __('global.success') }}', successMessage, 'success')
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            Swal.fire('{{ __('global.error') }}', data.message || '{{ __('global.error') }}', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('{{ __('global.error') }}', '{{ __('global.error') }}', 'error');
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
                title: '{{ __('global.areYouSure') }}',
                text: "{{ __('global.areYouSure') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('global.yes') }}, {{ __('global.delete') }} it!',
                cancelButtonText: '{{ __('global.cancel') }}'
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
                            Swal.fire('{{ __('global.response.deleted') }}!', '{{ __('global.delete_success') }}', 'success');
                            // Reload the page to refresh the file list
                            location.reload();
                        } else {
                            Swal.fire('{{ __('global.error') }}!', data.message || '{{ __('global.delete_failed') }}', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('{{ __('global.error') }}!', '{{ __('global.delete_failed') }}', 'error');
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
            
            // Adjust sticky top if there's an admin-lte navbar
            const navbar = document.querySelector('.main-header');
            if (navbar) {
                const navbarHeight = navbar.offsetHeight;
                const stickyEl = document.querySelector('.sticky-top');
                if(stickyEl) {
                    stickyEl.style.top = (navbarHeight + 15) + 'px';
                }
            }
        });

        // Function for Document and Media Preview (Adopted from BTOR)
        function previewFileFromData(element) {
            const url = element.getAttribute('data-url');
            const mimeType = element.getAttribute('data-mime');
            const name = element.getAttribute('data-name') || '{{ __('global.view') }}';

            // Sanitize name for HTML rendering to prevent XSS
            const safeName = $('<div/>').text(name).html();

            // Custom Download Button for Swal Footer
            const downloadBtnHtml = `<a href="${url}" download class="btn btn-success mt-3"><i class="fas fa-download mr-1"></i> {{ __('btor.download_file') }}</a>`;

            if (mimeType.startsWith('image/')) {
                Swal.fire({
                    title: safeName,
                    html: `<img src="${url}" class="img-fluid rounded" style="max-width: 100%; height: auto;">` + downloadBtnHtml,
                    width: '80%',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            } else if (mimeType === 'application/pdf') {
                event.preventDefault();
                Swal.fire({
                    title: safeName,
                    html: `<iframe src="${url}" style="width: 100%; height: 65vh; border: none; border-radius: 4px;"></iframe>` + downloadBtnHtml,
                    width: '80%',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            } else if (mimeType.includes('word') || mimeType.includes('powerpoint') || mimeType.includes('excel') || mimeType.includes('officedocument') || mimeType.includes('spreadsheet') || mimeType.includes('presentation')) {
                event.preventDefault();
                // Menggunakan Microsoft Office Web Viewer (Syarat: URL file harus bisa diakses publik/online)
                const officeUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(url)}`;
                
                const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
                let contentHtml = '';

                if (isLocalhost) {
                    contentHtml = `
                        <div class="alert alert-warning text-left">
                            <i class="fas fa-exclamation-triangle mr-1"></i> 
                            <strong>{{ __('btor.preview_not_available_localhost') }}</strong><br>
                            {{ __('btor.preview_online_requirement') }}
                        </div>
                        <div class="text-center py-5 bg-light rounded border">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i><br>
                            <h5 class="text-muted">{{ __('btor.file_label') }}: ${safeName}</h5>
                        </div>
                    `;
                } else {
                    contentHtml = `
                        <div class="alert alert-info text-left small mb-2 py-2">
                            <i class="fas fa-info-circle"></i> {{ __('btor.preview_failed_notice') }}
                        </div>
                        <iframe src="${officeUrl}" style="width: 100%; height: 60vh; border: 1px solid #dee2e6; border-radius: 4px;"></iframe>
                    `;
                }

                Swal.fire({
                    title: safeName,
                    html: contentHtml + downloadBtnHtml,
                    width: '80%',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            } else {
                // For other file types (zip, rar, dll), open in new tab (triggers download automatically)
                window.open(url, '_blank');
            }
        }
    </script>
@endpush