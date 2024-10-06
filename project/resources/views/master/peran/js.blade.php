<script>
    
    // Data Table
    $(document).ready(function() {
        $('#peran-list').DataTable({
            responsive: true,
            ajax: "{{ route('data.peran') }}",  // Update the route for kaitan_sdg data
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
                    return data ? 
                        '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="' + row.id + '" class="icheck-primary" alt="aktif" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' + row.id + '"></label></div>' 
                        : 
                        '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="' + row.id + '" class="icheck-primary" alt="not-aktif" title="{{ __("cruds.status.tidak_aktif") }}" type="checkbox"><label for="aktif_' + row.id + '"></label></div>';
                }},
                { data: "action", width: "8%", className: "text-center", orderable: false }
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

        $('#editPeranForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#id').val();
            let url = '{{ route('peran.update', ':id') }}'.replace(':id', id);
            let formData = $(this).serialize();
            
            $.ajax({
                url: url,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#editPeranModal').modal('hide');
                        $('#peran-list').DataTable().ajax.reload();
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });

    // Insert Data
    $(document).ready(function() {
        // Handle the form submission for Kaitan SDG
        $('.btn-add-peran').on('click', function(e) {
            e.preventDefault();
            var formDataPeran = $('#peranForm').serialize(); // Serialize form data
            $.ajax({
                method: "POST",
                url: '{{ route('peran.store') }}', // Route for storing Kaitan SDG
                data: formDataPeran,
                dataType: 'json',
                success: function(response) {
                    if (response.success === true) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            timer: 500,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        $('#add_peran').modal('hide'); // Hide modal after success
                        $('#peranForm').trigger('reset'); // Reset form fields
                        $('#peran-list').DataTable().ajax.reload(); // Reload DataTable to reflect changes
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

    //View Data
    $(document).ready(function() {
        $('#peran-list tbody').on('click', '.view-peran-btn', function(e) {
            e.preventDefault();
            let peranId = $(this).data('peran-id'); // Get the ID for the kaitan_sdg
            let action = $(this).data('action'); // Get the action, in this case 'view'
            $.ajax({
                url: '{{ route('peran.show', ':id') }}'.replace(':id', peranId), // Route with ID placeholder
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    let data = response || [];
                    if (action === 'view') {
                        $("#show-nama").text(data.nama); // Assuming `nama` is the column name in the kaitan_sdg table

                        // Handle the 'aktif' checkbox (assuming it's also a field in kaitan_sdg)
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked", true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked", false); // Set checked to false if value is not 1
                        }

                        $('#showPeranModal').modal('show'); // Show the modal
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

    // Call Modal Edit
    $(document).ready(function() {
        $('#peran-list tbody').on('click', '.edit-peran-btn', function(e) {
            e.preventDefault();
            let peranId = $(this).data('peran-id'); // Get the ID of the Kaitan SDG
            let newActionUrl = '{{ route('peran.update', ':id') }}'.replace(':id', peranId); // Update the action URL dynamically
            // Make an AJAX call to get the data for the selected Kaitan SDG
            $.ajax({
                url: '{{ route('peran.edit', ':id') }}'.replace(':id', peranId),
                method: 'GET',
                dataType: 'json',
                success: function(response) {

                    $("#editPeranForm").trigger('reset'); // Reset the form fields
                    $("#editPeranForm").attr("action", newActionUrl); // Set the form action URL

                    // Populate the form fields with the response data
                    $('#id').val(response.id);
                    $('#editnama').val(response.nama); // Assuming 'nama' is the column name

                    // Check if 'aktif' is 1 (active) and set the checkbox accordingly
                    if (response.aktif === 1) {
                        $('#edit-aktif').val(response.aktif);
                        $("#editaktif").prop("checked", true);
                    } else {
                        $('#edit-aktif').val(0);
                        $("#editaktif").prop("checked", false);
                    }

                    $('#editPeranModal').modal('show'); // Show the edit modal
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

    // Update Data
    $(document).ready(function() {
        // Handle checkbox change for aktif status in the edit form
        $('#editaktif').change(function() {
            $('#edit-aktif').val(this.checked ? 1 : 0);
        });

        // Handle form submission for updating Kaitan SDG
        $('#editPeranForm').submit(function(e) {
            e.preventDefault();
            let peranId = $('#id').val(); // Get the ID of the Kaitan SDG
            let formData = $(this).serialize(); // Serialize the form data
            // AJAX request for updating Kaitan SDG
            $.ajax({
                url: '{{ route('peran.update', ':id_p') }}'.replace(':id_p', peranId), // Replace the placeholder with the actual ID
                method: 'PUT',
                dataType: 'JSON',
                data: formData,
                success: function(response) {
                    if (response.success === true) {  // Check the `success` key, not `status`
                        Swal.fire({
                            title: "Success",
                            html: response.message,
                            icon: "success",
                            timer: 500,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        $('#editPeranModal').modal('hide'); // Hide the modal
                        $('#editPeranForm').trigger('reset'); // Reset the form
                        $('#peran-list').DataTable().ajax.reload(); // Reload the DataTable
                    } else if (response.status === "error" || response.status === "warning") {
                        Swal.fire({
                            title: 'Unable to Update Data!',
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

</script>