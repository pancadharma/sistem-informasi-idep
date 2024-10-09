@extends('layouts.app')

@section('subtitle', __('global.edit') . ' ' . __('cruds.program.title_singular'))
@section('content_header_title', __('cruds.program.title_singular'))
@section('sub_breadcumb', __('global.create') . ' ' . __('cruds.program.title'))

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

        <div class="row">
            <div class="col-sm-12">
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
                    {{-- Informasi Dasar --}}
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="nama"
                                        class="control-label mb-0 small">{{ __('cruds.program.nama') }}</label>
                                    <input type="text" id="nama" name="nama" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="kode"
                                        class="control-label mb-0 small">{{ __('cruds.program.form.kode') }}</label>
                                    <input type="text" id="kode" name="kode" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="tanggalmulai"
                                        class="control-label mb-0 small">{{ __('cruds.program.form.tgl_mulai') }}</label>
                                    <input type="date" id="tanggalmulai" name="tanggalmulai" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="tanggalselesai"
                                        class="control-label mb-0 small">{{ __('cruds.program.form.tgl_selesai') }}</label>
                                    <input type="date" id="tanggalselesai" name="tanggalselesai" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="totalnilai"
                                        class="control-label mb-0 small">{{ __('cruds.program.form.total_nilai') }}</label>
                                    <input type="text" id="totalnilai" name="totalnilai" class="form-control"
                                        minlength="0" step=".01",>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Ekspektasi Penerima Manfaat --}}
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaat"
                                        class="control-label mb-0 small">{{ __('cruds.program.expektasi') }}</label>
                                    <input type="number" id="ekspektasipenerimamanfaat" name="ekspektasipenerimamanfaat"
                                        class="form-control" placeholder="{{ __('cruds.program.expektasi') }}"
                                        oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaatman"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.form.pria') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaatman" name="ekspektasipenerimamanfaatman"
                                        class="form-control" oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaatwoman"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.form.wanita') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaatwoman" name="ekspektasipenerimamanfaatwoman"
                                        class="form-control" oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaatboy"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.form.laki') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaatboy" name="ekspektasipenerimamanfaatboy"
                                        class="form-control" oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaatgirl"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.form.perempuan') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaatgirl" name="ekspektasipenerimamanfaatgirl"
                                        class="form-control" oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="ekspektasipenerimamanfaattidaklangsung"
                                        class="control-label mb-0 small"><strong>{{ __('cruds.program.ex_indirect') }}</strong></label>
                                    <input type="number" id="ekspektasipenerimamanfaattidaklangsung" name="ekspektasipenerimamanfaattidaklangsung"
                                        class="form-control" oninput="this.value = Math.max(0, this.value)">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Kelompok Marjinal --}}
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kelompokmarjinal" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.program.marjinal.list') }}
                                        </strong>
                                    </label>
                                    <div class="select2-purple">
                                        <select class="form-control select2" name="kelompokmarjinal[]"
                                            id="kelompokmarjinal" multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Target Reinstra --}}
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="targetreinstra" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.program.list_reinstra') }}
                                        </strong>
                                    </label>
                                    <div class="select2-orange">
                                        <select class="form-control select2-hidden-accessible" name="targetreinstra[]"
                                            id="targetreinstra" multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Kaitan SDG --}}
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kaitansdg" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.program.list_sdg') }}
                                        </strong>
                                    </label>
                                    <div class="select2-orange">
                                        <select class="form-control select2" name="kaitansdg[]" id="kaitansdg"
                                            multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi Program --}}
                    <div class="card-body pt-0 pb-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="deskripsiprojek" class="control-label mb-0 small ">
                                        <strong>
                                            {{ __('cruds.program.deskripsi') }}
                                        </strong>
                                    </label>
                                    <textarea id="deskripsiprojek" name="deskripsiprojek" cols="30" rows="5" class="form-control {{ $errors->has('deskripsiprojek') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('cruds.program.deskripsi') }}" maxlength="500"></textarea>
                                        @if ($errors->has('deskripsiprojek'))
                                            <span class="text-danger">{{ $errors->first('deskripsiprojek') }}</span>
                                        @endif
                                </div>
                            </div>
                            {{-- Analisis Program --}}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="analisamasalah" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.program.analisis') }}
                                        </strong>
                                    </label>
                                    <textarea id="analisamasalah" name="analisamasalah" cols="30" rows="5" class="form-control {{ $errors->has('analisamasalah') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('cruds.program.analisis') }}" maxlength="500"></textarea>

                                    @if ($errors->has('deskripsiprojek'))
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
                                <label for="file_pendukung" class="control-label mb-0 small">
                                    <strong>
                                        {{ __('cruds.program.upload') }}
                                    </strong>
                                    <span class="text-red">
                                        ( {{ __('allowed file: .jpg .png .pdf .docx | max: 4MB') }} )
                                    </span>
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
                                    <label for="status" class="control-label mb-0 small">
                                        <strong>
                                            {{ __('cruds.status.title') }}
                                        </strong>
                                    </label>
                                    <div class="select2-green">
                                        <select class="form-control select2" name="status" id="status" required>
                                            <optgroup label="Status Progran">
                                                <option value="draft">Draft</option>
                                                <option value="running" disabled>Running</option>
                                                <option value="submit" disabled>Submit</option>
                                                <option value="completed" disabled>Completed</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="users" class="control-label mb-0 small">
                                        <strong>
                                            User Program
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
                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-primary btn-block">
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
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

<script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/buffer.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/piexif.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/locales/id.js') }}"></script>

<script>
    //SCRIPT FOR CREATE PROGRAM FORM
    $('#totalnilai').maskMoney({
        prefix: 'Rp. ',
        allowNegative: true,
        thousands: '.',
        decimal: ',',
        affixesStay: false
    });
    $(document).ready(function() {

        // $("#file_pendukung").fileinput({
        //     // uploadUrl: "{{ route('program.store') }}",
        //     theme: 'fa',
        //     showRemove: true,
        //     previewZoomButtonTitles: true,
        //     dropZoneEnabled: false,
        //     removeIcon: '<i class="bi bi-trash"></i>',
        //     showDrag: true,
        //     dragIcon: '<i class="bi-arrows-move"></i>',
        //     showZoom: true,
        //     showUpload: false,
        //     showRotate: true,
        //     showCaption: true,
        //     uploadAsync: false,
        //     maxFileSize: 4096,
        //     allowedFileExtensions: ["jpg", "png", "gif", "pdf", "jpeg", "bmp", "doc", "docx"],
        //     append: true
        // });
        var fileIndex = 0;
        var fileCaptions = {}; // Object to track files and captions

        $("#file_pendukung").fileinput({
            theme: "fa",
            showUpload: false,
            showRemove: false,
            browseOnZoneClick: true,
            allowedFileExtensions: ['jpg', 'png', 'jpeg', 'pdf', 'doc'],
            maxFileSize: 4096,
            // maxFilesNum: 10,
            overwriteInitial: false, // Ensure new files are added without removing previous ones
        }).on('fileloaded', function(event, file, previewId, index, reader) {
            // Increment the file index for each new file
            fileIndex++;
            var uniqueId = 'file-' + fileIndex;
            fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
            $('#captions-container').append(
                `<div class="form-group" id="caption-group-${uniqueId}">
                    <label for="caption-${uniqueId}"> {{ __('cruds.program.ket_file') }} ${file.name}</label>
                    <input type="text" class="form-control" name="keterangan[]" id="keterangan-${uniqueId}">
                    </div>`
            );
            // Store the unique identifier in the file preview element
            $(`#${previewId}`).attr('data-unique-id', uniqueId);
        }).on('fileremoved', function(event, id) {
            // Remove the corresponding caption input
            var uniqueId = $(`#${id}`).attr('data-unique-id');
            delete fileCaptions[uniqueId]; // Remove the file from the tracking object
            $(`#caption-group-${uniqueId}`).remove();
        }).on('fileclear', function(event) {
            // Clear all caption inputs when files are cleared
            fileCaptions = {}; // Reset the tracking object
            $('#captions-container').empty();
        }).on('filebatchselected', function(event, files) {
            // Clear all caption inputs when new files are selected
            fileCaptions = {}; // Reset the tracking object
            $('#captions-container').empty();
            // Iterate over each selected file and trigger the fileloaded event manually
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                fileIndex++;
                var uniqueId = 'file-' + fileIndex;
                fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
                $('#captions-container').append(
                    `<div class="form-group" id="caption-group-${uniqueId}">
                        <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                        <input type="text" class="form-control" name="captions[]" id="caption-${uniqueId}">
                        </div>`
                );
            }
        });

        $('#status').select2();

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
            var unmaskedValue = $('#totalnilai').maskMoney('unmasked')[0];
            formData.set('totalnilai', unmaskedValue);

            $.ajax({
                url: "{{ route('program.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "Processing...",
                        timer: 3000,
                        timerProgressBar: true,
                    });
                },
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
