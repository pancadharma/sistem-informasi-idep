<script>
$(function () {

    const table = $('#mdivisi_list').DataTable({
    responsive: true,
    processing: true,
    serverSide: true,
    ajax: "{{ route('data.mdivisi') }}",
    order: [[1, 'asc']], // ⬅️ jangan kolom index
    lengthMenu: [10, 25, 50, 100, 500],

    columns: [
        {
            data: null,
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '5%',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        { data: 'nama', name: 'nama' },
        {
            data: 'aktif',
            name: 'aktif',
            className: 'text-center',
            render: function (data) {
                return data == 1
                    ? '<span class="badge badge-primary">Aktif</span>'
                    : '<span class="badge badge-danger">Nonaktif</span>';
            }
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '15%'
        }
    ]
});

    /* CREATE */
    $('#mdivisiForm').on('submit', function (e) {
        e.preventDefault();

        $.post("{{ route('mdivisi.store') }}", $(this).serialize(), () => {
            Swal.fire('Success', 'Divisi ditambahkan', 'success');
            this.reset();
            table.ajax.reload();
        });
    });

    /* EDIT OPEN */
    $('#mdivisi_list').on('click', '.edit-mdivisi-btn', function () {
        const id = $(this).data('mdivisi-id');

        $.get("{{ route('mdivisi.edit', ':id') }}".replace(':id', id), res => {
            $('#edit_id').val(res.id);
            $('#edit_nama').val(res.nama);
            $('#editaktif').prop('checked', res.aktif == 1);
            $('#edit-aktif').val(res.aktif);
            $('#editMdivisiModal').modal('show');
        });
    });

    $('#editaktif').on('change', function () {
        $('#edit-aktif').val(this.checked ? 1 : 0);
    });

    /* UPDATE */
    $('#editMdivisiForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#edit_id').val();

        $.ajax({
            url: "{{ route('mdivisi.update', ':id') }}".replace(':id', id),
            method: 'PUT',
            data: $(this).serialize(),
            success: () => {
                Swal.fire('Updated', 'Divisi diperbarui', 'success');
                $('#editMdivisiModal').modal('hide');
                table.ajax.reload();
            }
        });
    });

});
</script>