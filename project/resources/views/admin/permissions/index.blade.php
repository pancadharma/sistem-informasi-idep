@extends('layouts.app')

@section('subtitle', __('Daftar Hak Akses'))
@section('content_header_title', __('Daftar Hak Akses'))
@section('sub_breadcumb', __('Daftar Hak Akses'))

@section('content_body')

<div class="row mb-2">
    <div class="col-lg-12">
        <x-adminlte-button label="{{ __('global.add') }} {{ __('cruds.permission.title_singular') }}" data-toggle="modal" data-target="#permission-modal" class="bg-success" id="add-permission-btn"/>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h2 class="card-title">{{ __('Daftar Hak Akses') }}</h2>
    </div>
    <div class="card-body table-responsive">
        <table id="permissions-table" class="table table-bordered table-striped table-hover row-border display compact responsive nowrap datatable-Permission" style="width:100%">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>{{ __('Nama') }}</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<x-adminlte-modal id="permission-modal" title="{{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}" size="lg" theme="teal" icon="fas fa-key" v-centered static-backdrop scrollable>
    <form id="permission-form">
        <input type="hidden" id="permission-id">
        <div class="mb-3">
            <label for="permission-nama" class="form-label">{{ __('Nama') }}</label>
            <input type="text" class="form-control" id="permission-nama" required>
        </div>
    </form>
    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="success" label="{{ trans('global.save') }}" id="save-permission-btn"/>
        <x-adminlte-button theme="danger" label="{{ trans('global.close') }}" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>
@stop

@push('js')
    @section('plugins.Datatables', true)
    <script>
        $(document).ready(function() {
            var table = $('#permissions-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('permissions.index') }}",
                columns: [
                    {
                        "data": null,
                        "nama": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'nama', name: 'nama' },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return '<button class="btn btn-xs btn-warning edit-btn" data-id="' + row.id + '" data-nama="' + row.nama + '">Edit</button> ' +
                                   '<button class="btn btn-xs btn-danger delete-btn" data-id="' + row.id + '">Delete</button>';
                        }
                    }
                ]
            });

            $('#add-permission-btn').on('click', function () {
                $('#permission-form')[0].reset();
                $('#permission-id').val('');
                $('#permission-modal').modal('show');
            });

            $('#permissions-table').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                $('#permission-id').val(id);
                $('#permission-nama').val(nama);
                $('#permission-modal').modal('show');
            });

            $('#save-permission-btn').on('click', function(e) {
                e.preventDefault();
                var id = $('#permission-id').val();
                var nama = $('#permission-nama').val();
                var url = id ? '/permissions/' + id : '/permissions';
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        nama: nama,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: id ? 'Permission updated successfully!' : 'Permission created successfully!',
                            position: 'top-end',
                            timer: 1500,
                        });

                        setTimeout(() => {
                            $('#permission-modal').modal('hide');
                            table.ajax.reload();
                        }, 200);
                    },
                    error: function(xhr) {
                        let errorMessage = 'An unknown error occurred.';
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                        });
                    }
                });
            });

            $('#permissions-table').on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/permissions/' + id,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your permission has been deleted.',
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting the permission.',
                                    'error'
                                );
                                console.error(xhr);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush