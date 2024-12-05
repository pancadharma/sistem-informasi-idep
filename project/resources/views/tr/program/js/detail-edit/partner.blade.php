{{-- @push('js')
    <script>
        var data_partner = "{{ route('api.program.partner') }}";
        var placeholder = "{{ __('global.pleaseSelect') . ' ' . __('cruds.program.partner.label') }}";

        $('#partner').select2({
            placeholder: placeholder,
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_partner,
                method: 'GET',
                delay: 1000,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            }
        });

    </script>
@endpush --}}

@push('js')
    <script type="text/javascript" defer>
        $(document).ready(function() {  // Shorter syntax for document.ready
            const CONFIG = {
                dataUrl: "{{ route('api.program.partner') }}",
                placeholder: '{{ __('cruds.program.partner.label') }}',
                editDataPartner: @json($program->partner->map(fn($partner) => [
                    'id' => $partner->id,
                    'text' => $partner->nama
                ])),
                selectedPartner: @json($program->partner->pluck('id')),
            };

            const select2Options = {
                placeholder: CONFIG.placeholder,
                width: '100%',
                data: CONFIG.editDataPartner || [],
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
            const $partner = $('#partner').select2(select2Options);

            // Set initial value if exists
            if (CONFIG.selectedPartner.length > 0) {
                $partner.val(CONFIG.selectedPartner).trigger('change');
            }

            // Error handling
            $partner.on('select2:error', function(e) {
                console.error('Select2 error:', e);
            });

            // Destroy Select2 on page unload to prevent memory leaks
            $(window).on('beforeunload', function() {
                if ($partner.data('select2')) {
                    $partner.select2('destroy');
                }
            });
        });

    </script>
@endpush