<!-- javascript to create kegiatan first -->
<script>
    function handleErrors(response) {
        let errorMessage = response.message;
        if (response.status === 400) {
            try {
                const errors = response.errors;
                errorMessage = formatErrorMessages(errors);
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

    function formatErrorMessages(errors) {
        let message = '<br><ul style="text-align:left!important">';
        for (const field in errors) {
            errors[field].forEach(function(error) {
                message += `<li>${error}</li>`;
            });
        }
        message += '</ul>';
        return message;
    }

    function getErrorMessage(xhr) {
        let message;
        try {
            const response = JSON.parse(xhr.responseText);
            message = response.message || 'An unexpected error occurred. Please try again later.';
        } catch (e) {
            message = 'An unexpected error occurred. Please try again later.';
        }
        return message;
    }

    function addInvalidClassToFields(errors) {
        for (const field in errors) {
            if (errors.hasOwnProperty(field)) {
                errors[field].forEach(function(error) {
                    const inputField = $(`[name="${field}"]`);
                    if (inputField.length) {
                        inputField.addClass('is-invalid');
                        if (inputField.next('.invalid-feedback').length === 0) {
                            inputField.after(`<div class="invalid-feedback">${error}</div>`);
                        }
                    }
                });
            }
        }

        // Attach an event listener to remove the invalid class and message on input change
        $('input, textarea, select').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    }
</script>
<!-- Script for Input Summernote -->
<script>
    $(document).ready(function() {
        $('.summernote').each(function() {
            if (!$(this).data('initialized')) {
                $(this).summernote({
                    height: 120,
                    width: '100%',
                    toolbar: [
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['color', ['color']],
                        ['paragraph', ['paragraph']],
                        ['view', ['fullscreen', 'codeview']],
                    ],
                    inheritPlaceholder: true,
                    tabDisable: true,
                    codeviewFilter: false,
                });
                $(this).data('initialized', true); // Mark this textarea as initialized
            }
        });
    });
</script>
<!-- Script for Kegiatan Peserta -->
<script>
    // Function to save form data to localStorage
    function saveFormDataToStorage() {
        var formData = {
            program_id: $('#program_id').val(),
            program_kode: $('#program_kode').val(),
            kode_kegiatan: $('#kode_kegiatan').val(),
            nama_kegiatan: $('#nama_kegiatan').val(),
            nama_desa: $('#nama_desa').val(),
            lokasi: $('#lokasi').val(),
            lat: $('#lat').val(),
            longitude: $('#longitude').val(),
            tanggalmulai: $('#tanggalmulai').val(),
            tanggalselesai: $('#tanggalselesai').val(),
            nama_mitra: $('#nama_mitra').val(),
            status: $('#status').val(),
            fase_pelaporan: $('#fase_pelaporan').val(),


            deskripsi_kegiatan: $('#deskripsi_kegiatan').val(),
            tujuan_kegiatan: $('#tujuan_kegiatan').val(),
            yang_terlibat: $('#yang_terlibat').val(),
            pelatih_asal: $('#pelatih_asal').val(),
            kegiatan: $('#kegiatan').val(),
            informasi_lain: $('#informasi_lain').val(),
            luas_lahan: $('#luas_lahan').val(),
            barang: $('#barang').val(),
            satuan: $('#satuan').val(),
            others: $('#others').val(),

        };

        // Include Summernote content
        $('.summernote').each(function () {
            const id = $(this).attr('id');
            formData[id] = $(this).summernote('isEmpty') ? '' : $(this).summernote('code');
        });

        // Include select2 selected values
        $('.select2').each(function () {
            const id = $(this).attr('id');
            formData[id] = $(this).val(); // Store the selected value(s)
        });

        // Save form data to localStorage
        localStorage.setItem('kegiatanFormData', JSON.stringify(formData));
    }
    // function initializeSelect2WithDynamicUrl(fieldId) {
    //     var apiUrl = $('#' + fieldId).data('api-url');
    //     var placeholder = $('#' + fieldId).attr('placeholder');
    //     $('#' + fieldId).select2({
    //         ajax: {
    //             url: apiUrl,
    //             dataType: 'json',
    //             delay: 300, // Debounce time
    //             data: function (params) {
    //                 return {
    //                     search: params.term || '', // Search term, empty for initial load
    //                     page: params.page || 1 // Page number
    //                 };
    //             },
    //             processResults: function (data, params) {
    //                 params.page = params.page || 1;
    //                 return {
    //                     results: data.data.map(item => ({
    //                         id: item.id,
    //                         text: item.nama
    //                     })),
    //                     pagination: {
    //                         more: data.current_page < data.last_page
    //                     }
    //                 };
    //             },
    //             cache: true
    //         },
    //         minimumInputLength: 0, // Minimum input length to trigger search
    //         placeholder: placeholder,
    //         allowClear: true // Allow clearing the selection
    //     });
    // }

    // Function to initialize Select2 with dynamic URL
    function initializeSelect2WithDynamicUrl(fieldId) {
        var select2Field = $('#' + fieldId);
        var apiUrl = select2Field.data('api-url');

        select2Field.select2({
            width: '100%',
            placeholder: select2Field.attr('placeholder'),
            allowClear: true,
            ajax: {
                url: apiUrl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama
                            };
                        }),
                        pagination: {
                            more: data.current_page < data.last_page
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 0
        });
    }

    // function loadFormDataFromStorage() {
    //     var storedData = localStorage.getItem('kegiatanFormData');
    //     if (storedData) {
    //         var formData = JSON.parse(storedData);

    //         // Populate basic form fields
    //         $('#program_id').val(formData.program_id || '');
    //         $('#program_kode').val(formData.program_kode || '');
    //         //
    //         $('#kode_kegiatan').val(formData.kode_kegiatan || '');
    //         $('#nama_kegiatan').val(formData.nama_kegiatan || '');
    //         $('#nama_desa').val(formData.nama_desa || '');
    //         $('#lokasi').val(formData.lokasi || '');
    //         $('#lat').val(formData.lat || '');
    //         $('#longitude').val(formData.longitude || '');
    //         $('#tanggalmulai').val(formData.tanggalmulai || '');
    //         $('#tanggalselesai').val(formData.tanggalselesai || '');
    //         $('#nama_mitra').val(formData.nama_mitra || '');
    //         $('#deskripsi_kegiatan').val(formData.deskripsi_kegiatan || '');
    //         // $('#deskripsi_kegiatan').summernote('code', formData.deskripsi_kegiatan || '');

    //         $('#tujuan_kegiatan').val(formData.tujuan_kegiatan || '');
    //         $('#yang_terlibat').val(formData.yang_terlibat || '');
    //         $('#pelatih_asal').val(formData.pelatih_asal || '');
    //         $('#kegiatan').val(formData.kegiatan || '');
    //         $('#informasi_lain').val(formData.informasi_lain || '');
    //         $('#luas_lahan').val(formData.luas_lahan || '');
    //         $('#barang').val(formData.barang || '');
    //         $('#satuan').val(formData.satuan || '');
    //         $('#others').val(formData.others || '');

    //         // Populate Summernote fields
    //         $('.summernote').each(function () {
    //             const id = $(this).attr('id');
    //             if (!id) {
    //                 console.error('Found Summernote element with undefined id:', this);
    //                 return; // Skip this element
    //             }
    //             const value = formData[id] || '';
    //             $(this).summernote('code', value); // Set the content
    //             // console.log(`Setting content for ${id}: ${value}`);
    //         });



    //         // Populate and initialize select2 fields with fetched data from API
    //         $('.select2').each(function () {
    //             var fieldId = $(this).attr('id');
    //             var value = formData[fieldId];
    //             if (value) {
    //                 var select2Field = $(this);
    //                 var apiUrl = $(this).data('api-url');
    //                 $.ajax({
    //                     url: apiUrl,
    //                     method: 'GET',
    //                     data: { id: value },
    //                     success: function (data) {
    //                         var item = data.data.find(item => item.id == value);
    //                         if (item) {
    //                             var newOption = new Option(item.nama, item.id, false, true);
    //                             select2Field.append(newOption).trigger('change');
    //                         }
    //                     },
    //                     error: function (error) {
    //                         console.error('Error fetching data:', error);
    //                     }
    //                 });
    //             }
    //             initializeSelect2WithDynamicUrl(fieldId);
    //         });
    //     } else {
    //         // Initialize select2 fields even if there's no data in localStorage
    //         $('.select2').each(function () {
    //             var fieldId = $(this).attr('id');
    //             initializeSelect2WithDynamicUrl(fieldId);
    //         });
    //     }
    // }
    function loadFormDataFromStorage() {
        var storedData = localStorage.getItem('kegiatanFormData');
        if (storedData) {
            var formData = JSON.parse(storedData);

            // Populate basic form fields
            $('#program_id').val(formData.program_id || '');
            $('#program_kode').val(formData.program_kode || '');
            $('#kode_kegiatan').val(formData.kode_kegiatan || '');
            $('#nama_kegiatan').val(formData.nama_kegiatan || '');
            $('#nama_desa').val(formData.nama_desa || '');
            $('#lokasi').val(formData.lokasi || '');
            $('#lat').val(formData.lat || '');
            $('#longitude').val(formData.longitude || '');
            $('#tanggalmulai').val(formData.tanggalmulai || '');
            $('#tanggalselesai').val(formData.tanggalselesai || '');
            $('#status').val(formData.status || '');
            $('#fase_pelaporan').val(formData.fase_pelaporan || '');


            $('#deskripsi_kegiatan').val(formData.deskripsi_kegiatan || '');
            $('#tujuan_kegiatan').val(formData.tujuan_kegiatan || '');
            $('#yang_terlibat').val(formData.yang_terlibat || '');
            $('#pelatih_asal').val(formData.pelatih_asal || '');
            $('#kegiatan').val(formData.kegiatan || '');
            $('#informasi_lain').val(formData.informasi_lain || '');
            $('#luas_lahan').val(formData.luas_lahan || '');
            $('#barang').val(formData.barang || '');
            $('#satuan').val(formData.satuan || '');
            $('#others').val(formData.others || '');

            // Populate Summernote fields
            $('.summernote').each(function () {
                const id = $(this).attr('id');
                if (!id) {
                    console.error('Found Summernote element with undefined id:', this);
                    return;
                }
                const value = formData[id] || '';
                $(this).summernote('code', value);
            });

            if (formData.jenis_kegiatan) {
                var apiUrl = $('#jenis_kegiatan').data('api-url');
                $.ajax({
                    url: apiUrl,
                    method: 'GET',
                    data: { id: formData.jenis_kegiatan },
                    success: function (response) {
                        if (response.data && response.data.length > 0) {
                            var item = response.data[0];
                            var newOption = new Option(item.nama, item.id, true, true);
                            $('#jenis_kegiatan')
                                .append(newOption)
                                .trigger('change');
                        }
                    }
                });
            }

            // Populate and initialize select2 fields with fetched data from API
            // $('.select2').each(function () {
            $('.select2').not('#jenis_kegiatan').each(function () {
                var fieldId = $(this).attr('id');
                var values = formData[fieldId];
                var select2Field = $(this);
                var apiUrl = $(this).data('api-url');

                if (values) {
                    // Handle both single value and array of values
                    var valueArray = Array.isArray(values) ? values : [values];

                    $.ajax({
                        url: apiUrl,
                        method: 'GET',
                        data: { id: valueArray },
                        success: function (data) {
                            // Clear existing options
                            select2Field.empty();

                            // Add options from API response
                            if (data.data && Array.isArray(data.data)) {
                                data.data.forEach(function(item) {
                                    var newOption = new Option(item.nama, item.id, true, true);
                                    select2Field.append(newOption);
                                });
                            }

                            // Initialize Select2
                            initializeSelect2WithDynamicUrl(fieldId);

                            // Set the values and trigger change
                            select2Field.val(valueArray).trigger('change');
                        },
                        error: function (error) {
                            console.error('Error fetching Select2 data:', error);
                            // Still initialize Select2 even if there's an error
                            initializeSelect2WithDynamicUrl(fieldId);
                        }
                    });
                } else {
                    // Initialize Select2 even if no values are stored
                    initializeSelect2WithDynamicUrl(fieldId);
                }
            });
        } else {
            // Initialize select2 fields even if there's no data in localStorage
            $('.select2').each(function () {
                var fieldId = $(this).attr('id');
                initializeSelect2WithDynamicUrl(fieldId);
            });
        }
    }

    $(document).ready(function() {
        loadParticipantsFromStorage();
        loadFormDataFromStorage();

        $('#createKegiatan').on('change', 'input, select, textarea', function () {
            saveFormDataToStorage();
        });

        // Specifically handle Select2 events
        $(document).on('select2:select select2:unselect', '.select2', function () {
            saveFormDataToStorage();
        });

        // Specifically handle Summernote changes
        $(document).on('summernote.change', '.summernote', function () {
            saveFormDataToStorage();
        });

        // Attach change event listeners to input, select, date, select2, and summernote fields
        $('#createKegiatan').on('change', 'input, select, textarea, .select2, .summernote', function () {
            saveFormDataToStorage();
            // this works
        });

        // Additionally, capture changes from select2 and summernote
        $('.select2').on('select2:select select2:unselect', function() {
            saveFormDataToStorage();
        });
        $('.summernote').on('summernote.change', function() {
            saveFormDataToStorage();
        });

        $('#saveModalData').click(function() {
            // Collect form values (existing code remains the same)
            var identitas = $('#identitas').val() || '';
            var nama = $('#nama').val() || '';

            // Jenis Kelamin mapping
            var jenis_kelamin = $('#jenis_kelamin').val();
            var jenis_kelamin_label = {
                'pria': 'Laki-laki'
                , 'wanita': 'Perempuan'
            } [jenis_kelamin] || 'Tidak Diketahui';

            var tanggal_lahir = $('#tanggal_lahir').val() ? formatDate($('#tanggal_lahir').val()) : '';

            // Disabilitas dan Hamil (sesuai sebelumnya)
            var disabilitasValue = $('#disabilitas').val();
            var disabilitasIcon = disabilitasValue === '1' ?
                '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

            var hamilValue = $('#hamil').val();
            var hamilIcon = hamilValue === '1' ?
                '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

            // Status Kawin mapping
            var status_kawin = $('#status_kawin').val();
            var status_kawin_label = {
                'belum_menikah': 'Belum Menikah'
                , 'menikah': 'Menikah'
                , 'cerai': 'Cerai'
                , 'cerai_mati': 'Cerai Mati'
            } [status_kawin] || 'Tidak Diketahui';

            var no_kk = $('#no_kk').val() || '';
            var jenis_peserta = $('#jenis_peserta').val() || '';
            var nama_kk = $('#nama_kk').val() || '';

            // Get the current number of rows to determine the next row number
            var rowCount = $('#list_peserta_kegiatan tbody tr').length + 1;

            // Create new table row with collected values
            var newRow = `
                <tr data-identitas="${identitas}" data-jenis-kelamin="${jenis_kelamin}" data-status-kawin="${status_kawin}" data-no-kk="${no_kk}" data-disabilitas="${disabilitasValue}" data-hamil="${hamilValue}">
                    <td class="text-center self-center text-nowrap py-2 rounded-start-3">${rowCount}</td>
                    <td class="text-nowrap py-2 border-start text-start">${identitas}</td>
                    <td class="text-nowrap py-2 border-start text-start">${nama}</td>
                    <td class="text-nowrap py-2 border-start text-start">${jenis_kelamin_label}</td>
                    <td class="text-nowrap py-2 border-start text-start">${tanggal_lahir}</td>
                    <td class="text-center self-center" data-disabilitas="${disabilitasValue}">
                        ${disabilitasIcon}
                    </td>
                    <td class="text-center self-center" data-hamil="${hamilValue}">
                        ${hamilIcon}
                    </td>
                    <td class="text-center text-nowrap py-2 border-start text-start">${status_kawin_label}</td>
                    <td class="bg-silver text-nowrap py-2 border-start text-start">${no_kk}</td>
                    <td class="text-center text-nowrap py-2 border-start text-start">${jenis_peserta}</td>
                    <td class="bg-silver text-nowrap py-2 px-4 border-start">${nama_kk}</td>
                    <td class="text-center text-nowrap py-0 border-start rounded-end-3">
                        <div class="button-container pt-1 self-center">
                            <button class="btn btn-sm btn-info edit-row"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-danger delete-row"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>`;

            // Append the new row to the table body
            $('#tableBody').append(newRow);

            // Save participants to localStorage
            saveParticipantsToStorage();

            // Clear form inputs
            $('#identitas').val('');
            $('#nama').val('');
            $('#jenis_kelamin').val('pria');
            $('#tanggal_lahir').val('');
            $('#disabilitas').val('0');
            $('#hamil').val('0');
            $('#status_kawin').val('belum_menikah');
            $('#no_kk').val('');
            $('#jenis_peserta').val('');
            $('#nama_kk').val('');

            // Close the modal
            $('#ModalTambahPeserta').modal('hide');
            saveFormDataToStorage();
        });

        // Delete row functionality
        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
            // Renumber the rows
            $('#list_peserta_kegiatan tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });

            // Update localStorage after deletion
            saveParticipantsToStorage();
        });

        // Update edit functionality to match new structure
        $(document).on('click', '.edit-row', function() {
            var row = $(this).closest('tr');

            // Populate modal with row data
            $('#identitas').val(row.data('identitas'));
            $('#nama').val(row.find('td:eq(2)').text());
            $('#jenis_kelamin').val(row.data('jenis-kelamin'));

            // Convert formatted date back to input date format
            var formattedDate = row.find('td:eq(4)').text();
            var parsedDate = parseFormattedDate(formattedDate);
            $('#tanggal_lahir').val(parsedDate);

            // Set disabilitas and hamil values
            $('#disabilitas').val(row.data('disabilitas'));
            $('#hamil').val(row.data('hamil'));

            $('#status_kawin').val(row.data('status-kawin'));
            $('#no_kk').val(row.data('no-kk'));
            $('#jenis_peserta').val(row.find('td:eq(9)').text());
            $('#nama_kk').val(row.find('td:eq(10)').text());

            // Open the modal
            $('#ModalTambahPeserta').modal('show');

            // Remove the original row
            row.remove();

            // Renumber the rows
            $('#list_peserta_kegiatan tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });

            // Update localStorage after editing
            saveParticipantsToStorage();
        });

        // Function to save participants to localStorage
        function saveParticipantsToStorage() {
            var participants = [];
            $('#list_peserta_kegiatan tbody tr').each(function() {
                participants.push({
                    identitas: $(this).data('identitas')
                    , nama: $(this).find('td:eq(2)').text()
                    , jenis_kelamin: $(this).data('jenis-kelamin')
                    , tanggal_lahir: $(this).find('td:eq(4)').text()
                    , disabilitas: $(this).data('disabilitas')
                    , hamil: $(this).data('hamil')
                    , status_kawin: $(this).data('status-kawin')
                    , no_kk: $(this).data('no-kk')
                    , jenis_peserta: $(this).find('td:eq(9)').text()
                    , nama_kk: $(this).find('td:eq(10)').text()
                });
            });

            localStorage.setItem('participantsData', JSON.stringify(participants));
        }

        // Function to load participants from localStorage
        function loadParticipantsFromStorage() {
            var savedParticipants = localStorage.getItem('participantsData');
            if (savedParticipants) {
                var participants = JSON.parse(savedParticipants);

                // Clear existing table body
                $('#tableBody').empty();

                // Recreate rows from saved data
                participants.forEach(function(participant, index) {
                    var jenis_kelamin_label = {
                        'pria': 'Laki-laki'
                        , 'wanita': 'Perempuan'
                    } [participant.jenis_kelamin] || 'Tidak Diketahui';

                    var disabilitasIcon = participant.disabilitas === '1' ?
                        '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                        '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

                    var hamilIcon = participant.hamil === '1' ?
                        '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                        '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

                    var status_kawin_label = {
                        'belum_menikah': 'Belum Menikah'
                        , 'menikah': 'Menikah'
                        , 'cerai': 'Cerai'
                        , 'cerai_mati': 'Cerai Mati'
                    } [participant.status_kawin] || 'Tidak Diketahui';

                    var newRow = `
                    <tr data-identitas="${participant.identitas}" data-jenis-kelamin="${participant.jenis_kelamin}" data-status-kawin="${participant.status_kawin}" data-no-kk="${participant.no_kk}" data-disabilitas="${participant.disabilitas}" data-hamil="${participant.hamil}">
                        <td class="text-center self-center text-nowrap py-2 rounded-start-3">${index + 1}</td>
                        <td class="text-nowrap py-2 border-start text-start">${participant.identitas}</td>
                        <td class="text-nowrap py-2 border-start text-start">${participant.nama}</td>
                        <td class="text-nowrap py-2 border-start text-start">${jenis_kelamin_label}</td>
                        <td class="text-nowrap py-2 border-start text-start">${participant.tanggal_lahir}</td>
                        <td class="text-center self-center" data-disabilitas="${participant.disabilitas}">
                            ${disabilitasIcon}
                        </td>
                        <td class="text-center self-center" data-hamil="${participant.hamil}">
                            ${hamilIcon}
                        </td>
                        <td class="text-center text-nowrap py-2 border-start text-start">${status_kawin_label}</td>
                        <td class="bg-silver text-nowrap py-2 border-start text-start">${participant.no_kk}</td>
                        <td class="text-center text-nowrap py-2 border-start text-start">${participant.jenis_peserta}</td>
                        <td class="bg-silver text-nowrap py-2 px-4 border-start">${participant.nama_kk}</td>
                        <td class="text-center text-nowrap py-0 border-start rounded-end-3">
                            <div class="button-container pt-1 self-center">
                                <button class="btn btn-sm btn-info edit-row"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-danger delete-row"><i class="bi bi-trash-fill"></i></button>
                            </div>
                        </td>
                    </tr>`;

                    $('#tableBody').append(newRow);
                });
            }
        }

        // Function to clear localStorage (optional, can be added to a clear/reset button)
        function clearParticipantsStorage() {
            localStorage.removeItem('participantsData');
            $('#tableBody').empty();
        }

        // Existing formatDate and parseFormattedDate functions remain the same
        function parseFormattedDate(formattedDate) {
            const months = {
                'Januari': '01'
                , 'Februari': '02'
                , 'Maret': '03'
                , 'April': '04'
                , 'Mei': '05'
                , 'Juni': '06'
                , 'Juli': '07'
                , 'Agustus': '08'
                , 'September': '09'
                , 'Oktober': '10'
                , 'November': '11'
                , 'Desember': '12'
            };

            const parts = formattedDate.split(' ');
            const day = parts[0].padStart(2, '0');
            const month = months[parts[1]];
            const year = parts[2];

            return `${year}-${month}-${day}`;
        }

        function formatDate(dateString) {
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'
                , 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const date = new Date(dateString);
            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();

            return `${day} ${month} ${year}`;
        }

        function clearStoredFormData() {
            localStorage.removeItem('pesertaFormData');
            localStorage.removeItem('participantsData');
            localStorage.removeItem('kegiatanFormData');

            // Reset the form and clear Summernote content
            $('#createKegiatan')[0].reset();
            $('#tableBody').empty();

            // Clear Summernote content
            $('.summernote').each(function() {
                $(this).summernote('reset');
                // console.info($(this), 'summernote removed')
            });
            $('.select2').val(null).trigger('change');
        }

        // Optional: Add a clear button or method to reset stored data
        $('#clearStorageButton').on('click', function() {
            clearStoredFormData();
            $('#createKegiatan').each(function() {
                inputFields = $(this).find('input, select, textarea').removeAttr('disabled');
                inputFields.each(function() {
                    $(this).val('');
                });
            });
        });
    });
</script>

<!-- Script for description.blade.php -->
<script>
    $(document).ready(function() {
        function calculateTotals() {
        // Calculate row totals and update respective fields
        $('tr').each(function() {
            let pria = parseInt($(this).find('input[id$="lakilaki"]').val()) || 0;
            let wanita = parseInt($(this).find('input[id$="perempuan"]').val()) || 0;
            let total = pria + wanita;
            $(this).find('input[id$="total"]').val(total);
        });

        // Calculate overall totals
        let totalPerempuan = 0;
        let totalLakilaki = 0;
        let totalAll = 0;

        totalPerempuan += parseInt($('#penerimamanfaatdewasaperempuan').val()) || 0;
        totalPerempuan += parseInt($('#penerimamanfaatlansiaperempuan').val()) || 0;
        totalPerempuan += parseInt($('#penerimamanfaatremajaperempuan').val()) || 0;
        totalPerempuan += parseInt($('#penerimamanfaatanakperempuan').val()) || 0;

        totalLakilaki += parseInt($('#penerimamanfaatdewasalakilaki').val()) || 0;
        totalLakilaki += parseInt($('#penerimamanfaatlansialakilaki').val()) || 0;
        totalLakilaki += parseInt($('#penerimamanfaatremajalakilaki').val()) || 0;
        totalLakilaki += parseInt($('#penerimamanfaatanaklakilaki').val()) || 0;

        totalAll += parseInt($('#penerimamanfaatdewasatotal').val()) || 0;
        totalAll += parseInt($('#penerimamanfaatlansiatotal').val()) || 0;
        totalAll += parseInt($('#penerimamanfaatremajatotal').val()) || 0;
        totalAll += parseInt($('#penerimamanfaatanaktotal').val()) || 0;

        // Update overall total fields
        $('#penerimamanfaatperempuantotal').val(totalPerempuan);
        $('#penerimamanfaatlakilakitotal').val(totalLakilaki);
        $('#penerimamanfaattotal').val(totalAll);
    }


        // Trigger calculateTotals on input change
        $('.calculate').on('input', function() {
            calculateTotals();
        });

        // Initial calculation
        calculateTotals();
    // });
    // $(document).ready(function() {
        var table = $('#peserta_kegiatan_summary').DataTable({
            width: '100%',
            columns: [{ width: '40%', targets: 0 }, { width: '20%', targets: 1 }, { width: '20%', targets: 2 }, { width: '20%', targets: 3 }],
            layout: {
                topStart: null,
                bottom: null,
                bottomStart: null,
                bottomEnd: null
            },
            searching: false,
            ordering: false, // 'sorting' should be 'ordering'
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: [0, 1, 2, 3]
                },
                { width: '40%', targets: 0 },
                { width: '20%', targets: 1 },
                { width: '20%', targets: 2 },
                { width: '20%', targets: 3 }
            ]
        });
        $('#submit_peserta').on('click', function(e) {
            e.preventDefault();
            var data = table.$('input, select').serialize();
            console.log(data);
            // Parse serialized data
            var params = new URLSearchParams(data);
            var parsedData = {
                dewasa: {
                    perempuan: params.get('penerimamanfaatdewasaperempuan'),
                    laki: params.get('penerimamanfaatdewasalakilaki'),
                    total: params.get('penerimamanfaatdewasatotal')
                },
                lansia: {
                    perempuan: params.get('penerimamanfaatlansiaperempuan'),
                    laki: params.get('penerimamanfaatlansialakilaki'),
                    total: params.get('penerimamanfaatlansiatotal')
                },
                remaja: {
                    perempuan: params.get('penerimamanfaatremajaperempuan'),
                    laki: params.get('penerimamanfaatremajalakilaki'),
                    total: params.get('penerimamanfaatremajatotal')
                },
                anak: {
                    perempuan: params.get('penerimamanfaatanakperempuan'),
                    laki: params.get('penerimamanfaatanaklakilaki'),
                    total: params.get('penerimamanfaatanaktotal')
                },
                total: {
                    perempuan: params.get('penerimamanfaatperempuantotal'),
                    laki: params.get('penerimamanfaatlakilakitotal'),
                    total: params.get('penerimamanfaattotal')
                }
            };

            // Create SweetAlert content
            var htmlContent = `
            <table style="width:100%; text-align:left;">
                <tr>
                <th>Category</th>
                <th>Female</th>
                <th>Male</th>
                <th>Total</th>
                </tr>
                <tr>
                <td>Dewasa</td>
                <td>${parsedData.dewasa.perempuan}</td>
                <td>${parsedData.dewasa.laki}</td>
                <td>${parsedData.dewasa.total}</td>
                </tr>
                <tr>
                <td>Lansia</td>
                <td>${parsedData.lansia.perempuan}</td>
                <td>${parsedData.lansia.laki}</td>
                <td>${parsedData.lansia.total}</td>
                </tr>
                <tr>
                <td>Remaja</td>
                <td>${parsedData.remaja.perempuan}</td>
                <td>${parsedData.remaja.laki}</td>
                <td>${parsedData.remaja.total}</td>
                </tr>
                <tr>
                <td>Anak-anak</td>
                <td>${parsedData.anak.perempuan}</td>
                <td>${parsedData.anak.laki}</td>
                <td>${parsedData.anak.total}</td>
                </tr>
                <tr>
                <th>Total</th>
                <th>${parsedData.total.perempuan}</th>
                <th>${parsedData.total.laki}</th>
                <th>${parsedData.total.total}</th>
                </tr>
            </table>
            `;

            // Display SweetAlert with dynamic content
            Swal.fire({
                title: 'Participant Data Summary',
                html: htmlContent,
                icon: 'info',
                confirmButtonText: 'OK'
            });

        });

    });
</script>

