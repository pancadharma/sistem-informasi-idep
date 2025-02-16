<!-- javascript to create kegiatan first -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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
                    // toolbar: [
                    //     ['font', ['bold', 'italic', 'underline', 'clear']],
                    //     ['table', ['table']],
                    //     ['color', ['color']],
                    //     ['paragraph', ['paragraph']],
                    //     ['view', ['fullscreen', 'codeview']],
                    // ],
                    inheritPlaceholder: true,
                    tabDisable: true,
                    codeviewFilter: false,
                });
                $(this).data('initialized', true); // Mark this textarea as initialized
            }
        });
    });
</script>

<!-- javascript to push javascript to stack('basic_tab_js') -->
<script>
    // Next button
    document.getElementById('next-button').addEventListener('click', function(e) {
        e.preventDefault();
        var tabs = document.querySelectorAll('#details-kegiatan-tab .nav-link');
        var activeTab = document.querySelector('#details-kegiatan-tab .nav-link.active');
        var nextTabIndex = Array.from(tabs).indexOf(activeTab) + 1;

        if (nextTabIndex < tabs.length) {
            tabs[nextTabIndex].click();
        }
    });
</script>

<!-- JS for Modal Program -->
<script>
    $(document).ready(function() {
        // Variable to hold the selected program ID
        let programId = null;

        // Event handler when a program is selected in the modal
        $('#list_program_kegiatan tbody').on('click', '.select-program', function(e) {
            e.preventDefault();

            // Fetch the selected program details
            programId = $(this).data('program-id');
            const programKode = $(this).data('program-kode');
            const programNama = $(this).data('program-nama');

            // Update the hidden input and display fields
            $('#program_id').val(programId).trigger('change');
            $('#kode_program').val(programKode);
            $('#nama_program').val(programNama).prop('disabled', true);

            $('#programoutcomeoutputactivity_id, #kode_kegiatan').val('').trigger('change');
            $('#kode_kegiatan').val('').trigger('change');
            $('#nama_kegiatan').val('').trigger('change');

            // Blur the currently focused element
            $('#nama_kegiatan').focus();
            setTimeout(function() {
                $('#ModalDaftarProgram').modal('hide');
            }, 200);


        });

        // Handle opening the activities modal
        $('#kode_kegiatan').click(function(e) {
            if (!programId) {
                e.preventDefault(); // Prevent modal from opening
                Toast.fire({
                    icon: "warning",
                    title: "Opssss...",
                    text: "Please select a program first.",
                    timer: 2000,
                    position: "top-end",
                    timerProgressBar: true,
                });

                $('#ModalDaftarProgram').modal('show');
                return false;
            } else {
                fetchProgramActivities(programId);
            }
        });


        // Function to fetch activities based on programId
        function fetchProgramActivities(programId) {
            const url = '{{ route('api.program.kegiatan', ':id') }}'.replace(':id', programId);

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "{{ __('cruds.activity.search') }}...",
                        timer: 2000,
                        position: "top-end",
                        timerProgressBar: true,
                    });
                },
                success: function(data) {
                    setTimeout(() => {
                        populateModalWithActivities(data);
                    }, 500);
                },
                error: function() {
                    Toast.fire({
                        icon: "error",
                        title: "Failed to fetch activities.",
                    });
                }
            });
        }

        function populateModalWithActivities(data) {
            const tbody = $('#list_program_out_activity tbody'); // Ensure tbody selector points to the correct table
            tbody.empty(); // Clear existing rows
            // Iterate over the activity data

            if (data.length > 0) {
                data.forEach(activity => {
                    const row = `
                        <tr data-id="${activity.id}" data-kode="${activity.kode}" data-nama="${activity.nama}" data-deskripsi="${activity.deskripsi}" data-indikator="${activity.indikator}" data-target="${activity.target}">
                            <td>${activity.kode}</td>
                            <td>${activity.nama}</td>
                            <td>${activity.deskripsi}</td>
                            <td>${activity.indikator}</td>
                            <td>${activity.target}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info select-activity" data-id="${activity.id}">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row); // Append the row to the table body
                });
            } else {
                const row = `
                    <tr>
                        <td colspan="6" class="dt-empty text-center">
                            {{ __('global.no_results') }}
                        </td>
                    </tr>
                `;
                tbody.append(row); // Append the row to the table body
            }

            // Show the modal
            $('#ModalDaftarProgramActivity').modal('show');
        }
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
            fasepelaporan: $('#fasepelaporan').val(),

            provinsi_id: $('#provinsi_id').val(),
            kabupaten_id: $('#kabupaten_id').val(),

            sektor_kegiatan: $('#sektor_kegiatan').val(),
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
        $('.summernote').each(function() {
            const id = $(this).attr('id');
            formData[id] = $(this).summernote('isEmpty') ? '' : $(this).summernote('code');
        });

        // Include select2 selected values
        $('.select2').each(function() {
            const id = $(this).attr('id');
            formData[id] = $(this).val(); // Store the selected value(s)
        });

        // Save form data to localStorage
        localStorage.setItem('kegiatanFormData', JSON.stringify(formData));
    }

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
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
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
            $('#fasepelaporan').val(formData.fasepelaporan || '');

            $('#provinsi_id').val(formData.provinsi_id || '');
            $('#kabupaten_id').val(formData.kabupaten_id || '');


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
            $('.summernote').each(function() {
                const id = $(this).attr('id');
                if (!id) {
                    console.error('Found Summernote element with undefined id:', this);
                    return;
                }
                const value = formData[id] || '';
                $(this).summernote('code', value);
            });

            if (formData.jenis_kegiatan) {
                var apiUrl = $('#jeniskegiatan_id').data('api-url');
                $.ajax({
                    url: apiUrl,
                    method: 'GET',
                    data: {
                        id: formData.jenis_kegiatan
                    },
                    success: function(response) {
                        if (response.data && response.data.length > 0) {
                            var item = response.data[0];
                            var newOption = new Option(item.nama, item.id, true, true);
                            $('#jeniskegiatan_id')
                                .append(newOption)
                                .trigger('change');
                        }
                    }
                });
            }
            var uniqueId = Date.now();
            // exclude some select2 field id attribute
            $('.select2').not('#jeniskegiatan_id, #provinsi_id, #kabupaten_id, #kecamatan, #kelurahan, [id^="provinsi-"], [id^="kabupaten-"], [id^="kecamatan-"], [id^="kelurahan-"]').each(function() {
                $
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
                        data: {
                            id: valueArray
                        },
                        success: function(data) {
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
                        error: function(error) {
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
            $('.select2').each(function() {
                var fieldId = $(this).attr('id');
                initializeSelect2WithDynamicUrl(fieldId);
            });
        }
    }

    $(document).ready(function() {
        loadParticipantsFromStorage();
        loadFormDataFromStorage();

        $('#createKegiatan').on('change', 'input, select, textarea', function() {
            saveFormDataToStorage();
        });

        // Specifically handle Select2 events
        $(document).on('select2:select select2:unselect', '.select2', function() {
            saveFormDataToStorage();
        });

        // Specifically handle Summernote changes
        $(document).on('summernote.change', '.summernote', function() {
            saveFormDataToStorage();
        });

        // Attach change event listeners to input, select, date, select2, and summernote fields
        $('#createKegiatan').on('change', 'input, select, textarea, .select2, .summernote', function() {
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
                'pria': 'Laki-laki',
                'wanita': 'Perempuan'
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
                'belum_menikah': 'Belum Menikah',
                'menikah': 'Menikah',
                'cerai': 'Cerai',
                'cerai_mati': 'Cerai Mati'
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
                    identitas: $(this).data('identitas'),
                    nama: $(this).find('td:eq(2)').text(),
                    jenis_kelamin: $(this).data('jenis-kelamin'),
                    tanggal_lahir: $(this).find('td:eq(4)').text(),
                    disabilitas: $(this).data('disabilitas'),
                    hamil: $(this).data('hamil'),
                    status_kawin: $(this).data('status-kawin'),
                    no_kk: $(this).data('no-kk'),
                    jenis_peserta: $(this).find('td:eq(9)').text(),
                    nama_kk: $(this).find('td:eq(10)').text()
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
                        'pria': 'Laki-laki',
                        'wanita': 'Perempuan'
                    } [participant.jenis_kelamin] || 'Tidak Diketahui';

                    var disabilitasIcon = participant.disabilitas === '1' ?
                        '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                        '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

                    var hamilIcon = participant.hamil === '1' ?
                        '<i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>' :
                        '<i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>';

                    var status_kawin_label = {
                        'belum_menikah': 'Belum Menikah',
                        'menikah': 'Menikah',
                        'cerai': 'Cerai',
                        'cerai_mati': 'Cerai Mati'
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
                'Januari': '01',
                'Februari': '02',
                'Maret': '03',
                'April': '04',
                'Mei': '05',
                'Juni': '06',
                'Juli': '07',
                'Agustus': '08',
                'September': '09',
                'Oktober': '10',
                'November': '11',
                'Desember': '12',
            };

            const parts = formattedDate.split(' ');
            const day = parts[0].padStart(2, '0');
            const month = months[parts[1]];
            const year = parts[2];

            return `${year}-${month}-${day}`;
        }

        function formatDate(dateString) {
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
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
            localStorage.removeItem('KegiatanLokasi');

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

            let totalBeneficiariesPerempuan = 0;
            let totalBeneficiariesLakilaki = 0;
            let totalBeneficiariesTotal = 0;

            totalBeneficiariesPerempuan += parseInt($('#penerimamanfaatdisabilitasperempuan').val()) || 0;
            totalBeneficiariesPerempuan += parseInt($('#penerimamanfaatnondisabilitasperempuan').val()) || 0;
            totalBeneficiariesPerempuan += parseInt($('#penerimamanfaatmarjinalperempuan').val()) || 0;

            totalBeneficiariesLakilaki += parseInt($('#penerimamanfaatdisabilitaslakilaki').val()) || 0;
            totalBeneficiariesLakilaki += parseInt($('#penerimamanfaatnondisabilitaslakilaki').val()) || 0;
            totalBeneficiariesLakilaki += parseInt($('#penerimamanfaatmarjinallakilaki').val()) || 0;

            totalBeneficiariesTotal += parseInt($('#penerimamanfaatdisabilitastotal').val()) || 0;
            totalBeneficiariesTotal += parseInt($('#penerimamanfaatnondisabilitastotal').val()) || 0;
            totalBeneficiariesTotal += parseInt($('#penerimamanfaatmarjinaltotal').val()) || 0;

            // Update overall total fields
            $('#total_beneficiaries_perempuan').val(totalBeneficiariesPerempuan);
            $('#total_beneficiaries_lakilaki').val(totalBeneficiariesLakilaki);
            $('#beneficiaries_difable_total').val(totalBeneficiariesTotal);

        }


        // Trigger calculateTotals on input change
        $('.calculate').on('input', function() {
            calculateTotals();
        });

        $('.hitung').on('input', function() {
            calculateTotals();
        });

        // Initial calculation
        calculateTotals();

        var table = $('#peserta_kegiatan_summary').DataTable({
            width: '100%',
            layout: {
                topStart: null,
                bottom: null,
                bottomStart: null,
                bottomEnd: null
            },
            searching: false,
            ordering: false, // 'sorting' should be 'ordering'
            columnDefs: [{
                    searchable: false,
                    orderable: false,
                    targets: [0, 1, 2, 3]
                },
                {
                    width: '40%',
                    targets: 0
                },
                {
                    width: '20%',
                    targets: 1
                },
                {
                    width: '20%',
                    targets: 2
                },
                {
                    width: '20%',
                    targets: 3
                }
            ]
        });
        // alert modal for data peserta
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
            <table style="width:100%; text-align:left;" class="table-sm table-borderless">
                <tr>
                <th>{{ __('cruds.kegiatan.peserta.kategori') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.pria') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.total') }}</th>
                </tr>
                <tr>
                <td>{{ __('cruds.kegiatan.peserta.adult') }}</td>
                <td>${parsedData.dewasa.perempuan}</td>
                <td>${parsedData.dewasa.laki}</td>
                <td>${parsedData.dewasa.total}</td>
                </tr>
                <tr>
                <td>{{ __('cruds.kegiatan.peserta.elderly') }}</td>
                <td>${parsedData.lansia.perempuan}</td>
                <td>${parsedData.lansia.laki}</td>
                <td>${parsedData.lansia.total}</td>
                </tr>
                <tr>
                <td>{{ __('cruds.kegiatan.peserta.teen') }}</td>
                <td>${parsedData.remaja.perempuan}</td>
                <td>${parsedData.remaja.laki}</td>
                <td>${parsedData.remaja.total}</td>
                </tr>
                <tr>
                <td>{{ __('cruds.kegiatan.peserta.kids') }}</td>
                <td>${parsedData.anak.perempuan}</td>
                <td>${parsedData.anak.laki}</td>
                <td>${parsedData.anak.total}</td>
                </tr>
                <tr>
                <td>{{ __('cruds.kegiatan.peserta.grand_total') }}</td>
                <th>${parsedData.total.perempuan}</th>
                <th>${parsedData.total.laki}</th>
                <th>${parsedData.total.total}</th>
                </tr>
            </table>
            `;

            // Display SweetAlert with dynamic content
            Swal.fire({
                title: "{{ __('cruds.kegiatan.peserta.info') }}",
                html: htmlContent,
                icon: 'info',
                confirmButtonText: 'OK'
            });

        });

    });
</script>

<!-- JS for drop down jenis kegiatan -->

<script>
    $(document).ready(function() {
        $('#jeniskegiatan_id').select2({
            placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.basic.jenis_kegiatan') }}',
            ajax: {
                url: '{{ route('api.kegiatan.jenis_kegiatan') }}',
                dataType: 'json',
                processResults: function(data) {
                    var results = [];
                    $.each(data, function(group, options) {
                        var optgroup = {
                            text: group,
                            children: []
                        };
                        $.each(options, function(id, text) {
                            optgroup.children.push({
                                id: id,
                                text: text
                            });
                        });
                        results.push(optgroup);
                    });
                    return {
                        results: results
                    };
                }
            }
        });


        function updateToggleDisplay(toggleInput) {
            var isChecked = toggleInput.is(':checked');
            var displayTarget = toggleInput.data('display');
            $(`#${displayTarget}`).text(isChecked ? 'Yes' : 'No'); // added # selector
            // set the input value to 1 or 0
            // set the input value to yes or no
            toggleInput.val(isChecked ? 'yes' : 'no');
            // toggleInput.val(isChecked ? 1 : 0);
        }
        // Initial update when the page loads
        $('.toggle-input').each(function() {
            updateToggleDisplay($(this));
        });

        $('.toggle-input').on('change', function() {
            updateToggleDisplay($(this));
        });

        $('#fasepelaporan').select2({
            placeholder: '{{ __('global.pleaseSelect') }}',
        });
        $('#status').select2({
            placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.basic.status_kegiatan') }}',
        });

        // Map dropdown values to form field prefixes
        const formFieldMap = {
            "1": "assessment",
            "2": "sosialisasi",
            "3": "pelatihan",
            "4": "pembelanjaan",
            "5": "pengembangan",
            "6": "kampanye",
            "7": "pemetaan",
            "8": "monitoring",
            "9": "kunjungan",
            "10": "konsultasi",
            "11": "lainnya",
        };
        const formContainer = $('#dynamic-form-container');

        // Event handler for jenis_kegiatan dropdown change
        $('#jeniskegiatan_id').on('change', function() {
            const selectedValue = $(this).val();
            const fieldPrefix = formFieldMap[selectedValue];
            formContainer.empty();
            if (fieldPrefix) {
                const formFields = getFormFields(fieldPrefix);
                formContainer.append(formFields);

                $('.summernote').each(function() {
                    const placeholder = $(this).attr('placeholder');
                    $('.summernote').summernote({
                        inheritPlaceholder: true,
                        height: 150,
                        width: '100%',
                        codeviewFilter: false,
                    })
                    $(this).summernote('placeholder', placeholder);
                    // console.info("data", placeholder)
                });
            }

        });

        function getFormFields(fieldPrefix) {
            let formFields = '';
            const fields = {
                assessment: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_assessmentyangterlibat') }}',
                        label: '{{ __('cruds.kegiatan.hasil.assessmentyangterlibat') }}',
                        name: 'assessmentyangterlibat',
                        type: 'textarea',
                        placeholder: '{{ __('cruds.kegiatan.hasil.assessmentyangterlibat') }}'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_assessmenttemuan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.assessmenttemuan') }}',
                        name: 'assessmenttemuan',
                        type: 'textarea',
                        placeholder: '{{ __('cruds.kegiatan.hasil.assessmenttemuan') }}'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_assessmenttambahan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.assessmenttambahan') }}',
                        name: 'assessmenttambahan',
                        type: 'radio',
                        placeholder: '{{ __('cruds.kegiatan.hasil.assessmenttambahan') }}'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_assessmenttambahan_ket') }}',
                        label: '{{ __('cruds.kegiatan.hasil.assessmenttambahan_ket') }}',
                        name: 'assessmenttambahan_ket',
                        type: 'textarea',
                        placeholder: '{{ __('cruds.kegiatan.hasil.assessmenttambahan_ket') }}'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_assessmentkendala') }}',
                        label: '{{ __('cruds.kegiatan.hasil.assessmentkendala') }}',
                        name: 'assessmentkendala',
                        type: 'textarea',
                        placeholder: '{{ __('cruds.kegiatan.hasil.assessmentkendala') }}'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_assessmentisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.assessmentisu') }}',
                        name: 'assessmentisu',
                        type: 'textarea',
                        placeholder: '{{ __('cruds.kegiatan.hasil.assessmentisu') }}'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_assessmentpembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.assessmentpembelajaran') }}',
                        name: 'assessmentpembelajaran',
                        type: 'textarea',
                        placeholder: '{{ __('cruds.kegiatan.hasil.assessmentpembelajaran') }}'
                    },
                ],
                sosialisasi: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_sosialisasiyangterlibat') }}',
                        label: '{{ __('cruds.kegiatan.hasil.sosialisasiyangterlibat') }}',
                        name: 'sosialisasiyangterlibat',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_sosialisasitemuan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.sosialisasitemuan') }}',
                        name: 'sosialisasitemuan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_sosialisasitambahan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.sosialisasitambahan') }}',
                        name: 'sosialisasitambahan',
                        type: 'radio'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_sosialisasitambahan_ket') }}',
                        label: '{{ __('cruds.kegiatan.hasil.sosialisasitambahan_ket') }}',
                        name: 'sosialisasitambahan_ket',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_sosialisasikendala') }}',
                        label: '{{ __('cruds.kegiatan.hasil.sosialisasikendala') }}',
                        name: 'sosialisasikendala',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_sosialisasiisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.sosialisasiisu') }}',
                        name: 'sosialisasiisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_sosialisasipembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.sosialisasipembelajaran') }}',
                        name: 'sosialisasipembelajaran',
                        type: 'textarea'
                    },
                ],
                pelatihan: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_pelatihanpelatih') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pelatihanpelatih') }}',
                        name: 'pelatihanpelatih',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pelatihanhasil') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pelatihanhasil') }}',
                        name: 'pelatihanhasil',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pelatihandistribusi') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pelatihandistribusi') }}',
                        name: 'pelatihandistribusi',
                        type: 'radio'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pelatihandistribusi_ket') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pelatihandistribusi_ket') }}',
                        name: 'pelatihandistribusi_ket',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pelatihanrencana') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pelatihanrencana') }}',
                        name: 'pelatihanrencana',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pelatihanunggahan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pelatihanunggahan') }}',
                        name: 'pelatihanunggahan',
                        type: 'radio'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pelatihanisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pelatihanisu') }}',
                        name: 'pelatihanisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pelatihanpembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pelatihanpembelajaran') }}',
                        name: 'pelatihanpembelajaran',
                        type: 'textarea'
                    },
                ],
                pembelanjaan: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaandetailbarang') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaandetailbarang') }}',
                        name: 'pembelanjaandetailbarang',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanmulai') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaanmulai') }}',
                        name: 'pembelanjaanmulai',
                        type: 'datetime-local'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanselesai') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaanselesai') }}',
                        name: 'pembelanjaanselesai',
                        type: 'datetime-local'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaandistribusimulai') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaandistribusimulai') }}',
                        name: 'pembelanjaandistribusimulai',
                        type: 'datetime-local'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaandistribusiselesai') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaandistribusiselesai') }}',
                        name: 'pembelanjaandistribusiselesai',
                        type: 'datetime-local'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanterdistribusi') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaanterdistribusi') }}',
                        name: 'pembelanjaanterdistribusi',
                        type: 'radio'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanakandistribusi') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaanakandistribusi') }}',
                        name: 'pembelanjaanakandistribusi',
                        type: 'radio'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanakandistribusi_ket') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaanakandistribusi_ket') }}',
                        name: 'pembelanjaanakandistribusi_ket',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaankendala') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaankendala') }}',
                        name: 'pembelanjaankendala',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaanisu') }}',
                        name: 'pembelanjaanisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanpembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pembelanjaanpembelajaran') }}',
                        name: 'pembelanjaanpembelajaran',
                        type: 'textarea'
                    },
                ],
                pengembangan: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_pengembanganjeniskomponen') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pengembanganjeniskomponen') }}',
                        name: 'pengembanganjeniskomponen',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pengembanganberapakomponen') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pengembanganberapakomponen') }}',
                        name: 'pengembanganberapakomponen',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pengembanganlokasikomponen') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pengembanganlokasikomponen') }}',
                        name: 'pengembanganlokasikomponen',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pengembanganyangterlibat') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pengembanganyangterlibat') }}',
                        name: 'pengembanganyangterlibat',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pengembanganrencana') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pengembanganrencana') }}',
                        name: 'pengembanganrencana',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pengembangankendala') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pengembangankendala') }}',
                        name: 'pengembangankendala',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pengembanganisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pengembanganisu') }}',
                        name: 'pengembanganisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pengembanganpembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pengembanganpembelajaran') }}',
                        name: 'pengembanganpembelajaran',
                        type: 'textarea'
                    },
                ],
                kampanye: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyeyangdikampanyekan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyeyangdikampanyekan') }}',
                        name: 'kampanyeyangdikampanyekan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyejenis') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyejenis') }}',
                        name: 'kampanyejenis',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyebentukkegiatan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyebentukkegiatan') }}',
                        name: 'kampanyebentukkegiatan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyeyangterlibat') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyeyangterlibat') }}',
                        name: 'kampanyeyangterlibat',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyeyangdisasar') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyeyangdisasar') }}',
                        name: 'kampanyeyangdisasar',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyejangkauan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyejangkauan') }}',
                        name: 'kampanyejangkauan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyerencana') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyerencana') }}',
                        name: 'kampanyerencana',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyekendala') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyekendala') }}',
                        name: 'kampanyekendala',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyeisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyeisu') }}',
                        name: 'kampanyeisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kampanyepembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kampanyepembelajaran') }}',
                        name: 'kampanyepembelajaran',
                        type: 'textarea'
                    },
                ],
                pemetaan: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_pemetaanyangdihasilkan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pemetaanyangdihasilkan') }}',
                        name: 'pemetaanyangdihasilkan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pemetaanluasan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pemetaanluasan') }}',
                        name: 'pemetaanluasan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pemetaanunit') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pemetaanunit') }}',
                        name: 'pemetaanunit',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pemetaanyangterlibat') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pemetaanyangterlibat') }}',
                        name: 'pemetaanyangterlibat',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pemetaanrencana') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pemetaanrencana') }}',
                        name: 'pemetaanrencana',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pemetaanisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pemetaanisu') }}',
                        name: 'pemetaanisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_pemetaanpembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.pemetaanpembelajaran') }}',
                        name: 'pemetaanpembelajaran',
                        type: 'textarea'
                    },
                ],
                monitoring: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringyangdipantau') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringyangdipantau') }}',
                        name: 'monitoringyangdipantau',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringdata') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringdata') }}',
                        name: 'monitoringdata',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringyangterlibat') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringyangterlibat') }}',
                        name: 'monitoringyangterlibat',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringmetode') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringmetode') }}',
                        name: 'monitoringmetode',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringhasil') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringhasil') }}',
                        name: 'monitoringhasil',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringkegiatanselanjutnya') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringkegiatanselanjutnya') }}',
                        name: 'monitoringkegiatanselanjutnya',
                        type: 'radio'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringkegiatanselanjutnya_ket') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringkegiatanselanjutnya_ket') }}',
                        name: 'monitoringkegiatanselanjutnya_ket',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringkendala') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringkendala') }}',
                        name: 'monitoringkendala',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringisu') }}',
                        name: 'monitoringisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_monitoringpembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.monitoringpembelajaran') }}',
                        name: 'monitoringpembelajaran',
                        type: 'textarea'
                    },
                ],
                kunjungan: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_kunjunganlembaga') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kunjunganlembaga') }}',
                        name: 'kunjunganlembaga',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kunjunganpeserta') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kunjunganpeserta') }}',
                        name: 'kunjunganpeserta',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kunjunganyangdilakukan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kunjunganyangdilakukan') }}',
                        name: 'kunjunganyangdilakukan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kunjunganhasil') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kunjunganhasil') }}',
                        name: 'kunjunganhasil',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kunjunganpotensipendapatan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kunjunganpotensipendapatan') }}',
                        name: 'kunjunganpotensipendapatan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kunjunganrencana') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kunjunganrencana') }}',
                        name: 'kunjunganrencana',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kunjungankendala') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kunjungankendala') }}',
                        name: 'kunjungankendala',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kunjunganisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kunjunganisu') }}',
                        name: 'kunjunganisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_kunjunganpembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.kunjunganpembelajaran') }}',
                        name: 'kunjunganpembelajaran',
                        type: 'textarea'
                    },
                ],
                konsultasi: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_konsultasilembaga') }}',
                        label: '{{ __('cruds.kegiatan.hasil.konsultasilembaga') }}',
                        name: 'konsultasilembaga',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_konsultasikomponen') }}',
                        label: '{{ __('cruds.kegiatan.hasil.konsultasikomponen') }}',
                        name: 'konsultasikomponen',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_konsultasiyangdilakukan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.konsultasiyangdilakukan') }}',
                        name: 'konsultasiyangdilakukan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_konsultasihasil') }}',
                        label: '{{ __('cruds.kegiatan.hasil.konsultasihasil') }}',
                        name: 'konsultasihasil',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_konsultasipotensipendapatan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.konsultasipotensipendapatan') }}',
                        name: 'konsultasipotensipendapatan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_konsultasirencana') }}',
                        label: '{{ __('cruds.kegiatan.hasil.konsultasirencana') }}',
                        name: 'konsultasirencana',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_konsultasikendala') }}',
                        label: '{{ __('cruds.kegiatan.hasil.konsultasikendala') }}',
                        name: 'konsultasikendala',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_konsultasiisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.konsultasiisu') }}',
                        name: 'konsultasiisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_konsultasipembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.konsultasipembelajaran') }}',
                        name: 'konsultasipembelajaran',
                        type: 'textarea'
                    },
                ],
                lainnya: [{
                        i: '{{ __('cruds.kegiatan.hasil.i_lainnyamengapadilakukan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.lainnyamengapadilakukan') }}',
                        name: 'lainnyamengapadilakukan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_lainnyadampak') }}',
                        label: '{{ __('cruds.kegiatan.hasil.lainnyadampak') }}',
                        name: 'lainnyadampak',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_lainnyasumberpendanaan') }}',
                        label: '{{ __('cruds.kegiatan.hasil.lainnyasumberpendanaan') }}',
                        name: 'lainnyasumberpendanaan',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_lainnyasumberpendanaan_ket') }}',
                        label: '{{ __('cruds.kegiatan.hasil.lainnyasumberpendanaan_ket') }}',
                        name: 'lainnyasumberpendanaan_ket',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_lainnyayangterlibat') }}',
                        label: '{{ __('cruds.kegiatan.hasil.lainnyayangterlibat') }}',
                        name: 'lainnyayangterlibat',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_lainnyarencana') }}',
                        label: '{{ __('cruds.kegiatan.hasil.lainnyarencana') }}',
                        name: 'lainnyarencana',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_lainnyakendala') }}',
                        label: '{{ __('cruds.kegiatan.hasil.lainnyakendala') }}',
                        name: 'lainnyakendala',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_lainnyaisu') }}',
                        label: '{{ __('cruds.kegiatan.hasil.lainnyaisu') }}',
                        name: 'lainnyaisu',
                        type: 'textarea'
                    },
                    {
                        i: '{{ __('cruds.kegiatan.hasil.i_lainnyapembelajaran') }}',
                        label: '{{ __('cruds.kegiatan.hasil.lainnyapembelajaran') }}',
                        name: 'lainnyapembelajaran',
                        type: 'textarea'
                    },
                ],

            };
            if (fields[fieldPrefix]) {
                fields[fieldPrefix].forEach(field => {
                    const fieldId = `${fieldPrefix}-${field.name}`;
                    if (field.type === 'checkbox') {
                        formFields += `
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">
                                    ${field.label}
                                    <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="${field.i}"></i>
                                </label>

                                <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input toggle-input" data-display="${fieldId}-display" name="${field.name}" id="${fieldId}">
                                        <label class="custom-control-label" for="${fieldId}"></label>
                                        <span id="${fieldId}-display">No</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else if (field.type === 'textarea') {
                        formFields += `
                            <div class="form-group row">
                            <label for="${field.name}"
                                class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label"
                                data-toggle="tooltip" title="${field.label}">
                                ${field.label}
                                <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="${field.i}"></i>
                            </label>
                            <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                                <textarea name="${field.name}" id="${fieldId}" class="form-control summernote" rows="2" data-placeholder="${field.i}" placeholder="${field.i}"></textarea>
                                </div>
                            </div>
                        `;
                    } else if (field.type === 'radio') {
                        const selectedValue = field.value || '0'; // Default to '0' if not set
                        formFields += `
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1" data-toggle="tooltip"
                                title="${field.label}">${field.label}
                                    <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="${field.i}"></i>
                                </label>
                                <div class="col-sm col-md col-lg order-2 order-md-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="${fieldId}-yes" name="${field.name}" value="1" class="custom-control-input custom-control-input-success" ${selectedValue == '1' ? 'checked' : ''}>
                                        <label class="custom-control-label" for="${fieldId}-yes">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="${fieldId}-no" name="${field.name}" value="0" class="custom-control-input custom-control-input-danger" ${selectedValue == '0' ? 'checked' : ''}>
                                        <label class="custom-control-label" for="${fieldId}-no">No</label>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        formFields += `
                        <div class="form-group row">
                                <label for="${field.name}" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label" data-toggle="tooltip" title="${field.label}">${field.label}
                                    <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="${field.i}"></i>
                                </label>
                                <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                                    <input type="${field.type}" class="form-control" name="${field.name}" id="${fieldId}">
                                </div>
                            </div>
                        `;
                    }

                });
            }
            return formFields;
        }

    });
</script>

<!-- fase kegiatan js to fecth data an autoamtically pre-populate fase -->
<script>
    $(document).ready(function() {

        // Function to fetch and update fase pelaporan
        function fetchFasePelaporan(programOutcomeOutputActivityId) {
            if (!programOutcomeOutputActivityId) {
                $('#fasepelaporan').val(1).trigger('change');
                $('#fasepelaporan option').prop('disabled', false);
                return;
            }
            $.ajax({
                url: '{{ url('kegiatan/api/fase-pelaporan') }}/' + programOutcomeOutputActivityId,
                method: 'GET',
                success: function(data) {
                    $('#fasepelaporan').val(data.next_fase_pelaporan).trigger('change');
                    $('#fasepelaporan option').prop('disabled', false); // Enable all options first
                    $.each(data.disabled_fase, function(index, value) {
                        $('#fasepelaporan option[value="' + value + '"]').prop('disabled', true);
                    });
                }
            });
        }
        //listen to program id change
        $('#program_id').on('change', function() {
            const programOutcomeOutputActivityId = $('#programoutcomeoutputactivity_id').val();
            fetchFasePelaporan(programOutcomeOutputActivityId);
        });
        //listen to program out activity id change
        $('#programoutcomeoutputactivity_id').on('change', function() {
            const programOutcomeOutputActivityId = $(this).val();
            fetchFasePelaporan(programOutcomeOutputActivityId);
        });
        //Initial Load
        const initialProgramOutcomeOutputActivityId = $('#programoutcomeoutputactivity_id').val();
        fetchFasePelaporan(initialProgramOutcomeOutputActivityId);
    });
</script>

