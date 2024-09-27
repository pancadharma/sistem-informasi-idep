<script>
    $(document).ready(function() {
        // INIT DATATABLES
        $('#provinsi').DataTable({
            responsive: true, processing: true, serverSide: true, deferRender: true, stateSave: true,
            ajax: {
                url : "{{ route('provinsi.data') }}",
                type: "GET",
                dataType: 'JSON',
            },
            columns: [
                {
                    data: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center align-middle", width: "5%",
                },
                {
                    data: "kode", width: "10%", className: "align-middle",
                },
                {
                    data: "nama", width: "40%", className: "align-middle",
                },
                {
                    data: "aktif", width: "10%", className: "text-center", orderable: false, searchable: false,
                    render : function(data, type, row) {
                        if (data === 1) {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' + row.id + '" data-aktif-id="aktif_' + row.id + '" class="icheck-primary" title="{{ __("cruds.status.aktif") }}" type="checkbox" disabled checked><label for="aktif_' + row.id + '"></label></div>';// return '☑️';
                        } else {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' + row.id + '" data-aktif-id="aktif_' + row.id + '" class="icheck-primary" title="{{ trans('cruds.status.tidak_aktif')}}" type="checkbox" disabled><label for="aktif_' + row.id + '"></label></div>';
                        }
                    }
                },
                {
                    data: "action", width: "15%", className: "text-center", orderable: false
                }
            ],
            layout: {
                topStart: {
                    buttons: [
                        {
                            extend: 'print', text: `<i class="fas fa-print"></i>`, titleAttr: "Print Table Data",
                            exportOptions: {
                                stripHTML: false,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 3) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0,1,2,3]
                            }
                        },
                        {
                            extend: 'excelHtml5', text: `<i class="far fa-file-excel"></i>`, titleAttr: "Export to EXCEL", className: "btn-success",
                            exportOptions: {
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 3) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0,1,2,3]
                            }
                        },{
                            extend: 'pdfHtml5', text: `<i class="far fa-file-pdf"></i>`, titleAttr: "Export to PDF", className: "btn-danger",
                            exportOptions: {
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 3) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0,1,2,3]
                            }
                        },{
                            extend: 'copy', text: `<i class="fas fa-copy"></i>`, titleAttr: "Copy",
                            exportOptions: {
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 3) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0,1,2,3]
                            }
                        },
                        {extend: 'colvis', text: `<i class="fas fa-eye"></i>`, titleAttr: "Select Visible Column", className: "btn-warning"},
                    ],
                },
                bottomStart: {
                    pageLength: 5,
                }
            },
            order: [
                [2, 'asc']
            ],
            lengthMenu: [5, 25, 50, 100, 500],
        })
        // ADD PROVINSI VALIDATION
        $("#provinsiForm").validate({
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                var formData = $(form).serialize();
                $.ajax({
                    method: "POST",
                    url: '{{ route('provinsi.store') }}', // Get form action URL
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success"
                            });
                            // $('#addProvinsi').modal('hide');
                            $('#provinsiForm').trigger('reset');
                            $('#provinsi').DataTable().ajax.reload();
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
                            // $('#addProvinsi').modal('hide');
                        }
                    },
                    error: function(jqXHR) {
                        const errors = JSON.parse(jqXHR.responseText).errors;
                        const errorMessage = Object.values(errors).flat().map(error => `<p>* ${error}</p>`).join('');
                        Swal.fire({
                            title: jqXHR.statusText,
                            html: errorMessage,
                            icon: 'error'
                        });
                        $('#provinsiForm').trigger('reset');
                    }
                });
            }
        });

        $('#editaktif').change(function() {
            $('#edit-aktif').val(this.checked ? 1 : 0);
        });

        // UPDATE PROVINSI DATA ON SUBMIT
        $('#editProvinceForm').submit(function(e){
            e.preventDefault();

            let id_prov = $('#id').val();
            let formData = $(this).serialize();
            $.ajax({
                url: '{{ route('provinsi.update', ':id_p') }}'.replace(':id_p', id_prov),
                method: 'PUT',
                dataType: 'JSON',
                data: formData,
                success:function(response){
                    if (response.status === 'success') {
                        Swal.fire({
                            title: "Success",
                            html: response.message,
                            icon: "success"
                        });

                        $('#editProvinceModal').modal('hide');
                        $('#editProvinceForm').trigger('reset');
                        $('#provinsi').DataTable().ajax.reload();
                    }else{
                        console.log(response.status);
                        Swal.fire({
                            title: 'Unable to Update Data !',
                            html: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function(jqXHR) {
                    const errors = JSON.parse(jqXHR.responseText).errors;
                    const errorMessage = Object.values(errors).flat().map(error => `<p>* ${error}</p>`).join('');
                    Swal.fire({
                        title: jqXHR.statusText,
                        html: errorMessage,
                        icon: 'error'
                    });
                }
            });
        });

        // CALL EDIT PROVINSI MODAL
        $('#provinsi tbody').on('click', '.edit-provinsi-btn', function(e) {
            let id_provinsi = $(this).data('provinsi-id');
            let action = $(this).data('action');
            // Make Ajax request to fetch provinsi data for editing
            $.ajax({
                url: '{{ route('provinsi.getedit', ':id') }}'.replace(':id', id_provinsi), // Route with ID placeholder

                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    let newActionUrl = '{{ route('provinsi.update', ':id') }}'.replace(':id', response.id);
                    $("#editProvinceForm").trigger('reset');
                    $("#editProvinceForm").attr("action", newActionUrl);
                    $('#id').val(response.id);
                    $('#editkode').val(response.kode);
                    $('#editnama').val(response.nama);

                    if (response.aktif === 1) {
                        $('#edit-aktif').val(response.aktif);
                        $("#editaktif").prop("checked", true); // Set checked to true if value is 1
                        } else {
                        $('#edit-aktif').val(0);
                        $("#editaktif").prop("checked", false); // Set checked to false if value is not 1
                    }
                    $('#editProvinceModal').modal('show'); // Show the modal
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const errorData = JSON.parse(jqXHR.responseText);
                    const errors = errorData.errors; // Access the error object
                    let errorMessage = "";
                    for (const field in errors) {
                        errors[field].forEach(error => {
                            errorMessage += `* ${error}\n`; // Build a formatted error message string
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

        // AJAX VIEW DETAILS MODAL
        $('#provinsi tbody').on('click', '.view-provinsi-btn', function(e) {
            e.preventDefault();
            let provinsiID = $(this).data('provinsi-id');
            let action = $(this).data('action')
            $.ajax({
                url: '{{ route('provinsi.show', ':id') }}'.replace(':id', provinsiID), // Route with ID placeholder
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    let data = response || [];
                    if (action === 'view') {
                        $("#show-kode").text(data.kode);
                        $("#show-nama").text(data.nama);
                    //    $("#show-aktif").prop("checked", data.aktif === 1 );
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked", true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked", false); // Set checked to false if value is not 1
                        }

                        $('#showProvinceModal').modal('show');
                    }
                    else{
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
</script>
