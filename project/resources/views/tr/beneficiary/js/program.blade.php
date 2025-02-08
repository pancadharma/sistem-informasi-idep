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
                            processing: "<i class='fa fa-spinner fa-spin'></i> Memuat..."
                        },
                        lengthMenu: [5, 10, 25, 50, 100],
                        bDestroy: true // Important to re-initialize datatable
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

        // Initialize select2 for activity selection
        $("#activitySelect").select2({
            placeholder: "Select Activities",
            width: "100%",
            dropdownParent: $("#ModalTambahPeserta"), // Adjust according to your modal ID
        })

        $(document).on('click', '.select-program', function() {
            const id = $(this).data('id');
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            const url = "{{ route('api.program.activity', ':id') }}".replace(':id', id);

            $('#program_id').val(id);
            $('#kode_program').val(kode);
            $('#nama_program').val(nama).prop('disabled', true);

            // Fetch activities based on the selected program
            // fetch(`/api/getActivityProgram/${id}`)
            fetch(url)
                .then(response => response.json())
                .then(activities => {
                    populateActivitySelect(activities);
                    updateActivityHeaders(activities);
                })
                .catch(error => console.error('Error fetching activities:', error));

            $('#ModalDaftarProgram').modal('hide');
        });

        function populateActivitySelect(activities) {
            const activitySelect = $('#activitySelect');
            activitySelect.empty(); // Clear existing options

            activities.forEach(activity => {
                const option = new Option(activity.kode, activity.id, false, false);
                activitySelect.append(option).trigger('change');
            });
        }

        function updateActivityHeaders(activities) {
            if (activities.length > 0) {
                const activityHeaders = activities.map(activity => `
                    <th class="align-middle text-center activity-header" data-activity-id="${activity.id}">${activity.kode}</th>
                `).join('');

                $('#activityHeaders').html(`
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }} <sup><i class="fas fa-question-circle"  title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                    <th class="align-middle text-center">0-17</th>
                    <th class="align-middle text-center">18-24</th>
                    <th class="align-middle text-center">25-59</th>
                    <th class="align-middle text-center"> > 60 </th>
                    ${activityHeaders}
                `);

                $('#headerActivityProgram').attr('rowspan', 1);
                $('#headerActivityProgram').attr('colspan', activities.length);

            } else {
                $('#activityHeaders').html(`
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }} <sup><i class="fas fa-question-circle"  title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                    <th class="align-middle text-center">0-17</th>
                    <th class="align-middle text-center">18-24</th>
                    <th class="align-middle text-center">25-59</th>
                    <th class="align-middle text-center"> > 60 </th>
                `);

                $('#headerActivityProgram').attr('rowspan', 2);
            }
        }

    });

</script>
