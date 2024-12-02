{{--
<!---->
<!-- Dewasa -->
<div class="form-group row">
    <label for="pria" class="col-sm-2 col-md-2 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.peserta.pria') }}</label>
<div class="col-sm-4 col-md-4 col-lg-4 order-2 order-md-2 self-center">
    <input type="text" class="form-control" id="pria" placeholder="0" name="pria">
</div>
<label for="wanita" class="col-sm-2 col-md-2 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-right text-lg-right self-center">{{ __('cruds.kegiatan.peserta.wanita') }}</label>
<div class="col-sm-4 col-md-4 col-lg-4 order-4 order-md-4 self-center">
    <input type="text" class="form-control" id="wanita" placeholder="0" name="wanita">
</div>
</div>
<!-- Anak-anak -->
<div class="form-group row">
    <label for="laki" class="col-sm-2 col-md-2 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.peserta.laki') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-2 order-md-2 self-center">
        <input type="text" class="form-control" id="laki" placeholder="0" name="laki">
    </div>
    <label for="perempuan" class="col-sm-2 col-md-2 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-right text-lg-right self-center">{{ __('cruds.kegiatan.peserta.perempuan') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="perempuan" placeholder="0" name="perempuan">
    </div>
</div>
<!-- Anak-anak -->
<div class="form-group row">
    <label for="total" class="col-sm-2 col-md-2 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.peserta.total') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-2 order-md-2 self-center">
        <input type="text" class="form-control" id="total" placeholder="0" name="total">
    </div>
</div>
--}}


<!-- Penulis Laporan Kegiatan-->
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
        // Load existing participants from localStorage on page load
        loadParticipantsFromStorage();
        loadFormDataFromStorage();

        // Save form data to localStorage whenever an input changes
        $('#createKegiatan input, #createKegiatan select, #createKegiatan date, #pesertaForm input, #pesertaForm select').on('change', function() {
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

        // Function to save ALL form data to localStorage
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

                //// Peserta Form Fields (from modal)
                // identitas: $('#identitas').val(),
                // no_kk: $('#no_kk').val(),
                // nama: $('#nama').val(),
                // jenis_kelamin: $('#jenis_kelamin').val(),
                // tanggal_lahir: $('#tanggal_lahir').val(),
                // disabilitas: $('#disabilitas').val(),
                // hamil: $('#hamil').val(),
                // status_kawin: $('#status_kawin').val(),
                // jenis_peserta: $('#jenis_peserta').val(),
                // nama_kk: $('#nama_kk').val()
            };

            localStorage.setItem('kegiatanFormData', JSON.stringify(formData));
        }

        // Function to load form data from localStorage
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

                // // Populate Peserta form fields (modal)
                // $('#identitas').val(formData.identitas || '');
                // $('#no_kk').val(formData.no_kk || '');
                // $('#nama').val(formData.nama || '');
                // $('#jenis_kelamin').val(formData.jenis_kelamin || 'pria');
                // $('#tanggal_lahir').val(formData.tanggal_lahir || '');
                // $('#disabilitas').val(formData.disabilitas || '0');
                // $('#hamil').val(formData.hamil || '0');
                // $('#status_kawin').val(formData.status_kawin || 'belum_menikah');
                // $('#jenis_peserta').val(formData.jenis_peserta || '');
                // $('#nama_kk').val(formData.nama_kk || '');
            }
        }

        function clearStoredFormData() {
            localStorage.removeItem('pesertaFormData');
            localStorage.removeItem('participantsData');

            $('#createKegiatan')[0].reset();
            $('#pesertaForm')[0].reset();
            $('#tableBody').empty();
        }

        // Optional: Add a clear button or method to reset stored data
        $('#clearStorageButton').on('click', function() {
            clearStoredFormData();
        });

        // Modify existing save function to clear form data after saving
        // $('#saveModalData').click(function() {
        //     // Existing save logic remains the same

        //     // Clear stored form data after successful save
        //     localStorage.removeItem('pesertaFormData');
        // });
    });

</script>

@endpush
