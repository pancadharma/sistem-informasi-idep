<script>
    let rowCount = 0;

    function addRow(data) {
        rowCount++;
         const newRow = `
        <tr data-row-id="${rowCount}">
            <td class="text-center">${rowCount}</td>
            <td>${data.nama}</td>
            <td>${data.gender}</td>
            <td>${data.disabilitas}</td>
            <td>${data.kelompok_rentan}</td>
            <td>${data.rt}</td>
             <td>${data.rw_banjar}</td>
            <td>${data.dusun}</td>
            <td>${data.desa}</td>
            <td>${data.no_telp}</td>
            <td>${data.jenis_kelompok}</td>
             <td class="usia-cell">${data.usia}</td>
            <td class="text-center age-0-17"></td>
            <td class="text-center age-18-24"></td>
            <td class="text-center age-25-59"></td>
            <td class="text-center age-60-plus"></td>
             <td class="text-center">
             <button class="btn btn-sm btn-info edit-btn">Edit</button>
             <button class="btn btn-sm btn-danger delete-btn">Delete</button>
             </td>
        </tr>
        `;
        $('#tableBody').append(newRow)
        updateAgeCheckmarks($('#tableBody').find(`tr[data-row-id="${rowCount}"]`).find('.usia-cell'));
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
         $('#dataModal').modal('hide');
    }

    function saveRow() {
          const formData = $('#dataForm').serializeArray().reduce((obj, item) => {
            obj[item.name] = item.value;
            return obj;
        }, {});
        addRow(formData);
        $('#dataModal').modal('hide');
        $('#dataForm')[0].reset(); // Reset the form

    }

     function editRow(row) {
        const rowId = $(row).closest('tr').data('row-id');
       const currentRow =  $('#tableBody').find(`tr[data-row-id="${rowId}"]`);
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


       $('#editRowId').val(rowId)
        $('#editNama').val(nama)
        $('#editGender').val(gender);
        $('#editDisabilitas').val(disabilitas)
        $('#editKelompokRentan').val(kelompok_rentan)
         $('#editRt').val(rt)
        $('#editRwBanjar').val(rw_banjar)
        $('#editDusun').val(dusun)
        $('#editDesa').val(desa)
       $('#editNoTelp').val(no_telp);
       $('#editJenisKelompok').val(jenis_kelompok);
       $('#editUsia').val(usia)
      $('#editDataModal').modal('show');
     }

       function updateRow() {
            const rowId = $('#editRowId').val();
            const formData = $('#editDataForm').serializeArray().reduce((obj, item) => {
                 obj[item.name] = item.value;
                return obj;
              }, {});

           const currentRow =  $('#tableBody').find(`tr[data-row-id="${rowId}"]`);
           currentRow.find('td:eq(1)').text(formData.nama);
           currentRow.find('td:eq(2)').text(formData.gender);
           currentRow.find('td:eq(3)').text(formData.disabilitas);
            currentRow.find('td:eq(4)').text(formData.kelompok_rentan);
           currentRow.find('td:eq(5)').text(formData.rt);
            currentRow.find('td:eq(6)').text(formData.rw_banjar);
            currentRow.find('td:eq(7)').text(formData.dusun);
            currentRow.find('td:eq(8)').text(formData.desa);
           currentRow.find('td:eq(9)').text(formData.no_telp);
           currentRow.find('td:eq(10)').text(formData.jenis_kelompok);
           currentRow.find('td:eq(11)').text(formData.usia);
            updateAgeCheckmarks(currentRow.find('.usia-cell'))
           $('#editDataModal').modal('hide');
     }
    function deleteRow(row) {
        $(row).closest('tr').remove();
    }


    $(document).ready(function() {
        $("#addDataBtn").on("click", function() {
             $('#dataModal').modal('show');
        });
        $("#saveDataBtn").on("click", saveRow);
        $('#tableBody').on('click', '.edit-btn', function() {
            editRow(this);
        });
          $('#updateDataBtn').on('click', updateRow);
          $('#tableBody').on('click', '.delete-btn', function() {
           deleteRow(this);
        });

    });
</script>