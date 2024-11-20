<script>
    $(document).ready(function () {
        $('#kegiatan-list').DataTable({
            serverSide: true,
            processing: true,
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
                {data: 'nama', name: 'nama', title: '{{ __('cruds.kegiatan.nama') }}'},
                {data: 'activity.program_outcome_output.program_outcome.program.nama', name: 'activity.program_outcome_output.program_outcome.program.nama', title: '{{ __('cruds.program.nama') }}'},
                {data: 'dusun.nama', name: 'dusun.nama', title: '{{ __('cruds.desa.form.nama') }}'},
                {data: 'tanggalmulai', name: 'tanggalmulai', title: '{{ __('cruds.kegiatan.tanggalmulai') }}'},
                {data: 'tanggalselesai', name: 'tanggalselesai', title: '{{ __('cruds.kegiatan.tanggalselesai') }}'},
                {data: 'duration_in_days', name: 'duration_in_days', title: '{{ __('cruds.kegiatan.durasi') }}'},
                {data: 'tempat', name: 'tempat', title: '{{ __('cruds.kegiatan.tempat') }}'},
                {data: 'status', name: 'status', title: '{{ __('cruds.kegiatan.status') }}'},
                {data: 'action', name: 'action', title: '{{ __('global.action') }}', orderable: false, searchable: false, className: 'text-center'},
            ],
            order: [0, 'asc'],
            lengthMenu: [10,25,50,100, 'all'],

        });
    });

</script>
