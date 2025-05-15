<script>
	$doc.ready(function() {
		// Initialize Functions & Variables
		let $modal				= $('#ModalHistoryTargetProgress'),
			$history_table		= $modal.find("#history_table"),
			historiesDataTable	= undefined;

		let $targetProgressHistoryModal	= $('#target_progress_history_modal');

		$modal.on('shown.bs.modal', function (e) {
			let program_id = $modal.data("program-id") || $('#program_id').val() || 0;

			historiesDataTable = $history_table.DataTable({
				processing: true,
				serverSide: true,
				autoWidth: false,
				fixedColumns: {
					leftColumns: 1,
					rightColumns: 1,
				},
				rowGroup: {
					dataSrc: 'tanggal',
				},
				ajax: {
					url: "{{ route('api.target_progress.histories', ':id') }}".replace(':id', program_id),
					type: "GET",
				},
				columnDefs: [
					{
						targets: [0, 1],
						className: 'no-colvis dt-control',
						visible: false,
					},
					{
						targets: 2,
						width: '1%',
						className: 'px-2 pl-3 text-nowrap',
					},
					{
						targets: [3,4,5,6],
						className: "align-self text-left",
					},
					{
						targets: 7,
						className: 'no-colvis',
						searchable: false,
						visible: false,
					},
				],
				columns: [
					{ data: 'raw_tanggal',	name: 'raw_tanggal' },
					{ data: 'tanggal',		name: 'tanggal' },
					{ data: 'waktu',		name: 'waktu' },
					{ data: 'kode',			name: 'kode' },
					{ data: 'nama',			name: 'nama' },
					{ data: 'target',		name: 'target' },
					{ data: 'action',		name: 'action', width: "10%", className: "align-self text-center", orderable: false, searchable: false },
					{ data: 'id',			name: 'id' },
				],
				createdRow: function(row, data, rowIndex) {
					let table				= this.get(0),
						$row				= $(row),
						$showBtn			= $row.find(".show-target-progress-history"),
						target_progress_id	= data.id;

					if(data.recent_history) $row.addClass("bg-25-success");

					$showBtn.off("click.showTargetProgressHistoryModal")
						.on("click.showTargetProgressHistoryModal", function(event){
							$targetProgressHistoryModal.modal("show");
							$('#target_progress_history_table').trigger("init:DataTable", target_progress_id);
						});
				},
				language: {
					processing: " Memuat..."
				},
				lengthMenu: [5, 10, 25, 50, 100],
				bDestroy: true, // Important to re-initialize datatable
				order: [[0, 'desc']],
			});
			$modal.removeAttr('inert');
		});

		$modal.on('hidden.bs.modal', function (e) {
			if (historiesDataTable) {
				historiesDataTable.destroy();
				historiesDataTable = undefined;
				$(this).find("#history_table tbody tr").remove();
			}
			$(this).attr('inert', '');
		});
	});
</script>
