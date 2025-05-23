<script>
        $(document).ready(function () {
                $('#prepostTable').DataTable({
                    serverSide: false,
                    processing: true,
                    // responsive: true,
                    ajax: '{{ route('prepost.datatable') }}',
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            width: "5%",
                            name: 'No.',
                            className: "text-center",
                            title: '{{ __('No.') }}',
                            orderable: false,
                            searchable: false,
                        },
                        { data: 'program_name', name: 'program_name', title: '{{ __('cruds.program.nama') }}' },
                        { data: 'kegiatan_name', name: 'kegiatan_name', title: '{{ __('cruds.kegiatan.nama') }}' },
                        { data: 'trainingname', name: 'trainingname', title: '{{ __('cruds.prepost.nama_pelatihan') }}' },
                        { data: 'tanggalmulai', name: 'tanggalmulai', title: '{{ __('cruds.prepost.start') }}' }, // ✅ ubah dari 'tanggal_mulai'
                        { data: 'tanggalselesai', name: 'tanggalselesai', title: '{{ __('cruds.prepost.end') }}' }, // ✅ ubah dari 'tanggal_selesai'
                        { data: 'total_peserta', name: 'total_peserta', title: '{{ __('cruds.prepost.total_peserta') }}' },
                        { data: 'action', name: 'action',title: '{{ __('global.action') }}', orderable: false, searchable: false },
                    ],
                    layout: {
                        topStart: {
                            buttons: [{
                                    text: '<i class="fas fa-print"></i> <span class="d-none d-md-inline"></span>',
                                    className: 'btn btn-secondary',
                                    extend: 'print',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3,4,5] // Ensure these indices match your visible columns
                                    }
                                },
                                {
                                    text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline"></span>',
                                    className: 'btn btn-success',
                                    extend: 'excel',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3,4,5]
                                    }
                                },
                                {
                                    text: '<i class="fas fa-file-pdf"></i> <span class="d-none d-md-inline"></span>',
                                    className: 'btn btn-danger',
                                    extend: 'pdf',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3,4,5]
                                    }
                                },
                                {
                                    extend: 'copy',
                                    text: '<i class="fas fa-copy"></i> <span class="d-none d-md-inline"></span>',
                                    className: 'btn btn-info',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3,4,5]
                                    }
                                },
                                {
                                    extend: 'colvis',
                                    text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline"></span>',
                                    className: 'btn btn-warning',
                                    exportOptions: {
                                        columns: [0, 1, 2, 3,4,5]
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

            $(document).ready(function () {
                $('#prepostTable tbody').on('click', '.edit-prepost-btn', function () {
                    var id = $(this).data('prepost-id');
                    window.location.href = '{{ route('prepost.edit', ['id' => ':id']) }}'
                            .replace(':id', id);
                });
            });
</script>
