<script>
    $(document).ready(function() {
        // $("#provinsiForm").validate({
        //     errorElement: 'span',
        //     errorPlacement: function(error, element) {
        //         error.addClass('invalid-feedback');
        //         element.closest('.form-group').append(error);
        //     },
        //     highlight: function(element) {
        //         $(element).addClass('is-invalid');
        //     },
        //     unhighlight: function(element) {
        //         $(element).removeClass('is-invalid');
        //     },
            // submitHandler: function(form) {
            //     var formData = $(form).serialize();
            $('.btn-add-provinsi').on('click', function(e) {
            e.preventDefault();
            var formDataprovinsi = $('#provinsiForm').serialize();
                $.ajax({
                    method: "POST",
                    url: '{{ route('provinsi.store') }}', // Get form action URL
                    // data: formData,
                    data: formDataprovinsi,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success"
                            });
                            // $('#addProvinsi').modal('hide');
                            $('#provinsiForm').trigger('reset');
                            $('#provinsi').DataTable().ajax.reload();
                        } else {
                            var errorMessage = response.message;
                            if (response.status === 400) {
                                try {
                                    const errors = JSON.parse(response.responseText).errors;
                                    errorMessage = Object.values(errors).flat().map(error => `<p>* ${error}</p>`).join('');
                                } catch (error) {
                                    errorMessage = "<p>An unexpected error occurred. Please try again later.</p>";
                                }
                            }
                            Swal.fire({
                                title: "Error!",
                                html: errorMessage,
                                icon: "error"
                            });
                            $('#addProvinsi').modal('hide');
                        }
                    },
                    error: function(jqXHR) {
                        const errors = JSON.parse(jqXHR.responseText).errors;
                        const errorMessage = Object.values(errors).flat().map(error => `<p>* ${error}</p>`).join('');
                        Swal.fire({
                            title: jqXHR.statusText,
                            html: errorMessage,
                            icon: 'error'
                        });
                        $('#provinsiForm').trigger('reset');
                    }
                });
            });
            // }
        });
    // });
</script>