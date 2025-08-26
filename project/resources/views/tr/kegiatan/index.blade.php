@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.list'))
@section('content_header_title')
    @can('kegiatan_access')
        <a class="btn-success btn" href="{{ route('kegiatan.create') }}" title="{{ __('cruds.kegiatan.add') }}">
            {{ __('global.create') .' '.__('cruds.kegiatan.label') }}
        </a>
    @endcan
@endsection
@section('sub_breadcumb', __('cruds.kegiatan.list'))

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{__('cruds.kegiatan.list')}}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('kegiatan.create') }}`"
                    title="{{ __('global.create') . ' ' . __('cruds.kegiatan.label') }}">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table id="kegiatan-list" class="table table-sm table-hover responsive-table table-bordered datatable-kegiatan" width="100%">
                <thead class="text-wrap text-center align-top">
                    <tr>
                        <th class="text-center align-top" style="width: 5%;" data-orderable="false">{{ __('global.no') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.program.nama') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.kegiatan.nama') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.desa.form.nama') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.kegiatan.tanggalmulai') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.kegiatan.tanggalselesai') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.kegiatan.tempat') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.kegiatan.status') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.kegiatan.status') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.kegiatan.status') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.kegiatan.status') }}</th>
                        <th class="text-center align-middle">{{ __('cruds.kegiatan.status') }}</th>
                        <th class="text-center align-middle">{{ __('global.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap overflow-auto">

                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="exportKegiatanModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="icheck-primary">
                            <input type="radio" id="exportPdf" name="export_format" value="pdf" checked>
                            <label for="exportPdf">PDF</label>
                        </div>
                        <div class="icheck-primary">
                            <input type="radio" id="exportDocx" name="export_format" value="docx">
                            <label for="exportDocx">DOCX</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.cancel') }}</button>
                    <button type="button" class="btn btn-primary confirm-export">Export</button>
                </div>
            </div>
        </div>
    </div>

@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<style>
    .responsive-table {
        overflow-x: visible;
        overflow-y: visible;
    }

    .ellipsis-cell {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px; /* Adjust as needed */
        /* display: block; Or display: block */
    }
</style>
@endpush

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

@include('tr.kegiatan.js')
@endpush
