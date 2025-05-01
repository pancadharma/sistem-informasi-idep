<script>
    $(document).ready(function () {
        $('#saveKomponenBtn').click(function (e) {
            e.preventDefault(); // Mencegah refresh halaman

            let formData = {
                nama: $('#namaKomponen').val(),
            };

            $.ajax({
                url: "{{ route('api.komodel.komponen.store') }}", // Sesuai dengan route API
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                        });

                        // Tutup modal setelah berhasil
                        $('#ModalTambahKomponen').modal('hide');

                        // Reset form
                        $('#formTambahKomponen')[0].reset();

                        // Reload data tabel (jika ada)
                        // $('#yourDataTable').DataTable().ajax.reload();
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Terjadi kesalahan!";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage,
                    });
                }
            });
        });
    });

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

            // initializeSelect2ForKelompokRentan();
            initializeSelect2ForProvinsi();
            initializeSelect2ForKabupaten();
            initializeSelect2ForKecamatan();
            initializeSelect2ForDesa();
            initializeSelect2ForDusun();
            initializeSatuan();
            
            // initalizeJenisKelompok(); // initialize the select for jenis kelompok
        }


        function initializeSelect2ForProvinsi() {
            $("#pilihprovinsi_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.provinsi.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.prov') }}',
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
                dropdownParent: $("#ModalTambah"),
                width: "100%",
            });

            $("#editProvinsi").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.provinsi.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.prov') }}',
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

        function initializeSelect2ForKabupaten() {
            $("#pilihkabupaten_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kabupaten.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.kab') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            provinsi_id: $("#pilihprovinsi_id").val() || $("#editProvinsi").val(),
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
                dropdownParent: $("#ModalTambah"),
                width: "100%",
                // minimumInputLength: 2,
            });

            $("#editKabupaten").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kabupaten.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.prov') }}',
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

        function initializeSelect2ForKecamatan() {
            $("#pilihkecamatan_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kecamatan.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.kec') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kabupaten_id: $("#pilihkabupaten_id").val() || $("#editKabupaten").val(),
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
                dropdownParent: $("#ModalTambah"),
                width: "100%",
                // minimumInputLength: 2,
            });

            $("#editKecamatan").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kecamatan.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.kec') }}',
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

        function initializeSelect2ForDesa() {
            $("#pilihdesa_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.desa') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kecamatan_id: $("#pilihkecamatan_id").val() || $("#editKecamatan").val(),
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
                dropdownParent: $("#ModalTambah"),
                width: "100%",
                // minimumInputLength: 2,
            });

            $("#editDesa").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.desa') }}',
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
            $("#pilihdusun_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.dusun.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.dusun') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            desa_id: $("#pilihdesa_id").val() || $("#editDesa").val(),
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
                dropdownParent: $("#ModalTambah"),
                width: "100%",
                // minimumInputLength: 2,
            });

            $("#editDusun").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.dusun.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.dusun') }}',
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

        function initializeSatuan() 
        {
            let placeholder = '{{ __('global.pleaseSelect') . ' ' . __('cruds.satuan.title') }}';
            
            $("#satuan_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.satuan.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.satuan') }}',
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
                dropdownParent: $("#ModalTambah"),
                width: "100%",
            });
        }


        function addRow(data) {
            rowCount++;

            const provinsiText = $("#pilihprovinsi_id option:selected").text();
            const kabupatenText = $("#pilihkabupaten_id option:selected").text();
            const kecamatanText = $("#pilihkecamatan_id option:selected").text();
            const desaText = $("#pilihdesa_id option:selected").text();
            const dusunText = $("#pilihdusun_id option:selected").text();
            const satuanText = $("#satuan_id option:selected").text();

            const newRow = `
            <tr data-row-id="${rowCount}" class="nowrap">
                <td class="text-center align-middle d-none">${rowCount}</td>
                <td data-provinsi-id="${data.pilihprovinsi_id}" data-provinsi-nama="${provinsiText}" class="text-center align-middle">${provinsiText}</td>
                <td data-kabupaten-id="${data.pilihkabupaten_id}" data-kabupaten-nama="${kabupatenText}" class="text-center align-middle">${kabupatenText}</td>
                <td data-kecamatan-id="${data.pilihkecamatan_id}" data-kecamatan-nama="${kecamatanText}" class="text-center align-middle">${kecamatanText}</td>
                <td data-desa-id="${data.pilihdesa_id}" data-desa-nama="${desaText}" class="text-center align-middle">${desaText}</td>
                <td data-dusun-id="${data.pilihdusun_id}" data-dusun-nama="${dusunText}" class="text-center align-middle">${dusunText}</td>
                <td data-no_telp="${data.long}" class="text-center align-middle">${data.long}</td>
                <td data-no_telp="${data.lat}" class="text-center align-middle">${data.lat}</td>
                <td data-no_telp="${data.jumlah}" class="text-center align-middle">${data.jumlah}</td>
                <td data-satuan-id="${data.satuan_id}" data-satuan-nama="${satuanText}" class="text-center align-middle">${satuanText}</td>
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}" hidden><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
                </td>
            </tr>
            `;

            $("#tableBody").append(newRow);
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

            // Reset Select2 dropdowns
            $("#pilihprovinsi_id").val(null).trigger("change");
            $("#pilihkabupaten_id").val(null).trigger("change");
            $("#pilihkecamatan_id").val(null).trigger("change");
            $("#pilihdesa_id").val(null).trigger("change");
            $("#pilihdusun_id").val(null).trigger("change");
            $("#satuan_id").val(null).trigger("change");

            // Reset input text dan angka
            $("input[name='long']").val("");
            $("input[name='lat']").val("");
            $("input[name='jumlah']").val("");

            // Sembunyikan modal setelah reset
            $("#ModalTambah").modal("hide");
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
            $("#editRwBanjar").val(currentRow.find("td[data-rw_banjar]").attr("data-rw_banjar"));
            $("#editDesa").append(new Option(desaNama, desaId, true, true)).trigger("change");
            $("#editDusun").append(new Option(dusunNama, dusunId, true, true)).trigger("change");
            $("#editJenisKelompok").append(new Option(jenisKelompokNama, jenisKelompokId, true, true)).trigger("change");
            // $("#editJenisKelompok").val(currentRow.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok"));

            $("#editNoTelp").val(currentRow.find("td[data-no_telp]").attr("data-no_telp"));
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
                // Update activities in the row
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
                currentRow.find("td[data-rw_banjar]").text(formData.rw_banjar).attr("data-rw_banjar", formData.rw_banjar);
                currentRow.find("td[data-no_telp]").text(formData.no_telp).attr("data-no_telp", formData.no_telp);
                // currentRow.find("td[data-jenis_kelompok]").text(formData.jenis_kelompok.join(", ")).attr("data-jenis_kelompok", formData.jenis_kelompok.join(","));
                currentRow.find("td[data-usia]").text(formData.usia).attr("data-usia", formData.usia);
                currentRow.find("td[data-desa-id]").attr("data-desa-id", desaId).attr("data-desa-nama", desaText).text(desaText);
                currentRow.find("td[data-dusun-id]").attr("data-dusun-id", dusunId).attr("data-dusun-nama", dusunText).text(dusunText);
                currentRow.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok", jenisKelompokId).attr("data-jenis_kelompok-text", jenisKelompokText).text(jenisKelompokText);

                currentRow.find("td[data-kelompok_rentan]").html(kelompokRentanHtml).attr("data-kelompok_rentan", kelompokRentanData.map((item) => item.id).join(",")).attr("data-kelompok_rentan_full", JSON.stringify(kelompokRentanData));


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

                $("#ModalTambah").modal("show");
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

        $("#ModalTambah, #editDataModal").on("shown.bs.modal", function() {
            $(this).removeAttr("inert");
        });

        $("#ModalTambah, #editDataModal").on("hide.bs.modal", function(e) {
            $(this).attr("inert", "");
            $(document.activeElement).blur();
        });

        loadSelect2Option();
        bindEvents();
    });
</script>
