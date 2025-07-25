{{-- Basic Information --}}
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_program" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
        <!-- id program -->
        <input type="hidden" name="program_id" id="program_id">
        <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id ?? '' }}"
            title="{{ auth()->user()->nama ?? '' }}">
        <!-- kode program -->
        <input type="text" class="form-control" id="kode_program"
            placeholder="{{ __('cruds.kegiatan.basic.program_select_kode') }}" name="kode_program" data-toggle="modal"
            data-target="#ModalDaftarProgram">
    </div>
    <!-- nama program-->
    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_program" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.program_nama') }}
        </label>
        <input type="text" class="form-control" id="nama_program"
            placeholder="{{ __('cruds.kegiatan.basic.program_nama') }}" name="nama_program">
    </div>
</div>
<div class="form-group row">
    <!-- kode kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.kode') }}
        </label>
        <input type="hidden" class="form-control" id="programoutcomeoutputactivity_id"
            placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="programoutcomeoutputactivity_id">
        <input type="text" class="form-control" id="kode_kegiatan"
            placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kode_kegiatan" data-toggle="modal"
            data-target="#ModalDaftarProgramActivity">
    </div>
    <!-- nama kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_kegiatan" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.nama') }}
        </label>
        <input type="text" class="form-control" id="nama_kegiatan"
            placeholder="{{ __('cruds.kegiatan.basic.nama') }}" name="nama_kegiatan">
    </div>
</div>

<div class="form-group row">
    <!-- jenis kegiatan-->
    <!-- bentuk kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
        <label for="jeniskegiatan_id" class="input-group col-form-label">
            <strong>{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control select2" name="jeniskegiatan_id" id="jeniskegiatan_id"
                data-api-url="{{ route('api.kegiatan.jenis_kegiatan') }}">
                <!-- Options will be populated by select2 -->
            </select>
        </div>
    </div>
    <!-- sektor kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1 mb-1">
        <label for="sektor_id" class="input-group col-form-label">
            <strong>{{ __('cruds.kegiatan.basic.sektor_kegiatan') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control select2" name="sektor_id[]" id="sektor_id" multiple
                data-api-url="{{ route('api.kegiatan.sektor_kegiatan') }}">
                <!-- Options will be populated by select2 -->
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
            <select class="form-control select2-readonly" name="fasepelaporan" id="fasepelaporan">
                <option value="">{{ __('global.pleaseSelect') }}</option>
                @for ($i = 1; $i <= 99; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
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
        <input type="date" class="form-control" id="tanggalmulai" name="tanggalmulai">
    </div>
    <!-- tgl selesai-->
    <div class="col-sm-6 col-md-6 col-lg-3 self-center order-2 order-md-2">
        <label for="tanggalselesai" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.tanggalselesai') }}
        </label>
        <input type="date" class="form-control" id="tanggalselesai" name="tanggalselesai">
    </div>
    <!-- durasi kegiatan-->
    <div class="col-sm-6 col-md-6 col-lg-3 self-center order-2 order-md-2">
        <label for="durasi" class="input-group col-form-label">
            {{ __('Durasi') }}
        </label>
        <input type="text" class="form-control" id="durasi" name="durasi" readonly>
    </div>
</div>

<!-- nama mitra-->
<div class="form-group row">
    <div class="col-sm-12 col-md-9 col-lg-9 self-center order-2 order-md-2">
        <label for="mitra_id" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.nama_mitra') }}</label>
        <div class="select2-purple">
            <select class="form-control select2" data-api-url="{{ route('api.kegiatan.mitra') }}" id="mitra_id"
                placeholder=" {{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.basic.nama_mitra') }}"
                name="mitra_id[]" multiple>
            </select>
        </div>
    </div>
    <!-- status kegiatan-->
    <div class="col-sm-12 col-md-3 col-lg-3 self-center order-1 order-md-1">
        <label for="status" class="input-group col-form-label">
            <strong>{{ __('cruds.status.title') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control" name="status" id="status" required
                placeholder=" {{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.basic.status_kegiatan') }}">
                <optgroup label="{{ __('cruds.kegiatan.status') }}">
                    @foreach ($statusOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
        <label for="provinsi_id" class="input-group col-form-label">{{ __('cruds.provinsi.title') }}</label>
        <select name="provinsi_id" id="provinsi_id" class="form-control select2"
            data-api-url="{{ route('api.kegiatan.provinsi') }}"
            data-placeholder="{{ __('global.pleaseSelect') . ' ' . __('cruds.provinsi.title') }}">
        </select>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
        <label for="kabupaten_id" class="input-group col-form-label">{{ __('cruds.kabupaten.title') }}</label>
        <select name="kabupaten_id" id="kabupaten_id" class="form-control select2"
            data-api-url="{{ route('api.kegiatan.kabupaten') }}"
            data-placeholder="{{ __('global.pleaseSelect') . ' ' . __('cruds.kabupaten.title') }}">
        </select>
    </div>
</div>

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
                    <i class="bi bi-geo-alt-fill" data-toggle="tooltip" title="{{ __('cruds.desa.title') }}"></i>
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
    <div class="list-lokasi-kegiatan"></div>
</div>

<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
        <label class="input-group col-form-label">
            {{ __('Get Coordinate') }}
            <i class="bi bi-map-fill"></i>
        </label>
                {{-- <div id="map" class="form-control col-form-label"></div> --}}
                {{-- @include('tr.kegiatan._google_map_create') --}}
                <div id="googleMap" style="height: 400px; width: 100%;"></div>
            </div>
        </div>


@include('tr.kegiatan.tabs.program')
@include('tr.kegiatan.tabs.program-act')

@push('next-button')
    <div class="button" id="task_flyout">
        <button type="button" id="clearStorageButton" class="btn btn-warning float-left">Reset</button>
        <button type="button" id="next-button" class="btn btn-primary float-right">Next</button>
    </div>
@endpush

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map {
            height: 500px;
            width: 100%;
            /* margin-top: 20px; */
        }

        .select-container {
            /* margin-bottom: 15px; */
        }

        .select2-container {
            width: 100% !important;
            /* margin-bottom: 10px; */
        }
    </style>
@endpush
@push('basic_tab_js')
    <script>
        function calculateDuration() {
            const start = new Date($('#tanggalmulai').val());
            const end = new Date($('#tanggalselesai').val());

            if (!isNaN(start) && !isNaN(end) && end >= start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 untuk inklusif
                $('#durasi').val(diffDays + '  {{ __('cruds.kegiatan.days') }}');
            } else {
                $('#durasi').val('');
            }
        }


        $('#tanggalmulai, #tanggalselesai').on('change', calculateDuration);
        document.addEventListener('DOMContentLoaded', function() {
            calculateDuration();
        })
    </script>
@endpush
