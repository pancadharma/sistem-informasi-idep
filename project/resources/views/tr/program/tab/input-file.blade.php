{{-- @foreach ($mediaFiles as $media)
<div class="file-preview">
    @if (in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif']))

    <img src="{{ $media->getUrl() }}" alt="{{ $media->file_name }}" class="img-thumbnail">

    @elseif ($media->mime_type == 'application/pdf')
    <a href="{{ $media->getUrl() }}" target="_blank">{{ $media->file_name }}</a>
    @else
    <img src="{{ asset('path/to/pdf-icon.png') }}" alt="{{ $media->file_name }}" class="img-thumbnail">
    @endif
    <form action="{{ route('trprogram.delete.doc', ['media' => $media->id]) }}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
    </form>
</div>
@endforeach --}}



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
            showRemove: true,
            showDelete: false,
            browseOnZoneClick: true,
            allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf'],
            maxFileSize: 10000,
            maxFilesNum: 10,
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
            fileIndex++;
            var uniqueId = 'file-' + fileIndex;
            fileCaptions[uniqueId] = file.name;

            $('#captions-container').append(
                `<div class="form-group" id="caption-group-${uniqueId}">
                        <label for="caption-${uniqueId}">KET : ${file.name}</label>
                        <input type="text" class="form-control" name="captions[]" id="caption-${uniqueId}">
                        </div>`
            );

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
                fileCaptions[uniqueId] = file.name;
                $('#captions-container').append(
                    `<div class="form-group" id="caption-group-${uniqueId}">
                    <label for="caption-${uniqueId}">Caption for ${file.name}</label>
                    <input type="text" class="form-control" name="captions[]" id="caption-${uniqueId}">
                </div>`
                );
            }
        });
    });


    $(document).on('click', '.kv-file-remove', function() {
        var url = $(this).data('url');
        console.log('Sending DELETE request to:', url); // Debugging line
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
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
                console.log('AJAX success:', response);
                if (response.success) {
                    Swal.fire({
                        title: "Success",
                        text: response.success,
                        icon: 'success',
                        timer: 2000,
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        // text: response,
                        icon: 'error',
                        timer: 2000,
                    });
                }
            },
            error: function(xhr) {
                console.error('AJAX error:', xhr);
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
    //     $.ajax({
    //         url: $(this).attr('action'),
    //         type: 'PUT',
    //         data: formData,
    //         contentType: false,
    //         processData: false,
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
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
    //             $('#upload-form').trigger('reset');
    //         },
    //         error: function(response) {
    //             alert('File upload failed');
    //         }
    //     });
    // });

    // $('#upload-form').on('submit', function(e) {
    //     e.preventDefault();
    //     var _token = '{{ csrf_token() }}';
    //     var formData = new FormData(this);
    //     $.ajax({
    //         url: $(this).attr('action'),
    //         type: 'PUT',
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
