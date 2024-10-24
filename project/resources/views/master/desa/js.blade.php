<script>
    $(document).ready(function() {
        function formatErrorMessages(errors) {
            let message = '<ul>';
            for (const field in errors) {
                errors[field].forEach(function(error) {
                    message += `<li>${error}</li>`;
                });
            }
            message += '</ul>';
            return message;
        }
        //load data desa into datatables
        $('#desa_list').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            deferRender: true,
            stateSave: true,
            // ajax: "{{ route('data.desa') }}",
            ajax: {
                url: "{{ route('data.desa') }}",
                method: "GET",
                dataType: "json",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: "text-center align-middle",
                    width: "5%",
                },
                {
                    data: "kode",
                    // name: 'kelurahan.kode', //needed when using alternative query
                    width: "5%",
                    className: "text-center",
                    orderable: false
                },
                {
                    data: "nama",
                    // name: 'kelurahan.nama',
                    width: "30%",
                    className: "text-left"
                },
                {
                    // data: "kecamatan_nama", // Update to match the server-side column name
                    data: "kecamatan.nama", // Update to match the server-side column name
                    // name: 'kecamatan.nama',
                    width: "30%",
                    className: "text-left"
                },
                {
                    data: "aktif",
                    // name: 'kelurahan.aktif',
                    width: "10%",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data === 1) {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' +
                                row.id + '" data-aktif-id="' + row.id +
                                '" class="icheck-primary" alt="☑️" title="{{ __('cruds.status.aktif') }}" type="checkbox" checked><label for="aktif_' +
                                row.id + '"></label></div>';
                        } else {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' +
                                row.id + '" data-aktif-id="' + row.id +
                                '" class="icheck-primary" title="{{ __('cruds.status.tidak_aktif') }}" type="checkbox"><label for="aktif_' +
                                row.id + '"></label></div>';
                        }
                    }
                },
                {
                    data: "action",
                    width: "15%",
                    className: "text-center",
                    orderable: false,
                }
            ],
            layout: {
                topStart: {
                    buttons: [{
                            extend: 'print',
                            text: `<i class="fas fa-print"></i>`,
                            titleAttr: "Print Table Data",
                            exportOptions: {
                                stripHTML: false,
                                format: {
                                    body: function(data, row, column, node) {
                                        if (column === 4) {
                                            return $(data).find('input').is(':checked') ?
                                                '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2, 3,
                                    4
                                ] // Ensure these indices match your visible columns
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: `<i class="far fa-file-excel"></i>`,
                            titleAttr: "Export to EXCEL",
                            className: "btn-success",
                            exportOptions: {
                                format: {
                                    body: function(data, row, column, node) {
                                        if (column === 4) {
                                            return $(data).find('input').is(':checked') ?
                                                '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: `<i class="far fa-file-pdf"></i>`,
                            titleAttr: "Export to PDF",
                            className: "btn-danger",
                            exportOptions: {
                                format: {
                                    body: function(data, row, column, node) {
                                        if (column === 4) {
                                            return $(data).find('input').is(':checked') ?
                                                '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'copy',
                            text: `<i class="fas fa-copy"></i>`,
                            titleAttr: "Copy",
                            exportOptions: {
                                format: {
                                    body: function(data, row, column, node) {
                                        if (column === 4) {
                                            return $(data).find('input').is(':checked') ?
                                                '\u2611' : '\u2610';
                                        }
                                        return data;

                                    }
                                },
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-warning',
                            titleAttr: "Select Visible Column",
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
                [2, 'asc'] // Ensure this matches the index of the `kecamatan_nama` column
            ],
            lengthMenu: [5, 25, 50, 100, 500],
        });

        //trigger provinsi when it changes
        $('#provinsi_id').change(function() {
            var provinsi_id = $(this).val();
            if (provinsi_id) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('kab.data', ':id') }}'.replace(':id', provinsi_id),
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        Toast.fire({
                            icon: "info",
                            title: "Loading {{ __('cruds.kabupaten.title') }}",
                            // position: "bottom-end",
                            timer: 500,
                        });
                    },
                    success: function(hasil) {
                        $("#kabupaten_id").empty();
                        $('#kabupaten_id').html(
                            `<option value="0">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title') }}</option>`
                        );
                        if (hasil) {
                            $.each(hasil, function(index, item) {
                                $("#kabupaten_id").append('<option value="' + item
                                    .id + '" data-id="' + item.kode + '">' +
                                    item.text + '</option>');
                            });
                        } else {
                            $("#kabupaten_id").empty();
                        }
                    },
                    error: function(xhr, jqXHR, textStatus, errorThrown) {
                        $('#updateProfileBtn').removeAttr('disabled');
                        var errorMessage = 'An unexpected error occurred.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON
                                .message; // Get the message from the response
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'Okay'
                        });
                    },
                });
            }
            $('#kabupaten_id').select2({
                placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title') }}",
            });
        });
        //autofill desa_kode with kabupatan_kode
        $('#provinsi_id, #kabupaten_id').change(function() {
            var selected = $(this).find('option:selected');
            var kode_kab = selected.data('id');
            var kab_id = selected.val();

            if (kab_id) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('kec.data', ':id') }}'.replace(':id', kab_id),
                    method: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        Toast.fire({
                            icon: "info",
                            title: "Loading {{ __('cruds.kecamatan.title') }}",
                            // position: "bottom-end",
                            timer: 500,
                        });
                    },
                    success: function(hasil) {
                        $("#kecamatan_id").empty();
                        $('#kecamatan_id').html(
                            `<option value="0">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title') }}</option>`
                        );
                        if (hasil) {
                            $.each(hasil, function(index, item) {
                                $("#kecamatan_id").append('<option value="' + item
                                    .id + '" data-id="' + item.kode + '">' +
                                    item.text + '</option>');
                            });
                        } else {
                            $("#kabupaten_id").empty();
                            $("#kecamatan_id").empty();
                        }
                    },
                    error: function(xhr, jqXHR, textStatus, errorThrown) {
                        $('#updateProfileBtn').removeAttr('disabled');
                        var errorMessage = 'An unexpected error occurred.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON
                                .message; // Get the message from the response
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'Okay'
                        });
                    },
                });
            }
            if (kode_kab !== undefined) {
                $('#kode').val(kode_kab + '.');
                $('#kode').prop("title", "please input 4 digit additional village code");
            } else {
                $('#kode').val('');
                $('#kode').prop("placeholder",
                    "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title') }}");
            }
        });

        $('#kecamatan_id').change(function() {
            var selected = $(this).find('option:selected');
            var kode_kec = selected.data('id');
            console.log('koded kec: ', kode_kec);

            if (kode_kec !== undefined || kode_kec != '') {
                Toast.fire({
                    icon: "info",
                    title: "Loading {{ __('cruds.kecamatan.title') }}",
                    // position: "bottom-end",
                    timer: 500,
                });
                setTimeout(() => {
                    $('#kode').val(kode_kec + '.');
                    $('#kode').prop("title", "add two more of kode kecamatan");
                }, 800);
            } else {
                $('#kode').val('');
                $('#kode').prop("placeholder",
                    "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title') }}");
            }
        });

        // VALIDATION CHECK
        function validateKode(value) {
            // Regex untuk format 15.05.04.xxxx
            const regex = /^\d{2}\.\d{2}\.\d{2}\.\d{4}$/;
            return regex.test(value);
        }

        function validateNama(value) {
            return value.length >= 3;
        }

        function checkValidity() {
            let fields = ['#provinsi_id', '#kabupaten_id', '#kecamatan_id', '#nama', '#kode'];
            let isValid = true;
            fields.forEach(function(field) {
                let $field = $(field);
                let value = $field.val();

                if ($field.hasClass('select2-hidden-accessible')) {
                    if (!value) {
                        $field.next('.select2-container').addClass('is-invalid').removeClass(
                            'is-valid');
                        isValid = false;
                    } else {
                        $field.next('.select2-container').addClass('is-valid').removeClass(
                            'is-invalid');
                    }
                } else {
                    if (!value || value.trim() === '') {
                        $field.addClass('is-invalid').removeClass('is-valid');
                        isValid = false;
                    } else {
                        // Specific validation for 'kode'
                        if (field === '#kode' && value.length !== 13) {
                            $field.addClass('is-invalid').removeClass('is-valid');
                            isValid = false;
                        } else {
                            $field.addClass('is-valid').removeClass('is-invalid');
                        }
                        const kodeInput = document.getElementById('kode');
                        if (validateKode(kodeInput.value)) {
                            kodeInput.classList.add('is-valid');
                            kodeInput.classList.remove('is-invalid');
                        } else {
                            kodeInput.classList.add('is-invalid');
                            kodeInput.classList.remove('is-valid');
                        }
                        const namaInput = document.getElementById('nama');
                        if (!validateNama(namaInput.value)) {
                            document.getElementById('nama_error').textContent =
                                'Required. Min data is 3 characters.';
                            namaInput.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            namaInput.classList.remove('is-invalid');
                            namaInput.classList.add('is-valid');
                        }

                    }
                }
            });
            return isValid;
        }
        $('#submit_desa').find('input, select, textarea').on('input change', function() {
            checkValidity();
        });

        // SUBMIT DESA DATA
        $('#submit_desa').on('submit', function(e) {
            e.preventDefault();
            let kecamatan_kode = $('#kecamatan_id').children('option:selected').data('id');
            let formData = $(this).serialize();
            formData += '&kecamatan_kode=' + kecamatan_kode;
            let url = $(this).attr('action');
            var formIsValid = true;

            $(this).find('input, select, textarea').each(function() {
                if (!checkValidity(this)) {
                    formIsValid = false;
                }
            });
            if (formIsValid) {
                $.ajax({
                    url: '{{ route('desa.store') }}',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    beforeSend: function() {
                        Toast.fire({
                            icon: "info",
                            title: "Processing...",
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success === true) {
                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success",
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                            $("#kabupaten_id").empty();
                            $("#kecamatan_id").empty();
                            $('#submit_desa').trigger('reset');
                            $('#desa_list').DataTable().ajax.reload();
                            $('#nama').removeClass('is-valid');
                            $('#kode').removeClass('is-valid');
                            $(".btn-tool").trigger('click');
                            resetForm();
                        }
                        $("#provinsi_id").select2({
                            placeholder: "{{ trans('global.pleaseSelect') . ' ' . trans('cruds.provinsi.title') }}",
                        });
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                        try {
                            const response = xhr.responseJSON;
                            if (response.errors) {
                                errorMessage +=
                                    '<br><br><ul style="text-align:left!important">';
                                $.each(response.errors, function(field, messages) {
                                    messages.forEach(message => {
                                        errorMessage +=
                                            `<li>${field}: ${message}</li>`;
                                        $(`#${field}-error`).text(message)
                                            .removeClass('is-valid')
                                            .addClass('is-invalid');
                                        $(`#${field}`).removeClass(
                                            'invalid').addClass(
                                            'is-invalid');
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
            }
        });
        //View Kelurahan / Desa
        $('#desa_list tbody').on('click', '.view-desa-btn', function(e) {
            e.preventDefault();
            let desaId = $(this).data('desa-id');
            let action = $(this).data('action');
            let url = '{{ route('desa.show', ':id') }}'.replace(':id', desaId);
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "Processing...",
                        timer: 1500,
                        timerProgressBar: true,
                    });
                },
                success: function(response) {
                    let data = response || [];
                    if (action === 'view') {
                        $("#show-kode").text(data.kode);
                        $("#show-nama").text(data.nama);
                        $("#show-kecamatan").text(data.kecamatan.nama);
                        if (data.aktif === 1) {
                            $('#show-aktif').val(data.aktif);
                            $("#show-aktif").prop("checked",
                                true); // Set checked to true if value is 1
                        } else {
                            $('#show-aktif').val(0);
                            $("#show-aktif").prop("checked",
                                false); // Set checked to false if value is not 1
                        }
                        $('#showDesaModal').modal('show');
                    } else {
                        Swal.fire({
                            text: "Error",
                            message: "Failed to fetch data",
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, errors) {
                    var response = JSON.parse(xhr.responseText);
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                        if (response.errors) {
                            const errors = response.errors;
                            errorMessage +=
                                '<br><br><ul style="text-align:left!important">';
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
                        html: errorMessage,
                    });
                }
            });
        });
        //edit desa modal show
        $('#desa_list tbody').on('click', '.edit-desa-btn', function(e) {
            e.preventDefault();
            let desaId = $(this).data('desa-id');
            let action = $(this).data('action');
            let url = '{{ route('desa.edit', ':id') }}'.replace(':id', desaId);
            let url_update = '{{ route('desa.update', ':id') }}'.replace(':id', desaId);
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "Loading...",
                        timerProgressBar: true,
                        timer: 500,
                    })
                },
                success: function(hasil) {
                    if (action === 'edit') {
                        let provID = hasil.desa.kecamatan.kabupaten.provinsi_id;
                        let kabID = hasil.desa.kecamatan.kabupaten.id;
                        let kecID = hasil.desa.kecamatan.id;
                        resetForm();
                        $("#editDesaForm").trigger('reset');
                        $("#editDesaForm").attr('action', url_update);
                        $('#id').val(hasil.desa.id);
                        $("#editkode").val(hasil.desa.kode);
                        $("#editnama").val(hasil.desa.nama);
                        $('#editaktif').prop('checked', hasil.desa.aktif === 1);
                        $('#edit-aktif').val(hasil.desa.aktif);

                        $('#edit_provinsi_id').empty();
                        $.each(hasil.provinsi, function(key, value) {
                            let selected = (value.id === provID) ? 'selected' : '';
                            $('#edit_provinsi_id').append('<option value="' + value.id + '" ' + selected + '>' + value.kode +' - ' + value.nama + '</option>');
                        });
                        $('#edit_kabupaten_id').empty();

                        $.each(hasil.kabupaten, function(key, value) {
                            let selected = (value.id === kabID) ? 'selected' : '';
                            $('#edit_kabupaten_id').append('<option value="' + value.id + '" ' + selected + ' data-id="' + value.kode + '">' + value.kode + ' - ' + value.nama +'</option>');
                        });
                        // edit_kecamatan_id
                        $('#edit_kecamatan_id').empty();
                        $.each(hasil.kecamatan, function(key, value) {
                            let selected = (value.id === kecID) ? 'selected' : '';
                            $('#edit_kecamatan_id').append('<option value="' + value.id + '" ' + selected + ' data-id="' + value.kode + '">' + value.kode + ' - ' + value.nama + '</option>');
                        });
                        $('#editDesaModal').modal('show');
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Failed to fetch data",
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, errors) {
                    var response = JSON.parse(xhr.responseText);
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                        if (response.errors) {
                            const errors = response.errors;
                            errorMessage +=
                                '<br><br><ul style="text-align:left!important">';
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
                        html: errorMessage,
                    });
                }
            });
        });

        //Update Submit by clicking editDesa btn
        // $('#editDesa').on('click', function(e){
        //     e.preventDefault();
        //     alert('updated');
        // });
        $('#editkode, #kode').on('input', function() {
            let input = $(this).val();
            input = input.replace(/\D/g, ''); // Remove all non-digit characters
            if (input.length > 2) {
                input = input.substring(0, 2) + '.' + input.substring(2);
            }
            if (input.length > 5) {
                input = input.substring(0, 5) + '.' + input.substring(5);
            }
            if (input.length > 8) {
                input = input.substring(0, 8) + '.' + input.substring(8, 12);
            }
            $(this).val(input);
        });

        $(function() {
            $.validator.setDefaults({
                submitHandler: function() {
                    let kecamatan_kode = $('#edit_kecamatan_id').children('option:selected').data('id');
                    update_desa(kecamatan_kode);
                }
            });
            const validator = $('#editDesaForm').validate({
                rules: {
                    nama: {
                        required: true,
                        minlength: 3,
                    },
                    kode: {
                        required: true,
                        minlength: 13,
                        maxlength: 13
                    },
                    kecamatan_id: {
                        required: true
                    },
                },
                messages: {
                    nama: {
                        required: "{{ trans('cruds.desa.validation.req_nama') }}",
                        minlength: "{{ trans('cruds.desa.validation.min_nama') }}"
                    },
                    kode: {
                        required: "{{ trans('cruds.desa.validation.req_kode') }}",
                        minlength: "{{ trans('cruds.desa.validation.min_kode') }}"
                    },
                    kecamatan_id: "{{ trans('cruds.desa.validation.kec') }}"
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                }
            });

            $('#editnama, #editkode, #edit_kecamatan_id').on('blur change', function() {
                validator.element($(this)); // Validate the field that triggered the change event
            });

            function capitalizeWords(str) {
                return str.replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                });
            }
            $('#nama, #editnama').on('input', function() {
                let input = $(this).val();
                if (/^\d{1,3}/.test(input)) {
                    input = input.replace(/^\d{1,3}/, '');
                }
                input = capitalizeWords(input);
                $(this).val(input);
            });
            // $('#nama').on('blur change', function() {
            //     let input = $(this).val();
            //     if (input.length < 3) {
            //         alert("Nama harus memiliki minimal 3 karakter.");
            //     }
            // });
        });

        function update_desa(kecamatan_kode) {
            $('#editaktif').change(function() {
                $('#edit-aktif').val(this.checked ? 1 : 0);
            });
            let formData = $('#editDesaForm').serialize();
            formData += '&kecamatan_kode=' + kecamatan_kode;
            let url = $('#editDesaForm').attr('action');
            let idDesa = $('#id').val();
            let nama = $('#editnama').val();
            let url_update = '{{ route('desa.update', ':kec') }}'.replace(':kec', idDesa);
            $.ajax({
                url: url,
                type: 'PUT',
                dataType: 'JSON',
                data: formData,
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "Updating...",
                        timerProgressBar: true,
                        timer: 500,
                    });
                },
                success: function(response) {
                    if (response.success === true) {
                        Swal.fire({
                            title: response.data.nama,
                            text: response.message,
                            icon: 'success',
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $('#editDesaModal').modal('hide');
                        resetForm();
                        $('#desa_list').DataTable().ajax.reload();
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "Failed to Update",
                            position: 'top-end',
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                },
                error: function(xhr, status, errors) {
                    var response = JSON.parse(xhr.responseText);
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
                        html: errorMessage,
                    });
                }
            });
            return true;
        }

        function resetForm() {
            // Clear input fields
            $('#editDesaForm, #desaForm')[0].reset();
            $('.form-group').find('.is-invalid').removeClass('is-invalid');
            $('.form-group').find('.is-valid').removeClass('is-valid');
            $('.form-group').find('.invalid-feedback').text(''); // Clear text in error spans
        }

        $('#provinsi_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title') }}",
            allowClear: true,
            delay: 250,
        });
        $('#edit_provinsi_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title') }}",
            delay: 250,
            allowClear: true,
            dropdownParent: $('#editDesaModal')
        });
        $('#kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title') }}",
            allowClear: true,
            delay: 250,
        });
        $('#edit_kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title') }}",
            delay: 250,
        });
        $('#kecamatan_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title') }}",
            delay: 250,
        });
        $('#edit_kecamatan_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') . ' ' . trans('cruds.kecamatan.title') }}",
            delay: 250,
            dropdownParent: $('#editDesaModal')
        });

        $('#show-aktif, #aktif_').click(function(event) {
            event.preventDefault();
        });

        $('#aktif').change(function() {
            $('#aktif').val(this.checked ? 1 : 0);
        });

        $('#edit_provinsi_id').on('change', function() {
            var provinsiID = $(this).val();
            var kab_load = $("#edit_kabupaten_id").find('option:selected');
            var KabKode = kab_load.data('id');
            setTimeout(() => {
                Toast.fire({
                    icon: "info",
                    title: "Loading {{ __('cruds.dusun.form.kode') }}",
                    // position: "bottom-end",
                    timer: 500,
                });
                loadKab(provinsiID);
            }, 500);
            if (KabKode !== undefined && $("#edit_kabupaten_id").prop('selectedIndex') > 0) {
                $('#editkode').html('');
                $('#editkode').empty();
                $('#editkode').val(provinsiID + '.');
            } else {
                // $('#editkode').val(KabKode+'.');
                $('#editkode').html('');
            }
        });

        $('#edit_kabupaten_id').on('change', function() {
            let KabupatenID = $(this).val();
            var kab_load = $("#edit_kabupaten_id").find('option:selected');
            var KabKode = kab_load.data('id');
            setTimeout(() => {
                loadKec(KabupatenID);
            }, 500);

        });

        $('#edit_kecamatan_id').on('change', function() {
            Toast.fire({
                icon: "info",
                title: "Loading {{ __('cruds.desa.form.kode') }}",
                // position: "bottom-end",
                timer: 500,
            });
            let kec_load = $("#edit_kecamatan_id").find('option:selected');
            let KecKode = kec_load.data('id');
            setTimeout(() => {
                $('#editkode').val(KecKode + '.');
                $('#editkode').focus();
            }, 500);
        });

        function loadKab(provinsiID, selectedKabupatenId) {
            if (provinsiID) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('kab.data', ':id') }}'.replace(':id', provinsiID),
                    method: 'GET',
                    dataType: 'json',
                    success: function(hasil) {
                        $("#edit_kabupaten_id").empty();
                        $("#edit_kecamatan_id").empty();
                        $('#edit_kabupaten_id').html(
                            `<option value="">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title') }}</option>`
                        );
                        if (hasil) {
                            $.each(hasil, function(index, item) {
                                $("#edit_kabupaten_id").append('<option value="' + item.id +
                                    '" data-id="' + item.kode + '">' + item.text +
                                    '</option>');
                            });
                            $('#edit_kabupaten_id').val(selectedKabupatenId).trigger(
                                'change.select2');
                        } else {
                            $("#edit_kabupaten_id").empty();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Something Wrong !",
                            text: xhr.message,
                            icon: "error",
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        console.log("failed to load kabupaten data");
                    }
                });
            }
        }

        function loadKec(KabupatenID) {
            if (KabupatenID) {
                $.ajax({
                    url: '{{ route('kec.data', ':id') }}'.replace(':id', KabupatenID),
                    method: 'GET',
                    dataType: 'JSON',
                    beforeSend: function() {
                        Toast.fire({
                            icon: "info",
                            title: "Loading {{ __('cruds.kecamatan.title') }}",
                            // position: "bottom-end",
                            timer: 500,
                        });
                    },
                    success: function(hasil) {
                        $("#edit_kecamatan_id").empty();
                        $('#edit_kecamatan_id').html(`<option value="">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title') }}</option>`);
                        if (hasil) {
                            setTimeout(() => {
                                $.each(hasil, function(index, item) {
                                    $("#edit_kecamatan_id").append('<option value="' + item.id + '" data-id="' + item.kode + '">' + item.text +'</option>');
                                });
                                $('#edit_kecamatan_id').val("").trigger('change.select2');
                            }, 500);
                        } else {
                            $("#edit_kecamatan_id").empty();
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: "error",
                            title: "Loading {{ __('cruds.desa.title') }}",
                            // position: "bottom-end",
                            timer: 500,
                        });
                    }
                });
            }
        }

    });
</script>
