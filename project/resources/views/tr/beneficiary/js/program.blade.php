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
            placeholder: "HAH Activities...",
            width: "100%",
            dropdownParent: $("#ModalTambahPeserta"), // Adjust according to your modal ID
        });

        // Initialize select2 for edit modal
        $("#activitySelectEdit").select2({
            placeholder: "Edit Activities",
            width: "100%",
            dropdownParent: $("#editDataModal"),
        });

        // $(document).on('click', '.select-program', function() {
        //     const id = $(this).data('id');
        //     const kode = $(this).data('kode');
        //     const nama = $(this).data('nama');
        //     const url = "{{ route('api.program.activity', ':id') }}".replace(':id', id);

        //     $('#program_id').val(id);
        //     $('#kode_program').val(kode);
        //     $('#nama_program').val(nama).prop('disabled', true);

        //     // Fetch activities based on the selected program
        //     // fetch(`/api/getActivityProgram/${id}`)
        //     fetch(url)
        //         .then(response => response.json())
        //         .then(activities => {
        //             populateActivitySelect(activities, $("#activitySelect"));
        //             populateActivitySelect(activities, $("#activitySelectEdit"));
        //             updateActivityHeaders(activities);
        //         })
        //         .catch(error => console.error('Error fetching activities:', error));

        //     $('#ModalDaftarProgram').modal('hide');
        // });


        // function populateActivitySelect(activities, selectElement) {
            
        //     activities.forEach(activity => {
        //         const option = new Option(activity.kode, activity.id, false, false);
        //         selectElement.empty(); // Clear existing options
        //         selectElement.append('<option value="">Pilih Aja Dulu</option>');

        //         $.each(activity, function() {
        //             selectElement.select2();
        //             let dataoptions = '<option value="' + activity.id + '" data-id="' + activity.id + '" title="'+ activity.nama+'">' + activity.kode + '</option>';
        //             console.log("coba aja ini", dataoptions);
        //             selectElement.append(dataoptions).trigger('change');
        //         });
        //     });
        // }

        $(document).on('click', '.select-program', function() {
            const id = $(this).data('id');
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            const url = "{{ route('api.program.activity', ':id') }}".replace(':id', id);

            $('#program_id').val(id);
            $('#kode_program').val(kode);
            $('#nama_program').val(nama).prop('disabled', true);

            // Fetch activities based on the selected program
            fetch(url)
                .then(response => response.json())
                .then(activities => {
                    populateActivitySelect(activities, $("#activitySelect"));
                    populateActivitySelect(activities, $("#activitySelectEdit"));
                    updateActivityHeaders(activities);
                })
                .catch(error => console.error('Error fetching activities:', error));

            $('#ModalDaftarProgram').modal('hide');
        });

        function populateActivitySelect(activities, selectElement) {
            // Clear existing options before adding new ones
            selectElement.empty().append('Pilih Aja Dulu');

            activities.forEach(activity => {
                // Create and append option for each activity
                const option = new Option(activity.kode, activity.id, false, false);
                option.setAttribute('title', activity.nama); // Set title attribute for tooltip or additional info
                selectElement.append(option);
            });

            // Refresh Select2 after adding options
            selectElement.select2();
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
