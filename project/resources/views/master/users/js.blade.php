<script>
    $(document).ready(function(){
        $('.select2').select2({
            placeholder: "{{ trans('global.pleaseSelect')}}",
            allowClear: true,
        });
        $(document).on('click', '#show-aktif, [id^="aktif_"]', function(e) {
            e.preventDefault(); //not allow user to click the checkbox for aktif checbox
        });

        // LOAD DATA USERS with ROLES into DATATABLES
        $('#users_list').DataTable({
            ajax: {
                    "url": "{{ route('users.api') }}",
                    "type": "GET",
                    "dataType": "JSON",
            },
            responsive: true,lengthChange: false,
            processing: true,autoWidth: false,serverSide: true,deferRender: true, stateSave: true,
            rowCallback: function(row, data, index) {
                if (index % 2 === 0) {
                    $(row).addClass('even');
                } else {
                    $(row).addClass('odd');
                }
            },
            columns: [
                {data: 'DT_RowIndex', className: "text-center align-middle", orderable: false, searchable: false, width: "5%"},
                {data: "nama", className: "text-left align-middle", width: "15%"},
                {data: "username",width: "10%",className: "text-left align-middle"},
                {data: "email",width: "15%",className: "text-left align-middle"},
                {data: "roles", name: "roles.nama", width: "10%", className: "text-left align-middle", searchable: true, orderable: false,}, //added name:"roles.name" to get eager relation of users to roles
                {data: "status",width: "10%", className: "text-center", orderable: false, searchable: false,}, //change column aktif into status by adding ->addColumn in Controller that send DataTables
                {data: "action", width: "10%", orderable: false, searchable: false}
            ],
            layout: {
                topStart: {
                    buttons: [
                        {
                            extend: 'print', text: `<i class="fas fa-print"></i>`, titleAttr: "Print Table Data",
                            exportOptions: {
                                columns: [0,1,2,3,4,5], stripHTML: false,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 5) { //select column 2 for column aktif/status to exported still has html re4der
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
                                columns: [0,1,2,3,4,5], stripHTML: true,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 5) {
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
                                columns: [0,1,2,3,4,5], stripHTML: false,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 5) {
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
                                columns: [0,1,2,3,4,5], stripHTML: false,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 5) {
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
                [2, 'asc'] // Ensure this matches the index of the `users` column
            ],
            lengthMenu: [5, 10 ,25, 50, 100, 200],
        });


        //CALL VIEW & EDIT MODAL USERS
        $('#users_list tbody').on('click', '.edit-user-btn, .view-user-btn', function (e){
            e.preventDefault();
            let action    = $(this).data('action');
            let id_users  = $(this).data('user-id');
            let url_show  = '{{ route('users.showmodal', ':id') }}'.replace(':id',id_users);
            let url_edit  = '{{ route('users.edit', ':id') }}'.replace(':id',id_users);
            let url_update  = '{{ route('users.update', ':id') }}'.replace(':id',id_users);
            resetFormEdit();
            // if button view clicked
            if(action === "view"){
                $.ajax({
                    url: url_show,
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function(){
                        Toast.fire({icon: "info",title: "Processing...",timer: 300,timerProgressBar: true,});
                    },
                    success: function(data) {
                        $('#view_nama').text(data.nama);
                        $('#view_username').text(data.username);
                        $('#view_email').text(data.email);
                        // $('#view_jabatan').text(data.jabatan.nama); //use to display jabatan if jabatan modul finish
                        $('#view_roles').empty();
                        data.roles.forEach(role => {
                            $('#view_roles').append(`<span class="btn-xs bg-warning">${role.nama}</span> `);
                        });
                        if (data.aktif === 1) {
                            $('#aktif_show').val(data.aktif);
                            $("#aktif_show").prop("checked",true);
                        } else {
                            $('#aktif_show').val(0);
                            $("#aktif_show").prop("checked",false);
                        }
                        $('#status').text(data.aktif === 1? "{{ __('cruds.status.aktif') }}" : "{{ __('cruds.status.tidak_aktif') }}");
                        $('#showUsersModal .modal-title').text(data.nama);
                        $('#showUsersModal').modal('show');
                    }
                });
            }
            // if button edit clicked
            if(action === "edit"){
                $.ajax({
                    url: url_edit,
                    method: 'GET',
                    dataType: 'JSON',
                    beforeSend: function(){
                        Toast.fire({icon: "info", title: "Processing...",timer: 500, timerProgressBar: true,});
                    },
                    success: function(data) {
                        setTimeout(() => {
                            $('#EditUserForm').trigger('reset');
                            $('#EditUserForm').attr('action', url_update);
                            $('#id_user').val(data.id);
                            $('#edit_nama').val(data.nama);
                            $('#edit_username').val(data.username);
                            if (data.username === '' || data.username === null) {
                                $('#edit_username').prop('readonly', false); // Enable editing
                                $('#edit_username').prop('disabled', false); // Enable the field
                            } else {
                                $('#edit_username').prop('readonly', true);  // Disable editing
                                $('#edit_username').prop('disabled', false); // Keep the field enabled, but readonly
                            }

                            $('#edit_email').val(data.email);
                            $('#edit_password').val('');
                            $('#edit_password_confirmation').val('');

                            var selectedRoles = data.roles.map(function(role) {
                                return role.id;
                            });
                            $('#edit_roles').val(selectedRoles).trigger('change');

                            $('#edit_aktif').prop('checked', data.aktif == 1);
                            $('#status').text(data.aktif == 1 ? 'Active' : 'Not Active');
                            // $('#edit_jabatan').val(data.jabatan.nama || '');
                            $('#EditUsersModal .modal-title').text("Edit Data" +data.nama);
                            $('#EditUsersModal').modal('show');
                        }, 400);
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
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    html: errorMessage,
                                });
                            }

                            if (response.message) {
                                errorMessage = response.message;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    html: errorMessage,
                                });
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                        });
                    }
                });
                return false;
            }
        });

        //ADD USER FORM
        $('#AddUserForm').on('submit', function(e){
            e.preventDefault();
            if (!$(this).valid()) {
                return;
            }
            let formData = $(this).serialize();
            let url = $(this).attr('action');
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function(){
                    Toast.fire({icon: "info", title: "Processing...",timer: 300, timerProgressBar: true,});
                },
                success: function(response) {
                    if(response.success){
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            },
                        });
                        $('#AddUserForm')[0].reset();
                        $('#AddUserForm').trigger('reset');
                        $(this).trigger('reset');
                        $(".btn-tool").trigger('click');
                        $('#users_list').DataTable().ajax.reload();
                        $('#AddUserModal').modal('hide');
                        $('#roles').val(null).trigger('change');
                    }else{
                        Toast.fire({icon: "error", title: response.message});
                    }
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
                }
            });
        });

        //UPDATE USER BUTTON CLICKED with ID BUTTON OF #UpdateUserData
        $('#UpdateUserData').on('click', function(e){
            e.preventDefault();
            if (!$(this).valid()) {
                return;
            }
            $('#EditUserForm').find('button[type="submit"]').attr('disabled', 'disabled');
            let formData = $('#EditUserForm').serialize();
            let url = $('#EditUserForm').attr('action');

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
                            $('#EditUserForm')[0].reset();
                            $('#EditUserForm').trigger('reset');
                            $('#users_list').DataTable().ajax.reload();
                            $('#edit_roles').val(null).trigger('change');
                            $('#EditUsersModal').modal('hide');
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
                        $('#EditUserForm').find('button[type="submit"]').removeAttr('disabled');
                        console.log('Tombol Update Disable Attribut Di Hapuskan');
                    }, 500);

                }
            });

        });

        //VALIDATE FORM
        $(function(){
            const AddUserValidator = $("#AddUserForm").validate({
                rules: {
                    nama: { required: true, maxlength: 100, minlength: 5},
                    username: {
                        required: true, maxlength: 50, minlength: 5 ,
                        remote: {
                            url: "{{ route('check.username') }}", // Laravel route
                            type: "get",
                            dataType: 'json',
                            data: {
                                _token: "{{ csrf_token() }}",
                                username: function() {
                                    return $('#username').val();
                                }
                            },
                            delay: 3000,
                        }
                    },
                    email: {
                        required: true, email: true, maxlength: 100,
                        remote: {
                            url: "{{ route('check.email') }}", // Laravel route
                            type: "get",
                            dataType: 'json',
                            data: {
                                _token: "{{ csrf_token() }}",
                                email: function() {
                                    return $('#email').val();
                                }
                            },
                            delay: 3000 ,
                        }
                    },
                    password: { required: true, maxlength: 100 },
                    jabatan: { required: true },
                    'roles[]': {
                        required: true,
                        },
                    password_confirmation: {required: true, equalTo: "#password"},
                },
                messages: {
                    nama: {
                        required: "Nama is required",
                        maxlength: "Nama cannot be more than 100 characters"
                    },
                    jabatan: {
                        required: "Jabatan is required",
                    },
                    username: {
                        required: "Username is required",
                        minlength: "Username minimum length must be at least 5 characters",
                        maxlength: "Username cannot be more than 50 characters",
                        remote: "Username already in use"
                    },
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address",
                        maxlength: "Email cannot be more than 100 characters",
                        remote: "Email already in use"
                    },
                    password: {
                        required: "Password is required",
                        minlength: "Password minimal 8 characters",
                        maxlength: "Password cannot be more than 100 characters"
                    },
                    password_confirmation: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    },
                    'roles[]': {
                        required: "At least one role is required",
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
                // submitHandler: function(form) {
                //     AddUserForm()
                // }
            });

            const EditUserValidator = $("#EditUserForm").validate({
                rules: {
                    nama: { required: true, maxlength: 100, minlength: 5},
                    username: {
                        required: true, maxlength: 50, minlength: 5 ,
                        remote: {
                            url: "{{ route('check.username') }}", // Laravel route
                            type: "get",
                            dataType: 'json',
                            data: {
                                _token: "{{ csrf_token() }}",
                                username: function() {
                                    return $('#edit_username').val();
                                },
                                id: function() {
                                    return $('#id_user').val();
                                }
                            },
                            delay: 3000,
                        }
                    },
                    email: {
                        required: true, email: true, maxlength: 100,
                        remote: {
                            url: "{{ route('check.email') }}", // Laravel route
                            type: "get",
                            dataType: 'json',
                            data: {
                                _token: "{{ csrf_token() }}",
                                email: function() {
                                    return $('#edit_email').val();
                                },
                                id: function() {
                                    return $('#id_user').val();
                                }
                            },
                            delay: 3000 ,
                        }
                    },
                    password: { required: false, maxlength: 100 },
                    'roles[]': {
                        required: true,
                        },
                    password_confirmation: {required: false, equalTo: "#edit_password"},
                },
                messages: {
                    nama: {
                        required: "Nama is required",
                        maxlength: "Nama cannot be more than 100 characters"
                    },
                    jabatan: {
                        required: "Jabatan is required",
                    },
                    username: {
                        required: "Username is required",
                        minlength: "Username minimum length must be at least 5 characters",
                        maxlength: "Username cannot be more than 50 characters",
                        remote: "Username already in use"
                    },
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address",
                        maxlength: "Email cannot be more than 100 characters",
                        remote: "Email already in use"
                    },
                    password: {
                        required: "Password is required",
                        minlength: "Password minimal 8 characters",
                        maxlength: "Password cannot be more than 100 characters"
                    },
                    password_confirmation: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    },
                    'roles[]': {
                        required: "At least one role is required",
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
            $('#EditUserForm').trigger('reset'); // Reset the form fields
            $('#EditUserForm').find('.is-invalid').removeClass('is-invalid'); // Remove invalid classes
            $('#EditUserForm').find('.invalid-feedback').remove(); // Remove error messages
            $('#EditUserForm').find('.error').remove();
        }
    });
</script>
