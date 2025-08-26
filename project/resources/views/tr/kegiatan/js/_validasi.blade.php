<script>
    $(document).ready(function() {
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
        // submision if all validated

        // $('#simpan_kegiatan').on('click', function(e) {
        //     e.preventDefault();
        //     if (!validateForm()) {
        //         return false;
        //     }
        //     // Get form data
        //     let formData = new FormData($('#createKegiatan')[0]);
        //     let serializedData = $("#createKegiatan").serializeArray();
        //     let url_update = "{{ route('kegiatan.update', $kegiatan->id) }}";

        //     // Convert serialized data to a readable format for display
        //     let displayData = serializedData.map(item => `${item.name}: ${item.value}`).join('\n');
        //     console.log(`pre ${displayData}`);

        //     Swal.fire({
        //         title: 'Konfirmasi',
        //         text: 'Apakah anda yakin ingin menyimpan data ini?',
        //         // html: `<pre>${displayData}</pre>`,
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: '{{ __('global.yes') }}' + ', ' +
        //             '{{ __('global.save') }}' + ' ! '
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // Show loading state
        //             Swal.fire({
        //                 title: 'Processing...',
        //                 html: 'Please wait while we save your data.',
        //                 allowOutsideClick: false,
        //                 didOpen: () => {
        //                     Swal.showLoading();
        //                 }
        //             });

        //             $.ajax({
        //                 // url: "{{ route('api.kegiatan.store') }}", // Ensure this is the correct route
        //                 url: url_update,
        //                 type: 'PUT',
        //                 data: formData,
        //                 processData: false,
        //                 contentType: false,
        //                 cache: false,
        //                 beforeSend: function() {
        //                     Swal.fire({
        //                         title: 'Processing...',
        //                         text: 'Please wait while we save your data.',
        //                         allowOutsideClick: false,
        //                         timer: 10000,
        //                         showLoading: true
        //                     });
        //                 },
        //                 success: function(data) {
        //                     Swal.fire({
        //                         title: '{{ __('global.response.success') }}',
        //                         text: '{{ __('global.response.save_success') }}',
        //                         icon: 'success'
        //                     }).then((result) => {
        //                         if (result.isConfirmed) {
        //                             // Optionally redirect or reset form here
        //                             // redirect to route kegiatan index
        //                             window.location.href =
        //                                 "{{ route('kegiatan.index') }}";
        //                             // location.reload(); // Example to reload page
        //                             // $('#createKegiatan')[0].reset(); // Reset form
        //                         }
        //                     });
        //                 },
        //                 error: function(data) {
        //                     Swal.fire({
        //                         title: '{{ __('global.error') }}',
        //                         text: '{{ __('global.response.save_failed') }}',
        //                         icon: 'error'
        //                     });
        //                 }
        //             });
        //         }
        //     });
        // });


        // $('#update_kegiatan').on('click', function(e) {
        //     e.preventDefault();
        //     if (!validateForm()) {
        //         return false;
        //     }
        //     // Get form data
        //     let formData = new FormData($('#updateKegiatan')[0]);
        //     let serializedData = $("#updateKegiatan").serializeArray();
        //     let url_update = "{{ route('kegiatan.update', $kegiatan->id) }}";

        //     // Convert serialized data to a readable format for display
        //     let displayData = serializedData.map(item => `${item.name}: ${item.value}`).join('\n');
        //     console.log(`pre ${displayData}`);

        //     Swal.fire({
        //         title: '{{ __('global.areYouSure') }}',
        //         text: '{{ __('cruds.kegiatan.validate.update_kegiatan') }}',
        //         // html: `<pre>${displayData}</pre>`,
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: '{{ __('global.yes') }}' + ', ' +
        //             '{{ __('global.save') }}' + ' ! '
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // Show loading state
        //             Swal.fire({
        //                 title: 'Processing...',
        //                 html: 'Please wait while we save your data.',
        //                 allowOutsideClick: false,
        //                 didOpen: () => {
        //                     Swal.showLoading();
        //                 }
        //             });

        //             $.ajax({
        //                 // url: "{{ route('api.kegiatan.store') }}", // Ensure this is the correct route
        //                 url: url_update,
        //                 type: 'PUT',
        //                 data: formData,
        //                 processData: false,
        //                 contentType: false,
        //                 cache: false,
        //                 beforeSend: function() {
        //                     Swal.fire({
        //                         title: 'Processing...',
        //                         text: 'Please wait while we save your data.',
        //                         allowOutsideClick: false,
        //                         timer: 10000,
        //                         // showLoading: true
        //                     });
        //                 },
        //                 success: function(data) {
        //                     Swal.fire({
        //                         title: '{{ __('global.response.success') }}',
        //                         text: '{{ __('global.response.save_success') }}',
        //                         icon: 'success'
        //                     }).then((result) => {
        //                         if (result.isConfirmed) {
        //                             // Optionally redirect or reset form here
        //                             // redirect to route kegiatan index
        //                             window.location.href =
        //                                 "{{ route('kegiatan.index') }}";
        //                             // location.reload(); // Example to reload page
        //                             // $('#updateKegiatan')[0].reset(); // Reset form
        //                         }
        //                     });
        //                 },
        //                 error: function(data) {
        //                     Swal.fire({
        //                         title: '{{ __('global.error') }}',
        //                         text: '{{ __('global.response.save_failed') }}',
        //                         icon: 'error'
        //                     });
        //                 }
        //             });
        //         }
        //     });
        // });

        $('#update_kegiatan').on('click', function(e) {
            e.preventDefault();

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

            if (coords.length === 2) {
                const lat = parseFloat(coords[0]);
                const lng = parseFloat(coords[1]);

                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    const row = $(this).closest('.lokasi-kegiatan');
                    const latInput = row.find('.lat-input');
                    const lngInput = row.find('.lang-input');

                    // Determine which input was pasted into and fill accordingly
                    if ($(this).hasClass('lat-input')) {
                        latInput.val(lat.toFixed(6));
                        lngInput.val(lng.toFixed(6));
                    } else {
                        latInput.val(lat.toFixed(6));
                        lngInput.val(lng.toFixed(6));
                    }

                    // Trigger change events to update map markers
                    latInput.trigger('change');
                    lngInput.trigger('change');

                    // Show success feedback
                    Toast.fire({
                        icon: 'success',
                        title: 'Coordinates filled successfully',
                        timer: 1500,
                        position: 'top-end'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Invalid coordinates format',
                        text: 'Please use format: latitude, longitude (e.g., -8.49779174444027, 115.27579431731596)',
                        timer: 3000,
                        position: 'top-end'
                    });
                }
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Invalid coordinates format',
                    text: 'Please paste exactly 2 coordinates separated by comma or space',
                    timer: 3000,
                    position: 'top-end'
                });
            }
        });
</script>
