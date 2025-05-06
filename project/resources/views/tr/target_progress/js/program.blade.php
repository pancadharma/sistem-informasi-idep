<script>
    $doc.ready(function() {
        // Initialize Functions & Variables
        let targetProgressDataTable = undefined,
            programsDataTable       = undefined,
            $targetProgressTable    = $('#target_progress_table'),
            $programsTable          = $('#programs_table'),
            $kodeProgram            = $('#kode_program'),
            $namaProgram            = $('#nama_program'),
            $idProgram              = $('#program_id'),
            $modal                  = $('#ModalDaftarProgram'),
            showConfirmationModal   = function() {
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
                        setTimeout(() => {
                            $namaProgram.val("");
                            $idProgram.val("");
                            $kodeProgram.val("").trigger("click.selectProgram");
                            $targetProgressTable.trigger("ajaxLoad:DataTable");
                        }, 500);
                    }
                });
            };

        // Chose Program to Select
        $kodeProgram.on('click.selectProgram', function(e) {
            e.preventDefault();

            if($(this).val()) {
                showConfirmationModal();
                return e.preventDefault();
            }

            setTimeout(() => {
                if (!programsDataTable) {
                    programsDataTable = $programsTable.DataTable({
                        processing: true,
                        serverSide: true,
                        width: '100%',
                        ajax: {
                            url: "{{ route('api.target_progress.programs') }}",
                            type: "GET"
                        },
                        columns: [
                            { data: 'kode', name: 'kode', className: "align-self text-left", width: "20%", },
                            { data: 'nama', name: 'nama', className: "align-self text-left", width: "50%" },
                            { data: 'target', name: 'target', className: "align-self text-left", width: "20%" },
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

            $modal.removeAttr('inert');
            $modal.modal('show');
        });

        $modal.on('hidden.bs.modal', function (e) {
            if (programsDataTable) {
                programsDataTable.destroy();
                programsDataTable = undefined;
                $(this).find("#programs_table tbody tr").remove();
            }
            $(this).attr('inert', '');
        });

        // Select Program
        $doc.on('click', '.select-program', function(event) {
            const id    = $(this).data('id');
            const kode  = $(this).data('kode');
            const nama  = $(this).data('nama');
            const url   = "{{ route('api.target_progress.targets', ':id') }}".replace(':id', id);

            $idProgram.val(id);
            $kodeProgram.val(kode);
            $namaProgram.val(nama).prop('disabled', true);

            $targetProgressTable.trigger("ajaxLoad:DataTable");

		// Tanggal
		let $fieldTanggal	= $('#target_progress_tanggal'),
			minDate			= $fieldTanggal.val() || null;

		$('#target_progress_tanggal').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			autoApply: true,
			minDate: minDate,
			format: "DD/MM/YYYY",
			locale: @json(__('daterangepicker.locale')),
		});
	});
</script>
