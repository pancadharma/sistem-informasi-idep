
<!-- Index Kegiatan List -->
<script>
    $(document).ready(function () {
        $('#kegiatan-list').DataTable({
            serverSide: true,
            processing: true,
            // responsive: true,
            ajax: '{{ route('api.kegiatan.list') }}',
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
                {data: 'kode', name: 'kode', title: '{{ __('cruds.kegiatan.kode') }}'},
                {data: 'nama', name: 'nama', title: '{{ __('cruds.kegiatan.nama') }}'},
                {data: 'activity.program_outcome_output.program_outcome.program.nama', name: 'activity.program_outcome_output.program_outcome.program.nama', title: '{{ __('cruds.program.nama') }}'},
                {data: 'dusun.nama', name: 'dusun.nama', title: '{{ __('cruds.desa.form.nama') }}'},
                {data: 'tanggalmulai', name: 'tanggalmulai', title: '{{ __('cruds.kegiatan.tanggalmulai') }}'},
                {data: 'tanggalselesai', name: 'tanggalselesai', title: '{{ __('cruds.kegiatan.tanggalselesai') }}'},
                {data: 'duration_in_days', name: 'duration_in_days', title: '{{ __('cruds.kegiatan.durasi') }}'},
                {data: 'tempat', name: 'tempat', title: '{{ __('cruds.kegiatan.tempat') }}'},
                {data: 'fase', name: 'tempat', title: '{{ __('cruds.kegiatan.fase') }}'},
                {data: 'status', name: 'status', title: '{{ __('cruds.kegiatan.status') }}'},
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

