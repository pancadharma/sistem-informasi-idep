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

        $("#activitySelect").select2({
            width: "100%",
            dropdownParent: $("#ModalTambahPeserta"), // Adjust according to your modal ID
        });

        $("#activitySelectEdit").select2({
            width: "100%",
            dropdownParent: $("#editDataModal"),
        });

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

        // function updateActivityHeaders(activities) {
        //     if (activities.length > 0) {
        //         const activityHeaders = activities.map(activity => `
        //             <th class="align-middle text-center activity-header" data-activity-id="${activity.id}">${activity.kode}</th>
        //         `).join('');
        //         $('#activityHeaders').html(`
        //             <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
        //             <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }} <sup><i class="fas fa-question-circle"  title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
        //             <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }}</th>
        //             <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
        //             <th class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
        //             <th class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
        //             <th class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
        //             <th class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
        //             ${activityHeaders}
        //         `);

        //         $('#headerActivityProgram').attr('rowspan', 1);
        //         $('#headerActivityProgram').attr('colspan', activities.length);

        //     } else {
        //         $('#activityHeaders').html(`
        //             <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
        //             <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }} <sup><i class="fas fa-question-circle"  title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
        //             <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }}</th>
        //             <th class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
        //             <th class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
        //             <th class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
        //             <th class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
        //             <th class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
        //         `);

        //         $('#headerActivityProgram').attr('rowspan', 2);
        //     }
        // }

    });

</script>
