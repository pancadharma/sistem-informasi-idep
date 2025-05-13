<script>
    $(document).ready(function () {
    $('#benchmarkTable').DataTable({
        serverSide: true,
        processing: false,
        // responsive: true,
        ajax: '{{ route('api.benchmark.datatable') }}',
        columns: [
            {
                data: 'DT_RowIndex',
                width: "5%",
                name: 'No.',
                className: "text-center",
                title: '{{ __('No.') }}',
                orderable: false,
                searchable: true,
            },
            // {data: 'kode', name: 'kode', title: '{{ __('cruds.program.kode') }}'},
            {data: 'nama', name: 'nama', title: '{{ __('cruds.program.nama') }}'},
            {data: 'tipekegiatan', name: 'tipekegiatan', title: '{{ __('cruds.benchmark.tipe_kegiatan') }}'},
            {data: 'namakegiatan', name: 'namakegiatan', title: '{{ __('cruds.benchmark.nama_kegiatan') }}'},
            {data: 'tanggalimplementasi', name: 'tanggalimplementasi', title: '{{ __('cruds.benchmark.tanggal_implementasi') }}'},
            {data: 'score', name: 'score', title: '{{ __('cruds.benchmark.score') }}'},
            {data: 'action', name: 'action', title: '{{ __('global.action') }}', orderable: false, searchable: false, className: 'text-center'},
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
        lengthMenu: [10,25,50,100],

    });
});
</script>
