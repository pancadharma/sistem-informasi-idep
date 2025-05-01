<!-- JS for Modal Program -->
<script>
    $(document).ready(function() {
        let programId = null;

        $('#list_program_kegiatan tbody').on('click', '.select-program', function(e) {
            e.preventDefault();
            programId = $(this).data('program-id');
            const programKode = $(this).data('program-kode');
            const programNama = $(this).data('program-nama');

            // Update the hidden input and display fields
            $('#program_id').val(programId).trigger('change');
            $('#kode_program').val(programKode);
            $('#nama_program').val(programNama).prop('disabled', true);

            $('#programoutcomeoutputactivity_id, #kode_kegiatan').val('').trigger('change');
            $('#kode_kegiatan').val('').trigger('change');
            $('#nama_kegiatan').val('').trigger('change');

            // Blur the currently focused element
            $('#nama_kegiatan').focus();
            setTimeout(function() {
                $('#ModalDaftarProgram').modal('hide');
            }, 200);


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
            const tbody = $('#list_program_out_activity tbody');
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
                const row = `<tr><td colspan="6" class="dt-empty text-center">{{ __('global.no_results') }}</td></tr>`;
                tbody.append(row);
            }
            $('#ModalDaftarProgramActivity').modal('show');
        }
    });
</script>