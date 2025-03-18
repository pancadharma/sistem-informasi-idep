<script>
    $(document).ready(function() {
        if (typeof $ === "undefined") {
            console.error("jQuery is not included. Please include jQuery in your HTML file.");
            return;
        }

        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        if (!csrfToken) {
            console.error("CSRF token is not found. Please include the CSRF token meta tag in your HTML file.");
            return;
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
        });

        let rowCount = 0;

        function loadSelect2Option() {
            initializeSelect2ForKelompokRentan();
            initializeSelect2ForDesa();
            initializeSelect2ForDusun();
            initalizeJenisKelompok(); // initialize the select for jenis kelompok
        }

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
            '#provinsi_id_tambah',
            '#kabupaten_id_tambah',
            '#kecamatan_id_tambah',
            '#desa_id_tambah',
            '#dusun_id_tambah',
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


        function initializeSelect2ForKelompokRentan() {
            $("#kelompok_rentan").select2({
                placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
                dropdownParent: $("#ModalTambahPeserta"),
                width: "100%",
                allowClear: true,
                ajax: {
                    url: '{{ route('api.beneficiary.kelompok.rentan') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more,
                            },
                        };
                    },
                    cache: true,
                },
            });

            $("#editKelompokRentan").select2({
                placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
                dropdownParent: $("#editDataModal"),
                width: "100%",
                allowClear: true,
                ajax: {
                    url: '{{ route('api.beneficiary.kelompok.rentan') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more,
                            },
                        };
                    },
                    cache: true,
                },
            });
        }

        function initializeSelect2ForDesa() {
            $("#desa_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}',
                dropdownParent: $("#ModalTambahPeserta"),
                width: "100%",
                ajax: {
                    
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more,
                            },
                        };
                    },
                    cache: true,
                },
                dropdownPosition: 'below',
            });

            $("#editDesa").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}',
                ajax: {
                    
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more,
                            },
                        };
                    },
                    cache: true,
                },
                dropdownParent: $("#editDataModal"),
                width: "100%",
            });
        }

        function initializeSelect2ForDusun() {
            $("#dusun_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.dusun.title') }}',
                ajax: {
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            desa_id: $("#desa_id").val() || $("#editDesa").val(),
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: data.pagination,
                        };
                    },
                    cache: true,
                },
                dropdownParent: $("#ModalTambahPeserta"),
                width: "100%",
                dropdownPosition: 'below',
            });

            $("#editDusun").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.dusun.title') }}',
                ajax: {
                    
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            desa_id: $("#desa_id").val() || $("#editDesa").val(),
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: data.pagination,
                        };
                    },
                    cache: true,
                },
                dropdownParent: $("#editDataModal"),
                width: "100%",
            });
        }

        function initalizeJenisKelompok(){
            let placeholder = '{{ __('global.pleaseSelect') . ' ' . __('cruds.beneficiary.penerima.jenis_kelompok') }}';
            $("#jenis_kelompok").select2({
                placeholder: placeholder,
                ajax: {
                    url: '{{ route('api.jenis.kelompok') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more,
                            },
                        };
                    },
                    // data: function(params) {
                    //     return {
                    //         search: params.term,
                    //         page: params.page || 1,
                    //     };
                    // },
                    // processResults: function(data, params) {
                    //     params.page = params.page || 1;
                    //     return {
                    //         results: data.results,
                    //         pagination: {
                    //             more: data.pagination.more,
                    //         },
                    //     };
                    // },
                    cache: true,
                },
                dropdownParent: $("#ModalTambahPeserta"),
                width: "100%",
            });
            $("#editJenisKelompok").select2({
                placeholder: placeholder,
                ajax: {
                    url: '{{ route('api.jenis.kelompok') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more,
                            },
                        };
                    },
                    cache: true,
                },
                dropdownParent: $("#editDataModal"),
                width: "100%",
            });


        }

        function updateAgeCheckmarks(usiaCell) {
            const row = usiaCell.closest("tr")[0];
            const ageText = usiaCell.text().trim();
            const age = Number.parseInt(ageText, 10);

            if (isNaN(age)) {
                row.querySelector(".age-0-17").innerHTML = "";
                row.querySelector(".age-18-24").innerHTML = "";
                row.querySelector(".age-25-59").innerHTML = "";
                row.querySelector(".age-60-plus").innerHTML = "";
                return;
            }
            row.querySelector(".age-0-17").innerHTML = age >= 0 && age <= 17 ? '<span class="checkmark"><i class="bi bi-check2-square text-success" title="✔️"></i></span>' : "";
            row.querySelector(".age-18-24").innerHTML = age > 17 && age <= 24 ? '<span class="checkmark"><i class="bi bi-check2-square text-success" title="✔️"></i></span>' : "";
            row.querySelector(".age-25-59").innerHTML = age >= 25 && age <= 59 ? '<span class="checkmark"><i class="bi bi-check2-square text-success" title="✔️"></i></span>' : "";
            row.querySelector(".age-60-plus").innerHTML = age >= 60 ? '<span class="checkmark"><i class="bi bi-check2-square text-success" title="✔️"></i></span>' : "";
        }

        function addRow(data) {
            rowCount++;

            const disabilitasArray = Array.isArray(data.disabilitas) ? data.disabilitas : [];
            const kelompokRentanArray = Array.isArray(data.kelompok_rentan) ? data.kelompok_rentan : [];
            const desaText = $("#desa_id option:selected").text();
            const dusunText = $("#dusun_id option:selected").text();
            const jenisKelompokText = $("#jenis_kelompok option:selected").text();
            const disabilitasText = disabilitasArray.map((value) => {
                const option = $('#ModalTambahPeserta select[name="disabilitas"] option[value="' + value + '"]');
                const text = option.length ? option.text() : "";
                const randomColor = getRandomColor();
                return `<span class="badge badge-${randomColor}">${text}</span>`;
            });
            const kelompokRentanData = kelompokRentanArray.map((value) => {
                const option = $("#kelompok_rentan").select2("data").find((opt) => opt.id === value) || {
                    id: value,
                    text: value
                };
                return {
                    id: option.id,
                    text: option.text,
                };
            });
            const kelompokRentanText = kelompokRentanData.map((item) => {
                const randomColor = getRandomColor();
                return `<span class="badge badge-${randomColor}">${item.text}</span>`;
            });
            const genderText = $('#ModalTambahPeserta select[name="gender"] option[value="' + data.gender + '"]').text();
            const selectedActivities = $("#activitySelect").val() || [];
            const activityHeaders = $('#activityHeaders th.activity-header');

            const activityCells = activityHeaders.map((index, header) => {
                const activityId = $(header).data('activity-id');
                const isChecked = selectedActivities.includes(activityId.toString());
                const checkmark = isChecked ? '✔️' : '';
                return `<td class="text-center align-middle" data-program-activity-id="${activityId}">${checkmark}</td>`;
            }).get().join('');

            const nonAcCell = `<td class="text-center align-middle" data-is_non_activity="${data.is_non_activity ? 'true' : 'false'}">${data.is_non_activity ? '✔️' : ''}</td>`;

            const newRow = `
            <tr data-row-id="${rowCount}" class="nowrap">
                <td class="text-center align-middle d-none">${rowCount}</td>
                <td data-nama="${data.nama}" class="text-left align-middle">${data.nama}</td>
                <td data-gender="${data.gender}" class="text-center align-middle text-nowrap">${genderText}</td>
                <td data-disabilitas="${disabilitasArray.join(",")}" class="text-left align-middle text-wrap d-none">${disabilitasText.join(", ")}</td>
                <td data-kelompok_rentan="${kelompokRentanArray.join(",")}" data-kelompok_rentan_full='${JSON.stringify(kelompokRentanData)}' class="text-left align-middle text-wrap">${kelompokRentanText.join(" ")}</td>
                <td data-rt="${data.rt}" class="text-center align-middle">${data.rt}</td>
                <td data-rw="${data.rw}" class="text-center align-middle">${data.rw}</td>
                <td data-dusun-id="${data.dusun_id}" data-dusun-nama="${dusunText}" class="text-center align-middle">${dusunText}</td>
                <td data-desa-id="${data.desa_id}" data-desa-nama="${desaText}" class="text-center align-middle">${desaText}</td>
                <td data-no_telp="${data.no_telp}" class="text-center align-middle">${data.no_telp}</td>
                <td data-jenis_kelompok="${data.jenis_kelompok}" data-jenis_kelompok-text="${jenisKelompokText}" class="text-center align-middle">${jenisKelompokText}</td>
                <td data-usia="${data.usia}" class="text-center align-middle usia-cell">${data.usia}</td>
                <td class="text-center align-middle age-0-17"></td>
                <td class="text-center align-middle age-18-24"></td>
                <td class="text-center align-middle age-25-59"></td>
                <td class="text-center align-middle age-60-plus"></td>
                ${activityCells}
                ${nonAcCell}
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
                </td>
            </tr>
            `;

            $("#tableBody").append(newRow);
            updateAgeCheckmarks($("#dataTable tbody").find(`tr[data-row-id="${rowCount}"]`).find(".usia-cell"));
            resetFormAdd();
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

                if (!Array.isArray(formData.disabilitas)) {
                    formData.disabilitas = [formData.disabilitas];
                }

                if (!Array.isArray(formData.kelompok_rentan)) {
                    formData.kelompok_rentan = [formData.kelompok_rentan];
                }

                formData.is_non_activity = $("#is_non_activity").is(":checked");
                formData.activitySelect = $("#activitySelect").val();

                addRow(formData);
                resetFormAdd();
            } else {
                form.reportValidity();
            }
        }

        function resetFormAdd() {
            $("#dataForm")[0].reset();
            $("#kelompok_rentan").val(null).trigger("change");
            $("#disabilitas").val(null).trigger("change");
            $("#activitySelect").val(null).trigger("change");
            $("#desa_id").val(null).trigger("change");
            $("#dusun_id").val(null).trigger("change");
            $("#ModalTambahPeserta").modal("hide");
        }

        function resetFormEdit() {
            $("#editDataForm")[0].reset();
            $("#kelompok_rentan").val(null).trigger("change");
            $("#editDisabilitas").val(null).trigger("change");
            $("#activitySelectEdit").val(null).trigger("change");
            $("#editDesa").val(null).trigger("change");
            $("#editDusun").val(null).trigger("change");
            $("#editDataModal").modal("hide");
        }

        function editRow(row) {
            const currentRow = $(row).closest("tr");
            const rowId = currentRow.data("row-id");

            const disabilitas = currentRow.find("td[data-disabilitas]").attr("data-disabilitas");
            const disabilitasValues = disabilitas ? disabilitas.split(",") : [];

            const kelompok_rentan = currentRow.find("td[data-kelompok_rentan]").attr("data-kelompok_rentan");
            const kelompokRentanValues = kelompok_rentan ? kelompok_rentan.split(",") : [];
            const kelompokRentanFullData = JSON.parse(currentRow.find("td[data-kelompok_rentan]").attr("data-kelompok_rentan_full") || "[]");

            const desaId = currentRow.find("td[data-desa-id]").data("desa-id");
            const desaNama = currentRow.find("td[data-desa-id]").data("desa-nama");

            const dusunId = currentRow.find("td[data-dusun-id]").data("dusun-id");
            const dusunNama = currentRow.find("td[data-dusun-id]").data("dusun-nama");

            const jenisKelompokId = currentRow.find("td[data-jenis_kelompok]").data("jenis_kelompok");
            const jenisKelompokNama = currentRow.find("td[data-jenis_kelompok-text]").data("jenis_kelompok-text");
            const isNonActivity = currentRow.find("td[data-is_non_activity]").attr("data-is_non_activity") === "true";

            // console.log("jenis_kelompk:", jenisKelompokId, jenisKelompokNama);

            $("#editKelompokRentan").select2({
                multiple: true,
                ajax: {
                    url: '{{ route('api.beneficiary.kelompok.rentan') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        const combinedResults = [...kelompokRentanFullData, ...data.results];
                        const uniqueResults = combinedResults.filter((v, i, a) => a.findIndex((t) => t.id === v.id) === i);
                        return {
                            results: uniqueResults,
                            pagination: {
                                more: data.pagination.more,
                            },
                        };
                    },
                    cache: true,
                },
                dropdownParent: $("#editDataModal"),
                width: "100%",
                closeOnSelect: false,
                placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
            });

            kelompokRentanFullData.forEach(function(item) {
                if ($("#editKelompokRentan option[value='" + item.id + "']").length === 0) {
                    var option = new Option(item.text, item.id, true, true);
                    $("#editKelompokRentan").append(option);
                }
            });

            const activityHeaders = $('#activityHeaders th.activity-header');
            const selectedActivities = activityHeaders.map((index, header) => {
                const activityId = $(header).data('activity-id');
                const cell = currentRow.find(`td[data-program-activity-id="${activityId}"]`);
                const isChecked = cell.text().trim() === '✔️'; // Assuming checkmark still indicates selection
                return isChecked ? activityId.toString() : null;
            }).get().filter(Boolean);

            // Ensure Select2 is initialized
            if (!$('#activitySelectEdit').data('select2')) {
                $('#activitySelectEdit').select2({
                    placeholder: "Select Activities",
                    allowClear: true
                });
            }

            $('#activitySelectEdit').val(selectedActivities).trigger('change');
            // console.info("Activities: ", selectedActivities)


            // Kemudian set nilai dan trigger change
            $("#editKelompokRentan").val(kelompokRentanValues).trigger("change");
            // $("#editKelompokRentan").val(kelompokRentanValues).trigger("change");
            // $("#editJenisKelompok").val(jenisKelompokId).trigger("change");

            $("#editRowId").val(rowId);
            $("#editNama").val(currentRow.find("td[data-nama]").attr("data-nama"));
            $("#editGender").val(currentRow.find("td[data-gender]").attr("data-gender")).trigger("change");
            $("#editDisabilitas").val(disabilitasValues).trigger("change");
            $("#editRt").val(currentRow.find("td[data-rt]").attr("data-rt"));
            $("#editRwBanjar").val(currentRow.find("td[data-rw]").attr("data-rw"));
            $("#editDesa").append(new Option(desaNama, desaId, true, true)).trigger("change");
            $("#editDusun").append(new Option(dusunNama, dusunId, true, true)).trigger("change");
            $("#editJenisKelompok").append(new Option(jenisKelompokNama, jenisKelompokId, true, true)).trigger("change");
            // $("#editJenisKelompok").val(currentRow.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok"));

            $("#editNoTelp").val(currentRow.find("td[data-no_telp]").attr("data-no_telp"));
            $("#editUsia").val(currentRow.find("td[data-usia]").attr("data-usia"));

            $("#edit_is_non_activity").prop("checked", isNonActivity);

            $("#editDataModal").modal("show");
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

        function updateRow() {
            const rowId = $("#editRowId").val();
            const form = document.getElementById("editDataForm");

            const desaId = $("#editDesa").val();
            const desaText = $("#editDesa option:selected").text();
            const dusunId = $("#editDusun").val();
            const dusunText = $("#editDusun option:selected").text();

            const jenisKelompokId = $("#editJenisKelompok").val();
            const jenisKelompokText = $("#editJenisKelompok option:selected").text();

            console.log("Update Jenis Kelompok Value: ", jenisKelompokId + jenisKelompokText);

            if (!form) {
                console.error("Edit form not found");
                return;
            }

            if (form.checkValidity()) {
                const formData = $("#editDataForm").serializeArray().reduce((obj, item) => {
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
                formData.is_non_activity = $("#edit_is_non_activity").is(":checked");
                // Ensure formData.disabilitas is an array
                if (!Array.isArray(formData.disabilitas)) {
                    formData.disabilitas = [formData.disabilitas];
                }
                // Ensure formData.kelompok_rentan is an array
                if (!Array.isArray(formData.kelompok_rentan)) {
                    formData.kelompok_rentan = [formData.kelompok_rentan];
                }
                // Ensure formData.jenis_kelompok is an array
                if (!Array.isArray(formData.jenis_kelompok)) {
                    formData.jenis_kelompok = [formData.jenis_kelompok];
                }

                const updatedActivities = $("#activitySelectEdit").val() || [];
                const activityHeaders = $('#activityHeaders th.activity-header');
                const genderText = $("#editGender option:selected").text();

                const kelompokRentanData = $("#editKelompokRentan").select2("data").map(item => ({
                    id: item.id,
                    text: item.text
                }));

                const kelompokRentanHtml = kelompokRentanData.map((item) => {
                    const randomColor = getRandomColor();
                    return `<span class="badge badge-${randomColor}">${item.text}</span>`;
                }).join(" ");

                const currentRow = $("#dataTable tbody").find(`tr[data-row-id="${rowId}"]`);
                if (currentRow.length === 0) {
                    console.error("Row not found");
                    return;
                }


                activityHeaders.each(function(index) {
                    const activityId = $(this).data('activity-id');
                    const cell = currentRow.find(`td[data-program-activity-id="${activityId}"]`);
                    if (updatedActivities.includes(activityId.toString())) {
                        cell.text('✔️'); // Set checkmark if activity is in updated list
                    } else {
                        cell.text(''); // Clear checkmark if activity is not in updated list
                    }
                });


                currentRow.find("td[data-nama]").text(formData.nama).attr("data-nama", formData.nama);
                currentRow.find("td[data-gender]").text(genderText).attr("data-gender", formData.gender);

                currentRow.find("td[data-disabilitas]").html(formData.disabilitas.map((value) => {
                    const text = $('#editDisabilitas option[value="' + value + '"]').text();
                    const randomColor = getRandomColor();
                    return `<span class="badge badge-${randomColor}">${text}</span>`;
                }).join(" ")).attr("data-disabilitas", formData.disabilitas.join(","));


                currentRow.find("td[data-rt]").text(formData.rt).attr("data-rt", formData.rt);
                currentRow.find("td[data-rw]").text(formData.rw).attr("data-rw", formData.rw);
                currentRow.find("td[data-no_telp]").text(formData.no_telp).attr("data-no_telp", formData.no_telp);
                // currentRow.find("td[data-jenis_kelompok]").text(formData.jenis_kelompok.join(", ")).attr("data-jenis_kelompok", formData.jenis_kelompok.join(","));
                currentRow.find("td[data-usia]").text(formData.usia).attr("data-usia", formData.usia);
                currentRow.find("td[data-desa-id]").attr("data-desa-id", desaId).attr("data-desa-nama", desaText).text(desaText);
                currentRow.find("td[data-dusun-id]").attr("data-dusun-id", dusunId).attr("data-dusun-nama", dusunText).text(dusunText);
                currentRow.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok", jenisKelompokId).attr("data-jenis_kelompok-text", jenisKelompokText).text(jenisKelompokText);

                currentRow.find("td[data-kelompok_rentan]").html(kelompokRentanHtml).attr("data-kelompok_rentan", kelompokRentanData.map((item) => item.id).join(",")).attr("data-kelompok_rentan_full", JSON.stringify(kelompokRentanData));

                currentRow.find("td[data-is_non_activity]").text(formData.is_non_activity ? '✔️' : '').attr("data-is_non_activity", formData.is_non_activity ? 'true' : 'false');

                updateAgeCheckmarks(currentRow.find(".usia-cell"));

                resetFormEdit();
                $("#editDataModal").modal("hide");

                $("#disabilitas").val(null).trigger("change");
                $("#editKelompokRentan").val(null).trigger("change");
                $("#editGender").val(null).trigger("change");

                form.reset();
            } else {
                form.reportValidity();
            }
        }

        function deleteRow(row) {
            $(row).closest("tr").remove();
        }

        function getRandomColor() {
            const colors = ["primary", "secondary", "success", "danger", "warning", "info", "light", "dark"];
            const randomIndex = Math.floor(Math.random() * colors.length);
            return colors[randomIndex];
        }

        function bindEvents() {
            $("#desa_id, #editDesa").on("change", function() {
                const targetDusun = $(this).attr("id") === "desa_id" ? "#dusun_id" : "#editDusun";
                $(targetDusun).val(null).trigger("change");
            });

            $("#addDataBtn").on("click", function() {
                const programId = $("#program_id").val();
                if (!programId || programId === "" || programId === undefined) {
                    Swal.fire({
                        text: '{{ __('global.pleaseSelect') . ' ' . __('cruds.program.title') }}',
                        position: "center",
                        title: "Opssss...",
                        timer: 500,
                        timerProgressBar: true,
                        icon: "error",
                        showCloseButton: true,
                        showCancelButton: false,
                        showConfirmButton: false,
                        showDenyButton: false,
                    });

                    $("#kode_program").click();
                    return false;
                }

                $("#ModalTambahPeserta").modal("show");
            });

            $("#saveDataBtn").on("click", function(e) {
                e.preventDefault();
                saveRow();
            });

            $("#dataTable tbody").on("click", ".edit-btn", function(e) {
                e.preventDefault();
                // console.log(this);
                editRow(this);
            });

            $("#updateDataBtn").on("click", function(e) {
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
                        Swal.fire("Deleted!", "Your file has been deleted.", "success");
                    }
                });
            });

            $("#submitDataBtn").on("click", function() {
                const tableData = [];
                $("#dataTable tbody tr").each(function() {
                    const row = $(this);
                    const rowData = {
                        nama: row.find("td[data-nama]").attr("data-nama"),
                        gender: row.find("td[data-gender]").attr("data-gender"),
                        disabilitas: row.find("td[data-disabilitas]").attr("data-disabilitas").split(","),
                        kelompok_rentan: row.find("td[data-kelompok_rentan]").attr("data-kelompok_rentan").split(","),
                        rt: row.find("td[data-rt]").attr("data-rt"),
                        rw: row.find("td[data-rw]").attr("data-rw"),
                        dusun_id: row.find("td[data-dusun-id]").attr("data-dusun-id"),
                        desa_id: row.find("td[data-desa-id]").attr("data-desa-id"),
                        no_telp: row.find("td[data-no_telp]").attr("data-no_telp"),
                        jenis_kelompok: row.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok"),
                        usia: row.find("td[data-usia]").attr("data-usia"),
                        is_non_activity: row.find("td[data-is_non_activity]").attr("data-is_non_activity") === "true",
                    };
                    tableData.push(rowData);
                });

                const jsonData = JSON.stringify(tableData, null, 2);
                $("#modalData").text(jsonData);
                $("#previewModalsData").modal("show");
            });

            $("#sendDataBtn").on("click", function() {
                const finalData = JSON.parse($("#modalData").text());

                $.ajax({
                    url: "/beneficiary/kirim-peserta",
                    method: "POST",
                    data: JSON.stringify(finalData),
                    contentType: "application/json",
                    success: function(response) {
                        alert("Data sent successfully!");
                        $("#previewModal").modal("hide");
                    },
                    error: function(xhr, status, error) {
                        alert("Error sending data: " + error);
                    },
                });
            });
        }

        $("#ModalTambahPeserta, #editDataModal").on("shown.bs.modal", function() {
            $(this).removeAttr("inert");
        });

        $("#ModalTambahPeserta, #editDataModal").on("hide.bs.modal", function(e) {
            $(this).attr("inert", "");
            $(document.activeElement).blur();
        });

        loadSelect2Option();
        bindEvents();
    });
</script>
