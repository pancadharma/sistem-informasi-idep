@extends('layouts.app')

@section('subtitle', __('cruds.provinsi.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.provinsi.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.provinsi.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="row mb-2">
        <div class="col-lg-12">
            <x-adminlte-button label="{{ trans('global.add') }} {{ trans('cruds.provinsi.title_singular') }}" data-toggle="modal" data-target="#modalCustom" class="bg-success"/>
        </div>
    </div>

    {{-- <div class="card-body card-outline card-primary">
        <div id="example-table"></div>
    </div> --}}

    <div class="card card-body card-outline card-primary">
        <div class="card-header">
            <h2 class="card-title">{{ trans('cruds.provinsi.list') }}</h2>
        </div>
        <div class="card-body">
            <table id="provinsi" class="table table-bordered table-striped table-hover ajaxTable datatable-provinsi" style="width:100%">
                <thead>
                    <tr>
                        {{-- <th width="10"></th> --}}
                        <th>{{ trans('cruds.provinsi.kode') }} {{ trans('cruds.provinsi.title') }}</th>
                        <th>{{ trans('cruds.provinsi.nama') }}</th>
                        <th>{{ trans('cruds.status.title') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('master.provinsi.create')
@stop

@push('css')
@endpush

@push('js')
    {{-- @section('plugins.Tabulator', false)  --}}
    @section('plugins.Datatables', true) 
    @section('plugins.DatatablesPlugins', true) 
    @section('plugins.Sweetalert2', true) 
    
    <script>
        $(document).ready(function() {
            $('#provinsi').DataTable({
                responsive: true,
                ajax: "{{ route('provinsi.data') }}",
                processing: true,
                serverSide: true,
                "columns": [
                    { "data": "kode", width:"20%" },
                    { "data": "nama" },
                    { "data": "aktif",
                        "render": function(data, type, row){
                            if (data === 1){
                                return '<input type="checkbox" checked disabled>';
                            }else{
                                return '<input type="checkbox" disabled>';
                            }
                        }                                
                    }
                ],
                dom: 'Bfrtip',
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                order: [[2, 'asc']],
                lengthMenu: [ 10, 50, 100, 500],
            }).buttons().container().appendTo('#provinsi_wrapper .col-md-6:eq(0)');
        });
    </script>
    {{-- <script>
        var table = new Tabulator("#example-table", {
            height:"80%",
            layout:"fitColumns",
            ajaxURL:"{{ route('provinsi.data') }}",
            pagination:"local",
            paginationSize:10,
            paginationSizeSelector:[5, 10, 25, 50, 100],
            paginationCounter:"rows",
            columns:[
            {title:'{{ __('cruds.provinsi.kode') }}', field:"kode", width:150,hozAlign:"center"},
            {title:'{{ __('cruds.provinsi.title') }}', field:"nama"},
            {title:'{{ __('cruds.status.title') }}', field:"aktif", width:150, hozAlign:"center",
                formatter: function(cell, formatParams){
                    if(cell.getValue() == 1){
                        return "<input type='checkbox' disable checked>"; // Display checked checkbox
                    }else{
                        return "<input type='checkbox'>"; // Display unchecked checkbox
                    }
                }
            },
            ],
        });
    </script> --}}
@endpush