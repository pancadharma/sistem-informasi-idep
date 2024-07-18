<script>
    //Ajax Request data using server side data table to reduce large data load --}}
    $(document).ready(function() {
        $('#kabupaten').DataTable({
            responsive: true,
            ajax: "{{ route('data.kabupaten') }}",
            processing: true,
            serverSide: true,
            stateSave: true,
            "columns": [
                {
                    data: "kode",
                    width: "5%",
                    className: "text-center"
                },
                {
                    data: "nama",
                    width: "20%"
                },
                {
                    data: 'provinsi.nama',
                    name: 'provinsi.nama',
                    width: "15%"
                },
                {
                    data: "aktif",
                    width: "5%",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data === 1) {
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
                                '" class="icheck-primary" type="checkbox" checked><label for="aktif_' +
                                row.id + '"></label></div>'; // return '☑️';
                        } else {
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
                                '" class="icheck-primary" type="checkbox" ><label for="aktif_' +
                                row.id + '"></label></div>';
                        }
                    }
                },
                {
                    data: "action",
                    width: "8%",
                    className: "text-center",
                    orderable: false
                }
            ],
            layout: {
                bottomStart: {
                    buttons: ['csv', 'excel', 'pdf', 'copy', 'print', 'colvis']
                }
            },
            order: [
                [2, 'asc']
            ],
            pageLength: 5,
            lengthMenu: [5, 10, 50, 100, 500],
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

            console.log(formData);
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
                            icon: "success"
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
                error: function(jqXHR) {
                    const errors = JSON.parse(jqXHR.responseText).errors;
                    const errorMessage = Object.values(errors).flat().map(error =>
                        `<p>* ${error}</p>`).join('');
                    Swal.fire({
                        title: jqXHR.statusText,
                        html: errorMessage,
                        icon: 'error'
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
                    $('#editKabupatenModal').modal('show'); // Show the modal
                    
                    // set select 2 data
                    let id_prov = response[0].provinsi.id;
                    let nama_prov = response[0].provinsi.nama;
                    
                    let data = response.results.map(function(item) {
                        return {
                            id: item.id,
                            text: item.nama,
                        };
                    });
                    $('#provinsi_id').select2({
                        dropdownParent: $('#editKabupatenModal'),
                        data : data
                    });
                    let selected_data = new Option(response[0].provinsi.nama,response[0].provinsi.id,true,true);
                    $('#provinsi_id').append(selected_data).trigger('change');
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
    $(document).ready(function() {
        $('#kode, #editkode').on('input', function() {
            // Remove any non-numeric characters
            let value = $(this).val().replace(/[^\d.]/g, '');
            
            // Split by dot (.) and ensure only two parts
            let parts = value.split('.');
            if (parts.length > 1) {
                parts[0] = parts[0].slice(0, 2); // Ensure first part is maximum two digits
                parts[1] = parts[1].slice(0, 2); // Ensure second part is maximum two digits
                value = parts.join('.');
            } else {
                parts[0] = parts[0].slice(0, 2); // Ensure first part is maximum two digits
                value = parts[0];
            }
            $(this).val(value);
        });
    });

    // $(document).ready(function() {
    //     $('#kabupaten tbody').on('click', '.edit-kab-btn', function(e) {
    //         e.preventDefault();
    //         console.log('clicked');
    //         alert('hellow');


    //     });
    // });

    // $(document).ready(function() {
    //     $('#kabupaten tbody').on('click', '.view-kabupaten-btn', function(e) {
    //     e.preventDefault();
    //         console.log('hell hell helll');
    //         alert('wkwkwkwwkwkw wwkwkwkwk');
    //     });
    // });
</script>
