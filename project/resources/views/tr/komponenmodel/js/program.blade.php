{{-- // Script to Load DataTables Program into the Modal Page --}}

<script>
    $(document).ready(function() {
        let programTable;

        function showConfirmationModal() {
            Swal.fire({
                title: 'Change Program Confirmation',
                text: 'Changing the program will clear all current entries. Do you want to proceed?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Clear All',
                cancelButtonText: 'No, Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    clearTableRows();
                    setTimeout(() => {
                        $('#kode_program').click();
                    }, 500);
                }
            });
        }

        function clearTableRows() {
            $("#dataTable tbody tr").remove();
            rowCount = 0; // Reset row count if needed for your application logic
        }

        $('#kode_program').on('click', function(e) {
            e.preventDefault();
            if ($("#dataTable tbody tr").length > 0) {
                showConfirmationModal();
                return false;
            } else {
                setTimeout(() => {
                    if (!programTable) {
                        programTable = $('#list_program_kegiatan').DataTable({
                            processing: true,
                            serverSide: true,
                            width: '100%',
                            ajax: {
                                url: "{{ route('api.beneficiary.program') }}",
                                type: "GET"
                            },
                            columns: [
                                { data: 'kode', name: 'kode', className: "align-self text-left", width: "20%", },
                                { data: 'nama', name: 'nama', className: "align-self text-left", width: "70%" },
                                { data: 'action', name: 'action', width: "10%", className: "align-self text-center", orderable: false, searchable: false }
                            ],
                            responsive: true,
                            language: {
                                processing: " Memuat..."
                            },
                            lengthMenu: [5, 10, 25, 50, 100],
                            bDestroy: true // Important to re-initialize datatable
                        });
                    }
                }, 500);
                $('#ModalDaftarProgram').removeAttr('inert');
                $('#ModalDaftarProgram').modal('show');
            }
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
            const url = "{{ route('api.program.activity', ':id') }}".replace(':id', id);

            $('#program_id').val(id);
            $('#kode_program').val(kode);
            $('#nama_program').val(nama).prop('disabled', true);

            $('#ModalDaftarProgram').modal('hide');
        });

    });

</script>
