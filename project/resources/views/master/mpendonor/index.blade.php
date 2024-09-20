@extends('layouts.app')

@section('subtitle', __('cruds.mpendonor.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.mpendonor.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.mpendonor.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="card card-primary">
            <div class="card-header">
                {{ trans('global.create')}} {{trans('cruds.mpendonor.title')}}
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
                <div class="card-body">
                <form action="{{ route('pendonor.store')}}" method="POST" class="resettable-form" id="mpendonorForm" autocomplete="off">
                    @csrf
                    @method('POST')
                    <div class="form-group"> {{--  id kategori pendonor --}}
                        <label for="kategoripendonor_nama">{{ trans('cruds.kategoripendonor.nama') }} {{ trans('cruds.kategoripendonor.title') }}</label>
                        <div class="form-group">
                            <select id="kategoripendonor_add" name="kategoripendonor_id" class="form-control select2 kategoripendonor-data" style="width: 100%">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nama">{{ trans('cruds.mpendonor.nama') }} {{ trans('cruds.mpendonor.title') }}</label>
                        <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="pic">{{ trans('cruds.mpendonor.pic') }} {{ trans('cruds.mpendonor.title') }}</label>
                        <input type="text" id="pic" name="pic" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="email">{{ trans('cruds.mpendonor.email') }} {{ trans('cruds.mpendonor.title') }}</label>
                        <input type="text" id="email" name="email" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="phone">{{ trans('cruds.mpendonor.phone') }} {{ trans('cruds.mpendonor.title') }}</label>
                        <input type="text" id="phone" name="phone" class="form-control" required maxlength="20">
                    </div>
                    <div class="form-group">
                    <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.mpendonor.title') }}</strong>
                        <div class="icheck-primary">
                            <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                            <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success float-right btn-add-mpendonor" data-toggle="tooltip" data-placement="top" title="{{ trans('global.submit') }}"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
                </form>


            </div>
        </div>
    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="mpendonor" class="table table-bordered cell-border ajaxTable datatable-mpendonor" style="width:100%">
                <thead>
                    <tr>
                        
                        <th class="center">{{ trans('cruds.mpendonor.no') }}</th>
                        <th>{{ trans('cruds.mpendonor.title') }}</th>
                        <th>{{ trans('cruds.status.title') }}</th>
                        <th>{{ trans('cruds.status.action') }}</th>
                        
                        
                    </tr>
                </thead>
            </table>
        </div>
    </div>
   
    {{-- @include('master.kelompokmarjinal.create') --}}
    @include('master.kelompokmarjinal.edit')
    @include('master.kelompokmarjinal.show')
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

    @include('master.kelompokmarjinal.js')
    <script>
        $.ajax({
                url:  '{{ route('pendonor.create') }}',
                method: 'GET',
                dataType: 'json',
                success: function(response){
                    let data = response.map(function(item) {
                        return {
                            id: item.id,
                            text: item.nama,
                        };
                    });
                    
                    $('#kategoripendonor_add').select2({
                        placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kategoripendonor.title')}}",
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
    </script>
@endpush
