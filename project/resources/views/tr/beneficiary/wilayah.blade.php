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


        function addRow(data) {
            let rowCount = $("#tableBody tr").length;
            rowCount++;
            const provinsiText = $("#provinsi option:selected").text();
            const kabupatenText = $("#kabupaten option:selected").text();
            const kecamatanText = $("#kecamatan option:selected").text();
            const desaText = $("#desa option:selected").text();
            const dusunText = $("#dusun option:selected").text();

            const provinsiId = $("#provinsi").val();
            const kabupatenId = $("#kabupaten").val();
            const kecamatanId = $("#kecamatan").val();
            const desaId = $("#desa").val();
            const dusunId = $("#dusun").val();


            const newRow = `
            <tr>
                <td class="text-center align-middle" data-provinsi-id="${provinsiId}" data-kabupaten-id="${kabupatenId}" data-kecamatan-id="${kecamatanId}" data-desa-id="${desaId}" data-dusun-id="${dusunId}" data-provinsi-text="${provinsiText}" data-kabupaten-text="${kabupatenText}" data-kecamatan-text="${kecamatanText}" data-desa-text="${desaText}" data-dusun-text="${dusunText}">${rowCount}</td>
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
                resetFormAdd();
            } else {
                form.reportValidity();
            }
        }

        function editRow(row) {
            const currentRow = $(row).closest("tr");

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
            $("#editRowId").val(rowIndex);

            // For debugging
            console.log("Editing row with data:", {
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

        // // working script
        // function editRow(row) {
        //     const currentRow = $(row).closest("tr");
        //     const firstCell = currentRow.find("td:first");

        //     // Get the data attributes from the first cell
        //     const provinsiId = firstCell.data("provinsi-id");
        //     const kabupatenId = firstCell.data("kabupaten-id");
        //     const kecamatanId = firstCell.data("kecamatan-id");
        //     const desaId = firstCell.data("desa-id");
        //     const dusunId = firstCell.data("dusun-id");

        //     // Store the row index for reference
        //     const rowIndex = currentRow.index();
        //     $("#editRowId").val(rowIndex);

        //     // For debugging
        //     console.log("Editing row with data:", {
        //         provinsiId, kabupatenId, kecamatanId, desaId, dusunId
        //     });

        //     // Reset the edit form dropdowns
        //     $("#provinsi_id_edit").empty().trigger('change');
        //     $("#kabupaten_id_edit").empty().trigger('change');
        //     $("#kecamatan_id_edit").empty().trigger('change');
        //     $("#desa_id_edit").empty().trigger('change');
        //     $("#dusun_id_edit").empty().trigger('change');

        //     // Function to load a Select2 option and set it as selected
        //     function loadAndSelectOption(selectElement, url, id, additionalParams = {}) {
        //         return new Promise((resolve) => {
        //             const params = {
        //                 id: [id],  // Send as array as your controller expects
        //                 ...additionalParams
        //             };

        //             console.log(`Loading ${selectElement} with params:`, params);

        //             $.ajax({
        //                 url: url,
        //                 data: params,
        //                 dataType: 'json',
        //                 success: function(data) {
        //                     if (data.results && data.results.length > 0) {
        //                         // Create the option and append it
        //                         const option = new Option(data.results[0].text, data.results[0].id, true, true);
        //                         $(selectElement).append(option).trigger('change');
        //                         resolve(data.results[0].id);
        //                     } else {
        //                         console.error(`No data found for ID ${id}`);
        //                         resolve(null);
        //                     }
        //                 },
        //                 error: function(error) {
        //                     console.error(`Error loading option for ${selectElement}:`, error);
        //                     resolve(null);
        //                 }
        //             });
        //         });
        //     }

        //     // Load and set provinsi
        //     loadAndSelectOption(
        //         "#provinsi_id_edit",
        //         "{{ route('api.beneficiary.provinsi') }}",
        //         provinsiId
        //     ).then((loadedProvinsiId) => {
        //         // After provinsi is loaded, load kabupaten with provinsi_id parameter
        //         return loadAndSelectOption(
        //             "#kabupaten_id_edit",
        //             "{{ route('api.beneficiary.kab', ['id' => ':id']) }}".replace(':id', provinsiId),
        //             kabupatenId,
        //             { provinsi_id: loadedProvinsiId || provinsiId }
        //         );
        //     }).then((loadedKabupatenId) => {
        //         // After kabupaten is loaded, load kecamatan with kabupaten_id parameter
        //         return loadAndSelectOption(
        //             "#kecamatan_id_edit",
        //             "{{ route('api.beneficiary.kec', ['id' => ':id']) }}".replace(':id', kabupatenId),
        //             kecamatanId,
        //             { kabupaten_id: loadedKabupatenId || kabupatenId }
        //         );
        //     }).then((loadedKecamatanId) => {
        //         // After kecamatan is loaded, load desa with kecamatan_id parameter
        //         return loadAndSelectOption(
        //             "#desa_id_edit",
        //             "{{ route('api.beneficiary.desa', ['id' => ':id']) }}".replace(':id', kecamatanId),
        //             desaId,
        //             { kecamatan_id: loadedKecamatanId || kecamatanId }
        //         );
        //     }).then((loadedDesaId) => {
        //         // After desa is loaded, load dusun with desa_id parameter
        //         return loadAndSelectOption(
        //             "#dusun_id_edit",
        //             "{{ route('api.beneficiary.dusun', ['id' => ':id']) }}".replace(':id', desaId),
        //             dusunId,
        //             { desa_id: loadedDesaId || desaId }
        //         );
        //     }).catch(error => {
        //         console.error("Error in cascade loading:", error);
        //     });

        //     // Show the edit form if it's hidden
        //     $("#editDataForm").show();
        // }

        function resetFormAdd() {
            $("#dataForm")[0].reset();
            $("#provinsi").val(null).trigger("change");
            $("#kabupaten").val(null).trigger("change");
            $("#kecamatan").val(null).trigger("change");
            $("#desa").val(null).trigger("change");
            $("#dusun").val(null).trigger("change");
        }

        function deleteRow(row) {
            $(row).closest("tr").remove();
        }

        $("#btnTambahWilayah").on("click", function(e) {
            e.preventDefault();
            saveRow();
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


