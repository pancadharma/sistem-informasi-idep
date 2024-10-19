<script>
    $('#updateProfile').on('submit', function(e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').attr('disabled', 'disabled');
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('_method', 'PUT');

        var userID = {{ $user->id }};

        console.log(formData);
        $.ajax({
            url: "{{ route('profiles.update', ':id' ) }}".replace(':id', userID),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                Toast.fire({
                    icon: "info",
                    title: "Processing...",
                    timer: 2000,
                    timerProgressBar: true,
                });
            },
            success: function(data) {
                $('#updateProfileBtn').removeAttr('disabled');
                setTimeout(() => {
                    if (data.success === true) {
                        Toast.fire({
                            title: "{{ __('global.success') }}",
                            text: data.message,
                            icon: "success",
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        $('.profile-user-img').attr('src', data.user.adminlte_image + '?t=' + new Date().getTime());
                         $('#profile_picture').val('');
                    }else {
                        var errorMessage = formatErrorMessages(data.errors);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                            confirmButtonText: 'Okay'
                        });
                    }
                }, 500);

            },
            error: function(xhr, jqXHR, textStatus, errorThrown) {
                $('#updateProfileBtn').removeAttr('disabled');
                var errorMessage = 'An unexpected error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message; // Get the message from the response
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonText: 'Okay'
                });
            },
            complete: function() {

                $('#updateProfileBtn').removeAttr('disabled');
            }

        });
    });

    function formatErrorMessages(errors) {
        let message = '<ul>';
        for (const field in errors) {
            errors[field].forEach(function(error) {
                message += `<li>${error}</li>`;
            });
        }
        message += '</ul>';
        return message;
    }
</script>
