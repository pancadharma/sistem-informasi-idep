<script>
    $(document).ready(function() {
        // Permission and initial-status logic
        const CAN_EDIT_STATUS_KEGIATAN = {{ (auth()->user()->id == 1 || (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('Administrator')) || auth()->user()->can('kegiatan_status_edit')) ? 'true' : 'false' }};
        const INITIALLY_COMPLETED_KEGIATAN = '{{ $kegiatan->status ?? '' }}' === 'completed';

        // Lock status field for unauthorized users and preserve value via hidden input
        (function lockStatusIfUnauthorized(){
            const $form = $('#updateKegiatan');
            const $status = $('#status');
            if ($form.length && $status.length && !CAN_EDIT_STATUS_KEGIATAN) {
                const currentVal = $status.val();
                $status.prop('disabled', true).addClass('is-readonly');
                const s2 = $status.data('select2');
                if (s2 && s2.$container) {
                    s2.$container.addClass('select2-container--disabled');
                }
                if ($form.find('input[type="hidden"][name="status"]').length === 0) {
                    $('<input>', { type: 'hidden', name: 'status', value: currentVal }).appendTo($form);
                } else {
                    $form.find('input[type="hidden"][name="status"]').val(currentVal);
                }
                $status.on('change', function(){
                    $(this).val(currentVal).trigger('change.select2');
                    if (typeof toastr !== 'undefined') {
                        toastr.warning('You do not have permission to change status.');
                    } else if (typeof Swal !== 'undefined') {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'You do not have permission to change status.', showConfirmButton: false, timer: 2500 });
                    }
                });
            }
        })();

        // If already completed and user is not allowed, disable update
        if (INITIALLY_COMPLETED_KEGIATAN && !CAN_EDIT_STATUS_KEGIATAN) {
            $('#update_kegiatan').prop('disabled', true);
            if (typeof toastr !== 'undefined') {
                toastr.error('Kegiatan is already completed. Not allowed to update unless Administrator only.');
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Kegiatan is already completed. Not allowed to update unless Administrator only.', showConfirmButton: false, timer: 3000 });
            }
        }
        function validasiProgramIDActivityID() {
            let program_id = $('#program_id').val();
            let kode_program = $('#kode_program').val();
            let activity_id = $('#programoutcomeoutputactivity_id').val();

            // Check if either program_id and activity_id is empty or invalid
            if (!program_id && !activity_id) {
                return false;
            }
            if (isNaN(program_id) || isNaN(activity_id)) {
                return false;
            }
            if (program_id !== '' && activity_id !== '') {
                return false;
            }

            return true;
        }

        let errorMessage = '';

        function validateForm() {
            let isValid = true;
            const programValidation = validasiProgramIDActivityID();

            errorMessage = '';
            if (!programValidation.isValid) {
                isValid = false;
                errorMessage += programValidation.message + '<br>';
            }

            const jenisKegiatanValidation = validasiSingleSelect2('#jeniskegiatan_id',
                '{{ __('cruds.kegiatan.validate.jenis_kegiatan') }}');

            if (!jenisKegiatanValidation.isValid) {
                isValid = false;
                errorMessage += jenisKegiatanValidation.message + '<br>';
            }

            const sektorValidation = validasiMultipleSelect2('#sektor_id',
                '{{ __('cruds.kegiatan.validate.sektor') }}');
            if (!sektorValidation.isValid) {
                isValid = false;
                errorMessage += sektorValidation.message + '<br>';
            }

            const mitraValidation = validasiMultipleSelect2('#mitra_id',
                '{{ __('cruds.kegiatan.validate.mitra') }}');
            // const mitraValidation = validasiMultipleSelect2('#mitra_id', '{!! __('cruds.kegiatan.validate.mitra', ['icon' => '<i class="bi bi-exclamation-lg text-danger"></i>']) !!}');
            if (!mitraValidation.isValid) {
                isValid = false;
                errorMessage += mitraValidation.message + '<br>';
            }

            const longLatValidation = validasiLongLat();
            if (!longLatValidation.isValid) {
                isValid = false;
                errorMessage += longLatValidation.errorMessage + '<br>';
            }

            const penulisValidation = validasiPenulis();
            if (!penulisValidation.isValid) {
                isValid = false;
                errorMessage += penulisValidation.message + '<br>';
            }

            if (!isValid) {
                Swal.fire({
                    title: '',
                    html: errorMessage,
                    icon: 'warning'
                });
                return false;
            }

            return true;
        }

        function validasiLongLat() {
            let kecamatanInputs = $('.kecamatan-select').map(function() {
                return $(this).val();
            }).get();
            let kelurahanInputs = $('.kelurahan-select').map(function() {
                return $(this).val();
            }).get();
            let latInputs = document.querySelectorAll('input[name="lat[]"]');
            let longInputs = document.querySelectorAll('input[name="long[]"]');
            let lokasiInputs = document.querySelectorAll('input[name="lokasi[]"]');

            let isValid = true;
            let longLatErrorMessage = '';
            let kecamatanKelurahanError = false;
            let longLatError = false;
            let lokasiError = false;

            for (let i = 0; i < latInputs.length; i++) {
                let latValue = latInputs[i].value.trim();
                let longValue = longInputs[i].value.trim();
                let lokasiValue = lokasiInputs[i].value.trim();

                let kecamatanValue = kecamatanInputs[i];
                let kelurahanValue = kelurahanInputs[i];

                console.log("Data Select2 of array Kec & Kel :", kecamatanValue, kelurahanValue);

                let $kecamatanSelect2Container = $($('.kecamatan-select')[i]).next('.select2-container');
                let $kelurahanSelect2Container = $($('.kelurahan-select')[i]).next('.select2-container');

                if (!kecamatanValue || !kelurahanValue) {
                    isValid = false;
                    kecamatanKelurahanError = true;

                    $kecamatanSelect2Container.find('.select2-selection').addClass('is-invalid-select2');
                    $kelurahanSelect2Container.find('.select2-selection').addClass('is-invalid-select2');

                } else {
                    $kecamatanSelect2Container.find('.select2-selection').removeClass('is-invalid-select2');
                    $kelurahanSelect2Container.find('.select2-selection').removeClass('is-invalid-select2');
                    $kecamatanSelect2Container.find('.select2-selection').addClass('is-valid-select2');
                    $kelurahanSelect2Container.find('.select2-selection').addClass('is-valid-select2');
                }

                if (!lokasiValue) {
                    isValid = false;
                    lokasiError = true;
                    lokasiInputs[i].classList.add('is-invalid');
                } else {
                    lokasiInputs[i].classList.remove('is-invalid');
                }

                // Check if latitude is empty or not a valid number between -90 and 90
                if (!latValue || isNaN(parseFloat(latValue)) || parseFloat(latValue) < -90 || parseFloat(
                        latValue) > 90) {
                    isValid = false;
                    longLatError = true;
                    latInputs[i].classList.add('is-invalid');
                } else {
                    latInputs[i].classList.remove('is-invalid');
                }

                if (!longValue || isNaN(parseFloat(longValue)) || parseFloat(longValue) < -180 || parseFloat(
                        longValue) > 180) {
                    isValid = false;
                    longLatError = true;
                    longInputs[i].classList.add('is-invalid');
                } else {
                    longInputs[i].classList.remove('is-invalid');
                }
            }
            if (kecamatanKelurahanError) {
                longLatErrorMessage += '{{ __('cruds.kegiatan.validate.kec_kel') }} <br>';
            }
            if (longLatError) {
                longLatErrorMessage += '{{ __('cruds.kegiatan.validate.longlat') }} <br>';
            }
            if (lokasiError) {
                longLatErrorMessage += '{{ __('cruds.kegiatan.validate.tempat_kegiatan') }} <br>';
            }

            return {
                isValid: isValid,
                errorMessage: longLatErrorMessage
            };
        }

        function validasiPenulis() {
            let isValid = true;
            let message = '';

            $('.penulis-row').each(function(index) {
                let penulisValue = $(this).find('.penulis-select').val();
                let jabatanValue = $(this).find('.jabatan-select').val();

                if (!penulisValue) {
                    isValid = false;
                    message =
                        "{{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.penulis.nama') }}";
                    applySelect2ErrorStyling($(this).find('.penulis-select'));
                } else {
                    removeSelect2ErrorStyling($(this).find('.penulis-select'));
                }

                if (!jabatanValue) {
                    isValid = false;
                    message =
                        "{{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.penulis.jabatan') }}";
                    applySelect2ErrorStyling($(this).find('.jabatan-select'));
                } else {
                    removeSelect2ErrorStyling($(this).find('.jabatan-select'));
                }
            });

            return {
                isValid: isValid,
                message: message
            };
        }

        function validasiMultipleSelect2(selector, message) {
            let values = $(selector).val();
            if (!values || values.length === 0) {
                applySelect2ErrorStyling(selector);
                return {
                    isValid: false,
                    message: message
                };
            }
            removeSelect2ErrorStyling(selector);
            return {
                isValid: true,
                message: ''
            };
        }

        function validasiSingleSelect2(selector, message) {
            let value = $(selector).val();
            if (!value) {
                applySelect2ErrorStyling(selector);
                return {
                    isValid: false,
                    message: message
                };
            }
            removeSelect2ErrorStyling(selector);
            return {
                isValid: true,
                message: ''
            };
        }

        function applySelect2ErrorStyling(selector) {
            $(selector).next('.select2-container').find('.select2-selection').addClass('is-invalid-select2');
        }

        function removeSelect2ErrorStyling(selector) {
            $(selector).next('.select2-container').find('.select2-selection').removeClass('is-invalid-select2');
            $(selector).next('.select2-container').find('.select2-selection').addClass('is-valid-select2');
        }

        function validasiProgramIDActivityID() {
            let isValid = true;
            let message = '';

            let program_id = $('#program_id').val();
            let activity_id = $('#programoutcomeoutputactivity_id').val();

            let programError = false;
            let activityError = false;

            if (!program_id) {
                isValid = false;
                programError = true;
                $('#kode_program, #nama_program').addClass('is-invalid');
            } else {
                $('#kode_program, #nama_program').removeClass('is-invalid');
                $('#kode_program, #nama_program').addClass('is-valid');
            }

            if (!activity_id) {
                isValid = false;
                activityError = true;
                $('#kode_kegiatan, #nama_kegiatan').addClass('is-invalid');
            } else {
                $('#kode_kegiatan, #nama_kegiatan').removeClass('is-invalid');
                $('#kode_kegiatan, #nama_kegiatan').addClass('is-valid');
            }

            if (programError && activityError) {
                message = '{{ __('cruds.kegiatan.validate.program_activity') }}';
            } else if (programError) {
                message = '{{ __('cruds.kegiatan.validate.program') }}';
            } else if (activityError) {
                message = '{{ __('cruds.kegiatan.validate.activity') }}';
            }

            return {
                isValid: isValid,
                message: message
            };
        }


        function validateStatusComplete(){
            const fieldNameMapping = {
                'jeniskegiatan': 'Jenis Kegiatan',
                'sektor_id': 'Sektor Kegiatan',
                'fasepelaporan': 'Fase Pelaporan',
                'tanggalmulai': 'Tanggal Mulai',
                'tanggalselesai': 'Tanggal Selesai',
                'mitra_id': 'Nama Mitra',
                'status': 'Status',
                'provinsi_id': 'Provinsi',
                'kabupaten_id': 'Kabupaten',
                // 'deskripsilatarbelakang': 'Latar Belakang',
                // 'deskripsitujuan': 'Tujuan Kegiatan',
                // 'deskripsikeluaran': 'Deskripsi Keluaran',
                'penerimamanfaatdewasaperempuan': 'Penerima Manfaat Dewasa Perempuan',
                'penerimamanfaatdewasalakilaki': 'Penerima Manfaat Dewasa Laki-laki',
                'penerimamanfaatlansiaperempuan': 'Penerima Manfaat Lansia Perempuan',
                'penerimamanfaatlansialakilaki': 'Penerima Manfaat Lansia Laki-laki',
                'penerimamanfaatremajaperempuan': 'Penerima Manfaat Remaja Perempuan',
                'penerimamanfaatremajalakilaki': 'Penerima Manfaat Remaja Laki-laki',
                'penerimamanfaatanakperempuan': 'Penerima Manfaat Anak Perempuan',
                'penerimamanfaatanaklakilaki': 'Penerima Manfaat Anak Laki-laki'
            };

            if ($('#status').val() === 'completed') {
                let isValid = true;
                let errorMessages = [];

                // Helper function to get clean field name
                function getCleanFieldName(element) {
                    const fieldId = element.attr('id') || element.attr('name');
                    return fieldNameMapping[fieldId] || fieldId || 'Field';
                }

                // Helper function to check if content is empty (for rich text editors)
                function isEmptyContent(html) {
                    if (!html) return true;
                    const textContent = html.replace(/<[^>]*>/g, '').trim();
                    return textContent === '' || html === '<p><br></p>' || html === '<p></p>' || html === '<br>';
                }

                // Validate specific required fields (existing logic)
                const requiredFields = [
                    { selector: '#jeniskegiatan', type: 'select2' },
                    { selector: '#sektor_id', type: 'select2' },
                    { selector: '#fasepelaporan', type: 'select2' },
                    { selector: '#tanggalmulai', type: 'input' },
                    { selector: '#tanggalselesai', type: 'input' },
                    { selector: '#mitra_id', type: 'select2' },
                    { selector: '#status', type: 'select2' },
                    { selector: '#provinsi_id', type: 'select2' },
                    { selector: '#kabupaten_id', type: 'select2' },
                    // { selector: '#deskripsilatarbelakang', type: 'summernote' },
                    // { selector: '#deskripsitujuan', type: 'summernote' },
                    // { selector: '#deskripsikeluaran', type: 'summernote' }
                ];

                // Validate each required field (existing logic)
                requiredFields.forEach(field => {
                    const $element = $(field.selector);
                    if ($element.length === 0) return; // Skip if element doesn't exist

                    const fieldName = getCleanFieldName($element);
                    let value;
                    let isEmpty = false;

                    switch (field.type) {
                        case 'summernote':
                            if ($element.data('summernote')) {
                                try {
                                    value = $element.summernote('code');
                                } catch (e) {
                                    value = $element.val();
                                }
                            } else {
                                value = $element.val();
                            }
                            isEmpty = isEmptyContent(value);

                            if (isEmpty) {
                                let noteEditor = $element.siblings('.note-editor').first();
                                if (noteEditor.length === 0) {
                                    noteEditor = $element.next('.note-editor');
                                }
                                noteEditor.addClass('is-invalid');
                            } else {
                                let noteEditor = $element.siblings('.note-editor').first();
                                if (noteEditor.length === 0) {
                                    noteEditor = $element.next('.note-editor');
                                }
                                noteEditor.removeClass('is-invalid');
                            }
                            break;

                        case 'select2':
                            value = $element.val();
                            isEmpty = !value || (Array.isArray(value) && value.length === 0) || value === '';

                            if (isEmpty) {
                                $element.next('.select2-container').find('.select2-selection').addClass('is-invalid-select2');
                            } else {
                                $element.next('.select2-container').find('.select2-selection').removeClass('is-invalid-select2');
                            }
                            break;

                        case 'input':
                            value = $element.val();
                            isEmpty = !value || value.trim() === '';

                            if (isEmpty) {
                                $element.addClass('is-invalid');
                            } else {
                                $element.removeClass('is-invalid');
                            }
                            break;
                    }

                    if (isEmpty) {
                        isValid = false;
                        errorMessages.push(`${fieldName} tidak boleh kosong`);
                    }
                });

                // NEW: Validate dynamic Summernote fields in #dynamic-form-container
                const $dynamicContainer = $('#dynamic-form-container');
                const $descriptionContainer = $('#description-tab');
                if ($dynamicContainer.length > 0) {
                    $dynamicContainer.find('.summernote').each(function(index) {
                        const $dynamicElement = $(this);
                        let dynamicValue;
                        let dynamicIsEmpty = false;

                        // Get field name from id, name, or placeholder
                        let dynamicFieldName = 'Dynamic Field';
                        if ($dynamicElement.attr('id')) {
                            // Convert camelCase or snake_case to readable format
                            dynamicFieldName =  $dynamicElement.attr('placeholder') || $dynamicElement.attr('id')
                                .replace(/([A-Z])/g, ' $1') // camelCase to space
                                .replace(/_/g, ' ') // snake_case to space
                                .replace(/\b\w/g, l => l.toUpperCase()) // capitalize first letters
                                .trim();
                                console.log("Test 1");
                            } else if ($dynamicElement.attr('placeholder')) {
                                dynamicFieldName = $dynamicElement.attr('placeholder');
                                // console.log("Test 2");
                            } else if ($dynamicElement.attr('name')) {
                                dynamicFieldName = $dynamicElement.attr('placeholder') || $dynamicElement.attr('name')
                                .replace(/([A-Z])/g, ' $1')
                                .replace(/_/g, ' ')
                                .replace(/\b\w/g, l => l.toUpperCase())
                                .trim();
                                // console.log("Test 3");
                            }

                        // Check if it's initialized as Summernote
                        if ($dynamicElement.data('summernote')) {
                            try {
                                dynamicValue = $dynamicElement.summernote('code');
                            } catch (e) {
                                console.warn('Error getting Summernote content:', e);
                                dynamicValue = $dynamicElement.val();
                            }
                        } else {
                            dynamicValue = $dynamicElement.val();
                        }

                        dynamicIsEmpty = isEmptyContent(dynamicValue);

                        // console.table(dynamicValue);
                        // Apply visual validation
                        if (dynamicIsEmpty) {
                            let noteEditor = $dynamicElement.siblings('.note-editor').first();
                            if (noteEditor.length === 0) {
                                noteEditor = $dynamicElement.next('.note-editor');
                            }
                            if (noteEditor.length === 0) {
                                // Fallback: find note-editor in parent
                                noteEditor = $dynamicElement.closest('.form-group, .col, div').find('.note-editor');
                            }
                            noteEditor.addClass('is-invalid');

                            isValid = false;
                            errorMessages.push(`${dynamicFieldName} tidak boleh kosong`);
                        } else {
                            let noteEditor = $dynamicElement.siblings('.note-editor').first();
                            if (noteEditor.length === 0) {
                                noteEditor = $dynamicElement.next('.note-editor');
                            }
                            if (noteEditor.length === 0) {
                                noteEditor = $dynamicElement.closest('.form-group, .col, div').find('.note-editor');
                            }
                            noteEditor.removeClass('is-invalid');
                        }
                    });
                }
                if ($descriptionContainer.length > 0) {
                    $descriptionContainer.find('.summernote').each(function(index) {
                        const $dynamicElement = $(this);
                        let dynamicValue;
                        let dynamicIsEmpty = false;

                        // Get field name from id, name, or placeholder
                        let dynamicFieldName = 'Dynamic Field';
                        if ($dynamicElement.attr('id')) {
                            // Convert camelCase or snake_case to readable format
                            dynamicFieldName =  $dynamicElement.attr('placeholder') || $dynamicElement.attr('id')
                                .replace(/([A-Z])/g, ' $1') // camelCase to space
                                .replace(/_/g, ' ') // snake_case to space
                                .replace(/\b\w/g, l => l.toUpperCase()) // capitalize first letters
                                .trim();
                                console.log("Test 1");
                            } else if ($dynamicElement.attr('placeholder')) {
                                dynamicFieldName = $dynamicElement.attr('placeholder');
                                // console.log("Test 2");
                            } else if ($dynamicElement.attr('name')) {
                                dynamicFieldName = $dynamicElement.attr('placeholder') || $dynamicElement.attr('name')
                                .replace(/([A-Z])/g, ' $1')
                                .replace(/_/g, ' ')
                                .replace(/\b\w/g, l => l.toUpperCase())
                                .trim();
                                // console.log("Test 3");
                            }

                        // Check if it's initialized as Summernote
                        if ($dynamicElement.data('summernote')) {
                            try {
                                dynamicValue = $dynamicElement.summernote('code');
                            } catch (e) {
                                console.warn('Error getting Summernote content:', e);
                                dynamicValue = $dynamicElement.val();
                            }
                        } else {
                            dynamicValue = $dynamicElement.val();
                        }

                        dynamicIsEmpty = isEmptyContent(dynamicValue);

                        // console.table(dynamicValue);
                        // Apply visual validation
                        if (dynamicIsEmpty) {
                            let noteEditor = $dynamicElement.siblings('.note-editor').first();
                            if (noteEditor.length === 0) {
                                noteEditor = $dynamicElement.next('.note-editor');
                            }
                            if (noteEditor.length === 0) {
                                // Fallback: find note-editor in parent
                                noteEditor = $dynamicElement.closest('.form-group, .col, div').find('.note-editor');
                            }
                            noteEditor.addClass('is-invalid');

                            isValid = false;
                            errorMessages.push(`${dynamicFieldName} tidak boleh kosong`);
                        } else {
                            let noteEditor = $dynamicElement.siblings('.note-editor').first();
                            if (noteEditor.length === 0) {
                                noteEditor = $dynamicElement.next('.note-editor');
                            }
                            if (noteEditor.length === 0) {
                                noteEditor = $dynamicElement.closest('.form-group, .col, div').find('.note-editor');
                            }
                            noteEditor.removeClass('is-invalid');
                        }
                    });
                }

                // Validate beneficiary data (existing logic)
                const beneficiaryFields = [
                    '#penerimamanfaatdewasaperempuan',
                    '#penerimamanfaatdewasalakilaki',
                    '#penerimamanfaatlansiaperempuan',
                    '#penerimamanfaatlansialakilaki',
                    '#penerimamanfaatremajaperempuan',
                    '#penerimamanfaatremajalakilaki',
                    '#penerimamanfaatanakperempuan',
                    '#penerimamanfaatanaklakilaki'
                ];

                let hasBeneficiaryData = false;
                beneficiaryFields.forEach(selector => {
                    const value = parseInt($(selector).val()) || 0;
                    if (value > 0) {
                        hasBeneficiaryData = true;
                    }
                });

                if (!hasBeneficiaryData) {
                    isValid = false;
                    errorMessages.push('Data penerima manfaat harus diisi minimal satu kategori');
                }

                // Validate locations (existing logic)
                let hasValidLocation = false;
                $('.lokasi-kegiatan').each(function() {
                    const kecamatan = $(this).find('select[name="kecamatan_id[]"]').val();
                    const kelurahan = $(this).find('select[name="kelurahan_id[]"]').val();
                    const lokasi = $(this).find('input[name="lokasi[]"]').val();

                    if (kecamatan && kelurahan && lokasi && lokasi.trim() !== '') {
                        hasValidLocation = true;
                        return false; // break the loop
                    }
                });

                if (!hasValidLocation) {
                    isValid = false;
                    errorMessages.push('Setidaknya satu lokasi kegiatan harus diisi dengan lengkap');
                }

                // Validate penulis data (existing logic)
                let hasValidPenulis = false;
                $('.penulis-row').each(function() {
                    const penulis = $(this).find('select[name="penulis[]"]').val();
                    const jabatan = $(this).find('select[name="jabatan[]"]').val();

                    if (penulis && jabatan) {
                        hasValidPenulis = true;
                        return false; // break the loop
                    }
                });

                if (!hasValidPenulis) {
                    isValid = false;
                    errorMessages.push('Data penulis laporan harus diisi minimal satu orang');
                }

                // Show validation results
                if (!isValid) {
                    Swal.fire({
                        title: '{{ __("global.error") }}',
                        html: `<div style="text-align: left;"><ul style="padding-left: 20px;">${errorMessages.map(msg => `<li>${msg}</li>`).join('')}</ul></div>`,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        width: '600px'
                    });

                    // Focus on first invalid element
                    const firstInvalid = $('#updateKegiatan').find('.is-invalid, .is-invalid-select2').first();
                    if (firstInvalid.length) {
                        firstInvalid.focus();
                        // Scroll to the invalid field
                        $('html, body').animate({
                            scrollTop: firstInvalid.offset().top - 100
                        }, 500);
                    }
                } else {
                    // Show loading state
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang menyimpan data',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $('#updateKegiatan').submit();
                }
            }

            // Ensure validation styles are added
            if (!$('#validation-styles').length) {
                $('<style id="validation-styles">')
                    .text(`
                        .is-invalid-select2 {
                            border-color: #dc3545 !important;
                            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
                        }
                        .note-editor.is-invalid {
                            border-color: #dc3545 !important;
                            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
                        }
                        .note-editor.is-invalid .note-toolbar {
                            border-bottom: 1px solid #dc3545 !important;
                        }
                        .note-editor.is-invalid .note-editable {
                            border-color: #dc3545 !important;
                        }
                    `)
                    .appendTo('head');
            }
        }

        $('#update_kegiatan').on('click', function(e) {
            e.preventDefault();

            if (INITIALLY_COMPLETED_KEGIATAN && !CAN_EDIT_STATUS_KEGIATAN) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Kegiatan is already completed. Not allowed to update unless Administrator only.');
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Action not allowed', text: 'Kegiatan is already completed. Not allowed to update unless Administrator only.'});
                }
                return false;
            }

            if ($('#status').val() === 'completed') {
                validateStatusComplete();
                return;
            }
            // Validate form (define or adjust as needed)

            if (!validateForm()) {
                Swal.fire({
                    title: '{{ __('global.error') }}',
                    text: 'Please fill out all required fields correctly.',
                    icon: 'error'
                });
                return false;
            }

            // Get form data
            let formData = new FormData($('#updateKegiatan')[0]);

            // Append CSRF token (required for Laravel POST/PUT requests)
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT'); // Laravel requires _method for PUT in forms

            let url_update = "{{ route('kegiatan.update', $kegiatan->id) }}";

            // Optional: Log form data for debugging
            let serializedData = $("#updateKegiatan").serializeArray();
            let displayData = serializedData.map(item => `${item.name}: ${item.value}`).join('\n');
            console.log(`Form Data:\n${displayData}`);

            Swal.fire({
                title: '{{ __('global.areYouSure') }}',
                text: '{{ __('cruds.kegiatan.validate.update_kegiatan') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('global.yes') }}' + ', ' + '{{ __('global.save') }}' + '!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we save your data.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: url_update,
                        type: 'POST', // Use POST with _method=PUT for Laravel
                        data: formData,
                        method: 'POST',
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function(response) {
                            // Admin\KegiatanController@update redirects, so we may not get JSON
                            // Handle redirect manually or adjust backend to return JSON
                            Swal.fire({
                                title: '{{ __('global.response.success') }}',
                                text: '{{ __('global.response.save_success') }}',
                                icon: 'success'
                            }).then(() => {
                                window.location.href = "{{ route('kegiatan.index') }}";
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = xhr.responseJSON?.message || '{{ __('global.response.save_failed') }}';
                            Swal.fire({
                                title: '{{ __('global.error') }}',
                                text: errorMessage,
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        // Example validateForm function (adjust based on your form requirements)
        function validateForm() {
            let form = $('#updateKegiatan');
            let requiredFields = form.find('input[required], select[required], textarea[required]');
            let isValid = true;

            requiredFields.each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Additional validation for arrays (e.g., penulis, jabatan)
            if (form.find('select[name="penulis[]"]').length && !form.find('select[name="penulis[]"]').val()) {
                isValid = false;
                form.find('select[name="penulis[]"]').addClass('is-invalid');
            }

            return isValid;
        }



    });

        $(document).on('paste', '.lat-input, .lang-input', function(e) {
            e.preventDefault();
            const pasteData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
            const coords = pasteData.split(/[,;\s]+/).map(coord => coord.trim()).filter(coord => coord !== '');

            const isLatField = $(this).hasClass('lat-input');
            const isLngField = $(this).hasClass('lang-input');
            const row = $(this).closest('.lokasi-kegiatan');
            const latInput = row.find('.lat-input');
            const lngInput = row.find('.lang-input');

            // Case 1: Pair "lat, lng"
            if (coords.length === 2) {
                const lat = parseFloat(coords[0]);
                const lng = parseFloat(coords[1]);
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    latInput.val(lat.toFixed(6));
                    lngInput.val(lng.toFixed(6));
                    latInput.trigger('change');
                    lngInput.trigger('change');
                    Toast.fire({ icon: 'success', title: 'Coordinates filled successfully', timer: 1500, position: 'top-end' });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Invalid coordinates format',
                        text: 'Use: latitude, longitude (e.g., -8.497791, 115.275794)',
                        timer: 3000,
                        position: 'top-end'
                    });
                }
                return;
            }

            // Case 2: Single value pasted (fill only the active field if valid)
            if (coords.length === 1) {
                const value = parseFloat(coords[0]);
                if (isLatField && !isNaN(value) && value >= -90 && value <= 90) {
                    latInput.val(value.toFixed(6));
                    latInput.trigger('change');
                    Toast.fire({ icon: 'success', title: 'Latitude set', timer: 1200, position: 'top-end' });
                    return;
                }
                if (isLngField && !isNaN(value) && value >= -180 && value <= 180) {
                    lngInput.val(value.toFixed(6));
                    lngInput.trigger('change');
                    Toast.fire({ icon: 'success', title: 'Longitude set', timer: 1200, position: 'top-end' });
                    return;
                }
                Toast.fire({
                    icon: 'error',
                    title: 'Invalid coordinate',
                    text: isLatField ? 'Latitude must be between -90 and 90' : 'Longitude must be between -180 and 180',
                    timer: 2500,
                    position: 'top-end'
                });
                return;
            }

            // Case 3: Unsupported format
            Toast.fire({
                icon: 'error',
                title: 'Invalid coordinates format',
                text: 'Paste a single value or "lat, lng"',
                timer: 3000,
                position: 'top-end'
            });
        });
</script>
