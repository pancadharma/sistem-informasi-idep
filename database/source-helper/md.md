Your current `edit.blade.php` script is well-structured for managing the edit page, including dynamic activity headers, Select2 initialization for dropdowns, and modal-based editing. However, it currently relies on a modal form submission with an `updateRow` function that triggers a page reload upon success. To align it with your idea of using `addRow` and `updateRow` with AJAX (similar to the create page’s dynamic row addition), we’ll modify it to:

- Use `addRow` to add new beneficiaries directly to the table via AJAX.
- Update `updateRow` to modify existing rows in the table via AJAX without reloading the page.
- Remove the dependency on a full page reload, keeping the experience seamless.

Here’s the revised script incorporating these changes:

---

### Updated `edit.blade.php` Script

```html
<script>
  $(window).on('resize', function() {
      $('.select2-container--open').each(function() {
          const $select = $(this).prev('select');
          $select.select2('close');
          $select.select2('open');
      });
  });

  function escapeHtml(str) {
      if (!str) return ""; // Handle null/undefined cases
      return str
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#039;");
  }

  function updateActivityHeaders(activities) {
      if (activities.length > 0) {
          const activityHeaders = activities.map(activity => `
              <th class="align-middle text-center activity-header" data-activity-id="${activity.id}">${activity.kode}</th>
          `).join('');
          $('#activityHeaders').html(`
              <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
              <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }}</th>
              <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }}  <sup><i class="fas fa-question-circle" title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
              <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
              <th colspan="1" class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
              <th colspan="1" class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
              <th colspan="1" class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
              <th colspan="1" class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
              ${activityHeaders}
          `);
          $('#headerActivityProgram').attr('rowspan', 1).attr('colspan', activities.length);
      } else {
          $('#activityHeaders').html(`
              <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
              <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }}</th>
              <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }} <sup><i class="fas fa-question-circle" title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
              <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
              <th colspan="1" class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
              <th colspan="1" class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
              <th colspan="1" class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
              <th colspan="1" class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
          `);
          $('#headerActivityProgram').attr('rowspan', 2);
      }
  }

  function populateActivitySelect(activities, selectElement) {
      selectElement.empty().append('<option value="">Pilih Activity</option>');
      activities.forEach(activity => {
          const option = new Option(activity.kode, activity.id, false, false);
          option.setAttribute('title', activity.nama);
          selectElement.append(option);
      });
      selectElement.select2();
  }

  function loadJenisKelompok() {
      const placeholder = '{{ __('global.pleaseSelect') . ' ' . __('cruds.beneficiary.penerima.jenis_kelompok') }}';
      $("#jenis_kelompok").select2({
          placeholder: placeholder,
          ajax: { url: '{{ route('api.jenis.kelompok') }}', dataType: "json", delay: 250, data: params => ({ search: params.term, page: params.page || 1 }), processResults: data => ({ results: data.results, pagination: { more: data.pagination.more } }), cache: true },
          dropdownParent: $("#ModalTambahPeserta"),
          width: "100%",
          multiple: true,
          allowClear: true
      });
      $("#editJenisKelompok").select2({
          placeholder: placeholder,
          ajax: { url: '{{ route('api.jenis.kelompok') }}', dataType: "json", delay: 250, data: params => ({ search: params.term, page: params.page || 1 }), processResults: data => ({ results: data.results, pagination: { more: data.pagination.more } }), cache: true },
          dropdownParent: $("#editDataModal"),
          width: "100%",
          multiple: true,
          allowClear: true
      });
  }

  function loadKelompokMarjinal() {
      $("#kelompok_rentan").select2({
          placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
          dropdownParent: $("#ModalTambahPeserta"),
          width: "100%",
          allowClear: true,
          multiple: true,
          ajax: { url: '{{ route('api.beneficiary.kelompok.rentan') }}', dataType: "json", delay: 250, data: params => ({ search: params.term, page: params.page || 1 }), processResults: data => ({ results: data.results, pagination: { more: data.pagination.more } }), cache: true }
      });
      $("#editKelompokRentan").select2({
          placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
          dropdownParent: $("#editDataModal"),
          width: "100%",
          allowClear: true,
          multiple: true,
          ajax: { url: '{{ route('api.beneficiary.kelompok.rentan') }}', dataType: "json", delay: 250, data: params => ({ search: params.term, page: params.page || 1 }), processResults: data => ({ results: data.results, pagination: { more: data.pagination.more } }), cache: true }
      });
  }

  function setLocationForm(provinsiSelector, kabupatenSelector, kecamatanSelector, desaSelector, dusunSelector, dropdownParent) {
      if (!$(provinsiSelector).val()) {
          $(kabupatenSelector).prop('disabled', true);
          $(kecamatanSelector).prop('disabled', true);
          $(desaSelector).prop('disabled', true);
          $(dusunSelector).prop('disabled', true);
      }
      $(provinsiSelector).select2({
          ajax: { url: "{{ route('api.beneficiary.provinsi') }}", dataType: 'json', delay: 250, data: params => ({ search: params.term, page: params.page || 1 }), processResults: data => ({ results: data.results, pagination: data.pagination }), cache: true },
          dropdownParent: dropdownParent,
          placeholder: "{{ __('global.selectProv') }}"
      }).on('change', function() {
          $(kabupatenSelector).val(null).trigger('change').prop('disabled', !$(this).val());
          $(kecamatanSelector).val(null).trigger('change').prop('disabled', true);
          $(desaSelector).val(null).trigger('change').prop('disabled', true);
          $(dusunSelector).val(null).trigger('change').prop('disabled', true);
      });

      $(kabupatenSelector).select2({
          ajax: { url: () => "{{ route('api.beneficiary.kab', ['id' => ':id']) }}".replace(':id', $(provinsiSelector).val()), dataType: 'json', delay: 250, data: params => ({ provinsi_id: $(provinsiSelector).val(), search: params.term, page: params.page || 1 }), processResults: data => ({ results: data.results, pagination: data.pagination }) },
          dropdownParent: dropdownParent
      }).on('change', function() {
          $(kecamatanSelector).val(null).trigger('change').prop('disabled', !$(this).val());
          $(desaSelector).val(null).trigger('change').prop('disabled', true);
          $(dusunSelector).val(null).trigger('change').prop('disabled', true);
      });

      $(kecamatanSelector).select2({
          ajax: { url: () => "{{ route('api.beneficiary.kec', ['id' => ':id']) }}".replace(':id', $(kabupatenSelector).val()), dataType: 'json', delay: 250, data: params => ({ kabupaten_id: $(kabupatenSelector).val(), search: params.term, page: params.page || 1 }), processResults: data => ({ results: data.results, pagination: data.pagination }) },
          dropdownParent: dropdownParent
      }).on('change', function() {
          $(desaSelector).val(null).trigger('change').prop('disabled', !$(this).val());
          $(dusunSelector).val(null).trigger('change').prop('disabled', true);
      });

      $(desaSelector).select2({
          ajax: { url: () => "{{ route('api.beneficiary.desa', ['id' => ':id']) }}".replace(':id', $(kecamatanSelector).val()), dataType: 'json', delay: 250, data: params => ({ kecamatan_id: $(kecamatanSelector).val(), search: params.term, page: params.page || 1 }), processResults: data => ({ results: data.results, pagination: data.pagination }) },
          dropdownParent: dropdownParent
      }).on('change', function() {
          $(dusunSelector).val(null).trigger('change').prop('disabled', !$(this).val());
      });

      $(dusunSelector).select2({
          ajax: { url: () => "{{ route('api.beneficiary.dusun', ['id' => ':id']) }}".replace(':id', $(desaSelector).val()), dataType: 'json', delay: 250, data: params => ({ desa_id: $(desaSelector).val(), search: params.term, page: params.page || 1 }), processResults: data => ({ results: data.results, pagination: data.pagination }) },
          dropdownParent: dropdownParent
      });
  }

  $(document).ready(function() {
      const csrfToken = $('meta[name="csrf-token"]').attr("content");
      $.ajaxSetup({ headers: { "X-CSRF-TOKEN": csrfToken } });

      const activities = @json($activities);
      const beneficiaries = @json($beneficiaries);
      let rowCount = beneficiaries.length;

      loadJenisKelompok();
      loadKelompokMarjinal();
      setLocationForm('#provinsi_id_tambah', '#kabupaten_id_tambah', '#kecamatan_id_tambah', '#desa_id_tambah', '#dusun_id_tambah', $('#ModalTambahPeserta'));
      setLocationForm('#provinsi_id_edit', '#kabupaten_id_edit', '#kecamatan_id_edit', '#desa_id_edit', '#dusun_id_edit', $('#editDataModal'));

      fetch("{{ route('api.program.activity', $program->id) }}")
          .then(response => response.json())
          .then(activities => {
              populateActivitySelect(activities, $("#activitySelect"));
              populateActivitySelect(activities, $("#activitySelectEdit"));
              updateActivityHeaders(activities);
          })
          .catch(error => console.error('Error fetching activities:', error));

      $("#activitySelect").select2({ width: "100%", dropdownParent: $("#ModalTambahPeserta"), multiple: true, allowClear: true });
      $("#activitySelectEdit").select2({ width: "100%", dropdownParent: $("#editDataModal"), multiple: true, allowClear: true });

      function addRow(data) {
          $.ajax({
              url: '{{ route('beneficiary.store') }}',
              method: 'POST',
              data: JSON.stringify({
                  program_id: '{{ $program->id }}',
                  nama: data.nama,
                  no_telp: data.no_telp,
                  jenis_kelamin: data.gender,
                  umur: data.usia,
                  rt: data.rt,
                  rw: data.rw,
                  dusun_id: data.dusun_id,
                  kelompok_rentan: data.kelompok_rentan,
                  jenis_kelompok: data.jenis_kelompok,
                  activity_ids: data.activity_ids,
                  is_non_activity: data.is_non_activity,
                  keterangan: data.keterangan
              }),
              contentType: 'application/json',
              success: function(response) {
                  const newBeneficiary = response.data;
                  rowCount++;
                  const kelompokRentanText = newBeneficiary.kelompok_marjinal.map(k => `<span class="badge badge-info">${k.nama}</span>`).join(' ');
                  const jenisKelompokText = newBeneficiary.jenis_kelompok.map(j => `<span class="badge badge-info">${j.nama}</span>`).join(' ');
                  const activityCells = activities.map(activity => `
                      <td data-program-activity-id="${activity.id}">${newBeneficiary.penerima_activity.some(a => a.id === activity.id) ? '√' : ''}</td>
                  `).join('');

                  const newRow = `
                      <tr data-id="${newBeneficiary.id}" class="nowrap">
                          <td class="d-none">${rowCount}</td>
                          <td data-nama="${newBeneficiary.nama}">${newBeneficiary.nama}</td>
                          <td data-gender="${newBeneficiary.jenis_kelamin}">${newBeneficiary.jenis_kelamin === 'laki' ? 'Laki-laki' : 'Perempuan'}</td>
                          <td data-kelompok_rentan="${newBeneficiary.kelompok_marjinal.map(k => k.id).join(',')}" data-kelompok_rentan_full='${JSON.stringify(newBeneficiary.kelompok_marjinal.map(k => ({id: k.id, text: k.nama})))}'>${kelompokRentanText}</td>
                          <td data-rt="${newBeneficiary.rt}">${newBeneficiary.rt}</td>
                          <td data-rw="${newBeneficiary.rw}">${newBeneficiary.rw}</td>
                          <td data-dusun-id="${newBeneficiary.dusun_id}">${newBeneficiary.dusun?.nama || ''}</td>
                          <td data-desa-id="${newBeneficiary.dusun?.desa_id || ''}">${newBeneficiary.dusun?.desa?.nama || ''}</td>
                          <td data-no_telp="${newBeneficiary.no_telp}">${newBeneficiary.no_telp}</td>
                          <td data-jenis_kelompok="${newBeneficiary.jenis_kelompok.map(j => j.id).join(',')}" data-jenis_kelompok_full='${JSON.stringify(newBeneficiary.jenis_kelompok.map(j => ({id: j.id, text: j.nama})))}'>${jenisKelompokText}</td>
                          <td data-usia="${newBeneficiary.umur}">${newBeneficiary.umur}</td>
                          <td>${newBeneficiary.umur <= 17 ? '√' : ''}</td>
                          <td>${newBeneficiary.umur >= 18 && newBeneficiary.umur <= 24 ? '√' : ''}</td>
                          <td>${newBeneficiary.umur >= 25 && newBeneficiary.umur <= 59 ? '√' : ''}</td>
                          <td>${newBeneficiary.umur > 60 ? '√' : ''}</td>
                          ${activityCells}
                          <td data-is_non_activity="${newBeneficiary.is_non_activity ? 'true' : 'false'}">${newBeneficiary.is_non_activity ? '√' : ''}</td>
                          <td data-keterangan="${newBeneficiary.keterangan || ''}">${newBeneficiary.keterangan || ''}</td>
                          <td>
                              <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                              <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                          </td>
                      </tr>
                  `;
                  $("#tableBody").append(newRow);
                  $("#addDataForm")[0].reset();
                  $("#kelompok_rentan, #jenis_kelompok, #activitySelect").val(null).trigger("change");
                  $("#ModalTambahPeserta").modal("hide");
                  Swal.fire("Success", "Beneficiary added!", "success");
              },
              error: function(xhr) {
                  Swal.fire("Error", xhr.responseJSON?.message || "Failed to add beneficiary.", "error");
              }
          });
      }

      function editRow(row) {
          const beneficiaryId = $(row).data('id');
          const beneficiary = beneficiaries.find(b => b.id === beneficiaryId);
          if (!beneficiary) {
              console.error("Beneficiary not found:", beneficiaryId);
              return;
          }

          $("#editRowId").val(beneficiaryId);
          $("#editNama").val(beneficiary.nama);
          $("#editNoTelp").val(beneficiary.no_telp);
          $("#editGender").val(beneficiary.jenis_kelamin).trigger("change");
          $("#editUsia").val(beneficiary.umur);
          $("#editRt").val(beneficiary.rt);
          $("#editRwBanjar").val(beneficiary.rw);
          $("#edit_is_non_activity").prop("checked", beneficiary.is_non_activity);
          $("#keterangan_edit").val(beneficiary.keterangan);

          const addOptionAndTriggerChange = (selector, text, value) => {
              const option = new Option(text || '-', value || '', true, true);
              $(selector).empty().append(option).trigger('change');
          };
          addOptionAndTriggerChange("#provinsi_id_edit", beneficiary.provinsi_nama || '-', beneficiary.provinsi_id || '');
          addOptionAndTriggerChange("#kabupaten_id_edit", beneficiary.kabupaten_nama || '-', beneficiary.kabupaten_id || '');
          addOptionAndTriggerChange("#kecamatan_id_edit", beneficiary.kecamatan_nama || '-', beneficiary.kecamatan_id || '');
          addOptionAndTriggerChange("#desa_id_edit", beneficiary.dusun?.desa?.nama || '-', beneficiary.dusun?.desa_id || '');
          addOptionAndTriggerChange("#dusun_id_edit", beneficiary.dusun?.nama || '-', beneficiary.dusun_id || '');

          $("#editKelompokRentan").empty();
          const kelompokMarjinalData = beneficiary.kelompok_marjinal.map(k => ({ id: k.id, text: k.nama }));
          kelompokMarjinalData.forEach(item => {
              $("#editKelompokRentan").append(new Option(item.text, item.id, true, true));
          });
          $("#editKelompokRentan").val(kelompokMarjinalData.map(k => k.id)).trigger("change");

          $("#editJenisKelompok").empty();
          const jenisKelompokData = beneficiary.jenis_kelompok.map(j => ({ id: j.id, text: j.nama }));
          jenisKelompokData.forEach(item => {
              $("#editJenisKelompok").append(new Option(item.text, item.id, true, true));
          });
          $("#editJenisKelompok").val(jenisKelompokData.map(j => j.id)).trigger("change");

          const selectedActivityIds = beneficiary.penerima_activity.map(a => a.id.toString());
          $("#activitySelectEdit").val(selectedActivityIds).trigger("change");

          $("#editDataModal").modal("show");
      }

      function updateRow() {
          const form = document.getElementById("editDataForm");
          const beneficiaryId = $("#editRowId").val();
          if (!form.checkValidity()) {
              form.reportValidity();
              return;
          }

          const formData = $("#editDataForm").serializeArray().reduce((obj, item) => {
              if (obj[item.name]) {
                  if (!Array.isArray(obj[item.name])) obj[item.name] = [obj[item.name]];
                  obj[item.name].push(item.value);
              } else {
                  obj[item.name] = item.value;
              }
              return obj;
          }, {});
          formData.is_non_activity = $("#edit_is_non_activity").is(":checked");
          formData.kelompok_rentan = $("#editKelompokRentan").val() || [];
          formData.jenis_kelompok = $("#editJenisKelompok").val() || [];
          formData.activity_ids = $("#activitySelectEdit").val() || [];
          formData.program_id = '{{ $program->id }}';

          $.ajax({
              url: `{{ route('beneficiary.update', '') }}/${beneficiaryId}`,
              method: "PUT",
              data: JSON.stringify(formData),
              contentType: "application/json",
              success: function(response) {
                  const updatedBeneficiary = response.data;
                  const currentRow = $(`tr[data-id="${beneficiaryId}"]`);
                  const kelompokRentanText = updatedBeneficiary.kelompok_marjinal.map(k => `<span class="badge badge-info">${k.nama}</span>`).join(' ');
                  const jenisKelompokText = updatedBeneficiary.jenis_kelompok.map(j => `<span class="badge badge-info">${j.nama}</span>`).join(' ');

                  currentRow.find('td[data-nama]').attr('data-nama', updatedBeneficiary.nama).text(updatedBeneficiary.nama);
                  currentRow.find('td[data-gender]').attr('data-gender', updatedBeneficiary.jenis_kelamin).text(updatedBeneficiary.jenis_kelamin === 'laki' ? 'Laki-laki' : 'Perempuan');
                  currentRow.find('td[data-kelompok_rentan]')
                      .attr('data-kelompok_rentan', updatedBeneficiary.kelompok_marjinal.map(k => k.id).join(','))
                      .attr('data-kelompok_rentan_full', JSON.stringify(updatedBeneficiary.kelompok_marjinal.map(k => ({id: k.id, text: k.nama}))))
                      .html(kelompokRentanText);
                  currentRow.find('td[data-rt]').attr('data-rt', updatedBeneficiary.rt).text(updatedBeneficiary.rt);
                  currentRow.find('td[data-rw]').attr('data-rw', updatedBeneficiary.rw).text(updatedBeneficiary.rw);
                  currentRow.find('td[data-dusun-id]').attr('data-dusun-id', updatedBeneficiary.dusun_id).text(updatedBeneficiary.dusun?.nama || '');
                  currentRow.find('td[data-desa-id]').attr('data-desa-id', updatedBeneficiary.dusun?.desa_id || '').text(updatedBeneficiary.dusun?.desa?.nama || '');
                  currentRow.find('td[data-no_telp]').attr('data-no_telp', updatedBeneficiary.no_telp).text(updatedBeneficiary.no_telp);
                  currentRow.find('td[data-jenis_kelompok]')
                      .attr('data-jenis_kelompok', updatedBeneficiary.jenis_kelompok.map(j => j.id).join(','))
                      .attr('data-jenis_kelompok_full', JSON.stringify(updatedBeneficiary.jenis_kelompok.map(j => ({id: j.id, text: j.nama}))))
                      .html(jenisKelompokText);
                  currentRow.find('td[data-usia]').attr('data-usia', updatedBeneficiary.umur).text(updatedBeneficiary.umur);
                  currentRow.find('td:eq(11)').text(updatedBeneficiary.umur <= 17 ? '√' : '');
                  currentRow.find('td:eq(12)').text(updatedBeneficiary.umur >= 18 && updatedBeneficiary.umur <= 24 ? '√' : '');
                  currentRow.find('td:eq(13)').text(updatedBeneficiary.umur >= 25 && updatedBeneficiary.umur <= 59 ? '√' : '');
                  currentRow.find('td:eq(14)').text(updatedBeneficiary.umur > 60 ? '√' : '');
                  activities.forEach((activity, index) => {
                      currentRow.find(`td[data-program-activity-id="${activity.id}"]`).text(updatedBeneficiary.penerima_activity.some(a => a.id === activity.id) ? '√' : '');
                  });
                  currentRow.find('td[data-is_non_activity]').attr('data-is_non_activity', updatedBeneficiary.is_non_activity ? 'true' : 'false').text(updatedBeneficiary.is_non_activity ? '√' : '');
                  currentRow.find('td[data-keterangan]').attr('data-keterangan', updatedBeneficiary.keterangan || '').text(updatedBeneficiary.keterangan || '');

                  $("#editDataModal").modal("hide");
                  Swal.fire("Success", "Beneficiary updated!", "success");
              },
              error: function(xhr) {
                  Swal.fire("Error", xhr.responseJSON?.message || "Failed to update beneficiary.", "error");
              }
          });
      }

      function deleteRow(row) {
          const beneficiaryId = $(row).data('id');
          Swal.fire({
              title: "Are you sure?",
              text: "This action cannot be undone!",
              icon: "warning",
              showCancelButton: true,
              confirmButtonText: "Yes, delete!"
          }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                      url: `{{ route('beneficiary.destroy', '') }}/${beneficiaryId}`,
                      method: "DELETE",
                      success: function() {
                          $(row).closest("tr").remove();
                          Swal.fire("Deleted!", "Beneficiary removed.", "success");
                      },
                      error: function(xhr) {
                          Swal.fire("Error", xhr.responseJSON?.message || "Failed to delete.", "error");
                      }
                  });
              }
          });
      }

      $("#addDataBtn").on("click", function(e) {
          e.preventDefault();
          const formData = $("#addDataForm").serializeArray().reduce((obj, item) => {
              obj[item.name] = item.value;
              return obj;
          }, {});
          formData.kelompok_rentan = $("#kelompok_rentan").val() || [];
          formData.jenis_kelompok = $("#jenis_kelompok").val() || [];
          formData.activity_ids = $("#activitySelect").val() || [];
          formData.is_non_activity = $("#is_non_activity").is(":checked");
          addRow(formData);
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
          deleteRow(this);
      });
  });
</script>
```

---

### Key Changes

1. **`addRow` with AJAX**:

   - Sends a `POST` request to `beneficiary.store` with the form data.
   - On success, dynamically appends a new row to `#tableBody` using the server-returned `newBeneficiary` data.
   - Resets the add form and hides the modal.

2. **`updateRow` with AJAX**:

   - Sends a `PUT` request to `beneficiary.update` with the updated form data.
   - On success, updates the existing row in the table without reloading the page, using the server-returned `updatedBeneficiary` data.
   - Hides the edit modal and shows a success message.

3. **Removed Page Reload**:

   - The `location.reload()` in `updateRow` is removed, ensuring the table updates dynamically.

4. **Multiple Selection**:

   - Added `multiple: true` to Select2 initialization for `#jenis_kelompok`, `#editJenisKelompok`, `#kelompok_rentan`, `#editKelompokRentan`, `#activitySelect`, and `#activitySelectEdit` to match your many-to-many relationships.

5. **Data Consistency**:
   - Uses `beneficiaries` JSON data from Blade for pre-populating the edit modal, and updates the table with server responses to ensure accuracy.

---

### Controller Requirements

For this to work, your `BeneficiaryController` must return the created/updated beneficiary with its relationships:

```php
// BeneficiaryController.php
public function store(Request $request)
{
    $beneficiary = Meals_Penerima_Manfaat::create($request->only('program_id', 'nama', 'no_telp', 'jenis_kelamin', 'umur', 'rt', 'rw', 'dusun_id', 'is_non_activity', 'keterangan'));
    $beneficiary->kelompokMarjinal()->sync($request->kelompok_rentan);
    $beneficiary->jenisKelompok()->sync($request->jenis_kelompok);
    $beneficiary->penerimaActivity()->sync($request->activity_ids);
    return response()->json(['data' => $beneficiary->load('kelompokMarjinal', 'jenisKelompok', 'penerimaActivity', 'dusun.desa')], 201);
}

public function update(Request $request, $id)
{
    $beneficiary = Meals_Penerima_Manfaat::findOrFail($id);
    $beneficiary->update($request->only('nama', 'no_telp', 'jenis_kelamin', 'umur', 'rt', 'rw', 'dusun_id', 'is_non_activity', 'keterangan'));
    $beneficiary->kelompokMarjinal()->sync($request->kelompok_rentan);
    $beneficiary->jenisKelompok()->sync($request->jenis_kelompok);
    $beneficiary->penerimaActivity()->sync($request->activity_ids);
    return response()->json(['data' => $beneficiary->load('kelompokMarjinal', 'jenisKelompok', 'penerimaActivity', 'dusun.desa')]);
}

public function destroy($id)
{
    $beneficiary = Meals_Penerima_Manfaat::findOrFail($id);
    $beneficiary->delete();
    return response()->json(null, 204);
}
```

---

### Blade Table

Your existing Blade table should remain mostly unchanged, as the script now updates it dynamically:

```html
<table id="dataTable" class="table table-bordered">
    <thead>
        <tr>
            <th class="d-none">No</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Kelompok Marjinal</th>
            <th>RT</th>
            <th>RW</th>
            <th>Dusun</th>
            <th>Desa</th>
            <th>No Telp</th>
            <th>Jenis Kelompok</th>
            <th>Usia</th>
            <th>0-17</th>
            <th>18-24</th>
            <th>25-59</th>
            <th>>60</th>
            @foreach ($activities as $activity)
                <th class="activity-header" data-activity-id="{{ $activity->id }}">{{ $activity->kode }}</th>
            @endforeach
            <th>Non Aktivitas</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        @foreach ($beneficiaries as $beneficiary)
            <tr data-id="{{ $beneficiary->id }}">
                <td class="d-none">{{ $loop->iteration }}</td>
                <td data-nama="{{ $beneficiary->nama }}">{{ $beneficiary->nama }}</td>
                <td data-gender="{{ $beneficiary->jenis_kelamin }}">{{ $beneficiary->jenis_kelamin === 'laki' ? 'Laki-laki' : 'Perempuan' }}</td>
                <td data-kelompok_rentan="{{ $beneficiary->kelompokMarjinal->pluck('id')->implode(',') }}" data-kelompok_rentan_full='{{ json_encode($beneficiary->kelompokMarjinal->map(fn($k) => ['id' => $k->id, 'text' => $k->nama])) }}'>
                    {{ $beneficiary->kelompokMarjinal->pluck('nama')->implode(', ') }}
                </td>
                <td data-rt="{{ $beneficiary->rt }}">{{ $beneficiary->rt }}</td>
                <td data-rw="{{ $beneficiary->rw }}">{{ $beneficiary->rw }}</td>
                <td data-dusun-id="{{ $beneficiary->dusun_id }}">{{ $beneficiary->dusun ? $beneficiary->dusun->nama : '' }}</td>
                <td data-desa-id="{{ $beneficiary->dusun ? $beneficiary->dusun->desa_id : '' }}">{{ $beneficiary->dusun && $beneficiary->dusun->desa ? $beneficiary->dusun->desa->nama : '' }}</td>
                <td data-no_telp="{{ $beneficiary->no_telp }}">{{ $beneficiary->no_telp }}</td>
                <td data-jenis_kelompok="{{ $beneficiary->jenisKelompok->pluck('id')->implode(',') }}" data-jenis_kelompok_full='{{ json_encode($beneficiary->jenisKelompok->map(fn($j) => ['id' => $j->id, 'text' => $j->nama])) }}'>
                    {{ $beneficiary->jenisKelompok->pluck('nama')->implode(', ') }}
                </td>
                <td data-usia="{{ $beneficiary->umur }}">{{ $beneficiary->umur }}</td>
                <td>{{ $beneficiary->umur <= 17 ? '√' : '' }}</td>
                <td>{{ $beneficiary->umur >= 18 && $beneficiary->umur <= 24 ? '√' : '' }}</td>
                <td>{{ $beneficiary->umur >= 25 && $beneficiary->umur <= 59 ? '√' : '' }}</td>
                <td>{{ $beneficiary->umur > 60 ? '√' : '' }}</td>
                @foreach ($activities as $activity)
                    <td data-program-activity-id="{{ $activity->id }}">{{ $beneficiary->penerimaActivity->contains('id', $activity->id) ? '√' : '' }}</td>
                @endforeach
                <td data-is_non_activity="{{ $beneficiary->is_non_activity ? 'true' : 'false' }}">{{ $beneficiary->is_non_activity ? '√' : '' }}</td>
                <td data-keterangan="{{ $beneficiary->keterangan }}">{{ $beneficiary->keterangan }}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $beneficiary->id }}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $beneficiary->id }}">Delete</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
```

---

### Notes

- **DataTable**: I removed the commented-out DataTable initialization since it wasn’t active in your script. If you want to keep it, ensure it’s reinitialized after `addRow` and `updateRow` to reflect table changes (e.g., `$('#dataTable').DataTable().draw()`).
- **Server Response**: Ensure the `store` and `update` methods return the beneficiary with `kelompok_marjinal`, `jenis_kelompok`, `penerima_activity`, and `dusun.desa` loaded, as the script relies on this structure.
- **Error Handling**: The script includes basic error handling with SweetAlert; enhance it if needed for specific cases.

---

### Testing

1. **Add a Row**:
   - Open `#ModalTambahPeserta`, fill the form, click `#addDataBtn`, and verify a new row appears in `#tableBody`.
2. **Update a Row**:
   - Click `.edit-btn`, modify the form, click `#updateDataBtn`, and confirm the row updates in place without reloading.
3. **Delete a Row**:
   - Click `.delete-btn`, confirm, and ensure the row is removed from the table.

This script now mirrors the dynamic `addRow` approach from your create page while enhancing the edit page with real-time AJAX updates. Let me know if you need further refinements!

###

You’re right to ask! In my previous response, I simplified `addRow` to use an AJAX form submission followed by a table redraw, but I didn’t explicitly simplify `editRow` and `updateRow` in the same way. However, the current `editRow` and `updateRow` functions can also be streamlined to align with this approach—using AJAX submissions and redrawing the table—while reducing complexity.

Let’s simplify `editRow` and `updateRow` to match the `addRow` pattern, ensuring they:

- Submit data via AJAX like a normal form.
- Redraw the table after success, leveraging server-side rendering instead of manually updating rows in JavaScript.

Here’s how we can simplify all three functions (`addRow`, `editRow`, and `updateRow`) in your `edit.blade.php` script.

---

### Fully Simplified Script with `addRow`, `editRow`, and `updateRow`

```html
<script>
  // ... (Keep all other functions like escapeHtml, updateActivityHeaders, loadJenisKelompok, etc. unchanged)

  $(document).ready(function() {
      const csrfToken = $('meta[name="csrf-token"]').attr("content");
      $.ajaxSetup({ headers: { "X-CSRF-TOKEN": csrfToken } });

      const activities = @json($activities);
      const beneficiaries = @json($beneficiaries);
      let rowCount = beneficiaries.length;

      loadJenisKelompok();
      loadKelompokMarjinal();
      setLocationForm('#provinsi_id_tambah', '#kabupaten_id_tambah', '#kecamatan_id_tambah', '#desa_id_tambah', '#dusun_id_tambah', $('#ModalTambahPeserta'));
      setLocationForm('#provinsi_id_edit', '#kabupaten_id_edit', '#kecamatan_id_edit', '#desa_id_edit', '#dusun_id_edit', $('#editDataModal'));

      fetch("{{ route('api.program.activity', $program->id) }}")
          .then(response => response.json())
          .then(activities => {
              populateActivitySelect(activities, $("#activitySelect"));
              populateActivitySelect(activities, $("#activitySelectEdit"));
              updateActivityHeaders(activities);
          })
          .catch(error => console.error('Error fetching activities:', error));

      $("#activitySelect").select2({ width: "100%", dropdownParent: $("#ModalTambahPeserta"), multiple: true, allowClear: true });
      $("#activitySelectEdit").select2({ width: "100%", dropdownParent: $("#editDataModal"), multiple: true, allowClear: true });

      // Simplified addRow: Submit form via AJAX and redraw table
      function addRow() {
          const form = document.getElementById("addDataForm");
          if (!form.checkValidity()) {
              form.reportValidity();
              return;
          }

          const formData = $("#addDataForm").serializeArray().reduce((obj, item) => {
              obj[item.name] = item.value;
              return obj;
          }, {});
          formData.program_id = '{{ $program->id }}';
          formData.kelompok_rentan = $("#kelompok_rentan").val() || [];
          formData.jenis_kelompok = $("#jenis_kelompok").val() || [];
          formData.activity_ids = $("#activitySelect").val() || [];
          formData.is_non_activity = $("#is_non_activity").is(":checked");

          $.ajax({
              url: '{{ route('beneficiary.store') }}',
              method: 'POST',
              data: JSON.stringify(formData),
              contentType: 'application/json',
              success: function(response) {
                  $("#ModalTambahPeserta").modal("hide");
                  Swal.fire("Success", "Beneficiary added!", "success").then(() => {
                      redrawTable();
                  });
              },
              error: function(xhr) {
                  Swal.fire("Error", xhr.responseJSON?.message || "Failed to add beneficiary.", "error");
              }
          });
      }

      // Simplified editRow: Populate form and show modal (no manual row update)
      function editRow(row) {
          const beneficiaryId = $(row).data('id');
          const beneficiary = beneficiaries.find(b => b.id === beneficiaryId);
          if (!beneficiary) {
              console.error("Beneficiary not found:", beneficiaryId);
              return;
          }

          $("#editRowId").val(beneficiaryId);
          $("#editNama").val(beneficiary.nama);
          $("#editNoTelp").val(beneficiary.no_telp);
          $("#editGender").val(beneficiary.jenis_kelamin).trigger("change");
          $("#editUsia").val(beneficiary.umur);
          $("#editRt").val(beneficiary.rt);
          $("#editRwBanjar").val(beneficiary.rw);
          $("#edit_is_non_activity").prop("checked", beneficiary.is_non_activity);
          $("#keterangan_edit").val(beneficiary.keterangan);

          const addOptionAndTriggerChange = (selector, text, value) => {
              const option = new Option(text || '-', value || '', true, true);
              $(selector).empty().append(option).trigger('change');
          };
          addOptionAndTriggerChange("#provinsi_id_edit", beneficiary.provinsi_nama || '-', beneficiary.provinsi_id || '');
          addOptionAndTriggerChange("#kabupaten_id_edit", beneficiary.kabupaten_nama || '-', beneficiary.kabupaten_id || '');
          addOptionAndTriggerChange("#kecamatan_id_edit", beneficiary.kecamatan_nama || '-', beneficiary.kecamatan_id || '');
          addOptionAndTriggerChange("#desa_id_edit", beneficiary.dusun?.desa?.nama || '-', beneficiary.dusun?.desa_id || '');
          addOptionAndTriggerChange("#dusun_id_edit", beneficiary.dusun?.nama || '-', beneficiary.dusun_id || '');

          $("#editKelompokRentan").empty();
          beneficiary.kelompok_marjinal.forEach(k => {
              $("#editKelompokRentan").append(new Option(k.nama, k.id, true, true));
          });
          $("#editKelompokRentan").val(beneficiary.kelompok_marjinal.map(k => k.id)).trigger("change");

          $("#editJenisKelompok").empty();
          beneficiary.jenis_kelompok.forEach(j => {
              $("#editJenisKelompok").append(new Option(j.nama, j.id, true, true));
          });
          $("#editJenisKelompok").val(beneficiary.jenis_kelompok.map(j => j.id)).trigger("change");

          $("#activitySelectEdit").val(beneficiary.penerima_activity.map(a => a.id.toString())).trigger("change");

          $("#editDataModal").modal("show");
      }

      // Simplified updateRow: Submit form via AJAX and redraw table
      function updateRow() {
          const form = document.getElementById("editDataForm");
          const beneficiaryId = $("#editRowId").val();
          if (!form.checkValidity()) {
              form.reportValidity();
              return;
          }

          const formData = $("#editDataForm").serializeArray().reduce((obj, item) => {
              if (obj[item.name]) {
                  if (!Array.isArray(obj[item.name])) obj[item.name] = [obj[item.name]];
                  obj[item.name].push(item.value);
              } else {
                  obj[item.name] = item.value;
              }
              return obj;
          }, {});
          formData.is_non_activity = $("#edit_is_non_activity").is(":checked");
          formData.kelompok_rentan = $("#editKelompokRentan").val() || [];
          formData.jenis_kelompok = $("#editJenisKelompok").val() || [];
          formData.activity_ids = $("#activitySelectEdit").val() || [];
          formData.program_id = '{{ $program->id }}';

          $.ajax({
              url: `{{ route('beneficiary.update', '') }}/${beneficiaryId}`,
              method: "PUT",
              data: JSON.stringify(formData),
              contentType: "application/json",
              success: function(response) {
                  $("#editDataModal").modal("hide");
                  Swal.fire("Success", "Beneficiary updated!", "success").then(() => {
                      redrawTable();
                  });
              },
              error: function(xhr) {
                  Swal.fire("Error", xhr.responseJSON?.message || "Failed to update beneficiary.", "error");
              }
          });
      }

      // Function to redraw the table by fetching updated data
      function redrawTable() {
          $.ajax({
              url: '{{ route('beneficiary.edit', $program->id) }}',
              method: 'GET',
              success: function(response) {
                  const newTableBody = $(response).find('#tableBody').html();
                  $('#tableBody').html(newTableBody);
                  rowCount = $('#tableBody tr').length;
              },
              error: function(xhr) {
                  Swal.fire("Error", "Failed to reload table data.", "error");
              }
          });
      }

      function deleteRow(row) {
          const beneficiaryId = $(row).data('id');
          Swal.fire({
              title: "Are you sure?",
              text: "This action cannot be undone!",
              icon: "warning",
              showCancelButton: true,
              confirmButtonText: "Yes, delete!"
          }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                      url: `{{ route('beneficiary.destroy', '') }}/${beneficiaryId}`,
                      method: "DELETE",
                      success: function() {
                          Swal.fire("Deleted!", "Beneficiary removed.", "success").then(() => {
                              redrawTable();
                          });
                      },
                      error: function(xhr) {
                          Swal.fire("Error", xhr.responseJSON?.message || "Failed to delete.", "error");
                      }
                  });
              }
          });
      }

      $("#addDataBtn").on("click", function(e) {
          e.preventDefault();
          addRow();
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
          deleteRow(this);
      });
  });
</script>
```

---

### Simplifications Applied

1. **`addRow`**:

   - Already simplified in the previous response: submits the form via AJAX and redraws the table on success.
   - No manual row construction; relies on server-side rendering via `redrawTable()`.

2. **`editRow`**:

   - Simplified to only populate the edit modal with data from the `beneficiaries` array and show it.
   - Removed unnecessary complexity around manual row updates (since `updateRow` now handles this via redraw).
   - Still uses `addOptionAndTriggerChange` for location fields, but this is minimal and necessary for Select2.

3. **`updateRow`**:

   - Simplified to submit the form data via AJAX to `beneficiary.update`.
   - On success, hides the modal and calls `redrawTable()` instead of manually updating the row’s DOM elements.
   - Eliminates the need for complex DOM manipulation (e.g., updating each `<td>` individually).

4. **`redrawTable`**:
   - Centralized function to fetch the updated table HTML from the `beneficiary.edit` route and replace `#tableBody`.
   - Used consistently across `addRow`, `updateRow`, and `deleteRow`.

---

### Why This is Simpler

- **No DOM Manipulation**: Previously, `updateRow` manually updated each `<td>` in the row with new data from the server response. Now, it delegates this to the server via `redrawTable()`, reducing client-side logic.
- **Consistency**: All CRUD operations (`addRow`, `updateRow`, `deleteRow`) follow the same pattern: AJAX submission followed by a table redraw.
- **Server-Driven**: Leverages Blade’s server-side rendering, ensuring the table always reflects the database state without client-side discrepancies.

### Remaining Complexity

- **Form Population in `editRow`**: Populating the modal with existing data (especially for Select2 fields) still requires some JavaScript due to the dynamic nature of `kelompok_rentan`, `jenis_kelompok`, and `activity_ids`. This could be further simplified by moving this logic to a server-rendered modal, but that would require a separate endpoint or partial view, which might not align with your current setup.
- **Select2 Handling**: The need to pre-populate and trigger changes for Select2 fields adds some overhead, but it’s unavoidable given the AJAX-driven dropdowns.

---

### Controller Requirements

The controller methods remain unchanged from the previous simplification:

```php
public function store(Request $request)
{
    $beneficiary = Meals_Penerima_Manfaat::create($request->only('program_id', 'nama', 'no_telp', 'jenis_kelamin', 'umur', 'rt', 'rw', 'dusun_id', 'is_non_activity', 'keterangan'));
    $beneficiary->kelompokMarjinal()->sync($request->kelompok_rentan);
    $beneficiary->jenisKelompok()->sync($request->jenis_kelompok);
    $beneficiary->penerimaActivity()->sync($request->activity_ids);
    return response()->json(['message' => 'Beneficiary added'], 201);
}

public function update(Request $request, $id)
{
    $beneficiary = Meals_Penerima_Manfaat::findOrFail($id);
    $beneficiary->update($request->only('nama', 'no_telp', 'jenis_kelamin', 'umur', 'rt', 'rw', 'dusun_id', 'is_non_activity', 'keterangan'));
    $beneficiary->kelompokMarjinal()->sync($request->kelompok_rentan);
    $beneficiary->jenisKelompok()->sync($request->jenis_kelompok);
    $beneficiary->penerimaActivity()->sync($request->activity_ids);
    return response()->json(['message' => 'Beneficiary updated']);
}

public function destroy($id)
{
    $beneficiary = Meals_Penerima_Manfaat::findOrFail($id);
    $beneficiary->delete();
    return response()->json(['message' => 'Beneficiary deleted'], 204);
}

public function edit(Program $program)
{
    abort_if(Gate::denies('beneficiary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    $beneficiaries = Meals_Penerima_Manfaat::where('program_id', $program->id)
        ->with(['kelompokMarjinal', 'jenisKelompok', 'penerimaActivity', 'dusun.desa'])
        ->get();
    $activities = $program->programOutputActivities()->get(['id', 'kode', 'nama']);
    return view('tr.beneficiary.edit', compact('program', 'beneficiaries', 'activities'));
}
```

---

### Testing

1. **Add a Row**:
   - Open `#ModalTambahPeserta`, submit the form, and verify the table updates with the new row after redraw.
2. **Edit a Row**:
   - Click `.edit-btn`, modify the form, submit via `#updateDataBtn`, and confirm the table reflects the changes after redraw.
3. **Delete a Row**:
   - Click `.delete-btn`, confirm, and ensure the row is removed after redraw.

---

### Further Simplification (Optional)

If you want to push this even further:

- **Server-Rendered Modals**: Move the modal population to the server by creating an API endpoint (e.g., `GET /beneficiary/{id}/edit-form`) that returns the pre-filled modal HTML. This would eliminate most of `editRow`’s JavaScript.
- **Single AJAX Endpoint**: Create a single endpoint to fetch the table body HTML (e.g., `GET /beneficiary/{program_id}/table`) instead of the full `edit` route, reducing payload size.

However, the current version is already significantly simpler than the original, balancing maintainability and functionality. Let me know if you’d like to explore these additional options or if this meets your needs!
