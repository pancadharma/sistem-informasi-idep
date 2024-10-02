@extends('layouts.app')

@section('subtitle', __('cruds.kategoripendonor.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.kategoripendonor.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.kategoripendonor.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="card card-primary collapsed-card">
            <div class="card-header">
                {{ trans('global.create')}} {{trans('cruds.kategoripendonor.title')}}
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
                <div class="card-body">
                <form action="{{ route('kategoripendonor.store')}}" method="POST" class="resettable-form" id="kategoripendonorForm" autocomplete="off">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <label for="nama">{{ trans('cruds.kategoripendonor.nama') }} {{ trans('cruds.kategoripendonor.title') }}</label>
                        <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                    <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.kategoripendonor.title') }}</strong>
                        <div class="icheck-primary">
                            <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                            <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success float-right btn-add-kategoripendonor" data-toggle="tooltip" data-placement="top" title="{{ trans('global.submit') }}"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
                </form>
            </div>
        </div>
    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="kategoripendonor" class="table table-bordered cell-border ajaxTable datatable-kategoripendonor" style="width:100%">
                <thead>
                    <tr>
                        
                        <th class="center">No.</th>
                        <th>{{ trans('cruds.kategoripendonor.title') }}</th>
                        <th>{{ trans('cruds.status.title') }}</th>
                        <th>{{ trans('cruds.status.action') }}</th>
                        
                        
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    {{-- @include('master.kategoripendonor.create') --}}
    @include('master.kategoripendonor.edit')
    @include('master.kategoripendonor.show')
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

    @include('master.kategoripendonor.js')
@endpush
