<style>
    /* Sembunyikan panah (spinner) pada input number menit */
    input[type="number"].jam::-webkit-inner-spin-button,
    input[type="number"].jam::-webkit-outer-spin-button,
    input[type="number"].input-jam-display::-webkit-inner-spin-button,
    input[type="number"].input-jam-display::-webkit-outer-spin-button,
    input[type="number"].input-mnt-display::-webkit-inner-spin-button,
    input[type="number"].input-mnt-display::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"].jam,
    input[type="number"].input-jam-display,
    input[type="number"].input-mnt-display {
        -moz-appearance: textfield;
    }
</style>
<script>
$(function () {

    // ===============================
    // DATA MASTER (WAJIB ADA)
    // ===============================
    const donors   = @json($donors ?? []);
    const programs = @json($programs ?? []);
    let rowIndex = 0;

    // ===============================
    // OPTIMASI: PRE-COMPILE TEMPLATE
    // ===============================
    // Compile string HTML satu kali saja agar tidak memberatkan loop saat menambah baris
    const donorOptHtml = `
        <optgroup label="Non Donor">
            <option value="internal_funds">Internal Funds</option>
            <option value="donations">Donations</option>
            <option value="others">Others</option>
        </optgroup>
        <optgroup label="Donor Based">
            ${donors.map(d => `<option value="${d.id}">${d.nama}</option>`).join('')}
        </optgroup>
    `;

    const progOptHtml = `
        <optgroup label="Non Donor">
            <option value="benih_alami">Benih Alami</option>
            <option value="sapi_bergulir">Sapi Bergulir</option>
            <option value="train_with_idep">Train With IDEP</option>
            <option value="visit_idep">Visit IDEP</option>
            <option value="consult_idep">Consult IDEP</option>
            <option value="bwp_project">BWP Project</option>
            <option value="kios_idep">Kios IDEP</option>
            <option value="capacity_building">Capacity Building</option>
            <option value="garden_day">Garden Day</option>
            <option value="others">Others</option>
        </optgroup>
        <optgroup label="Donor Based">
            ${programs.map(p => `<option value="${p.id}">${p.nama}</option>`).join('')}
        </optgroup>
    `;

    // ===============================
    // DRAFT HELPER
    // ===============================
    function getDraftKey() {
        const timesheetId = $('input[name="timesheet_id"]').val() || 'new';
        const workDate = $('#modal-work-date').val() || 'unknown';
        return `timesheet_draft_${timesheetId}_${workDate}`;
    }

    function saveDraft() {
        const rows = [];

        $('#activityTable tbody tr').each(function () {
            rows.push({
                location_detail: $(this).find('.location-detail').val(),
                work_location: $(this).find('.work-location').val(),
                minutes: $(this).find('.jam').val(),
                program_id: $(this).find('.program').val(),
                donor_id: $(this).find('.donor').val(),
                activity: $(this).find('.kegiatan').val(),
            });
        });

        const payload = {
            day_status: $('#day-status').val(),
            note: $('textarea[name="note"]').val(),
            rows: rows,
            updated_at: Date.now()
        };

        localStorage.setItem(getDraftKey(), JSON.stringify(payload));
    }

    function clearDraft() {
        localStorage.removeItem(getDraftKey());
    }

    function restoreDraft() {
        const draft = localStorage.getItem(getDraftKey());
        if (!draft) return null;

        try {
            return JSON.parse(draft);
        } catch (e) {
            return null;
        }
    }

    // ===============================
    // FUNGSI RENDER ROWS (DRY)
    // ===============================
    function renderActivities(rows) {
        const $rowsToAdd = [];
        rows.forEach(row => {
            const tr = $(rowTemplate(rowIndex));
            tr.find('.location-detail').val(row.location_detail || '');
            tr.find('.work-location').val(row.work_location || '');

            // Memecah total menit ke input visual Jam & Mnt
            const totalMins = parseInt(row.minutes) || 0;
            const h = Math.floor(totalMins / 60);
            const m = totalMins % 60;
            tr.find('.input-jam-display').val(h > 0 ? h : '');
            tr.find('.input-mnt-display').val(m > 0 ? m : '');
            tr.find('.jam').val(totalMins);

            tr.find('.program').val(row.program_id || '');
            tr.find('.donor').val(row.donor_id || '');
            tr.find('.kegiatan').val(row.activity || '');
            $rowsToAdd.push(tr);
            rowIndex++;
        });
        $('#activityTable tbody').append($rowsToAdd);
        $rowsToAdd.forEach((tr, idx) => {
            initSelect2(tr);
            tr.find('.program').trigger('change', ['from_render']);
            tr.find('.donor').val(rows[idx].donor_id).trigger('change', ['from_render']);
        });
    }

    function applyDraft(data) {
        if (!data) return;

        $('#day-status').val(data.day_status).trigger('change', ['from_render']);
        $('textarea[name="note"]').val(data.note || '');

        $('#activityTable tbody').empty();
        rowIndex = 0;

        if (data.rows && data.rows.length) {
            renderActivities(data.rows);
        }
    }

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
               <div class="d-flex justify-content-center align-items-center" style="gap: 8px;">
                   <div class="text-center">
                       <div style="font-size: 11px; color: #6c757d; margin-bottom: 2px;">Jam</div>
                       <input type="number" class="form-control text-center input-jam-display" min="0" placeholder="0" style="width: 60px; padding: 4px;">
                   </div>
                   <div class="text-center">
                       <div style="font-size: 11px; color: #6c757d; margin-bottom: 2px;">Mnt</div>
                       <input type="number" class="form-control text-center input-mnt-display" min="0" max="59" placeholder="0" style="width: 60px; padding: 4px;">
                   </div>
                   
                   <!-- Hidden input menyimpan total menit asli untuk dikirim ke Backend -->
                   <input type="hidden" name="activities[${index}][minutes]" class="jam" value="0">
           </td>

           <td>
               <select name="activities[${index}][program_id]"
                       class="form-control program">
                   <option value=""></option>
                   ${progOptHtml}
               </select>
           </td>


           <td>
               <select name="activities[${index}][donor_id]"
                       class="form-control donor">
                   <option value=""></option>
                   ${donorOptHtml}
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
    // HITUNG TOTAL MENIT
    // ===============================
    function totalMenit() {
        let total = 0;
        $('.jam').each(function () {
            total += parseInt($(this).val()) || 0;
        });
        return total;
    }

    function formatWaktu(menit) {
        let jam = Math.floor(menit / 60);
        let sisaMenit = Math.round(menit % 60);
        let text = [];
        if (jam > 0) text.push(jam + ' jam');
        if (sisaMenit > 0 || jam === 0) text.push(sisaMenit + ' mnt');
        return text.join(' ');
    }

    // ===============================
    // STATUS HARI (KERJA / LIBUR / DOC)
    // ===============================
    function toggleByStatus() {
        const status = $('#day-status').val();
        const isKerja = status === 'kerja';

        $('#activityTable').find('input, select, textarea').prop('disabled', !isKerja);

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

$(document).on('click', '.btn-input-day', function () {

    const date = $(this).data('date');

    $('#modal-date-text').text(date);
    $('#modal-work-date').val(date);
    $('#activityTable tbody').html('');
    rowIndex = 0;

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

        const draftData = restoreDraft();
        const hasDraft = draftData && draftData.rows && draftData.rows.length > 0;

        // =====================================
        // 1. PRIORITAS DATA DARI SERVER
        // =====================================
        if (res.day_status && res.day_status !== 'kosong') {

            $('#day-status').val(res.day_status).trigger('change', ['from_render']);
            $('textarea[name="note"]').val(res.note || '');

        }
        // =====================================
        // 2. JIKA BELUM ADA → PAKAI RULE AUTO
        // =====================================
        else {

            if (hasDraft) {
                applyDraft(draftData);
                $('#modalDay').modal('show');
                toggleByStatus();
                return;
            }
            if (day === 0 || day === 6) {
                $('#day-status').val('libur').trigger('change', ['from_render']);
            } else {
                $('#day-status').val('kerja').trigger('change', ['from_render']);
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
            renderActivities(res.entries);

        } else {

            // default 1 baris kosong kalau kerja
            const tr = $(rowTemplate(rowIndex));
            $('#activityTable tbody').append(tr);
            initSelect2(tr);
            rowIndex++;
        }

        if (hasActivities && hasDraft) {
            Swal.fire({
                icon: 'question',
                title: 'Draft Ditemukan',
                text: 'Ada draft yang belum tersimpan untuk tanggal ini. Lanjutkan draft terakhir?',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan Draft',
                cancelButtonText: 'Gunakan Data Tersimpan'
            }).then((result) => {
                if (result.isConfirmed) {
                    applyDraft(draftData);
            } else {
                clearDraft(); // Hapus draf agar tidak mengganggu lagi di masa depan
                }

                toggleByStatus();
                $('#modalDay').modal('show');
            });
            return;
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
    // ===============================
    // AUTO SAVE DRAFT
    // ===============================
    // OPTIMASI: Debounce untuk meringankan beban localStorage saat user mengetik
    function debounce(func, wait = 500) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    const debouncedSaveDraft = debounce(saveDraft, 500);

    $(document).on('input change', '#formDay input, #formDay textarea, #formDay select', function (e, context) {
        if (context === 'from_render') return; // Abaikan jika ini perubahan hasil render sistem
        debouncedSaveDraft();
    });

    $('#addRow').on('click', function () {
        debouncedSaveDraft();
    });

    $(document).on('click', '.remove-row', function () {
        const row = $(this).closest('tr');
        
        Swal.fire({
            title: 'Hapus Aktivitas?',
            text: "Baris aktivitas ini akan dihapus dari form.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                row.remove();
                debouncedSaveDraft();
            }
        });
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
    $(document).on('input', '.input-jam-display, .input-mnt-display', function () {
        let tr = $(this).closest('tr');
        
        let h = parseInt(tr.find('.input-jam-display').val()) || 0;
        let m = parseInt(tr.find('.input-mnt-display').val()) || 0;

        if (h < 0) { h = 0; tr.find('.input-jam-display').val(''); }
        if (m < 0) { m = 0; tr.find('.input-mnt-display').val(''); }

        let currentRowTotal = (h * 60) + m;
        tr.find('.jam').val(currentRowTotal);

        let tm = totalMenit();
        // max 24 jam (1440 menit)
        if (tm > 1440) {
            Swal.fire({
                icon: 'warning',
                title: 'Waktu Berlebih',
                text: 'Total waktu maksimal 24 jam (1440 menit). Input saat ini: ' + formatWaktu(tm)
            });
            tr.find('.input-jam-display').val('');
            tr.find('.input-mnt-display').val('');
            tr.find('.jam').val(0);
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

            let minutes = parseInt($(this).find('.jam').val()) || 0;
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

                    clearDraft();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 800,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalDay').modal('hide');
                        // window.location.href = "{{ route('timesheet.index') }}";
                        window.location.reload();
                    });
                },

                error: function (xhr) {

                    if (xhr.status === 419) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Session Expired',
                            text: 'Halaman terlalu lama terbuka. Silakan refresh halaman. Draft aktivitas kamu sudah disimpan.'
                        });
                        return;
                    }

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

                    // 🔥 TANGKAP STATUS EMAIL DARI CONTROLLER
                    if (res.email_sent) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        // Jika masuk ke database tapi email gagal
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tersubmit (Email Gagal)',
                            text: res.message,
                            footer: '<small class="text-danger">Silakan beri tahu atasan Anda secara manual.</small>'
                        }).then(() => {
                            window.location.reload();
                        });
                    }

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
            donorSelect.val(null).trigger('change', ['from_render']);
            donorSelect.empty();

            let opt = `
                <option value=""></option>
                <optgroup label="Non Donor">
                    <option value="internal_funds">Internal Funds</option>
                    <option value="donations">Donations</option>
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

            donorSelect.val(null).trigger('change', ['from_render']);

            let opt = `<optgroup label="Donor Based">`;

            if (res.donors && res.donors.length > 0) {
                res.donors.forEach(d => {
                    opt += `<option value="${d.id}">${d.nama}</option>`;
                });
            } else {
                opt += `<option value="">(Tidak ada donor)</option>`;
            }

            opt += `</optgroup>`;

            donorSelect.html(opt).trigger('change', ['from_render']);

        })
        .fail(function () {

            donorSelect.html(`
                <optgroup label="Donor Based">
                    <option value="">Gagal load donor</option>
                </optgroup>
            `).trigger('change', ['from_render']);

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