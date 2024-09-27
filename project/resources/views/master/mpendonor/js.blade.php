<script>
//Ajax Request data using server side data table to reduce large data load --}}
$(document).ready(function() {
    var table = $('#mpendonor').DataTable({
        responsive: true,
        ajax: "{{ route('data.pendonor') }}",
        processing: true,
        serverSide: true,
        order: [[1, 'asc']],
        columns: [
            {
                data: null, // Ganti "id" dengan null untuk menghitung penomoran
                width: "1%",
                className: "text-center",
                orderable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1; // Menghitung nomor
                }
            },
            {
                data: "mpendonnorkategori.nama",
                name: "mpendonnorkategori.nama",
                width: "10%"
            },
            {
                data: "nama",
                width: "10%"
            },
            {
                data: "pic",
                width: "10%"
            },
            {
                data: "email",
                width: "10%"
            },
            {
                data: "phone",
                width: "10%"
            },
            {
                data: "aktif",
                width: "5%",
                className: "text-center",
                orderable: false,
                searchable: false,
                render: function(data) {
                    return data === 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
                }
            },
            {
                data: "action",
                width: "10%",
                className: "text-center",
                orderable: false,
            },
        ],
        layout: {
            topStart: {
                buttons: [
                    { extend: 'print', exportOptions: { columns: [0, 1, 2, 3] } },
                    { extend: 'excel', exportOptions: { columns: [0, 1, 2, 3] } },
                    { extend: 'pdf', exportOptions: { columns: [0, 1, 2, 3] } },
                    { extend: 'copy', exportOptions: { columns: [0, 1, 2, 3] } },
                    'colvis',
                ],
            },
            bottomStart: {
                pageLength: 5,
            }
        },
        lengthMenu: [5, 25, 50, 100, 500],
    });
});

//-------------------------------------------------------------------------
// Form Add / submit ------------------------------------------------------
//-------------------------------------------------------------------------

$(document).ready(function() {
     // persiapan data select 2  harus di index-----------------
     
    // Submit form---------
        $('.btn-add-mpendonor').on('click', function(e) {
            e.preventDefault();
            var formDatampendonor = $('#mpendonorForm').serialize();
            $.ajax({
                method: "POST",
                url: '{{ route('pendonor.store') }}', // Get form action URL
                data: formDatampendonor,
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    if (response.success === true) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        $('#addmpendonor').modal('hide');
                        $('#mpendonorForm').trigger('reset');
                        $('#mpendonor').DataTable().ajax.reload();
                    } else {
                        var errorMessage = response.message;
                        if (response.status === 400) {
                            try {
                                const errors = JSON.parse(response.responseText).errors;
                                errorMessage = Object.values(errors).flat().map(error => `<p>* ${error}</p>`).join('');
                            } catch (error) {
                                errorMessage = "<p>An unexpected error occurred. Please try again later.</p>";
                            }
                        }
                        Swal.fire({
                            title: "Error!",
                            html: errorMessage,
                            icon: "error"
                        });
                        //$('#addmpendonor').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                        errorMessage = response.message;
                        }

                        if (response.errors) {
                        const errors = response.errors;
                        errorMessage += '<br><br><ul style="text-align:left!important">';
                        for (const field in errors) {
                            if (errors.hasOwnProperty(field)) {
                            errors[field].forEach(err => {
                                errorMessage += `<li>${field}: ${err}</li>`;
                            });
                            }
                        }
                        errorMessage += '</ul>';
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage, // Use 'html' instead of 'text'
                    });
                }
            });
            
        });
    });


//-----------------------------------------------------------
//------panggil modal edit and populate data into modal form
//-----------------------------------------------------------
$(document).ready(function() {
        $('#mpendonor tbody').on('click', '.edit-mpendonor-btn', function(e) {
            e.preventDefault();

            let mpendonorId = $(this).data('mpendonor-id');
            let newActionUrl = '{{ route('pendonor.update', ':id') }}'.replace(':id', mpendonorId);
            $.ajax({
                url: '{{ route('pendonor.edit', ':id') }}'.replace(':id', mpendonorId),
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $("#editmpendonorForm").trigger('reset');
                    $("#editmpendonorForm").attr("action", newActionUrl);
                    $('#id').val(response[0].id);
                    $('#editnama').val(response[0].nama);
                    $('#editpic').val(response[0].pic);
                    $('#editemail').val(response[0].email);
                    $('#editphone').val(response[0].phone);
                    if (response[0].aktif === 1) {
                        $('#edit-aktif').val(response[0].aktif);
                        $("#editaktif").prop("checked",true); // Set checked to true if value is 1
                    } else {
                        $('#edit-aktif').val(0);
                        $("#editaktif").prop("checked",false); // Set checked to false if value is not 1
                    }

                    $('#type_edit').select2();
                    $('#editmpendonorModal').modal('show');
                    
                    let id_kategori   = response[0].mpendonnorkategori.id;
                    let data = response.results.map(function(item) {
                        return {
                            id: item.id,
                            text:item.nama,
                        };
                    });

                    $('#mpendonorkategori_id').select2({
                        dropdownParent: $('#editmpendonorModal'),
                        data : data,
                        placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kategoripendonor.title')}}",
                    });
                    $('#mpendonorkategori_id').val(response[0].mpendonnorkategori.id).trigger('change');
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const errorData = JSON.parse(jqXHR.responseText);
                    const errors = errorData.errors; // Access the error object
                    let errorMessage = "";
                    for (const field in errors) {
                        errors[field].forEach(error => {
                            errorMessage +=
                            `* ${error}\n`; // Build a formatted error message string
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


//---------------------------------------  
//-------------SUBMIT UPDATE FORM - EDIT
//--------------------------------------- 

    $(document).ready(function() {
        $('#editaktif').change(function() {
            $('#edit-aktif').val(this.checked ? 1 : 0);
        });

        $('#editmpendonorForm').submit(function(e) {
            e.preventDefault();
            let idmpendonor = $('#id').val();
            let formData = $(this).serialize();
            let url=$(this).attr('action');
            // console.log('idmpendonor:', idmpendonor);
            // console.log('formData:', formData);
            // console.log('url:', url);
            $.ajax({
                url:url,
                method: 'PUT',
                dataType: 'JSON',
                data: formData,
                success: function(response) {
                    //console.log('Response data:', response.data);
                    if (response.status === 'success') {
                        Swal.fire({
                            title: "Success",
                            html: response.message,
                            icon: "success",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            },
                        });
                        //$('#editmpendonorModal').modal('show');
                        $('#editmpendonorModal').modal('hide');
                        $('#editmpendonorForm').trigger('reset');
                        $('#mpendonorkategori_id').val(null).trigger('change');
                        $('#mpendonor').DataTable().ajax.reload();
                    } else if(response.status === "error" || response.status === "warning"){
                        // console.log(response.status);
                        Swal.fire({
                            title: 'Unable to Update Data !',
                            html: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                    try {
                        const response = xhr.responseJSON;
                        if (response.message) {
                            errorMessage = response.message;
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMessage,
                            });
                        }
                        if (response.errors) {
                            errorMessage += '<br><br><ul style="text-align:left!important">';
                            $.each(response.errors, function(field, messages) {
                                messages.forEach(message => {
                                    errorMessage += `<li>${field}: ${message}</li>`;
                                    $(`#${field}`).removeClass('is-valid').addClass('error is-invalid');
                                    $(`#${field}`).text(message);
                                    $(`#${field}`).removeClass('invalid').addClass('error is-invalid');
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
                }
            });
        });
    });

    // AJAX CALL DETAILS
    $(document).ready(function() {
        $('#mpendonor tbody').on('click', '.view-mpendonor-btn', function(e) {
            e.preventDefault();
            let mpendonorId = $(this).data('mpendonor-id');
            let action = $(this).data('action');

            $.ajax({
                url: '{{ route('pendonor.show', ':id') }}'.replace(':id',
                mpendonorId), // Route with ID placeholder
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    let data = response || [];
                    

                    if (action === 'view') {
                        // $("#show-kode").text(data.id);
                        $("#show-mpendonnorkategori").text(data.mpendonnorkategori.nama);
                        $("#show-nama").text(data.nama);
                        $("#show-phone").text(data.phone);
                        $("#show-pic").text(data.pic);
                        $("#show-email").text(data.email);
                        //    $("#show-aktif").prop("checked", data.aktif === 1 );
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked",true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked",false); // Set checked to false if value is not 1
                        }

                        $('#showmpendonorModal').modal('show');
                    } else {
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

    //prevent checkbox clicked in show modal 
    $(document).ready(function() {
        $('#show-aktif, #aktif_').click(function(event) {
            event.preventDefault();
        });
    });

    
   
</script>
