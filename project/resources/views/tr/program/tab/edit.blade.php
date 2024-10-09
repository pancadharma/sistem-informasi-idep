@extends('layouts.app')

@section('subtitle', __('Upload'))
@section('content_header_title', __('Uploads'))
@section('sub_breadcumb', __('Uploads'))

@section('content_body')
    @include('master.role.create')
    <div class="card card-outline card-primary">
        <div class="card-body table-responsive">
        <form action="{{ route('trprogram.update.doc', [$program->id]) }}" id="upload-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="files">Select Files</label>
                    <div class="file-loading">
                        <input id="file-input" name="files[]" type="file" multiple>
                    </div>
                    <div id="captions-container"></div>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
@endpush


@push('js')
    <script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/buffer.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/sortable.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/piexif.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/locales/id.js') }}"></script>
    <script>
        // Good to Remove Caption / Keterangan Input Field When user click/ re-browse to select more files
        $(document).ready(function() {
            var fileIndex = 0;
            var fileCaptions = {}; // Object to track files and captions

            $("#file-input").fileinput({
                theme: "fa5",
                showUpload: false,
                showRemove: false,
                browseOnZoneClick: true,
                allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf'],
                maxFileSize: 10000,
                maxFilesNum: 10,
                overwriteInitial: false, // Ensure new files are added without removing previous ones
                append: true,
                initialPreview: @json($initialPreview),
                initialPreviewAsData: true,
                initialPreviewConfig: @json($initialPreviewConfig),
                overwriteInitial: false,
                previewFileType: 'any', // Ensure all file types are previewed
                fileActionSettings: {
                    showZoom: function(config) {
                        return config.type === 'pdf'; // Enable zoom for PDFs
                    },
                    showRemove: true, // Show remove button
                    removeIcon: '<i class="fa fa-trash"></i>',
                    removeClass: 'btn btn-sm btn-kv btn-default btn-outline-secondary',
                    removeTitle: 'Delete file',
                    removeError: 'Error deleting file',
                    removeUrl: function(config) {
                        return config.url;
                    },
                    removeMethod: 'DELETE',
                    remove: function(config) {
                        var url = config.url;
                        console.log('Sending DELETE request to:', url); // Debugging line
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    console.log('File deleted successfully');
                                } else {
                                    console.error('Error deleting file');
                                }
                            },
                            error: function(xhr) {
                                console.error('Error deleting file');
                            }
                        });
                    }
                },

            }).on('fileloaded', function(event, file, previewId, index, reader) {
                // Increment the file index for each new file
                fileIndex++;
                var uniqueId = 'file-' + fileIndex;
                fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
                $('#captions-container').append(
                    `<div class="form-group" id="caption-group-${uniqueId}">
                <label for="caption-${uniqueId}">Caption for ${file.name}</label>
                <input type="text" class="form-control" name="captions[]" id="caption-${uniqueId}">
            </div>`
                );
                // Store the unique identifier in the file preview element
                $(`#${previewId}`).attr('data-unique-id', uniqueId);
            }).on('fileremoved', function(event, id) {
                // Remove the corresponding caption input
                var uniqueId = $(`#${id}`).attr('data-unique-id');
                delete fileCaptions[uniqueId]; // Remove the file from the tracking object
                $(`#caption-group-${uniqueId}`).remove();
            }).on('fileclear', function(event) {
                // Clear all caption inputs when files are cleared
                fileCaptions = {}; // Reset the tracking object
                $('#captions-container').empty();
            }).on('filebatchselected', function(event, files) {
                // Clear all caption inputs when new files are selected
                fileCaptions = {}; // Reset the tracking object
                $('#captions-container').empty();

                // Iterate over each selected file and trigger the fileloaded event manually
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    fileIndex++;
                    var uniqueId = 'file-' + fileIndex;
                    fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
                    $('#captions-container').append(
                        `<div class="form-group" id="caption-group-${uniqueId}">
                    <label for="caption-${uniqueId}">Caption for ${file.name}</label>
                    <input type="text" class="form-control" name="captions[]" id="caption-${uniqueId}">
                </div>`
                    );
                }
            });
        });
        $('#upload-form').on('submit', function(e) {
        e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "Uploading...",
                        timer: 2000,
                        timerProgressBar: true,
                    });
                },
                success: function(response) {
                    Swal.fire({
                        title: "Success",
                        text: response.success,
                        icon: 'success',
                        timer: 2000,
                    });
                    $('#upload-form').trigger('reset');
                },
                error: function(response) {
                    alert('File upload failed');
                }
            });
        });
        // $('#upload-form').on('submit', function(e) {
        //     e.preventDefault();
        //     var formData = new FormData(this);
        //     // allFiles.forEach(file => formData.append('files[]', file));

        //     $.ajax({
        //         url: $(this).attr('action'),
        //         type: 'POST',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         beforeSend: function() {
        //             Toast.fire({
        //                 icon: "info",
        //                 title: "Uploading...",
        //                 timer: 2000,
        //                 timerProgressBar: true,
        //             });
        //         },
        //         success: function(response) {
        //             Swal.fire({
        //                 title: "Success",
        //                 text: response.success,
        //                 icon: 'success',
        //                 timer: 2000,
        //             });
        //             // alert('Files uploaded successfully');
        //             $('#upload-form').trigger('reset');
        //             // window.location.reload();
        //         },
        //         error: function(response) {
        //             alert('File upload failed');
        //         }
        //     });
        // });
    </script>
    @section('plugins.Toastr', true)
@endpush
