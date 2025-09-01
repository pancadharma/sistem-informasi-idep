<script>
$(document).ready(function() {
    // Define clean field names mapping
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
        'deskripsilatarbelakang': 'Latar Belakang',
        'deskripsitujuan': 'Tujuan Kegiatan',
        'deskripsikeluaran': 'Deskripsi Keluaran',
        'penerimamanfaatdewasaperempuan': 'Penerima Manfaat Dewasa Perempuan',
        'penerimamanfaatdewasalakilaki': 'Penerima Manfaat Dewasa Laki-laki',
        'penerimamanfaatlansiaperempuan': 'Penerima Manfaat Lansia Perempuan',
        'penerimamanfaatlansialakilaki': 'Penerima Manfaat Lansia Laki-laki',
        'penerimamanfaatremajaperempuan': 'Penerima Manfaat Remaja Perempuan',
        'penerimamanfaatremajalakilaki': 'Penerima Manfaat Remaja Laki-laki',
        'penerimamanfaatanakperempuan': 'Penerima Manfaat Anak Perempuan',
        'penerimamanfaatanaklakilaki': 'Penerima Manfaat Anak Laki-laki'
    };

    $('#update_kegiatan').on('click', function(e) {
        e.preventDefault();

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

            // Validate specific required fields
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
                { selector: '#deskripsilatarbelakang', type: 'summernote' },
                { selector: '#deskripsitujuan', type: 'summernote' },
                { selector: '#deskripsikeluaran', type: 'summernote' }
            ];

            // Validate each required field
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

            // Validate beneficiary data (at least one field should have a value > 0)
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

            // Validate locations
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

            // Validate penulis data
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
                    title: '{{ __("global.validation_error") }}',
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

    });

    // Add CSS for validation styling
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
            `)
            .appendTo('head');
    }
});



</script>
