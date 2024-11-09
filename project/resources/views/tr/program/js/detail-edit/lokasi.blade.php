
<script type="text/javascript" defer>
    $(document).ready(function() {  // Shorter syntax for document.ready
        const CONFIG = {
            dataUrl: "{{ route('api.program.lokasi') }}",
            placeholder: '{{ __('cruds.program.lokasi.select') }}',
            editDataLokasi: @json($program->lokasi->map(fn($lokasi) => [
                'id' => $lokasi->id,
                'text' => $lokasi->nama
            ])),
            selectedLokasi: @json($program->lokasi->pluck('id')),
        };

        const select2Options = {
            placeholder: CONFIG.placeholder,
            width: '100%',
            data: CONFIG.editDataLokasi || [],
            allowClear: true,
            dropdownPosition: 'below',
            ajax: {
                url: CONFIG.dataUrl,
                method: 'GET',
                delay: 250,  // Reduced delay for better responsiveness
                cache: true, // Enable caching
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.nama
                    }))
                }),
                data: params => ({
                    search: params.term,
                    page: params.page || 1
                })
            },
            // minimumInputLength: 2, // Only trigger ajax after 2 characters
            templateResult: formatResult,
            templateSelection: formatSelection
        };

        // Format the dropdown results
        function formatResult(data) {
            if (data.loading) return data.text;
            return $('<span>').text(data.text);
        }

        // Format the selected option
        function formatSelection(data) {
            return data.text;
        }

        // Initialize Select2
        const $lokasi = $('#lokasi').select2(select2Options);

        // Set initial value if exists
        if (CONFIG.selectedLokasi.length > 0) {
            $lokasi.val(CONFIG.selectedLokasi).trigger('change');
        }

        // Error handling
        $lokasi.on('select2:error', function(e) {
            console.error('Select2 error:', e);
        });

        // Destroy Select2 on page unload to prevent memory leaks
        $(window).on('beforeunload', function() {
            if ($lokasi.data('select2')) {
                $lokasi.select2('destroy');
            }
        });
    });

</script>
