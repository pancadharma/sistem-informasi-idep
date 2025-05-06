<script>
	function isNumeric(value) {
		return !isNaN(parseFloat(value)) && isFinite(value);
	}

	$.fn.dataTable.ext.type.order['fields-order-asc'] = function(val1, val2) {
		let val1IsArray = Array.isArray(val1),
			val2IsArray = Array.isArray(val2);

		let [priority1, value1] = val1IsArray ? val1 : [Infinity, val1],
			[priority2, value2] = val2IsArray ? val2 : [Infinity, val2];

		if (priority1 !== priority2) return priority1 - priority2;

		if (value1 < value2) return -1;
		if (value1 > value2) return 1;
		return 0;
	};

	$.fn.dataTable.ext.type.order['fields-order-desc'] = function(val1, val2) {
		let val1IsArray = Array.isArray(val1),
			val2IsArray = Array.isArray(val2);

		let [priority1, value1] = val1IsArray ? val1 : [Infinity, val1],
			[priority2, value2] = val2IsArray ? val2 : [Infinity, val2];

		if (priority1 !== priority2) return priority1 - priority2;

		if (value1 > value2) return -1;
		if (value1 < value2) return 1;
		return 0;
	};

	$.fn.dataTable.ext.order['fields-order'] = function(settings, col) {
		let api			= new $.fn.dataTable.Api(settings),
			columnType	= settings.aoColumns[col].sType;

		return api.column(col, { order: 'index' }).nodes().map(function(td, i) {
			let val			= td.dataset.fieldsValue,
				isEmpty		= (val === undefined) || (val === null) || (String(val).trim() === ''),
				priority	= isEmpty ? 1 : 0;

			if (!isEmpty && isNumeric(val)) {
				let num = parseFloat(val);
				return [priority, isNaN(num) ? val : num];
			} else {
				return [priority, String(val || '').toLowerCase()];
			}
		});
	};

	$(document).ready(function () {
		let targetDataTable			= undefined,
			$targetProgressTable	= $('#target_progress_history_table'),
			getInputField			= function($row, attribute) {
				return $row.find(`[name$='[${attribute}]'], [name$='[${attribute}][]']`).first();
			},
			inputFieldsMapping		= function($row, attributes) {
				return attributes.reduce((acc, attribute) => {
					acc[attribute] = getInputField($row, attribute);
					return acc;
				}, {});
			};

		let collapsedGroups = {};

		// Initialization - Init DataTable by Event
		$targetProgressTable.on("init:DataTable", function(event, target_progress_id){
			if (targetDataTable) targetDataTable.destroy();

			$targetProgressTable.off("preInit.dt destroy.dt")
			.on("preInit.dt", function(e, settings){
				let table = this,
					emptyHiddenFields = $("<div class='data-table-hidden-fields-wrapper'>").hide();

				table.hiddenFields = emptyHiddenFields.clone();
			}).on('destroy.dt', function(e, settings) {
				let table = this;

				table.hiddenFields.remove();
				table.hiddenFields = undefined;
			});

			targetDataTable = $(this).DataTable({
				// serverSide: true,
				processing: true,
				scrollX: true,
				pageLength: 10,
				lengthMenu: [10,25,50,100],
				ajax: {
					url: "{{ route('api.target_progress.show_targets', ':id') }}".replace(':id', target_progress_id),
					type: "GET",
					data: function (params) {
						params.program_id	= $('#program_id').val();
						params.tanggal		= $("#target_progress_tanggal").val();
					},
					dataSrc: function (json) {
						let hiddenFields = $targetProgressTable.get(0).hiddenFields;

						json.data.forEach(function (row, index) {
							let $row = $(),
								hiddenFields = $targetProgressTable.get(0).hiddenFields;

							$.each(row, function(attribute, column) {
								try{
									$row = $row.add($(`<div>${column}</div>`));
								}catch(e){
								}
							});

							let fields = inputFieldsMapping($row, [
								'level',
								'tipe',
								'targetable_id',
								'targetable_type',
								'achievements',
								'progress',
								'persentase_complete',
								'status',
								'challenges',
								'mitigation',
								'risk',
								'notes',
							]);

							$.each(fields, function(attribute, $field) {
								let name = $field.prop("name"),
									hiddenInput = $(`<input type='hidden' name='${name}'>`);

								hiddenInput.val($field.val());
								hiddenInput.appendTo(hiddenFields);
							});
						});

						return json.data; // Must return an array of row data
					},
				},
				fixedHeader: {
					header: true,
					footer: false,
					headerOffset: $("body > .wrapper > nav").outerHeight(),
				},
				fixedColumns: {
					leftColumns: 2,
					rightColumns: 0
				},
				dom: (
					"<'row mt-2 justify-content-between'" +
					"<'left-buttons col-md-auto me-auto'B>" +   // Buttons left
					"<'right-buttons col-md-auto ms-auto'f>" +  // Search right (f for search box)
					">" +
					"<'row mt-2 justify-content-md-center'" +
					"<'col-12't>" +                             // Table
					">" +
					"<'row mt-2 justify-content-between'" +
					"<'col-md-auto me-auto'l>" +                // Length
					"<'col-md-auto ms-auto'p>" +                // Paging
					">"
				),
				buttons: [
					{
						extend: 'colvis',
						text: '<i class="fas fa-eye" aria-hidden="true"></i>',
						// popoverTitle: 'Select Column to Show',
						columns: ':not(.no-colvis)',
						className: 'btn btn-warning',  // Assign class to specify it goes to the left
					},
				],
				columnDefs: [
					{
						targets: [1],
						className: 'text-nowrap',
						render: function(data, type, row) {
							const indent = '&nbsp;'.repeat(row.indent * 4);
							return `${indent}${data}`;
						},
					},
					{
						targets: [2, 3],
						render: function(data, type, row) {
							return data.replace(/\n/g, '<br>');
						}
					},
					{
						targets: [2, 3, 4],
						className: 'mw-300-px',
					},
					{
						targets: Array.from({ length: 5 }, (_, i) => i),
						className: ''
					},
					{
						targets: Array.from({ length: 8 }, (_, i) => i+5),
						orderDataType: "fields-order",
						type: 'fields-order',
						className: 'text-nowrap'
					},
					{
						targets: [8, 11],
						className: 'text-nowrap mw-250-px',
					},
					{
						targets: [12],
						className: 'mw-200-px'
					},
					{
						targets: Array.from({ length: 2 }, (_, i) => i+13),
						className: 'no-colvis',
						visible: false,
						searchable: false,
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
					{data: 'level',					name: 'level',					title: '{{ __('cruds.target_progress.level') }}'},
					{data: 'deskripsi',				name: 'deskripsi',				title: '{{ __('cruds.target_progress.deskripsi') }}'},
					{data: 'indikator',				name: 'indikator',				title: '{{ __('cruds.target_progress.indikator') }}'},
					{data: 'target',				name: 'target',					title: '{{ __('cruds.target_progress.target') }}'},
					// Fields
					{data: 'achievements',			name: 'achievements',			title: '{{ __('cruds.target_progress.achievements') }}'},
					{data: 'progress',				name: 'progress',				title: '{{ __('cruds.target_progress.progress') }}'},
					{data: 'persentase_complete',	name: 'persentase_complete',	title: '{{ __('cruds.target_progress.persentase_complete') }}'},
					{data: 'status',				name: 'status',					title: '{{ __('cruds.target_progress.status') }}'},
					{data: 'challenges',			name: 'challenges',				title: '{{ __('cruds.target_progress.challenges') }}'},
					{data: 'mitigation',			name: 'mitigation',				title: '{{ __('cruds.target_progress.mitigation') }}'},
					{data: 'risk',					name: 'risk',					title: '{{ __('cruds.target_progress.risk') }}'},
					{data: 'notes',					name: 'notes',					title: '{{ __('cruds.target_progress.notes') }}'},
					// {data: 'tipe',					name: 'tipe',					title: '{{ __('cruds.target_progress.tipe') }}'},
					{data: 'target_id',				name: 'target_id',				title: 'target_id'},
					{data: 'parent_target_id',		name: 'parent_target_id',		title: 'parent_target_id'},
				],
				initComplete: function(settings, json) {
					let table	= this.get(0),
						$table	= $(table),
						$form	= $table.closest("form");

					if(json.data.length){
						targetDataTable.button('checkHistory:name').enable();
					}else{
						targetDataTable.button('checkHistory:name').disable();
					}

					table.hiddenFields.appendTo($form);
				},
				createdRow: function(row, data, rowIndex) {
					let table	= this.get(0),
						$row	= $(row),
						fields	= inputFieldsMapping($row, [
							'achievements',
							'progress',
							'persentase_complete',
							'status',
							'challenges',
							'mitigation',
							'risk',
							'notes',
						]);

					// Hidden fields shadowing - simpan value jika pindah pagination
					$.each(fields, function(attribute, $field) {
						let $this		= $field,
							name		= $this.prop("name"),
							hiddenInput	= table.hiddenFields?.find(`[name='${name}']`),
							currentVal	= hiddenInput.val() || $this.val(),
							cellTd		= $field.closest("td"),
							cellIndex	= cellTd.index(),
							dtCell		= targetDataTable.cell(rowIndex, cellIndex);

						// Refresh input field
						cellTd.attr("data-fields-value", currentVal);
						fields[attribute] = $field = $this = cellTd.find(`[name='${name}']`);

						if(hiddenInput.length){
							$this.val(hiddenInput.val());
						}

						$field.off("change")
						.on("change.setHiddenFields", function(event){
							if(!hiddenInput.length){
								hiddenInput = $(`<input type='hidden' name='${name}'>`).appendTo(table.hiddenFields);
							}
							cellTd.attr("data-fields-value", $this.val());
							hiddenInput.val($this.val());
						});
					});
				},
			});

			// Hindari kolom berantakan karena `scrollX: true`
			setTimeout(() => {
				targetDataTable.columns.adjust().draw();
			}, 500);
		});
		// END Initialization - Init DataTable by Event
	});
</script>
