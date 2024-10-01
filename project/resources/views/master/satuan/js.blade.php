<script>
    $(document).on('click', '#show-aktif, [id^="aktif_"]', function(e) {
        e.preventDefault();
        // $(".btn-tool").trigger('click');
    });

    //initialize satuan datatable
    $(document).ready(function() {
        $('#satuan_list').DataTable({
            responsive: true, processing: true, serverSide: true, deferRender: true, stateSave: true,
            ajax: {
                url : "{{ route('satuan.api') }}",
                type: "GET",
                dataType: 'JSON',
            },
            columns: [
                {data: 'DT_RowIndex', name: 'No.', orderable: false, searchable: false, className: "text-center", width: "5%"},
                {
                    data: "nama", orderable: true, searchable: true, width: "70%"
                },
                {
                    data: "status", className: "text-center", orderable: false, searchable: false, width: "5%",
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            return data;
                        }
                        return data;
                    }
                },
                {data: "action", className: "text-center", orderable: false, searchable: false, width: "20%",}
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
                                // format: {
                                //     body: function (data, row, column, node) {
                                //         if (column === 2) {
                                //             return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                //         }
                                //         return data;
                                //     }
                                // }
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
            lengthMenu: [5, 10 ,25, 50, 100],
        });

        //EDIT & VIEW TARGET satuan FORM (MODAL)
        $('#satuan_list tbody').on('click', '.edit-satuan-btn, .view-satuan-btn', function(e){
            e.preventDefault();
            let action      = $(this).data('action');
            let satuan_id   = $(this).data('satuan-id');
            let url_show    = '{{ route('satuan.show', ':id') }}'.replace(':id',satuan_id);
            let url_edit    = '{{ route('satuan.edit', ':id') }}'.replace(':id',satuan_id);
            let url_update  = '{{ route('satuan.update', ':id') }}'.replace(':id',satuan_id);

            if(action === "edit"){
                $.ajax({
                    url: url_edit,
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function(){
                        Toast.fire({
                            icon: "info", title: "Processing...", timer: 300, timerProgressBar: true,
                        });
                    },
                    success: function(data) {
                        setTimeout(() => {
                            resetForm();
                            $('#EditSatuanForm').trigger('reset');
                            $('#EditSatuanForm').attr('action', url_update);;
                            $('#satuan_id').val(data.id);
                            $('#edit_nama').val(data.nama);

                            $('#edit_aktif').prop('checked', data.aktif == 1);
                            $('#status').text(data.aktif === 1 ? "{{ __('cruds.status.aktif') }}" : "{{ __('cruds.status.tidak_aktif') }}");
                            $('#EditSatuanModal .modal-title').html(`<i class="fas fa-pencil-alt"></i> {{ __('global.edit') }} ${data.nama}`);
                            $('#EditSatuanModal').modal('show');
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
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        html: errorMessage,
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
                            // console.log('Tombol Update Disable Attribut Di Hapuskan');
                        }, 500);
                    }
                });
            }
            if(action === "view"){
                $.ajax({
                    url: url_show,
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function(){
                        Toast.fire({icon: "info",title: "Processing...",timer: 300,timerProgressBar: true,});
                    },
                    success: function(data) {
                        setTimeout(() => {
                            $('#view_nama').text(data.nama);
                            // $('#view_nama').text(data.keterangan);
                            if (data.aktif === 1) {
                                $('#aktif_show').val(data.aktif);
                                $("#aktif_show").prop("checked",true);
                            } else {
                                $('#aktif_show').val(0);
                                $("#aktif_show").prop("checked",false);
                            }
                            $('#status').text(data.aktif === 1? "{{ __('cruds.status.aktif') }}" : "{{ __('cruds.status.tidak_aktif') }}");
                            $('#showSatuanModal .modal-title').html(`<i class="fas fa-folder-open"></i> {{ __('global.view') }} ${data.nama}`);
                            $('#showSatuanModal').modal('show');
                        }, 500)
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
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        html: errorMessage,
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
                return
            }
        });

        //UPDATE PARTNER DATA FROM EDIT MODAL
        $('#EditSatuanForm').on('submit', function(e){
            e.preventDefault();
            if (!$(this).valid()) {
                return;
            }
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            let formData = $(this).serialize();
            let url = $(this).attr('action');
            // console.log(formData);

            $.ajax({
                url: url,
                method: 'PUT',
                data: formData,
                dataType: 'json',
                beforeSend: function(e){
                    Toast.fire({
                        icon: "info", title: "Processing...", timer: 300, timerProgressBar: true,
                    });
                },
                success: function(partner) {
                    setTimeout(() => {
                        if(partner.success === true) {
                            Swal.fire({
                                title: "{{ __('global.success') }}",
                                text: partner.message,
                                icon: "success",
                                timer: 1500,
                                timerProgressBar: true,
                                didOpen: ()=>{
                                    Swal.showLoading();
                                },
                            });
                            $(this).trigger('reset');
                            $('#EditSatuanForm').find('button[type="submit"]').removeAttr('disabled');
                            $('#satuan_list').DataTable().ajax.reload();
                            $('#EditSatuanModal').modal('hide');
                            resetForm()
                        }
                    }, 300)
                },
                complete: function(){
                    setTimeout(() => {

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
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    html: errorMessage,
                                });
                                $(this).find('button[type="submit"]').removeAttr('disabled');
                            });
                            errorMessage += '</ul>';
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                    $('#EditSatuanForm').find('button[type="submit"]').removeAttr('disabled');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                    });
                },

            });
        });

        //ADD PARTNER FORM
        $("#AddSatuanForm").on('submit', function(e){
            e.preventDefault();
            if (!$(this).valid()) {
                return;
            }

            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            let formData = $(this).serialize();
            let url = "{{ route('satuan.store') }}";
            console.log(formData);
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
                                timer: 1000,
                                timerProgressBar: true,
                            });
                            $(this).trigger('reset');
                            $('#satuan_list').DataTable().ajax.reload();
                            $('#AddSatuanForm')[0].reset();
                            $(".btn-tool").trigger('click');
                            resetForm();
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
                    $('#AddSatuanForm').find('button[type="submit"]').removeAttr('disabled');
                },
                complete: function() {
                    setTimeout(() => {
                        $('#AddSatuanForm').find('button[type="submit"]').removeAttr('disabled');
                        // console.log('Tombol Update Disable Attribut Di Hapuskan');
                    }, 500);

                }
            });

        });

        //VALIDATE FORMS
        $(function(){
            const AddDataValidator = $("#AddSatuanForm").validate({
                rules: {
                    nama: { required: true, maxlength: 200, minlength: 2},
                },
                messages: {
                    nama: {
                        required    : "Satuan Name is required",
                        maxlength   : "Satuan Name cannot be more than 200 characters",
                        minlength   : "Satuan Name must be more than 2 characters",
                    },
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
                        }
                    },
            });
            const EditDataValidator = $("#EditSatuanForm").validate({
                rules: {
                    nama: { required: true, maxlength: 200, minlength: 3},
                },
                messages: {
                    nama: {
                        required    : "Satuan Name is required",
                        maxlength   : "Satuan Name cannot be more than 200 characters",
                        minlength   : "Satuan Name must be more than 3 characters",
                    },
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
                        }
                },
            });
        });

        function resetForm() {
            $('#AddSatuanForm, #EditSatuanForm').trigger('reset'); // Reset the form fields
            $('#AddSatuanForm, #EditSatuanForm').find('.is-invalid').removeClass('is-invalid'); // Remove invalid classes
            $('#AddSatuanForm, #EditSatuanForm').find('.is-valid').removeClass('is-valid'); // Remove valid classes
            $('#AddSatuanForm, #EditSatuanForm').find('.invalid-feedback').remove(); // Remove error messages
            $('#AddSatuanForm, #EditSatuanForm').find('.error').remove();
            $('#AddSatuanForm, #EditSatuanForm').find('button[type="submit"]').removeAttr('disabled');
        }
    });
</script>
