<script>
//Ajax Request data using server side data table to reduce large data load --}}
    $(document).ready(function() {
      var table = $('#kategoripendonor').DataTable({
            responsive: true,
            ajax: "{{ route('data.kategoripendonor') }}",
            processing: true,
            serverSide: true,
            // stateSave: true,

        order: [[ 1, 'asc' ]],
            
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
                    data: "nama",
                    width: "10%"
                },
                {
                    data: "aktif",
                    width: "5%",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data === 1) {
                            // return '<span class="badge bg-success">Aktif</span>'
                           return  '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
                                '" class="icheck-primary" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' +
                                row.id + '"></label></div>';
                        } else {
                            //return '<span class="badge bg-danger">Tidak Aktif</span>'
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
                                '" class="icheck-primary" title="{{ __("cruds.status.tidak_aktif") }}" type="checkbox" ><label for="aktif_' +
                                row.id + '"></label></div>';
                        }
                    }
                },
                {
                    data: "action",
                    width: "8%",
                    className: "text-center",
                    orderable: false,
                },

            ],


             layout: {
                topStart: {
                    buttons: [
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },{
                            extend: 'pdf', 
                            exportOptions: {
                                columns: [0, 1, 2]
                            }    
                        },{
                            extend: 'copy',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline">Column visibility</span>',
                            className: 'btn btn-warning',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                    ],
                },
                bottomStart: {
                    pageLength: 5,
                }
            },
            order: [
                [1, 'asc']
            ],
            lengthMenu: [5, 25, 50, 100, 500],
        });


    });

//-------------------------------------------------------------------------
// Form Add / submit ------------------------------------------------------

$(document).ready(function() {
        // Submit form
        $('.btn-add-kategoripendonor').on('click', function(e) {
            e.preventDefault();
            var formDatakategoripendonor = $('#kategoripendonorForm').serialize();
            $.ajax({
                method: "POST",
                url: '{{ route('kategoripendonor.store') }}', // Get form action URL
                data: formDatakategoripendonor,
                dataType: 'json',
                success: function(response) {
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
                        $('#addkategoripendonor').modal('hide');
                        $('#kategoripendonorForm').trigger('reset');
                        $('#kategoripendonor').DataTable().ajax.reload();
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
                        //$('#addkategoripendonor').modal('hide');
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
                                // error message dengan menunjukan nama field
                                // errorMessage += `<li>${field}: ${err}</li>`;
                                
                                // error message tanpa menunjukan nama field
                                errorMessage += `<li>${err}</li>`;
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
        $('#kategoripendonor tbody').on('click', '.edit-kategoripendonor-btn', function(e) {
            e.preventDefault();

            let kategoripendonorId = $(this).data('kategoripendonor-id');
            let newActionUrl = '{{ route('kategoripendonor.update', ':id') }}'.replace(':id', kategoripendonorId);
            $.ajax({
                url: '{{ route('kategoripendonor.edit', ':id') }}'.replace(':id', kategoripendonorId),
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('#id_edit').val(kategoripendonorId);
                    $('#editkategoripendonorModal').modal('show');
                    $("#editkategoripendonorForm").trigger('reset');
                    $("#editkategoripendonorForm").attr("action", newActionUrl);
                    $('#editnama').val(response.nama);
                    if (response.aktif === 1) {
                        $('#edit-aktif').val(response.aktif);
                        $("#editaktif").prop("checked",true); // Set checked to true if value is 1
                    } else {
                        $('#edit-aktif').val(0);
                        $("#editaktif").prop("checked",false); // Set checked to false if value is not 1
                    }
                    
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

        $('#editkategoripendonorForm').submit(function(e) {
            e.preventDefault();

            let idkategoripendonor = $('#id_edit').val();
            let formData = $(this).serialize();
            let url=$(this).attr('action');
            $.ajax({
                //url: '{{ route('kategoripendonor.update', ':id_kategoripendonor') }}'.replace(':id_kategoripendonor', idkategoripendonor),
                url:url,
                method: 'PUT',
                dataType: 'JSON',
                data: formData,
                success: function(response) {
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
                        $('#editkategoripendonorModal').modal('hide');
                        $('#editkategoripendonorForm').trigger('reset');
                        $('#kategoripendonor').DataTable().ajax.reload();
                    } else if(response.status === "error" || response.status === "warning"){
                        console.log(response);
                        console.log(response.status);
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
        $('#kategoripendonor tbody').on('click', '.view-kategoripendonor-btn', function(e) {
            e.preventDefault();
            let kategoripendonorId = $(this).data('kategoripendonor-id');
            let action = $(this).data('action');

            $.ajax({
                url: '{{ route('kategoripendonor.show', ':id') }}'.replace(':id',
                kategoripendonorId), // Route with ID placeholder
                method: 'GET',
                dataType: 'json',
                success: function(response) {

                    let data = response || [];
                    

                    if (action === 'view') {
                        // $("#show-kode").text(data.id);
                        $("#show-nama").text(data.nama);
                        //    $("#show-aktif").prop("checked", data.aktif === 1 );
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked",true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked",false); // Set checked to false if value is not 1
                        }

                        $('#showkategoripendonorModal').modal('show');
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
