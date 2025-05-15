<script>
	let params	= @json($params ?? []); 

	const modalTemplate = $(`
			<div class="modal fade" id="modal-template" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog modal-lg modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">
								<label>Achievement</label>
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
								<span>&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<textarea rows="10" class="form-control w-100"></textarea>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-sm btn-secondary discardChange">{{ __('global.cancel') }} <i class="bi bi-box-arrow-left"></i></button>
							<button type="button" data-dismiss="modal" class="btn btn-sm btn-success saveChange" title="Ctrl + Enter">{{ __('global.save') }} <i class="bi bi-save"></i></button>
						</div>
					</div>
				</div>
			</div>
		`);

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
			$targetProgressTable	= $('#target_progress_table'),
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
		$targetProgressTable.on("init:DataTable", function(){
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
					url: "{{ route('api.target_progress.targets') }}",
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
									hiddenInput = $(`<input type='hidden' name='${name}'>`),
									param_value = params?.target_progress?.details?.[index]?.[attribute],
									error = messages[`target_progress.details.${index}.${attribute}`];

								if(!!error) hiddenInput.addClass("is-invalid");
								$field.val(param_value || $field.val());
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
						name: "checkHistory",
						text: '<i class="fas fa-history"></i> <span class="d-none d-md-inline"></span>',
						className: 'btn btn-secondary',
						attr: {
							id: 'checkHistory',
							title: 'Click to check history',
						},
						action: function (e, dt, node, config) {
							$('#ModalHistoryTargetProgress').modal("show");
						},
					},
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
						className: 'bg-25-warning text-nowrap',
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
						className: 'bg-25-warning mw-300-px',
					},
					{
						targets: Array.from({ length: 5 }, (_, i) => i),
						className: 'bg-25-warning'
					},
					{
						targets: Array.from({ length: 8 }, (_, i) => i+5),
						orderDataType: "fields-order",
						type: 'fields-order',
						className: 'bg-25-success text-nowrap'
					},
					{
						targets: [8, 11],
						className: 'bg-25-success text-nowrap mw-250-px',
					},
					{
						targets: [12],
						className: 'bg-25-success mw-200-px'
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
						className: "bg-25-warning text-center",
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
						]),
						textsAreas	= function(){
							return jQuery()
									.add(fields.achievements)
									.add(fields.challenges)
									.add(fields.mitigation)
									.add(fields.notes);
						};

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
						if (hiddenInput.is(".is-invalid")) {
							$this.addClass(hiddenInput.prop("class"));
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

					// Text Area Modals
					textsAreas().each(function() {
						let $realInput		= $(this),
							$modal			= modalTemplate.clone(),
							$modalInput		= $modal.find("textarea"),
							$titleLabel		= $modal.find(".modal-title label"),
							cellIndex		= $realInput.closest("td").index();


						$realInput.off("dynamicRows focus.dynamicRowEvents")
						.on("focus.dynamicRowEvents", function(event) {
							let headerText		= $realInput.closest("table").find("thead th .dt-column-title").get(cellIndex).innerText,
								unixTimestamp	= new Date().getTime(),
								input_id		= `input-${unixTimestamp}`,
								cancelBtn		= $modal.find(".discardChange"),
								saveBtn			= $modal.find(".saveChange"),
								discardChange	= false;

							$realInput.blur();
							$modal.prop("id", unixTimestamp);
							$modalInput.prop("id", input_id);
							$titleLabel.prop("for", input_id);
							$titleLabel.text(headerText);

							cancelBtn.on('click.toCancel', function (e) {
								discardChange = true;
							});
							$modal.on("show.bs.modal", function(event) {
								$modalInput.val($realInput.val());
							});
							$modal.on("shown.bs.modal", function(event) {
								$modalInput.focus();
							});
							$modal.on("hide.bs.modal", function(event) {
								if(discardChange){
									if($realInput.val() !== $modalInput.val()){
										event.preventDefault();
										Swal.fire({
											text: 'Do you want to discard your changes?',
											icon: 'warning',
											showCancelButton: true,
											cancelButtonColor: 'var(--secondary)',
											confirmButtonColor: 'var(--danger)',
											cancelButtonText: 'Back',
											confirmButtonText: 'Discard',
										}).then((result) => {
											if (result.isConfirmed) {
												$modalInput.val($realInput.val());
												$modal.modal("hide");
											}else{
												discardChange = false;
											}
										});
									}else{
										discardChange = false;
									}
								}else{
									$realInput.val($modalInput.val())
										.trigger("change.setHiddenFields")
										.trigger("dynamicRows");
								}
							});
							$modal.on("hidden.bs.modal", function(event) {
								$modal.remove();
							});

							$modalInput.on("focus.enterToSave", function(event) {
								function handleKeyDown(event) {
									if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
										event.preventDefault(); // Optional: prevent newline
										console.log('Ctrl + Enter pressed!');
										$modal.modal("hide");
									}
								}
								this.addEventListener('keydown', handleKeyDown);
								this.addEventListener('blur', () => {
									this.removeEventListener('keydown', handleKeyDown);
								}, { once: true });
							});

							$modal.modal("show");
						})
						.on("dynamicRows", function(event){
							$(this).prop("rows", (($(this).val().match(/\n/g) || []).length + 1));
						}).trigger("dynamicRows");
					});

					// Auto calculation `Progress` & `% to complete`
					fields.progress.on("change.dynamicRowEvents", function(){
						let $this					= $(this),
							$persentase_complete	= fields.persentase_complete,
							progress				= $this.val(),
							persentase_complete		= $persentase_complete.val();
							
						if(progress > 100){
							progress = 100;
						}else if(progress < 0){
							progress = 0;
						}
						persentase_complete = 100 - progress;

						$this.val(progress).trigger("change.setHiddenFields");
						$persentase_complete.val(persentase_complete).trigger("change.setHiddenFields");
					});
					fields.persentase_complete.on("change.dynamicRowEvents", function(){
						let $this				= $(this),
							$progress			= fields.progress,
							persentase_complete	= $this.val(),
							progress			= $progress.val();
							
						if(persentase_complete > 100){
							persentase_complete = 100;
						}else if(persentase_complete < 0){
							persentase_complete = 0;
						}
						progress = 100 - persentase_complete;

						$this.val(persentase_complete).trigger("change.setHiddenFields");
						$progress.val(progress).trigger("change.setHiddenFields");
					});
					
					// SETUP SELECT2 - untuk status & risk input
					fields.status.select2({
						placeholder: "{{__('enums.target_progress_status.placeholder')}}",
						ajax: {
							url: "{{ route('api.target_progress.status_options') }}",
							processResults: function (data) {
								return {
									results: data,
								};
							},
						},
    					templateResult: function(el) {
							return `<span class="target-progress-status opt-${el.id}">${el.text}</span>`;
						},
    					templateSelection: function(el) {
							return `<span class="target-progress-status selected opt-${el.id}">${el.text}</span>`;
						},
    					escapeMarkup: function(markup) {
							return markup;
						},
						dropdownAutoWidth: true,
						width: '100%',
					});
					fields.risk.select2({
						placeholder: "{{__('enums.target_progress_risk.placeholder')}}",
						ajax: {
							url: "{{ route('api.target_progress.risk_options') }}",
							processResults: function (data) {
								return {
									results: data,
								};
							},
						},
    					templateResult: function(el) {
							return `<span class="target-progress-risk opt-${el.id}">${el.text}</span>`;
						},
    					templateSelection: function(el) {
							return `<span class="target-progress-risk selected opt-${el.id}">${el.text}</span>`;
						},
    					escapeMarkup: function(markup) {
							return markup;
						},
						dropdownAutoWidth: true,
						width: '100%',
					});
				},
			});

			// Hindari kolom berantakan karena `scrollX: true`
			setTimeout(() => {
				targetDataTable.columns.adjust().draw();
			}, 1000);
		});
		// END Initialization - Init DataTable by Event

		// Trigger Target Progress Data Table Initialization
		$targetProgressTable.trigger("init:DataTable");
	});
</script>
