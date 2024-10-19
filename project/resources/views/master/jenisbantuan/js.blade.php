<script>
//Ajax Request data using server side data table to reduce large data load --}}
    $(document).ready(function() {
      var table = $('#jenisbantuan').DataTable({
            responsive: true,
            ajax: "{{ route('data.jenisbantuan') }}",
            processing: true,
            serverSide: true,
            // stateSave: true,
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
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
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
                            extend: 'print', text: `<i class="fas fa-print"></i>`, titleAttr: "Print Table Data",
                            exportOptions: {
                                stripHTML: false,
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 2) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'excelHtml5', text: `<i class="far fa-file-excel"></i>`, titleAttr: "Export to EXCEL", className: "btn-success",
                            exportOptions: {
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 2) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2]
                            }
                        },{
                            extend: 'pdfHtml5', text: `<i class="far fa-file-pdf"></i>`, titleAttr: "Export to PDF", className: "btn-danger",
                            exportOptions: {
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 2) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? 'Aktif' : '-';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2]
                            }
                        },{
                            extend: 'copy', text: `<i class="fas fa-copy"></i>`, titleAttr: "Copy",
                            exportOptions: {
                                format: {
                                    body: function (data, row, column, node) {
                                        if (column === 2) {
                                            // return $(data).find('input').is(':checked') ? '✅' : '⬜';
                                            return $(data).find('input').is(':checked') ? '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2]
                            }
                        },
                        {extend: 'colvis', text: `<i class="fas fa-eye"></i><span class="d-none d-md-inline">Column visibility</span>`, titleAttr: "Select Visible Column", className: "btn-warning"},
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

//------panggil modal edit and populate data into modal form
    $(document).ready(function() {
        $('#jenisbantuan tbody').on('click', '.edit-jenisbantuan-btn', function(e) {
            e.preventDefault();

            let jenisbantuanId = $(this).data('jenisbantuan-id');
            let newActionUrl = '{{ route('jenisbantuan.update', ':id') }}'.replace(':id', jenisbantuanId);
            $.ajax({
                url: '{{ route('jenisbantuan.edit', ':id') }}'.replace(':id', jenisbantuanId),
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('#id_edit').val(jenisbantuanId);
                    $('#editjenisbantuanModal').modal('show');
                    $("#editjenisbantuanForm").trigger('reset');
                    $("#editjenisbantuanForm").attr("action", newActionUrl);
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


 //------------   
//-------------SUBMIT UPDATE FORM - EDIT
$(document).ready(function() {
        $('#editaktif').change(function() {
            $('#edit-aktif').val(this.checked ? 1 : 0);
        });

        $('#editjenisbantuanForm').submit(function(e) {
            e.preventDefault();

            let idjenisbantuan = $('#id_edit').val();
            let formData = $(this).serialize();
            let url=$(this).attr('action');
            $.ajax({
                //url: '{{ route('jenisbantuan.update', ':id_jenisbantuan') }}'.replace(':id_jenisbantuan', idjenisbantuan),
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
                        $('#editjenisbantuanModal').modal('hide');
                        $('#editjenisbantuanForm').trigger('reset');
                        $('#jenisbantuan').DataTable().ajax.reload();
                    } else if(response.status === "error" || response.status === "warning"){
                        // console.log(response);
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
        $('#jenisbantuan tbody').on('click', '.view-jenisbantuan-btn', function(e) {
            e.preventDefault();
            let jenisbantuanId = $(this).data('jenisbantuan-id');
            let action = $(this).data('action');

            $.ajax({
                url: '{{ route('jenisbantuan.show', ':id') }}'.replace(':id',
                jenisbantuanId), // Route with ID placeholder
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

                        $('#showjenisbantuanModal').modal('show');
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
   
   
    //call add kabupaten modal form
    $(document).ready(function() {
        // $('#type').select2({
        //     placeholder: "{{ trans('global.select_type') }} {{ trans('cruds.kabupaten.title') }} / {{ trans('cruds.kabupaten.kota') }}"
        // });
        // $('.add-kabupaten').on('click', function(e){
        //     e.preventDefault();
        //     $.ajax({
        //         url:  '{{ route('kabupaten.create') }}',
        //         method: 'GET',
        //         dataType: 'json',
        //         success: function(response){
        //             let data = response.map(function(item) {
        //                 return {
        //                     id: item.id,
        //                     text: item.id+' - '+ item.nama,
        //                 };
        //             });
        //             $('#provinsi_add').select2({
        //                 dropdownParent: $('#addKabupaten'),
        //                 data : data,
        //                 placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
        //             });

        //             $(document).on('select2:open', function() {
        //                 setTimeout(function() {
        //                     document.querySelector('.select2-search__field').focus();
        //                 }, 100);
        //             });
        //         },error: function(jqXHR, textStatus, errorThrown) {
        //             const errorData = JSON.parse(jqXHR.responseText);
        //             const errors = errorData.errors; // Access the error object
        //             let errorMessage = "";
        //             for (const field in errors) {
        //                 errors[field].forEach(error => {
        //                     errorMessage +=
        //                     `* ${error}\n`; // Build a formatted error message string
        //                 });
        //             }
        //             Swal.fire({
        //                 // title: jqXHR.statusText,
        //                 title: 'ERROR !',
        //                 text: errorMessage,
        //                 icon: 'error'
        //             });
        //         }
        //     });
        // });

        // $('#provinsi_add').on('change', function() {
        //     let val_prov_id = $('#provinsi_add').val();
        //     $('#kode').val(val_prov_id+'.');
        // });        

    // submit form 
        // Submit form
        $('.btn-add-jenisbantuan').on('click', function(e) {
            e.preventDefault();
            var formDataJenisbantuan = $('#jenisbantuanForm').serialize();
            $.ajax({
                method: "POST",
                url: '{{ route('jenisbantuan.store') }}', // Get form action URL
                data: formDataJenisbantuan,
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
                        $('#addjenisbantuan').modal('hide');
                        $('#jenisbantuanForm').trigger('reset');
                        $('#jenisbantuan').DataTable().ajax.reload();
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
                        //$('#addjenisbantuan').modal('hide');
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
</script>
