<script>
    // Utility function to escape HTML special characters
    function escapeHtml(str) {
        if (!str) return ""; // Handle null/undefined cases
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

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
            initalizeJenisKelompok();
            initializeLocationSelects();
        }

        function initializeLocationSelects(provinsiSelector, kabupatenSelector, kecamatanSelector, desaSelector, dusunSelector, dropdownParent) {
            if (!$(provinsiSelector).val()) {
                $(kabupatenSelector).prop('disabled', true);
                $(kecamatanSelector).prop('disabled', true);
                $(desaSelector).prop('disabled', true);
                $(dusunSelector).prop('disabled', true);
            }
            $(provinsiSelector).select2({
                ajax: {
                    url: "{{ route('api.beneficiary.provinsi') }}",
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: params => ({
                        search: params.term,
                        page: params.page || 1
                    }),
                    processResults: data => ({
                        results: data.results,
                        pagination: data.pagination
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: "{{ __('global.selectProv') }}"
            }).on('change', function() {
                $(kabupatenSelector).val(null).trigger('change').prop('disabled', !$(this).val());
                $(kecamatanSelector).val(null).trigger('change').prop('disabled', true);
                $(desaSelector).val(null).trigger('change').prop('disabled', true);
                $(dusunSelector).val(null).trigger('change').prop('disabled', true);
            });

            $(kabupatenSelector).select2({
                ajax: {
                    url: () => "{{ route('api.beneficiary.kab', ['id' => ':id']) }}".replace(':id', $(provinsiSelector).val()),
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: params => ({
                        provinsi_id: $(provinsiSelector).val(),
                        search: params.term,
                        page: params.page || 1
                    }),
                    processResults: data => ({
                        results: data.results,
                        pagination: data.pagination
                    })
                },
                dropdownParent: dropdownParent
            }).on('change', function() {
                $(kecamatanSelector).val(null).trigger('change').prop('disabled', !$(this).val());
                $(desaSelector).val(null).trigger('change').prop('disabled', true);
                $(dusunSelector).val(null).trigger('change').prop('disabled', true);
            });

            $(kecamatanSelector).select2({
                ajax: {
                    url: () => "{{ route('api.beneficiary.kec', ['id' => ':id']) }}".replace(':id', $(kabupatenSelector).val()),
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        kabupaten_id: $(kabupatenSelector).val(),
                        search: params.term,
                        page: params.page || 1
                    }),
                    processResults: data => ({
                        results: data.results,
                        pagination: data.pagination
                    })
                },
                dropdownParent: dropdownParent
            }).on('change', function() {
                $(desaSelector).val(null).trigger('change').prop('disabled', !$(this).val());
                $(dusunSelector).val(null).trigger('change').prop('disabled', true);
            });

            $(desaSelector).select2({
                ajax: {
                    url: () => "{{ route('api.beneficiary.desa', ['id' => ':id']) }}".replace(':id', $(kecamatanSelector).val()),
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        kecamatan_id: $(kecamatanSelector).val(),
                        search: params.term,
                        page: params.page || 1
                    }),
                    processResults: data => ({
                        results: data.results,
                        pagination: data.pagination
                    })
                },
                dropdownParent: dropdownParent
            }).on('change', function() {
                $(dusunSelector).val(null).trigger('change').prop('disabled', !$(this).val());
            });

            $(dusunSelector).select2({
                ajax: {
                    url: () => "{{ route('api.beneficiary.dusun', ['id' => ':id']) }}".replace(':id', $(desaSelector).val()),
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        desa_id: $(desaSelector).val(),
                        search: params.term,
                        page: params.page || 1
                    }),
                    processResults: data => ({
                        results: data.results,
                        pagination: data.pagination
                    })
                },
                dropdownParent: dropdownParent
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

        // function to save form data
        function submitBeneficiaryData(programId) {
            const tableData = [];
            $("#dataTable tbody tr").each(function() {
                const row = $(this);
                const activityHeaders = $('#activityHeaders th.activity-header');
                const selectedActivities = activityHeaders.map((index, header) => {
                    const activityId = $(header).data('activity-id');
                    const cell = row.find(`td[data-program-activity-id="${activityId}"]`);
                    return cell.text().trim() === '√' ? activityId.toString() : null;
                }).get().filter(Boolean);

                const rowData = {
                    nama: row.find("td[data-nama]").attr("data-nama"),
                    gender: row.find("td[data-gender]").attr("data-gender"),
                    is_head_family: row.find("td[data-is_head_family]").attr("data-is_head_family") === "true",
                    head_family_name: row.find("td[data-head_family_name]").attr("data-head_family_name"),
                    kelompok_rentan: row.find("td[data-kelompok_rentan]").attr("data-kelompok_rentan").split(",").filter(Boolean),
                    rt: row.find("td[data-rt]").attr("data-rt"),
                    rw: row.find("td[data-rw]").attr("data-rw"),
                    dusun_id: row.find("td[data-dusun-id]").attr("data-dusun-id"),
                    desa_id: row.find("td[data-desa-id]").attr("data-desa-id"),
                    no_telp: row.find("td[data-no_telp]").attr("data-no_telp"),
                    jenis_kelompok: row.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok").split(",").filter(Boolean),
                    usia: row.find("td[data-usia]").attr("data-usia"),
                    is_non_activity: row.find("td[data-is_non_activity]").attr("data-is_non_activity") === "true",
                    keterangan: row.find("td[data-keterangan]").attr("data-keterangan"),
                    activitySelect: selectedActivities,
                };
                tableData.push(rowData);
            });

            if (tableData.length === 0) {
                Swal.fire({
                    title: "Error",
                    text: "No data to submit! Please add at least one beneficiary.",
                    icon: "error",
                    timer: 1500,
                    timerProgressBar: true,
                });
                return;
            }

            // Prepare the data to send
            const submitData = {
                program_id: programId,
                data: tableData,
            };

            const jsonData = JSON.stringify(tableData, null, 2);
            // console.table(jsonData);
            // // $("#modalData").text(jsonData);
            // // $("#previewModalsData").modal("show");

            // Submit via AJAX
            $.ajax({
                url: "{{ route('beneficiary.store') }}",
                method: "POST",
                data: JSON.stringify(submitData),
                contentType: "application/json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                beforeSend: function() {
                    Swal.fire({
                        title: "Submitting...",
                        text: "Please wait while the data is being processed.",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                },
                success: function(response) {
                    Swal.fire({
                        title: "Success",
                        text: response.message || "Beneficiaries created successfully!",
                        icon: "success",
                        timer: 1500,
                        timerProgressBar: true,
                    }).then(() => {
                        $("#dataTable tbody").empty();
                        $("#program_id").val("");
                        $("#kode_program").val("");
                        $("#nama_program").val("").prop("disabled", false);
                        if (response.data && response.data.redirect_url) {
                            window.location.href = response.data.redirect_url; // pakai dari response
                        } else {
                            window.location.href = "{{ route('beneficiary.index') }}"; // fallback ke default
                        }
                        // window.location.href = "{{ route('beneficiary.index') }}"; // Redirect to index
                    });
                },
                error: function(xhr) {
                    let errorMessage = "An error occurred while submitting the data.";

                    if (xhr.status === 422) {
                        // Ini error validation dari Laravel
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            errorMessage = Object.keys(errors).map((key) => {
                                return errors[key].join("\n");
                            }).join("\n\n"); // Pisah antar field pakai double new line
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        title: "Validation Error",
                        text: errorMessage,
                        icon: "error",
                        width: '40em',
                        timer: 5000,
                        timerProgressBar: true,
                    });
                }
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
            row.querySelector(".age-0-17").innerHTML = age >= 0 && age <= 17 ? '<span class="checkmark"><i class="bi bi-check2-square text-success" title="√"></i></span>' : "";
            row.querySelector(".age-18-24").innerHTML = age > 17 && age <= 24 ? '<span class="checkmark"><i class="bi bi-check2-square text-success" title="√"></i></span>' : "";
            row.querySelector(".age-25-59").innerHTML = age >= 25 && age <= 59 ? '<span class="checkmark"><i class="bi bi-check2-square text-success" title="√"></i></span>' : "";
            row.querySelector(".age-60-plus").innerHTML = age >= 60 ? '<span class="checkmark"><i class="bi bi-check2-square text-success" title="√"></i></span>' : "";
        }
        function addRow(data) {
            rowCount++;
            const kelompokRentanArray = Array.isArray(data.kelompok_rentan) ? data.kelompok_rentan.filter(Boolean) : [];
            const jenis_kelompok_array = Array.isArray(data.jenis_kelompok) ? data.jenis_kelompok.filter(Boolean) : [];

            const provinsiText = $("#provinsi_id_tambah option:selected").text() || "-";
            const kabupatenText = $("#kabupaten_id_tambah option:selected").text() || "-";
            const kecamatanText = $("#kecamatan_id_tambah option:selected").text() || "-";
            const desaText = $("#desa_id_tambah option:selected").text() || "-";
            const dusunText = $("#dusun_id_tambah option:selected").text() || "-";
            const keteranganText = $("#keterangan").val() || "";

            const provinsiId = $("#provinsi_id_tambah").val() || 0;
            const kabupatenId = $("#kabupaten_id_tambah").val() || 0;
            const kecamatanId = $("#kecamatan_id_tambah").val() || 0;
            const desaId = $("#desa_id_tambah").val() || 0;
            const dusunId = $("#dusun_id_tambah").val() || 0;

            const jenis_kelompok_data = jenis_kelompok_array.map((value) => {
                const option = $("#jenis_kelompok").select2("data").find((opt) => opt.id === value) || {
                    id: value,
                    text: value
                };
                return {
                    id: option.id,
                    text: option.text,
                };
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

            const kelompokRentanText = (kelompokRentanData || []).map(item => {
                const randomColor = getRandomColor();
                return `<span class="badge bg-${randomColor} me-1">${item.text}</span>`;
            }).join('');

            const jenisKelompokText = (jenis_kelompok_data || []).map(item => {
                const randomColor = getRandomColor();
                return `<span class="badge bg-${randomColor} me-1">${item.text}</span>`;
            }).join('');

            const genderText = $('#ModalTambahPeserta select[name="gender"] option[value="' + data.gender + '"]').text();
            const selectedActivities = $("#activitySelect").val() || [];
            const activityHeaders = $('#activityHeaders th.activity-header');

            const activityCells = activityHeaders.map((index, header) => {
                const activityId = $(header).data('activity-id');
                const isChecked = selectedActivities.includes(activityId.toString());
                const checkmark = isChecked ? '√' : '';
                return `<td class="text-center align-middle" data-program-activity-id="${activityId}">${checkmark}</td>`;
            }).get().join('');

            const nonAcCell = `<td class="text-center align-middle" data-is_non_activity="${data.is_non_activity ? 'true' : 'false'}">${data.is_non_activity ? '√' : ''}</td>`;

            const KetValue = escapeHtml(keteranganText);
            const KetCell = `<td class="text-left align-middle ellipsis-cell" data-keterangan="${KetValue}" title="${KetValue}">${keteranganText}</td>`;


            const headFamilyName = escapeHtml(data.head_family_name || '');
            const familyNameCell = `<td class="text-left align-middle ellipsis-cell" data-head_family_name="${headFamilyName}" title="${headFamilyName}">${headFamilyName || '-'}</td>`;

            const isHeadFamily = `<td class="text-center align-middle" data-is_non_activity="${data.is_head_family ? 'true' : 'false'}">${data.is_head_family ? '√' : ''}</td>`;


            const newRow = `
            <tr data-row-id="${rowCount}" class="nowrap">
                <td class="text-center align-middle d-none" data-provinsi-id="${provinsiId}" data-provinsi-nama="${provinsiText}" data-kabupaten-id="${kabupatenId}" data-kabupaten-nama="${kabupatenText}" data-kecamatan-id="${kecamatanId}" data-kecamatan-nama="${kecamatanText}" data-desa-id="${desaId}" data-desa-nama="${desaText}" data-dusun-id="${dusunId}" data-dusun-nama="${dusunText}">${rowCount}</td>
                <td data-nama="${data.nama || ''}" data-is_head_family="${data.is_head_family ? 'true' : 'false'}" data-head_family_name="${headFamilyName}" class="text-left align-middle">${data.nama || ''}</td>
                <td data-gender="${data.gender}" class="text-center align-middle text-nowrap">${genderText}</td>

                <td data-kelompok_rentan="${kelompokRentanArray.join(",")}" data-kelompok_rentan_full='${JSON.stringify(kelompokRentanData)}' class="text-left align-middle text-wrap">${kelompokRentanText}</td>

                <td data-rt="${data.rt}" class="text-center align-middle">${data.rt}</td>
                <td data-rw="${data.rw}" class="text-center align-middle">${data.rw}</td>
                <td data-dusun-id="${data.dusun_id_tambah}" data-dusun-nama="${dusunText}" class="text-center align-middle">${dusunText}</td>
                <td data-desa-id="${data.desa_id_tambah}" data-desa-nama="${desaText}" class="text-center align-middle">${desaText}</td>
                <td data-no_telp="${data.no_telp}" class="text-center align-middle">${data.no_telp}</td>


                <td data-jenis_kelompok="${jenis_kelompok_array.join(",")}" data-jenis_kelompok_full='${JSON.stringify(jenis_kelompok_data)}' class="text-left align-middle text-wrap">${jenisKelompokText}</td>


                <td data-usia="${data.usia}" class="text-center align-middle usia-cell">${data.usia}</td>
                <td class="text-center align-middle age-0-17"></td>
                <td class="text-center align-middle age-18-24"></td>
                <td class="text-center align-middle age-25-59"></td>
                <td class="text-center align-middle age-60-plus"></td>
                ${activityCells}
                ${nonAcCell}

                ${KetCell}
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

            // Remove 'required' from hidden elements to avoid focus error
            $("#dataForm [required]").each(function () {
                if (!$(this).is(":visible")) {
                    $(this).removeAttr("required").data("was-required", true);
                }
            });

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

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

                if (!Array.isArray(formData.jenis_kelompok)) {
                    formData.jenis_kelompok = formData.jenis_kelompok ? [formData.jenis_kelompok] : [];
                }

                if (!Array.isArray(formData.kelompok_rentan)) {
                    formData.kelompok_rentan = formData.kelompok_rentan ? [formData.kelompok_rentan] : [];
                }

                formData.is_head_family = $("#is_head_family").is(":checked")
                formData.head_family_name = $("#head_family_name").val() || "";

                formData.is_non_activity = $("#is_non_activity").is(":checked");
                formData.activitySelect = $("#activitySelect").val();


                addRow(formData);
                resetFormAdd();

                // Re-add previously removed 'required' attributes
                $("#dataForm [data-was-required]").each(function () {
                    $(this).attr("required", true).removeData("was-required");
                });
            }
            else
            {

            }
        }


        function resetFormAdd() {
            $("#dataForm")[0].reset();
            $("#head_family_name").prop('readonly', false);
            $("#kelompok_rentan").val(null).trigger("change");
            $("#jenis_kelompok").val(null).trigger("change");
            $("#activitySelect").val(null).trigger("change");

            $("#provinsi_id_tambah").val(null).trigger("change");
            $("#kabupaten_id_tambah").val(null).trigger("change");
            $("#kecamatan_id_tambah").val(null).trigger("change");
            $("#desa_id_tambah").val(null).trigger("change");
            $("#dusun_id_tambah").val(null).trigger("change");

            $("#ModalTambahPeserta").modal("hide");
        }

        function resetFormEdit() {
            $("#editDataForm")[0].reset();
            $("#edit_head_family_name").prop('readonly', false);
            $("#editKelompokRentan").val(null).trigger("change");
            $("#editJenisKelompok").val(null).trigger("change");
            $("#activitySelectEdit").val(null).trigger("change");

            $("#provinsi_id_edit").val(null).trigger("change");
            $("#kabupaten_id_edit").val(null).trigger("change");
            $("#kecamatan_id_edit").val(null).trigger("change");
            $("#desa_id_edit").val(null).trigger("change");
            $("#dusun_id_edit").val(null).trigger("change");

            $("#editDataModal").modal("hide");
        }

        function editRow(row) {
            const currentRow = $(row).closest("tr");
            const rowId = currentRow.data("row-id");
            const firstCell = currentRow.find("td:first");

            const locationFields = ["provinsi", "kabupaten", "kecamatan", "desa", "dusun"];
            locationFields.forEach(field => {
                const id = currentRow.find(`td[data-${field}-id]`).data(`${field}-id`) || 0;
                const name = currentRow.find(`td[data-${field}-nama]`).data(`${field}-nama`) || "-";
                const select = $(`#${field}_id_edit`);
                select.empty().append(new Option(name, id, true, true)).trigger('change');
            });
            // const provinsiId = firstCell.data("provinsi-id") || 0;
            // const kabupatenId = firstCell.data("kabupaten-id") || 0;
            // const kecamatanId = firstCell.data("kecamatan-id") || 0;
            // const desaId = firstCell.data("desa-id") || 0;
            // const dusunId = firstCell.data("dusun-id") || 0;

            // const provinsiText = firstCell.data("provinsi-nama") || "-";
            // const kabupatenText = firstCell.data("kabupaten-nama") || "-";
            // const kecamatanText = firstCell.data("kecamatan-nama") || "-";
            // const desaText = firstCell.data("desa-nama") || "-";
            // const dusunText = firstCell.data("dusun-nama") || "-";

            // Store the row index for reference
            const rowIndex = currentRow.index();
            $("#editRowId").val(rowIndex);

            // // For debugging
            // console.log("Editing row with data:", {
            //     provinsiId, kabupatenId, kecamatanId, desaId, dusunId,
            //     provinsiText, kabupatenText, kecamatanText, desaText, dusunText
            // });

            // $("#provinsi_id_edit").empty();
            // $("#kabupaten_id_edit").empty();
            // $("#kecamatan_id_edit").empty();
            // $("#desa_id_edit").empty();
            // $("#dusun_id_edit").empty();

            // function addOptionAndTriggerChange(selector, text, value) {
            //     return new Promise(resolve => {
            //         const option = new Option(text, value, true, true);
            //         $(selector).append(option).trigger('change');
            //         setTimeout(resolve, 100);
            //     });
            // }

            // addOptionAndTriggerChange("#provinsi_id_edit", provinsiText, provinsiId)
            //     .then(() => addOptionAndTriggerChange("#kabupaten_id_edit", kabupatenText, kabupatenId))
            //     .then(() => addOptionAndTriggerChange("#kecamatan_id_edit", kecamatanText, kecamatanId))
            //     .then(() => addOptionAndTriggerChange("#desa_id_edit", desaText, desaId))
            //     .then(() => addOptionAndTriggerChange("#dusun_id_edit", dusunText, dusunId));

            const kelompok_rentan = currentRow.find("td[data-kelompok_rentan]").attr("data-kelompok_rentan");
            const kelompokRentanValues = kelompok_rentan ? kelompok_rentan.split(",") : [];
            const kelompokRentanFullData = JSON.parse(currentRow.find("td[data-kelompok_rentan]").attr("data-kelompok_rentan_full") || "[]");

            const jenis_kelompok = currentRow.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok");
            const jenis_kelompok_val = jenis_kelompok ? jenis_kelompok.split(",") : [];
            const jenis_kelompok_full_data = JSON.parse(currentRow.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok_full") || "[]");


            const isNonActivity = currentRow.find("td[data-is_non_activity]").attr("data-is_non_activity") === "true";
            const keteranganText = currentRow.find("td[data-keterangan]").attr("data-keterangan") || "";
            const isHeadFamily =  currentRow.find("td[data-is_head_family]").attr("data-is_head_family") === "true";
            const familyHeadName = currentRow.find("td[data-head_family_name]").attr("data-head_family_name") || "";
            // $('#edit_head_family_name').val(data.head_family_name || '');


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

            $("#editJenisKelompok").select2({
                multiple: true,
                ajax: {
                    url: '{{ route('api.beneficiary.kelompok.jenis') }}',
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
                        const combinedResults = [...jenis_kelompok_full_data, ...data.results];
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
                placeholder: "{{ __('cruds.beneficiary.penerima.jenis_kelompok') }} ...",
            });

            kelompokRentanFullData.forEach(function(item) {
                if ($("#editKelompokRentan option[value='" + item.id + "']").length === 0) {
                    var option = new Option(item.text, item.id, true, true);
                    $("#editKelompokRentan").append(option);
                }
            });

            jenis_kelompok_full_data.forEach(function(item) {
                if ($("#editJenisKelompok option[value='" + item.id + "']").length === 0) {
                    var option = new Option(item.text, item.id, true, true);
                    $("#editJenisKelompok").append(option);
                }
            });

            const activityHeaders = $('#activityHeaders th.activity-header');
            const selectedActivities = activityHeaders.map((index, header) => {
                const activityId = $(header).data('activity-id');
                const cell = currentRow.find(`td[data-program-activity-id="${activityId}"]`);
                const isChecked = cell.text().trim() === '√'; // Assuming checkmark still indicates selection
                return isChecked ? activityId.toString() : null;
            }).get().filter(Boolean);

            // Ensure Select2 is initialized
            if (!$('#activitySelectEdit').data('select2')) {
                $('#activitySelectEdit').select2({
                    placeholder: "{{ __('cruds.beneficiary.select_activity') }}",
                    allowClear: true
                });
            }

            $('#activitySelectEdit').val(selectedActivities).trigger('change');

            $("#editRowId").val(rowId);
            $("#editNama").val(currentRow.find("td[data-nama]").attr("data-nama"));
            $('#edit_is_head_family').prop('checked', isHeadFamily);
            $('#edit_head_family_name').val(familyHeadName);

            $("#editNoTelp").val(currentRow.find("td[data-no_telp]").attr("data-no_telp"));
            $("#editGender").val(currentRow.find("td[data-gender]").attr("data-gender")).trigger("change");
            $("#editUsia").val(currentRow.find("td[data-usia]").attr("data-usia"));

            $("#editKelompokRentan").val(kelompokRentanValues).trigger("change"); // Kelompok Marjinal
            $("#editJenisKelompok").val(jenis_kelompok_val).trigger("change"); // Kelompok Marjinal

            $("#editRt").val(currentRow.find("td[data-rt]").attr("data-rt"));
            $("#editRwBanjar").val(currentRow.find("td[data-rw]").attr("data-rw"));

            $("#edit_is_non_activity").prop("checked", isNonActivity);

            $("#keterangan_edit").val(keteranganText)

            $("#editDataModal").modal("show");
        }

        function updateRow() {
            const rowId = $("#editRowId").val();
            const form          = document.getElementById("editDataForm");
            // const $provinsi     = $("#provinsi_id_edit");
            // const $kabupaten    = $("#kabupaten_id_edit");
            // const $kecamatan    = $("#kecamatan_id_edit");
            // const $desa         = $("#desa_id_edit");
            // const $dusun        = $("#desa_id_edit");

            // const provinsiId    = $provinsi.val() || 0;
            // const kabupatenId   = $kabupaten.val() || 0;
            // const kecamatanId   = $kabupaten.val() || 0;
            // const desaId        = $desa.val() || 0;
            // const dusunId       = $dusun.val() || 0;

            // const provinsiText  = $provinsi.find("option:selected").text() || "-";
            // const kabupatenText = $kabupaten.find("option:selected").text() || "-";
            // const kecamatanText = $kecamatan.find("option:selected").text() || "-";
            // const desaText      = $desa.find("option:selected").text() || "-";
            // const dusunText     = $dusun.find("option:selected").text() || "-";

            const headFamily = $('#edit_head_family_name').val() || '';
            if (!form) {
                console.error("Edit form not found");
                return;
            }

            // check if form requierd fields is empty show validation error
            if (!form.checkValidity()) {
                form.reportValidity();
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

                // console.log("Updating row with data:", {rowId,
                //     provinsiId, kabupatenId, kecamatanId, desaId, dusunId,
                //     provinsiText, kabupatenText, kecamatanText, desaText, dusunText
                // });

                formData.is_non_activity = $("#edit_is_non_activity").is(":checked");
                formData.is_head_family = $('#edit_is_head_family').is(':checked');

                // Ensure formData.kelompok_rentan & jenis_kelompok is an array
                if (!Array.isArray(formData.kelompok_rentan)) {
                    formData.kelompok_rentan = formData.kelompok_rentan ? [formData.kelompok_rentan] : [];
                }
                if (!Array.isArray(formData.jenis_kelompok)) {
                    formData.jenis_kelompok = formData.jenis_kelompok ? [formData.jenis_kelompok] : [];
                }

                const updatedActivities = $("#activitySelectEdit").val() || [];
                const activityHeaders = $('#activityHeaders th.activity-header');
                const genderText = $("#editGender option:selected").text();

                // start for kelompok_marjinal & jenis_kelompok
                const kelompokRentanData = $("#editKelompokRentan").select2("data").map(item => ({
                    id: item.id,
                    text: item.text
                }));
                const jenis_kelompok_data = $("#editJenisKelompok").select2("data").map(item => ({
                    id: item.id,
                    text: item.text
                }));

                // console.log("Update Jenis Kelompok Value: ", jenis_kelompok_data);

                const kelompokRentanHtml = kelompokRentanData.map((item) => {
                    const randomColor = getRandomColor();
                    return `<span class="badge badge-${randomColor}">${item.text}</span>`;
                }).join(" ");

                const jenis_kelompok_html = jenis_kelompok_data.map((item) => {
                    const randomColor = getRandomColor();
                    return `<span class="badge badge-${randomColor}">${item.text}</span>`;
                }).join(" ");

                // end for kelompok_marjinal & jenis_kelompok

                const currentRow = $("#dataTable tbody").find(`tr[data-row-id="${rowId}"]`);
                if (currentRow.length === 0) {
                    console.error("Row not found");
                    return;
                }

                // Update location fields
                const locationFields = ["provinsi", "kabupaten", "kecamatan", "desa", "dusun"];
                locationFields.forEach(field => {
                    const id = $(`#${field}_id_edit`).val() || 0;
                    const name = $(`#${field}_id_edit option:selected`).text() || "-";
                    currentRow.find(`td[data-${field}-id]`).data(`${field}-id`, id).data(`${field}-nama`, name).text(name);
                });

                activityHeaders.each(function(index) {
                    const activityId = $(this).data('activity-id');
                    const cell = currentRow.find(`td[data-program-activity-id="${activityId}"]`);
                    if (updatedActivities.includes(activityId.toString())) {
                        cell.text('√'); // Set checkmark if activity is in updated list
                    } else {
                        cell.text(''); // Clear checkmark if activity is not in updated list
                    }
                });

                const KetValue = escapeHtml(formData.keterangan || "");

                // currentRow.find("td[data-provinsi-id]").attr("data-provinsi-id", provinsiId).attr("data-provinsi-nama", provinsiText).text(provinsiText);
                // currentRow.find("td[data-kabupaten-id]").attr("data-kabupaten-id", kabupatenId).attr("data-kabupaten-nama", kabupatenText).text(kabupatenText);
                // currentRow.find("td[data-kecamatan-id]").attr("data-kecamatan-id", kecamatanId).attr("data-kecamatan-nama", kecamatanText).text(kecamatanText);
                // currentRow.find("td[data-desa-id]").attr("data-desa-id", desaId).attr("data-desa-nama", desaText).text(desaText);
                // currentRow.find("td[data-dusun-id]").attr("data-dusun-id", dusunId).attr("data-dusun-nama", dusunText).text(dusunText);
                // currentRow.find("td[data-row-id]").attr("data-row-id", rowId).text(rowId);

                currentRow.find("td[data-nama]").text(formData.nama).attr("data-nama", formData.nama);
                currentRow.find("td[data-head_family_name]").attr("data-head_family_name", formData.head_family_name);
                currentRow.find("td[data-is_head_family]").attr("data-is_head_family", formData.is_head_family ? 'true' : 'false');
                currentRow.find("td[data-gender]").text(genderText).attr("data-gender", formData.gender);

                currentRow.find("td[data-no_telp]").text(formData.no_telp).attr("data-no_telp", formData.no_telp);

                currentRow.find("td[data-rt]").text(formData.rt).attr("data-rt", formData.rt);
                currentRow.find("td[data-rw]").text(formData.rw).attr("data-rw", formData.rw);
                currentRow.find("td[data-usia]").text(formData.usia).attr("data-usia", formData.usia);

                currentRow.find("td[data-jenis_kelompok]").html(jenis_kelompok_html).attr("data-jenis_kelompok", jenis_kelompok_data.map((item) => item.id).join(",")).attr("data-jenis_kelompok_full", JSON.stringify(jenis_kelompok_data));

                currentRow.find("td[data-kelompok_rentan]").html(kelompokRentanHtml).attr("data-kelompok_rentan", kelompokRentanData.map((item) => item.id).join(",")).attr("data-kelompok_rentan_full", JSON.stringify(kelompokRentanData));

                currentRow.find("td[data-is_non_activity]").text(formData.is_non_activity ? '√' : '').attr("data-is_non_activity", formData.is_non_activity ? 'true' : 'false');
                currentRow.find("td[data-keterangan]").text(formData.keterangan).attr("data-keterangan", KetValue).addClass("ellipsis-cell").attr("title", KetValue);

                updateAgeCheckmarks(currentRow.find(".usia-cell"));

                resetFormEdit();
                Swal.fire({
                    title: "Success",
                    text: "Data updated successfully",
                    icon: "success",
                    timer: 500,
                    timerProgressBar: true,
                });

                $("#editDataModal").modal("hide");
                $("#editJenisKelompok").val(null).trigger("change");
                $("#editKelompokRentan").val(null).trigger("change");
                $("#editGender").val(null).trigger("change");
                form.reset();

            } else {

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
                const form = document.getElementById('dataForm');
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }
                saveRow();
            });

            $("#dataTable tbody").on("click", ".edit-btn", function(e) {
                e.preventDefault();
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
                const programId = $("#program_id").val();
                if (!programId) {
                    Swal.fire({
                        title: "Error",
                        text: "Please select a program before submitting!",
                        icon: "error",
                        timer: 1500,
                        timerProgressBar: true,
                    });
                    $("#kode_program").click();
                    return;
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "You are about to submit the data!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, submit!",
                    cancelButtonText: "No, cancel!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitBeneficiaryData(programId); // Call the submit function
                    }
                });
            });
        }

        $("#ModalTambahPeserta, #editDataModal").on("shown.bs.modal", function() {
            $(this).removeAttr("inert");

        });

        $('#ModalTambahPeserta, #editDataModal').on('shown.bs.modal', function () {
            $('input[type="checkbox"][data-sync-nama][data-sync-head-family]').off('change input').each(function () {
                const $checkbox = $(this);
                const $namaInput = $($checkbox.data('sync-nama'));
                const $headFamilyInput = $($checkbox.data('sync-head-family'));

                function toggleHeadFamilyInput() {
                    if ($checkbox.is(':checked')) {
                        $headFamilyInput.val($namaInput.val()).prop('readonly', true);
                    } else {
                        $headFamilyInput.prop('readonly', false);
                        $headFamilyInput.prop('required', true);
                    }
                }

                $checkbox.on('change', toggleHeadFamilyInput);

                $namaInput.on('input', function () {
                    if ($checkbox.is(':checked')) {
                        $headFamilyInput.val($namaInput.val());
                    }
                });

                toggleHeadFamilyInput(); // Initialize on modal load
            });
        });


        $("#ModalTambahPeserta, #editDataModal").on("hide.bs.modal", function(e) {
            $(this).attr("inert", "");
            $(document.activeElement).blur();
        });

        loadSelect2Option();
        bindEvents();
    });
</script>
