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
    @include('master.provinsi.edit')
    @include('master.provinsi.show')
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

@push('js')
    {{-- @section('plugins.Tabulator', false)  --}}
    {{-- @section('plugins.DatatablesPlugins', true)  --}}
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#editProvinceForm").validate({
                rules: {
                    kode: {
                        required: true,
                        pattern: "[0-9]+",
                        maxlength: 10
                    },
                    nama: {
                        required: true,
                        maxlength: 200
                    }
                },
                messages: {
                    kode: {
                        required: "Kode is required.",
                        pattern: "Please enter only numbers.",
                        maxlength: "Kode cannot exceed 10 characters."
                    },
                    nama: {
                        required: "Nama is required.",
                    }
                }
            });
        });
    </script>
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
                    { "data": "aktif", width:"10%", className: "text-center", orderable: false, searchable : false,
                        "render": function(data, type, row){
                            
                            if (data === 1){
                                return '<div class="icheck-primary d-inline"><input id="aktif_'+row.id+'" data-aktif-id="aktif_'+row.id+'" class="icheck-primary" type="checkbox" disabled checked><label for="aktif_'+row.id+'"></label></div>';
                                // return '☑️';
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
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#provinsiForm").submit(function (event) {
                event.preventDefault();
                var formData = $(this).serialize();
                var provinsi_form = $('#addProvinsi');
                $.ajax({
                    method: "POST",
                    url: '{{ route('provinsi.store')}}', // Get form action URL
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success"
                            });
                            // $('#addProvinsi').modal('hide');

                            $('#provinsi').DataTable().ajax.reload(function() {
                                provinsi_form.modal('fade');
                                $('#provinsiForm').find('form').trigger('reset');
                            });


                        } else if (response.status === 400) {
                            try {
                                const errorData = JSON.parse(response.responseText);
                                const errors = errorData.errors; // Access the error object
                                let errorMessage = "";
                                    for (const field in errors) {
                                        errors[field].forEach(error => {
                                        errorMessage += `* ${error}\n`; // Build a formatted error message string
                                        });
                                    }

                                    Swal.fire({
                                        title: "Error!",
                                        text: errorMessage,
                                        icon: "error"
                                    });
                                } catch (error) {
                                console.error("Error parsing error response:", error);
                                Swal.fire({
                                    title: "Error!",
                                    text: "An unexpected error occurred. Please try again later.",
                                    icon: "error"
                                });
                                }
                            } else {
                                // Handle other errors
                                console.error("Unexpected response:", response);
                                Swal.fire({
                                    title: "Error!",
                                    text: response.message,
                                    icon: "error"
                                });
                                $('#addProvinsi').modal('hide');
                              }
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred during submission.',
                                icon: 'error'
                            });
                        }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.edit-province-btn').click(function(e) {
                e.preventDefault(); // Prevent default form submission
                //data-province-id
                let provinceId = $(this).data('province-id');
                console.log(provinceId);


                // Make Ajax request to fetch province data for editing
                $.ajax({
                    url: '{{ route('provinsi.edit', ':id') }}'.replace(':id', provinceId), // Route with ID placeholder
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        $('#editkode').val(response.kode);  // Set form field value
                        $('#editnama').val(response.nama);
                        $('#editaktif').val(response.aktif);

                        $('#editProvinceForm').valid()
                        $('#editProvinceModal').modal('show'); // Show the modal
                    },
                    error: function(error) {
                        console.error("Error fetching Provinsi data:", error);
                        // Handle errors appropriately (e.g., display error message)
                        swal.fire({
                            text: "Error",
                            message: "Failed to fetch data",
                            icon: "error"
                        });
                    }
                });
            });
        });

    </script>
    <script>
    $(document).ready(function() {
        $('#provinsi tbody').on('click', '.view-province-btn', function(e) {
            e.preventDefault(); // Prevent default form submission
            let provinceId = $(this).data('province-id');
            let action = $(this).data('action')

            // console.log(provinceId);
            $.ajax({
                url: '{{ route('provinsi.show', ':id') }}'.replace(':id', provinceId), // Route with ID placeholder
                method: 'GET',
                dataType: 'json',
                success: function(response) {

                    let data = response || [];

                    if (action === 'view') {
                        $("#show-kode").text(data.kode);
                        $("#show-nama").text(data.nama);
                        $("#show-aktif").prop("checked", data.aktif === 1);
                        $('#showProvinceModal').modal('show');
                    } else {
                        swal.fire({
                            text: "Error",
                            message: "Failed to fetch data",
                            icon: "error"
                        });
                    }
                }
            })
        });
    });
</script>


@endpush
