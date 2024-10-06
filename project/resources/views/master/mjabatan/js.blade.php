<script>
    //Ajax Request data using server side data table to reduce large data load
    $(document).ready(function() {
            $('#mjabatan_list').DataTable({
                responsive: true,
                ajax: "{{ route('data.mjabatan') }}",
                processing: true,
                serverSide: true,
                columns: [
                    
                    {
                        data: null, // Use null for the row number
                        width: "5%", // Adjust the width as necessary
                        className: "text-center",
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1; // Calculate the row number
                        }
                    },

                    { data: "nama", width: "10%" },
                    { data: "aktif", width: "5%", className: "text-center", render: function(data, row) {
                        return data ? '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="' + row.id +
                            '" class="icheck-primary" alt="☑️ aktif" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' + row.id + '"></label></div>' 
                            : 
                            '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="' + row.id +
                            '" class="icheck-primary" alt="not-aktif" title="{{ __("cruds.status.tidak_aktif") }}" type="checkbox"><label for="aktif_' +
                            row.id + '"></label></div>';
                    }},
                    { data: "action", width: "8%", className: "text-center", orderable: false },
                ],

                layout: {
                topStart: {
                    buttons: [
                        {
                            text: '<i class="fas fa-print"></i> <span class="d-none d-md-inline">Print</span>',
                            className: 'btn btn-secondary',
                            extend: 'print',
                            exportOptions: {
                                columns: [0, 1, 2] // Ensure these indices match your visible columns
                            }
                        },
                        {
                            text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline">Excel</span>',
                            className: 'btn btn-success',
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            text: '<i class="fas fa-file-pdf"></i> <span class="d-none d-md-inline">PDF</span>',
                            className: 'btn btn-danger',
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> <span class="d-none d-md-inline">Copy</span>',
                            className: 'btn btn-info',
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
                    pageLength: 10,
                }
            },

                order: [[0, 'asc']],
                lengthMenu: [10, 25, 50, 100, 500],
            });

            $('#editMjabatanForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#id').val();
                let url = '{{ route('mjabatan.update', ':id') }}'.replace(':id', id);
                let formData = $(this).serialize();
                
                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#editMjabatanModal').modal('hide');
                            $('#mjabatan_list').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
    });

    // SUBMIT UPDATE FORM - EDIT
    $(document).ready(function() {
        $('#editaktif').change(function() {
            $('#edit-aktif').val(this.checked ? 1 : 0);
        });

        $('#editMjabatanForm').submit(function(e) {
            e.preventDefault();
            let idMjabatan = $('#id').val();
            let formData = $(this).serialize();
            $.ajax({
                url: '{{ route('mjabatan.update', ':id_p') }}'.replace(':id_p', idMjabatan),
                method: 'PUT',
                dataType: 'JSON',
                data: formData,
                success: function(response) {
                    if (response.success === true) {
                        Swal.fire({
                            title: "Success",
                            html: response.message,
                            icon: "success",
                            timer: 500,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            },
                        });
                        $('#editMjabatanModal').modal('hide');
                        $('#editMjabatanForm').trigger('reset');
                        $('#mjabatan').DataTable().ajax.reload();
                    } else if(response.status === "error" || response.status === "warning"){
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

    // AJAX CALL DETAILS & EDIT
    $(document).ready(function() {
        $('#mjabatan_list tbody').on('click', '.view-mjabatan-btn', function(e) {
            e.preventDefault();
            let mjabatanId = $(this).data('mjabatan-id');
            let action = $(this).data('action');

            $.ajax({
                url: '{{ route('mjabatan.show', ':id') }}'.replace(':id', mjabatanId), // Route with ID placeholder
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    let data = response || [];
                    
                    if (action === 'view') {
                        $("#show-nama").text(data.nama);
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked", true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked", false); // Set checked to false if value is not 1
                        }

                        $('#showMjabatanModal').modal('show');
                    } else {
                        Swal.fire({
                            text: "Error",
                            message: "Failed to fetch data",
                            icon: "error"
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred during data fetch',
                        icon: 'error'
                    });
                }
            });
        });
    });

    // Prevent checkbox clicked in show modal
    $(document).ready(function() {
        $('#show-aktif').click(function(event) {
            event.preventDefault();
        });
    });

    // Call modal edit and populate data into modal form
    $(document).ready(function() {
        $('#mjabatan_list tbody').on('click', '.edit-mjabatan-btn', function(e) {
            e.preventDefault();

            let mjabatanId = $(this).data('mjabatan-id');
            let newActionUrl = '{{ route('mjabatan.update', ':id') }}'.replace(':id', mjabatanId);
            $.ajax({
                url: '{{ route('mjabatan.edit', ':id') }}'.replace(':id', mjabatanId),
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $("#editMjabatanForm").trigger('reset');
                    $("#editMjabatanForm").attr("action", newActionUrl);
                    $('#id').val(response.id);
                    $('#editnama').val(response.nama);
                    if (response.aktif === 1) {
                        $('#edit-aktif').val(response.aktif);
                        $("#editaktif").prop("checked", true); // Set checked to true if value is 1
                    } else {
                        $('#edit-aktif').val(0);
                        $("#editaktif").prop("checked", false); // Set checked to false if value is not 1
                    }

                    $('#editMjabatanModal').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while fetching the data',
                        icon: 'error'
                    });
                }
            });
        });
    });

    // Call add mjabatan modal form
    $(document).ready(function() {
        $('.add-mjabatan').on('click', function(e){
            e.preventDefault();
            $.ajax({
                url:  '{{ route('mjabatan.create') }}',
                method: 'GET',
                dataType: 'json',
                success: function(response){
                    // Modal form handling (if required for adding)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: 'ERROR!',
                        text: 'An error occurred while fetching the form',
                        icon: 'error'
                    });
                }
            });
        });

        // Submit form 
        $(document).ready(function() {
            $('.btn-add-mjabatan').on('click', function(e) {
            e.preventDefault();
            var formDataMjab = $('#mjabatanForm').serialize();
            $.ajax({
                method: "POST",
                url: '{{ route('mjabatan.store') }}',
                data: formDataMjab,
                dataType: 'json',
                success: function(response) {
                    if (response.success === true) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            timer: 500,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            },
                        });
                        $('#addMjabatan').modal('hide');
                        $('#mjabatanForm').trigger('reset');
                        $('#mjabatan_list').DataTable().ajax.reload();
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
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                    });
                }
            });
        });
        });
        
    });

</script>