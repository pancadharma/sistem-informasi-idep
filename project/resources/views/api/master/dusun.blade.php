<div class="modal fade" id="ModalDusunBaru" tabindex="-1" role="dialog" aria-labelledby="ModalDusunBaruLabel"
    aria-hidden="true" >
    <div class="modal-dialog  modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal">
                <h5 class="modal-title" id="ModalDusunBaruLabel">
                    {{ __('global.create') . ' ' . __('cruds.dusun.title') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="add_dusun" class="form-dusun">
                    <form id="submit_dusun" action="{{ route('api.beneficiary.dusun.simpan') }}" method="POST"
                        autocomplete="off" novalidate>
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="provinsi_id">{{ __('cruds.dusun.form.prov') }}</label>
                            <select class="form-control select2" name="provinsi_id" id="provinsi_id" required
                                style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            <label for="kabupaten_id">{{ __('cruds.dusun.form.kab') }}</label>
                            <select id="kabupaten_id" name="kabupaten_id" class="form-control select2" required
                                style="width: 100%">
                                <option value="" selected>
                                    {{ __('global.pleaseSelect') . ' ' . __('cruds.kabupaten.title') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kecamatan_id">{{ __('cruds.dusun.form.kec') }}</label>
                            <select id="kecamatan_id" name="kecamatan_id" class="form-control select2" required
                                style="width: 100%">
                                <option value="" selected>
                                    {{ __('global.pleaseSelect') . ' ' . __('cruds.kecamatan.title') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="desa_id_dusun">{{ __('cruds.dusun.form.des') }}</label>
                            <input type="hidden" name="kode_desa" id="kode_desa">
                            <select id="desa_id_dusun" name="desa_id_dusun" class="form-control select2" required
                                style="width: 100%">
                                <option value="" selected>
                                    {{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kode">{{ __('cruds.dusun.form.kode') }}</label>
                            <input type="text" id="kode" name="kode" required class="form-control"
                                maxlength="16" minlength="16">
                        </div>

                        <div class="form-group">
                            <label for="nama">{{ __('cruds.dusun.form.nama') }}</label>
                            <input type="text" id="nama" name="nama" class="form-control" required
                                maxlength="200" pattern="^[A-Za-z][A-Za-z0-9 .]*$" title="Must start with a letter.">
                        </div>

                        <div class="form-group">
                            <label for="kode_pos">{{ __('cruds.dusun.form.kode_pos') }}</label>
                            <input type="text" id="kode_pos" name="kode_pos" class="form-control" maxlength="5"
                                pattern="\d*" title="Only numbers allowed">
                            <span class="invalid-feedback" id="kodepos_kurang"></span>
                        </div>

                        <div class="form-group">
                            <strong>{{ __('cruds.status.title') }}</strong>
                            <div class="icheck-primary">
                                <input type="checkbox" name="aktif" id="aktif"
                                    {{ old('aktif', 1) == 1 ? 'checked' : '' }} value="1">
                                <label for="aktif">{{ __('cruds.status.aktif') }}</label>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-success float-right btn-add-dusun" type="submit"><i
                                class="fas fa-save"></i> {{ trans('global.save') }}</button>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>


    @push('js')
        <script>
            $(document).ready(function() {

                // Provinsi Select2 with AJAX
                $('#provinsi_id').select2({
                    placeholder: "{{ trans('global.selectProv') }}",
                    allowClear: true,
                    dropdownParent: $('#ModalDusunBaru'), // Correct modal ID is crucial
                    ajax: {
                        url: "{{ route('api.prov') }}",
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });

                // Kabupaten Select2 (initialized after Provinsi selection)
                $('#kabupaten_id').select2({
                    placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title') }}",
                    dropdownParent: $('#ModalDusunBaru') // Important for modal functionality
                });

                // Kecamatan Select2 (initialized after Kabupaten selection)
                $('#kecamatan_id').select2({
                    placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title') }}",
                    dropdownParent: $('#ModalDusunBaru')
                });

                // Desa Select2 (initialized after Kecamatan selection)
                $('#desa_id_dusun').select2({
                    placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.desa.title') }}",
                    dropdownParent: $('#ModalDusunBaru')
                });


                $('#kode').prop("placeholder", "{{ trans('global.pleaseSelect') }} {{ trans('cruds.desa.title') }}");


                // Kode Dusun auto-formatting
                $('#kode').on('input', function() {
                    let input = $(this).val();
                    input = input.replace(/\D/g, '');

                    if (input.length > 2) {
                        input = input.substring(0, 2) + '.' + input.substring(2);
                    }
                    if (input.length > 4) {
                        input = input.substring(0, 5) + '.' + input.substring(5);
                    }
                    if (input.length > 6) {
                        input = input.substring(0, 8) + '.' + input.substring(8);
                    }
                    if (input.length > 10) {
                        input = input.substring(0, 13) + '.' + input.substring(13);
                    }
                    if (input.length > 15) {
                        input = input.substring(0, 16);
                    }
                    $(this).val(input);
                });

                // Remove is-invalid class on input change
                $('.form-control').on('input change', function() {
                    $(this).removeClass('is-invalid');
                });

                // Client-side validation
                $('#submit_dusun').validate({
                    rules: {
                        nama: {
                            required: true,
                            minlength: 3
                        },
                        kode: {
                            required: true,
                            minlength: 16,
                            maxlength: 16
                        },
                        provinsi_id: {
                            required: true
                        },
                        kabupaten_id: {
                            required: true
                        },
                        kecamatan_id: {
                            required: true
                        },
                        desa_id_dusun: {
                            required: true
                        },
                    },
                    messages: {
                        nama: {
                            required: "{{ trans('cruds.dusun.validation.req_nama') }}",
                            minlength: "{{ trans('cruds.dusun.validation.min_nama') }}"
                        },
                        kode: {
                            required: "{{ trans('cruds.dusun.validation.kode') }}",
                            minlength: "{{ trans('cruds.dusun.validation.min_kode') }}",
                            maxlength: "{{ trans('cruds.dusun.validation.max_kode') }}"
                        },
                        provinsi_id: "{{ trans('cruds.dusun.validation.prov') }}",
                        kabupaten_id: "{{ trans('cruds.dusun.validation.kab') }}",
                        kecamatan_id: "{{ trans('cruds.dusun.validation.kec') }}",
                        desa_id_dusun: "{{ trans('cruds.dusun.validation.des') }}",
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });

                function resetForm() {
                    $('#submit_dusun')[0].reset();
                    $('.form-group').find('.is-invalid').removeClass('is-invalid');
                    $('.form-group').find('.is-valid').removeClass('is-valid');
                    $('.form-group').find('.invalid-feedback').text('');
                    // Clear Select2 values:
                    $('#provinsi_id').val(null).trigger('change'); // Resets Select2 to placeholder
                    $('#kabupaten_id').val(null).trigger('change');
                    $('#kecamatan_id').val(null).trigger('change');
                    $('#desa_id_dusun').val(null).trigger('change');
                }

                function capitalizeWords(str) {
                    return str.replace(/\b\w/g, function(char) {
                        return char.toUpperCase();
                    });
                }

                $('#nama').on('input', function() {
                    let input = $(this).val();
                    if (/^\d{1,3}/.test(input)) {
                        input = input.replace(/^\d{1,3}/, '');
                    }
                    input = capitalizeWords(input);
                    $(this).val(input);
                });

                $('#kode_pos').on('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 5) {
                        this.value = this.value.slice(0, 5);
                    }
                    const errorElement = $('#kodepos_kurang');
                    if (this.value.length < 5) {
                        errorElement.text('Postal code must be exactly 5 digits.');
                        this.setCustomValidity('Postal code must be exactly 5 digits.');
                    } else if (this.value.length > 5) {
                        errorElement.text('Postal code cannot be more than 5 digits.');
                        this.setCustomValidity('Postal code cannot be more than 5 digits.');
                    } else {
                        errorElement.text('');
                        this.setCustomValidity('');
                    }
                });


                // AJAX form submission
                $('#submit_dusun').on('submit', function(e) {
                    e.preventDefault();
                    if (!$(this).valid()) {
                        return; // Stop the form submission if validation fails
                    }

                    let desa_id = $('#desa_id_dusun').val();
                    let kode_desa = $('#desa_id_dusun').find('option:selected').data('id') || $('#kode_desa')
                        .val() || '';
                    let formData = $(this).serialize() + '&kode_desa=' + kode_desa + '&desa_id=' + desa_id;

                    $.ajax({
                        url: $(this).attr('action'),
                        type: "POST",
                        dataType: "json",
                        data: formData,
                        beforeSend: function() {
                            Toast.fire({
                                icon: "info",
                                title: "Processing...",
                                timer: 1000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                        },
                        success: function(response) {
                            if (response.success) {
                                Toast.fire({
                                    icon: "success",
                                    title: "Success",
                                    timer: 1500,
                                    html: response.message || 'Data saved successfully.',
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                })
                                resetForm();

                                $('#ModalDusunBaru').modal('hide');
                                $('#desa_id').focus();
                            } else {
                                handleAjaxError(response);
                            }
                        },
                        error: function(xhr, status, error) {
                            handleAjaxError(xhr);
                        }
                    });
                });


                //Provinsi change event
                $('#provinsi_id').change(function() {
                    var provinsi_id = $(this).val();
                    if (provinsi_id) {
                        // AJAX call to get Kabupaten data
                        $.ajax({
                            url: "{{ route('api.kab', ':id') }}".replace(':id',
                                provinsi_id), // Replace with your kabupaten API route
                            type: 'GET',
                            dataType: 'json',
                            beforeSend: function() {
                                Toast.fire({
                                    icon: "info",
                                    title: "Loading {{ __('cruds.kabupaten.title') }}...",
                                    timer: 500,
                                });
                            },
                            success: function(data) {
                                $('#kabupaten_id').empty();
                                $('#kabupaten_id').append(
                                    '<option value="" selected>{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title') }}</option>'
                                ); // Add default option
                                $.each(data, function(key, value) {
                                    $('#kabupaten_id').append('<option value="' + value.id +
                                        '" data-id="' + value.kode + '">' + value.text +
                                        '</option>'); // Assuming API returns id and text
                                });
                                $('#kabupaten_id').trigger(
                                    'change'
                                    ); // Trigger change event to re-initialize Select2 (important!)
                            },
                            error: function(xhr, status, error) {
                                handleAjaxError(xhr); // Handle errors
                            }
                        });
                    } else {
                        // If provinsi is cleared, clear related dropdowns
                        $('#kabupaten_id').empty().trigger('change');
                        $('#kecamatan_id').empty().trigger('change');
                        $('#desa_id_dusun').empty().trigger('change');
                        $('#kode').val('');
                    }
                });

                // Kabupaten change event
                $('#kabupaten_id').change(function() {
                    var kabupaten_id = $(this).val();
                    if (kabupaten_id) {
                        $.ajax({
                            url: "{{ route('api.kec', ':id') }}".replace(':id', kabupaten_id),
                            type: 'GET',
                            dataType: 'json',
                            beforeSend: function() {
                                Toast.fire({
                                    icon: "info",
                                    title: "Loading {{ __('cruds.kecamatan.title') }}...",
                                    timer: 500,
                                });
                            },
                            success: function(data) {
                                $('#kecamatan_id').empty();
                                $('#kecamatan_id').append(
                                    '<option value="" selected>{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title') }}</option>'
                                ); // Add default option
                                $.each(data, function(key, value) {
                                    $('#kecamatan_id').append('<option value="' + value.id +
                                        '" data-id="' + value.kode + '">' + value.text +
                                        '</option>'); // Assuming API returns id and text
                                });
                                $('#kecamatan_id').trigger('change'); // Trigger change event to re-initialize Select2
                            },
                            error: function(xhr, status, error) {
                                handleAjaxError(xhr);
                            }
                        });
                    } else {
                        $('#kecamatan_id').empty().trigger('change');
                        $('#desa_id_dusun').empty().trigger('change');
                        $('#kode').val('');
                    }
                });

                // Kecamatan change event
                $('#kecamatan_id').change(function() {
                    var kecamatan_id = $(this).val();
                    if (kecamatan_id) {
                        // AJAX call to get Desa data
                        $.ajax({
                            url: "{{ route('api.desa', ':id') }}".replace(':id',
                                kecamatan_id), // Replace with your desa API route
                            type: 'GET',
                            dataType: 'json',
                            beforeSend: function() {
                                Toast.fire({
                                    icon: "info",
                                    title: "Loading {{ __('cruds.desa.title') }}...",
                                    timer: 500,
                                });
                            },
                            success: function(data) {
                                $('#desa_id_dusun').empty();
                                $('#desa_id_dusun').append(
                                    '<option value="" selected>{{ __('global.pleaseSelect') }} {{ __('cruds.desa.title') }}</option>'
                                ); // Add default option
                                $.each(data, function(key, value) {
                                    $('#desa_id_dusun').append('<option value="' + value
                                        .id + '" data-id="' + value.kode + '">' + value
                                        .text + '</option>'
                                    ); // Assuming API returns id and text
                                });
                                $('#desa_id_dusun').trigger('change'); // Trigger change event to re-initialize Select2
                            },
                            error: function(xhr, status, error) {
                                handleAjaxError(xhr);
                                // show info data not found or not added 
                                console.log('data not found or not added');
                            }
                        });
                    } else {
                        // If kecamatan is cleared, clear related dropdowns
                        $('#desa_id_dusun').empty().trigger('change');
                        $('#kode').val('');
                    }
                });

                // Desa change event
                $('#desa_id_dusun').change(function() {
                    // Get kode Dusun and populate the field
                    var kode_desa = $(this).find('option:selected').data('id');
                    if (kode_desa) {
                        $('#kode_desa').val(kode_desa); // Set the hidden input
                        $('#kode').val(kode_desa + '.').focus(); // Pre-fill and focus
                    } else {
                        $('#kode_desa').val(''); // Clear if no desa is selected
                        $('#kode').val('');
                    }
                });

                function handleAjaxError(xhr) {
                    let errorMessage = "An error occurred. Please try again later.";
                    let errorTitle = "Error!";

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            errorTitle = "Validation Error!";
                            errorMessage = "<ul>";
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                $.each(messages, function(index, message) {
                                    errorMessage += "<li>" + (field ? field + ": " : "") + message +
                                        "</li>"; // Show field name
                                });
                            });
                            errorMessage += "</ul>";
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: errorTitle,
                        html: errorMessage,
                    });
                }

            });
        </script>
    @endpush
