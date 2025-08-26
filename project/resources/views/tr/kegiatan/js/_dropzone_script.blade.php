{{--
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
</script> --}}

<script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
<link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />

<script>
    // Disable Dropzone auto-discovery
    Dropzone.autoDiscover = false;

    $(document).ready(function() {
        // Debug URL
        console.log("Dropzone URL:", "{{ route('kegiatan.update', $kegiatan->id ?? 0) }}");

        // Initialize Dropzone for dokumen_pendukung
        var dokumenDropzone = new Dropzone("#dokumenPendukungDropzone", {
            url: "{{ route('kegiatan.update', $kegiatan->id ?? 0) }}",
            paramName: "dokumen_pendukung",
            maxFiles: 25,
            maxFilesize: 50, // MB
            acceptedFiles: ".pdf,.doc,.docx,.xls,.xlsx,.pptx",
            addRemoveLinks: true,
            dictDefaultMessage: "Drag or click to upload documents (PDF, DOC, XLS, PPTX)",
            dictInvalidFileType: "Only PDF, DOC, XLS, and PPTX files are allowed.",
            dictFileTooBig: "File is too big. Max size: 50 MB.",
            dictMaxFilesExceeded: "You can upload up to 25 files only.",
            autoProcessQueue: false,
            clickable: true,
            init: function() {
                var dropzone = this;

                // Load pre-existing files
                @foreach ($dokumen_initialPreviewConfig as $index => $config)
                    var mockFile = {
                        name: "{{ addslashes($config['caption']) }}",
                        size: {{ $dokumen_files[$index]->size ?? 0 }},
                        accepted: true,
                        dataURL: "{{ $dokumen_initialPreview[$index] }}",
                        id: "{{ $config['key'] }}"
                    };
                    dropzone.emit("addedfile", mockFile);
                    dropzone.emit("thumbnail", mockFile, "{{ $dokumen_initialPreview[$index] }}");
                    dropzone.emit("complete", mockFile);
                    document.querySelector("#captions-container-docs").insertAdjacentHTML(
                        'beforeend',
                        `<div class="form-group caption-group" data-file-id="${mockFile.id}">
                            <label for="keterangan-${mockFile.id}">{{ __('cruds.program.ket_file') }}: <span class="text-green">{{ addslashes($config['caption']) }}</span></label>
                            <input type="text" class="form-control" name="keterangan[]" id="keterangan-${mockFile.id}" value="{{ addslashes($config['extra']['keterangan']) }}">
                        </div>`
                    );
                @endforeach

                // Add caption for new files
                this.on("addedfile", function(file) {
                    if (!file.id) {
                        file.id = 'new-' + new Date().getTime() + '-' + dropzone.files.length;
                        document.querySelector("#captions-container-docs").insertAdjacentHTML(
                            'beforeend',
                            `<div class="form-group caption-group" data-file-id="${file.id}">
                                <label for="keterangan-${file.id}">{{ __('cruds.program.ket_file') }}: <span class="text-red">${file.name}</span></label>
                                <input type="text" class="form-control" name="keterangan[]" id="keterangan-${file.id}" value="">
                            </div>`
                        );
                    }
                });

                // Remove caption when file is removed
                this.on("removedfile", function(file) {
                    var captionGroup = document.querySelector(`#captions-container-docs .caption-group[data-file-id="${file.id}"]`);
                    if (captionGroup) captionGroup.remove();
                    if (file.id && !file.id.startsWith('new-')) {
                        fetch("{{ $config['url'] ?? '' }}", {
                            method: "DELETE",
                            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                        }).catch(error => console.error("Delete failed:", error));
                    }
                });
            }
        });

        // Initialize Dropzone for media_pendukung
        var mediaDropzone = new Dropzone("#mediaPendukungDropzone", {
            url: "{{ route('kegiatan.update', $kegiatan->id ?? 0) }}",
            paramName: "media_pendukung",
            maxFiles: 25,
            maxFilesize: 50, // MB
            filesize: [0, 50], // MB
            acceptedFiles: ".jpg,.jpeg,.png",
            addRemoveLinks: true,
            dictDefaultMessage: "Drag or click to upload media (JPG, PNG)",
            dictInvalidFileType: "Only JPG and PNG files are allowed.",
            dictFileTooBig: "File is too big. Max size: 50MB.",
            dictMaxFilesExceeded: "You can upload up to 25 files only.",
            autoProcessQueue: false,
            clickable: true,
            init: function() {
                var dropzone = this;

                // Load pre-existing files
                @foreach ($media_initialPreviewConfig as $index => $config)
                    var mockFile = {
                        name: "{{ addslashes($config['caption']) }}",
                        size: {{ $media_files[$index]->size ?? 0 }},
                        accepted: true,
                        dataURL: "{{ $media_initialPreview[$index] }}",
                        id: "{{ $config['key'] }}"
                    };
                    dropzone.emit("addedfile", mockFile);
                    dropzone.emit("thumbnail", mockFile, "{{ $media_initialPreview[$index] }}");
                    dropzone.emit("complete", mockFile);
                    document.querySelector("#captions-container-media").insertAdjacentHTML(
                        'beforeend',
                        `<div class="form-group caption-group" data-file-id="${mockFile.id}">
                            <label for="keterangan-${mockFile.id}">{{ __('cruds.program.ket_file') }}: <span class="text-red">{{ addslashes($config['caption']) }}</span></label>
                            <input type="text" class="form-control" name="keterangan[]" id="keterangan-${mockFile.id}" value="{{ addslashes($config['extra']['keterangan']) }}">
                        </div>`
                    );
                @endforeach

                this.on("addedfile", function(file) {
                    if (!file.id) {
                        file.id = 'new-' + new Date().getTime() + '-' + dropzone.files.length;
                        document.querySelector("#captions-container-media").insertAdjacentHTML(
                            'beforeend',
                            `<div class="form-group caption-group" data-file-id="${file.id}">
                                <label for="keterangan-${file.id}">{{ __('cruds.program.ket_file') }}: <span class="text-red">${file.name}</span></label>
                                <input type="text" class="form-control" name="keterangan[]" id="keterangan-${file.id}" value="">
                            </div>`
                        );
                    }
                });

                this.on("removedfile", function(file) {
                    var captionGroup = document.querySelector(`#captions-container-media .caption-group[data-file-id="${file.id}"]`);
                    if (captionGroup) captionGroup.remove();
                    if (file.id && !file.id.startsWith('new-')) {
                        fetch("{{ $config['url'] ?? '' }}", {
                            method: "DELETE",
                            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                        }).catch(error => console.error("Delete failed:", error));
                    }
                });
            }
        });

        // Form submission with caption validation
        document.querySelector("#kegiatanForm").addEventListener("submit", function(e) {
            e.preventDefault();
            var form = this;
            var formData = new FormData(form);

            // Collect new files and captions
            var dokumenFiles = dokumenDropzone.files.filter(file => file.id && file.id.startsWith('new-'));
            var mediaFiles = mediaDropzone.files.filter(file => file.id && file.id.startsWith('new-'));
            var captions = Array.from(form.querySelectorAll('input[name="keterangan[]"]')).map(input => input.value);

            // Validation: Ensure captions match new files
            var totalNewFiles = dokumenFiles.length + mediaFiles.length;
            if (captions.length !== totalNewFiles) {
                alert("{{ json_encode(__('Error: Number of captions must match number of new files.')) }}");
                return;
            }

            // Append Dropzone files
            dokumenFiles.forEach(file => formData.append("dokumen_pendukung[]", file));
            mediaFiles.forEach(file => formData.append("media_pendukung[]", file));

            // AJAX submission
            fetch(form.action, {
                method: form.method,
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert("{{ json_encode(__('global.update_success')) }}");
                      window.location.reload();
                  } else {
                      alert("{{ json_encode(__('Error: ')) }}" + data.message);
                  }
              })
              .catch(error => {
                  console.error("Submission failed:", error);
                  alert("{{ json_encode(__('Submission failed. Please try again.')) }}");
              });
        });
    });
</script>
