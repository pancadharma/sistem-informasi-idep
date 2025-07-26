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
                            <!--BASIC INFORMATION-->
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
                                <!--Kode Kegiatan & Nama Kegiatan -->
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
                                                @if ($kegiatan->jeniskegiatan)
                                                    <option value="{{ $kegiatan->jeniskegiatan->id }}" selected>{{ $kegiatan->jeniskegiatan->nama }}</option>
                                                @endif
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
                                                @foreach ($kegiatan->sektor as $item)
                                                    <option value="{{ $item->id }}" selected>{{ $item->nama }}</option>
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
                                                    <option value="{{ $item->id }}" selected>{{ $item->nama }}</option>
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
                                        {{ !is_null($preselectedProvinsiId) || empty($provinsiList) || count($provinsiList) == 0 ? 'value=' . $preselectedProvinsiId . '' : '' }}>
                                        <select name="provinsi_id" id="provinsi_id" class="form-control select2"
                                            data-api-url="{{ route('api.kegiatan.provinsi') }}"
                                            data-placeholder="{{ __('global.pleaseSelect') . ' ' . __('cruds.provinsi.title') }}"
                                            {{ !is_null($preselectedProvinsiId) || empty($provinsiList) || count($provinsiList) == 0 ? 'disabled' : '' }}>
                                            @foreach ($provinsiList as $provinsi)
                                                <option value="{{ $provinsi->id }}" {{ $preselectedProvinsiId == $provinsi->id ? 'selected' : '' }}>
                                                    {{ $provinsi->nama }}
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
                                            @foreach (($provinsiList->firstWhere('id', $preselectedProvinsiId)?->kabupaten ?? collect()) as $kabupaten)
                                                <option value="{{ $kabupaten->id }}" {{ $preselectedKabupatenId == $kabupaten->id ? 'selected' : '' }}>
                                                    {{ $kabupaten->nama }}
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
                                            <button type="button" id="btn-preview-kegiatan"
                                                class="btn btn-info ml-2">{{ __('Preview Data') }}</button>
                                        </div>
                                    </div>
                                    <div class="card-body pl-0 pt-1 pb-0 pr-1 mb-0">
                                        <div class="form-group row header-lokasi-kegiatan mb-0">
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
                                            <?php $uniqueId = "loc_" . (time() . '' . rand(100, 999) ?? $item->id ); // Use db id or fallback to timestamp + random ?>
                                            <div class="form-group row lokasi-kegiatan" data-unique-id="{{ $uniqueId }}">
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                                                    <select name="kecamatan_id[]" class="form-control dynamic-select2 kecamatan-select"
                                                            id="kecamatan-{{ $uniqueId }}"
                                                            data-placeholder="{{ __('global.pleaseSelect') . ' ' . __('cruds.kecamatan.title') }}"
                                                            data-selected="{{ $item->desa->kecamatan->id ?? '' }}">
                                                        <option value="{{ $item->desa->kecamatan->id ?? '' }}" selected>
                                                            {{ $item->desa->kecamatan->nama ?? '' }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                                                    <select name="kelurahan_id[]" class="form-control dynamic-select2 kelurahan-select"
                                                            id="kelurahan-{{ $uniqueId }}"
                                                            data-placeholder="{{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}"
                                                            data-selected="{{ $item->desa_id ?? '' }}">
                                                        <option value="{{ $item->desa_id ?? '' }}" selected>
                                                            {{ $item->desa->nama ?? '' }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-5">
                                                    <input type="text" class="form-control lokasi-input" id="lokasi-{{ $uniqueId }}" name="lokasi[]"
                                                           value="{{ old('lokasi', $item->lokasi ?? '') }}"
                                                           placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}">
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-6">
                                                    <input type="text" class="form-control lat-input" id="lat-{{ $uniqueId }}" name="lat[]"
                                                           value="{{ old('lat', $item->lat ?? '') }}"
                                                           placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-7 d-flex align-items-center">
                                                    <input type="text" class="form-control lang-input flex-grow-1" id="long-{{ $uniqueId }}" name="long[]"
                                                           value="{{ old('long', $item->long ?? '') }}"
                                                           placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                                                    <button type="button" class="btn btn-danger remove-lokasi-row btn-sm ml-1">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="form-group row lokasi-kegiatan" data-unique-id="loc_default">
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                                                    <select name="kecamatan_id[]" class="form-control dynamic-select2 kecamatan-select" id="kecamatan-loc_default" data-placeholder="Pilih Kecamatan"></select>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                                                    <select name="kelurahan_id[]" class="form-control dynamic-select2 kelurahan-select" id="kelurahan-loc_default" data-placeholder="Pilih Desa"></select>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-5">
                                                    <input type="text" class="form-control lokasi-input" id="lokasi-loc_default" name="lokasi[]" placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}">
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-6">
                                                    <input type="text" class="form-control lat-input" id="lat-loc_default" name="lat[]" placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-7 d-flex align-items-center">
                                                    <input type="text" class="form-control lang-input flex-grow-1" id="long-loc_default" name="long[]" placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                                                    <button type="button" class="btn btn-danger remove-lokasi-row btn-sm ml-1">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                                            <label class="input-group col-form-label">
                                                {{ __('Get Coordinate') }}
                                                <i class="bi bi-map-fill"></i>
                                            </label>
                                            <!--MAPS-->
                                            <div class="card-info pt-2">
                                            {{-- @include('tr.kegiatan._map') --}}
                                            @include('tr.kegiatan._google_map')
                                            </div>
                                        </div>
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
                                            rows="2">{{ old('deskripsilatarbelakang', $kegiatan->deskripsilatarbelakang) }}</textarea>
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
                                            rows="2">{{ old('deskripsitujuan', $kegiatan->deskripsitujuan) }}</textarea>
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
                                            rows="2">{{ old('deskripsikeluaran', $kegiatan->deskripsikeluaran) }}</textarea>
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

@include('tr.kegiatan.modal._preview')
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
{{-- @include('tr.kegiatan.js.tabs.basic') --}}
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
    $(document).ready(function() {

        $('.select2').select2({
            width: 'resolve'
        });

        $('.select2').each(function() {
            var fieldId = $(this).attr('id');
            initializeSelect2WithDynamicUrl(fieldId);
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

        // start grok
        $('#provinsi_id, #kabupaten_id').select2({
            placeholder: function() {
                return $(this).data('placeholder');
            },
            allowClear: true
        });

        $('.list-lokasi-kegiatan .lokasi-kegiatan').each(function() {
            const uniqueId = $(this).data('unique-id');
            initializeLocationSelect2(uniqueId, $(this).find('.kecamatan-select'), $(this).find('.kelurahan-select'));
        });

        $('.list-lokasi-kegiatan').on('click', '.remove-lokasi-row', function() {
            $(this).closest('.lokasi-kegiatan').remove();
        });

        function initializeLocationSelect2(uniqueId, kecamatanSelect, kelurahanSelect, kabupatenId = $('#kabupaten_id').val(), isReset = false) {
            // Initialize Kecamatan Select2
            kecamatanSelect.select2({
                placeholder: '{{ __('cruds.kegiatan.basic.select_kecamatan') }}',
                allowClear: true,
                ajax: {
                    url: "{{ route('api.kegiatan.kecamatan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kabupaten_id: kabupatenId,
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
                    cache: false // Disable cache to ensure fresh options
                }
            }).val(isReset ? null : kecamatanSelect.data('selected')).trigger('change');

            // Initialize Kelurahan Select2 with dependency on Kecamatan
            kelurahanSelect.select2({
                placeholder: '{{ __('cruds.kegiatan.basic.select_desa') }}',
                allowClear: true,
                ajax: {
                    url: "{{ route('api.kegiatan.kelurahan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        const kecamatanId = $(`#kecamatan-${uniqueId}`).val();
                        return {
                            search: params.term,
                            kecamatan_id: kecamatanId,
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
                    cache: false // Disable cache to ensure fresh options
                }
            }).val(isReset ? null : kelurahanSelect.data('selected')).trigger('change');

            // Reset Kelurahan when Kecamatan changes
            kecamatanSelect.on('change', function() {
                kelurahanSelect.val(null).trigger('change');
            });
        }

        function addNewLocationInputs(uniqueId = "loc_" + Date.now()) {
            var newLocationField = `
                <div class="form-group row lokasi-kegiatan" data-unique-id="${uniqueId}">
                    <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                        <select name="kecamatan_id[]" class="form-control dynamic-select2 kecamatan-select" id="kecamatan-${uniqueId}" data-placeholder="Pilih Kecamatan"></select>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                        <select name="kelurahan_id[]" class="form-control dynamic-select2 kelurahan-select" id="kelurahan-${uniqueId}" data-placeholder="Pilih Desa"></select>
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

            $(`.list-lokasi-kegiatan .lokasi-kegiatan[data-unique-id="${uniqueId}"]`).on('click', '.remove-lokasi-row', function() {
                $(this).closest('.lokasi-kegiatan').remove();
            });

            initializeLocationSelect2(uniqueId, $(`#kecamatan-${uniqueId}`), $(`#kelurahan-${uniqueId}`));
            return uniqueId;
        }

        // grook
        $('#btn-lokasi-kegiatan').on('click', function() {
            let idProvinsi = $('#provinsi_id').val();
            let idKabupaten = $('#kabupaten_id').val();
            if (!idProvinsi) {
                Swal.fire({
                    icon: 'warning',
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
                    text: 'Please select a kabupaten after selecting a province.',
                    position: 'center',
                    timer: 1000,
                    timerProgressBar: true
                });
                $('#kabupaten_id').focus();
                return false;
            }

            const newUniqueId = addNewLocationInputs();
            initializeLocationSelect2(newUniqueId, $(`#kecamatan-${newUniqueId}`), $(`#kelurahan-${newUniqueId}`));
        });

        $('#status, #fasepelaporan').select2({
            width: 'resolve',
            minimumResultsForSearch: -1 // Disable search for static dropdowns
        });

        let isProgrammaticChange = false;

        $('#kabupaten_id').on('change', function() {
            if (isProgrammaticChange) {
                isProgrammaticChange = false; // Reset flag setelah diproses
                return; // Hentikan eksekusi jika ini perubahan terprogram
            }

            const newKabupatenId = $(this).val();
            const previousKabupatenId = {{ $preselectedKabupatenId ?? 'null' }};

            Swal.fire({
                title: "{{ __('global.warning') }}",
                text: "{{ __('cruds.kegiatan.validate.kab_change') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ __('global.yes') }}",
                cancelButtonText: "{{ __('global.no') }}",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna setuju, kosongkan lokasi dan tambahkan baris default
                    $('.list-lokasi-kegiatan').empty();
                    const defaultUniqueId = "loc_" + Date.now();
                    addNewLocationInputs(defaultUniqueId);
                    initializeLocationSelect2(defaultUniqueId, $(`#kecamatan-${defaultUniqueId}`), $(`#kelurahan-${defaultUniqueId}`), newKabupatenId, true);

                    // Simpan nilai baru sebagai previous-value
                    $('#kabupaten_id').data('previous-value', newKabupatenId);
                } else {
                    // Jika pengguna membatalkan, kembalikan ke nilai sebelumnya tanpa memicu loop
                    isProgrammaticChange = true; // Set flag untuk perubahan terprogram
                    $(this).val(previousKabupatenId).trigger('change'); // Kembali ke nilai sebelumnya
                }
            });

            // Simpan nilai saat ini sebagai previous-value sebelum perubahan diterapkan
            if (!$(this).data('previous-value')) {
                $(this).data('previous-value', $(this).val());
            }
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

<script>
    function validateCoordinate(value, type) {
        if (!value) return {
            valid: false,
            message: `${type} is required`
        };

        // Remove any spaces
        value = value.toString().trim();

        // Regex patterns for latitude and longitude
        const latPattern = /^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/;
        const longPattern = /^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/;

        const pattern = type === 'latitude' ? latPattern : longPattern;

        if (!pattern.test(value)) {
            return {
                valid: false,
                message: `Invalid ${type} format. ${type === 'latitude' ? 'Must be between -90 and 90' : 'Must be between -180 and 180'}`
            };
        }

        return {
            valid: true,
            value: value
        };
    }

    function collectFormData() {
        const formData = {};

        // Collect basic form fields
        formData.programoutcomeoutputactivity_id = $('#programoutcomeoutputactivity_id').val();
        formData.jeniskegiatan_id = $('#jeniskegiatan_id').val();
        formData.fasepelaporan = $('#fasepelaporan').val();
        formData.tanggalmulai = $('#tanggalmulai').val();
        formData.tanggalselesai = $('#tanggalselesai').val();
        formData.status = $('#status').val();
        formData.mitra_id = $('#mitra_id').val();
        formData.provinsi_id = $('#provinsi_id').val();
        formData.kabupaten_id = $('#kabupaten_id').val();

        // Collect description fields
        formData.deskripsilatarbelakang = $('#deskripsilatarbelakang').val();
        formData.deskripsitujuan = $('#deskripsitujuan').val();
        formData.deskripsikeluaran = $('#deskripsikeluaran').val();

        // Collect beneficiary data
        formData.penerimamanfaatdewasaperempuan = $('#penerimamanfaatdewasaperempuan').val();
        formData.penerimamanfaatdewasalakilaki = $('#penerimamanfaatdewasalakilaki').val();
        formData.penerimamanfaatdewasatotal = $('#penerimamanfaatdewasatotal').val();
        // ... (collect all other beneficiary fields)

        // Collect location data
        formData.locations = [];
        $('.lokasi-kegiatan').each(function() {
            const uniqueId = $(this).data('unique-id');
            const kecamatanId = $(this).find('select[name="kecamatan_id[]"]').val();
            // const kecamatanText = $(this).find('select[name="kecamatan_id[]"] option:selected').text();
            const kecamatanSelect = $(this).find('select[name="kecamatan_id[]"]');
            const kecamatanText = kecamatanSelect.find('option:selected').text().trim() || '-';

            const desaId = $(this).find('select[name="kelurahan_id[]"]').val();
            // const desaText = $(this).find('select[name="desa_id[]"] option:selected').text();
            const desaSelect = $(this).find('select[name="kelurahan_id[]"]');
            const desaData = desaSelect.select2('data');
            const desaText = desaSelect.find('option:selected').text().trim() || '-';


            const lokasi = $(this).find('input[name="lokasi[]"]').val();
            const lat = $(this).find('input[name="lat[]"]').val();
            const long = $(this).find('input[name="long[]"]').val();

            // formData.locations.push({
            //     kecamatan_id: kecamatanId,
            //     kecamatan_text: kecamatanText,
            //     desa_id: desaId,
            //     desa_text: desaText,
            //     lokasi: lokasi,
            //     lat: lat,
            //     long: long
            // });

            formData.locations.push({
                kecamatan_id: kecamatanSelect.val(),
                kecamatan_text: kecamatanText,
                desa_id: desaSelect.val(),
                desa_text: desaText,
                lokasi: lokasi,
                lat: lat,
                long: long
            });
        });

        // Collect penulis data
        formData.penulis = [];
        $('.penulis-row').each(function() {
            const penulisId = $(this).find('select[name="penulis[]"]').val();
            const penulisText = $(this).find('select[name="penulis[]"] option:selected').text();
            const jabatanId = $(this).find('select[name="jabatan[]"]').val();
            const jabatanText = $(this).find('select[name="jabatan[]"] option:selected').text();

            formData.penulis.push({
                penulis_id: penulisId,
                penulis_text: penulisText,
                jabatan_id: jabatanId,
                jabatan_text: jabatanText
            });
        });

        return formData;
    }

    function validateFormData(formData) {
        const errors = [];

        // Validate required fields
        if (!formData.programoutcomeoutputactivity_id) errors.push("Program Activity is required");
        if (!formData.jeniskegiatan_id) errors.push("Jenis Kegiatan is required");
        if (!formData.tanggalmulai) errors.push("Tanggal Mulai is required");
        if (!formData.tanggalselesai) errors.push("Tanggal Selesai is required");

        // Validate locations
        if (formData.locations.length === 0) {
            errors.push("At least one location is required");
        } else {
            formData.locations.forEach((location, index) => {
                if (!location.kecamatan_id) errors.push(`Location ${index+1}: Kecamatan is required`);
                if (!location.desa_id) errors.push(`Location ${index+1}: Desa is required`);
                if (!location.lokasi) errors.push(`Location ${index+1}: Lokasi is required`);

                // Validate lat/long
                if (location.lat) {
                    const latValidation = validateCoordinate(location.lat, 'latitude');
                    if (!latValidation.valid) errors.push(`Location ${index+1}: ${latValidation.message}`);
                }

                if (location.long) {
                    const longValidation = validateCoordinate(location.long, 'longitude');
                    if (!longValidation.valid) errors.push(`Location ${index+1}: ${longValidation.message}`);
                }
            });
        }

        return errors;
    }

    function displayPreview(formData) {
        let html = '<div class="container-fluid">';

        // Basic Information
        html += '<h4>Basic Information</h4>';
        html += '<div class="row">';
        html += `<div class="col-md-6"><strong>Program Activity:</strong> ${$('#nama_kegiatan').val()}</div>`;
        html +=
            `<div class="col-md-6"><strong>Jenis Kegiatan:</strong> ${$('#jeniskegiatan_id option:selected').text()}</div>`;
        html += `<div class="col-md-6"><strong>Fase Pelaporan:</strong> ${formData.fasepelaporan}</div>`;
        html += `<div class="col-md-6"><strong>Status:</strong> ${$('#status option:selected').text()}</div>`;
        html += `<div class="col-md-6"><strong>Tanggal Mulai:</strong> ${formData.tanggalmulai}</div>`;
        html += `<div class="col-md-6"><strong>Tanggal Selesai:</strong> ${formData.tanggalselesai}</div>`;
        html += '</div>';

        // Location Information
        html += '<h4 class="mt-4">Location Information</h4>';
        html += '<div class="table-responsive">';
        html += '<table class="table table-bordered table-sm">';
        html +=
            '<thead><tr><th>Kecamatan</th><th>Desa</th><th>Lokasi</th><th>Latitude</th><th>Longitude</th></tr></thead>';
        html += '<tbody>';

        formData.locations.forEach(location => {
            html += '<tr>';
            html += `<td>${location.kecamatan_text || '-'} - ${location.kecamatan_id || '-'}</td>`;
            html += `<td>${location.desa_text || '-'} - ${location.desa_id || '-'}</td>`;
            html += `<td>${location.lokasi || '-'}</td>`;
            html += `<td>${location.lat || '-'}</td>`;
            html += `<td>${location.long || '-'}</td>`;
            html += '</tr>';
        });

        html += '</tbody></table></div>';

        // Penulis Information
        if (formData.penulis && formData.penulis.length > 0) {
            html += '<h4 class="mt-4">Penulis Information</h4>';
            html += '<div class="table-responsive">';
            html += '<table class="table table-bordered table-sm">';
            html += '<thead><tr><th>Penulis</th><th>Jabatan</th></tr></thead>';
            html += '<tbody>';

            formData.penulis.forEach(penulis => {
                html += '<tr>';
                html += `<td>${penulis.penulis_text || '-'}</td>`;
                html += `<td>${penulis.jabatan_text || '-'}</td>`;
                html += '</tr>';
            });

            html += '</tbody></table></div>';
        }

        html += '</div>'; // Close container

        $('#preview-content').html(html);

        console.table(formData.locations);
    }

    $(document).ready(function() {
        // Preview button click handler
        $('#btn-preview-kegiatan').on('click', function() {
            const formData = collectFormData();
            // const errors = validateFormData(formData);

            // if (errors.length > 0) {
            //     // Display validation errors
            //     let errorHtml = '<ul>';
            //     errors.forEach(error => {
            //         errorHtml += `<li>${error}</li>`;
            //     });
            //     errorHtml += '</ul>';

            //     $('#validation-errors').html(errorHtml).show();
            // } else {
            //     // Hide any previous errors
            //     $('#validation-errors').hide();

            //     // Display the preview
            displayPreview(formData);
            // }

            // Show the modal
            $('#previewModal').modal('show');
        });

        // Submit button in modal click handler
        $('#btn-submit-preview').on('click', function() {
            // Get form data
            const formData = collectFormData();

            // Send data via AJAX
            $.ajax({
                url: '{{ route('kegiatan.update', [$kegiatan->id]) }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT',
                    ...formData
                },
                success: function(response) {
                    // Close the modal
                    $('#previewModal').modal('hide');

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data has been saved successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Optionally redirect or refresh
                    // window.location.href = '{{ route('kegiatan.index') }}';
                },
                error: function(xhr) {
                    // Handle errors
                    let errorMessage = 'An error occurred while saving the data.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
        });
    });


    // $('#previewModal').on('show.bs.modal', function () {
    //     let tableBody = $('#lokasiPreview tbody');
    //     tableBody.empty();

    //     $('.lokasi-row').each(function () {
    //         const kecamatanSelect = $(this).find('.kecamatan_id');
    //         const desaSelect = $(this).find('.desa_id');

    //         const kecamatanText = kecamatanSelect.find('option:selected').text().trim() || '-';
    //         const desaData = desaSelect.select2('data');
    //         const desaText = desaData.length ? desaData[0].text : '-';

    //         const lokasi = $(this).find('input[name="lokasi[]"]').val() || '-';
    //         const lat = $(this).find('input[name="lat[]"]').val() || '-';
    //         const long = $(this).find('input[name="long[]"]').val() || '-';

    //         tableBody.append(`
    //             <tr>
    //                 <td>${kecamatanText}</td>
    //                 <td>${desaText}</td>
    //                 <td>${lokasi}</td>
    //                 <td>${lat}</td>
    //                 <td>${long}</td>
    //             </tr>
    //         `);
    //     });
    // });
</script>

@include('tr.kegiatan.js._validasi')
@endpush
