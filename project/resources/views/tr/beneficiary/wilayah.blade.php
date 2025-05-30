@extends('layouts.app')

@section('subtitle', __('cruds.beneficiary.list'))
@section('content_header_title')
    @can('meals_access')
        <a class="btn-success btn">Wilayah Dependency</a>
    @endcan
@endsection
@section('sub_breadcumb', __('Wilayah Dependency'))

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-graph-up"></i>
                Wilayah Dependency
            </h3>
            <div class="card-tools">
            </div>
        </div>

        <form id="dataForm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="ModalTambahPeserta">
                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <select class="form-control select2" id="provinsi" name="provinsi">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kabupaten">Kabupaten</label>
                            <select class="form-control select2" id="kabupaten" name="kabupaten">
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>
                            <select class="form-control select2" id="kecamatan" name="kecamatan">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="desa">Desa</label>
                            <select class="form-control select2" id="desa" name="desa">
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dusun">Dusun</label>
                            <select class="form-control select2" id="dusun" name="dusun">
                                <option value="">Pilih Dusun</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button id="btnTambahWilayah" class="form-control btn btn-primary">Tambah Wilayah</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="custom-search-input" id="custom-search-input" placeholder="Search ..." class="form-control">
                        <span class="input-group-append">
                            <button type="button" class="btn btn-primary">Search</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="dataTable" class="table table-bordered table-striped" data-sortlist="[[0,0],[2,0]]">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Provinsi</th>
                                <th>Kabupaten</th>
                                <th>Kecamatan</th>
                                <th>Desa</th>
                                <th>Dusun</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- edit form --}}
        <form id="editDataForm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="editDataModal">
                        <input type="hidden" id="editRowId" name="editRowId">
                        <div class="form-group">
                            <label for="provinsi_id_edit">Update Provinsi</label>
                            <select class="form-control select2" id="provinsi_id_edit" name="provinsi_id_edit">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kabupaten_id_edit">Update Kabupaten</label>
                            <select class="form-control select2" id="kabupaten_id_edit" name="kabupaten_id_edit">
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kecamatan_id_edit">Update Kecamatan</label>
                            <select class="form-control select2" id="kecamatan_id_edit" name="kecamatan_id_edit">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="desa_id_edit">Update Desa</label>
                            <select class="form-control select2" id="desa_id_edit" name="desa_id_edit">
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dusun_id_edit">Update Dusun</label>
                            <select class="form-control select2" id="dusun_id_edit" name="dusun_id_edit">
                                <option value="">Pilih Dusun</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button id="btnUpdateWilayah" class="form-control btn btn-primary">Update Wilayah</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
@include('api.master.dynamic-table')
<script>
    $(document).ready(function() {
        $('.select2').select2();


        $('#dataTable').dynamicTableHandler({
            searchInputSelector: '#custom-search-input',
            noResultsMessage: 'No matching records found',
            excludeLastColumn: false,
            onNoResults: function() {
                console.log('No results found');
            }
        });

        let rowCount = 0;

        function addRow(data) {
            rowCount++;
            const provinsiText = $("#provinsi option:selected").text() || "-";
            const kabupatenText = $("#kabupaten option:selected").text() || "-";
            const kecamatanText = $("#kecamatan option:selected").text() || "-";
            const desaText = $("#desa option:selected").text() || "-";
            const dusunText = $("#dusun option:selected").text() || "-";

            const provinsiId = $("#provinsi").val() || 0;
            const kabupatenId = $("#kabupaten").val() || 0;
            const kecamatanId = $("#kecamatan").val() || 0;
            const desaId = $("#desa").val() || 0;
            const dusunId = $("#dusun").val() || 0;


            const newRow = `
            <tr data-row-id="${rowCount}">
                <td class="text-center align-middle" data-provinsi-id="${provinsiId}" data-kabupaten-id="${kabupatenId}" data-kecamatan-id="${kecamatanId}" data-desa-id="${desaId}" data-dusun-id="${dusunId}" data-provinsi-text="${provinsiText}" data-kabupaten-text="${kabupatenText}" data-kecamatan-text="${kecamatanText}" data-desa-text="${desaText}" data-dusun-text="${dusunText}" data-row-id="${rowCount}">${rowCount}</td>
                <td class="text-center align-middle" data-provinsi-text="${provinsiText}" data-provinsi-id="${provinsiId}">${provinsiText}</td>
                <td class="text-center align-middle" data-kabupaten-text="${kabupatenText}" data-kabupaten-id="${kabupatenId}">${kabupatenText}</td>
                <td class="text-center align-middle" data-kecamatan-text="${kecamatanText}" data-kecamatan-id="${kecamatanId}">${kecamatanText}</td>
                <td class="text-center align-middle" data-desa-text="${desaText}" data-desa-id="${desaId}">${desaText}</td>
                <td class="text-center align-middle" data-dusun-text="${dusunText}" data-dusun-id="${dusunId}">${dusunText}</td>
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
                </td>
            </tr>
            `;
            $("#tableBody").append(newRow);


        }


        function saveRow() {
            const form = $("#dataForm")[0];
            if (form.checkValidity()) {
                const formData = $("#dataForm").serializeArray().reduce((obj, item) => {
                    if (obj[item.name]) {
                        if (!Array.isArray(obj[item.name])) {
                            obj[item.name] = [obj[item.name]];
                        }
                        obj[item.name].push(item.value);
                    } else {
                        obj[item.name] = item.value;
                    }
                    return obj;
                }, {});

                addRow(formData);
                // resetFormAdd();
            } else {
                form.reportValidity();
            }
        }

        function editRow(row) {
            const currentRow = $(row).closest("tr");
            const rowId = currentRow.data("row-id");

            // Get the first cell which contains all the data attributes
            const firstCell = currentRow.find("td:first");

            // Get the data attributes from the first cell
            const provinsiId = firstCell.data("provinsi-id");
            const kabupatenId = firstCell.data("kabupaten-id");
            const kecamatanId = firstCell.data("kecamatan-id");
            const desaId = firstCell.data("desa-id");
            const dusunId = firstCell.data("dusun-id");

            // Get the text values from the respective cells
            const provinsiText = currentRow.find("td:eq(1)").text().trim();
            const kabupatenText = currentRow.find("td:eq(2)").text().trim();
            const kecamatanText = currentRow.find("td:eq(3)").text().trim();
            const desaText = currentRow.find("td:eq(4)").text().trim();
            const dusunText = currentRow.find("td:eq(5)").text().trim();

            // Store the row index for reference
            const rowIndex = currentRow.index();
            $("#editRowId").val(rowId);

            // For debugging
            console.log("Editing row with data:", {rowId,
                provinsiId, kabupatenId, kecamatanId, desaId, dusunId,
                provinsiText, kabupatenText, kecamatanText, desaText, dusunText
            });

            // Clear all dropdowns first
            $("#provinsi_id_edit").empty();
            $("#kabupaten_id_edit").empty();
            $("#kecamatan_id_edit").empty();
            $("#desa_id_edit").empty();
            $("#dusun_id_edit").empty();

            // Create a function to add an option and handle the change event
            function addOptionAndTriggerChange(selector, text, value) {
                return new Promise(resolve => {
                    // Create and add the option
                    const option = new Option(text, value, true, true);
                    $(selector).append(option).trigger('change');

                    // Wait a bit for the change event to be processed
                    setTimeout(resolve, 100);
                });
            }

            // Add options in sequence, waiting for each change event to complete
            addOptionAndTriggerChange("#provinsi_id_edit", provinsiText, provinsiId)
                .then(() => addOptionAndTriggerChange("#kabupaten_id_edit", kabupatenText, kabupatenId))
                .then(() => addOptionAndTriggerChange("#kecamatan_id_edit", kecamatanText, kecamatanId))
                .then(() => addOptionAndTriggerChange("#desa_id_edit", desaText, desaId))
                .then(() => addOptionAndTriggerChange("#dusun_id_edit", dusunText, dusunId))
                .catch(error => console.error("Error setting dropdown values:", error));

            // Show the edit form if it's hidden
            $("#editDataForm").show();
        }

        function updateRow() {
            const rowId = $("#editRowId").val();
            const form = $("#editDataForm")[0];

            const provinsiId = $("#provinsi_id_edit").val() || 0;
            const kabupatenId = $("#kabupaten_id_edit").val() || 0;
            const kecamatanId = $("#kecamatan_id_edit").val() || 0;
            const desaId = $("#desa_id_edit").val() || 0;
            const dusunId = $("#dusun_id_edit").val() || 0;

            const provinsiText = $("#provinsi_id_edit option:selected").text() || "-";
            const kabupatenText = $("#kabupaten_id_edit option:selected").text() || "-";
            const kecamatanText = $("#kecamatan_id_edit option:selected").text() || "-";
            const desaText = $("#desa_id_edit option:selected").text() || "-";
            const dusunText = $("#dusun_id_edit option:selected").text() || "-";

            if (!form) {
                console.error("Edit form not found");
                return;
            }

            if (form.checkValidity()) {
                const formData = $("#editDataForm").serializeArray();

                console.log("Updating row with data:", {
                    provinsiId, kabupatenId, kecamatanId, desaId, dusunId,
                    provinsiText, kabupatenText, kecamatanText, desaText, dusunText
                });

                const currentRow = $("#dataTable tbody").find(`tr[data-row-id="${rowId}"]`);
                if (currentRow.length === 0) {
                    console.error("Row not found");
                    return;
                }

                currentRow.find("td[data-provinsi-id]").attr("data-provinsi-id", provinsiId).attr("data-provinsi-nama", provinsiText).text(provinsiText);
                currentRow.find("td[data-kabupaten-id]").attr("data-kabupaten-id", kabupatenId).attr("data-kabupaten-nama", kabupatenText).text(kabupatenText);
                currentRow.find("td[data-kecamatan-id]").attr("data-kecamatan-id", kecamatanId).attr("data-kecamatan-nama", kecamatanText).text(kecamatanText);
                currentRow.find("td[data-desa-id]").attr("data-desa-id", desaId).attr("data-desa-nama", desaText).text(desaText);
                currentRow.find("td[data-dusun-id]").attr("data-dusun-id", dusunId).attr("data-dusun-nama", dusunText).text(dusunText);
                currentRow.find("td[data-row-id]").attr("data-row-id", rowId).text(rowId);

                resetFormEdit();
                Swal.fire({
                    title: "Success",
                    text: "Data updated successfully",
                    icon: "success"
                });
                form.reset();

            } else {
                Swal.fire({
                    title: "Error",
                    text: "Failed to update data",
                    icon: "error"
                });
                form.reportValidity();
            }
        }

        function resetFormAdd() {
            $("#dataForm")[0].reset();
            $("#provinsi").val(null).trigger("change");
            $("#kabupaten").val(null).trigger("change");
            $("#kecamatan").val(null).trigger("change");
            $("#desa").val(null).trigger("change");
            $("#dusun").val(null).trigger("change");
        }

        function resetFormEdit() {
            $("#editDataForm")[0].reset();
            $("#provinsi_id_edit").val(null).trigger("change");
            $("#kabupaten_id_edit").val(null).trigger("change");
            $("#kecamatan_id_edit").val(null).trigger("change");
            $("#desa_id_edit").val(null).trigger("change");
            $("#dusun_id_edit").val(null).trigger("change");
        }

        function deleteRow(row) {
            $(row).closest("tr").remove();
        }

        $("#btnTambahWilayah").on("click", function(e) {
            e.preventDefault();
            saveRow();
        });

        $("#btnUpdateWilayah").on("click", function(e) {
            e.preventDefault();
            updateRow();
        });

        $("#dataTable tbody").on("click", ".delete-btn", function(e) {
                e.preventDefault();
                const row = this;
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteRow(row);
                    Swal.fire("Deleted!", "Your data been deleted.", "success");
                }
            });
        });

        $("#dataTable tbody").on("click", ".edit-btn", function(e) {
            e.preventDefault();
            editRow(this);
        });




        function initializeLocationSelects(provinsiSelector, kabupatenSelector, kecamatanSelector, desaSelector, dusunSelector, dropdownParent) {
            // Initialize Provinsi dropdown
            $(provinsiSelector).select2({
                ajax: {
                    placeholder: "{{ __('global.selectProv') }}",
                    url: "{{ route('api.beneficiary.provinsi') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { search: params.term, page: params.page || 1 };
                    },
                    processResults: function(data) {
                        return { results: data.results, pagination: data.pagination };
                    }
                },
                cache: true,
                dropdownParent: dropdownParent
            });

            // Initialize Kabupaten (depends on Provinsi)
            $(kabupatenSelector).select2({
                ajax: {
                    url: function() {
                        return "{{ route('api.beneficiary.kab', ['id' => ':id']) }}".replace(':id', $(provinsiSelector).val());
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            provinsi_id: $(provinsiSelector).val(),
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return { results: data.results, pagination: data.pagination };
                    }
                },
                dropdownParent: dropdownParent
            });

            // Initialize Kecamatan (depends on Kabupaten)
            $(kecamatanSelector).select2({
                ajax: {
                    url: function() {
                        return "{{ route('api.beneficiary.kec', ['id' => ':id']) }}".replace(':id', $(kabupatenSelector).val());
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            kabupaten_id: $(kabupatenSelector).val(),
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return { results: data.results, pagination: data.pagination };
                    }
                },
                dropdownParent: dropdownParent
            });

            // Initialize Desa (depends on Kecamatan)
            $(desaSelector).select2({
                ajax: {
                    url: function() {
                        return "{{ route('api.beneficiary.desa', ['id' => ':id']) }}".replace(':id', $(kecamatanSelector).val());
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kecamatan_id: $(kecamatanSelector).val(),
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return { results: data.results, pagination: data.pagination };
                    }
                },
                dropdownParent: dropdownParent
            });
            // Initialize Desa (depends on Kecamatan)
            $(dusunSelector).select2({
                ajax: {
                    url: function() {
                        return "{{ route('api.beneficiary.dusun', ['id' => ':id']) }}".replace(':id', $(desaSelector).val());
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            desa_id: $(desaSelector).val(),
                            // desa_id: $("#desa_id").val() || $("#editDesa").val(),
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return { results: data.results, pagination: data.pagination };
                    }
                },
                dropdownParent: dropdownParent
            });

            // Clear dependent dropdowns when parent changes
            $(provinsiSelector).on('change', function() {
                $(kabupatenSelector).val(null).trigger('change');
                $(kecamatanSelector).val(null).trigger('change');
                $(desaSelector).val(null).trigger('change');
                $(dusunSelector).val(null).trigger('change');
            });

            $(kabupatenSelector).on('change', function() {
                $(kecamatanSelector).val(null).trigger('change');
                $(desaSelector).val(null).trigger('change');
                $(dusunSelector).val(null).trigger('change');
            });

            $(kecamatanSelector).on('change', function() {
                $(desaSelector).val(null).trigger('change');
                $(dusunSelector).val(null).trigger('change');
            });

            $(desaSelector).on('change', function() {
                $(dusunSelector).val(null).trigger('change');
            });
        }

        initializeLocationSelects(
            '#provinsi',
            '#kabupaten',
            '#kecamatan',
            '#desa',
            '#dusun',
            $('#ModalTambahPeserta')
        );

        // Initialize for #editDataModal
        initializeLocationSelects(
            '#provinsi_id_edit',
            '#kabupaten_id_edit',
            '#kecamatan_id_edit',
            '#desa_id_edit',
            '#dusun_id_edit',
            $('#editDataModal')
        );

    });
</script>

@endpush


