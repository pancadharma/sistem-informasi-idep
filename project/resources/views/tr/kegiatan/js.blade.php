<script>
    $(document).ready(function () {
        $('#kegiatan-list').DataTable({
            serverSide: true,
            processing: true,
            ajax: '{{ route('api.kegiatan.list') }}',
            columns: [
                {data: 'id', name: 'id', title: '{{ __('No.') }}'},
                {data: 'nama', name: 'nama', title: '{{ __('cruds.kegiatan.nama') }}'},
                {data: 'nama', name: 'nama', title: '{{ __('cruds.program.nama') }}'},
                {data: 'nama', name: 'nama', title: '{{ __('cruds.desa.form.nama') }}'},
                {data: 'tanggalmulai', name: 'tanggalmulai', title: '{{ __('cruds.kegiatan.tanggalmulai') }}'},
                {data: 'tanggalselesai', name: 'tanggalselesai', title: '{{ __('cruds.kegiatan.tanggalselesai') }}'},
                {data: 'tempat', name: 'tempat', title: '{{ __('cruds.kegiatan.tempat') }}'},
                {data: 'status', name: 'status', title: '{{ __('cruds.kecamatan.aktif') }}'},
                {data: 'action', name: 'action', title: '{{ __('global.action') }}', orderable: false, searchable: false, className: 'text-center'},
            ],
            order: [0, 'asc'],
            lengthMenu: [10,25,50,100, 'all'],
            
        });
    });

</script>
