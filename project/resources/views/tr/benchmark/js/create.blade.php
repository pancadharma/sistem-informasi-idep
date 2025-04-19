<script>
    $(document).ready(function () {
        $('#benchmarkForm').on('submit', function (e) {
            e.preventDefault();

            let form = $(this)[0];
            let formData = new FormData(form);

            $.ajax({
                url: "{{ route('benchmark.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Benchmark berhasil disimpan!',
                    });
                    $('#benchmarkForm')[0].reset();
                    $('.select2').val(null).trigger('change'); // reset select2
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    $.each(errors, function (key, value) {
                        errorMessage += `- ${value.join(', ')}\n`;
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal menyimpan!',
                        text: errorMessage,
                        customClass: {
                            popup: 'text-start'
                        }
                    });
                }
            });
        });

        $('#program_id, #jenis_kegiatan_id, #outcome_activity_id, #dusun_id, #user_handler_id, #user_compiler_id').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

       
    });
</script>
