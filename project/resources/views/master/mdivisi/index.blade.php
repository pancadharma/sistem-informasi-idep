@extends('layouts.app')

@section('subtitle', __('cruds.mdivisi.list'))
@section('content_header_title', __('cruds.mdivisi.list'))

@section('content_body')

    @include('master.mdivisi.create')

    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="mdivisi_list"
                   class="table table-bordered table-striped ajaxTable"
                   style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>{{ trans('cruds.mdivisi.nama') }}</th>
                        <th width="10%">{{ trans('cruds.status.title') }}</th>
                        <th width="15%">{{ trans('global.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('master.mdivisi.edit')
    @include('master.mdivisi.show')

@stop

@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @include('master.mdivisi.js')
@endpush