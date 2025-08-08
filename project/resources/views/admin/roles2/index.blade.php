@extends('layouts.app')

@section('subtitle', 'Roles')
@section('content_header_title', 'Roles')
@section('sub_breadcumb', 'Roles')

@section('content_body')

<div class="row mb-2">
    <div class="col-lg-12">
        <x-adminlte-button label="{{ __('global.add') }} Role" data-toggle="modal" data-target="#role-modal" class="bg-success" id="add-role-btn"/>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h2 class="card-title">Roles</h2>
    </div>
    <div class="card-body table-responsive">
        <table id="roles-table" class="table table-bordered table-striped table-hover row-border display compact responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>Name</th>
                    <th>Permissions</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<x-adminlte-modal id="role-modal" title="Add Role" size="lg" theme="teal" icon="fas fa-user-tag" v-centered static-backdrop scrollable>
    <form id="role-form">
        <input type="hidden" id="role-id">
        <div class="mb-3">
            <label for="role-name" class="form-label">Role Name</label>
            <input type="text" class="form-control" id="role-name" required>
        </div>
        <div class="mb-3">
            <label for="permissions-checkboxes" class="form-label">Permissions</label>
            <div class="mb-2">
                <button type="button" class="btn btn-sm btn-secondary" id="select-all-permissions">Select All</button>
                <button type="button" class="btn btn-sm btn-secondary" id="deselect-all-permissions">Deselect All</button>
            </div>
            <div id="permissions-checkboxes" class="row"></div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="success" label="{{ __('global.save') }}" id="save-role-btn"/>
        <x-adminlte-button theme="danger" label="{{ __('global.close') }}" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>

<x-adminlte-modal id="view-permissions-modal" title="View Permissions" size="lg" theme="info" icon="fas fa-eye" v-centered static-backdrop scrollable>
    <div id="permissions-list"></div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="{{ __('global.close') }}" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>
@stop

@push('js')
    @section('plugins.Datatables', true)
    @section('plugins.Select2', false) {{-- Disable Select2 as we are using checkboxes --}}
    @section('plugins.Sweetalert2', true)
    <script>
        $(document).ready(function() {
            console.log('Document ready. Checking modal and form presence:');
            console.log('Is #role-modal found?', $('#role-modal').length > 0);
            console.log('Is #role-form found?', $('#role-form').length > 0);

            var table = $('#roles-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('roles2.index') }}",
                columns: [
                    {
                        "data": null,
                        "name": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'nama', name: 'nama' },
                    { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            function populatePermissionsCheckboxes(selectedPermissions = []) {
                $.ajax({
                    url: "{{ route('roles2.create') }}", // This route returns all available permissions
                    method: 'GET',
                    success: function(allPermissions) {
                        var container = $('#permissions-checkboxes');
                        container.empty();
                        $.each(allPermissions, function(id, nama) {
                            var isChecked = selectedPermissions.includes(parseInt(id));
                            var checkboxHtml =
                                `<div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="${id}" id="permission-${id}" ${isChecked ? 'checked' : ''}>
                                        <label class="form-check-label" for="permission-${id}">
                                            ${nama}
                                        </label>
                                    </div>
                                </div>`;
                            container.append(checkboxHtml);
                        });
                    }
                });
            }

            // Select All / Deselect All functionality
            $('#select-all-permissions').on('click', function() {
                $('#permissions-checkboxes input[type="checkbox"]').prop('checked', true);
            });

            $('#deselect-all-permissions').on('click', function() {
                $('#permissions-checkboxes input[type="checkbox"]').prop('checked', false);
            });

            $('#add-role-btn').on('click', function () {
                var roleForm = $('#role-form');
                if (roleForm.length > 0) {
                    roleForm[0].reset();
                } else {
                    console.error("Error: #role-form not found in DOM when trying to reset in add-role-btn click handler.");
                }
                $('#role-id').val('');
                $('#role-modal .modal-title').text('Add Role');
                populatePermissionsCheckboxes(); // Call without selected permissions for new role
                $('#role-modal').modal('show');
            });

            $('#roles-table').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                var roleForm = $('#role-form');
                if (roleForm.length > 0) {
                    roleForm[0].reset();
                    console.log('Form reset successfully.');
                } else {
                    console.error("Error: #role-form not found in DOM when trying to reset in edit-btn click handler.");
                }
                $.ajax({
                    url: '/roles2/' + id + '/edit',
                    method: 'GET',
                    success: function(data) {
                        $('#role-id').val(data.id);
                        $('#role-name').val(data.nama);
                        $('#role-modal .modal-title').text('Edit Role');

                        var selectedPermissions = data.permissions.map(function(p) { return p.id; });
                        console.log('Data from server for edit:', data);
                        console.log('Selected permissions IDs for edit:', selectedPermissions);
                        populatePermissionsCheckboxes(selectedPermissions); // Pass selected permissions

                        $('#role-modal').modal('show');
                    }
                });
            });

            $('#roles-table').on('click', '.view-permissions-btn', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: '/roles2/' + id + '/edit',
                    method: 'GET',
                    success: function(data) {
                        console.log('Data received for View Permissions modal:', data);
                        var permissionsList = $('#permissions-list');
                        permissionsList.empty();
                        if (data.permissions && data.permissions.length > 0) {
                            var list = '<ul class="list-group list-group-flush">';
                            data.permissions.forEach(function(p) {
                                list += '<li class="list-group-item">' + p.nama + '</li>';
                            });
                            list += '</ul>';
                            permissionsList.html(list);
                        } else {
                            permissionsList.html('No permissions assigned.');
                        }
                        $('#view-permissions-modal').modal('show');
                    }
                });
            });

            $('#save-role-btn').on('click', function(e) {
                e.preventDefault();
                var id = $('#role-id').val();
                var nama = $('#role-name').val();

                var selectedPermissions = [];
                $('#permissions-checkboxes input[type="checkbox"]:checked').each(function() {
                    selectedPermissions.push(parseInt($(this).val()));
                });

                var url = id ? '/roles2/' + id : '/roles2';
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        nama: nama,
                        permissions: selectedPermissions,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: id ? 'Role updated successfully!' : 'Role created successfully!',
                            position: 'top-end',
                            timer: 1500,
                        });
                        console.log('Response from server:', response);

                        Swal.fire({
                            icon: 'success',
                            title: id ? 'Role updated successfully!' : 'Role created successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#role-modal').modal('hide');
                        table.ajax.reload();
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

            $('#roles-table').on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Deleting this role might break system functionality!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/roles2/' + id,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your role has been soft deleted.',
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting the role.',
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
