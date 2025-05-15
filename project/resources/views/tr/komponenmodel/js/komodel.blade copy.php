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

            handleLokasiChange("#pilihprovinsi_id", "#pilihkabupaten_id", "#pilihkecamatan_id", "#pilihdesa_id", "#pilihdusun_id");
            handleLokasiChange("#editprovinsi_id", "#editkabupaten_id", "#editkecamatan_id", "#editdesa_id", "#editdusun_id");

            
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
                    cache: false,
                },
                dropdownParent: $("#ModalTambah"),
                width: "100%",
            });

            $("#editprovinsi_id").select2({
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
                    cache: false,
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
                            provinsi_id: $("#pilihprovinsi_id").val() || $("#editprovinsi_id").val(),
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

            $("#editkabupaten_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kabupaten.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.kab') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            provinsi_id: $("#pilihprovinsi_id").val() || $("#editprovinsi_id").val(),
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
                            kabupaten_id: $("#pilihkabupaten_id").val() || $("#editkabupaten_id").val(),
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

            $("#editkecamatan_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kecamatan.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.kec') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kabupaten_id: $("#pilihkabupaten_id").val() || $("#editkabupaten_id").val(),
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
                            kecamatan_id: $("#pilihkecamatan_id").val() || $("#editkecamatan_id").val(),
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

            $("#editdesa_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.desa') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kecamatan_id: $("#pilihkecamatan_id").val() || $("#editkecamatan_id").val(),
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
                            desa_id: $("#pilihdesa_id").val() || $("#editdesa_id").val(),
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

            $("#editdusun_id").select2({
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.dusun.title') }}',
                ajax: {
                    url: '{{ route('api.komodel.dusun') }}',
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            desa_id: $("#pilihdesa_id").val() || $("#editdesa_id").val(),
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

        //handler lokasi enable / disable
        function handleLokasiChange(provinsiID, kabupatenID, kecamatanID, desaID, dusunID) {
            const $provinsi = $(provinsiID);
            const $kabupaten = $(kabupatenID);
            const $kecamatan = $(kecamatanID);
            const $desa = $(desaID);
            const $dusun = $(dusunID);

            // Fungsi bantu: reset & disable untuk Select2
            function resetAndDisable(...$elements) {
                $elements.forEach($el => {
                    if ($el && $el.length) {
                        $el.prop("disabled", true)
                        .val(null)
                        .empty()
                        .append(new Option('-- Pilih --', ''))
                        .trigger("change");
                    }
                });
            }

            // Inisialisasi awal
            if (!$provinsi.val()) resetAndDisable($kabupaten, $kecamatan, $desa, $dusun);
            if (!$kabupaten.val()) resetAndDisable($kecamatan, $desa, $dusun);
            if (!$kecamatan.val()) resetAndDisable($desa, $dusun);
            if (!$desa.val()) resetAndDisable($dusun);

            // Handler cascading
            $provinsi.off("change").on("change", function () {
                const adaProvinsi = $(this).val();
                if (adaProvinsi) {
                    resetAndDisable($kabupaten, $kecamatan, $desa, $dusun);
                    $kabupaten.prop("disabled", false);
                } else {
                    resetAndDisable($kabupaten, $kecamatan, $desa, $dusun);
                }
            });

            $kabupaten.off("change").on("change", function () {
                const adaKabupaten = $(this).val();
                if (adaKabupaten) {
                    resetAndDisable($kecamatan, $desa, $dusun);
                    $kecamatan.prop("disabled", false);
                } else {
                    resetAndDisable($kecamatan, $desa, $dusun);
                }
            });

            $kecamatan.off("change").on("change", function () {
                const adaKecamatan = $(this).val();
                if (adaKecamatan) {
                    resetAndDisable($desa, $dusun);
                    $desa.prop("disabled", false);
                } else {
                    resetAndDisable($desa, $dusun);
                }
            });

            $desa.off("change").on("change", function () {
                const adaDesa = $(this).val();
                if (adaDesa) {
                    resetAndDisable($dusun);
                    $dusun.prop("disabled", false);
                } else {
                    resetAndDisable($dusun);
                }
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
                    cache: false,
                },
                dropdownParent: $("#ModalTambah"),
                width: "100%",
            });

            $("#editsatuan_id").select2({
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
                    cache: false,
                },
                dropdownParent: $("#editDataModal"),
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
                <td data-long="${data.long}" class="text-center align-middle">${data.long}</td>
                <td data-lat="${data.lat}" class="text-center align-middle">${data.lat}</td>
                <td data-jumlah="${data.jumlah}" class="text-center align-middle">${data.jumlah}</td>
                <td data-satuan-id="${data.satuan_id}" data-satuan-nama="${satuanText}" class="text-center align-middle">${satuanText}</td>
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
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
            // Reset input biasa
            $("#dataForm")[0].reset();

            // Reset semua Select2 (harus setelah .reset())
            const selectsToReset = [
                "#pilihprovinsi_id",
                "#pilihkabupaten_id",
                "#pilihkecamatan_id",
                "#pilihdesa_id",
                "#pilihdusun_id",
                "#satuan_id"
            ];

            selectsToReset.forEach(id => {
                $(id).val(null).empty().trigger("change");
            });

            // Reset input text dan angka (optional karena sudah pakai .reset())
            $("input[name='long']").val("");
            $("input[name='lat']").val("");
            $("input[name='jumlah']").val("");

            // Tutup modal
            $("#ModalTambah").modal("hide");
        }

        function resetFormEdit() {
            $("#editDataForm")[0].reset();

            // Reset Select2 dropdowns
            $("#editprovinsi_id").val(null).empty().trigger("change");
            $("#editkabupaten_id").val(null).empty().trigger("change");
            $("#editkecamatan_id").val(null).empty().trigger("change");
            $("#editdesa_id").val(null).empty().trigger("change");
            $("#editdusun_id").val(null).empty().trigger("change");
            $("#editsatuan_id").val(null).empty().trigger("change");

            // Reset input text dan angka
            $("input[name='long']").val("");
            $("input[name='lat']").val("");
            $("input[name='jumlah']").val("");

            // Sembunyikan modal setelah reset
            $("#editDataModal").modal("hide");
        }
        //>>>>>>edit baris<<<<<<<//
        // Edit button click event
        function editRow(row) {
            
            const currentRow = $(row).closest("tr");
            const rowId = currentRow.data("row-id");

           
            // Lokasi: provinsi, kabupaten, kecamatan, desa, dusun
            const locationFields = ["provinsi", "kabupaten", "kecamatan", "desa", "dusun"];
            locationFields.forEach(field => {
                const id = currentRow.find(`td[data-${field}-id]`).data(`${field}-id`) || 0;
                const name = currentRow.find(`td[data-${field}-nama]`).data(`${field}-nama`) || "-";
                const select = $(`#edit${field}_id`);
                select.empty().append(new Option(name, id, true, true)).trigger('change');
            });

            // Satuan sebagai Select2
            const satuanId = currentRow.find("td[data-satuan-id]").data("satuan-id") || null;
            const satuanNama = currentRow.find("td[data-satuan-nama]").data("satuan-nama") || "-";
            $("#editsatuan_id").empty().append(new Option(satuanNama, satuanId, true, true)).trigger("change");

            // Input angka & koordinat pake id
            $("#jumlah").val(currentRow.find("td[data-jumlah]").data("jumlah") || "");
            $("#lat").val(currentRow.find("td[data-lat]").data("lat") || "");
            $("#long").val(currentRow.find("td[data-long]").data("long") || "");

            //console.log("Editing row with row-id:", rowId);
            // Simpan index baris
            //$("#editRowId").val(currentRow.index());
            $("#editRowId").val(rowId); // simpan data-row-id, bukan .index()

            $("#editDataModal").modal("show");
        }

        //>>>>>>simpan perubahan ke baris<<<<<<<//

        function updateRow() {
            
            const rowId = $("#editRowId").val();
            //const rowIndex = $("#editRowId").val();
            const form = document.getElementById("editDataForm");

            if (!form) {
                console.error("Form tidak ditemukan");
                return;
            }

            if (form.checkValidity()) {
                // Ambil data dari form
                const formData = $("#editDataForm").serializeArray().reduce((obj, item) => {
                    obj[item.name] = item.value;
                    return obj;
                }, {});

                // Ambil lokasi
                const locationFields = ["provinsi", "kabupaten", "kecamatan", "desa", "dusun"];
                const lokasiData = {};

                locationFields.forEach(field => {
                    const id = $(`#edit${field}_id`).val();
                    const text = $(`#edit${field}_id option:selected`).text();
                    lokasiData[field] = { id, text };
                });

                // Ambil data satuan
                const satuanId = $("#editsatuan_id").val();
                const satuanText = $("#editsatuan_id option:selected").text();

                // Cari baris berdasarkan index
                //const currentRow = $("#dataTable tbody").find("tr").eq(rowIndex);
                //const currentRow = $("#dataTable tbody").find(`tr[data-row-id="${rowId}"]`);
                // console.log("rowId:", rowId);
                // console.log("Matching row length:", $("#tableBody").find(`tr[data-row-id='${rowId}']`).length);

                const currentRow = $("#tableBody").find(`tr[data-row-id='${rowId}']`);
                if (currentRow.length === 0) {
                    console.error("Baris tidak ditemukan");
                    return;
                }

                // Update lokasi
                locationFields.forEach(field => {
                    currentRow.find(`td[data-${field}-id]`)
                        .attr(`data-${field}-id`, lokasiData[field].id)
                        .attr(`data-${field}-nama`, lokasiData[field].text)
                        .text(lokasiData[field].text);
                });

                // Update satuan
                currentRow.find("td[data-satuan-id]")
                    .attr("data-satuan-id", satuanId)
                    .attr("data-satuan-nama", satuanText)
                    .text(satuanText);

                // Update jumlah, lat, long
                currentRow.find("td[data-jumlah]")
                    .attr("data-jumlah", formData.jumlah)
                    .text(formData.jumlah);

                currentRow.find("td[data-lat]")
                    .attr("data-lat", formData.lat)
                    .text(formData.lat);

                currentRow.find("td[data-long]")
                    .attr("data-long", formData.long)
                    .text(formData.long);

                // Reset dan tutup modal
                $("#editDataModal").modal("hide");
                form.reset();

                // Reset Select2
                locationFields.forEach(field => $(`#edit${field}_id`).val(null).trigger("change"));
                $("#editsatuan_id").val(null).trigger("change");
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

            $("#addDataBtn").on("click", function () {
                const programId = $("#program_id").val();
                const modelId = $("#model_id").val();
                const sektorId = $("#sektor_id").val();

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

                if (!modelId || modelId === "" || modelId.length === 0) {
                    Swal.fire({
                        text: '{{ __('global.pleaseSelect') . ' Model' }}',
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

                    $("#model_id").select2('open');
                    return false;
                }

                if (!sektorId || sektorId === "" || sektorId.length === 0) {
                    Swal.fire({
                        text: '{{ __('global.pleaseSelect') . ' Sektor' }}',
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

                    $("#sektor_id").select2('open');
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
