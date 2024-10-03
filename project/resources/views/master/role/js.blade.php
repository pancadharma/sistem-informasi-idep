<script>
$(document).ready(function(){
    $(document).on('click', '#show-aktif, [id^="aktif_"]', function(e) {
        e.preventDefault();
    });
    var url_permission = "{{ route('roles.permission') }}"
    var placeholder = "{{ __('global.pleaseSelect') }}";

    $('#permissions').select2({
        placeholder: placeholder,
        width: '100%',
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
            url: url_permission,
            method: 'GET',
            delay: 1000,
            processResults: function (data) {
                return {
                    results: data.data.map(function (item) {
                        return {
                            id: item.id,
                            text: item.nama // Mapping 'nama' to 'text'
                        };
                    })
                };
            },
            data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                };
                return query;
            }
        }
    });



    $('#edit_permissions').select2({
        placeholder: placeholder,
        width: '100%',
        allowClear : true
    })
    $('#role_list').DataTable({
        responsive: true,
        ajax: {
            url : "{{ route('roles.api') }}",
            type: "GET",
            dataType: 'JSON',
        },
        processing: true,serverSide: true,deferRender: true,stateSave: true,
        columns: [
            {
                data: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center align-middle", width: "5%",
            },
            {data: "nama", width: "60%", className: "text-left align-middle", orderable: true, searchable: true},
            {data: "status", width: "10%", className: "text-center align-middle", orderable: false, searchable: false},
            {data: "action", width: "10%", className: "text-center align-middle", orderable: false,width: "20%",}
        ],

        layout: {
                topStart: {
                    buttons: [
                        {
                            extend: 'print', text: `<i class="fas fa-print"></i>`, titleAttr: "Print Table Data",
                            exportOptions: {
                                columns: [0, 1, 2], stripHTML: false,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 2) { //select column 2 for column aktif/status to exported still has html render
                                            return $(data).find('input').is(':checked') ? `\u2611` : '\u2610';
                                            // return data;
                                        }
                                        return data;
                                    }
                                },
                            }
                        },
                        {
                            extend: 'excelHtml5', text: `<i class="far fa-file-excel"></i>`, titleAttr: "Export to EXCEL", className: "btn-success",
                            exportOptions: {
                                columns: [0, 1, 2], stripHTML: true,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 2) {
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdfHtml5', text: `<i class="far fa-file-pdf"></i>`, titleAttr: "Export to PDF", className: "btn-danger",
                            orientation: 'portrait',
                            pageSize: 'A4',
                            exportOptions: {
                                columns: [0, 1,2], stripHTML: false,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 2) {
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'copy', text: `<i class="fas fa-copy"></i>`, titleAttr: "Copy",
                            exportOptions: {
                                columns: [0, 1,2], stripHTML: false,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 2) {
                                            return $(data).find('input').is(':checked') ? '✅' : '❌';
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {extend: 'colvis', text: `<i class="fas fa-eye"></i>`, titleAttr: "Select Visible Column", className: "btn-warning"},
                    ],
                },
                bottomStart: {pageLength: 10}
            },
            order: [
                [1, 'asc']
            ],
            lengthMenu: [10, 25, 50, 100],
        });

    $('#role_list tbody').on('click', '.edit-role-btn, .view-role-btn', function (e) {
        e.preventDefault();
        let action    = $(this).data('action');
        let id_role  = $(this).data('role-id');
        let url_show  = '{{ route('roles.show', ':id') }}'.replace(':id',id_role);
        let url_edit  = '{{ route('roles.edit', ':id') }}'.replace(':id',id_role);
        let url_update  = '{{ route('roles.update', ':id') }}'.replace(':id',id_role);

        if(action == 'edit') {
            // alert('edit roles data');
            $.ajax({
                url: url_edit,
                method: 'GET',
                dataType: 'json',
                beforeSend: function(){
                    Toast.fire({icon: "info",title: "Processing...",timer: 300,timerProgressBar: true,});
                },
                success: function(response) {
                    console.log(response);
                    $('#EditRoleForm').trigger('reset');

                    var permissions = response.permissions;
                    var rolePermissions = response.role.permissions.map(function(permission) {
                        return permission.id;
                    });
                    $('#edit_permissions').select2({
                        data: permissions,
                        minimumInputLength: 3,
                        dropdownParent: $('#EditRoleModal'),
                        placeholder: "{{ __('global.pleaseSelect') }}",
                        width: '100%',
                        allowClear: true,
                        ajax: {
                            url: url_permission,
                            method: 'GET',
                            delay: 1000,
                            processResults: function (data) {
                                return {
                                    results: data.data.map(function (item) {
                                        return {
                                            id: item.id,
                                            text: item.nama // Mapping 'nama' to 'text'
                                        };
                                    })
                                };
                            },
                            data: function (params) {
                                var query = {
                                    search: params.term,
                                    page: params.page || 1
                                };
                                return query;
                            }
                        }
                    });
                    if (rolePermissions.length > 0) {
                        $('#edit_permissions').val(rolePermissions).trigger('change');
                    }

                    $('#EditRoleForm').attr('action', url_update);
                    $('#id_role').val(response.role.id);
                    $('#edit_nama').val(response.role.nama);

                    $('#edit_aktif').prop('checked', response.role.aktif == 1);
                    $('#status').text(response.role.aktif == 1 ? 'Active' : 'Not Active');
                    $('#EditRoleModal .modal-title').html(`<i class="fas fa-edit"></i> {{ __('global.edit') }} ${response.role.nama}`);
                    $('#EditRoleModal').modal('show');
                }
            });

        } else if(action == 'view') {
            // alert('show roles data');
            $.ajax({
                url: url_show,
                method: 'GET',
                dataType: 'json',
                beforeSend: function(){
                    Toast.fire({icon: "info",title: "Processing...",timer: 300,timerProgressBar: true,});
                },
                success: function(data) {
                    $('#view_nama').text(data.nama);
                    $('#view_permissions').empty();
                    data.permissions.forEach(permission => {
                        $('#view_permissions').append(`<span class="btn-xs bg-warning">${permission.nama ?? '' }</span> `);
                    });
                    $('#view_users').empty();
                    data.users.forEach(user => {
                        $('#view_users').append(`<span class="btn-xs bg-info">${user.nama ?? '' }</span> `);
                    });
                    if (data.aktif === 1) {
                        $('#aktif_show').val(data.aktif);
                        $("#aktif_show").prop("checked",true);
                    } else {
                        $('#aktif_show').val(0);
                        $("#aktif_show").prop("checked",false);
                    }
                    $('#status').text(data.aktif === 1? "{{ __('cruds.status.aktif') }}" : "{{ __('cruds.status.tidak_aktif') }}");
                    $('#showRoleModal .modal-title').html(`<i class="fas fa-bolt"></i> {{ __('global.view') }} ${data.nama}`);
                    $('#showRoleModal').modal('show');
                }
            });


        }
    });

    //UPDATE USER BUTTON CLICKED with ID BUTTON OF #UpdateUserData
    $('#UpdateRoleData').on('click', function(e){
        e.preventDefault();
        if (!$(this).valid()) {
            return;
        }
        $('#EditRoleForm').find('button[type="submit"]').attr('disabled', 'disabled');
        let formData = $('#EditRoleForm').serialize();
        let url = $('#EditRoleForm').attr('action');
        console.log(formData);
        $.ajax({
            url: url,
            method: 'PUT',
            data: formData,
            dataType: 'json',
            beforeSend: function(){
                Toast.fire({icon: "info", title: "Processing...", timer: 500, timerProgressBar: true});
            },
            success: function(response) {
                setTimeout(() => {
                    if(response.success === true){
                        Swal.fire({
                            title: "{{ __('global.success') }}",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        $('#EditRoleForm')[0].reset();
                        $('#EditRoleForm').trigger('reset');
                        $('#role_list').DataTable().ajax.reload();
                        $('#edit_permission').val(null).trigger('change');
                        $('#EditRoleModal').modal('hide');
                        $(this).trigger('reset');
                    }
                }, 500);
            },
            error: function(xhr, status, error) {
                let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                try {
                    const response = xhr.responseJSON;
                    if (response.errors) {
                        errorMessage += '<br><br><ul style="text-align:left!important">';
                        $.each(response.errors, function(field, messages) {
                            messages.forEach(message => {
                                errorMessage += `<li>${field}: ${message}</li>`;
                                $(`#${field}-error`).removeClass('is-valid').addClass('is-invalid');
                                $(`#${field}-error`).text(message);
                                $(`#${field}`).removeClass('invalid').addClass('is-invalid');
                            });
                        });
                        errorMessage += '</ul>';
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage,
                });
            },
            complete: function() {
                setTimeout(() => {
                    $('#EditRoleForm').find('button[type="submit"]').removeAttr('disabled');
                    // console.log('Tombol Update Disable Attribut Di Hapuskan');
                }, 500);

            }
        });
    });

    //ADD ROLE SUBMIT
    $('#AddRole').on('submit', function(e){
        e.preventDefault();
        if (!$(this).valid()) {
            return;
        }
        $('#AddRole').find('button[type="submit"]').attr('disabled', 'disabled');
        let formData = $('#AddRole').serialize();
        let url = "{{ route('roles.store') }}"
        console.log(formData)

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function(){
                Toast.fire({icon: "info", title: "Processing...", timer: 500, timerProgressBar: true});
            },
            success: function(response) {
                setTimeout(() => {
                    if(response.success === true){
                        Swal.fire({
                            title: "{{ __('global.success') }}",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        $('#role_list').DataTable().ajax.reload();
                        $('#AddRole')[0].reset();
                        $('#AddRole').trigger('reset');
                        $(this).trigger('reset');
                        $('#permission').val(null).trigger('change');
                        $('#AddRole').modal('hide');
                        $(".btn-tool").trigger('click');
                        $('#AddRole').modal('hide');
                        $('#permissions').val(null).trigger('change');
                    }
                }, 500);
            },
            error: function(xhr, status, error) {
                let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                try {
                    const response = xhr.responseJSON;
                    if (response.errors) {
                        errorMessage += '<br><br><ul style="text-align:left!important">';
                        $.each(response.errors, function(field, messages) {
                            messages.forEach(message => {
                                errorMessage += `<li>${field}: ${message}</li>`;
                                $(`#${field}-error`).removeClass('is-valid').addClass('is-invalid');
                                $(`#${field}-error`).text(message);
                                $(`#${field}`).removeClass('invalid').addClass('is-invalid');
                            });
                        });
                        errorMessage += '</ul>';
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage,
                });
            },
            complete: function() {
                setTimeout(() => {
                    $('#AddRole').find('button[type="submit"]').removeAttr('disabled');
                    // console.log('Tombol Update Disable Attribut Di Hapuskan');
                }, 500);

            }
        });
    });

    //VALIDATE FORM
    $(function(){
        const AddUserValidator = $("#AddRole").validate({
            rules: {
                nama: { required: true, maxlength: 100, minlength: 5},
                'permission[]': {required: true,},
            },
            messages: {
                nama: {
                    required: "Role Name is required",
                    maxlength: "Role Name cannot be more than 100 characters"
                },
                'permission[]': {
                    required: "At least one Permission is required",
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
            showErrors: function(errorMap, errorList) {
                this.defaultShowErrors(); // Call the default showErrors first
                if (errorList.length) {
                    $.each(errorList, function(index, error) {
                        const field = error.element.id;
                        const message = error.message;
                        $(`#${field}-error`).removeClass('is-valid').addClass('is-invalid');
                        $(`#${field}-error`).text(message);
                        $(`#${field}`).removeClass('is-valid').addClass('is-invalid');
                    });
                } else {
                    $("#error-summary").remove();
                }
            },
        });

        const EditUserValidator = $("#EditUserForm").validate({
            rules: {
                nama: { required: true, maxlength: 100, minlength: 5},
                'roles[]': {
                    required: true,
                    },
                password_confirmation: {required: false, equalTo: "#edit_password"},
            },
            messages: {
                nama: {
                    required: "Role Name is required",
                    maxlength: "Role Name cannot be more than 100 characters"
                },
                'permission[]': {
                    required: "At least one Permission is required",
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
            showErrors: function(errorMap, errorList) {
                this.defaultShowErrors(); // Call the default showErrors first
                if (errorList.length) {
                    $.each(errorList, function(index, error) {
                        const field = error.element.id;
                        const message = error.message;
                        $(`#${field}-error`).removeClass('is-valid').addClass('is-invalid');
                        $(`#${field}-error`).text(message);
                        $(`#${field}`).removeClass('is-valid').addClass('is-invalid');
                    });
                } else {
                    $("#error-summary").remove();
                }
            },
        });
    });

    //RESET FORM EDIT
    function resetFormEdit() {
        $('#EditRoleForm').trigger('reset'); // Reset the form fields
        $('#EditRoleForm').find('.is-invalid').removeClass('is-invalid'); // Remove invalid classes
        $('#EditRoleForm').find('.is-valid').removeClass('is-valid'); // Remove valid classes
        $('#EditRoleForm').find('.invalid-feedback').remove(); // Remove error messages
        $('#EditRoleForm').find('.error').remove();
    }

    $('#permissions').on('select2:select', function (e) {
        $(this).closest('.form-group').find('.error, .invalid-feedback, .is-invalid').removeClass('is-invalid'); // Adjust the selector as needed
        $(this).removeClass('is-invalid');
        $('#permissions-error').hide();
    });

});

</script>
