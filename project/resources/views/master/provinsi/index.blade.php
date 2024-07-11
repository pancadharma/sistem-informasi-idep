@extends('layouts.app')

@section('subtitle', __('cruds.provinsi.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.provinsi.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.provinsi.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="row mb-2">
        <div class="col-lg-6">
            <x-adminlte-button label="{{ trans('global.add') }} {{ trans('cruds.provinsi.title_singular') }}" data-toggle="modal" data-target="#addProvinsi" class="bg-success"/>
        </div>
    </div>

    {{-- <div class="card-body card-outline card-primary">
        <div id="example-table"></div>
    </div> --}}

    <div class="card card-outline card-primary">
        {{-- <div class="card-header">
            <h2 class="card-title">{{ trans('cruds.provinsi.list') }}</h2>
        </div> --}}
        <div class="card-body">
            <table id="provinsi" class="table table-bordered cell-border ajaxTable datatable-provinsi" style="width:100%">
                <thead>
                    <tr>
                        {{-- <th width="10"></th> --}}
                        <th class="center">{{ trans('cruds.provinsi.kode') }} {{ trans('cruds.provinsi.title') }}</th>
                        <th>{{ trans('cruds.provinsi.nama') }}</th>
                        <th>{{ trans('cruds.status.title') }}</th>
                        <th>{{ trans('cruds.status.action') }}</th>
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
    @section('plugins.DatatablesNew', true) 
    {{-- @section('plugins.DatatablesPlugins', true)  --}}
    @section('plugins.Sweetalert2', true) 
    
    <script>
        $(document).ready(function() {
            $('#provinsi').DataTable({
                responsive: true,
                ajax: "{{ route('provinsi.data') }}",
                processing: true,
                serverSide: true,
                stateSave: true,
                "columns": [
                    { "data": "kode", width:"10%", className: "text-center" },
                    { "data": "nama", width:"40%" },
                    { "data": "aktif", width:"10%", className: "text-center", orderable: false,
                        "render": function(data, type, row){
                            if (data === 1){
                                // return '<input class="form-check-input" type="checkbox" disabled checked>';
                                return '☑️';
                            }else{
                                return '<input type="checkbox" disabled>';
                            }
                        }                                
                    },
                    { "data": "action", width: "8%", className: "text-center", orderable: false}
                ],
                layout: {
                    bottomStart: {buttons: ['csv', 'excel', 'pdf', 'copy', 'print', 'colvis']}
                },
                order: [[2, 'asc']],
                pageLength: 5,
                lengthMenu: [ 5, 10, 50, 100, 500],
                // dom: 'Bfrtip',
                // buttons: ["copy", "csv", "excel", "pdf", "print", "colvis",
                //     {   text: 'Reload',
                //         action: function ( e, dt, node, config ) {
                //         dt.ajax.reload()}
                //     },
                // ],
                // scrollCollapse: true,
                // scrollY: '70vh'
            });
        });

        //submit new provinsi
        $('#provinsiForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();

            // console.log('data submit');
            // console.log(formData);

            $.ajax({
            // url: form.attr('action'),
            type: 'POST',
            data: formData,

            success: function(response) {
                if (response.success) {
                    // Refresh Datatables after successful submission
                    $('#provinsi').DataTable().ajax.reload();
                    // Show success message (using a library like Toastr)
                    // toastr.success('{{ trans('global.success') }}');
                    console.log(response)
                    // Close the modal
                    $('#addProvinsi').modal('hide');
                } else {
                    console.log(data);
                    alert('{{ trans('global.error') }}');
                }
            },
            error: function(error) {
                console.error(error);
                alert('{{ trans('global.error') }}');
            }
        });


        });

    </script>
@endpush