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
            const disabilitasOptions = [{
                    id: "Fisik",
                    text: "Fisik"
                },
                {
                    id: "Sensorik",
                    text: "Sensorik"
                },
                {
                    id: "Intelektual",
                    text: "Intelektual"
                },
                {
                    id: "Mental",
                    text: "Mental"
                },
                {
                    id: "Ganda",
                    text: "Ganda"
                },
            ];

            $("#disabilitas").select2({
                data: disabilitasOptions,
                placeholder: '{{ __('global.select') . ' ' . __('cruds.beneficiary.penerima.disability') }} ...',
                dropdownParent: $("#ModalTambahPeserta"),
                width: "100%",
            });

            $("#editDisabilitas").select2({
                data: disabilitasOptions,
                placeholder: '{{ __('global.select') . ' ' . __('cruds.beneficiary.penerima.disability') }} ...',
                dropdownParent: $("#editDataModal"),
                width: "100%",
            });

            initializeSelect2ForKelompokRentan();
            initializeSelect2ForDesa();
            initializeSelect2ForDusun();
        }

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
                ajax: {
                    url: '{{ route('api.beneficiary.desa') }}',
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
                dropdownParent: $("#ModalTambahPeserta"),
                width: "100%",
            });

            $("#editDesa").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}',
                ajax: {
                    url: '{{ route('api.beneficiary.desa') }}',
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
                    url: '{{ route('api.beneficiary.dusun') }}',
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
                minimumInputLength: 2,
            });

            $("#editDusun").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.dusun.title') }}',
                ajax: {
                    url: '{{ route('api.beneficiary.dusun') }}',
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

            row.querySelector(".age-0-17").innerHTML = age >= 0 && age <= 17 ? '<span class="checkmark">✔</span>' : "";
            row.querySelector(".age-18-24").innerHTML = age > 17 && age <= 24 ? '<span class="checkmark">✔</span>' : "";
            row.querySelector(".age-25-59").innerHTML = age >= 25 && age <= 59 ? '<span class="checkmark">✔</span>' : "";
            row.querySelector(".age-60-plus").innerHTML = age >= 60 ? '<span class="checkmark">✔</span>' : "";
        }

        // function addRow(data) {
        //     rowCount++;

        //     const disabilitasArray = Array.isArray(data.disabilitas) ? data.disabilitas : [];
        //     const kelompokRentanArray = Array.isArray(data.kelompok_rentan) ? data.kelompok_rentan : [];

        //     const desaText = $("#desa_id option:selected").text();
        //     const dusunText = $("#dusun_id option:selected").text();

        //     const disabilitasText = disabilitasArray.map((value) => {
        //         const option = $('#ModalTambahPeserta select[name="disabilitas"] option[value="' + value + '"]');
        //         const text = option.length ? option.text() : "";
        //         const randomColor = getRandomColor();
        //         return `<span class="badge badge-${randomColor}">${text}</span>`;
        //     });

        //     const kelompokRentanData = kelompokRentanArray.map((value) => {
        //         const option = $("#kelompok_rentan").select2("data").find((opt) => opt.id === value) || {
        //             id: value,
        //             text: value
        //         };
        //         return {
        //             id: option.id,
        //             text: option.text,
        //         };
        //     });

        //     const kelompokRentanText = kelompokRentanData.map((item) => {
        //         const randomColor = getRandomColor();
        //         return `<span class="badge badge-${randomColor}">${item.text}</span>`;
        //     });

        //     const genderText = $('#ModalTambahPeserta select[name="gender"] option[value="' + data.gender + '"]').text();
        //     const newRow = `
        //     <tr data-row-id="${rowCount}" class="nowrap">
        //         <td class="text-center align-middle d-none">${rowCount}</td>
        //         <td data-nama="${data.nama}" class="text-left align-middle">${data.nama}</td>
        //         <td data-gender="${data.gender}" class="text-center align-middle text-nowrap">${genderText}</td>
        //         <td data-disabilitas="${disabilitasArray.join(",")}" class="text-left align-middle text-wrap">${disabilitasText.join(", ")}</td>
        //         <td data-kelompok_rentan="${kelompokRentanArray.join(",")}" data-kelompok_rentan_full='${JSON.stringify(kelompokRentanData)}' class="text-left align-middle text-wrap">${kelompokRentanText.join(" ")}</td>
        //         <td data-rt="${data.rt}" class="text-center align-middle">${data.rt}</td>
        //         <td data-rw_banjar="${data.rw_banjar}" class="text-center align-middle">${data.rw_banjar}</td>
        //         <td data-dusun-id="${data.dusun_id}" data-dusun-nama="${dusunText}" class="text-center align-middle">${dusunText}</td>
        //         <td data-desa-id="${data.desa_id}" data-desa-nama="${desaText}" class="text-center align-middle">${desaText}</td>
        //         <td data-no_telp="${data.no_telp}" class="text-center align-middle">${data.no_telp}</td>
        //         <td data-jenis_kelompok="${data.jenis_kelompok}" class="text-center align-middle">${data.jenis_kelompok}</td>
        //         <td data-usia="${data.usia}" class="text-center align-middle usia-cell">${data.usia}</td>
        //         <td class="text-center align-middle age-0-17"></td>
        //         <td class="text-center align-middle age-18-24"></td>
        //         <td class="text-center align-middle age-25-59"></td>
        //         <td class="text-center align-middle age-60-plus"></td>
        //         <td class="text-center align-middle" id="headerActivityProgram" data-activity-selected="0"></td>
        //         <td class="text-center align-middle">
        //             <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
        //             <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
        //         </td>
        //     </tr>
        //     `;

        //     $("#tableBody").append(newRow);
        //     updateAgeCheckmarks($("#dataTable tbody").find(`tr[data-row-id="${rowCount}"]`).find(".usia-cell"));
        //     resetFormAdd();
        // }

        function addRow(data) {
            rowCount++;

            const disabilitasArray = Array.isArray(data.disabilitas) ? data.disabilitas : [];
            const kelompokRentanArray = Array.isArray(data.kelompok_rentan) ? data.kelompok_rentan : [];

            const desaText = $("#desa_id option:selected").text();
            const dusunText = $("#dusun_id option:selected").text();

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

            // Get the selected activities data
            // const selectedActivities = $("#activitySelect").val();
            const selectedActivities = $("#activitySelect").val() || [];
            
            // Get all activity headers
            const activityHeaders = $('#activityHeaders th.activity-header');
            

            // Prepare cells for each activity with data-id
            const activityCells = activityHeaders.map((index, header) => {
                const activityId = $(header).data('activity-id');
                const isChecked = selectedActivities.includes(activityId.toString());
                const checkmark = isChecked ? '✔️' : '';
                return `<td class="text-center align-middle" data-program-activity-id="${activityId}">${checkmark}</td>`;
            }).get().join('');

            const newRow = `
            <tr data-row-id="${rowCount}" class="nowrap">
                <td class="text-center align-middle d-none">${rowCount}</td>
                <td data-nama="${data.nama}" class="text-left align-middle">${data.nama}</td>
                <td data-gender="${data.gender}" class="text-center align-middle text-nowrap">${genderText}</td>
                <td data-disabilitas="${disabilitasArray.join(",")}" class="text-left align-middle text-wrap">${disabilitasText.join(", ")}</td>
                <td data-kelompok_rentan="${kelompokRentanArray.join(",")}" data-kelompok_rentan_full='${JSON.stringify(kelompokRentanData)}' class="text-left align-middle text-wrap">${kelompokRentanText.join(" ")}</td>
                <td data-rt="${data.rt}" class="text-center align-middle">${data.rt}</td>
                <td data-rw_banjar="${data.rw_banjar}" class="text-center align-middle">${data.rw_banjar}</td>
                <td data-dusun-id="${data.dusun_id}" data-dusun-nama="${dusunText}" class="text-center align-middle">${dusunText}</td>
                <td data-desa-id="${data.desa_id}" data-desa-nama="${desaText}" class="text-center align-middle">${desaText}</td>
                <td data-no_telp="${data.no_telp}" class="text-center align-middle">${data.no_telp}</td>
                <td data-jenis_kelompok="${data.jenis_kelompok}" class="text-center align-middle">${data.jenis_kelompok}</td>
                <td data-usia="${data.usia}" class="text-center align-middle usia-cell">${data.usia}</td>
                <td class="text-center align-middle age-0-17"></td>
                <td class="text-center align-middle age-18-24"></td>
                <td class="text-center align-middle age-25-59"></td>
                <td class="text-center align-middle age-60-plus"></td>
                ${activityCells}
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

                // Get the selected activities
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

            // const selectedActivities = [];

            const activityHeaders = $('#activityHeaders th.activity-header');

            const selectedActivities = activityHeaders.map((index, header) => {
                const activityId = $(header).data('activity-id');
                // Check if the cell has a data-program-activity-id that matches the header's activity-id
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
            console.info("Activities: ", selectedActivities)


            // Kemudian set nilai dan trigger change
            $("#editKelompokRentan").val(kelompokRentanValues).trigger("change");
            $("#editKelompokRentan").val(kelompokRentanValues).trigger("change");


            $("#editRowId").val(rowId);
            $("#editNama").val(currentRow.find("td[data-nama]").attr("data-nama"));
            $("#editGender").val(currentRow.find("td[data-gender]").attr("data-gender")).trigger("change");
            $("#editDisabilitas").val(disabilitasValues).trigger("change");
            $("#editRt").val(currentRow.find("td[data-rt]").attr("data-rt"));
            $("#editRwBanjar").val(currentRow.find("td[data-rw_banjar]").attr("data-rw_banjar"));
            $("#editDesa").append(new Option(desaNama, desaId, true, true)).trigger("change");
            $("#editDusun").append(new Option(dusunNama, dusunId, true, true)).trigger("change");

            $("#editNoTelp").val(currentRow.find("td[data-no_telp]").attr("data-no_telp"));
            $("#editJenisKelompok").val(currentRow.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok"));
            $("#editUsia").val(currentRow.find("td[data-usia]").attr("data-usia"));

            $("#editDataModal").modal("show");
        }

        function updateRow() {
            const rowId = $("#editRowId").val();
            const form = document.getElementById("editDataForm");

            const desaId = $("#editDesa").val();
            const desaText = $("#editDesa option:selected").text();
            const dusunId = $("#editDusun").val();
            const dusunText = $("#editDusun option:selected").text();

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

                currentRow.find("td[data-nama]").text(formData.nama).attr("data-nama", formData.nama);
                currentRow.find("td[data-gender]").text(genderText).attr("data-gender", formData.gender);

                currentRow.find("td[data-disabilitas]").html(formData.disabilitas.map((value) => {
                    const text = $('#editDisabilitas option[value="' + value + '"]').text();
                    const randomColor = getRandomColor();
                    return `<span class="badge badge-${randomColor}">${text}</span>`;
                }).join(" ")).attr("data-disabilitas", formData.disabilitas.join(","));


                currentRow.find("td[data-rt]").text(formData.rt).attr("data-rt", formData.rt);
                currentRow.find("td[data-rw_banjar]").text(formData.rw_banjar).attr("data-rw_banjar", formData.rw_banjar);
                currentRow.find("td[data-no_telp]").text(formData.no_telp).attr("data-no_telp", formData.no_telp);
                currentRow.find("td[data-jenis_kelompok]").text(formData.jenis_kelompok.join(", ")).attr("data-jenis_kelompok", formData.jenis_kelompok.join(","));
                currentRow.find("td[data-usia]").text(formData.usia).attr("data-usia", formData.usia);
                currentRow.find("td[data-desa-id]").attr("data-desa-id", desaId).attr("data-desa-nama", desaText).text(desaText);
                currentRow.find("td[data-dusun-id]").attr("data-dusun-id", dusunId).attr("data-dusun-nama", dusunText).text(dusunText);

                currentRow.find("td[data-kelompok_rentan]").html(kelompokRentanHtml).attr("data-kelompok_rentan", kelompokRentanData.map((item) => item.id).join(",")).attr("data-kelompok_rentan_full", JSON.stringify(kelompokRentanData));

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
                        rw_banjar: row.find("td[data-rw_banjar]").attr("data-rw_banjar"),
                        dusun_id: row.find("td[data-dusun-id]").attr("data-dusun-id"),
                        desa_id: row.find("td[data-desa-id]").attr("data-desa-id"),
                        no_telp: row.find("td[data-no_telp]").attr("data-no_telp"),
                        jenis_kelompok: row.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok"),
                        usia: row.find("td[data-usia]").attr("data-usia"),
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
