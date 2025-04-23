<script>
	$(document).ready(function () {
		let targetDataTable = undefined,
			getInputField	= function($row, attribute) {
				return $row.find(`[name$='[${attribute}]'], [name$='[${attribute}][]']`).first();
			},
			inputFieldsMapping = function($row, attributes) {
				return attributes.reduce((acc, attribute) => {
					acc[attribute] = getInputField($row, attribute);
					return acc;
				}, {});
			};

		$('#target_progress_table').on("ajaxLoad:DataTable", function(){
			if (targetDataTable) targetDataTable.destroy();

			targetDataTable = $(this).DataTable({
				serverSide: true,
				processing: true,
				buttons: [],
				dom: (
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
					">"
				),
				ajax: "{{ route('api.target_progress.targets', ':id') }}".replace(':id', $('#program_id').val() || 0),
				columnDefs: [
					{
						targets: [1],
						className: 'bg-25-warning bg-opacity-50 text-nowrap'
					},
					{
						targets: [2, 3],
						render: function(data, type, row) {
							return data.replace(/\n/g, '<br>');
						}
					},
					{
						targets: [2, 3, 4],
						className: 'bg-25-warning bg-opacity-50 mw-300-px',
					},
					{
						targets: Array.from({ length: 5 }, (_, i) => i),
						className: 'bg-25-warning bg-opacity-50'
					},
					{
						targets: Array.from({ length: 9 }, (_, i) => i+5),
						className: 'bg-25-success bg-opacity-50 text-nowrap'
					},
					{
						targets: [13],
						className: 'bg-25-success bg-opacity-50 mw-200-px'
					},
				],
				columns: [
					{
						data: 'DT_RowIndex',
						width: "5%",
						name: 'No.',
						className: "bg-25-warning bg-opacity-50 text-center",
						title: '{{ __('No.') }}',
						orderable: false,
						searchable: true,
					},
					{data: 'level',                 name: 'level',                  title: '{{ __('cruds.target_progress.level') }}'},
					{data: 'deskripsi',             name: 'deskripsi',            	title: '{{ __('cruds.target_progress.deskripsi') }}'},
					{data: 'indikator',             name: 'indikator',              title: '{{ __('cruds.target_progress.indikator') }}'},
					{data: 'target',                name: 'target',                 title: '{{ __('cruds.target_progress.target') }}'},
					{data: 'achievements',          name: 'achievements',           title: '{{ __('cruds.target_progress.achievements') }}'},
					{data: 'progress',              name: 'progress',               title: '{{ __('cruds.target_progress.progress') }}'},
					{data: 'persentase_complete',		name: 'persentase_complete',		title: '{{ __('cruds.target_progress.persentase_complete') }}'},
					{data: 'status',                name: 'status',                 title: '{{ __('cruds.target_progress.status') }}'},
					{data: 'challenges',            name: 'challenges',             title: '{{ __('cruds.target_progress.challenges') }}'},
					{data: 'mitigation',						name: 'mitigation',							title: '{{ __('cruds.target_progress.mitigation') }}'},
					{data: 'risk',                  name: 'risk',                   title: '{{ __('cruds.target_progress.risk') }}'},
					{data: 'notes',                 name: 'notes',                  title: '{{ __('cruds.target_progress.notes') }}'},
					{data: 'tipe',                  name: 'tipe',                   title: '{{ __('cruds.target_progress.tipe') }}'},
				],
				initComplete: function(settings, json) {
					let table	= this.get(0),
						$table	= $(table),
						$form	= $table.closest("form"),
						emptyHiddenFields = $("<div class='data-table-hidden-fields-wrapper'>").hide();
							
					table.hiddenFields = emptyHiddenFields.clone();
					table.hiddenFields.appendTo($form);

					$table.off("destroy.dt")
					.on('destroy.dt', function(e, settings) {
						table.hiddenFields.remove();
					});
				},
				createdRow: function(row, data, dataIndex) {
					let table	= this.get(0),
						$row	= $(row),
						inputs	= inputFieldsMapping($row, [
							'achievements',
							'progress',
							'persentase_complete',
							'status',
							'challenges',
							'mitigation',
							'risk',
							'notes',
							'tipe',
						]);


					// SETUP STATUS
					let statusOptions = [
						{ value: null,				label: null },
						{ value: 'to_be_conducted', label: 'To be conducted' },
						{ value: 'completed',		label: 'Completed' },
						{ value: 'ongoing',			label: 'Ongoing' }
					];
					$.each(statusOptions, function(_, item) {
						inputs.status.append($('<option></option>').val(item.value).text(item.label));
					});

					// SETUP RISK
					let riskOptions = [
						{ value: null,		label: null },
						{ value: 'none',	label: 'None' },
						{ value: 'medium',	label: 'Medium' },
						{ value: 'high',	label: 'High' }
					];
					$.each(riskOptions, function(_, item) {
						inputs.risk.append($('<option></option>').val(item.value).text(item.label));
					});
					
					Object.values(inputs).forEach(function($input) {
						let $this		= $input,
							name		= $this.prop("name"),
							hiddenInput	= table.hiddenFields.find(`[name='${name}']`);

						if(hiddenInput.length){
							$this.val(hiddenInput.val());
						}

						$input.off("change.dynamicRowEvents")
						.on("change.setHiddenFields", function(event){
							if(!hiddenInput.length){
								hiddenInput = $(`<input type='hidden' name='${name}'>`).appendTo(table.hiddenFields);
							}

							hiddenInput.val($this.val());
						});
					});

					inputs.achievements.on("change.dynamicRowEvents", function(event) {
					});

					inputs.status.select2({
						placeholder: "Select Status",
						width: '100%',
						allowClear: true,
    					templateResult: function(el) {
							return `<span class="target-progress-status opt-${el.id}">${el.text}</span>`;
						},
    					templateSelection: function(el) {
							return `<span class="target-progress-status selected opt-${el.id}">${el.text}</span>`;
						},
    					escapeMarkup: function(markup) {
							return markup;
						},
					});
					inputs.risk.select2({
						placeholder: "Select Risk",
						width: '100%',
						allowClear: true,
    					templateResult: function(el) {
							return `<span class="target-progress-risk opt-${el.id}">${el.text}</span>`;
						},
    					templateSelection: function(el) {
							return `<span class="target-progress-risk selected opt-${el.id}">${el.text}</span>`;
						},
    					escapeMarkup: function(markup) {
							return markup;
						},
					});
				},
				pageLength: 10,
				lengthMenu: [10,25,50,100],
			});
		}).trigger("ajaxLoad:DataTable");
	});
</script>
