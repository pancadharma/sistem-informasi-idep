<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

if (typeof $ === "undefined") {
        console.error("jQuery is not included. Please include jQuery in your HTML file.")
}
    let rowCount = 0;
    let editDataForm = null; // Initialize as null

    function loadSelect2Option() {
        const disabilitasOptions = [
            {id: 'Fisik', text: 'Fisik'},
            {id: 'Sensorik', text: 'Sensorik'},
            {id: 'Intelektual', text: 'Intelektual'},
            {id: 'Mental', text: 'Mental'},
            {id: 'Ganda', text: 'Ganda'}
        ];

        // Initialize Select2 for Kelompok Rentan in add modal
        $('#kelompok_rentan').select2({
            placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
            dropdownParent: $('#ModalTambahPeserta'),
            width: '100%',
            closeOnSelect: false,
            allowClear: true,
            ajax: {
                url: '{{ route("api.beneficiary.kelompok.rentan") }}',
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
        });

        // Initialize Select2 for Kelompok Rentan for edit modal
        $('#editKelompokRentan').select2({
            allowClear: true,
            ajax: {
                url: '{{ route("api.beneficiary.kelompok.rentan") }}',
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
            dropdownParent: $('#editDataModal'),
            width: '100%',
            closeOnSelect: false,
            placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
        });

        $('#disabilitas').select2({
            data: disabilitasOptions,
            closeOnSelect: false,
            placeholder: '{{ __("global.select") ." ". __("cruds.beneficiary.penerima.disability") }} ...',
            dropdownParent: $('#ModalTambahPeserta'),
            width: '100%'
        });


        $('#editDisabilitas').select2({
            data: disabilitasOptions,
            placeholder: '{{ __("global.select") ." ". __("cruds.beneficiary.penerima.disability") }} ...',
            dropdownParent: $('#editDataModal'),
            width: '100%'
        });

        // Select2 modal for desa and dusun

        $(`#desa_id`).select2({
            placeholder: '{{ __("global.pleaseSelect") ." ". __("cruds.desa.title") }}',
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
            dropdownPositionOption: 'below',
            dropdownParent: $("#ModalTambahPeserta"),
        });

        $(`#editDesa`).select2({
            placeholder: '{{ __("global.pleaseSelect") ." ". __("cruds.desa.title") }}',
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
            dropdownPositionOption: 'below',
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
            dropdownPositionOption: 'below',
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
        })
    }

    function initializeSelect2ForKelompokRentan() {
        $('#kelompok_rentan').select2({
            placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
            dropdownParent: $('#ModalTambahPeserta'),
            width: '100%',
            allowClear: true,
            ajax: {
                url: '{{ route("api.beneficiary.kelompok.rentan") }}',
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
        });
    }

    function loadSelect2EditKelompokRentan(){
        $('#editKelompokRentan').select2({
            multiple: true,
            ajax: {
                url: '{{ route("api.beneficiary.kelompok.rentan") }}',
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
            dropdownParent: $('#editDataModal'),
            width: '80%',
            placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
        });
    }
    // END SELECT2 INITIALIZATION

    function updateAgeCheckmarks(usiaCell) {
        const row = usiaCell.closest('tr')[0];
        const ageText = usiaCell.text().trim();
        const age = parseInt(ageText, 10);

        // Clear all checkmarks if invalid age
        if (isNaN(age)) {
            row.querySelector('.age-0-17').innerHTML = '';
            row.querySelector('.age-18-24').innerHTML = '';
            row.querySelector('.age-25-59').innerHTML = '';
            row.querySelector('.age-60-plus').innerHTML = '';
            return;
        }

        // Proceed with valid age
        row.querySelector('.age-0-17').innerHTML = (age >= 0 && age <= 17) ? '<span class="checkmark">✔</span>' : '';
        row.querySelector('.age-18-24').innerHTML = (age > 17 && age <= 24) ? '<span class="checkmark">✔</span>' : '';
        row.querySelector('.age-25-59').innerHTML = (age >= 25 && age <= 59) ? '<span class="checkmark">✔</span>' : '';
        row.querySelector('.age-60-plus').innerHTML = (age >= 60) ? '<span class="checkmark">✔</span>' : '';
    }

    function closeModal() {
        $('#ModalTambahPeserta').modal('hide');
    }

    function getRandomColor() {
        const colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
        const randomIndex = Math.floor(Math.random() * colors.length);
        return colors[randomIndex];
    }

    function addRow(data) {
        rowCount++;

        const disabilitasArray = Array.isArray(data.disabilitas) ? data.disabilitas : [];
        const kelompokRentanArray = Array.isArray(data.kelompok_rentan) ? data.kelompok_rentan : [];

        const desaText = $("#desa_id option:selected").text();
        const dusunText = $("#dusun_id option:selected").text();

        const disabilitasText = disabilitasArray.map(value => {
            const option = $('#ModalTambahPeserta select[name="disabilitas"] option[value="' + value + '"]');
            const text = option.length ? option.text() : '';
            const randomColor = getRandomColor();
            return `<span class="badge badge-${randomColor}">${text}</span>`;
        });

        const kelompokRentanText = kelompokRentanArray.map(value => {
            const option = $('#ModalTambahPeserta select[name="kelompok_rentan"] option[value="' + value + '"]');
            const text = option.length ? option.text() : '';
            const randomColor = getRandomColor();
            return `<span class="badge badge-${randomColor}">${text}</span>`;
        });

        const genderText = $('#ModalTambahPeserta select[name="gender"] option[value="' + data.gender + '"]').text();
        const newRow = `
        <tr data-row-id="${rowCount}" class="nowrap">
            <td class="text-center align-middle d-none">${rowCount}</td>
            <td data-nama="${data.nama}" class="text-left align-middle">${data.nama}</td>
            <td data-gender="${data.gender}" class="text-center align-middle text-nowrap">${genderText}</td>
            <td data-disabilitas="${disabilitasArray.join(',')}" class="text-left align-middle text-wrap">${disabilitasText.join(', ')}</td>
            <td data-kelompok_rentan="${kelompokRentanArray.join(',')}" class="text-left align-middle text-wrap">${kelompokRentanText.join(' ')}</td>
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
            <td class="text-center align-middle" id="headerActivityProgram" data-activity-selected="0"></td>
            <td class="text-center align-middle">
                <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
                <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
            </td>
        </tr>
        `;

        $('#tableBody').append(newRow);

        updateAgeCheckmarks($('#dataTable tbody').find(`tr[data-row-id="${rowCount}"]`).find('.usia-cell'));
        resetFormAdd();
    }

    function saveRow() {
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
            if (Array.isArray(formData.disabilitas)) {
                formData.disabilitas = formData.disabilitas;
            } else {
                formData.disabilitas = [formData.disabilitas];
            }

            if (Array.isArray(formData.kelompok_rentan)) {
                formData.kelompok_rentan = formData.kelompok_rentan;
            } else {
                formData.kelompok_rentan = [formData.kelompok_rentan];
            }
            addRow(formData);

            resetFormAdd();
        } else {
            form.reportValidity();
        }
    }

    function resetFormAdd() {
        $('#dataForm')[0].reset();
        $('#kelompok_rentan').val(null).trigger('change');
        $('#kelompok_rentan').select2('destroy').empty();
        initializeSelect2ForKelompokRentan();
        $('#disabilitas').val(null).trigger('change');
        $('.select2-multiple').val(null).trigger('change');
        $('.select2').val(null).trigger('change');
        $('#ModalTambahPeserta').modal('hide');
    }

    function editRow(row) {
        const currentRow = $(row).closest('tr');
        const rowId = currentRow.data('row-id');

        // Get stored values from data attributes
        const disabilitas = currentRow.find('td[data-disabilitas]').attr('data-disabilitas');
        const disabilitasValues = disabilitas ? disabilitas.split(',') : [];

        const kelompok_rentan = currentRow.find('td[data-kelompok_rentan]').attr('data-kelompok_rentan');
        const kelompokRentanValues = kelompok_rentan ? kelompok_rentan.split(',') : [];

        const desaId = currentRow.find("td[data-desa-id]").data("desa-id");
        const desaNama = currentRow.find("td[data-desa-id]").data("desa-nama");
        const dusunId = currentRow.find("td[data-dusun-id]").data("dusun-id");
        const dusunNama = currentRow.find("td[data-dusun-id]").data("dusun-nama");

        $('#editRowId').val(rowId);
        $('#editNama').val(currentRow.find('td[data-nama]').attr('data-nama'));
        $('#editGender').val(currentRow.find('td[data-gender]').attr('data-gender')).trigger('change');

        $('#editDisabilitas').val(null).trigger('change');
        if (disabilitasValues.length > 0) {
            $('#editDisabilitas').val(disabilitasValues).trigger('change');
        }

        $('#editKelompokRentan').val(null).trigger('change');
        if (kelompokRentanValues.length > 0) {
            const newOptions = kelompokRentanValues.map(value => new Option(value, value, true, true));
            $('#editKelompokRentan').append(newOptions).trigger('change');
        }

        $('#editRt').val(currentRow.find('td[data-rt]').attr('data-rt'));
        $('#editRwBanjar').val(currentRow.find('td[data-rw_banjar]').attr('data-rw_banjar'));

        $("#editDesa").append(new Option(desaNama, desaId, true, true)).trigger("change");
        $("#editDusun").append(new Option(dusunNama, dusunId, true, true)).trigger("change");

        $('#editNoTelp').val(currentRow.find('td[data-no_telp]').attr('data-no_telp'));
        $('#editJenisKelompok').val(currentRow.find('td[data-jenis_kelompok]').attr('data-jenis_kelompok'));
        $('#editUsia').val(currentRow.find('td[data-usia]').attr('data-usia'));

        $('#editDataModal').modal('show');
    }

    function updateRow() {
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

            const genderText = $('#editGender option:selected').text();

            // Generate badge HTML for kelompok_rentan
            const kelompokRentanHtml = Array.isArray(formData.kelompok_rentan)
                ? formData.kelompok_rentan.map(value => {
                    const text = $('#editKelompokRentan option[value="' + value + '"]').text();
                    const randomColor = getRandomColor();
                    return `<span class="badge badge-${randomColor}">${text}</span>`;
                }).join(' ')
                : (() => {
                    const text = $('#editKelompokRentan option:selected').text();
                    const randomColor = getRandomColor();
                    return `<span class="badge badge-${randomColor}">${text}</span>`;
                })();

            // Generate badge HTML for disabilitas
            const disabilitasHtml = Array.isArray(formData.disabilitas)
                ? formData.disabilitas.map(value => {
                    const text = $('#editDisabilitas option[value="' + value + '"]').text();
                    const randomColor = getRandomColor();
                    return `<span class="badge badge-${randomColor}">${text}</span>`;
                }).join(' ')
                : (() => {
                    const text = $('#editDisabilitas option:selected').text();
                    const randomColor = getRandomColor();
                    return `<span class="badge badge-${randomColor}">${text}</span>`;
                })();

            const currentRow = $('#dataTable tbody').find(`tr[data-row-id="${rowId}"]`);
            if (currentRow.length === 0) {
                console.error('Row not found');
                return;
            }

            currentRow.find('td[data-nama]').text(formData.nama).attr('data-nama', formData.nama);
            currentRow.find('td[data-gender]').text(genderText).attr('data-gender', formData.gender);
            currentRow.find('td[data-disabilitas]').html(disabilitasHtml).attr('data-disabilitas', formData.disabilitas);
            // currentRow.find('td[data-kelompok_rentan]').html(kelompokRentanHtml).attr('data-kelompok_rentan', formData.kelompok_rentan);
            currentRow.find('td[data-kelompok_rentan]').attr('data-kelompok_rentan', formData.kelompok_rentan.join(','));
            currentRow.find('td[data-rt]').text(formData.rt).attr('data-rt', formData.rt);
            currentRow.find('td[data-rw_banjar]').text(formData.rw_banjar).attr('data-rw_banjar', formData.rw_banjar);
            currentRow.find('td[data-no_telp]').text(formData.no_telp).attr('data-no_telp', formData.no_telp);
            currentRow.find('td[data-jenis_kelompok]').text(formData.jenis_kelompok).attr('data-jenis_kelompok', formData.jenis_kelompok);
            currentRow.find('td[data-usia]').text(formData.usia).attr('data-usia', formData.usia);

            currentRow.find("td[data-desa-id]").attr("data-desa-id", desaId).attr("data-desa-nama", desaText).text(desaText);
            currentRow.find("td[data-dusun-id]").attr("data-dusun-id", dusunId).attr("data-dusun-nama", dusunText).text(dusunText);

            updateAgeCheckmarks(currentRow.find('.usia-cell'));

            $('#editDataModal').modal('hide');

            // Resetting all relevant fields after update
            $('.select2-multiple').val(null).trigger('change');
            $('.select2').val(null).trigger('change');
            $('#disabilitas').val(null).trigger('change');
            $('#editKelompokRentan').val(null).trigger('change');
            $('#editGender').val(null).trigger('change');

            form.reset(); // Resetting all other fields in the edit data form
        } else {
            form.reportValidity();
        }
    }


    function deleteRow(row) {
        $(row).closest('tr').remove();
    }

    // load scripts when document is ready

    $(document).ready(function() {
        loadSelect2Option();

        $("#desa_id, #editDesa").on("change", function () {
            const targetDusun = $(this).attr("id") === "desa_id" ? "#dusun_id" : "#editDusun";
            $(targetDusun).val(null).trigger("change");
        });

        $("#addDataBtn").on("click", function() {
            let programId = $('#program_id').val();
            if (!programId || programId === "" || programId === undefined) {
                Toast.fire({
                    text:  '{{ __("global.pleaseSelect") ." ". __("cruds.program.title") }}',
                    position: 'center',
                    title: "Opssss...",
                    timer: 500,
                    timerProgressBar: true,
                    icon: 'error',
                    showCloseButton: true,
                    showCancelButton: false,
                    showConfirmButton: false,
                    showDenyButton: false,
                });

                 $('#kode_program').click();

                return false; // this mean that the user didn't select a program
            }

            $('#ModalTambahPeserta').modal('show').on('shown.bs.modal', function() {
                $(this).removeAttr('inert');
            });
        });

        $("#saveDataBtn").on("click", function(e) {
            e.preventDefault();
            saveRow();
        });


        $('#dataTable tbody').on('click', '.edit-btn', function(e) {
            e.preventDefault();
            editRow(this);
            loadSelect2EditKelompokRentan();
            $('#editDataModal').modal('show');
        });

        $('#updateDataBtn').on('click', function(e) {
            e.preventDefault();
            updateRow();
        });

        $('#dataTable tbody').on('click', '.delete-btn', function(e) {
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

        // preview data
        $("#submitDataBtn").on("click", function() {
            // Collect all the data from the table
            let tableData = [];
            $("#dataTable tbody tr").each(function() {
                let row = $(this);
                let rowData = {
                    nama: row.find('td[data-nama]').attr('data-nama'),
                    gender: row.find('td[data-gender]').attr('data-gender'),
                    disabilitas: row.find('td[data-disabilitas]').attr('data-disabilitas').split(','),
                    kelompok_rentan: row.find('td[data-kelompok_rentan]').attr('data-kelompok_rentan').split(','),
                    rt: row.find('td[data-rt]').attr('data-rt'),
                    rw_banjar: row.find('td[data-rw_banjar]').attr('data-rw_banjar'),
                    dusun_id: row.find('td[data-dusun-id]').attr('data-dusun-id'),
                    desa_id: row.find('td[data-desa-id]').attr('data-desa-id'),
                    no_telp: row.find('td[data-no_telp]').attr('data-no_telp'),
                    jenis_kelompok: row.find('td[data-jenis_kelompok]').attr('data-jenis_kelompok'),
                    usia: row.find('td[data-usia]').attr('data-usia')
                };
                tableData.push(rowData);
            });

            // Convert the data to a formatted JSON string
            let jsonData = JSON.stringify(tableData, null, 2);

            // Display the JSON data in the modal
            $("#modalData").text(jsonData);

            // Show the modal
            $("#previewModalsData").modal('show');
        });

        // send data to controller
        $("#sendDataBtn").on("click", function() {
            // Get the JSON data from the modal
            let finalData = JSON.parse($("#modalData").text());

            // Send the data to the server
            $.ajax({
                url: '/beneficiary/kirim-peserta', // Replace with your actual endpoint
                method: 'POST',
                data: JSON.stringify(finalData),
                contentType: 'application/json',
                success: function(response) {
                    // Handle success
                    alert('Data sent successfully!');
                    $("#previewModal").modal('hide');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('Error sending data: ' + error);
                }
            });
        });
    });

    // end script of document ready






















    // Event handler for modal when it is shown
    // So that would be able to remove the inert attribute
    // To minimize the modal from being focused

    $('#ModalTambahPeserta').on('shown.bs.modal', function() {
        $(this).removeAttr('inert');
    });

    $('#editDataModal').on('shown.bs.modal', function() {
        $('#editKelompokRentan').select2({
            dropdownParent: $('#editDataModal'),
            width: '100%'
        });

        $('#editDisabilitas').select2({
            dropdownParent: $('#editDataModal'),
            width: '100%'
        });
        $(this).removeAttr('inert');
    });

    $('#ModalTambahPeserta').on('hide.bs.modal', function(e) {
        $(this).attr('inert', '');
        $(document.activeElement).blur();
    });

    $('#editDataModal').on('hide.bs.modal', function(e) {
        $(this).attr('inert', '');
        $(document.activeElement).blur();
    });







</script>
