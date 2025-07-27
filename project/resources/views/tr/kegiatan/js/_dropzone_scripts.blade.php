
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

<script>
    Dropzone.autoDiscover = false;

    // Document Dropzone
    var uploadedDokumenMap = {};
    var dokumenDropzone = new Dropzone("#dokumen_pendukung-dropzone", {
        url: "{{ route('api.kegiatan.storeMedia') }}",
        maxFilesize: 50, // MB
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
            size: 50,
            collection: 'dokumen_pendukung'
        },
        success: function(file, response) {
            $('form').append('<input type="hidden" name="dokumen_pendukung[]" value="' + response.name + '">');
            uploadedDokumenMap[file.name] = response.name;
        },
        removedfile: function(file) {
            file.previewElement.remove();
            var name = '';
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name;
            } else {
                name = uploadedDokumenMap[file.name];
            }
            $('form').find('input[name="dokumen_pendukung[]"][value="' + name + '"]').remove();
        },
        init: function() {
            @if (isset($kegiatan) && $kegiatan->dokumenPendukung)
                var files = {!! json_encode($kegiatan->dokumenPendukung) !!};
                for (var i in files) {
                    var file = files[i];
                    this.options.addedfile.call(this, file);
                    file.previewElement.classList.add('dz-complete');
                    $('form').append('<input type="hidden" name="dokumen_pendukung[]" value="' + file.file_name + '">');
                }
            @endif
        }
    });

    // Media Dropzone
    var uploadedMediaMap = {};
    var mediaDropzone = new Dropzone("#media_pendukung-dropzone", {
        url: "{{ route('api.kegiatan.storeMedia') }}",
        maxFilesize: 50, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
            size: 50,
            width: 4096,
            height: 4096,
            collection: 'media_pendukung'
        },
        success: function(file, response) {
            $('form').append('<input type="hidden" name="media_pendukung[]" value="' + response.name + '">');
            uploadedMediaMap[file.name] = response.name;
        },
        removedfile: function(file) {
            file.previewElement.remove();
            var name = '';
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name;
            } else {
                name = uploadedMediaMap[file.name];
            }
            $('form').find('input[name="media_pendukung[]"][value="' + name + '"]').remove();
        },
        init: function() {
            @if (isset($kegiatan) && $kegiatan->mediaPendukung)
                var files = {!! json_encode($kegiatan->mediaPendukung) !!};
                for (var i in files) {
                    var file = files[i];
                    this.options.addedfile.call(this, file);
                    this.options.thumbnail.call(this, file, file.original_url);
                    file.previewElement.classList.add('dz-complete');
                    $('form').append('<input type="hidden" name="media_pendukung[]" value="' + file.file_name + '">');
                }
            @endif
        }
    });
</script>
