@extends('layouts.app')

@section('subtitle', __('cruds.country.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.country.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.country.countries')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

{{-- Content body: main page content --}}

@section('content_body')

{{-- @can('country_create') --}}
    <div class="row mb-2">
        <div class="col-lg-12">
            <x-adminlte-button label="{{ trans('global.add') }} {{ trans('cruds.country.title_singular') }}" data-toggle="modal" data-target="#modalCustom" class="bg-success"/>
            {{-- <a class="btn btn-success" href="{{ route('country.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.country.title_singular') }}
            </a> --}}
        </div>
    </div>
{{-- @endcan --}}

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h2 class="card-title">{{ trans('cruds.country.list') }}</h2>
        </div>
        <div class="card-body table-responsive">
            <table id="example" class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Country" style="width:100%">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.country.title') }}</th>
                        <th>{{ trans('cruds.country.iso1') }}</th>
                        <th>{{ trans('cruds.country.iso2') }}</th>
                        <th>{{ trans('cruds.country.flag') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <x-adminlte-modal id="modalCustom" title="{{ trans('global.add') }} {{ trans('cruds.country.title_singular') }}" size="lg" theme="teal"
icon="fas fa-bell" v-centered static-backdrop scrollable>
<div style="height:800px;">Read the account policies...</div>
<x-slot name="footerSlot">
    <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>
    <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
</x-slot>
</x-adminlte-modal>
@stop

{{-- Example button to open modal --}}
{{-- Custom --}}

{{-- Push extra CSS --}}
@push('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    @section('plugins.Datatables', true)
    <script>
        // new DataTable('#example', {
        //     ajax: {{ $countries }},
        //     processing: true,
        //     serverSide: true
        // });
    </script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                ajax: {
                    "url": "{{ route('country.data') }}",
                    "dataSrc": ""
                },
                columns: [
                    {
                        "data": null,
                        "render": function (data, type, row, meta) {
                            return meta.row + 1; // Return the row number
                        }
                    },
                    { "data": "nama" },
                    { "data": "iso1" },
                    { "data": "iso2" },
                    { "data": "flag",
                        "render": function(data, type, row){
                            return '<img src="/images/flag/'+data+'" />'
                        }

                    }
                ]
            });
        });
    </script>
@endpush

