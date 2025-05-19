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
            // initializeSelect2ForProvinsi();
            // initializeSelect2ForKabupaten();
            // initializeSelect2ForKecamatan();
            // initializeSelect2ForDesa();
            // initializeSelect2ForDusun();
            initializeSatuan();
            initializeLocationSelects();

            // handleLokasiChange("#pilihprovinsi_id", "#pilihkabupaten_id", "#pilihkecamatan_id", "#pilihdesa_id", "#pilihdusun_id");
            // handleLokasiChange("#editprovinsi_id", "#editkabupaten_id", "#editkecamatan_id", "#editdesa_id", "#editdusun_id");

            
            // initalizeJenisKelompok(); // initialize the select for jenis kelompok
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

        function initializeLocationSelects(provinsiSelector, kabupatenSelector, kecamatanSelector, desaSelector, dusunSelector, dropdownParent) {
            // Fungsi bantu reset + disable
            const resetSelect = selector => {
                $(selector).val(null).empty().trigger('change').prop('disabled', true);
            };

            // Disable semua jika provinsi belum dipilih
            if (!$(provinsiSelector).val()) {
                resetSelect(kabupatenSelector);
                resetSelect(kecamatanSelector);
                resetSelect(desaSelector);
                resetSelect(dusunSelector);
            }

            $(provinsiSelector).select2({
                ajax: {
                    url: "{{ route('api.komodel.prov') }}",
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
            }).on('change', function () {
                resetSelect(kabupatenSelector);
                resetSelect(kecamatanSelector);
                resetSelect(desaSelector);
                resetSelect(dusunSelector);

                if ($(this).val()) {
                    $(kabupatenSelector).prop('disabled', false);
                }
            });

            $(kabupatenSelector).select2({
                ajax: {
                    url: () => "{{ route('api.komodel.kab', ['id' => ':id']) }}".replace(':id', $(provinsiSelector).val()),
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
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: "{{ __('global.selectKab') }}"
            }).on('change', function () {
                resetSelect(kecamatanSelector);
                resetSelect(desaSelector);
                resetSelect(dusunSelector);

                if ($(this).val()) {
                    $(kecamatanSelector).prop('disabled', false);
                }
            });

            $(kecamatanSelector).select2({
                ajax: {
                    url: () => "{{ route('api.komodel.kec', ['id' => ':id']) }}".replace(':id', $(kabupatenSelector).val()),
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
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: "{{ __('global.selectKec') }}"
            }).on('change', function () {
                resetSelect(desaSelector);
                resetSelect(dusunSelector);

                if ($(this).val()) {
                    $(desaSelector).prop('disabled', false);
                }
            });

            $(desaSelector).select2({
                ajax: {
                    url: () => "{{ route('api.komodel.desa', ['id' => ':id']) }}".replace(':id', $(kecamatanSelector).val()),
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
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}',
            }).on('change', function () {
                resetSelect(dusunSelector);

                if ($(this).val()) {
                    $(dusunSelector).prop('disabled', false);
                }
            });

            $(dusunSelector).select2({
                ajax: {
                    url: () => "{{ route('api.komodel.dusun', ['id' => ':id']) }}".replace(':id', $(desaSelector).val()),
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
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.dusun.title') }}',
            });
        }

        initializeLocationSelects(
            '#pilihprovinsi_id',
            '#pilihkabupaten_id',
            '#pilihkecamatan_id',
            '#pilihdesa_id',
            '#pilihdusun_id',
            $('#ModalTambah')
        );

        // Initialize for #editDataModal
        initializeLocationSelects(
            '#editprovinsi_id',
            '#editkabupaten_id',
            '#editkecamatan_id',
            '#editdesa_id',
            '#editdusun_id',
            $('#editDataModal')
        );


        // function addRow(data) {
        //     rowCount++;

        //     const provinsiId = $("#pilihprovinsi_id").val() || 0;
        //     const kabupatenId = $("#pilihkabupaten_id").val() || 0;
        //     const kecamatanId = $("#pilihkecamatan_id").val() || 0;
        //     const desaId = $("#pilihdesa_id").val() || 0;
        //     const dusunId = $("#pilihdusun_id").val() || 0;
        //     const satuanId = $("#satuan_id").val() || 0;

        //     const provinsiText = $("#pilihprovinsi_id option:selected").text();
        //     const kabupatenText = $("#pilihkabupaten_id option:selected").text();
        //     const kecamatanText = $("#pilihkecamatan_id option:selected").text();
        //     const desaText = $("#pilihdesa_id option:selected").text();
        //     const dusunText = $("#pilihdusun_id option:selected").text();
        //     const satuanText = $("#satuan_id option:selected").text();
            

        //     const newRow = `
        //     <tr data-row-id="${rowCount}" class="nowrap">
        //         <td class="text-center align-middle d-none">${rowCount}</td>
        //         <td data-provinsi-id="${data.provinsiId}" data-provinsi-nama="${provinsiText}" class="text-center align-middle">${provinsiText}</td>
        //         <td data-kabupaten-id="${data.kabupatenId}" data-kabupaten-nama="${kabupatenText}" class="text-center align-middle">${kabupatenText}</td>
        //         <td data-kecamatan-id="${data.kecamatanId}" data-kecamatan-nama="${kecamatanText}" class="text-center align-middle">${kecamatanText}</td>
        //         <td data-desa-id="${data.desaId}" data-desa-nama="${desaText}" class="text-center align-middle">${desaText}</td>
        //         <td data-dusun-id="${data.dusunId}" data-dusun-nama="${dusunText}" class="text-center align-middle">${dusunText}</td>
        //         <td data-long="${data.long}" class="text-center align-middle">${data.long}</td>
        //         <td data-lat="${data.lat}" class="text-center align-middle">${data.lat}</td>
        //         <td data-jumlah="${data.jumlah}" class="text-center align-middle">${data.jumlah}</td>
        //         <td data-satuan-id="${data.satuanId}" data-satuan-nama="${satuanText}" class="text-center align-middle">${satuanText}</td>
        //         <td class="text-center align-middle">
        //             <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
        //             <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
        //         </td>
        //     </tr>
        //     `;

        //     $("#tableBody").append(newRow);
        //     resetFormAdd();
        // }
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
                <td data-provinsi-id="${data.provinsiId}" data-provinsi-nama="${provinsiText}" class="text-center align-middle">${provinsiText}</td>
                <td data-kabupaten-id="${data.kabupatenId}" data-kabupaten-nama="${kabupatenText}" class="text-center align-middle">${kabupatenText}</td>
                <td data-kecamatan-id="${data.kecamatanId}" data-kecamatan-nama="${kecamatanText}" class="text-center align-middle">${kecamatanText}</td>
                <td data-desa-id="${data.desaId}" data-desa-nama="${desaText}" class="text-center align-middle">${desaText}</td>
                <td data-dusun-id="${data.dusunId}" data-dusun-nama="${dusunText}" class="text-center align-middle">${dusunText}</td>
                <td data-long="${data.long}" class="text-center align-middle">${data.long}</td>
                <td data-lat="${data.lat}" class="text-center align-middle">${data.lat}</td>
                <td data-jumlah="${data.jumlah}" class="text-center align-middle">${data.jumlah}</td>
                <td data-satuan-id="${data.satuanId}" data-satuan-nama="${satuanText}" class="text-center align-middle">${satuanText}</td>
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
                </td>
            </tr>
            `;

            $("#tableBody").append(newRow);
            resetFormAdd();
        }


        //>>>>>>validasi dan simpan baris ke tabel<<<<<<<//
        //>>>>>>validasi keknya blm jalan hanya simpan baris ke table<<<<<<<//
        // function saveRow() {
        //     const form = $("#dataForm")[0];

        //     if (form.checkValidity()) {
        //         const formData = $("#dataForm").serializeArray().reduce((obj, item) => {
        //             if (obj[item.name]) {
        //                 if (!Array.isArray(obj[item.name])) {
        //                     obj[item.name] = [obj[item.name]];
        //                 }
        //                 obj[item.name].push(item.value);
        //             } else {
        //                 obj[item.name] = item.value;
        //             }
        //             return obj;
        //         }, {});

        //         addRow(formData);
        //         resetFormAdd();
        //     } else {
        //         form.reportValidity();
        //     }
        // }

        function saveRow() {
            const form = $("#dataForm")[0];

            if (form.checkValidity()) {
                const formData = {
                    provinsiId: $("#pilihprovinsi_id").val(),
                    kabupatenId: $("#pilihkabupaten_id").val(),
                    kecamatanId: $("#pilihkecamatan_id").val(),
                    desaId: $("#pilihdesa_id").val(),
                    dusunId: $("#pilihdusun_id").val(),
                    satuanId: $("#satuan_id").val(),
                    jumlah: $("#jumlah").val(),
                    long: $("#long").val(),
                    lat: $("#lat").val()
                };

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
                const id = currentRow.find(`td[data-${field}-id]`).attr(`data-${field}-id`) || 0;
                const name = currentRow.find(`td[data-${field}-nama]`).attr(`data-${field}-nama`) || "-";
                const select = $(`#edit${field}_id`);
                select.empty().append(new Option(name, id, true, true)).trigger('change');
            });

            // Satuan sebagai Select2
            const satuanId = currentRow.find("td[data-satuan-id]").attr("data-satuan-id") || null;
            const satuanNama = currentRow.find("td[data-satuan-nama]").attr("data-satuan-nama") || "-";
            $("#editsatuan_id").empty().append(new Option(satuanNama, satuanId, true, true)).trigger("change");

            // Input angka & koordinat pake id
            $("#editjumlah").val(currentRow.find("td[data-jumlah]").attr("data-jumlah") || "");
            $("#editlat").val(currentRow.find("td[data-lat]").attr("data-lat") || "");
            $("#editlong").val(currentRow.find("td[data-long]").attr("data-long") || "");

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

        // function to save form data
        // function prosesSimpanDataKomodel(metadata) {
        //     const tableData = [];
        //     $("#dataTable tbody tr").each(function () {
        //         const row = $(this);
        //         const activityHeaders = $('#activityHeaders th.activity-header');
        //         const selectedActivities = activityHeaders.map((index, header) => {
        //             const activityId = $(header).data('activity-id');
        //             const cell = row.find(`td[data-program-activity-id="${activityId}"]`);
        //             return cell.text().trim() === '√' ? activityId.toString() : null;
        //         }).get().filter(Boolean);

        //         const rowData = {
        //             nama: row.find("td[data-nama]").attr("data-nama"),
        //             gender: row.find("td[data-gender]").attr("data-gender"),
        //             is_head_family: row.find("td[data-is_head_family]").attr("data-is_head_family") === "true",
        //             head_family_name: row.find("td[data-head_family_name]").attr("data-head_family_name"),
        //             kelompok_rentan: row.find("td[data-kelompok_rentan]").attr("data-kelompok_rentan").split(",").filter(Boolean),
        //             rt: row.find("td[data-rt]").attr("data-rt"),
        //             rw: row.find("td[data-rw]").attr("data-rw"),
        //             dusun_id: row.find("td[data-dusun-id]").attr("data-dusun-id"),
        //             desa_id: row.find("td[data-desa-id]").attr("data-desa-id"),
        //             no_telp: row.find("td[data-no_telp]").attr("data-no_telp"),
        //             jenis_kelompok: row.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok").split(",").filter(Boolean),
        //             usia: row.find("td[data-usia]").attr("data-usia"),
        //             is_non_activity: row.find("td[data-is_non_activity]").attr("data-is_non_activity") === "true",
        //             keterangan: row.find("td[data-keterangan]").attr("data-keterangan"),
        //             activitySelect: selectedActivities,
        //         };
        //         tableData.push(rowData);
        //     });

        //     if (tableData.length === 0) {
        //         Swal.fire({
        //             title: "Error",
        //             text: "No data to submit! Please add at least one row.",
        //             icon: "error",
        //             timer: 1500,
        //             timerProgressBar: true,
        //         });
        //         return;
        //     }

        //     const submitData = {
        //         program_id: metadata.program_id,
        //         model_id: metadata.model_id,
        //         sektor_id: metadata.sektor_id, // array
        //         nama_program: metadata.nama_program,
        //         data: tableData,
        //     };

        //     $.ajax({
        //         url: "{{ route('komodel.store') }}",
        //         method: "POST",
        //         data: JSON.stringify(submitData),
        //         contentType: "application/json",
        //         headers: {
        //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        //         },
        //         beforeSend: function () {
        //             Swal.fire({
        //                 title: "Submitting...",
        //                 text: "Please wait while the data is being processed.",
        //                 allowOutsideClick: false,
        //                 didOpen: () => {
        //                     Swal.showLoading();
        //                 },
        //             });
        //         },
        //         success: function (response) {
        //             Swal.fire({
        //                 title: "Success",
        //                 text: response.message || "Data saved successfully!",
        //                 icon: "success",
        //                 timer: 1500,
        //                 timerProgressBar: true,
        //             }).then(() => {
        //                 $("#dataTable tbody").empty();
        //                 $("#program_id").val("");
        //                 $("#model_id").val("").trigger('change');
        //                 $("#sektor_id").val(null).trigger('change');
        //                 $("#nama_program").val("");
        //                 if (response.data?.redirect_url) {
        //                     window.location.href = response.data.redirect_url;
        //                 } else {
        //                     window.location.href = "{{ route('beneficiary.index') }}";
        //                 }
        //             });
        //         },
        //         error: function (xhr) {
        //             let errorMessage = "An error occurred while submitting the data.";

        //             if (xhr.status === 422 && xhr.responseJSON.errors) {
        //                 errorMessage = Object.values(xhr.responseJSON.errors).flat().join("\n");
        //             } else if (xhr.responseJSON?.message) {
        //                 errorMessage = xhr.responseJSON.message;
        //             }

        //             Swal.fire({
        //                 title: "Validation Error",
        //                 text: errorMessage,
        //                 icon: "error",
        //                 width: '40em',
        //                 timer: 5000,
        //                 timerProgressBar: true,
        //             });
        //         }
        //     });
        // }

        function prosesSimpanDataKomodel({ program_id, model_id, sektor_id }) {
            const userId = $("#user_id").val();
            const tableData = [];
            let totalJumlah = 0;

            $("#dataTable tbody tr").each(function () {
                const row = $(this);

                const jumlah = parseFloat(row.find("td[data-jumlah]").attr("data-jumlah")) || 0;
                totalJumlah += jumlah;

                const lokasiData = {
                    provinsi_id: row.find("td[data-provinsi-id]").attr("data-provinsi-id"),
                    kabupaten_id: row.find("td[data-kabupaten-id]").attr("data-kabupaten-id"),
                    kecamatan_id: row.find("td[data-kecamatan-id]").attr("data-kecamatan-id"),
                    desa_id: row.find("td[data-desa-id]").attr("data-desa-id"),
                    dusun_id: row.find("td[data-dusun-id]").attr("data-dusun-id"),
                    long: row.find("td[data-long]").attr("data-long"),
                    lat: row.find("td[data-lat]").attr("data-lat"),
                    jumlah: jumlah,
                    satuan_id: row.find("td[data-satuan-id]").attr("data-satuan-id"),
                };

                tableData.push(lokasiData);
            });

            if (tableData.length === 0) {
                Swal.fire({
                    title: "Error",
                    text: "Tidak ada data lokasi yang bisa disimpan!",
                    icon: "error",
                    timer: 1500,
                    timerProgressBar: true,
                });
                return;
            }

            const submitData = {
                program_id,
                user_id: userId,
                komponenmodel_id: model_id,
                sektor_ids: sektor_id,
                totaljumlah: totalJumlah,
                data: tableData
            };

            //console.log("submitData", submitData);

            $.ajax({
                url: "{{ route('komodel.store') }}",
                method: "POST",
                data: JSON.stringify(submitData),
                contentType: "application/json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                beforeSend: function () {
                    Swal.fire({
                        title: "Menyimpan...",
                        text: "Mohon tunggu data sedang diproses.",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                },
                success: function (response) {
                    Swal.fire({
                        title: "Sukses",
                        text: response.message || "Data berhasil disimpan!",
                        icon: "success",
                        timer: 1500,
                        timerProgressBar: true,
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    let errorMessage = "Terjadi kesalahan saat menyimpan data.";
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join("\n");
                    } else if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        title: "Error",
                        text: errorMessage,
                        icon: "error",
                        width: '40em',
                        timer: 5000,
                        timerProgressBar: true,
                    });
                }
            });
        }
        

        function bindEvents() {
            // $("#desa_id, #editDesa").on("change", function() {
            //     const targetDusun = $(this).attr("id") === "desa_id" ? "#dusun_id" : "#editDusun";
            //     $(targetDusun).val(null).trigger("change");
            // });

            //>>>>>>>>>>>>>>>>>validasi sebelum menambah data<<<<<<<<<<<<<<<<<<<<<//
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
            
            //>>>>>>>>>>>>>>>>>tombol simpan detail data<<<<<<<<<<<<<<<<<<<<<//
            $("#saveDataBtn").on("click", function(e) { 
                e.preventDefault();
                saveRow();
            });
            //>>>>>>>>>>>>>>>>>tombol edit detail data<<<<<<<<<<<<<<<<<<<<<//
            $("#dataTable tbody").on("click", ".edit-btn", function(e) {
                e.preventDefault();
                // console.log(this);
                editRow(this);
            });
            //>>>>>>>>>>>>>>>>>tombol update detail data<<<<<<<<<<<<<<<<<<<<<//
            $("#updateDataBtn").on("click", function(e) {
                e.preventDefault();
                updateRow();
            });
            //>>>>>>>>>>>>>>>>>tombol hapus detail data<<<<<<<<<<<<<<<<<<<<<//
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
                const modelId = $("#model_id").val();
                const sektorId = $("#sektor_id").val(); // Select2 multiple (array)
                const namaProgram = $("#nama_program").val();

                if (!programId || !modelId || !sektorId || sektorId.length === 0 || !namaProgram) {
                    Swal.fire({
                        title: "Error",
                        text: "Please fill all required fields: Program, Model, Sektor, and Nama Program!",
                        icon: "error",
                        timer: 2000,
                        timerProgressBar: true,
                    });
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
                        // Kirim semua ID ke fungsi submit
                        prosesSimpanDataKomodel({
                            program_id: programId,
                            model_id: modelId,
                            sektor_id: sektorId,
                        });
                    }
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
