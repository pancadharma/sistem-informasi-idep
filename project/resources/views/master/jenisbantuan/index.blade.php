@extends('layouts.app')

@section('subtitle', __('cruds.jenisbantuan.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.jenisbantuan.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.jenisbantuan.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="card card-primary collapsed-card">
            <div class="card-header">
                {{ trans('global.create')}} {{trans('cruds.jenisbantuan.title')}}
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
                <div class="card-body">
                <form action="{{ route('jenisbantuan.store')}}" method="POST" class="resettable-form" id="jenisbantuanForm" autocomplete="off">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <label for="nama">{{ trans('cruds.jenisbantuan.nama') }} {{ trans('cruds.jenisbantuan.title') }}</label>
                        <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                    <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.jenisbantuan.title') }}</strong>
                        <div class="icheck-primary">
                            <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                            <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success float-right btn-add-jenisbantuan" data-toggle="tooltip" data-placement="top" title="{{ trans('global.submit') }}"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
                </form>
            </div>
        </div>
    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="jenisbantuan" class="table table-bordered cell-border ajaxTable datatable-jenisbantuan" style="width:100%">
                <thead>
                    <tr>
                        
                        <th class="center">{{ trans('cruds.jenisbantuan.no') }}</th>
                        <th>{{ trans('cruds.jenisbantuan.title') }}</th>
                        <th>{{ trans('cruds.status.title') }}</th>
                        <th>{{ trans('cruds.status.action') }}</th>
                        
                        
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('master.jenisbantuan.create')
    @include('master.jenisbantuan.edit')
    @include('master.jenisbantuan.show')
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

    @include('master.jenisbantuan.js')
    {{-- <script>
        $(document).ready(function() {
            $.ajax({
                url:  '{{ route('jenisbantuan.create') }}',
                method: 'GET',
                dataType: 'json',
                success: function(response){
                    let data = response.map(function(item) {
                        return {
                            id: item.id,
                            text: item.id+' - '+ item.nama,
                        };
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
    </script> --}}
@endpush
