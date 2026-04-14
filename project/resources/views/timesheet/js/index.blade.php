<script>
$(function () {

    // ===============================
    // DATA MASTER (WAJIB ADA)
    // ===============================
    const donors   = @json($donors ?? []);
    const programs = @json($programs ?? []);
    let rowIndex = 0;

    // ===============================
    // INIT SELECT2 (MODAL SAFE)
    // ===============================
    function initSelect2(context = document) {
        $(context).find('.donor, .program, .work-location').select2({
            width: '100%',
            allowClear: true,
            placeholder: '-- pilih --',
            dropdownParent: $('#modalDay')
        });
    }

    // ===============================
    // TEMPLATE ROW
    // ===============================
    function rowTemplate(index) {

        let donorOpt = `
            <optgroup label="Umum">
                <option value="general">General</option>
                <option value="others">Others</option>
            </optgroup>
            <optgroup label="Donor Program">
                ${donors.map(d =>
                    `<option value="${d.id}">${d.nama}</option>`
                ).join('')}
            </optgroup>
        `;

        let progOpt = `
            <optgroup label="Umum">
                <option value="administratif">Administratif</option>
                <option value="bisnis_unit">Bisnis Unit</option>
                <option value="program_internal">Program Internal</option>
                <option value="others">Others</option>
            </optgroup>
            <optgroup label="Program-based">
                ${programs.map(p =>
                    `<option value="${p.id}">${p.nama}</option>`
                ).join('')}
            </optgroup>
        `;

        return `
       <tr>
           <td>
               <input type="text"
                    name="activities[${index}][location_detail]"
                    class="form-control location-detail"
                    placeholder="Area / lokasi detail">
           </td>

           <td>
               <select name="activities[${index}][work_location]"
                       class="form-control work-location">
                   <option value="">-- pilih --</option>
                   <option value="lapangan">Lapangan</option>
                   <option value="kantor">Kantor</option>
                   <option value="rumah">Rumah</option>
                   <option value="other">Other</option>
               </select>
           </td>

           <td>
               <input type="number"
                      name="activities[${index}][minutes]"
                      class="form-control jam"
                      step="0.25"
                      min="0"
                      value="0">
           </td>

           <td>
               <select name="activities[${index}][program_id]"
                       class="form-control program">
                   <option value=""></option>
                   ${progOpt}
               </select>
           </td>


           <td>
               <select name="activities[${index}][donor_id]"
                       class="form-control donor">
                   <option value=""></option>
                   ${donorOpt}
               </select>
           </td>
           <td>
               <textarea name="activities[${index}][activity]"
                         class="form-control kegiatan"
                         rows="2"
                         placeholder="Tuliskan kegiatan..."></textarea>
           </td>

           <td class="text-center">
               <button type="button"
                       class="btn btn-sm btn-danger remove-row">
                   ✕
               </button>
           </td>
       </tr>`;
    }

    // ===============================
    // HITUNG TOTAL JAM
    // ===============================
    function totalJam() {
        let total = 0;
        $('.jam').each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        return total;
    }

    // ===============================
    // STATUS HARI (KERJA / LIBUR / DOC)
    // ===============================
    function toggleByStatus() {
        const status = $('#day-status').val();
        const isKerja = status === 'kerja';

        $('#activityTable input, #activityTable select, #activityTable textarea')
            .prop('disabled', !isKerja);

        $('#addRow').prop('disabled', !isKerja);

        if (!isKerja) {
            $('#activityTable tbody').html('');
            rowIndex = 0;
        }
    }

    $('#day-status').on('change', toggleByStatus);
// ===============================
// OPEN MODAL (SUDAH SUPPORT WEEKEND & CUTI)
// ===============================
const fetchDayUrl = "{{ route('timesheet.day.get', ':date') }}";

$('.btn-input-day').on('click', function () {

    const date = $(this).data('date');

    $('#modal-date-text').text(date);
    $('#modal-work-date').val(date);
    $('#activityTable tbody').html('');

    // 🔥 DETEKSI WEEKEND
    let d = new Date(date);
    let day = d.getDay(); // 0 = minggu, 6 = sabtu

    $.get(fetchDayUrl.replace(':date', date), function (res) {

        // =====================================
        // 🔥 TAMBAHAN PENTING (JANGAN DIHAPUS)
        // simpan info apakah ada aktivitas lama
        // =====================================
        const hasActivities = res.entries.length > 0;
        $('#has-existing-activities').val(hasActivities ? 1 : 0);


        // =====================================
        // 1. PRIORITAS DATA DARI SERVER
        // =====================================
        if (res.day_status && res.day_status !== 'kosong') {

            $('#day-status').val(res.day_status).trigger('change');
            $('textarea[name="note"]').val(res.note || '');

        } 
        // =====================================
        // 2. JIKA BELUM ADA → PAKAI RULE AUTO
        // =====================================
        else {

            if (day === 0 || day === 6) {
                $('#day-status').val('libur').trigger('change');
            } else {
                $('#day-status').val('kerja').trigger('change');
            }
        }

        // =====================================
        // RENDER AKTIVITAS
        // =====================================

        // kalau libur / doc / cuti / sakit → tidak perlu baris aktivitas
        if (['libur', 'doc', 'cuti', 'sakit'].includes($('#day-status').val())) {
            $('#modalDay').modal('show');
            toggleByStatus();
            return;
        }

        // isi data lama kalau ada
        if (res.entries.length > 0) {

            res.entries.forEach(row => {

                const tr = $(rowTemplate(rowIndex));

                tr.find('.location-detail').val(row.location_detail);
                tr.find('.work-location').val(row.work_location);

                tr.find('.jam').val(row.minutes);

                tr.find('.donor').val(row.donor_id).trigger('change');
                tr.find('.program').val(row.program_id).trigger('change');

                tr.find('.kegiatan').val(row.activity);

                $('#activityTable tbody').append(tr);

                initSelect2(tr);

                rowIndex++;
            });

        } else {

            // default 1 baris kosong kalau kerja
            const tr = $(rowTemplate(rowIndex));
            $('#activityTable tbody').append(tr);
            initSelect2(tr);
            rowIndex++;
        }

        toggleByStatus();

        $('#modalDay').modal('show');
    });
});

    // ===============================
    // ADD ROW
    // ===============================
    $('#addRow').on('click', function () {
        const row = $(rowTemplate(rowIndex));
        $('#activityTable tbody').append(row);
        initSelect2(row);
        rowIndex++;
    });

    // ===============================
    // REMOVE ROW
    // ===============================
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });

    // ===============================
    // AUTO GROW TEXTAREA
    // ===============================
    $(document).on('input', '.kegiatan', function () {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    // ===============================
    // VALIDASI JAM (REALTIME)
    // ===============================
    $(document).on('input', '.jam', function () {
        let val = parseFloat($(this).val()) || 0;

        // kelipatan 0.25
        if (val % 0.25 !== 0) {
            $(this).addClass('is-invalid');
            return;
        } else {
            $(this).removeClass('is-invalid');
        }

        // max 24 jam
        if (totalJam() > 24) {
            Swal.fire({
                icon: 'warning',
                title: 'Jam Berlebih',
                text: 'Total jam per hari maksimal 24 jam'
            });
            $(this).val(0);
        }
    });

    // ===============================
    // SIMPAN HARIAN (AJAX)
    // ===============================
    $('#formDay').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const workDate = $('#modal-work-date').val();
        const status   = $('#day-status').val();

        // VALIDASI WAJIB KETERANGAN UNTUK CUTI/DOC/SAKIT
        if (['cuti','doc','sakit'].includes(status)) {
            const noteVal = $('textarea[name="note"]').val().trim();
            if (!noteVal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keterangan Wajib',
                    text: 'Mohon isi keterangan untuk status Cuti / DOC / Sakit'
                });
                return;
            }
        }

        let activities = [];

        $('#activityTable tbody tr').each(function () {

            let minutes = parseFloat($(this).find('.jam').val()) || 0;
            let activityText = $(this).find('.kegiatan').val();

            if (status === 'kerja' && (minutes <= 0 || !activityText)) {
                return;
            }

            activities.push({
                location_detail: $(this).find('input[name*="[location_detail]"]').val(),
                work_location: $(this).find('select[name*="[work_location]"]').val(),
                minutes: minutes,
                donor_id: $(this).find('select[name*="[donor_id]"]').val(),
                program_id: $(this).find('select[name*="[program_id]"]').val(),
                activity: activityText
            });
        });

        // =========================================
        // 🔥 KONFIRMASI KHUSUS GANTI KE NON KERJA
        // =========================================
        const isNonKerja = ['libur','cuti','doc','sakit'].includes(status);
        const hasExisting = $('#has-existing-activities').val() == '1';

        if (isNonKerja && hasExisting) {

            Swal.fire({
                icon: 'warning',
                title: 'Ganti Status?',
                text: 'Data aktivitas sebelumnya akan dihapus karena hari diubah menjadi LIBUR/CUTI/DOC',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(result => {

                if (result.isConfirmed) {
                    doSave();   // ✅ FIX DISINI BRO
                }

            });

            return;
        }

        // =========================================
        // VALIDASI NORMAL KERJA
        // =========================================
        if (status === 'kerja' && activities.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Jam Kosong',
                text: 'Minimal harus ada 1 aktivitas kerja'
            });
            return;
        }

        doSave();

        // =========================================
        // FUNGSI SIMPAN (AJAX)
        // =========================================
        function doSave() {

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: {
                    _token: form.find('input[name="_token"]').val(),
                    timesheet_id: form.find('input[name="timesheet_id"]').val(),
                    work_date: workDate,
                    day_status: status,
                    activities: activities,
                    note: $('textarea[name="note"]').val(),
                },

                success: function (res) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 800,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalDay').modal('hide');
                        window.location.href = "{{ route('timesheet.index') }}";
                    });
                },

                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON?.message ?? 'Terjadi kesalahan'
                    });
                }
            });
        }

    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#btnSubmitTimesheet').on('click', function () {

        Swal.fire({
            title: 'Yakin submit?',
            text: 'Setelah submit, data akan masuk proses approval dan tidak bisa diubah lagi.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Submit',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {

            if (!result.isConfirmed) return;

            // loading
            Swal.fire({
                title: 'Mengirim...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const form = $('#formSubmitTimesheet');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),

                success: function (res) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message || 'Timesheet berhasil disubmit',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });

                },

                error: function (xhr) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON?.message 
                            ?? 'Terjadi kesalahan saat submit'
                    });

                }
            });

        });

    });

    // =======================================
    // LOAD DONOR BERDASARKAN PROGRAM
    // =======================================
    $(document).on('change', '.program', function () {

        const programId = $(this).val();
        const donorSelect = $(this).closest('tr').find('.donor');

        // reset dulu
        donorSelect.html('<option value=""></option>');

        // =============================
        // 1. JIKA PROGRAM STATIC
        // =============================
        if (!programId || isNaN(programId)) {

            // 🔥 RESET KERAS SELECT2
            donorSelect.val(null).trigger('change');
            donorSelect.empty();

            let opt = `
                <option value=""></option>
                <optgroup label="Umum">
                    <option value="general">General</option>
                    <option value="others">Others</option>
                </optgroup>
            `;

            donorSelect.html(opt);

            // init ulang biar select2 refresh total
            donorSelect.select2({
                width: '100%',
                allowClear: true,
                placeholder: '-- pilih --',
                dropdownParent: $('#modalDay')
            });

            return;
        }

        // =============================
        // 2. JIKA PROGRAM BASE
        // =============================
        $.get(
            "{{ route('timesheet.program.donors', ':id') }}".replace(':id', programId)
        )
        .done(function (res) {

            donorSelect.val(null).trigger('change');

            let opt = `<optgroup label="Donor Program">`;

            if (res.donors && res.donors.length > 0) {
                res.donors.forEach(d => {
                    opt += `<option value="${d.id}">${d.nama}</option>`;
                });
            } else {
                opt += `<option value="">(Tidak ada donor)</option>`;
            }

            opt += `</optgroup>`;

            donorSelect.html(opt).trigger('change');

        })
        .fail(function () {

            donorSelect.html(`
                <optgroup label="Donor Program">
                    <option value="">Gagal load donor</option>
                </optgroup>
            `).trigger('change');

        });

    });

    $('#day-status').on('change', function(){

        const st = $(this).val();

        if (['doc','cuti','sakit'].includes(st)) {
            $('#noteBox').show();
        } else {
            $('#noteBox').hide();
            $('textarea[name="note"]').val('');
        }

    });

});
</script>