
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
                    // width: "2%",
                    name: 'No.',
                    className: "text-center align-middle",
                    title: '{{ __('No.') }}',
                    orderable: true,
                    searchable: false
                },
                { data: 'kegiatan_kode', name: 'kegiatan_kode', title: '{{ __('cruds.kegiatan.kode') }}', orderable: true, searchable: true }, // Corrected
                { data: 'activity.nama', name: 'activity.nama', title: '{{ __('cruds.kegiatan.nama') }}', orderable: true, searchable: true },  // Corrected
                { data: 'program_name', name: 'program_name', title: '{{ __('cruds.program.nama') }}', orderable: true, searchable: true }, // Corrected
                // { data: 'sektor_names', name: 'sektor_names', title: '{{ __('cruds.kegiatan.basic.sektor_kegiatan') }}', orderable: true, searchable: true, className: 'ellipsis-cell' }, // New column
                // { data: 'jenis_kegiatan', name: 'jenis_kegiatan', title: '{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}', orderable: true, searchable: true, className: 'ellipsis-cell' }, // New column
                {
                    data: 'sektor_names',
                    name: 'sektor_names',
                    title: '{{ __('cruds.kegiatan.basic.sektor_kegiatan') }}',
                    orderable: true,
                    searchable: true,
                    className: 'ellipsis-cell',
                    render: function (data, type, row) {
                        let random_color = ['success', 'info', 'dark', 'danger', 'warning', 'info'];
                        let badges = data.map(function(item) {
                            let random_color_index = Math.floor(Math.random() * random_color.length);
                            let color = random_color[random_color_index];
                            return '<span class="badge badge-sm badge-' + color + ' me-1" title="' + item + '">' + item + '</span>,';
                            // return '<span title="'+item+'">'+item+'</span>';
                        });
                        return badges.join(' ');
                    }
                },
                {
                    data: 'jenis_kegiatan',
                    name: 'jenis_kegiatan',
                    title: '{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}',
                    orderable: true,
                    searchable: true,
                    className: 'ellipsis-cell',
                    render: function (data, type, row) {
                        if (!data) {
                            return '-';
                        }
                        let random_color = ['success', 'info', 'dark', 'light', 'danger', 'warning', 'info'];
                        let randomIndex = Math.floor(Math.random() * random_color.length);
                        let color = random_color[randomIndex];

                        return '<span class="badge badge-sm badge-' + color + '" title="' + data + '">' + data + '</span>';
                    }
                },
                { data: 'total_beneficiaries', name: 'total_beneficiaries', title: '{{ __('Total Beneficiaries') }}', orderable: true, searchable: true, className: 'text-center' },
                { data: 'tanggalmulai', name: 'tanggalmulai', title: '{{ __('cruds.kegiatan.tanggalmulai') }}', orderable: true, searchable: true },
                { data: 'tanggalselesai', name: 'tanggalselesai', title: '{{ __('cruds.kegiatan.tanggalselesai') }}', orderable: true, searchable: true },
                { data: 'duration_in_days', name: 'duration_in_days', title: '{{ __('cruds.kegiatan.durasi') }}', orderable: true, searchable: false },
                { data: 'fasepelaporan', name: 'fasepelaporan', title: '{{ __('cruds.kegiatan.fase') }}', orderable: true, searchable: true, className: 'text-center' },
                {
                    data: 'status',
                    name: 'status',
                    title: '{{ __('cruds.kegiatan.status') }}',
                    orderable: true,
                    searchable: true,
                    className: 'text-center',
                    render: function (data, type, row) {
                        if (data  == 'completed') {
                            return '<span class="badge badge-sm badge-success">' + data + '</span>';
                        } else if (data  == 'draft') {
                            return '<span class="badge badge-sm badge-secondary">' + data + '</span>';
                        } else if (data  == 'cancelled') {
                            return '<span class="badge badge-sm badge-danger">' + data + '</span>';
                        } else if (data  == 'ongoing') {
                            return '<span class="badge badge-sm badge-warning">' + data + '</span>';
                        }
                    }
                },
                { data: 'action', name: 'action', title: '{{ __('global.action') }}', orderable: false, searchable: false, className: 'text-center' }
            ],
            layout: {
                topStart: {
                    buttons: [{
                            text: '<i class="fas fa-print"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-secondary',
                            extend: 'print',
                            exportOptions: {
                                // columns: [0, 1, 2, 3] // Ensure these indices match your visible columns
                            }
                        },
                        {
                            text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-success',
                            extend: 'excel',
                            exportOptions: {
                                // columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            text: '<i class="fas fa-file-pdf"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-danger d-none',
                            extend: 'pdf',
                            exportOptions: {
                                // columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-info d-none',
                            exportOptions: {
                                // columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-warning',
                            exportOptions: {
                                // columns: [0, 1, 2, 3]
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

