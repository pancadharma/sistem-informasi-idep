{{-- <script>
    let rowCount = 0;

    function addRow(data) {
        rowCount++;
        const newRow = `
        <tr data-row-id="${rowCount}" class="nowrap">
            <td class="text-center">${rowCount}</td>
            <td data-nama="${data.nama}">${data.nama}</td>
            <td data-gender="${data.gender}" class="text-center">${data.gender}</td>
            <td data-disabilitas="${data.disabilitas}">${data.disabilitas}</td>
            <td data-kelompok_rentan="${data.kelompok_rentan}">${Array.isArray(data.kelompok_rentan) ? data.kelompok_rentan.join(', ') : data.kelompok_rentan}</td>
            <td data-rt="${data.rt}">${data.rt}</td>
            <td data-rw_banjar="${data.rw_banjar}">${data.rw_banjar}</td>
            <td data-dusun="${data.dusun}">${data.dusun}</td>
            <td data-desa="${data.desa}">${data.desa}</td>
            <td data-no_telp="${data.no_telp}">${data.no_telp}</td>
            <td data-jenis_kelompok="${data.jenis_kelompok}">${data.jenis_kelompok}</td>
            <td data-usia="${data.usia}" class="usia-cell">${data.usia}</td>
            <td class="text-center age-0-17"></td>
            <td class="text-center age-18-24"></td>
            <td class="text-center age-25-59"></td>
            <td class="text-center age-60-plus"></td>
            <td class="text-center">
                <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><span class="material-symbols-outlined"> border_color </span></button>
                <button class="btn btn-sm btn-danger delete-btn"><span class="material-symbols-outlined"> delete_forever </span></button>
            </td>
        </tr>
        `;
        $('#tableBody').append(newRow)
        updateAgeCheckmarks($('#dataTable tbody').find(`tr[data-row-id="${rowCount}"]`).find('.usia-cell'));
    }


    function updateAgeCheckmarks(usiaCell) {
        const row = usiaCell.closest('tr')[0];
        const age = parseInt(usiaCell.text(), 10);

        row.querySelector('.age-0-17').innerHTML = (age >= 0 && age <= 17) ? '<span class="checkmark">✔</span>' : '';
        row.querySelector('.age-18-24').innerHTML = (age > 17 && age <= 24) ? '<span class="checkmark">✔</span>' : '';
        row.querySelector('.age-25-59').innerHTML = (age >= 25 && age <= 59) ? '<span class="checkmark">✔</span>' : '';
        row.querySelector('.age-60-plus').innerHTML = (age >= 60) ? '<span class="checkmark">✔</span>' : '';
    }

    function closeModal() {
        $('#ModalTambahPeserta').modal('hide');
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

            addRow(formData);
            $('#ModalTambahPeserta').modal('hide');
            $('#dataForm')[0].reset(); // Reset the form
            $('.select2-multiple').val(null).trigger('change'); // Reset Select2

        } else {
            form.reportValidity();
        }
    }

    $("#saveDataBtn").on("click", function(e) {
        e.preventDefault();
        saveRow();
    });

    function editRow(row) {
        const currentRow = $(row).closest('tr');
        const rowId = currentRow.data('row-id');

        const nama = currentRow.find('td:eq(1)').text();
        const gender = currentRow.find('td:eq(2)').text();
        const disabilitas = currentRow.find('td:eq(3)').text();
        const kelompok_rentan = currentRow.find('td:eq(4)').text();
        const rt = currentRow.find('td:eq(5)').text();
        const rw_banjar = currentRow.find('td:eq(6)').text();
        const dusun = currentRow.find('td:eq(7)').text();
        const desa = currentRow.find('td:eq(8)').text();
        const no_telp = currentRow.find('td:eq(9)').text();
        const jenis_kelompok = currentRow.find('td:eq(10)').text();
        const usia = currentRow.find('td:eq(11)').text();

        // Handle potential errors while setting values
        try {
            const kelompokRentanValues = kelompok_rentan.split(',').map(item => item.trim());

            $('#editRowId').val(rowId)
            $('#editNama').val(nama)
            $('#editGender').val(gender);
            $('#editDisabilitas').val(disabilitas)
            $('#editKelompokRentan').val(kelompokRentanValues).trigger('change');
            $('#editRt').val(rt)
            $('#editRwBanjar').val(rw_banjar)
            $('#editDusun').val(dusun)
            $('#editDesa').val(desa)
            $('#editNoTelp').val(no_telp);
            $('#editJenisKelompok').val(jenis_kelompok);
            $('#editUsia').val(usia)
            $('#editDataModal').modal('show');
        } catch (error) {
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while updating the row.',
                icon: 'error',
                confirmButtonText: 'Okay'
            })
        }
    }

    // function updateRow() {
    //     const rowId = $('#editRowId').val();
    //     const formData = $('#editDataForm').serializeArray().reduce((obj, item) => {
    //         if (obj[ item.name ]) {
    //             if (!Array.isArray(obj[ item.name ])) {
    //                 obj[ item.name ] = [ obj[ item.name ] ];
    //             }
    //             obj[ item.name ].push(item.value);
    //         } else {
    //             obj[ item.name ] = item.value;
    //         }
    //         return obj;
    //     }, {});

    //     const currentRow = $('#dataTable tbody').find(`tr[data-row-id="${rowId}"]`);
    //      currentRow.find('td:eq(1)').text(formData.nama);
    //     currentRow.find('td:eq(2)').text(formData.gender);
    //     currentRow.find('td:eq(3)').text(formData.disabilitas);
    //     currentRow.find('td:eq(4)').text(Array.isArray(formData.kelompok_rentan) ? formData.kelompok_rentan.join(', ') : formData.kelompok_rentan);
    //     currentRow.find('td:eq(5)').text(formData.rt);
    //     currentRow.find('td:eq(6)').text(formData.rw_banjar);
    //     currentRow.find('td:eq(7)').text(formData.dusun);
    //     currentRow.find('td:eq(8)').text(formData.desa);
    //     currentRow.find('td:eq(9)').text(formData.no_telp);
    //     currentRow.find('td:eq(10)').text(formData.jenis_kelompok);
    //     currentRow.find('td:eq(11)').text(formData.usia);
    //     updateAgeCheckmarks(currentRow.find('.usia-cell'))
    //     $('#editDataModal').modal('hide');
    //     $('.select2-multiple').val(null).trigger('change'); // Reset Select2
    // }
    function updateRow() {
        const rowId = $('#editRowId').val();
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

            const form = editDataForm;
            // debugger;
            if (form) {
                if(form.checkValidity()){
                        const currentRow = $('#dataTable tbody').find(`tr[data-row-id="${rowId}"]`);
                        currentRow.find('td:eq(1)').text(formData.nama).attr('data-nama', formData.nama);
                        currentRow.find('td:eq(2)').text(formData.gender).attr('data-gender', formData.gender);
                        currentRow.find('td:eq(3)').text(formData.disabilitas).attr('data-disabilitas',formData.disabilitas);
                        currentRow.find('td:eq(4)').text(Array.isArray(formData.kelompok_rentan) ? formData.kelompok_rentan.join(', ') : formData.kelompok_rentan).attr('data-kelompok_rentan', Array.isArray(formData.kelompok_rentan) ? formData.kelompok_rentan.join(', ') : formData.kelompok_rentan);
                        currentRow.find('td:eq(5)').text(formData.rt).attr('data-rt', formData.rt);
                        currentRow.find('td:eq(6)').text(formData.rw_banjar).attr('data-rw_banjar', formData.rw_banjar);
                        currentRow.find('td:eq(7)').text(formData.dusun).attr('data-dusun', formData.dusun);
                    currentRow.find('td:eq(8)').text(formData.desa).attr('data-desa', formData.desa);
                    currentRow.find('td:eq(9)').text(formData.no_telp).attr('data-no_telp',formData.no_telp);
                    currentRow.find('td:eq(10)').text(formData.jenis_kelompok).attr('data-jenis_kelompok',formData.jenis_kelompok);
                    currentRow.find('td:eq(11)').text(formData.usia).attr('data-usia',formData.usia);
                    updateAgeCheckmarks(currentRow.find('.usia-cell'));
                    $('#editDataModal').modal('hide');
                    $('.select2-multiple').val(null).trigger('change'); // Reset Select2
                }else{
                    form.reportValidity()
                }
            }else {
                console.error('Form with ID "editDataForm" not found.');
            }
    }

    function deleteRow(row) {
        $(row).closest('tr').remove();
    }


    $(document).ready(function() {
        $("#addDataBtn").on("click", function() {
            $('#ModalTambahPeserta').modal('show');
            $('.select2-multiple').select2({
                dropdownParent: $('#ModalTambahPeserta'),
                width: '100%'
            });
        });

        $("#dataForm").on("submit", function(e) {
            e.preventDefault();
            const form = $(this)[0];
            if (form.checkValidity()) {
                saveRow();
            } else {
                form.reportValidity()
            }
        });


        $('#dataTable tbody').on('click', '.edit-btn', function(e) {
            e.preventDefault();
            editRow(this);
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
                    )
                }
            })
            //    deleteRow(this);
            return false;
        });
    });
    $('#ModalTambahPeserta').on('shown.bs.modal', function() {
        $(this).removeAttr('inert');
    });
    $('#editDataModal').on('shown.bs.modal', function() {
        $('#editKelompokRentan').select2({
            dropdownParent: $('#editDataModal'),
            width: '100%'
        });
        editDataForm = document.getElementById('editDataForm');
        $(this).removeAttr('inert');
    });
    $('#editDataModal, #ModalTambahPeserta').on('hide.bs.modal', function(e) {
        $(this).attr('inert', '');
        $(document.activeElement).blur();
    });
</script>
 --}}

<script>
    let rowCount = 0;
    let editDataForm = null; // Initialize as null

    function addRow(data) {
        rowCount++;

        // Convert selected values to text
        let kelompokRentanText = [];
        if (Array.isArray(data.kelompok_rentan)) {
            kelompokRentanText = data.kelompok_rentan.map(value => {
                return $('#ModalTambahPeserta select[name="kelompok_rentan"] option[value="' + value + '"]').text();
            });
        }

        const newRow = `
        <tr data-row-id="${rowCount}" class="nowrap">
            <td class="text-center">${rowCount}</td>
            <td data-nama="${data.nama}">${data.nama}</td>
            <td data-gender="${data.gender}" class="text-center">${data.gender}</td>
            <td data-disabilitas="${data.disabilitas}">${data.disabilitas}</td>
            <td data-kelompok_rentan="${data.kelompok_rentan}">${kelompokRentanText.join(', ')}</td>
            <td data-rt="${data.rt}">${data.rt}</td>
            <td data-rw_banjar="${data.rw_banjar}">${data.rw_banjar}</td>
            <td data-dusun="${data.dusun}">${data.dusun}</td>
            <td data-desa="${data.desa}">${data.desa}</td>
            <td data-no_telp="${data.no_telp}">${data.no_telp}</td>
            <td data-jenis_kelompok="${data.jenis_kelompok}">${data.jenis_kelompok}</td>
            <td data-usia="${data.usia}" class="usia-cell">${data.usia}</td>
            <td class="text-center age-0-17"></td>
            <td class="text-center age-18-24"></td>
            <td class="text-center age-25-59"></td>
            <td class="text-center age-60-plus"></td>
            <td class="text-center">
                <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><span class="material-symbols-outlined"> border_color </span></button>
                <button class="btn btn-sm btn-danger delete-btn"><span class="material-symbols-outlined"> delete_forever </span></button>
            </td>
        </tr>
        `;
        $('#tableBody').append(newRow);
        updateAgeCheckmarks($('#dataTable tbody').find(`tr[data-row-id="${rowCount}"]`).find('.usia-cell'));
    }

    // Update the Select2 options in both modals
    function initializeSelect2() {
        const options = [
            {id: '1', text: 'Anak-anak'},
            {id: '2', text: 'Lansia'},
            {id: '3', text: 'Ibu Hamil'},
            {id: '4', text: 'Penyandang Disabilitas'},
            {id: '5', text: 'Minoritas'}
        ];

        // Initialize Select2 for add modal
        $('.select2-multiple').select2({
            data: options,
            dropdownParent: $('#ModalTambahPeserta'),
            width: '100%'
        });

        // Initialize Select2 for edit modal
        $('#editKelompokRentan').select2({
            data: options,
            dropdownParent: $('#editDataModal'),
            width: '100%'
        });
    }

    function updateAgeCheckmarks(usiaCell) {
        const row = usiaCell.closest('tr')[0];
        const ageText = usiaCell.text().trim();
        const age = parseInt(ageText, 10);

        // Clear all checkmarks if invalid age
        if (isNaN(age)) {
            row.querySelector('.age-0-17').innerHTML = '';
            row.querySelector('.age-18-24').innerHTML = '';
            row.querySelector('.age-25-59').innerHTML = '';
            row.querySelector('.age- 60-plus').innerHTML = '';
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

            addRow(formData);
            $('#ModalTambahPeserta').modal('hide');
            $('#dataForm')[0].reset(); // Reset the form
            $('.select2-multiple').val(null).trigger('change'); // Reset Select2
        } else {
            form.reportValidity();
        }
    }

    function editRow(row) {
        const currentRow = $(row).closest('tr');
        const rowId = currentRow.data('row-id');

        // Get the stored values from data attribute
        const kelompok_rentan = currentRow.find('td:eq(4)').attr('data-kelompok_rentan');
        const kelompokRentanValues = kelompok_rentan ? kelompok_rentan.split(',') : [];

        $('#editRowId').val(rowId);
        $('#editNama').val(currentRow.find('td:eq(1)').attr('data-nama'));
        $('#editGender').val(currentRow.find('td:eq(2)').attr('data-gender'));
        $('#editDisabilitas').val(currentRow.find('td:eq(3)').attr('data-disabilitas'));
        $('#editKelompokRentan').val(kelompokRentanValues).trigger('change');
        $('#editRt').val(currentRow.find('td:eq(5)').attr('data-rt'));
        $('#editRwBanjar').val(currentRow.find('td:eq(6)').attr('data-rw_banjar'));
        $('#editDusun').val(currentRow.find('td:eq(7)').attr('data-dusun'));
        $('#editDesa').val(currentRow.find('td:eq(8)').attr('data-desa'));
        $('#editNoTelp').val(currentRow.find('td:eq(9)').attr('data-no_telp'));
        $('#editJenisKelompok').val(currentRow.find('td:eq(10)').attr('data-jenis_kelompok'));
        $('#editUsia').val(currentRow.find('td:eq(11)').attr('data-usia'));
    }

    function updateRow() {
        const rowId = $('#editRowId').val();
        const form = document.getElementById('editDataForm');

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

            // Convert selected values to text for display
            const kelompokRentanText = Array.isArray(formData.kelompok_rentan)
                ? formData.kelompok_rentan.map(value =>
                    $('#editKelompokRentan option[value="' + value + '"]').text()
                ).join(', ')
                : $('#editKelompokRentan option[value="' + formData.kelompok_rentan + '"]').text();

            const currentRow = $('#dataTable tbody').find(`tr[data-row-id="${rowId}"]`);
            currentRow.find('td:eq(1)').text(formData.nama).attr('data-nama', formData.nama);
            currentRow.find('td:eq(2)').text(formData.gender).attr('data-gender', formData.gender);
            currentRow.find('td:eq(3)').text(formData.disabilitas).attr('data-disabilitas', formData.disabilitas);
            currentRow.find('td:eq(4)').text(kelompokRentanText).attr('data-kelompok_rentan', formData.kelompok_rentan);
            currentRow.find('td:eq(5)').text(formData.rt).attr('data-rt', formData.rt);
            currentRow.find('td:eq(6)').text(formData.rw_banjar).attr('data-rw_banjar', formData.rw_banjar);
            currentRow.find('td:eq(7)').text(formData.dusun).attr('data-dusun', formData.dusun);
            currentRow.find('td:eq(8)').text(formData.desa).attr('data-desa', formData.desa);
            currentRow.find('td:eq(9)').text(formData.no_telp).attr('data-no_telp', formData.no_telp);
            currentRow.find('td:eq(10)').text(formData.jenis_kelompok).attr('data-jenis_kelompok', formData.jenis_kelompok);
            currentRow.find('td:eq(11)').text(formData.usia).attr('data-usia', formData.usia);
            updateAgeCheckmarks(currentRow.find('.usia-cell'));

            $('#editDataModal').modal('hide');
            $('.select2-multiple').val(null).trigger('change');
        } else {
            form.reportValidity();
        }
    }

    function deleteRow(row) {
        $(row).closest('tr').remove();
    }

    $(document).ready(function() {
        initializeSelect2();

        $("#addDataBtn").on("click", function() {
            $('#ModalTambahPeserta').modal('show');
            $('.select2-multiple').select2({
                dropdownParent: $('#ModalTambahPeserta'),
                width: '100%'
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

        $('#dataTable tbody').on('click', '.edit-btn', function(e) {
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
    });

    $('#ModalTambahPeserta').on('shown.bs.modal', function() {
        $(this).removeAttr('inert');
    });

    $('#editDataModal').on('shown.bs.modal', function() {
        $('#editKelompokRentan').select2({
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
