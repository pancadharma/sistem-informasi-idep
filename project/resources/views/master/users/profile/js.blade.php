<script>
    var userID = {{ $user->id }};

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

    $('#updateProfile').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('_method', 'PUT');
        // $(this).find('button[type="submit"]').attr('disabled', 'disabled');
        $('#updateProfileBtn').attr('disabled', 'disabled');
        $.ajax({
            url: "{{ route('profiles.update', ':id') }}".replace(':id', userID),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                // $(this).find('button[type="submit"]').attr('disabled', 'disabled');
                // $('#updateProfileBtn').attr('disabled', 'disabled');
                Toast.fire({
                    icon: "info",
                    title: "Processing...",
                    timer: 1000,
                    timerProgressBar: true,
                });
            },
            success: function(data) {
                // $('#updateProfileBtn').removeAttr('disabled');
                setTimeout(() => {
                    if (data.success === true) {
                        Toast.fire({
                            title: "{{ __('global.success') }}",
                            text: data.message,
                            icon: "success",
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        $('.profile-user-img, .profile-pic, .img-url').attr('src', data.user.adminlte_image +'?t=' + new Date().getTime());
                        $('.img-url').attr('href', data.user.full_profile +'?t=' + new Date().getTime());
                        $('.user-desc').text(data.user.description);
                        $('.profile-username').text(data.user.nama);
                        $('#profile_picture').val('');
                    } else {
                        var errorMessage = formatErrorMessages(data.errors);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                }, 1000);

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
                setTimeout(function() {
                    $('#updateProfileBtn').removeAttr('disabled');
                }, 2000)
            }
        });
    });

    $('#updatePassword').on('submit', function(e) {

        e.preventDefault();
        $('#updatePasswordBtn').attr('disabled', 'disabled');
        if (!$(this).valid()) {
            $('#updatePasswordBtn').removeAttr('disabled');
            return;
        }
        let url = "{{ route('update.password') }}";
        let formData = $(this).serialize();
        $.ajax({
            url: url,
            method: 'PUT',
            data: formData,
            beforeSend: function() {
                Toast.fire({
                    icon: "info",
                    title: "Processing...",
                    timer: 2000,
                    timerProgressBar: true,
                });
            },
            success: function(data) {
                // $('#updatePasswordBtn').removeAttr('disabled');
                setTimeout(() => {
                    if (data.success === true) {
                        Toast.fire({
                            title: "{{ __('global.success') }}",
                            text: data.message,
                            icon: "success",
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        $('#updatePassword').trigger('reset')
                    } else {
                        var errorMessage = formatErrorMessages(data.errors);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                }, 1000);
            },
            error: function(xhr, jqXHR, textStatus, errorThrown) {
                $('#updatePasswordBtn').removeAttr('disabled');
                var errorMessage = 'An unexpected error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message; // Get the message from the response
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                setTimeout(function() {
                    $('#updatePasswordBtn').removeAttr('disabled');
                }, 2000)
            }
        });
    });
</script>
