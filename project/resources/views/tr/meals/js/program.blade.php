{{-- // Script to Load DataTables Program into the Modal Page --}}

<script>
    $(document).ready(function() {
        let programTable;

        $('#kode_program').on('click', function() {
            setTimeout(() => {
                if (!programTable) {
                    programTable = $('#list_program_kegiatan').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('api.meals.program') }}",
                            type: "GET"
                        },
                        columns: [
                            { data: 'kode', name: 'kode', className: "align-self text-left", width: "20%", },
                            { data: 'nama', name: 'nama', className: "align-self text-left", width: "70%" },
                            { data: 'action', name: 'action', width: "10%", className: "align-self text-center", orderable: false, searchable: false }
                        ],
                        responsive: true,
                        language: {
                            processing: "<i class='fa fa-spinner fa-spin'></i> Memuat..."
                        },
                        lengthMenu: [5, 10, 25, 50, 100],
                        bDestroy: true //Important to re-initialize datatable


                    });
                }
            }, 500);
            $('#ModalDaftarProgram').removeAttr('inert');
            $('#ModalDaftarProgram').modal('show');
        });

        $('#ModalDaftarProgram').on('hidden.bs.modal', function (e) {
            if (programTable) {
                programTable.destroy();
                programTable = null;
            }
            $(this).attr('inert', '');
        });

        $(document).on('click', '.select-program', function() {
            const id = $(this).data('id');
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');

            $('#program_id').val(id);
            $('#kode_program').val(kode);
            $('#nama_program').val(nama).prop('disabled', true);

            $('#ModalDaftarProgram').modal('hide');
        });
    });

</script>
