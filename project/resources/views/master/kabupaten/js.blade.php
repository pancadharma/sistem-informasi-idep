<script>
    //Ajax Request data using server side data table to reduce large data load --}}
    $(document).ready(function() {
        $('#kabupaten').DataTable({
            responsive: true, processing: true, serverSide: true, deferRender: true, stateSave: false,
            ajax: {
                url : "{{ route('data.kabupaten') }}",
                type: "GET",
                dataType: 'JSON',
            },
            columns: [
                {
                    data: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center align-middle", width: "5%",
                },
                {
                    data: "kode", width: "5%", className: "align-middle text-center"
                },
                {
                    data: "type", width: "5%", className: "align-middle text-center",
                    render: function(data, type, row) {
                        if (data === "kabupaten") {
                            return '{{ trans('cruds.kabupaten.title')}}';
                        } else {
                            return '{{ trans('cruds.kabupaten.kota')}}';
                        }
                    }
                },
                {
                    data: "nama", width: "15%",
                },
                {
                    data: 'provinsi.nama', name: 'provinsi.nama', width: "20%"
                },
                {
                    data: "aktif", width: "5%", className: "text-center align-middle", orderable: false, searchable: false,
                    render: function(data, type, row) {
                        if (data === 1) {
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
                                '" class="icheck-primary" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' +
                                row.id + '"></label></div>';
                        } else {
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
                                '" class="icheck-primary" title="{{ __("cruds.status.tidak_aktif") }}" type="checkbox" ><label for="aktif_' +
                                row.id + '"></label></div>';
                        }
                    }
                },
                {
                    data: "action", width: "10%", className: "align-middle text-center", orderable: false, searchable: false
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
                                        if (column === 5) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2, 3,4,5]
                            }
                        },
                        {
                            extend: 'excelHtml5', text: `<i class="far fa-file-excel"></i>`, titleAttr: "Export to EXCEL", className: "btn-success",
                            exportOptions: {
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 5) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2, 3,4,5]
                            }
                        },{
                            extend: 'pdfHtml5', text: `<i class="far fa-file-pdf"></i>`, titleAttr: "Export to PDF", className: "btn-danger",
                            exportOptions: {
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 5) {
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;
                                    }
                                },
                                columns: [0, 1, 2, 3,4,5]
                            }
                        },{
                            extend: 'copy', text: `<i class="fas fa-copy"></i>`, titleAttr: "Copy",
                            exportOptions: {
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 5) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;
                                    }
                                },
                                columns: [0, 1, 2, 3,4,5]
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
            lengthMenu: [5, 10, 25, ,50, 100, 500],
        });
    });

    // SUBMIT UPDATE FORM - EDIT
    $(document).ready(function() {
        $('#editaktif').change(function() {
            $('#edit-aktif').val(this.checked ? 1 : 0);
        });

        $('#editKabupatenForm').submit(function(e) {
            e.preventDefault();

            let idKabupaten = $('#id').val();
            let formData = $(this).serialize();
            $.ajax({
                url: '{{ route('kabupaten.update', ':id_p') }}'.replace(':id_p', idKabupaten),
                method: 'PUT',
                dataType: 'JSON',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: "Success",
                            html: response.message,
                            icon: "success",
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            },
                        });
                        $('#editKabupatenModal').modal('hide');
                        $('#editKabupatenForm').trigger('reset');
                        $('#provinsi_id').val(null).trigger('change');
                        $('#kabupaten').DataTable().ajax.reload();
                    } else if(response.status === "error" || response.status === "warning"){
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

    // AJAX CALL DETAILS & EDIT
    $(document).ready(function() {
        $('#kabupaten tbody').on('click', '.view-kabupaten-btn', function(e) {
            e.preventDefault();
            let kabupatenId = $(this).data('kabupaten-id');
            let action = $(this).data('action');

            $.ajax({
                url: '{{ route('kabupaten.show', ':id') }}'.replace(':id',
                kabupatenId), // Route with ID placeholder
                method: 'GET',
                dataType: 'json',
                success: function(response) {

                    let data = response || [];


                    if (action === 'view') {
                        $("#show-kode").text(data.kode);
                        $("#show-nama").text(data.nama);
                        $("#show-provinsi").text(data.provinsi.nama);
                        //    $("#show-aktif").prop("checked", data.aktif === 1 );
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked",true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked",false); // Set checked to false if value is not 1
                        }

                        $('#showKabupatenModal').modal('show');
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
    //call modal edit and populate data into modal form
    $(document).ready(function() {
        $('#kabupaten tbody').on('click', '.edit-kab-btn', function(e) {
            e.preventDefault();

            let kabupatenId = $(this).data('kabupaten-id');
            let newActionUrl = '{{ route('kabupaten.update', ':id') }}'.replace(':id', kabupatenId);
            $.ajax({
                url: '{{ route('kabupaten.edit', ':id') }}'.replace(':id', kabupatenId),
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $("#editKabupatenForm").trigger('reset');
                    $("#editKabupatenForm").attr("action", newActionUrl);
                    $('#id').val(response[0].id);
                    $('#editkode').val(response[0].kode);
                    $('#editnama').val(response[0].nama);
                    if (response[0].aktif === 1) {
                        $('#edit-aktif').val(response[0].aktif);
                        $("#editaktif").prop("checked",true); // Set checked to true if value is 1
                    } else {
                        $('#edit-aktif').val(0);
                        $("#editaktif").prop("checked",false); // Set checked to false if value is not 1
                    }
                    let type = response[0].type;
                    $('#type_edit').empty();
                    var options = [
                        { value: "kabupaten", text: "{{ trans('cruds.kabupaten.title') }}" },
                        { value: "kota", text: "{{ trans('cruds.kabupaten.kota') }}" }
                    ];
                    options.forEach(function(option) {
                        var selected = (option.value === type) ? ' selected' : '';
                        $('#type_edit').append('<option data-id="'+response["0"].kode+'" value="'+option.value+'"'+selected+'>'+option.text+'</option>');
                    });

                    $('#type_edit').select2();
                    $('#editKabupatenModal').modal('show');

                    let id_prov   = response[0].provinsi.id;
                    let data = response.results.map(function(item) {
                        return {
                            id: item.id,
                            text: item.id+' - '+ item.nama,
                        };
                    });

                    $('#provinsi_id').select2({
                        dropdownParent: $('#editKabupatenModal'),
                        data : data,
                        placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
                    });
                    $('#provinsi_id').val(response[0].provinsi.id).trigger('change');

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

    //call add kabupaten modal form
    $(document).ready(function() {
        $('#type').select2({
            placeholder: "{{ trans('global.select_type') }} {{ trans('cruds.kabupaten.title') }} / {{ trans('cruds.kabupaten.kota') }}"
        });
        $('.add-kabupaten').on('click', function(e){
            e.preventDefault();
            $.ajax({
                url:  '{{ route('kabupaten.create') }}',
                method: 'GET',
                dataType: 'json',
                success: function(response){
                    let data = response.map(function(item) {
                        return {
                            id: item.id,
                            text: item.id+' - '+ item.nama,
                        };
                    });
                    $('#provinsi_add').select2({
                        dropdownParent: $('#addKabupaten'),
                        data : data,
                        placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
                    });

                    $(document).on('select2:open', function() {
                        setTimeout(function() {
                            document.querySelector('.select2-search__field').focus();
                        }, 100);
                    });
                },error: function(jqXHR, textStatus, errorThrown) {
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
                        // title: jqXHR.statusText,
                        title: 'ERROR !',
                        text: errorMessage,
                        icon: 'error'
                    });
                }
            });
        });

        $('#provinsi_add').on('change', function() {
            let val_prov_id = $('#provinsi_add').val();
            $('#kode').val(val_prov_id+'.');
        });

    // submit form
        $('.btn-add-kabupaten').on('click', function(e, form) {
            e.preventDefault();
            var formDataKab = $('#kabupatenForm').serialize();
            $.ajax({
                method: "POST",
                url: '{{ route('kabupaten.store') }}', // Get form action URL
                data: formDataKab,
                dataType: 'json',
                success: function(response) {
                    if (response.success === true) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: ()=>{
                                Swal.showLoading();
                            },
                        });
                        $('#addKabupaten').modal('hide');
                        $('#kabupatenForm').trigger('reset');
                        $('#kabupaten').DataTable().ajax.reload();
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
                        $('#addKabupaten').modal('hide');
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
</script>
