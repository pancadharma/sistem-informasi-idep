<script>
    $(document).ready(function () {
        $('#penerimaManfaat').DataTable({
            serverSide: true,
            processing: true,
            ajax: '{{ route('api.beneficiary.datatable') }}',
            columns: [
                {
                    data: 'DT_RowIndex',
                    width: "3%",
                    name: 'No.',
                    className: "text-center",
                    title: '{{ __('No.') }}',
                    orderable: false,
                    searchable: true,
                },
                { data: 'kode', name: 'kode', title: '{{ __('cruds.beneficiary.program_code') }}' },
                { data: 'program_name', name: 'program_name', title: '{{ __('cruds.beneficiary.program_name') }}' },
                { data: 'total_beneficiaries', name: 'total_beneficiaries', title: '{{ __('cruds.kegiatan.peserta.total') }}' }, // Updated to match withCount
                { data: 'action', name: 'action', title: '{{ __('global.action') }}', orderable: false, searchable: false, className: 'text-center' },
            ],
            layout: {
                topStart: {
                    buttons: [{
                        text: '<i class="fas fa-print"></i> <span class="d-none d-md-inline"></span>',
                        className: 'btn btn-secondary',
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3] // Ensure these indices match your visible columns
                        }
                    },
                    {
                        text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline"></span>',
                        className: 'btn btn-success',
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        text: '<i class="fas fa-file-pdf"></i> <span class="d-none d-md-inline"></span>',
                        className: 'btn btn-danger',
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> <span class="d-none d-md-inline"></span>',
                        className: 'btn btn-info',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline"></span>',
                        className: 'btn btn-warning',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                ],
                },
                bottomStart: {
                    pageLength: 10,
                }
            },
            order: [1, 'asc'],
            lengthMenu: [10, 25, 50, 100],
        });
    });

    $(document).ready(function () {
        $('#penerimaManfaat tbody').on('click', '.edit-beneficiary-program-btn', function () {
            var id = $(this).data('beneficiary-program-id');
            window.location.href = '{{ route('beneficiary.edit', ['program' => ':id']) }}'
                .replace(':id', id);
        });
    });
</script>
