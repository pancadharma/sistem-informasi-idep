@extends('layouts.app')
@section('subtitle', __('cruds.kegiatan.edit') . ' ' . $kegiatan->activity->nama)
@section('content_header_title')
    <strong>
        {{ __('cruds.kegiatan.edit') . ' ' . $kegiatan->activity->nama ?? '-' }}</strong>
@endsection

@section('sub_breadcumb')
    <a href="{{ route('kegiatan.index') }}" title="{{ __('cruds.kegiatan.list') }}"> {{ __('cruds.kegiatan.list') }}</a>
@endsection

@section('sub_sub_breadcumb')
    / <span title="Current Page {{ __('cruds.kegiatan.edit') }}">{{ __('cruds.kegiatan.edit') }}</span>
@endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <form id="createKegiatan" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off"
        action="{{ route('kegiatan.update', [$kegiatan->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header border-bottom-0 card-header p-0 pt-1 navigasi">
                        <button type="button" class="btn btn-danger float-right mr-2 mt-1"
                            id="simpan_kegiatan">{{ __('global.save') }}</button>
                        <ul class="nav nav-tabs border-bottom-1 border-primary kegiatan-border pt-2"
                            id="details-kegiatan-tab" role="tablist">
                            <button type="button" class="btn btn-tool btn-small" data-card-widget="collapse"
                                title="Minimize">
                                <i class="bi bi-arrows-collapse"></i>
                            </button>
                            <li class="nav-item">
                                <a class="nav-link active" id="basic-tab" data-toggle="pill" href="#tab-basic"
                                    role="tab" aria-controls="tab-basic" aria-selected="true">
                                    {{ __('cruds.kegiatan.tabs.basic') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="description-nav-tab" data-toggle="pill" href="#description-tab"
                                    role="tab" aria-controls="description-tab" aria-selected="false">
                                    {{ __('cruds.kegiatan.tabs.description') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="hasil-nav-tab" data-toggle="pill" href="#tab-hasil" role="tab"
                                    aria-controls="tab-hasil" aria-selected="false">
                                    {{ __('cruds.kegiatan.tabs.hasil') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-files" data-toggle="pill" href="#tab-file" role="tab"
                                    aria-controls="tab-file" aria-selected="false">
                                    {{ __('cruds.kegiatan.tabs.file') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-report-tab" data-toggle="pill" href="#tab-penulis"
                                    role="tab" aria-controls="tab-penulis" aria-selected="false">
                                    {{ __('cruds.kegiatan.tabs.penulis') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="details-kegiatan-tabContent">
                            {{-- Basic Information --}}
                            <div class="tab-pane fade show active" id="tab-basic" role="tabpanel"
                                aria-labelledby="basic-tab">
                                <div class="form-group row">
                                    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
                                        <label for="kode_program"
                                            class="input-group col-form-label">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
                                        <!-- id program -->
                                        <input type="hidden" name="program_id" id="program_id"
                                            value="{{ $kegiatan->programoutcomeoutputactivity->program_outcome_output->program_outcome->program->id ?? '' }}">
                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ auth()->user()->id ?? '' }}"
                                            title="{{ auth()->user()->nama ?? '' }}">
                                        <!-- kode program -->
                                        <input type="text" class="form-control" id="kode_program"
                                            placeholder="{{ __('cruds.kegiatan.basic.program_select_kode') }}"
                                            name="kode_program" readonly
                                            value="{{ $kegiatan->programoutcomeoutputactivity->program_outcome_output->program_outcome->program->kode ?? '' }}">
                                    </div>
                                    <!-- nama program-->
                                    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
                                        <label for="nama_program" class="input-group col-form-label">
                                            {{ __('cruds.kegiatan.basic.program_nama') }}
                                        </label>
                                        <input type="text" class="form-control" id="nama_program"
                                            placeholder="{{ __('cruds.kegiatan.basic.program_nama') }}"
                                            name="nama_program" readonly
                                            value="{{ $kegiatan->programoutcomeoutputactivity->program_outcome_output->program_outcome->program->nama ?? '' }}">
                                    </div>
                                </div>
                                {{-- Kode Kegiatan & Nama Kegiatan --}}
                                <div class="form-group row">
                                    <!-- kode kegiatan-->
                                    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
                                        <label for="kode_kegiatan" class="input-group col-form-label">
                                            {{ __('cruds.kegiatan.basic.kode') }}
                                        </label>
                                        <input type="hidden" class="form-control" id="programoutcomeoutputactivity_id"
                                            placeholder="{{ __('cruds.kegiatan.basic.kode') }}"
                                            name="programoutcomeoutputactivity_id"
                                            value="{{ $kegiatan->programoutcomeoutputactivity_id ?? '' }}">
                                        <input type="text" class="form-control" id="kode_kegiatan"
                                            placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kode_kegiatan"
                                            data-toggle="modal" data-target="#ModalDaftarProgramActivity" readonly
                                            value="{{ $kegiatan->activity->kode ?? '' }}">
                                    </div>
                                    <!-- nama kegiatan-->
                                    <div
                                        class="col-sm-12
                                        col-md-12 col-lg-9 self-center order-2 order-md-2">
                                        <label for="nama_kegiatan" class="input-group col-form-label">
                                            {{ __('cruds.kegiatan.basic.nama') }}
                                        </label>
                                        <input type="text" class="form-control" id="nama_kegiatan"
                                            placeholder="{{ __('cruds.kegiatan.basic.nama') }}" name="nama_kegiatan"
                                            readonly value="{{ $kegiatan->activity->nama ?? '' }}">
                                    </div>
                                </div>

                                <!-- jenis kegiatan-->
                                <div class="form-group row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                                        <label for="jeniskegiatan_id" class="input-group col-form-label">
                                            <strong>{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}</strong>
                                        </label>
                                        <div class="select2-purple">
                                            <select name="jeniskegiatan_id" id="jeniskegiatan_id"
                                                class="form-control select2">
                                                <option value="">{{ __('global.pleaseSelect') }}</option>
                                                @foreach ($jenisKegiatanList as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('jeniskegiatan_id', $kegiatan->jeniskegiatan_id ?? '') == $item['id'] ? 'selected' : '' }}>
                                                        {{ $item->nama }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <!-- sektor kegiatan-->
                                    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1 mb-1">
                                        <label for="sektor_id" class="input-group col-form-label">
                                            <strong>{{ __('cruds.kegiatan.basic.sektor_kegiatan') }}</strong>
                                        </label>
                                        <div class="select2-purple">
                                            <select name="sektor_id[]" id="sektor_id" class="form-control select2"
                                                multiple data-api-url="{{ route('api.kegiatan.sektor_kegiatan') }}">
                                                @foreach ($sektorList as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ collect(old('sektor_id', $kegiatan->sektor->pluck('id') ?? []))->contains($item->id) ? 'selected' : '' }}>
                                                        {{ $item->nama }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <!-- fase pelaporan-->
                                    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
                                        <label for="fasepelaporan" class="input-group col-form-label">
                                            <strong>{{ __('cruds.kegiatan.basic.fase_pelaporan') }} </strong>
                                            <i class="bi bi-question-circle" data-toggle="tooltip"
                                                title="{{ __('cruds.kegiatan.basic.tooltip.fase_pelaporan') }}"></i>
                                        </label>
                                        <div class="select2-purple">
                                            <select name="fasepelaporan" id="fasepelaporan" class="form-control select2">
                                                <option value="" disabled>
                                                    {{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.basic.fase_pelaporan') }}
                                                </option>
                                                @php
                                                    $currentFase = old(
                                                        'fasepelaporan',
                                                        $kegiatan->fasepelaporan ?? null,
                                                    );
                                                @endphp
                                                @for ($i = 1; $i <= 99; $i++)
                                                    <option value="{{ $i }}"
                                                        {{ $currentFase == $i ? 'selected' : '' }}
                                                        {{ $currentFase && $i < $currentFase ? 'disabled' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>

                                        </div>
                                    </div>
                                    <!-- durasi kegiatan-->
                                    <!-- tgl mulai-->
                                    <div class="col-sm-6 col-md-6 col-lg-3 self-center order-1 order-md-1">
                                        <label for="tanggalmulai" class="input-group col-form-label">
                                            {{ __('cruds.kegiatan.basic.tanggalmulai') }}
                                        </label>
                                        <input type="date" class="form-control" id="tanggalmulai" name="tanggalmulai"
                                            value="{{ old('tanggalmulai', $kegiatan->tanggalmulai) }}">
                                    </div>
                                    <!-- tgl selesai-->
                                    <div class="col-sm-6 col-md-6 col-lg-3 self-center order-2 order-md-2">
                                        <label for="tanggalselesai" class="input-group col-form-label">
                                            {{ __('cruds.kegiatan.basic.tanggalselesai') }}
                                        </label>
                                        <input type="date" class="form-control" id="tanggalselesai"
                                            name="tanggalselesai"
                                            value="{{ old('tanggalselesai', $kegiatan->tanggalselesai) }}">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-3 self-center order-2 order-md-2">
                                        <label for="durasi" class="input-group col-form-label">
                                            {{ __('Durasi') }}
                                        </label>
                                        <input type="text" class="form-control" id="durasi" name="durasi"
                                            readonly>
                                    </div>
                                </div>

                                <!-- nama mitra-->
                                <div class="form-group row">
                                    <div class="col-sm-12 col-md-9 col-lg-9 self-center order-2 order-md-2">
                                        <label for="mitra_id"
                                            class="input-group col-form-label">{{ __('cruds.kegiatan.basic.nama_mitra') }}</label>
                                        <div class="select2-purple">
                                            <select name="mitra_id[]" id="mitra_id" class="form-control select2"
                                                multiple data-api-url="{{ route('api.kegiatan.mitra') }}">
                                                @foreach ($kegiatan->mitra as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ collect(old('mitra_id', $kegiatan->mitra->pluck('id') ?? []))->contains($item->id) ? 'selected' : '' }}>
                                                        {{ $item->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- status kegiatan-->
                                    <div class="col-sm-12 col-md-3 col-lg-3 self-center order-1 order-md-1">
                                        <label for="status" class="input-group col-form-label">
                                            <strong>{{ __('cruds.status.title') }}</strong>
                                        </label>
                                        <div class="select2-purple">
                                            <select name="status" id="status" class="form-control select2">
                                                @foreach (['draft', 'ongoing', 'completed', 'cancelled'] as $status)
                                                    <option value="{{ $status }}"
                                                        {{ old('status', $kegiatan->status ?? '') == $status ? 'selected' : '' }}>
                                                        {{ ucfirst($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--Pilih Provinsi Data-->
                                <div class="form-group row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                                        <label for="provinsi_id"
                                            class="input-group col-form-label">{{ __('cruds.provinsi.title') }}</label>
                                        <input type="hidden" name="provinsiID"
                                            value="{{ $kegiatan->lokasi['0']->kecamatan->kabupaten->provinsi->id ?? ' ?>' }}">
                                        <select name="provinsi_id" id="provinsi_id" class="form-control select2"
                                            data-api-url="{{ route('api.kegiatan.provinsi') }}"
                                            data-placeholder="{{ __('global.pleaseSelect') . ' ' . __('cruds.provinsi.title') }}"
                                            disabled>

                                            @foreach ($ProvinsiList as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('provinsi_id', $kegiatan->lokasi['0']->kecamatan->kabupaten->provinsi->id ?? '') == $item['id'] ? 'selected' : '' }}>
                                                    {{ $item->nama }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <!--Pilih Kabupaten Data-->
                                    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                                        <label for="kabupaten_id" class="input-group col-form-label">
                                            {{ __('cruds.kabupaten.title') }}</label>
                                        <select name="kabupaten_id" id="kabupaten_id" class="form-control select2"
                                            data-api-url="{{ route('api.kegiatan.kabupaten') }}"
                                            data-placeholder="{{ __('global.pleaseSelect') . ' ' . __('cruds.kabupaten.title') }}">
                                            @foreach ($kabupatenList as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('kabupaten_id', $kegiatan->lokasi['0']->kecamatan->kabupaten->id ?? '') == $item['id'] ? 'selected' : '' }}>
                                                    {{ $item->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--button tambah lokasi dinamis-->
                                <div class="card-info pt-2">
                                    <div class="card-header pl-1">
                                        <div class="col-sm-12 col-md-12 col-lg-4 self-center order-1 order-md-1">
                                            <button type="button" id="btn-lokasi-kegiatan"
                                                class="btn btn-warning">{{ __('cruds.kegiatan.basic.tambah_lokasi') }}</button>
                                        </div>
                                    </div>
                                    <div class="card-body pl-0 pt-1 pb-0 pr-1 mb-0">
                                        <div class="form-group row lokasi-kegiatan mb-0">
                                            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-1 order-md-1">
                                                <label class="input-group col-form-label">
                                                    {{ __('cruds.kecamatan.title') }}
                                                    <i class="bi bi-geo-alt-fill" data-toggle="tooltip"
                                                        title="{{ __('cruds.kegiatan.basic.tooltip.lokasi') }}"></i>
                                                </label>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-1 order-md-1">
                                                <label class="input-group col-form-label">
                                                    {{ __('cruds.desa.title') }}
                                                    <i class="bi bi-geo-alt-fill" data-toggle="tooltip"
                                                        title="{{ __('cruds.desa.title') }}"></i>
                                                </label>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-1 order-md-1">
                                                <label class="input-group col-form-label">
                                                    {{ __('cruds.kegiatan.basic.lokasi') }}
                                                    <i class="bi bi-geo-alt-fill" data-toggle="tooltip"
                                                        title="{{ __('cruds.kegiatan.basic.tooltip.lokasi') }}"></i>
                                                </label>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-2 order-md-2">
                                                <label class="input-group col-form-label">
                                                    {{ __('cruds.kegiatan.basic.lat') }}
                                                    <i class="bi bi-pin-map-fill" data-toggle="tooltip"
                                                        title="{{ __('cruds.kegiatan.basic.tooltip.long_lat') }}"></i>
                                                </label>
                                            </div>
                                            <div class="col-sm-10 col-md-10 col-lg-2 self-center order-3 order-md-3">
                                                <label class="input-group col-form-label">
                                                    {{ __('cruds.kegiatan.basic.long') }}
                                                    <i class="bi bi-geo" data-toggle="tooltip"
                                                        title="{{ __('cruds.kegiatan.basic.tooltip.long_lat') }}"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Input Kelurahan dan Kecamatan-->
                                    <div class="list-lokasi-kegiatan">
                                        @forelse ($kegiatan->lokasi as $item)
                                            <div class="form-group row lokasi-kegiatan" data-unique-id="${uniqueId}">
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                                                    <select name="kecamatan_id[]" id="kecamatan_id"
                                                        class="form-control select2"
                                                        data-api-url="{{ route('api.kegiatan.kecamatan') }}"
                                                        data-placeholder="{{ __('global.pleaseSelect') . '' . __('cruds.kecamatan.title') }}">
                                                        @foreach ($kecamatanList as $kecamatan)
                                                            <option value="{{ $kecamatan->id }}"
                                                                {{ old('kecamatan_id', $item->kecamatan_id ?? '') == $kecamatan['id'] ? 'selected' : '' }}>
                                                                {{ $kecamatan->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                                                    <select name="desa_id[]" id="desa_id" class="form-control select2"
                                                        data-api-url="{{ route('api.kegiatan.desa') }}"
                                                        data-placeholder="{{ __('global.pleaseSelect') . '' . __('cruds.desa.title') }}">
                                                        @foreach ($desaList as $desa)
                                                            <option value="{{ $desa->id }}"
                                                                {{ old('desa_id', $item->desa_id ?? '') == $desa['id'] ? 'selected' : '' }}>
                                                                {{ $desa->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-5">
                                                    <input type="text"
                                                        placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}"
                                                        class="form-control lokasi-input" id="lokasi" name="lokasi[]"
                                                        value="{{ old('lokasi', $item->lokasi ?? '') }}">
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-6">
                                                    <input type="text" class="form-control lat-input"
                                                        id="lat-${uniqueId}" name="lat[]"
                                                        placeholder="{{ __('cruds.kegiatan.basic.lat') }}"
                                                        value="{{ old('lat', $item->lat ?? '') }}">
                                                </div>

                                                <div
                                                    class="col-sm-12 col-md-12 col-lg-2 self-center order-7 d-flex align-items-center">
                                                    <input type="text" class="form-control lang-input flex-grow-1"
                                                        id="long-${uniqueId}" name="long[]"
                                                        placeholder="{{ __('cruds.kegiatan.basic.long') }}"
                                                        value="{{ old('lat', $item->long ?? '') }}">
                                                    <button type="button"
                                                        class="btn btn-danger remove-lokasi-row btn-sm ml-1">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            {{-- <div class="form-group row lokasi-kegiatan mb-0">
                                                <div class="col-sm-3 col-md-3 col-lg-3 self-center order-1 order-md-1">
                                                    <select name="kecamatan_id[]" id="kecamatan_id"
                                                        class="form-control select2"
                                                        data-api-url="{{ route('api.kegiatan.kecamatan') }}"
                                                        data-placeholder="{{ __('global.pleaseSelect') . '' . __('cruds.kecamatan.title') }}">
                                                        @foreach ($kecamatanList as $kecamatan)
                                                            <option value="{{ $kecamatan->id }}"
                                                                {{ old('kecamatan_id', $item->kecamatan_id ?? '') == $kecamatan['id'] ? 'selected' : '' }}>
                                                                {{ $kecamatan->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 col-md-3 col-lg-3 self-center order-1 order-md-1">
                                                    <select name="desa_id[]" id="desa_id" class="form-control select2"
                                                        data-api-url="{{ route('api.kegiatan.desa') }}"
                                                        data-placeholder="{{ __('global.pleaseSelect') . '' . __('cruds.desa.title') }}">
                                                        @foreach ($desaList as $desa)
                                                            <option value="{{ $desa->id }}"
                                                                {{ old('desa_id', $item->desa_id ?? '') == $desa['id'] ? 'selected' : '' }}>
                                                                {{ $desa->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 col-md-3 col-lg-3 self-center order-1 order-md-1">
                                                    <input type="text" class="form-control" id="lokasi"
                                                        name="lokasi[]" value="{{ old('lokasi', $item->lokasi ?? '') }}">
                                                </div>
                                                <div class="col-sm-3 col-md-3 col-lg-3 self-center order-2 order-md-2">
                                                    <input type="text" class="form-control" id="lat"
                                                        name="lat[]" value="{{ old('lat', $item->lat ?? '') }}">
                                                </div> --}}
                                        @empty

                                        @endforelse
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                                        <label class="input-group col-form-label">
                                            {{ __('Get Coordinate') }}
                                            <i class="bi bi-map-fill"></i>
                                        </label>
                                        <div id="map" class="form-control col-form-label"></div>
                                    </div>
                                </div>

                            </div>
                            <!-- deskripsi kegiatan -->
                            <div class="tab-pane fade" id="description-tab" role="tabpanel"
                                aria-labelledby="custom-tabs-four-profile-tab">
                                <div class="form-group row">
                                    <div class="col-sm col-md col-lg self-center">
                                        <label for="deskripsilatarbelakang" class="input-group">
                                            {{ __('cruds.kegiatan.description.latar_belakang') }}
                                            <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                                                title="{{ __('cruds.kegiatan.description.latar_belakang_helper') }}"></i>
                                        </label>
                                        <textarea name="deskripsilatarbelakang" id="deskripsilatarbelakang"
                                            placeholder=" {{ __('cruds.kegiatan.description.latar_belakang_helper') }}" class="form-control summernote"
                                            rows="2"></textarea>
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
                                        <textarea name="deskripsitujuan" id="deskripsitujuan"
                                            placeholder=" {{ __('cruds.kegiatan.description.tujuan_helper') }}" class="form-control summernote"
                                            rows="2"></textarea>
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

                                        <textarea name="deskripsikeluaran" id="deskripsikeluaran"
                                            placeholder=" {{ __('cruds.kegiatan.description.keluaran_helper') }}" class="form-control summernote"
                                            rows="2"></textarea>
                                    </div>
                                </div>
                                <!-- Peserta yang terlibat -->
                                <div class="form-group row mb-0">
                                    <div class="col-sm col-md col-lg self-center">
                                        <label class="mb-0 self-center input-group">
                                            {{ __('cruds.kegiatan.peserta.label') }}
                                            <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                                                title="{{ __('cruds.kegiatan.peserta.helper') }}"></i>
                                        </label>
                                    </div>
                                </div>

                                <!-- jumlah peserta kegiatan -->
                                <div class="form-group row">
                                    <div class="col-sm col-md col-lg self-center">
                                        <div class="card-body table-responsive p-0">
                                            <table id="peserta_kegiatan_summary"
                                                class="table table-sm table-borderless table-info mb-0 table-custom"
                                                width="100%">
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
                                                            <label
                                                                class="text-sm">{{ __('cruds.kegiatan.peserta.dewasa') }}</label>
                                                        </td>
                                                        <td class="pl-1">
                                                            <input type="number" id="penerimamanfaatdewasaperempuan"
                                                                name="penerimamanfaatdewasaperempuan"
                                                                class="calculate form-control-border border-width-2 form-control form-control-sm"
                                                                placeholder="0">
                                                        </td>
                                                        <td class="pl-1">
                                                            <input type="number" id="penerimamanfaatdewasalakilaki"
                                                                name="penerimamanfaatdewasalakilaki"
                                                                class="calculate form-control-border border-width-2 form-control form-control-sm"
                                                                placeholder="0">
                                                        </td>
                                                        <td class="pl-1 pr-1">
                                                            <input type="number" readonly id="penerimamanfaatdewasatotal"
                                                                name="penerimamanfaatdewasatotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm">
                                                        </td>
                                                    </tr>
                                                    <!--lansia row-->
                                                    <tr>
                                                        <td class="pl-1">
                                                            <label
                                                                class="text-sm">{{ __('cruds.kegiatan.peserta.lansia') }}</label>
                                                        </td>
                                                        <td class="pl-1">
                                                            <input type="number" id="penerimamanfaatlansiaperempuan"
                                                                name="penerimamanfaatlansiaperempuan"
                                                                class="calculate form-control-border border-width-2 form-control form-control-sm"
                                                                placeholder="0">
                                                        </td>
                                                        <td class="pl-1">
                                                            <input type="number" id="penerimamanfaatlansialakilaki"
                                                                name="penerimamanfaatlansialakilaki"
                                                                class="calculate form-control-border border-width-2 form-control form-control-sm"
                                                                placeholder="0">
                                                        </td>
                                                        <td class="pl-1 pr-1">
                                                            <input type="number" readonly id="penerimamanfaatlansiatotal"
                                                                name="penerimamanfaatlansiatotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm">
                                                        </td>
                                                    </tr>
                                                    <!--remaja row-->
                                                    <tr>
                                                        <td class="pl-1">
                                                            <label
                                                                class="text-sm">{{ __('cruds.kegiatan.peserta.remaja') }}</label>
                                                        </td>
                                                        <td class="pl-1">
                                                            <input type="number" id="penerimamanfaatremajaperempuan"
                                                                name="penerimamanfaatremajaperempuan"
                                                                class="calculate form-control-border border-width-2 form-control form-control-sm"
                                                                placeholder="0">
                                                        </td>
                                                        <td class="pl-1">
                                                            <input type="number" id="penerimamanfaatremajalakilaki"
                                                                name="penerimamanfaatremajalakilaki"
                                                                class="calculate form-control-border border-width-2 form-control form-control-sm"
                                                                placeholder="0">
                                                        </td>
                                                        <td class="pl-1 pr-1">
                                                            <input type="number" readonly id="penerimamanfaatremajatotal"
                                                                name="penerimamanfaatremajatotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm">
                                                        </td>
                                                    </tr>
                                                    <!--anak-anak row-->
                                                    <tr>
                                                        <td class="pl-1">
                                                            <label
                                                                class="text-sm">{{ __('cruds.kegiatan.peserta.anak') }}</label>
                                                        </td>
                                                        <td class="pl-1">
                                                            <input type="number" id="penerimamanfaatanakperempuan"
                                                                name="penerimamanfaatanakperempuan"
                                                                class="calculate form-control-border border-width-2 form-control form-control-sm"
                                                                placeholder="0">
                                                        </td>
                                                        <td class="pl-1">
                                                            <input type="number" id="penerimamanfaatanaklakilaki"
                                                                name="penerimamanfaatanaklakilaki"
                                                                class="calculate form-control-border border-width-2 form-control form-control-sm"
                                                                placeholder="0">
                                                        </td>
                                                        <td class="pl-1 pr-1">
                                                            <input type="number" readonly id="penerimamanfaatanaktotal"
                                                                name="penerimamanfaatanaktotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm">
                                                        </td>
                                                    </tr>
                                                    <tr class="align-middle text-center text-nowrap">
                                                        <th class="pl-1 text-left">
                                                            {{ __('cruds.kegiatan.peserta.total') }}</th>
                                                        <th class="pl-1">
                                                            <input type="number" readonly
                                                                id="penerimamanfaatperempuantotal"
                                                                name="penerimamanfaatperempuantotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm">
                                                        </th>
                                                        <th class="pl-1">
                                                            <input type="number" readonly
                                                                id="penerimamanfaatlakilakitotal"
                                                                name="penerimamanfaatlakilakitotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm">
                                                        </th>
                                                        <th class="pl-1 pr-1">
                                                            <input type="number" readonly id="penerimamanfaattotal"
                                                                name="penerimamanfaattotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm">
                                                        </th>
                                                    </tr>
                                                </tbody>
                                                <tfoot class="pl-1 pr-1">
                                                </tfoot>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-sm col-md col-lg self-center">
                                        <div class="card-body table-responsive p-0">
                                            <table id="penerima_manfaat_difabel"
                                                class="table table-sm table-borderless table-warning mb-0 table-custom"
                                                width="100%">
                                                <thead style="background-color: #6111bd !important">
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
                                                    <!--disabilitas row-->
                                                    <tr>
                                                        <td colspan="1" width="30%" class="pl-1">
                                                            <label
                                                                class="text-sm">{{ __('cruds.kegiatan.peserta.disabilitas') }}</label>
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1">
                                                            <input type="number" id="penerimamanfaatdisabilitasperempuan"
                                                                name="penerimamanfaatdisabilitasperempuan"
                                                                class="form-control-border border-width-2 form-control form-control-sm hitung-difabel"
                                                                placeholder="0">
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1">
                                                            <input type="number" id="penerimamanfaatdisabilitaslakilaki"
                                                                name="penerimamanfaatdisabilitaslakilaki"
                                                                class="form-control-border border-width-2 form-control form-control-sm hitung-difabel"
                                                                placeholder="0">
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1 pr-1">
                                                            <input type="number" id="penerimamanfaatdisabilitastotal"
                                                                name="penerimamanfaatdisabilitastotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm"
                                                                readonly>
                                                        </td>
                                                    </tr>
                                                    <!--non_disabilitas row-->
                                                    <tr>
                                                        <td colspan="1" width="30%" class="pl-1">
                                                            <label
                                                                class="text-sm">{{ __('cruds.kegiatan.peserta.non_disabilitas') }}</label>
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1">
                                                            <input type="number"
                                                                id="penerimamanfaatnondisabilitasperempuan"
                                                                name="penerimamanfaatnondisabilitasperempuan"
                                                                class="form-control-border border-width-2 form-control form-control-sm hitung-difabel"
                                                                placeholder="0">
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1">
                                                            <input type="number"
                                                                id="penerimamanfaatnondisabilitaslakilaki"
                                                                name="penerimamanfaatnondisabilitaslakilaki"
                                                                class="form-control-border border-width-2 form-control form-control-sm hitung-difabel"
                                                                placeholder="0">
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1 pr-1">
                                                            <input type="number" id="penerimamanfaatnondisabilitastotal"
                                                                name="penerimamanfaatnondisabilitastotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm"
                                                                readonly>
                                                        </td>
                                                    </tr>
                                                    <!--marjinal row-->
                                                    <tr>
                                                        <td colspan="1" width="30%" class="pl-1">
                                                            <label
                                                                class="text-sm">{{ __('cruds.kegiatan.peserta.marjinal_lain') }}</label>
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1">
                                                            <input type="number" id="penerimamanfaatmarjinalperempuan"
                                                                name="penerimamanfaatmarjinalperempuan"
                                                                class="form-control-border border-width-2 form-control form-control-sm hitung-difabel"
                                                                placeholder="0">
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1">
                                                            <input type="number" id="penerimamanfaatmarjinallakilaki"
                                                                name="penerimamanfaatmarjinallakilaki"
                                                                class="form-control-border border-width-2 form-control form-control-sm hitung-difabel"
                                                                placeholder="0">
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1 pr-1">
                                                            <input type="number" id="penerimamanfaatmarjinaltotal"
                                                                name="penerimamanfaatmarjinaltotal"
                                                                class="form-control-border border-width-2 form-control form-control-sm"
                                                                readonly>
                                                        </td>
                                                    </tr>
                                                    <!--total beneficiaries difabel-->
                                                    <tr>
                                                        <td colspan="1" width="30%" class="pl-1">
                                                            <label
                                                                class="text-sm">{{ __('cruds.kegiatan.peserta.total') }}</label>
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1">
                                                            <input type="number" id="total_beneficiaries_perempuan"
                                                                name="total_beneficiaries_perempuan"
                                                                class="form-control-border border-width-2 form-control form-control-sm"
                                                                readonly placeholder="0">
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1">
                                                            <input type="number" id="total_beneficiaries_lakilaki"
                                                                name="total_beneficiaries_lakilaki"
                                                                class="form-control-border border-width-2 form-control form-control-sm"
                                                                readonly placeholder="0">
                                                        </td>
                                                        <td colspan="1" width="10%" class="pl-1 pr-1">
                                                            <input type="number" id="beneficiaries_difable_total"
                                                                name="beneficiaries_difable_total"
                                                                class="form-control-border border-width-2 form-control form-control-sm"
                                                                readonly>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- HASIL --}}
                            <div class="tab-pane fade" id="tab-hasil" role="tabpanel" aria-labelledby="tab-hasil">
                                <div id="dynamic-form-container"></div>
                            </div>
                            {{-- File Uplaods Load --}}
                            <div class="tab-pane fade" id="tab-file" role="tabpanel" aria-labelledby="tab-file">
                                {{-- Document Uploads --}}
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="dokumen_pendukung" class="control-label mb-0">
                                                <strong>
                                                    {{ __('cruds.kegiatan.file.upload') }}
                                                </strong>
                                                <span class="text-red">
                                                    {{-- ONLY FOR DOCUMENT FILES ONLY --}}
                                                    (
                                                    {{ __('allowed file: .pdf, .doc, .docx, .xls, .xlsx, .pptx | max: 50 MB') }}
                                                    )
                                                </span>
                                            </label>
                                            <div class="form-group file-loading">
                                                <input id="dokumen_pendukung" name="dokumen_pendukung[]" type="file"
                                                    class="form-control" multiple data-show-upload="false"
                                                    data-show-caption="true">
                                            </div>
                                            <div id="captions-container-docs"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Media Photo/Video Uploads --}}
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="media_pendukung" class="control-label mb-0">
                                                <strong>
                                                    {{ __('cruds.kegiatan.file.upload_media') }}
                                                </strong>
                                                <span class="text-red">
                                                    {{-- ONLY FOR MEDIA FILES ONLY --}}
                                                    ( {{ __('allowed file: .jpg, .png, .jpeg | max: 50 MB') }} )
                                                </span>
                                            </label>
                                            <div class="form-group file-loading">
                                                <input id="media_pendukung" name="media_pendukung[]" type="file"
                                                    class="form-control" multiple data-show-upload="false"
                                                    data-show-caption="true">
                                            </div>
                                            <div id="captions-container-media"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Penulis Laporan Kegiatan --}}
                            <div class="tab-pane fade" id="tab-penulis" role="tabpanel" aria-labelledby="tab-penulis">
                                <!-- Penulis Laporan Kegiatan -->
                                <div class="form-group row tambah_penulis col" id="PenulisContainer">
                                    <button type="button" class="btn btn-success float-right" id="addPenulis">
                                        <i class="bi bi-folder-plus"></i>
                                        {{ __('global.add') . ' ' . __('cruds.kegiatan.penulis.laporan') }}
                                    </button>
                                </div>

                                <div class="form-group row col-md-12" id="list_penulis_edit">
                                    @if (!empty($kegiatan->penulis) && $kegiatan->penulis->isNotEmpty())
                                        @foreach ($kegiatan->penulis as $penulis)
                                            <div class="row penulis-row col-12">
                                                <div class="col-lg-5 form-group mb-0">
                                                    <label for="penulis">{{ __('cruds.kegiatan.penulis.nama') }}</label>
                                                    <div class="select2-orange">
                                                        <select class="form-control select2 penulis-select"
                                                            name="penulis[]" data-selected="{{ $penulis->id }}">
                                                            <option value="{{ $penulis->id }}" selected>
                                                                {{ $penulis->nama }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 form-group d-flex align-items-end">
                                                    <div class="flex-grow-1">
                                                        <label
                                                            for="jabatan">{{ __('cruds.kegiatan.penulis.jabatan') }}</label>
                                                        <div class="select2-orange">
                                                            <select class="form-control select2 jabatan-select"
                                                                name="jabatan[]"
                                                                data-selected="{{ $penulis->pivot->peran_id }}">
                                                                <option value="{{ $penulis->pivot->peran_id }}" selected>
                                                                    {{ $penulis->peran->find($penulis->pivot->peran_id)->nama ?? 'Unknown Role' }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="ml-2">
                                                        <button type="button" class="btn btn-danger remove-penulis-row">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        {{-- <p class="text-muted"></p> --}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        @stack('next-button')
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
    <style>
        .card-header.border-bottom-0.card-header.p-0.pt-1.navigasi {
            position: sticky;
            z-index: 1030;
            top: 0;
        }

        .select2-selection.is-invalid-select2 {
            border-color: #dc3545 !important;
        }

        .select2-selection.is-valid-select2 {
            border-color: #28a745 !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single.is-invalid-select2 {
            border-color: #a7dc35 !important;
        }

        .fixed {
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 2;
            width: 100% !important;
        }

        .content-header h1 {
            font-size: 1.1rem !important;
            margin: 0;
        }

        .note-toolbar {
            background: #00000000 !important;
        }

        .note-editor.note-frame .note-statusbar,
        .note-editor.note-airframe .note-statusbar {
            background-color: #007bff17 !important;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
            border-top: 1px solid #00000000;
        }

        .table-custom th:nth-child(2),
        .table-custom td:nth-child(2),
        .table-custom th:nth-child(3),
        .table-custom td:nth-child(3),
        .table-custom th:nth-child(4),
        .table-custom td:nth-child(4) {
            width: 20%;
        }

        .table-custom th:first-child,
        .table-custom td:first-child {
            width: 40%;
        }
    </style>
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
@section('plugins.Summernote', true)

<script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('/vendor/inputmask/AutoNumeric.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/buffer.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/piexif.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/locales/id.js') }}"></script>

@stack('basic_tab_js')
<script>
    let uniqueId = Date.now();
    var provinsiLayer = null;
    var kabupatenLayer = null;
    var map;

    function calculateDuration() {
        const start = new Date($('#tanggalmulai').val());
        const end = new Date($('#tanggalselesai').val());

        if (!isNaN(start) && !isNaN(end) && end >= start) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 untuk inklusif
            $('#durasi').val(diffDays + ' hari');
        } else {
            $('#durasi').val('');
        }
    }

    $('#tanggalmulai, #tanggalselesai').on('change', calculateDuration);
    $(document).ready(function() {

        $('.select2').select2({
            width: 'resolve'
        });

        // select2 jenis kegiatan
        $('#jeniskegiatan_id').select2({
            placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.basic.jenis_kegiatan') }}',
            ajax: {
                url: '{{ route('api.kegiatan.jenis_kegiatan') }}',
                dataType: 'json',
                processResults: function(data) {
                    var results = [];
                    $.each(data, function(group, options) {
                        var optgroup = {
                            text: group,
                            children: []
                        };
                        $.each(options, function(id, text) {
                            optgroup.children.push({
                                id: id,
                                text: text
                            });
                        });
                        results.push(optgroup);
                    });
                    return {
                        results: results
                    };
                }
            }
        });

        $(`#kabupaten_id`).select2({
            placeholder: '{{ __('cruds.kegiatan.basic.select_kabupaten') }}',
            allowClear: true,
            ajax: {
                url: "{{ route('api.kegiatan.kabupaten') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    const provinsiId = $(`#provinsi_id`).val();
                    return {
                        search: params.term,
                        provinsi_id: provinsiId,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true,
                error: function(jqXHR, textStatus, errorThrown) {
                    const provinsiId = $(`#provinsi_id`).val();
                    let errorMessage = "";
                    if (jqXHR.responseText) {
                        try {
                            const response = JSON.parse(jqXHR.responseText);
                            if (response.message) {
                                errorMessage = response
                                    .message; // Use message from the server if available
                            }
                        } catch (e) {
                            console.warn("Could not parse JSON response:", jqXHR.responseText);
                        }
                    }
                    if (provinsiId === null || provinsiId === undefined || provinsiId === '') {
                        Swal.fire({
                            icon: 'warning' ?? textStatus,
                            title: 'Failed to load Kabupaten data',
                            text: '{{ __('global.pleaseSelect') }} {{ __('cruds.provinsi.title') }}',
                            timer: 1500,
                        })
                        setTimeout(() => {
                            $(`#provinsi_id`).focus();
                        }, 1000);
                        return;
                    } else {
                        // Handle other AJAX errors
                        let errorMessage =
                            '{{ __('Failed to fetch kabupaten data.  Please check your internet connection or try again later.') }}'; // Default, localized message
                        Swal.fire({
                            icon: 'error', // Always use 'error' for AJAX failures
                            title: errorThrown ||
                                'Error', // Use errorThrown if available, otherwise generic 'Error'
                            text: errorMessage,
                            timer: 2500, // Slightly longer timer for general errors
                            showConfirmButton: false // Hide confirm button
                        });

                    }
                }
            }
        }).on('select2:open', function(e) {
            $('.select2-container').css('z-index', 1035);
        }).on('select2:close', function(e) {
            $('.select2-container').css('z-index', 999);
        });

        function initializeSelect2WithDynamicUrl(fieldId) {
            var select2Field = $('#' + fieldId);
            var apiUrl = select2Field.data('api-url');

            select2Field.select2({
                width: '100%',
                placeholder: select2Field.attr('placeholder'),
                allowClear: true,
                ajax: {
                    url: apiUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.nama
                                };
                            }),
                            pagination: {
                                more: data.current_page < data.last_page
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0
            });
        }

        function select2Single(fieldId) {
            var select2Field = $('#' + fieldId);
            var apiUrl = select2Field.data('api-url');

            select2Field.select2({
                width: 'resolve',
                placeholder: select2Field.attr('placeholder'),
                aallowClear: true,
                ajax: {
                    url: apiUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                }
            }).on('select2:open', function(e) {
                $('.select2-container').css('z-index', 1035);
            }).on('select2:close', function(e) {
                $('.select2-container').css('z-index', 999);
            });;
        }

        function addNewLocationInputs(uniqueId) {
            if (!uniqueId) {
                uniqueId = Date.now();
            }
            var newLocationField = `
            <div class="form-group row lokasi-kegiatan" data-unique-id="${uniqueId}">
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                    <select name="kecamatan_id[]" class="form-control dynamic-select2 kecamatan-select" id="kecamatan-${uniqueId}" data-placeholder="Pilih Kecamatan">
                    </select>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                    <select name="kelurahan_id[]" class="form-control dynamic-select2 kelurahan-select" id="kelurahan-${uniqueId}" data-placeholder="Pilih Desa">
                    </select>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-5">
                    <input type="text" class="form-control lokasi-input" id="lokasi-${uniqueId}" name="lokasi[]" placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}">
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-6">
                    <input type="text" class="form-control lat-input" id="lat-${uniqueId}" name="lat[]" placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-7 d-flex align-items-center">
                    <input type="text" class="form-control lang-input flex-grow-1" id="long-${uniqueId}" name="long[]" placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                    <button type="button" class="btn btn-danger remove-lokasi-row btn-sm ml-1">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
            $('.list-lokasi-kegiatan').append(newLocationField);

            $(document).on('blur', '.lat-input, .lang-input', function() {
                const $input = $(this);
                const value = $input.val();
                const type = $input.hasClass('lat-input') ? 'latitude' : 'longitude';

                const validationResult = validateCoordinate(value, type);

                if (validationResult.valid) {
                    $input.val(validationResult.value);
                    $input.removeClass('is-invalid').addClass('is-valid');
                } else {
                    $input.val('');
                    $input.removeClass('is-valid').addClass('is-invalid');
                }
            });

            // Initialize kecamatan select2
            $(`#kecamatan-${uniqueId}`).select2({
                placeholder: '{{ __('cruds.kegiatan.basic.select_kecamatan') }}',
                allowClear: true,
                ajax: {
                    url: "{{ route('api.kegiatan.kecamatan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kabupaten_id: $(`#kabupaten_id`).val(),
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true,
                    error: function(jqXHR, textStatus, errorThrown) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error fetching Kecamatan data. Please try again later.',
                            position: 'center',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        $('#kecamatan-' + uniqueId).focus();
                    }
                }
            }).on('select2:open', function(e) {
                $('.select2-container').css('z-index', 1035);
            }).on('select2:close', function(e) {
                $('.select2-container').css('z-index', 999);
            });

            $(`#kelurahan-${uniqueId}`).select2({
                placeholder: '{{ __('cruds.kegiatan.basic.select_desa') }}',
                allowClear: true,
                ajax: {
                    url: "{{ route('api.kegiatan.kelurahan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kecamatan_id: $(`#kecamatan-${uniqueId}`).val(),
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true,
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error fetching Kelurahan data:", textStatus, errorThrown);
                        Toast.fire({
                            icon: 'error', // Use 'error' icon for errors
                            title: 'Please Select Kecamatan First!',
                            text: 'Kelurahan data depends on Kecamatan data. Please select a Kecamatan first.',
                            position: 'center',
                            timer: 2000, // Increased timer to 3 seconds for better visibility
                            timerProgressBar: true
                        });
                        $('#kecamatan-' + uniqueId).focus();
                    }
                }
            }).on('select2:open', function(e) {
                $('.select2-container').css('z-index', 1035);
            }).on('select2:close', function(e) {
                $('.select2-container').css('z-index', 999);
            });

            // Handle dependencies
            $(`#provinsi_id`).on('change', function() {
                $(`#kabupaten_id`).val(null).trigger('change');
                changeKabupatenAlert();
            });

            $(`#kabupaten_id`).on('change', function() {
                changeKabupatenAlert();
            });

            $(`#kecamatan-${uniqueId}`).on('change', function() {
                $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
            });

            $(`#provinsi_id, #kabupaten_id, #kecamatan-${uniqueId}, #kelurahan-${uniqueId}, #lokasi-${uniqueId}, #lat-${uniqueId}, #long-${uniqueId}`)
                .on('change', function() {});

            $(`.list-lokasi-kegiatan .lokasi-kegiatan[data-unique-id="${uniqueId}"]`).on('click',
                '.remove-lokasi-row',
                function() {
                    $(this).closest('.lokasi-kegiatan').remove();
                });
            return uniqueId
        }

        $('#btn-lokasi-kegiatan').on('click', function() {
            let idProvinsi = $('#provinsi_id').val();
            let idKabupaten = $('#kabupaten_id').val();
            if (!idProvinsi) {
                Swal.fire({
                    icon: 'warning',
                    title: '',
                    text: 'Please select a province first.',
                    position: 'center',
                    timer: 1000,
                    timerProgressBar: true
                });
                $('#provinsi_id').focus();
                return false;
            }
            if (!idKabupaten) {
                Swal.fire({
                    icon: 'warning',
                    title: '',
                    text: 'Please select a kabupaten after selecting a province.',
                    position: 'center',
                    timer: 1000,
                    timerProgressBar: true
                });

                $('#kabupaten_id').focus();
                return false;
            }

            addNewLocationInputs();
        });

        calculateDuration();
        initializeSelect2WithDynamicUrl('mitra_id');
        initializeSelect2WithDynamicUrl('sektor_id');

        function changeKabupatenAlert() {
            Swal.fire({
                title: 'Change Kabupaten ?',
                text: 'Changing the Kabupaten will clear all current entries. Do you want to proceed?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Clear All',
                cancelButtonText: 'No, Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.kecamatan-select').val(null).trigger('change');
                    $('.kelurahan-select').val(null).trigger('change');
                }
            });
        }

    });
</script>
{{-- validation form --}}

@include('tr.kegiatan.js._validasi')
@endpush
