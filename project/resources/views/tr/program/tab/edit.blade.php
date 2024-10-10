@extends('layouts.app')

@section('subtitle', __('Upload'))
@section('content_header_title', __('Uploads'))
@section('sub_breadcumb', __('Uploads'))

@section('content_body')
    @include('master.role.create')
    <div class="card card-outline card-primary">
        <div class="card-body table-responsive">
            <form action="{{ route('trprogram.update.doc', [$program->id]) }}" id="upload-form" method="POST"
                enctype="multipart/form-data">
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

        $(document).on('click', '.kv-file-remove', function() {

            let mediaID = $(this).data('key');
            let url = '{{ route('trprogram.delete.doc', ':id') }}'.replace(':id', mediaID);

            console.log('Sending DELETE request to:', url); // Debugging line
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                // beforeSend: function() {
                //     Toast.fire({
                //         icon: "info",
                //         title: "Deleting...",
                //         timer: 2000,
                //         timerProgressBar: true,
                //     });
                // },
                success: function(response) {
                    console.log('AJAX success:', response);
                    if (response.success) {
                        Toast.fire({
                            icon: "success",
                            title: "Files Deleted",
                            timer: 2000,
                            timerProgressBar: true,
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            icon: 'error',
                            timer: 2000,
                        });
                    }
                    // window.location.reload();
                    let filePreview = $(`.kv-file-remove[data-key="${mediaID}"]`).closest(
                        '.file-preview-frame');
                    filePreview.remove();
                },

                error: function(xhr) {
                    console.error('AJAX error:', xhr);
                }
            });
        });



        // INIT LOAD MEDIA
        $(document).ready(function() {
            var fileIndex = 0;
            var fileCaptions = {}; // Object to track files and captions

            $("#file-input").fileinput({
                theme: "fa5",
                showUpload: false,
                showBrowse: false,
                browseOnZoneClick: true,
                showRemove: false,
                allowedFileExtensions: ['jpg', 'png', 'jpeg', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx',
                    'csv', 'gif', 'pdf',
                ],
                maxFileSize: 10000,
                overwriteInitial: false, // Ensure new files are added without removing previous ones
                append: true,
                initialPreview: @json($initialPreview),
                initialPreviewAsData: true,
                initialPreviewConfig: @json($initialPreviewConfig),
                previewFileType: 'any', // Ensure all file types are previewed
                fileActionSettings: {
                    showZoom: function(config) {
                        return config.type === 'pdf'; // Enable zoom for PDFs
                    },
                    showZoom: function(config) {
                        return config.type === 'image/*'; // Enable zoom for PDFs
                    },
                    showZoom: function(config) {
                        return config.type === 'office'; // Enable zoom for PDFs
                    },
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
                previewFileIconSettings: { // configure your icon file extensions
                    'doc': '<i class="fas fa-file-word text-primary"></i>',
                    'xls': '<i class="fas fa-file-excel text-success"></i>',
                    'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                    'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                    'zip': '<i class="fas fa-file-archive text-muted"></i>',
                    'htm': '<i class="fas fa-file-code text-info"></i>',
                    'txt': '<i class="fas fa-file-alt text-info"></i>',
                },
                previewFileExtSettings: { // configure the logic for determining icon file extensions
                    'doc': function(ext) {
                        return ext.match(/(doc|docx)$/i);
                    },
                    'xls': function(ext) {
                        return ext.match(/(xls|xlsx)$/i);
                    },
                    'ppt': function(ext) {
                        return ext.match(/(ppt|pptx)$/i);
                    },
                    'zip': function(ext) {
                        return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
                    },
                    'htm': function(ext) {
                        return ext.match(/(htm|html)$/i);
                    },
                    'txt': function(ext) {
                        return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
                    },
                    'mov': function(ext) {
                        return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
                    },
                    'mp3': function(ext) {
                        return ext.match(/(mp3|wav)$/i);
                    }
                },

            }).on('fileloaded', function(event, file, previewId, index, reader) {
                fileIndex++;
                var uniqueId = 'file-' + fileIndex;
                fileCaptions[uniqueId] = file.name;
                $('#captions-container').append(
                    `<div class="form-group" id="caption-group-${uniqueId}"><label for="caption-${uniqueId}">Caption for ${file.name}</label>
                        <input type="text" class="form-control" name="captions[]" id="caption-${uniqueId}">
                    </div>`);
                $(`#${previewId}`).attr('data-unique-id', uniqueId);
            }).on('fileremoved', function(event, id) {
                var uniqueId = $(`#${id}`).attr('data-unique-id');
                delete fileCaptions[uniqueId];
                $(`#caption-group-${uniqueId}`).remove();
            }).on('fileclear', function(event) {
                fileCaptions = {};
                $('#captions-container').empty();
            }).on('filebatchselected', function(event, files) {
                fileCaptions = {};
                $('#captions-container').empty();
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
    </script>
    @section('plugins.Toastr', true)
@endpush
