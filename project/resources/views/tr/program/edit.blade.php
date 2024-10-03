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
    {{-- <form action="" multipart/form-data >
    </form> --}}

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
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="nama_program" class="small">{{ __('cruds.program.form.nama') }}</label>
                                <input type="text" id="nama_program" name="nama" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="kode_program" class="small">{{ __('cruds.program.form.kode') }}</label>
                                <input type="text" id="kode_program" name="kode" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="tanggalselesai" class="small">{{ __('cruds.program.form.tgl_mulai') }}</label>
                                <input type="date" id="tanggalselesai" name="tanggalselesai" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="tanggalmulai" class="small">{{ __('cruds.program.form.tgl_selesai') }}</label>
                                <input type="date" id="tanggalmulai" name="tanggalmulai" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="totalnilai" class="small">{{ __('cruds.program.form.total_nilai') }}</label>
                                <input type="number" id="totalnilai" name="totalnilai" class="form-control" required
                                    step=".01",>
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
                                <label for="pria"
                                    class="small"><strong>{{ __('cruds.program.form.pria') }}</strong></label>
                                <input type="number" id="pria" name="ekspektasipenerimamanfaatman"
                                    class="form-control" required oninput="this.value = Math.max(0, this.value)">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="wanita"
                                    class="small"><strong>{{ __('cruds.program.form.wanita') }}</strong></label>
                                <input type="number" id="wanita" name="ekspektasipenerimamanfaatwoman"
                                    class="form-control" required oninput="this.value = Math.max(0, this.value)">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="laki"
                                    class="small"><strong>{{ __('cruds.program.form.laki') }}</strong></label>
                                <input type="number" id="laki" name="ekspektasipenerimamanfaatboy"
                                    class="form-control" required oninput="this.value = Math.max(0, this.value)">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="perempuan"
                                    class="small"><strong>{{ __('cruds.program.form.perempuan') }}</strong></label>
                                <input type="number" id="perempuan" name="ekspektasipenerimamanfaatgirl"
                                    class="form-control" required oninput="this.value = Math.max(0, this.value)">
                            </div>
                        </div>
                        {{-- <div class="col-lg-3">
                            <div class="form-group">
                                <label for="total"
                                    class="small"><strong>{{ __('cruds.program.form.total') }}</strong></label>
                                <input type="number" id="total" name="ekspektasipenerimamanfaattidaklangsung"
                                    class="form-control" required oninput="this.value = Math.max(0, this.value)">
                            </div>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="ex_indirect" class="small">{{ __('cruds.program.ex_indirect') }}</label>
                                <textarea id="ex_indirect" maxlength="100" name="ekspektasipenerimamanfaattidaklangsung" class="form-control"
                                    rows="2" placeholder="{{ __('cruds.program.ex_indirect') }}"></textarea>
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
                <div class="card-body table-responsive">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="kelompokmarjinal" class="small control-label">
                                    <strong>
                                        {{ __('cruds.program.marjinal.list') }}
                                    </strong>
                                </label>
                                <div class="select2-purple">
                                    <select class="form-control select2" name="kelompokmarjinal[]" id="kelompokmarjinal"
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
                <div class="card-body table-responsive pt-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="status" class="small control-label">
                                    <strong>
                                        {{ __('cruds.program.upload') }}
                                    </strong>
                                </label>
                                <input id="file_pendukung" name="file_pendukung[]" type="file"
                                    class="file form-control" multiple data-show-upload="false" data-show-caption="true">
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
                </div>
            </div>
        </div>
    </div>

@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
@section('plugins.KrajeeFileinput', true)
<script>
    // (function($) {
    //     var Defaults = $.fn.select2.amd.require('select2/defaults');
    //     $.extend(Defaults.defaults, {
    //         dropdownPosition: 'auto'
    //     });
    //     var AttachBody = $.fn.select2.amd.require('select2/dropdown/attachBody');
    //     var _positionDropdown = AttachBody.prototype._positionDropdown;
    //     AttachBody.prototype._positionDropdown = function() {
    //         var $window = $(window);
    //         var isCurrentlyAbove = this.$dropdown.hasClass('select2-dropdown--above');
    //         var isCurrentlyBelow = this.$dropdown.hasClass('select2-dropdown--below');
    //         var newDirection = null;
    //         var offset = this.$container.offset();
    //         offset.bottom = offset.top + this.$container.outerHeight(false);
    //         var container = {
    //             height: this.$container.outerHeight(false)
    //         };
    //         container.top = offset.top;
    //         container.bottom = offset.top + container.height;
    //         var dropdown = {
    //             height: this.$dropdown.outerHeight(false)
    //         };
    //         var viewport = {
    //             top: $window.scrollTop(),
    //             bottom: $window.scrollTop() + $window.height()
    //         };
    //         var enoughRoomAbove = viewport.top < (offset.top - dropdown.height);
    //         var enoughRoomBelow = viewport.bottom > (offset.bottom + dropdown.height);
    //         var css = {
    //             left: offset.left,
    //             top: container.bottom
    //         };
    //         // Determine what the parent element is to use for calciulating the offset
    //         var $offsetParent = this.$dropdownParent;
    //         // For statically positoned elements, we need to get the element
    //         // that is determining the offset
    //         if ($offsetParent.css('position') === 'static') {
    //             $offsetParent = $offsetParent.offsetParent();
    //         }
    //         var parentOffset = $offsetParent.offset();
    //         css.top -= parentOffset.top
    //         css.left -= parentOffset.left;
    //         var dropdownPositionOption = this.options.get('dropdownPosition');

    //         if (dropdownPositionOption === 'above' || dropdownPositionOption === 'below') {

    //             newDirection = dropdownPositionOption;

    //         } else {

    //             if (!isCurrentlyAbove && !isCurrentlyBelow) {
    //                 newDirection = 'below';
    //             }

    //             if (!enoughRoomBelow && enoughRoomAbove && !isCurrentlyAbove) {
    //                 newDirection = 'above';
    //             } else if (!enoughRoomAbove && enoughRoomBelow && isCurrentlyAbove) {
    //                 newDirection = 'below';
    //             }
    //         }

    //         if (newDirection == 'above' ||
    //             (isCurrentlyAbove && newDirection !== 'below')) {
    //             css.top = container.top - parentOffset.top - dropdown.height;
    //         }

    //         if (newDirection != null) {
    //             this.$dropdown
    //                 .removeClass('select2-dropdown--below select2-dropdown--above')
    //                 .addClass('select2-dropdown--' + newDirection);
    //             this.$container
    //                 .removeClass('select2-container--below select2-container--above')
    //                 .addClass('select2-container--' + newDirection);
    //         }

    //         this.$dropdownContainer.css(css);

    //     };

    // })(window.jQuery);
</script>


<script>
    //SCRIPT FOR CREATE PROGRAM FORM

    $("#file_pendukung").fileinput({
        showUpload: false,
        showCaption: true,
        maxFileSize: 2000,
        browseClass: "btn btn-primary",
        allowedFileExtensions: ["jpg", "png", "gif", "pdf", "jpeg", "bmp"],
        dropZoneEnabled: false,
        // allowedFileTypes: ["image", "pdf", "video"],
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
    });
    $(document).ready(function() {
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

    });
</script>
@endpush
