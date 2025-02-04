<script>
    'use strict';

// Declare jQuery variable if it's not globally defined.  This is a safer approach than assuming it will always be available.
let $ = jQuery;

if (typeof jQuery === "undefined") {
    console.error("jQuery is not included. Please include jQuery in your HTML file.");
    // Consider adding a more user-friendly error message or fallback
}

(function($) {
    let rowCount = 0;
    let editDataForm = null;

    const loadSelect2Option = () => {
        const kelompokRentanOption = [
            {id: '1', text: 'Anak-anak'},
            {id: '2', text: 'Lansia'},
            {id: '3', text: 'Ibu Hamil'},
            {id: '4', text: 'Penyandang Disabilitas'},
            {id: '5', text: 'Minoritas'}
        ];

        const disabilitasOptions = [
            {id: 'Fisik', text: 'Fisik'},
            {id: 'Sensorik', text: 'Sensorik'},
            {id: 'Intelektual', text: 'Intelektual'},
            {id: 'Mental', text: 'Mental'},
            {id: 'Ganda', text: 'Ganda'}
        ];

        $('.select2-multiple').select2({
            data: kelompokRentanOption,
            dropdownParent: $('#ModalTambahPeserta'),
            width: '100%'
        });

        $('#disabilitas').select2({
            data: disabilitasOptions,
            dropdownParent: $('#ModalTambahPeserta'),
            width: '100%'
        });

        $('#editKelompokRentan').select2({
            data: kelompokRentanOption,
            dropdownParent: $('#editDataModal'),
            width: '100%'
        });

        $('#editDisabilitas').select2({
            data: disabilitasOptions,
            dropdownParent: $('#editDataModal'),
            width: '100%'
        });

        $(`#desa_id`).select2({
            placeholder: '{{ __("global.pleaseSelect") ." ". __("cruds.desa.title") }}',
            allowClear: true,
            ajax: {
                url: '{{ route("api.beneficiary.desa") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
            dropdownParent: $("#ModalTambahPeserta"),
        });

        $(`#editDesa`).select2({
            placeholder: '{{ __("global.pleaseSelect") ." ". __("cruds.desa.title") }}',
            allowClear: true,
            ajax: {
                url: '{{ route("api.beneficiary.desa") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
            dropdownParent: $("#editDataModal"),
        });

        $("#dusun_id").select2({
            placeholder: '{{ __("global.pleaseSelect") ." ". __("cruds.dusun.title") }}',
            ajax: {
                url: '{{ route("api.beneficiary.dusun") }}',
                dataType: "json",
                delay: 250,
                data: (params) => ({
                    search: params.term,
                    desa_id: $("#desa_id").val() || $("#editDesa").val(),
                    page: params.page || 1,
                }),
                processResults: function (data) {
                    return {
                        results: data.results,
                        pagination: data.pagination,
                    }
                },
                cache: true,
            },
            minimumInputLength: 0,
            allowClear: true,
            dropdownParent: $('#ModalTambahPeserta'),
        });

        $("#editDusun").select2({
            placeholder: '{{ __("global.pleaseSelect") ." ". __("cruds.dusun.title") }}',
            ajax: {
                url: '{{ route("api.beneficiary.dusun") }}',
                dataType: "json",
                delay: 250,
                data: (params) => ({
                    search: params.term,
                    desa_id: $("#desa_id").val() || $("#editDesa").val(),
                    page: params.page || 1,
                }),
                processResults: function (data) {
                    return {
                        results: data.results,
                        pagination: data.pagination,
                    }
                },
                cache: true,
            },
            minimumInputLength: 0,
            allowClear: true,
            dropdownParent: $('#editDataModal'),
        });
    };

    const updateAgeCheckmarks = (usiaCell) => {
        const row = usiaCell.closest('tr')[0];
        const age = parseInt(usiaCell.text().trim(), 10);

        const ageRanges = [
            { selector: '.age-0-17', range: [0, 17] },
            { selector: '.age-18-24', range: [18, 24] },
            { selector: '.age-25-59', range: [25, 59] },
            { selector: '.age-60-plus', range: [60, Infinity] }
        ];

        ageRanges.forEach(({ selector, range }) => {
            row.querySelector(selector).innerHTML =
                (age >= range[0] && age <= range[1]) ? '<span class="checkmark">✔</span>' : '';
        });
    };

    const closeModal = () => {
        $('#ModalTambahPeserta').modal('hide');
    };

    const addRow = (data) => {
        rowCount++;
        let disabilitasText = [];
        let kelompokRentanText = [];

        const disabilitasArray = Array.isArray(data.disabilitas) ? data.disabilitas : [];
        const kelompokRentanArray = Array.isArray(data.kelompok_rentan) ? data.kelompok_rentan : [];

        const desaText = $("#desa_id option:selected").text();
        const dusunText = $("#dusun_id option:selected").text();

        disabilitasText = disabilitasArray.map(value => {
            const option = $('#ModalTambahPeserta select[name="disabilitas"] option[value="' + value + '"]');
            return option.length ? option.text() : '';
        });

        kelompokRentanText = kelompokRentanArray.map(value => {
            const option = $('#ModalTambahPeserta select[name="kelompok_rentan"] option[value="' + value + '"]');
            return option.length ? option.text() : '';
        });

        const genderText = $('#ModalTambahPeserta select[name="gender"] option[value="' + data.gender + '"]').text();

        const newRow = `
        <tr data-row-id="${rowCount}" class="nowrap">
            <td class="text-center align-middle d-none">${rowCount}</td>
            <td data-nama="${data.nama}" class="text-center align-middle">${data.nama}</td>
            <td data-gender="${data.gender}" class="text-center align-middle">${genderText}</td>
            <td data-disabilitas="${data.disabilitas.join(',')}" class="text-left align-middle">${disabilitasText.join(', ')}</td>
            <td data-kelompok_rentan="${data.kelompok_rentan.join(',')}" class="text-left align-middle">${kelompokRentanText.join(', ')}</td>
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
            <td class="text-center align-middle">
                <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
                <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
            </td>
        </tr>
        `;
        $('#tableBody').append(newRow);
        updateAgeCheckmarks($('#dataTable tbody').find(`tr[data-row-id="${rowCount}"]`).find('.usia-cell'));
    };

    const saveRow = () => {
        try {
            const form = $('#dataForm')[0];
            if (form.checkValidity()) {
                const formData = $('#dataForm').serializeArray().reduce((obj, item) => {
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

                formData.disabilitas = Array.isArray(formData.disabilitas) ? formData.disabilitas : [formData.disabilitas];
                formData.kelompok_rentan = Array.isArray(formData.kelompok_rentan) ? formData.kelompok_rentan : [formData.kelompok_rentan];

                addRow(formData);
                $('#ModalTambahPeserta').modal('hide');
                $('#dataForm')[0].reset();
                $('.select2-multiple').val(null).trigger('change');
                $('.select2').val(null).trigger('change');
                $('#disabilitas').val(null).trigger('change');
            } else {
                form.reportValidity();
            }
        } catch (error) {
            console.error('Error saving row:', error);
            // Display user-friendly error message
        }
    };

    const editRow = (row) => {
        const currentRow = $(row).closest('tr');
        const rowId = currentRow.data('row-id');

        const disabilitas = currentRow.find('td:eq(3)').attr('data-disabilitas');
        const disabilitasValues = disabilitas ? disabilitas.split(',') : [];

        const kelompok_rentan = currentRow.find('td:eq(4)').attr('data-kelompok_rentan');
        const kelompokRentanValues = kelompok_rentan ? kelompok_rentan.split(',') : [];

        const desaId = currentRow.find("td[data-desa-id]").data("desa-id");
        const desaNama = currentRow.find("td[data-desa-id]").data("desa-nama");
        const dusunId = currentRow.find("td[data-dusun-id]").data("dusun-id");
        const dusunNama = currentRow.find("td[data-dusun-id]").data("dusun-nama");

        $('#editRowId').val(rowId);
        $('#editNama').val(currentRow.find('td:eq(1)').attr('data-nama'));
        $('#editGender').val(currentRow.find('td:eq(2)').attr('data-gender')).trigger('change');
        $('#editDisabilitas').val(disabilitasValues).trigger('change');
        $('#editKelompokRentan').val(kelompokRentanValues).trigger('change');
        $('#editRt').val(currentRow.find('td:eq(5)').attr('data-rt'));
        $('#editRwBanjar').val(currentRow.find('td:eq(6)').attr('data-rw_banjar'));

        $("#editDesa").append(new Option(desaNama, desaId, true, true)).trigger("change");
        $("#editDusun").append(new Option(dusunNama, dusunId, true, true)).trigger("change");

        $('#editNoTelp').val(currentRow.find('td:eq(9)').attr('data-no_telp'));
        $('#editJenisKelompok').val(currentRow.find('td:eq(10)').attr('data-jenis_kelompok'));
        $('#editUsia').val(currentRow.find('td:eq(11)').attr('data-usia'));
    };

    const updateRow = () => {
        const rowId = $('#editRowId').val();
        const form = document.getElementById('editDataForm');

        const desaId = $("#editDesa").val();
        const desaText = $("#editDesa option:selected").text();
        const dusunId = $("#editDusun").val();
        const dusunText = $("#editDusun option:selected").text();

        if (!form) {
            console.error('Edit form not found');
            return;
        }

        if (form.checkValidity()) {
            const formData = $('#editDataForm').serializeArray().reduce((obj, item) => {
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

            const kelompokRentanText = Array.isArray(formData.kelompok_rentan)
                ? formData.kelompok_rentan.map(value => {
                    const option = $('#editKelompokRentan option[value="' + value + '"]');
                    return option.length ? option.text() : '';
                }).join(', ')
                : $('#editKelompokRentan option[value="' + formData.kelompok_rentan + '"]').text();

            const genderText = $('#editGender option[value="' + formData.gender + '"]').text();
            const disabilitasText = Array.isArray(formData.disabilitas)
                ? formData.disabilitas.map(value => {
                    const option = $('#editDisabilitas option[value="' + value + '"]');
                    return option.length ? option.text() : '';
                }).join(', ')
                : $('#editDisabilitas option[value="' + formData.disabilitas + '"]').text();

            const currentRow = $('#dataTable tbody').find(`tr[data-row-id="${rowId}"]`);
            if (currentRow.length === 0) {
                console.error('Row not found');
                return;
            }

            currentRow.find('td:eq(1)').text(formData.nama).attr('data-nama', formData.nama);
            currentRow.find('td:eq(2)').text(genderText).attr('data-gender', formData.gender);
            currentRow.find('td:eq(3)').text(disabilitasText).attr('data-disabilitas', formData.disabilitas);
            currentRow.find('td:eq(4)').text(kelompokRentanText).attr('data-kelompok_rentan', formData.kelompok_rentan);
            currentRow.find('td:eq(5)').text(formData.rt).attr('data-rt', formData.rt);
            currentRow.find('td:eq(6)').text(formData.rw_banjar).attr('data-rw_banjar', formData.rw_banjar);
            currentRow.find('td:eq(9)').text(formData.no_telp).attr('data-no_telp', formData.no_telp);
            currentRow.find('td:eq(10)').text(formData.jenis_kelompok).attr('data-jenis_kelompok', formData.jenis_kelompok);
            currentRow.find('td:eq(11)').text(formData.usia).attr('data-usia', formData.usia);

            currentRow.find("td[data-desa-id]").attr("data-desa-id", desaId).attr("data-desa-nama", desaText).text(desaText);
            currentRow.find("td[data-dusun-id]").attr("data-dusun-id", dusunId).attr("data-dusun-nama", dusunText).text(dusunText);

            updateAgeCheckmarks(currentRow.find('.usia-cell'));

            $('#editDataModal').modal('hide');

            $('.select2-multiple').val(null).trigger('change');
            form.reset();
        } else {
            form.reportValidity();
        }
    };

    const deleteRow = (row) => {
        $(row).closest('tr').remove();
    };

    const fetchDusunData = async (params) => {
        try {
            const response = await $.ajax({
                url: '{{ route("api.beneficiary.dusun") }}',
                data: params,
                dataType: 'json'
            });
            return {
                results: response.results,
                pagination: response.pagination
            };
        } catch (error) {
            console.error('Error fetching dusun data:', error);
            return { results: [], pagination: {} };
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        loadSelect2Option();

        // $("#desa_id, #editDesa").on("change", function () {
        //     const dusunSelect = $(this).attr("id") === "desa_id" ? "#dusun_id" : "#editDusun";
        //     $(dusunSelect).val(null).trigger("change");
        //     $(dusunSelect).select2("open");
        //     $(dusunSelect).select2("close");
        // });

        $("#desa_id, #editDesa").on("change", function () {
            const targetDusun = $(this).attr("id") === "desa_id" ? "#dusun_id" : "#editDusun";
            $(targetDusun).val(null).trigger("change"); // Clear Dusun selection
        });

        $("#addDataBtn").on("click", function() {
            $('#ModalTambahPeserta').modal('show');
            $('.select2-multiple').select2({
                dropdownParent: $('#ModalTambahPeserta'),
                width: '100%',
                placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
            });
        });

        $("#saveDataBtn").on("click", function(e) {
            e.preventDefault();
            saveRow();
        });

        $("#dataForm").on("submit", function(e) {
            e.preventDefault();
            const form = $(this)[0];
            if (form.checkValidity()) {
                saveRow();
            } else {
                form.reportValidity();
            }
        });

        $(document).on('click', '.edit-btn', function(e) {
            e.preventDefault();
            editRow(this);
            $('#editDataModal').modal('show');
            $('#editKelompokRentan').select2({
                dropdownParent: $('#editDataModal'),
                width: '100%'
            });
        });

        $('#updateDataBtn').on('click', function(e) {
            e.preventDefault();
            updateRow();
        });

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteRow(this);
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                }
            });
            return false;
        });
    });

    $('#ModalTambahPeserta, #editDataModal').on('shown.bs.modal', function() {
        $(this).removeAttr('inert');
    });

    $('#ModalTambahPeserta, #editDataModal').on('hide.bs.modal', function(e) {
        $(this).attr('inert', '');
        $(document.activeElement).blur();
    });

})(jQuery);
</script>
