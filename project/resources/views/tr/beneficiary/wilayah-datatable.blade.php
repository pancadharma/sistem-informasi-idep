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
                        <button id="btnTambahWilayah" class="form-control btn btn-primary">Tambah Wilayah</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="dataTable" class="table table-bordered table-striped">
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

<script>
    $(document).ready(function() {
        $('.select2').select2();
        let rowCount = 0;

        // Initialize DataTable
        var table = $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

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

            // Add row to DataTable with data attributes
            const newRow = `
                <tr data-row-id="${rowCount}" data-provinsi-id="${provinsiId}" data-kabupaten-id="${kabupatenId}" data-kecamatan-id="${kecamatanId}" data-desa-id="${desaId}" data-dusun-id="${dusunId}">
                    <td class="text-center align-middle">${rowCount}</td>
                    <td class="text-center align-middle">${provinsiText}</td>
                    <td class="text-center align-middle">${kabupatenText}</td>
                    <td class="text-center align-middle">${kecamatanText}</td>
                    <td class="text-center align-middle">${desaText}</td>
                    <td class="text-center align-middle">${dusunText}</td>
                    <td class="text-center align-middle">
                        <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
                    </td>
                </tr>
            `;

            // Add the row to the DataTable
            table.row.add($(newRow)).draw(false);
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

            // Get the data attributes from the row
            const provinsiId = currentRow.data("provinsi-id");
            const kabupatenId = currentRow.data("kabupaten-id");
            const kecamatanId = currentRow.data("kecamatan-id");
            const desaId = currentRow.data("desa-id");
            const dusunId = currentRow.data("dusun-id");

            // Get the text values from the respective cells
            const provinsiText = currentRow.find("td:eq(1)").text().trim();
            const kabupatenText = currentRow.find("td:eq(2)").text().trim();
            const kecamatanText = currentRow.find("td:eq(3)").text().trim();
            const desaText = currentRow.find("td:eq(4)").text().trim();
            const dusunText = currentRow.find("td:eq(5)").text().trim();

            // Store the row index for reference
            const rowIndex = currentRow.index();
            $("#editRowId").val(rowId);

            // Clear all dropdowns first
            // $("#provinsi_id_edit").empty();
            // $("#kabupaten_id_edit").empty();
            // $("#kecamatan_id_edit").empty();
            // $("#desa_id_edit").empty();
            // $("#dusun_id_edit").empty();

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
            // $("#editDataForm").reset();
            // resetFormEdit();
        }

        // function updateRow() {
        //     const rowId = $("#editRowId").val();
        //     const form = $("#editDataForm")[0];

        //     const provinsiId = $("#provinsi_id_edit").val() || 0;
        //     const kabupatenId = $("#kabupaten_id_edit").val() || 0;
        //     const kecamatanId = $("#kecamatan_id_edit").val() || 0;
        //     const desaId = $("#desa_id_edit").val() || 0;
        //     const dusunId = $("#dusun_id_edit").val() || 0;

        //     const provinsiText = $("#provinsi_id_edit option:selected").text() || "-";
        //     const kabupatenText = $("#kabupaten_id_edit option:selected").text() || "-";
        //     const kecamatanText = $("#kecamatan_id_edit option:selected").text() || "-";
        //     const desaText = $("#desa_id_edit option:selected").text() || "-";
        //     const dusunText = $("#dusun_id_edit option:selected").text() || "-";

        //     if (!form) {
        //         console.error("Edit form not found");
        //         return;
        //     }

        //     if (form.checkValidity()) {
        //         const formData = $("#editDataForm").serializeArray();

        //         console.log("Updating row with data:", {
        //             provinsiId, kabupatenId, kecamatanId, desaId, dusunId,
        //             provinsiText, kabupatenText, kecamatanText, desaText, dusunText
        //         });

        //         const currentRow = $("#dataTable tbody").find(`tr[data-row-id="${rowId}"]`);
        //         if (currentRow.length === 0) {
        //             console.error("Row not found");
        //             return;
        //         }

        //         // Update the row data attributes
        //         currentRow.attr("data-provinsi-id", provinsiId)
        //                 .attr("data-kabupaten-id", kabupatenId)
        //                 .attr("data-kecamatan-id", kecamatanId)
        //                 .attr("data-desa-id", desaId)
        //                 .attr("data-dusun-id", dusunId);

        //         // Update the row text
        //         currentRow.find("td:eq(1)").text(provinsiText);
        //         currentRow.find("td:eq(2)").text(kabupatenText);
        //         currentRow.find("td:eq(3)").text(kecamatanText);
        //         currentRow.find("td:eq(4)").text(desaText);
        //         currentRow.find("td:eq(5)").text(dusunText);

        //         resetFormEdit();
        //         Swal.fire({
        //             title: "Success",
        //             text: "Data updated successfully",
        //             icon: "success"
        //         });
        //         form.reset();

        //     } else {
        //         Swal.fire({
        //             title: "Error",
        //             text: "Failed to update data",
        //             icon: "error"
        //         });
        //         form.reportValidity();
        //     }
        // }
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

                // Update the row data attributes
                currentRow.attr("data-provinsi-id", provinsiId)
                        .attr("data-kabupaten-id", kabupatenId)
                        .attr("data-kecamatan-id", kecamatanId)
                        .attr("data-desa-id", desaId)
                        .attr("data-dusun-id", dusunId);

                // // Update the row text
                // currentRow.find("td:eq(1)").text(provinsiText);
                // currentRow.find("td:eq(2)").text(kabupatenText);
                // currentRow.find("td:eq(3)").text(kecamatanText);
                // currentRow.find("td:eq(4)").text(desaText);
                // currentRow.find("td:eq(5)").text(dusunText);

                currentRow.find("td[data-provinsi-id]").text(provinsiText || "");
                currentRow.find("td[data-kabupaten-id]").text(kabupatenText || "");
                currentRow.find("td[data-kecamatan-id]").text(kecamatanText || "");
                currentRow.find("td[data-desa-id]").text(desaText || "");
                currentRow.find("td[data-dusun-id]").text(dusunText || "");

                // Update the DataTable's internal cache
                const rowIndex = table.row(currentRow).index(); // Get the row index
                const rowData = table.row(rowIndex).data(); // Get the row data
                rowData[1] = provinsiText; // Update the data in the cache
                rowData[2] = kabupatenText;
                rowData[3] = kecamatanText;
                rowData[4] = desaText;
                rowData[5] = dusunText;
                table.row(rowIndex).data(rowData).draw(); // Update the row in the DataTable

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
            $('#dataForm')[0].reset(); // Reset Select2
            $('#dataForm').trigger('reset');
            // $('#dataForm select').val(null).trigger('change').empty(); // Reset Select2
            $("#provinsi").val(null).trigger("change");
            $("#kabupaten").val(null).trigger("change");
            $("#kecamatan").val(null).trigger("change");
            $("#desa").val(null).trigger("change");
            $("#dusun").val(null).trigger("change");
        }

        function resetFormEdit() {
            $("#editDataForm")[0].reset();
            $('#editDataForm').trigger('reset');
            $('#editDataForm select').val(null).trigger('change'); // Reset Select2
            // $('#editDataForm select').empty();
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
                    cache: false,
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
