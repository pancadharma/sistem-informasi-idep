@extends('layouts.app')

@section('subtitle', __('cruds.kelompokmarjinal.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.kelompokmarjinal.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.kelompokmarjinal.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="card card-primary">
            <div class="card-header">
                {{ trans('global.create')}} {{trans('cruds.kelompokmarjinal.title')}}
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
                <div class="card-body">
                <form action="{{ route('kelompokmarjinal.store')}}" method="POST" class="resettable-form" id="kelompokmarjinalForm" autocomplete="off">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <label for="nama">{{ trans('cruds.kelompokmarjinal.nama') }} {{ trans('cruds.kelompokmarjinal.title') }}</label>
                        <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
                    </div>
                    <div class="form-group">
                    <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.kelompokmarjinal.title') }}</strong>
                        <div class="icheck-primary">
                            <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                            <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success float-right btn-add-kelompokmarjinal" data-toggle="tooltip" data-placement="top" title="{{ trans('global.submit') }}"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
                </form>



                <script>
                    document.getElementById("kelompokmarjinalForm").addEventListener("submit", function(event) {
                        const checkbox = document.getElementById("aktif");
                        
                        if (!checkbox.checked) {
                            event.preventDefault(); // Prevent form submission
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'You must accept the status!',
                            });
                        }
                    });
                    </script>






            </div>
        </div>
    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="kelompokmarjinal" class="table table-bordered cell-border ajaxTable datatable-kelompokmarjinal" style="width:100%">
                <thead>
                    <tr>
                        
                        <th class="center">{{ trans('cruds.kelompokmarjinal.no') }}</th>
                        <th>{{ trans('cruds.kelompokmarjinal.title') }}</th>
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
@endpush
