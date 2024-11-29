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
    <div class="{{-- card card-outline card-info  --}}table-responsive">
        <table id="list_peserta_kegiatan" class="table table-bordered table-striped table-hover ajaxTable datatable-kegiatan" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('No.') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.identitas') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.nama') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.jenis_kelamin') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.tanggal_lahir') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.disabilitas') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.hamil') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.status_kawin') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.no_kk') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.jenis_peserta') }}</th>
                    <th>{{ __('cruds.kegiatan.peserta.nama_kk') }}</th>
                    <th>{{ __('global.action') }}</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                {{-- <tr>
                    <td>1</td>
                    <td>Hard Code No. identitas</td>
                    <td>Nama nya Siapa </td>
                    <td>Laki-laki</td>
                    <td>10 Mei 1999</td>
                    <td class="text-center self-center">
                        <i class="bi bi-check-lg btn-success p-1 text-center self-center"></i>
                    </td>
                    <td class="text-center self-center">
                        <i class="bi bi-x-lg btn-warning p-1 text-center self-center"></i>
                    </td>
                    <td>Belum Menikah</td>
                    <td>No KK. 102039930202 </td>
                    <td>Jenis Peserta</td>
                    <td>Nama Kepala Keluarga</td>
                    <td></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Hard Code No. identitas</td>
                    <td>Nama nya Siapa </td>
                    <td>Laki-laki</td>
                    <td>10 Mei 1999</td>
                    <td class="text-center self-center">
                        <i class="bi bi-check-lg btn-success p-1"></i>
                    </td>
                    <td class="text-center self-center">
                        <i class="bi bi-x-lg btn-warning p-1"></i>
                    </td>
                    <td>Belum Menikah</td>
                    <td>No KK. 102039930202 </td>
                    <td>Jenis Peserta</td>
                    <td>Nama Kepala Keluarga</td>
                    <td></td>
                </tr> --}}
            </tbody>
        </table>
    </div>

</div>

@include('tr.kegiatan.tabs.peserta-modal')

@push('basic_tab_js')
<script defer>
    $(document).ready(function() {
        $('#saveModalData').click(function() {
            // Collect form values
            var identitas = $('#identitas').val() || '';
            var nama = $('#nama').val() || '';

            // Jenis Kelamin mapping
            var jenis_kelamin = $('#jenis_kelamin').val();
            var jenis_kelamin_label = {
                'pria': 'Laki-laki', 'wanita': 'Perempuan'
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
                'belum_menikah': 'Belum Menikah', 'menikah': 'Menikah', 'cerai': 'Cerai', 'cerai_matiW': 'Cerai Mati'
            }
                [status_kawin] || 'Tidak Diketahui';

            var no_kk = $('#no_kk').val() || '';
            var jenis_peserta = $('#jenis_peserta').val() || '';
            var nama_kk = $('#nama_kk').val() || '';

            // Get the current number of rows to determine the next row number
            var rowCount = $('#list_peserta_kegiatan tbody tr').length + 1;

            // Create new table row with collected values
            var newRow = `
            <tr data-identitas="${identitas}"
                data-jenis-kelamin="${jenis_kelamin}"
                data-status-kawin="${status_kawin}"
                data-no-kk="${no_kk}"
                data-disabilitas="${disabilitasValue}"
                data-hamil="${hamilValue}">
                <td>${rowCount}</td>
                <td>${identitas}</td>
                <td>${nama}</td>
                <td>${jenis_kelamin_label}</td>
                <td>${tanggal_lahir}</td>
                <td class="text-center self-center" data-disabilitas="${disabilitasValue}">
                    ${disabilitasIcon}
                </td>
                <td class="text-center self-center" data-hamil="${hamilValue}">
                    ${hamilIcon}
                </td>
                <td>${status_kawin_label}</td>
                <td>${no_kk}</td>
                <td>${jenis_peserta}</td>
                <td>${nama_kk}</td>
                <td>
                    <button class="btn btn-sm btn-info edit-row"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-sm btn-danger delete-row"><i class="bi bi-trash-fill"></i></button>
                </td>
            </tr>`;

            // Append the new row to the table body
            $('#tableBody').append(newRow);

            // Clear form inputs
            $('#identitas').val('');
            $('#nama').val('');
            $('#jenis_kelamin').val('');
            $('#tanggal_lahir').val('');
            $('#disabilitas').val('0');
            $('#hamil').val('0');
            $('#status_kawin').val('');
            $('#no_kk').val('');
            $('#jenis_peserta').val('');
            $('#nama_kk').val('');

            // Close the modal
            $('#ModalTambahPeserta').modal('hide');
        });

        // Delete row functionality
        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
            // Renumber the rows
            $('#list_peserta_kegiatan tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
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
        });
    });


    function parseFormattedDate(formattedDate) {
        const months = {
            'Januari': '01', 'Februari': '02', 'Maret': '03', 'April': '04',
            'Mei': '05', 'Juni': '06', 'Juli': '07', 'Agustus': '08',
            'September': '09', 'Oktober': '10', 'November': '11', 'Desember': '12'
        };

        const parts = formattedDate.split(' ');
        const day = parts[0].padStart(2, '0');
        const month = months[parts[1]];
        const year = parts[2];

        return `${year}-${month}-${day}`;
    }

    function formatDate(dateString) {
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const date = new Date(dateString);
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();

        return `${day} ${month} ${year}`;
    }
</script>

@endpush
