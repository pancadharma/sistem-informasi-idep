<!-- JS for Modal Program -->
<script>
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
                clearStoredFormData();
                setTimeout(() => {
                    $('#kode_program').click();
                }, 500);
            }
        });
    }

    let programId = null;

    $(document).ready(function() {
        let programTable;

        $('#kode_program').on('click', function(e) {
            e.preventDefault();
            if ($(this).val()) {
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
                                url: "{{ route('api.data.program.kegiatan') }}",
                                type: "GET"
                            },
                            columns: [{
                                    data: 'kode',
                                    name: 'kode',
                                    className: "align-self text-left",
                                    width: "20%",
                                },
                                {
                                    data: 'nama',
                                    name: 'nama',
                                    className: "align-self text-left",
                                    width: "50%"
                                },
                                {
                                    data: 'activities',
                                    name: 'activities',
                                    className: "align-self text-left",
                                    width: "30%"
                                },
                                {
                                    data: 'action',
                                    name: 'action',
                                    width: "10%",
                                    className: "align-self text-center",
                                    orderable: false,
                                    searchable: false
                                }
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


        $('#list_program_out_activity tbody').on('click', '.select-activity', function(e) {
            e.preventDefault();
            var activity_Id = $(this).closest('tr').data('id');
            var activityKode = $(this).closest('tr').data('kode');
            var activityNama = $(this).closest('tr').data('nama');

            $('#programoutcomeoutputactivity_id').val(activity_Id).trigger('change');
            $('#kode_kegiatan').val(activityKode);
            $('#nama_kegiatan').val(activityNama).prop('disabled', true);
            $('#nama_kegiatan').focus();
            setTimeout(function() {
                $('#ModalDaftarProgramActivity').modal('hide');
            }, 200);

        });

        $('#kode_kegiatan').click(function(e) {
            e.preventDefault();
            let programId = $('#program_id').val();
            if (!programId) {
                e.preventDefault();
                Toast.fire({
                    icon: "warning",
                    title: "Opssss...",
                    text: "Please select a program first.",
                    timer: 2000,
                    position: "top-end",
                    timerProgressBar: true,
                });

                $('#kode_program').click();
                $('#ModalDaftarProgram').modal('show');
                return false;

            } else {
                fetchProgramActivities(programId);
            }
        });

        function fetchProgramActivities(programId) {
            const url = '{{ route('api.program.kegiatan', ':id') }}'.replace(':id', programId);

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "{{ __('cruds.activity.search') }}...",
                        timer: 2000,
                        position: "top-end",
                        timerProgressBar: true,
                    });
                },
                success: function(data) {
                    setTimeout(() => {
                        populateModalWithActivities(data);
                    }, 500);
                },
                error: function() {
                    Toast.fire({
                        icon: "error",
                        title: "Failed to fetch activities.",
                    });
                }
            });
        }

        function populateModalWithActivities(data) {
            const table_activity = $('#list_program_out_activity');

            // Destroy existing DataTable instance if any
            if ($.fn.DataTable.isDataTable(table_activity)) {
                table_activity.DataTable().clear().destroy();
            }

            const tbody = table_activity.find('tbody');
            tbody.empty();

            if (data.length > 0) {
                data.forEach(activity => {
                    const row = `
                <tr data-id="${activity.id}" data-kode="${activity.kode}" data-nama="${activity.nama}" data-deskripsi="${activity.deskripsi}" data-indikator="${activity.indikator}" data-target="${activity.target}">
                    <td>${activity.kode}</td>
                    <td>${activity.nama}</td>
                    <td>${activity.deskripsi}</td>
                    <td>${activity.indikator}</td>
                    <td>${activity.target}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-info select-activity" data-id="${activity.id}">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </td>
                </tr>
            `;
                    tbody.append(row);
                });
            } else {
                const row =
                    `<tr><td colspan="6" class="dt-empty text-center">{{ __('global.no_results') }}</td></tr>`;
                tbody.append(row);
            }

            // Re-initialize DataTable
            table_activity.DataTable({
                destroy: true,
                responsive: true,
                searching: true,
                lengthMenu: [5, 10, 25],
                language: {
                    processing: " Memuat...",
                    emptyTable: "Tidak ada data kegiatan"
                }
            });

            $('#ModalDaftarProgramActivity').modal('show');
        }

        $('#ModalDaftarProgram').on('hidden.bs.modal', function(e) {
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
            const url = "{{ route('api.program.kegiatan', ':id') }}".replace(':id', id);

            $('#program_id').val(id);
            $('#kode_program').val(kode);
            $('#nama_program').val(nama).prop('disabled', true);
            $('#ModalDaftarProgram').modal('hide');
        });

    });
</script>
