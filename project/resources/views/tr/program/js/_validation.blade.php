<script>
    function validateProgramComplete() {
        const fieldNameMapping = {
            'kode_program': '{{ __("cruds.program.form.kode") }}',
            'nama_program': '{{ __("cruds.program.form.nama") }}',
            'tanggalmulai': '{{ __("cruds.program.form.tgl_mulai") }}',
            'tanggalselesai': '{{ __("cruds.program.form.tgl_selesai") }}',
            'totalnilai': '{{ __("cruds.program.form.total_nilai") }}',
            'deskripsi': '{{ __("cruds.program.deskripsi") }}',
            'analisis': '{{ __("cruds.program.analisis") }}',
            'kelompokmarjinal': '{{ __("cruds.program.marjinal.list") }}',
            'targetreinstra': '{{ __("cruds.program.list_reinstra") }}',
            'kaitansdg': '{{ __("cruds.program.list_sdg") }}',
            'lokasi': '{{ __("cruds.program.lokasi.label") }}',
            'partner': '{{ __("cruds.program.partner.label") }}',
            'donor': '{{ __("cruds.program.donor.label") }}'
        };

        let isValid = true;
        let errorMessages = [];

        // --- Helper Functions ---
        function highlightTab(selector, hasError) {
            const tabPane = $(selector).closest('.tab-pane');
            if (tabPane.length) {
                const tabId = tabPane.attr('id');
                const tabLink = $(`a[href="#${tabId}"]`);
                if (hasError) {
                    tabLink.addClass('text-danger font-weight-bold');
                } else {
                    tabLink.removeClass('text-danger font-weight-bold');
                }
            }
        }

        function checkEmpty(selector, type, fieldName) {
            const element = $(selector);
            if (!element.length) return;

            let isEmpty = false;
            switch (type) {
                case 'input':
                case 'textarea':
                case 'date':
                    isEmpty = !element.val() || element.val().trim() === '';
                    if (isEmpty) element.addClass('is-invalid');
                    else element.removeClass('is-invalid').addClass('is-valid');
                    break;
                case 'select2':
                    const val = element.val();
                    isEmpty = !val || (Array.isArray(val) && val.length === 0);
                    const container = element.next('.select2-container');
                    if (isEmpty) container.find('.select2-selection').addClass('is-invalid-select2');
                    else container.find('.select2-selection').removeClass('is-invalid-select2').addClass('is-valid-select2');
                    break;
            }

            if (isEmpty) {
                isValid = false;
                errorMessages.push(`${fieldName} tidak boleh kosong.`);
                highlightTab(selector, true);
            } else {
                highlightTab(selector, false);
            }
        }

        function checkDynamicRows(containerSelector, rowSelector, minRows, errorMessage) {
            const rowCount = $(`${containerSelector} ${rowSelector}`).length;
            if (rowCount < minRows) {
                isValid = false;
                errorMessages.push(errorMessage);
                highlightTab(containerSelector, true);
            } else {
                highlightTab(containerSelector, false);
            }
        }

        // --- Validation Execution ---
        $('a[data-toggle="pill"]').removeClass('text-danger font-weight-bold');

        for (const [id, name] of Object.entries(fieldNameMapping)) {
            const selector = `#${id}`;
            let type = $(selector).is('select') ? 'select2' : ($(selector).is('textarea') ? 'textarea' : 'input');
            checkEmpty(selector, type, name);
        }

        const beneficiaries = ['#pria', '#wanita', '#laki', '#perempuan', '#total'];
        let totalBeneficiaries = 0;
        beneficiaries.forEach(selector => { totalBeneficiaries += parseInt($(selector).val(), 10) || 0; });
        if (totalBeneficiaries === 0) {
            isValid = false;
            errorMessages.push('Minimal satu data ekspektasi penerima manfaat harus diisi.');
            beneficiaries.forEach(s => $(s).addClass('is-invalid'));
        } else {
            beneficiaries.forEach(s => $(s).removeClass('is-invalid').addClass('is-valid'));
        }

        checkDynamicRows('#staffContainerEdit', '.staff-row', 1, 'Minimal harus ada satu Staff.');
        checkDynamicRows('#outcomeContainer', '.row', 1, 'Minimal harus ada satu Outcome.');

        if ($('#outcomeContainer .row').length > 0) {
            let outcomeTextValid = true;
            $('#outcomeContainer textarea').each(function() {
                if ($(this).val().trim() === '') {
                    $(this).addClass('is-invalid');
                    outcomeTextValid = false;
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });
            if (!outcomeTextValid) {
                isValid = false;
                errorMessages.push('Semua field pada Outcome (Deskripsi, Indikator, Target) tidak boleh kosong.');
                highlightTab('#outcomeContainer', true);
            }
        }

        if (!isValid) {
            Swal.fire({
                title: '{{ __("global.error") }}',
                html: `<div style="text-align: left;"><ul style="padding-left: 20px;">${errorMessages.map(msg => `<li>${msg}</li>`).join('')}</ul></div>`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

        return isValid;
    }
</script>
