@extends('layouts.app')

@section('subtitle', __('global.update') . ' ' . __('cruds.program.title_singular'))
@section('content_header_title', __('global.update') . ' ' . __('cruds.program.title_singular'))
@section('sub_breadcumb', __('cruds.program.title'))

@section('content_body')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info collapsed-card">
                <div class="card-header">
                    <h6>{{ __('global.update') . ' ' . __('cruds.program.title_singular') }}</h6>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" id="editProgram" action="{{ route('program.update', [$program->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $program->id }}">
        <input type="hidden" name="program_id" value="{{ $program->id }}">
        {{-- Informasi Dasar --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.program.info_dasar') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    {{-- Informasi Dasar --}}
                    <div class="card-body pb-0">
                        <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="kode_program" class="control-label small mb-0">{{ __('cruds.program.form.kode') }}</label>
                                        <input type="text" id="kode_program" name="kode" class="form-control {{ $errors->has('kode') ? 'is-invalid' : '' }}" value="{{ old('kode', $program->kode) }}" required oninput="this.value = this.value.toUpperCase();">
                                    </div>
                                    @if ($errors->has('kode'))
                                        <span class="text-danger">{{ $errors->first('kode') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label for="nama_program" class="control-label small mb-0">{{ __('cruds.program.form.nama') }}</label>
                                        <input type="text" id="nama_program" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" value="{{ old('nama', $program->nama) }}" required>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="tanggalmulai" class="control-label small mb-0">{{ __('cruds.program.form.tgl_mulai') }}</label>
                                    <input type="date" id="tanggalmulai" name="tanggalmulai"class="form-control date {{ $errors->has('tanggalmulai') ? 'is-invalid' : '' }}" value="{{ old('tanggalmulai', \Carbon\Carbon::parse($program->tanggalmulai)->format('Y-m-d')) }}" required>
                                    @if ($errors->has('tanggalmulai'))
                                        <span class="text-danger">{{ $errors->first('tanggalmulai') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="tanggalselesai" class="control-label small mb-0">{{ __('cruds.program.form.tgl_selesai') }}</label>
                                    <input type="date" id="tanggalselesai" name="tanggalselesai" class="form-control date {{ $errors->has('tanggalselesai') ? 'is-invalid' : '' }}" value="{{ old('tanggalselesai', \Carbon\Carbon::parse($program->tanggalselesai)->format('Y-m-d')) }}" required>

                                    @if ($errors->has('tanggalselesai'))
                                        <span class="text-danger">{{ $errors->first('tanggalselesai') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="totalnilai" class="control-label small mb-0">{{ __('cruds.program.form.total_nilai') }}</label>
                                    <input type="text" id="totalnilai" name="totalnilai" class="form-control currency {{ $errors->has('totalnilai') ? 'is-invalid' : '' }}" minlength="0" value="{{ old('totalnilai', $program->totalnilai) }}" step="0.001" required>

                                    @if ($errors->has('totalnilai'))
                                        <span class="text-danger">{{ $errors->first('totalnilai') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Ekspektasi Penerima Manfaat --}}
                    <div class="card-body pb-0 pt-0">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaat"
                                        class="control-label small mb-0">{{ __('cruds.program.expektasi') }}</label>
                                    <input type="number" id="ekspektasipenerimamanfaat" maxlength="1000000"
                                        name="ekspektasipenerimamanfaat"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaat') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaat', $program->ekspektasipenerimamanfaat) }}"
                                        placeholder="{{ __('cruds.program.expektasi') }}"
                                        oninput="this.value = Math.max(0, this.value)" readonly>

                                    @if ($errors->has('ekspektasipenerimamanfaat'))
                                        <span class="text-danger">{{ $errors->first('ekspektasipenerimamanfaat') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="pria"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.form.pria') }}</strong></label>
                                    <input type="number" id="pria" name="ekspektasipenerimamanfaatman"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaatman') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaatman', $program->ekspektasipenerimamanfaatman) }}"
                                        oninput="this.value = Math.max(0, this.value)">

                                    @if ($errors->has('ekspektasipenerimamanfaatman'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaatman') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="wanita"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.form.wanita') }}</strong></label>
                                    <input type="number" id="wanita" name="ekspektasipenerimamanfaatwoman"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaatwoman') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaatwoman', $program->ekspektasipenerimamanfaatwoman) }}"
                                        oninput="this.value = Math.max(0, this.value)">

                                    @if ($errors->has('ekspektasipenerimamanfaatwoman'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaatwoman') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="laki"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.form.laki') }}</strong></label>
                                    <input type="number" id="laki" name="ekspektasipenerimamanfaatboy"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaatboy') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaatboy', $program->ekspektasipenerimamanfaatboy) }}"
                                        oninput="this.value = Math.max(0, this.value)">

                                    @if ($errors->has('ekspektasipenerimamanfaatboy'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaatboy') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="perempuan"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.form.perempuan') }}</strong></label>
                                    <input type="number" id="perempuan" name="ekspektasipenerimamanfaatgirl"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaatgirl') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaatgirl', $program->ekspektasipenerimamanfaatgirl) }}"
                                        oninput="this.value = Math.max(0, this.value)">
                                    @if ($errors->has('ekspektasipenerimamanfaatgirl'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaatgirl') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="total"
                                        class="control-label small mb-0"><strong>{{ __('cruds.program.ex_indirect') }}</strong></label>
                                    <input type="number" id="total" name="ekspektasipenerimamanfaattidaklangsung"
                                        class="form-control {{ $errors->has('ekspektasipenerimamanfaattidaklangsung') ? 'is-invalid' : '' }}"
                                        value="{{ old('ekspektasipenerimamanfaattidaklangsung', $program->ekspektasipenerimamanfaattidaklangsung) }}"
                                        oninput="this.value = Math.max(0, this.value)">
                                    @if ($errors->has('ekspektasipenerimamanfaattidaklangsung'))
                                        <span
                                            class="text-danger">{{ $errors->first('ekspektasipenerimamanfaattidaklangsung') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Kelompok Marjinal --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kelompokmarjinal" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.marjinal.list') }}
                                        </strong>
                                    </label>
                                    <div class="select2-purple">
                                        <select
                                            class="form-control select2 {{ $errors->has('kelompokmarjinal') ? 'is-invalid' : '' }}"
                                            name="kelompokmarjinal[]" id="kelompokmarjinal" multiple="multiple">
                                        </select>
                                    </div>
                                    @if ($errors->has('kelompokmarjinal'))
                                        <span class="text-danger">{{ $errors->first('kelompokmarjinal') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Target Reinstra --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="targetreinstra" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.list_reinstra') }}
                                        </strong>
                                    </label>
                                    <div class="select2-orange">
                                        <select
                                            class="form-control select2-hidden-accessible {{ $errors->has('targetreinstra') ? 'is-invalid' : '' }}"
                                            name="targetreinstra[]" id="targetreinstra" multiple="multiple">
                                        </select>
                                    </div>
                                    @if ($errors->has('targetreinstra'))
                                        <span class="text-danger">{{ $errors->first('targetreinstra') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Kaitan SDG --}}
                    <div class="card-body table-responsive pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kaitansdg" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.list_sdg') }}
                                        </strong>
                                    </label>
                                    <div class="select2-cyan">
                                        <select
                                            class="form-control select2 {{ $errors->has('kaitansdg') ? 'is-invalid' : '' }}"
                                            name="kaitansdg[]" id="kaitansdg" multiple="multiple">
                                        </select>
                                    </div>
                                    @if ($errors->has('kaitansdg'))
                                        <span class="text-danger">{{ $errors->first('kaitansdg') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Deskripsi Program --}}
                    <div class="card-body pb-0 pt-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="users" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.deskripsi') }}
                                        </strong>
                                    </label>
                                    <textarea id="deskripsi" name="deskripsiprojek" cols="30" rows="5" class="form-control"
                                        placeholder="{{ __('cruds.program.deskripsi') }}" maxlength="500">{{ old('deskripsiprojek', $program->deskripsiprojek) }}</textarea>

                                    @if ($errors->has('deskripsi'))
                                        <span class="text-danger">{{ $errors->first('deskripsiprojek') }}</span>
                                    @endif
                                </div>
                            </div>
                            {{-- Analisis Program --}}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="users" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.program.analisis') }}
                                        </strong>
                                    </label>
                                    <textarea id="analisis" name="analisamasalah" cols="30" rows="5" class="form-control"
                                        placeholder="{{ __('cruds.program.analisis') }}" maxlength="500">{{ old('analisamasalah', $program->analisamasalah) }}</textarea>
                                    @if ($errors->has('analisamasalah'))
                                        <span class="text-danger">{{ $errors->first('analisamasalah') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- File Uploads --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="file_pendukung" class="control-label small mb-0">
                                    <strong>
                                        {{ __('cruds.program.upload') }}
                                    </strong>
                                    <span class="text-red">
                                        ( {{ __('allowed file: .jpg .png .pdf .docx | max: 4MB') }} )
                                    </span>
                                    <div class="small">{{ __('cruds.program.edit_file') }}</div>
                                </label>
                                <div class="form-group file-loading">
                                    <input id="file_pendukung" name="file_pendukung[]" type="file"
                                        class="form-control" multiple data-show-upload="false" data-show-caption="true">
                                </div>
                                <div id="captions-container"></div>
                            </div>
                        </div>
                    </div>
                    {{-- Status --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="status" class="control-label small mb-0">
                                        <strong>
                                            {{ __('cruds.status.title') }}
                                        </strong>
                                    </label>
                                    @php
                                        $canEditStatus = auth()->user()->id == 1 || (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('Administrator')) || auth()->user()->can('program_status_edit');
                                    @endphp
                                    <div class="select2-green">
                                        <select
                                            class="form-control select2 {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                            name="status" id="status" @if(!$canEditStatus) disabled @endif>
                                            <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>
                                                {{ trans('global.pleaseSelect') }}</option>
                                            @foreach (App\Models\Program::STATUS_SELECT as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('status', $program->status) === (string) $key ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @if(!$canEditStatus)
                                            <input type="hidden" name="status" value="{{ old('status', $program->status) }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="users" class="control-label small mb-0">
                                        <strong>
                                            Program Created / Updated by
                                        </strong>
                                    </label>
                                    <div class="select2-green">
                                        <input type="text" class="form-control"
                                            value="{{ old('nama', $program->users->nama) }}" id="user_id"
                                            name="user_id" readonly>
                                        <input type="hidden" class="form-control" value="{{ auth()->user()->id }}"
                                            name="user_id">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Call Detail Program Blade for Create Here --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mt-2">
                                    @include('tr.program.detail.edit')
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Submit Update Button --}}
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mt-2">
                                    <button type="button" id="updateProgramBtn" class="btn btn-info btn-block float-right">
                                        {{ __('global.update') . ' ' . __('cruds.program.title_singular') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

<script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('/vendor/inputmask/AutoNumeric.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/buffer.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/piexif.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/locales/id.js') }}"></script>


{{-- call every js for edit and it's tab detail --}}
@include('tr.program.js.edit')
@include('tr.program.js.detail-edit.donor')
@include('tr.program.js.detail-edit.lokasi')
@include('tr.program.js.detail-edit.staff')

@include('tr.program.js.detail-create.reportschedule')
@include('tr.program.js.detail-edit.outcome')
@include('tr.program.js.detail-edit.partner')
@include('tr.program.js._validation')
<script>
    $(document).ready(function() {
        const benefitInputs = [
            '#pria',
            '#wanita',
            '#laki',
            '#perempuan',
            '#total'
        ];
        const totalInput = $('#ekspektasipenerimamanfaat');

        function calculateTotal() {
            let total = 0;
            benefitInputs.forEach(function(selector) {
                const value = parseInt($(selector).val(), 10);
                if (!isNaN(value)) {
                    total += value;
                }
            });
            totalInput.val(total);
        }

        benefitInputs.forEach(function(selector) {
            $(selector).on('input', calculateTotal);
        });

        // Initial calculation on page load
        calculateTotal();

        // Permission and initial status flags from backend
        const CAN_EDIT_STATUS = {{ $canEditStatus ? 'true' : 'false' }};
        const initiallyComplete = '{{ $program->status }}' === 'complete';

        // Disable update button if the existing record is already complete
        if (initiallyComplete && !CAN_EDIT_STATUS) {
            $('#updateProgramBtn').prop('disabled', true);
            if (typeof toastr !== 'undefined') {
                toastr.error('Program is already completed. Not allowed to update unless Administrator only.');
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Program is already completed. Not allowed to update unless Administrator only.',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        }

        $('#updateProgramBtn').on('click', function(e) {
            e.preventDefault();

            // Block submission entirely if record was initially complete and user not allowed
            if (initiallyComplete && !CAN_EDIT_STATUS) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Program is already completed. Not allowed to update unless Administrator only.');
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Action not allowed',
                        text: 'Program is already completed. Not allowed to update unless Administrator only.'
                    });
                }
                return;
            }

            // If user is moving status to complete now, run completion validation
            if ($('#status').val() === 'complete') {
                if (!validateProgramComplete()) {
                    return; // Stop if client-side validation fails
                }
            }

            Swal.fire({
                title: '{{ __("global.areYouSure") }}',
                // text: '{{ __("global.response.confirm_text") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __("global.yes") }}',
                cancelButtonText: '{{ __("global.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '{{ __("global.response.processing") }}',
                        text: '{{ __("global.response.please_wait") }}',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    let formData = new FormData($('#editProgram')[0]);

                    // Get unformatted value from AutoNumeric field
                    const totalNilaiAN = AutoNumeric.getAutoNumericElement('#totalnilai');
                    if (totalNilaiAN) {
                        formData.set('totalnilai', totalNilaiAN.getNumericString());
                    }

                    $.ajax({
                        url: $('#editProgram').attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                title: '{{ __("global.success") }}',
                                text: '{{ __("global.response.save_success") }}',
                                icon: 'success'
                            }).then(() => {
                                window.location.href = "{{ route('program.index') }}";
                            });
                        },
                        error: function(xhr) {
                            Swal.close();
                            let errorMessages = [];
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                for (const key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        errors[key].forEach(message => {
                                            errorMessages.push(message);
                                        });
                                    }
                                }
                            } else {
                                errorMessages.push('{{ __("global.response.save_failed") }}');
                            }

                            Swal.fire({
                                title: '{{ __("global.error") }}',
                                html: `<div style="text-align: left;"><ul style="padding-left: 20px;">${errorMessages.map(msg => `<li>${msg}</li>`).join('')}</ul></div>`,
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
