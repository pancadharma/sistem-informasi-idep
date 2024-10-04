@extends('layouts.app')

@section('subtitle', __('global.edit') . ' ' . __('cruds.program.title_singular'))
@section('content_header_title', __('global.create') . ' ' . __('cruds.program.title_singular'))
@section('sub_breadcumb', __('cruds.program.title'))

@section('content_body')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary collapsed-card">
                <div class="card-header">
                    <h6>{{ __('global.create') . ' ' . __('cruds.program.title_singular') }}</h6>
                </div>
            </div>
        </div>
    </div>
    <form id="createProgram" method="POST" class="resettable-form" data-toggle="validator" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('POST')
        {{-- Informasi Dasar --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
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
                    <div class="card-body table-responsive pt-0">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="nama_program" class="small">{{ __('cruds.program.form.nama') }}</label>
                                    <input type="text" id="nama_program" name="nama" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="kode_program" class="small">{{ __('cruds.program.form.kode') }}</label>
                                    <input type="text" id="kode_program" name="kode" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="tanggalmulai"
                                        class="small">{{ __('cruds.program.form.tgl_selesai') }}</label>
                                    <input type="date" id="tanggalmulai" name="tanggalmulai" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="tanggalselesai"
                                        class="small">{{ __('cruds.program.form.tgl_mulai') }}</label>
                                    <input type="date" id="tanggalselesai" name="tanggalselesai" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="totalnilai"
                                        class="small">{{ __('cruds.program.form.total_nilai') }}</label>
                                    <input type="number" id="totalnilai" name="totalnilai" class="form-control"
                                        maxlength="15" minlength="0" step=".01",>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Ekspektasi Penerima Manfaat --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.program.expektasi') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive pt-0">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaat"
                                        class="small">{{ __('cruds.program.expektasi') }}</label>
                                    <input type="number" id="ekspektasipenerimamanfaat" maxlength="1000000"
                                        name="ekspektasipenerimamanfaat" class="form-control"
                                        placeholder="{{ __('cruds.program.expektasi') }}"
                                        oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="pria"
                                        class="small"><strong>{{ __('cruds.program.form.pria') }}</strong></label>
                                    <input type="number" id="pria" name="ekspektasipenerimamanfaatman"
                                        class="form-control" required oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="wanita"
                                        class="small"><strong>{{ __('cruds.program.form.wanita') }}</strong></label>
                                    <input type="number" id="wanita" name="ekspektasipenerimamanfaatwoman"
                                        class="form-control" required oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="laki"
                                        class="small"><strong>{{ __('cruds.program.form.laki') }}</strong></label>
                                    <input type="number" id="laki" name="ekspektasipenerimamanfaatboy"
                                        class="form-control" required oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="perempuan"
                                        class="small"><strong>{{ __('cruds.program.form.perempuan') }}</strong></label>
                                    <input type="number" id="perempuan" name="ekspektasipenerimamanfaatgirl"
                                        class="form-control" required oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="total"
                                        class="small"><strong>{{ __('cruds.program.ex_indirect') }}</strong></label>
                                    <input type="number" id="total" name="ekspektasipenerimamanfaattidaklangsung"
                                        class="form-control" oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kelompok Marjinal --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.program.marjinal.label') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kelompokmarjinal" class="small control-label">
                                        <strong>
                                            {{ __('cruds.program.marjinal.list') }}
                                        </strong>
                                    </label>
                                    <div class="select2-purple">
                                        <select class="form-control select2" name="kelompokmarjinal[]"
                                            id="kelompokmarjinal" multiple="multiple" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Target Reinstra --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.program.reinstra') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="targetreinstra" class="small control-label">
                                        <strong>
                                            {{ __('cruds.program.list_reinstra') }}
                                        </strong>
                                    </label>
                                    <div class="select2-orange">
                                        <select class="form-control select2-hidden-accessible" name="targetreinstra[]"
                                            id="targetreinstra" multiple="multiple" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kaitan SDG --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.program.sdg') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive pt-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kaitansdg" class="small control-label">
                                        <strong>
                                            {{ __('cruds.program.list_sdg') }}
                                        </strong>
                                    </label>
                                    <div class="select2-orange">
                                        <select class="form-control select2" name="kaitansdg[]" id="kaitansdg"
                                            multiple="multiple" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- Deskripsi Program --}}
            <div class="col-lg-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.program.deskripsi') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea id="deskripsi" name="deskripsiprojek" cols="30" rows="5" class="form-control"
                                        placeholder="{{ __('cruds.program.deskripsi') }}" required maxlength="500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Analisis Program --}}
            <div class="col-lg-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.program.analisis') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea id="analisis" name="analisis" cols="30" rows="5" class="form-control"
                                        placeholder="{{ __('cruds.program.analisis') }}" required maxlength="500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- File Uploads --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.program.files') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group file-loading">
                                    <label for="status" class="small control-label">
                                        <strong>
                                            {{ __('cruds.program.upload') }}
                                        </strong>
                                    </label>
                                    <input id="file_pendukung" name="file_pendukung[]" type="file"
                                        class="form-control" multiple data-show-upload="false" data-show-caption="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Status --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <strong>
                            {{ __('cruds.status.title') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive pt-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="status" class="small control-label">
                                        <strong>
                                            {{ __('cruds.status.title') }}
                                        </strong>
                                    </label>
                                    <div class="select2-green">
                                        <select class="form-control select2" name="status" id="status" required>
                                            <optgroup label="Status Progran">
                                                <option value="draft">Draft</option>
                                                <option value="running">Running</option>
                                                <option value="submit">Submit</option>
                                                <option value="completed">Completed</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="users" class="small control-label">
                                        <strong>
                                            User Program {{-- {{ auth()->user()->nama }} --}}
                                        </strong>
                                    </label>
                                    <div class="select2-green">
                                        <input type="text" class="form-control" value="{{ auth()->user()->nama }}"
                                            id="user_id" name="user_id" readonly>
                                        <input type="hidden" class="form-control" value="{{ auth()->user()->id }}"
                                            name="user_id">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Submit Button --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary float-right">
                                        {{ __('global.add') . ' ' . __('cruds.program.title_singular') }}
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
        crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-fileinput/css/fileinput.min.css" rel="stylesheet">
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
{{-- @section('plugins.KrajeeFileinput', true) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap-fileinput/js/fileinput.min.js"></script>

<script>
    //SCRIPT FOR CREATE PROGRAM FORM
    $(document).ready(function() {
        $('#status').select2();

        $("#file_pendukung").fileinput({
            uploadUrl: "{{ route('program.store') }}",
            uploadAsync: false,
            showUpload: false,
            showCaption: true,
            maxFileSize: 2048,
            allowedFileExtensions: ["jpg", "png", "gif", "pdf", "jpeg", "bmp", "doc", "docx"],
            dropZoneEnabled: false,
            multiple: true
        });
        var data_reinstra = "{{ route('program.api.reinstra') }}";
        var data_kelompokmarjinal = "{{ route('program.api.marjinal') }}";
        var data_sdg = "{{ route('program.api.sdg') }}";

        $('#kelompokmarjinal').select2({
            placeholder: '{{ __('cruds.program.marjinal.select') }}',
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_kelompokmarjinal,
                method: 'GET',
                delay: 1000,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            }
        });
        // SELECT2 For Target Reinstra
        $('#targetreinstra').select2({
            placeholder: '{{ __('cruds.program.select_reinstra') }}',
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_reinstra,
                method: 'GET',
                delay: 1000,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            }
        });

        //KAITAN SDG SELECT2

        $('#kaitansdg').select2({
            placeholder: '{{ __('cruds.program.select_sdg') }}',
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_sdg,
                method: 'GET',
                delay: 1000,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            }
        });

        $('#createProgram').on('submit', function(e) {
            e.preventDefault();
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            var formData = new FormData(this);
            // var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('program.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                // dataType: 'json',
                success: function(response) {
                    setTimeout(() => {
                        if (response.success === true) {
                            Swal.fire({
                                title: "{{ __('global.success') }}",
                                text: response.message,
                                icon: "success",
                                timer: 1500,
                                timerProgressBar: true,
                            });
                            $(this).trigger('reset');
                            $('#createProgram')[0].reset();
                            $('#createProgram').trigger('reset');
                            $('#kelompokmarjinal, #targetreinstra, #kaitansdg').val(
                                '').trigger('change');
                            $(".btn-tool").trigger('click');
                            $('#createProgram').find('button[type="submit"]')
                                .removeAttr('disabled');
                        }
                    }, 500);
                },
                error: function(xhr, status, error) {
                    $('#createProgram').find('button[type="submit"]').removeAttr(
                        'disabled');
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                    try {
                        const response = xhr.responseJSON;
                        if (response.errors) {
                            errorMessage +=
                                '<br><br><ul style="text-align:left!important">';
                            $.each(response.errors, function(field, messages) {
                                messages.forEach(message => {
                                    errorMessage +=
                                        `<li>${field}: ${message}</li>`;
                                    $(`#${field}-error`).removeClass(
                                        'is-valid').addClass(
                                        'is-invalid');
                                    $(`#${field}-error`).text(message);
                                    $(`#${field}`).removeClass('invalid')
                                        .addClass('is-invalid');
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    html: errorMessage,
                                });
                            });
                            errorMessage += '</ul>';
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                    });
                },
                complete: function() {
                    setTimeout(() => {
                        $(this).find('button[type="submit"]').removeAttr(
                            'disabled');
                    }, 500);
                }
            });

        });

    });
</script>
@endpush
