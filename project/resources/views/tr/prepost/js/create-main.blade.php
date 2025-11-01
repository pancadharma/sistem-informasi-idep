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

            initializeLocationSelects();
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


        function addRow(data) {
            rowCount++;

            const provinsiText = $("#pilihprovinsi_id option:selected").text();
            const kabupatenText = $("#pilihkabupaten_id option:selected").text();
            const kecamatanText = $("#pilihkecamatan_id option:selected").text();
            const desaText = $("#pilihdesa_id option:selected").text();
            const dusunText = $("#pilihdusun_id option:selected").text();
            const prefillText = $("#prefill option:selected").text();
            const postfillText = $("#postfill option:selected").text();
            const genderText = $("#gender option:selected").text();

            const selisih = parseInt(data.posttest || 0) - parseInt(data.pretest || 0); // Hitung selisih skor

            const newRow = `
                <tr data-row-id="${rowCount}" class="nowrap">
                    <td class="text-center align-middle d-none">${rowCount}</td>
                    <td data-nama="${data.nama}" class="text-center align-middle">${data.nama}</td>
                    <td data-gender="${data.gender}" class="text-center align-middle">${genderText}</td>
                    <td data-no_telp="${data.no_telp}" class="text-center align-middle">${data.no_telp}</td>

                    <td data-provinsi-id="${data.pilihprovinsi_id}" data-provinsi-nama="${provinsiText}" class="text-center align-middle">${provinsiText}</td>
                    <td data-kabupaten-id="${data.pilihkabupaten_id}" data-kabupaten-nama="${kabupatenText}" class="text-center align-middle">${kabupatenText}</td>
                    <td data-kecamatan-id="${data.pilihkecamatan_id}" data-kecamatan-nama="${kecamatanText}" class="text-center align-middle">${kecamatanText}</td>
                    <td data-desa-id="${data.pilihdesa_id}" data-desa-nama="${desaText}" class="text-center align-middle">${desaText}</td>
                    <td data-dusun-id="${data.pilihdusun_id}" data-dusun-nama="${dusunText}" class="text-center align-middle">${dusunText}</td>

                    <td data-pretest="${data.pretest}" class="text-center align-middle">${data.pretest}</td>
                    <td data-prefill="${data.prefill}" class="text-center align-middle">${prefillText}</td>

                    <td data-posttest="${data.posttest}" class="text-center align-middle">${data.posttest}</td>
                    <td data-postfill="${data.postfill}" class="text-center align-middle">${postfillText}</td>

                    <td data-selisih="${selisih}" class="text-center align-middle">${selisih}</td>
                    <td data-notes="${data.notes}" class="text-center align-middle">${data.notes}</td>

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
                const formData = {
                    nama: $("#nama").val(),
                    gender: $("#gender").val(),
                    no_telp: $("#no_telp").val(),
                    pilihprovinsi_id: $("#pilihprovinsi_id").val(),
                    pilihkabupaten_id: $("#pilihkabupaten_id").val(),
                    pilihkecamatan_id: $("#pilihkecamatan_id").val(),
                    pilihdesa_id: $("#pilihdesa_id").val(),
                    pilihdusun_id: $("#pilihdusun_id").val(),
                    pretest: $("#pretest").val(),
                    prefill: $("#prefill").val(),
                    posttest: $("#posttest").val(),
                    postfill: $("#postfill").val(),
                    notes: $("#notes").val()
                };

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
            $("#gender").val(null).trigger("change");
            $("#prefill").val(null).trigger("change");
            $("#postfill").val(null).trigger("change");

            // Reset input text dan angka
            $("input[name='nama']").val("");
            $("input[name='notelp']").val("");
            $("input[name='pretest']").val("");
            $("input[name='posttest']").val("");
            $("input[name='notes']").val("");

            // Sembunyikan modal setelah reset
            $("#ModalTambah").modal("hide");
        }

        function resetFormEdit() {
            $("#editDataForm")[0].reset();
            // Reset Select2 dropdowns
            $("#pilihprovinsi_id").val(null).trigger("change");
            $("#pilihkabupaten_id").val(null).trigger("change");
            $("#pilihkecamatan_id").val(null).trigger("change");
            $("#pilihdesa_id").val(null).trigger("change");
            $("#pilihdusun_id").val(null).trigger("change");
            $("#gender").val(null).trigger("change");
            $("#prefill").val(null).trigger("change");
            $("#postfill").val(null).trigger("change");

            // Reset input text dan angka
            $("input[name='nama']").val("");
            $("input[name='notelp']").val("");
            $("input[name='prescore']").val("");
            $("input[name='postscore']").val("");
            $("input[name='keterangan']").val("");

            // Sembunyikan modal setelah reset
            $("#editDataModal").modal("hide");
        }

        function editRow(row) {
            const currentRow = $(row).closest("tr");
            const rowId = currentRow.data("row-id");

            // Nama & No Telp
            $("#editnama").val(currentRow.find("td[data-nama]").attr("data-nama") || "");
            $("#editno_telp").val(currentRow.find("td[data-no_telp]").attr("data-no_telp") || "");

            // Gender
            const gender = currentRow.find("td[data-gender]").attr("data-gender") || "";
            $("#editgender").val(gender).trigger("change");

            // Lokasi (provinsi, kabupaten, kecamatan, desa, dusun)
            const locationFields = ["provinsi", "kabupaten", "kecamatan", "desa", "dusun"];
            locationFields.forEach(field => {
                const id = currentRow.find(`td[data-${field}-id]`).attr(`data-${field}-id`) || 0;
                const name = currentRow.find(`td[data-${field}-nama]`).attr(`data-${field}-nama`) || "-";
                const select = $(`#edit${field}_id`);
                select.empty().append(new Option(name, id, true, true)).trigger('change');
            });

            // Pretest & Prefill
            $("#editpretest").val(currentRow.find("td[data-pretest]").attr("data-pretest") || "");
            $("#editprefill").val(currentRow.find("td[data-prefill]").attr("data-prefill")).trigger("change");

            // Posttest & Postfill
            $("#editposttest").val(currentRow.find("td[data-posttest]").attr("data-posttest") || "");
            $("#editpostfill").val(currentRow.find("td[data-postfill]").attr("data-postfill")).trigger("change");

            // Notes
            $("#editnotes").val(currentRow.find("td[data-notes]").attr("data-notes") || "");

            // Simpan ID baris ke hidden input
            $("#editRowId").val(rowId);

            // Tampilkan modal edit
            $("#editDataModal").modal("show");
        }

        function updateRow() {
            const rowId = $("#editRowId").val();
            const form = document.getElementById("editDataForm");

            if (!form) {
                console.error("Edit form not found");
                return;
            }

            if (form.checkValidity()) {
                // Ambil nilai dari form edit
                const formData = {
                    nama: $("#editnama").val(),
                    gender: $("#editgender").val(),
                    genderText: $("#editgender option:selected").text(),
                    no_telp: $("#editno_telp").val(),
                    pretest: $("#editpretest").val(),
                    prefill: $("#editprefill").val(),
                    prefillText: $("#editprefill option:selected").text(),
                    posttest: $("#editposttest").val(),
                    postfill: $("#editpostfill").val(),
                    postfillText: $("#editpostfill option:selected").text(),
                    notes: $("#editnotes").val(),
                    provinsiId: $("#editprovinsi_id").val(),
                    provinsiText: $("#editprovinsi_id option:selected").text(),
                    kabupatenId: $("#editkabupaten_id").val(),
                    kabupatenText: $("#editkabupaten_id option:selected").text(),
                    kecamatanId: $("#editkecamatan_id").val(),
                    kecamatanText: $("#editkecamatan_id option:selected").text(),
                    desaId: $("#editdesa_id").val(),
                    desaText: $("#editdesa_id option:selected").text(),
                    dusunId: $("#editdusun_id").val(),
                    dusunText: $("#editdusun_id option:selected").text()
                };

                // Hitung selisih
                const selisih = parseInt(formData.posttest || 0) - parseInt(formData.pretest || 0);

                // Update data di baris tabel
                const currentRow = $("#dataTable tbody").find(`tr[data-row-id="${rowId}"]`);
                if (currentRow.length === 0) {
                    console.error("Row not found");
                    return;
                }

                currentRow.find("td[data-nama]").text(formData.nama).attr("data-nama", formData.nama);
                currentRow.find("td[data-gender]").text(formData.genderText).attr("data-gender", formData.gender);
                currentRow.find("td[data-no_telp]").text(formData.no_telp).attr("data-no_telp", formData.no_telp);

                const locationFields = ["provinsi", "kabupaten", "kecamatan", "desa", "dusun"];
                locationFields.forEach(field => {
                    const id = formData[`${field}Id`];
                    const name = formData[`${field}Text`];
                    currentRow.find(`td[data-${field}-id]`)
                        .text(name)
                        .attr(`data-${field}-id`, id)
                        .attr(`data-${field}-nama`, name);
                });

                currentRow.find("td[data-pretest]").text(formData.pretest).attr("data-pretest", formData.pretest);
                currentRow.find("td[data-prefill]").text(formData.prefillText).attr("data-prefill", formData.prefill);
                currentRow.find("td[data-posttest]").text(formData.posttest).attr("data-posttest", formData.posttest);
                currentRow.find("td[data-postfill]").text(formData.postfillText).attr("data-postfill", formData.postfill);
                currentRow.find("td[data-selisih]").text(selisih).attr("data-selisih", selisih);
                currentRow.find("td[data-notes]").text(formData.notes).attr("data-notes", formData.notes);

                // Reset form dan tutup modal
                resetFormEdit();
                $("#editDataModal").modal("hide");
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

        function prosesSimpanDataPreposttest({ activity_id, nama_pelatihan, start_date, end_date}) {
            const userId = $("#user_id").val();
            const tableData = [];

            $("#dataTable tbody tr").each(function () {
                const row = $(this);

                const rowData = {
                    nama: row.find("td[data-nama]").attr("data-nama"),
                    gender: row.find("td[data-gender]").attr("data-gender"),
                    no_telp: row.find("td[data-no_telp]").attr("data-no_telp"),
                    pretest: row.find("td[data-pretest]").attr("data-pretest"),
                    prefill: row.find("td[data-prefill]").attr("data-prefill"),
                    posttest: row.find("td[data-posttest]").attr("data-posttest"),
                    postfill: row.find("td[data-postfill]").attr("data-postfill"),
                    selisih: row.find("td[data-selisih]").attr("data-selisih"),
                    notes: row.find("td[data-notes]").attr("data-notes"),
                    provinsi_id: row.find("td[data-provinsi-id]").attr("data-provinsi-id"),
                    kabupaten_id: row.find("td[data-kabupaten-id]").attr("data-kabupaten-id"),
                    kecamatan_id: row.find("td[data-kecamatan-id]").attr("data-kecamatan-id"),
                    desa_id: row.find("td[data-desa-id]").attr("data-desa-id"),
                    dusun_id: row.find("td[data-dusun-id]").attr("data-dusun-id"),
                };

                tableData.push(rowData);
            });

            if (tableData.length === 0) {
                Swal.fire({
                    title: "Error",
                    text: "Tidak ada data peserta yang bisa disimpan!",
                    icon: "error",
                    timer: 1500,
                    timerProgressBar: true,
                });
                return;
            }

            const submitData = {
                user_id: userId,
                programoutcomeoutputactivity_id: activity_id,
                nama_pelatihan,
                start_date,
                end_date,
                data: tableData
            };

            $.ajax({
                url: "{{ route('prepost.store') }}",
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
                        //location.reload();
                        window.location.href = "{{ route('prepost.index') }}";
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
                const activityId = $("#programoutcomeoutputactivity_id").val();
                const namaPelatihan = $("#nama_pelatihan").val();
                const startDate = $("#start_date").val();
                const endDate = $("#end_date").val();

                if (!activityId || !namaPelatihan || !startDate || !endDate) {
                    Swal.fire({
                        title: "Error",
                        text: "Harap lengkapi semua isian: kegiatan, nama pelatihan, dan tanggal!",
                        icon: "error",
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    return;
                }

                Swal.fire({
                    title: "Yakin ingin menyimpan?",
                    text: "Data pelatihan akan disimpan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, simpan!",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        prosesSimpanDataPreposttest({
                            activity_id: activityId,
                            nama_pelatihan: namaPelatihan,
                            start_date: startDate,
                            end_date: endDate,
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
