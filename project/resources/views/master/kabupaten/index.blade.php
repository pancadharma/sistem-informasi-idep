@extends('layouts.app')

@section('subtitle', __('cruds.kabupaten.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.kabupaten.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.kabupaten.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="card card-primary collapsed-card">
            <div class="card-header">
                {{ __('global.create').' '. __('cruds.kabupaten.title')}}
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
                <div class="card-body">
                <form action="{{ route('kabupaten.store')}}" method="POST" class="resettable-form" id="kabupatenForm" autocomplete="off">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="provinsi_nama">{{ __('cruds.provinsi.nama') .' '. __('cruds.provinsi.title') }}</label>
                        <div class="form-group">
                            <select id="provinsi_add" name="provinsi_id" class="form-control select2 provinsi-data" style="width: 100%">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kode">{{ __('cruds.kabupaten.kode') .' '. __('cruds.kabupaten.title') }}</label>
                        <input placeholder="" type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required data-toggle="tooltip" data-placement="top" maxlength="5">
                    </div>
                    <div class="form-group">
                        <label for="nama">{{ __('cruds.kabupaten.nama') .' '. __('cruds.kabupaten.title') }}</label>
                        <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="type">{{ __('cruds.kabupaten.title') .' / '. __('cruds.kabupaten.kota') }}</label>
                        <select id="type" name="type" class="form-control select2 type" style="width: 100%">
                            <option></option>
                            <option value="Kabupaten"> {{ __('cruds.kabupaten.title') }} </option>
                            <option value="Kota"> {{ __('cruds.kabupaten.kota') }} </option>
                        </select>
                    </div>
                    <div class="form-group">
                    <strong>{{ __('cruds.status.title') .' '. __('cruds.kabupaten.title') }}</strong>
                        <div class="icheck-primary">
                            <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                            <label for="aktif">{{ __('cruds.status.aktif') }}</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success float-right btn-add-kabupaten" data-toggle="tooltip" data-placement="top" title="{{ __('global.submit') }}"><i class="fas fa-save"></i> {{ __('global.submit') }}</button>
                </form>
            </div>
        </div>
    <div class="card card-outline card-primary">
        <div class="card-body table-responsive">
            <table id="kabupaten" class="table table table-bordered table-striped table-hover row-border display compact responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th class="center align-middle">No.</th>
                        <th class="center">{{ __('cruds.kabupaten.kode') }}</th>
                        <th class="center">DT II</th>
                        <th class="align-middle">{{ __('cruds.kabupaten.title') }}</th>
                        <th class="align-middle">{{ __('cruds.provinsi.title') }}</th>
                        <th class="center align-middle">{{ __('cruds.status.title') }}</th>
                        <th class="center align-middle">{{ __('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    {{-- @include('master.kabupaten.create') --}}
    @include('master.kabupaten.edit')
    @include('master.kabupaten.show')
    @stop

    @push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @endpush

@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Select2', true)
    @section('plugins.Toastr', true)
    @section('plugins.Validation', true)

    @include('master.kabupaten.js')
    <script>
        $(document).ready(function() {
            $.ajax({
                url:  '{{ route('kabupaten.create') }}',
                method: 'GET',
                dataType: 'json',
                success: function(response){
                    let data = response.map(function(item) {
                        return {
                            id: item.id,
                            text: item.id+' - '+ item.nama,
                        };
                    });

                    $('#provinsi_add').select2({
                        placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
                        allowClear: true,
                        delay: 250,
                        data : data,
                    });
                    $(document).on('select2:open', function() {
                        setTimeout(function() {
                            document.querySelector('.select2-search__field').focus();
                        }, 100);
                    });
                },error: function(jqXHR, textStatus, errorThrown) {
                    const errorData = JSON.parse(jqXHR.responseText);
                    const errors = errorData.errors; // Access the error object
                    let errorMessage = "";
                    for (const field in errors) {
                        errors[field].forEach(error => {
                            errorMessage +=
                            `* ${error}\n`; // Build a formatted error message string
                        });
                    }
                    Swal.fire({
                        title: jqXHR.statusText,
                        text: errorMessage,
                        icon: 'error'
                    });
                }
            });
        });
    </script>
@endpush
