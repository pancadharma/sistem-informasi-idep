@extends('layouts.app')

@section('subtitle', __('cruds.provinsi.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.provinsi.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.provinsi.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    {{-- <div class="row mb-2">
        <div class="col-lg-6">
            <x-adminlte-button label="{{ trans('global.add') }} {{ trans('cruds.provinsi.title_singular') }}" data-toggle="modal" data-target="#addProvinsi" class="bg-success"/>
        </div>
    </div> --}}

    <div class="card card-primary collapsed-card">
        <div class="card-header">
            {{ trans('global.create')}} {{trans('cruds.provinsi.title')}}
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="handleSubmit" id="provinsiForm" action="{{ route('provinsi.store')}}" method="POST" class="resettable-form">
                @csrf
                  @method('POST')
                  <div class="form-group">
                    <label for="kode">{{ trans('cruds.form.kode') }} {{ trans('cruds.provinsi.title') }}</label>
                    <input type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required pattern="[0-9]+" maxlength="2">
                  </div>
                  <div class="form-group">
                    <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                    <input type="text" id="nama" name="nama" class="form-control" pattern="^[A-Za-z][A-Za-z0-9]{1,}$" required maxlength="200">
                  </div>
                  <div class="form-group">
                  <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.provinsi.title') }}</strong>
                      <div class="icheck-primary">
                          <input type="checkbox" name="aktif" id="aktif" {{ old('aktif') == 1 ? 'checked' : '' }} value="1">
                          <label for="aktif"></label>
                    </div>
                    {{-- <input type="checkbox" name="aktif" id="aktif" {{ old('aktif', 0) == 1 || old('aktif') === null ? 'checked' : '' }} value="1"> --}}
                    {{-- <input type="checkbox" name="aktif" id="aktif" value="1" > --}}
                  </div>
                  {{-- <button type="submit" class="btn btn-success float-right" @disabled($errors->isNotEmpty())><i class="fas fa-save"></i> {{ trans('global.submit') }}</button> --}}
                  <button type="submit" class="btn btn-success float-right btn-add-provinsi" data-toggle="tooltip" data-placement="top" title="{{ trans('global.submit') }}"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
               
              </form>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-body table-responsive">
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
    {{-- @include('master.provinsi.create') --}}
    @include('master.provinsi.edit')
    @include('master.provinsi.show')
    
    
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Validation', true)
     {{-- AJAX CALL CREATE FORM --}}
     @include('master.provinsi.js')
   

    {{-- Ajax Request data using server side data table to reduce large data load --}}
    <script>
        $(document).ready(function() {
            $('#provinsi').DataTable({
                responsive: true,
                ajax: "{{ route('provinsi.data') }}",
                processing: true,
                serverSide: true,
                stateSave: true,
                "columns": [{
                        "data": "kode",
                        width: "10%",
                        className: "text-center"
                    },
                    {
                        "data": "nama",
                        width: "40%"
                    },
                    {
                        "data": "aktif",
                        width: "10%",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        "render": function(data, type, row) {

                            if (data === 1) {
                                return '<div class="icheck-primary d-inline"><input id="aktif_' + row.id + '" data-aktif-id="aktif_' + row.id + '" class="icheck-primary" title="{{ __("cruds.status.aktif") }}" type="checkbox" disabled checked><label for="aktif_' + row.id + '"></label></div>';// return '☑️';
                            } else {
                                return '<div class="icheck-primary d-inline"><input id="aktif_' + row.id + '" data-aktif-id="aktif_' + row.id + '" class="icheck-primary" title="{{ trans('cruds.status.tidak_aktif')}}" type="checkbox" disabled><label for="aktif_' + row.id + '"></label></div>';
                            }
                        }
                    },
                    {
                        "data": "action",
                        width: "10%",
                        className: "text-center",
                        orderable: false
                    }
                ],
                layout: {
                    topStart: {
                        buttons: [
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: [0, 1,]
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: [0, 1, ]
                                }
                            },{
                                extend: 'pdf',
                                exportOptions: {
                                    columns: [0, 1,]
                                }
                            },{
                                extend: 'copy',
                                exportOptions: {
                                    columns: [0, 1,]
                                }
                            },
                            'colvis',
                        ],
                    },
                    bottomStart: {
                        pageLength: 5,
                    }
                },
                order: [
                    [2, 'asc']
                ],
                lengthMenu: [5, 25, 50, 100, 500],
            });
        });
    </script>

    

    {{-- AJAX CALL EDIT FORM--}}
    <script>
        $(document).ready(function() {
            $('#editaktif').change(function() {
                $('#edit-aktif').val(this.checked ? 1 : 0);
            });

            $('#editProvinceForm').submit(function(e){
                e.preventDefault();

                let id_prov = $('#id').val();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('provinsi.update', ':id_p') }}'.replace(':id_p', id_prov),
                    method: 'PUT',
                    dataType: 'JSON',
                    data: formData,
                    success:function(response){
                        if (response.status === 'success') {
                            Swal.fire({
                                title: "Success",
                                html: response.message,
                                icon: "success"
                            });

                            $('#editProvinceModal').modal('hide');
                            $('#editProvinceForm').trigger('reset');
                            $('#provinsi').DataTable().ajax.reload();
                        }else{
                            console.log(response.status);
                            Swal.fire({
                                title: 'Unable to Update Data !',
                                html: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(jqXHR) {
                        const errors = JSON.parse(jqXHR.responseText).errors;
                        const errorMessage = Object.values(errors).flat().map(error => `<p>* ${error}</p>`).join('');
                        Swal.fire({
                            title: jqXHR.statusText,
                            html: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            });

            $('#provinsi tbody').on('click', '.edit-province-btn', function(e) {
                let id_provinsi = $(this).data('provinsi-id');
                let action = $(this).data('action');
                // Make Ajax request to fetch province data for editing
                $.ajax({
                    url: '{{ route('provinsi.getedit', ':id') }}'.replace(':id', id_provinsi), // Route with ID placeholder

                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let newActionUrl = '{{ route('provinsi.update', ':id') }}'.replace(':id', response.id);
                        $("#editProvinceForm").trigger('reset');
                        $("#editProvinceForm").attr("action", newActionUrl);
                        $('#id').val(response.id);
                        $('#editkode').val(response.kode);
                        $('#editnama').val(response.nama);

                        if (response.aktif === 1) {
                            $('#edit-aktif').val(response.aktif);
                            $("#editaktif").prop("checked", true); // Set checked to true if value is 1
                            } else {
                            $('#edit-aktif').val(0);
                            $("#editaktif").prop("checked", false); // Set checked to false if value is not 1
                        }
                        $('#editProvinceModal').modal('show'); // Show the modal
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        const errorData = JSON.parse(jqXHR.responseText);
                        const errors = errorData.errors; // Access the error object
                        let errorMessage = "";
                        for (const field in errors) {
                            errors[field].forEach(error => {
                                errorMessage += `* ${error}\n`; // Build a formatted error message string
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
        });

    </script>
    {{-- AJAX CALL DETAILS --}}
    <script>
       $(document).ready(function() {
           $('#provinsi tbody').on('click', '.view-province-btn', function(e) {
               e.preventDefault();
               let provinceId = $(this).data('province-id');
               let action = $(this).data('action')
               $.ajax({
                   url: '{{ route('provinsi.show', ':id') }}'.replace(':id', provinceId), // Route with ID placeholder
                   method: 'GET',
                   dataType: 'json',
                   success: function(response) {
                       let data = response || [];
                       if (action === 'view') {
                           $("#show-kode").text(data.kode);
                           $("#show-nama").text(data.nama);
                        //    $("#show-aktif").prop("checked", data.aktif === 1 );
                           if (data.aktif === 1) {
                                $('#show-aktif').val(data.aktif);
                                $("#show-aktif").prop("checked", true); // Set checked to true if value is 1
                            } else {
                                $('#show-aktif').val(0);
                                $("#show-aktif").prop("checked", false); // Set checked to false if value is not 1
                            }

                           $('#showProvinceModal').modal('show');
                       }
                       else{
                           Swal.fire({
                               text: "Error",
                               message: "Failed to fetch data",
                               icon: "error"
                           });
                       }
                   },
                   error: function(jqXHR, textStatus, errorThrown) {
                       console.error("AJAX Error:", textStatus, errorThrown);
                       Swal.fire({
                           title: 'Error!',
                           text: 'An error occurred during data fetch',
                           icon: 'error'
                       });
                   }
               });
           });
       });
   </script>
@endpush
