<!-- Peserta Kegiatan-->
<div class="form-group row tambah_peserta" id="tambah_peserta">
    <div class="col-12 pl-0 pr-0">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalTambahPeserta" title="{{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}">
            <i class="bi bi-person-plus"></i>
            {{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}
        </button>
    </div>
</div>
<div class="form-group row list_peserta">
    <div class="table-responsive">
        <table id="list_peserta_kegiatan" class="table table-sm table-borderless table-hover mb-0 datatable-kegiatan" style="width:100%">
            <thead style="background-color: #149387 !important">
                <tr class="align-middle text-center undefined">
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 rounded-start-3 border-secondary">{{ __('No.') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.identitas') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.nama') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.jenis_kelamin') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.tanggal_lahir') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.disabilitas') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.hamil') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.status_kawin') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.no_kk') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.jenis_peserta') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.nama_kk') }}</th>
                    <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary rounded-end-3">{{ __('global.action') }}</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>
    </div>
</div>

@include('tr.kegiatan.tabs.peserta-modal')

@push('basic_tab_js')

<script defer>
    $(document).ready(function() {
        loadParticipantsFromStorage();
        loadFormDataFromStorage();

        // Save form data to localStorage whenever an input changes
        // $('#createKegiatan input, #createKegiatan select, #createKegiatan date, #pesertaForm input, #pesertaForm select').on('change', function() {
        //     saveFormDataToStorage();
        // });

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
            alert('summernote changed');
        });

        // Attach change event listeners to input, select, date, select2, and summernote fields
        $('#createKegiatan').on('change', 'input, select, textarea, .select2, .summernote', function () {
            saveFormDataToStorage();
            // this works
        });

        // Additionally, capture changes from select2 and summernote
        $('.select2').on('select2:select select2:unselect', function() {
            saveFormDataToStorage();
            // alert('select2 changed');
        });
        $('.summernote').on('summernote.change', function() {
            saveFormDataToStorage();
        });



        //Save data to local storage for peserta in modal into table
        //use temporary table to store data in local storage for pesertaa
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

        //// Function to save ALL form data to localStorage
        function saveFormDataToStorage() {
            var formData = {
                // Basic Kegiatan Form Fields
                kode_kegiatan: $('#kode_kegiatan').val(),
                nama_kegiatan: $('#nama_kegiatan').val(),
                nama_desa: $('#nama_desa').val(),
                lokasi: $('#lokasi').val(),
                lat: $('#lat').val(),
                longitude: $('#longitude').val(),
                tanggalmulai: $('#tanggalmulai').val(),
                tanggalselesai: $('#tanggalselesai').val(),
                nama_mitra: $('#nama_mitra').val(),

                // Additional Fields
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

            localStorage.setItem('kegiatanFormData', JSON.stringify(formData));
        }


        //// Function to load form data from localStorage
        function loadFormDataFromStorage() {
            var savedFormData = localStorage.getItem('kegiatanFormData');
            if (savedFormData) {
                var formData = JSON.parse(savedFormData);

                // Populate Kegiatan form fields
                $('#kode_kegiatan').val(formData.kode_kegiatan || '');
                $('#nama_kegiatan').val(formData.nama_kegiatan || '');
                $('#nama_desa').val(formData.nama_desa || '');
                $('#lokasi').val(formData.lokasi || '');
                $('#lat').val(formData.lat || '');
                $('#longitude').val(formData.longitude || '');
                $('#tanggalmulai').val(formData.tanggalmulai || '');
                $('#tanggalselesai').val(formData.tanggalselesai || '');
                $('#nama_mitra').val(formData.nama_mitra || '');

                // Populate Additional Fields
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

                // Restore Summernote content
                $('.summermnote').each(function () {
                    const id = $(this).attr('id');
                    if (formData[id]) {
                        $(this).summernote('code', formData[id]);
                    }
                });

                // Dynamically handle all select2 fields with a data attribute for API endpoints
                $('.select2').each(function () {
                    const $select = $(this);
                    const apiUrl = $select.data('api-url'); // Fetch API URL from data attribute
                    const savedValue = formData[$select.attr('id')]; // Get saved value using the field's ID

                    if (apiUrl) {
                        $select.select2({
                            ajax: {
                                url: apiUrl,
                                dataType: 'json',
                                delay: 250, // Wait before triggering the search
                                data: function (params) {
                                    return {
                                        search: params.term, // Send the search term
                                        page: params.page || 1 // Handle pagination
                                    };
                                },
                                processResults: function (data, params) {
                                    // Adjust pagination if needed
                                    params.page = params.page || 1;
                                    return {
                                        results: data.data.map(item => ({
                                            id: item.id,
                                            text: item.nama // Adjust according to your API response structure
                                        })),
                                        pagination: {
                                            more: data.current_page < data.last_page
                                        }
                                    };
                                },
                                cache: true
                            },
                            closeOnSelect: true,
                            dropdownPosition: 'below',
                            placeholder: 'Please select an option',
                            allowClear: true
                        });

                        // Set the saved value(s) if available
                        if (savedValue) {
                            const validIds = data.data.map(option => option.id);
                            const selectedValues = Array.isArray(savedValue)
                                ? savedValue.filter(val => validIds.includes(val))
                                : validIds.includes(savedValue) ? savedValue : null;

                            $select.val(selectedValues).trigger('change');
                        }
                    }
                });

            }
        }

        function clearStoredFormData() {
            localStorage.removeItem('pesertaFormData');
            localStorage.removeItem('participantsData');

            // Reset the form and clear Summernote content
            $('#createKegiatan')[0].reset();
            $('#tableBody').empty();

            // Clear Summernote content
            $('.summermnote').each(function() {
                $(this).summernote('reset');
            });
        }

        // Optional: Add a clear button or method to reset stored data
        $('#clearStorageButton').on('click', function() {
            clearStoredFormData();
        });
    });

</script>

@endpush
