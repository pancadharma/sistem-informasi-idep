 <div class="modal fade" id="ModalAddJenisKelompok" tabindex="-1" role="dialog" aria-labelledby="ModalAddJenisKelompokLabel"
    aria-hidden="true" >
    <div class="modal-dialog modal-dialog-scrollable ">
        <div class="modal-content">
            <div class="modal-header bg-red">
                <h5 class="modal-title" id="ModalAddJenisKelompokLabel">
                    {{ __('global.create') . ' ' . __('cruds.beneficiary.penerima.jenis_kelompok') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="add_jenis_kelompok" class="add_jenis_kelompok">
                    <form id="tambah_jenis_kelompok" method="POST" autocomplete="off" novalidate>
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="nama_jenis_kelompok">{{ __('cruds.beneficiary.penerima.jenis_kelompok') }}</label>
                            <input type="text" id="nama_jenis_kelompok" name="nama_jenis_kelompok" class="form-control" required maxlength="200" pattern="^[A-Za-z][A-Za-z0-9 .]*$" title="Must start with a letter.">
                        </div>
                        <div class="form-group">
                            <strong>{{ __('cruds.status.title') }}</strong>
                            <div class="icheck-primary">
                                <input type="checkbox" name="aktif" id="aktif" {{ old('aktif', 1) == 1 ? 'checked' : '' }} value="1">
                                <label for="aktif">{{ __('cruds.status.aktif') }}</label>
                            </div>
                        </div>
                        <button class="btn btn-secondary float-left" type="button" data-dismiss="modal">{{ __('global.close')}}</button>
                        <button class="btn btn-success float-right btn-add-jenis-kelompok" type="submit"><i class="fas fa-save"></i> {{ __('global.save') }}</button>
                    </form>
                </div>
                <!--
                <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
                     <button type="submit" class="btn btn-primary" id="save_jenis_kelompok">{{ __('global.save') }}</button>
                    </div>
                -->
            </div>
        </div>
    </div>
 </div>

 @push('js')
 <script>
    $(document).ready(function() {

        $('#tambah_jenis_kelompok').on('submit', function(e) {
            e.preventDefault();
            if (!$(this).valid()) {
                return; // Stop the form submission if validation fails
            }
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('api.jenis_kelompok.simpan') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
                    } else {
                        handleAjaxError(response);
                    }
                },
                error: function(xhr, status, error) {
                    handleAjaxError(xhr);
                }
            });
        })

        function resetForm() {
            $('#tambah_jenis_kelompok')[0].reset();
            $('.form-group').find('.is-invalid').removeClass('is-invalid');
            $('.form-group').find('.is-valid').removeClass('is-valid');
            $('.form-group').find('.invalid-feedback').text('');
            $('#ModalAddJenisKelompok').modal('hide');
        }

        $('#tambah_jenis_kelompok').validate({
            rules: {
                nama_jenis_kelompok: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                nama_jenis_kelompok: {
                    required: "{{ __('validation.required', ['attribute' => __('cruds.jenisKelompokInstansi.fields.nama')]) }}",
                    minlength: "{{ __('validation.minlength', ['attribute' => __('cruds.jenisKelompokInstansi.fields.nama'), 'min' => 3]) }}"
                }
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
