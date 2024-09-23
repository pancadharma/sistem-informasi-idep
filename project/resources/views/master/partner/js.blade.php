<script>
$(document).on('click', '#show-aktif, [id^="aktif_"]', function(e) {
    e.preventDefault();
});

$(document).ready(function(){
    //initialize partner data lists
    $('#partner_list').DataTable({
        responsive: true, processing: true, serverSide: true, deferRender: true, stateSave: true,
        ajax: {
            url : "{{ route('partners.api') }}",
            type: "GET",
            dataType: 'JSON',
        },
        columns: [
            {data: "nama", orderable: true, searchable: true},
            {data: "status", className: "text-center", orderable: false, searchable: false, width: "5%", },
            {data: "action", className: "text-center", orderable: false, searchable: false, width: "20%",}
        ],
        layout: {
            topStart: {
                buttons: [
                    {extend: 'print', exportOptions: {columns: [0, 1]}},
                    {extend: 'excel',exportOptions: {columns: [0, 1]}},
                    {extend: 'pdf',exportOptions: {columns: [0, 1]}},
                    {extend: 'copy',exportOptions: {columns: [0, 1]}},
                    'colvis',
                ],
            },
            bottomStart: {pageLength: 10}
        },
        order: [
            [0, 'asc']
        ],
        lengthMenu: [5, 10 ,25, 50, 100],
    });

    //ADD PARTNER FORM
    $("#AddPartnerForm").on('submit', function(e){
        e.preventDefault();
        if (!$(this).valid()) {
            return;
        }

        $(this).find('button[type="submit"]').attr('disabled', 'disabled');
        let formData = $(this).serialize();
        let url = "{{ route('partner.store') }}";
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
                        $('#partner_list').DataTable().ajax.reload();
                        $('#permission').val(null).trigger('change');
                        $('#AddPartnerForm')[0].reset();
                        $('#AddPartnerForm').trigger('reset');
                        $('#AddPartnerForm').modal('hide');
                        $(".btn-tool").trigger('click');
                        // $('#AddRole').modal('hide');
                        // $('#permissions').val(null).trigger('change');
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
                $('#AddPartnerForm').find('button[type="submit"]').removeAttr('disabled');
            },
            complete: function() {
                setTimeout(() => {
                    $('#AddRole').find('button[type="submit"]').removeAttr('disabled');
                    // console.log('Tombol Update Disable Attribut Di Hapuskan');
                }, 500);

            }
        });

    });

    //EDIT PARTNER FORM (MODAL)
    $('#partner_list tbody').on('click', '.edit-partner-btn, .view-partner-btn', function(e){
        e.preventDefault();
        let action    = $(this).data('action');
        let id_partner  = $(this).data('partner-id');
        let url_show  = '{{ route('partner.show', ':id') }}'.replace(':id',id_partner);
        let url_edit  = '{{ route('partner.edit', ':id') }}'.replace(':id',id_partner);
        let url_update  = '{{ route('partner.update', ':id') }}'.replace(':id',id_partner);

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
                        resetFormEdit();
                        $('#EditPartnerForm').trigger('reset');
                        $('#EditPartnerForm').attr('action', url_update);;

                        $('#partner_id').val(data.id);
                        $('#edit_nama').val(data.nama);
                        $('#edit_keterangan').val(data.keterangan);

                        $('#status').text(data.aktif === 1 ? "{{ __('cruds.status.aktif') }}" : "{{ __('cruds.status.tidak_aktif') }}");
                        $('#EditPartnerModal .modal-title').html(`<i class="fas fa-pencil-alt"></i> {{ __('global.edit') }} ${data.nama}`);
                        $('#EditPartnerModal').modal('show');
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
                        $('#view_nama').text(data.keterangan);
                        if (data.aktif === 1) {
                            $('#aktif_show').val(data.aktif);
                            $("#aktif_show").prop("checked",true);
                        } else {
                            $('#aktif_show').val(0);
                            $("#aktif_show").prop("checked",false);
                        }
                        $('#status').text(data.aktif === 1? "{{ __('cruds.status.aktif') }}" : "{{ __('cruds.status.tidak_aktif') }}");
                        $('#showPartnerModal .modal-title').html(`<i class="fas fa-folder-open"></i> {{ __('global.view') }} ${data.nama}`);
                        $('#showPartnerModal').modal('show');
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
    $('#EditPartnerForm').on('submit', function(e){
        e.preventDefault();
        if (!$(this).valid()) {
            return;
        }
        $(this).find('button[type="submit"]').attr('disabled', 'disabled');
        let formData = $(this).serialize();
        let url = $(this).attr('action');
        console.log(formData);

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
                        $('#EditPartnerForm').find('button[type="submit"]').removeAttr('disabled');
                        $('#partner_list').DataTable().ajax.reload();
                        $('#EditPartnerModal').modal('hide');
                        resetFormEdit()
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
                $('#EditPartnerForm').find('button[type="submit"]').removeAttr('disabled');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage,
                });
            },

        });
    });


    //VALIDATE FORM (IF USING JQUERY TO ADD)
    $(function(){
        const AddDataValidator = $("#AddPartnerForm").validate({
            rules: {
                nama: { required: true, maxlength: 200, minlength: 5},
            },
            messages: {
                nama: {
                    required: "Partner Name is required",
                    maxlength: "Partner Name cannot be more than 200 characters",
                    minlength: "Partner Name must be more than 3 characters",
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

        const EditDataValidator = $("#EditPartnerForm").validate({
            rules: {
                nama: { required: true, maxlength: 200, minlength: 3},
            },
            messages: {
                nama: {
                    required: "Partner Name is required",
                    maxlength: "Partner Name cannot be more than 200 characters",
                    minlength: "Partner Name must be more than 3 characters",
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

    function resetFormEdit() {
        $('#EditPartnerForm').trigger('reset'); // Reset the form fields
        $('#EditPartnerForm').find('.is-invalid').removeClass('is-invalid'); // Remove invalid classes
        $('#EditPartnerForm').find('.is-valid').removeClass('is-valid'); // Remove valid classes
        $('#EditPartnerForm').find('.invalid-feedback').remove(); // Remove error messages
        $('#EditPartnerForm').find('.error').remove();
        $('#EditPartnerForm').find('button[type="submit"]').removeAttr('disabled');
    }

});






















</script>
