<script>
	$doc.ready(function () {
		$('#target_progress_table').DataTable({
			serverSide: true,
			processing: true,
			dom:
				"<'row mt-2 justify-content-between'" +
				"<'col-md-auto me-auto'B>" +                // Buttons left
				"<'col-md-auto ms-auto'f>" +                // Search right
				">" +
				"<'row mt-2 justify-content-md-center'" +
				"<'col-12 table-responsive't>" +            // Table
				">" +
				"<'row mt-2 justify-content-between'" +
				"<'col-md-auto me-auto'l>" +                // Length
				"<'col-md-auto ms-auto'p>" +                // Paging
				">",
			buttons: [
				{
					text: '<i class="fas fa-print"></i> <span class="d-none d-md-inline"></span>',
					className: 'btn btn-secondary',
					extend: 'print',
					exportOptions: {
						columns: [0, 1, 2, 3] // Ensure these indices match your visible columns
					}
				},
				{
					text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline"></span>',
					className: 'btn btn-success',
					extend: 'excel',
					exportOptions: {
						columns: [0, 1, 2, 3]
					}
				},
				{
					text: '<i class="fas fa-file-pdf"></i> <span class="d-none d-md-inline"></span>',
					className: 'btn btn-danger',
					extend: 'pdf',
					exportOptions: {
						columns: [0, 1, 2, 3]
					}
				},
				{
					extend: 'copy',
					text: '<i class="fas fa-copy"></i> <span class="d-none d-md-inline"></span>',
					className: 'btn btn-info',
					exportOptions: {
						columns: [0, 1, 2, 3]
					}
				},
				{
					extend: 'colvis',
					text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline"></span>',
					className: 'btn btn-warning',
					exportOptions: {
						columns: [0, 1, 2, 3]
					}
				},
			],
			ajax: {
				url: "{{ route('api.target_progress.target_progresses') }}",
				type: 'GET',
			},
			columnDefs: [
				{
					targets: 1,
					visible: false,
					searchable: false,
				},
				{
					targets: 3,
					width: "300px",
				},
				{
					targets: [2, 4],
					className: "text-nowrap",
				},
			],
			columns: [
				{
					data: 'DT_RowIndex',
					width: "5%",
					name: 'No.',
					className: "text-center",
					title: '{{ __('No.') }}',
					orderable: false,
					searchable: true,
				},
				{data: 'program_id',	name: 'program_id',			title: 'program_id'},
				{data: 'kode_program',	name: 'kode_program',		title: '{{ __('cruds.target_progress.kode_program') }}'},
				{data: 'nama_program',	name: 'nama_program',		title: '{{ __('cruds.target_progress.nama_program') }}'},
				{data: 'tanggal',		name: 'tanggal',			title: '{{ __('cruds.target_progress.tanggal') }}'},
				{data: 'updated_count',	name: 'updated_count',		title: '{{ __('cruds.target_progress.updated_count') }}'},
				{data: 'action',		name: 'action',				title: '{{ __('global.action') }}', orderable: false, searchable: false, className: 'text-center'},
			],
			createdRow: function(row, data, rowIndex) {
				let table		= this.get(0),
				$row			= $(row),
				$checkHistory	= $row.find(".target-progress-history");
				
				$checkHistory.off("click.showHistoryModal").on("click.showHistoryModal", function(event){
					$('#ModalHistoryTargetProgress').data("program-id", data.program_id).modal("show");
				});
			},
			pageLength: 10,
			order: [1, 'asc'],
			lengthMenu: [10,25,50,100],
			
		});
	});
</script>
