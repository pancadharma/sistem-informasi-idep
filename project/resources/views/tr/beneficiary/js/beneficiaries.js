// script for add row and edit row of table benefeciaries (googled)
$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

if (typeof $ === "undefined") {
  console.error(
    "jQuery is not included.  Please include jQuery in your HTML file.",
  );
}

$(document).ready(function () {
  let rowCount = 0;

  function initSelect2() {
    const disabilitasOptions = [
      { id: "Fisik", text: "Fisik" },
      { id: "Sensorik", text: "Sensorik" },
      { id: "Intelektual", text: "Intelektual" },
      { id: "Mental", text: "Mental" },
      { id: "Ganda", text: "Ganda" },
    ];

    // Kelompok Rentan (Add Modal)
    $("#kelompok_rentan").select2({
      placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
      dropdownParent: $("#ModalTambahPeserta"),
      width: "100%",
      closeOnSelect: false,
      allowClear: true,
      ajax: {
        url: '{{ route("api.beneficiary.kelompok.rentan") }}',
        dataType: "json",
        delay: 250,
        data: function (params) {
          return { search: params.term, page: params.page || 1 };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: data.results,
            pagination: { more: data.pagination.more },
          };
        },
        cache: true,
      },
    });

    // Kelompok Rentan (Edit Modal) - AJAX handling with pre-selection
    $("#editKelompokRentan").select2({
      allowClear: true,
      ajax: {
        url: '{{ route("api.beneficiary.kelompok.rentan") }}',
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            search: params.term,
            page: params.page || 1,
            // NO selected: [...] here.  This was causing problems.
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          let results = data.results;

          // Get currently selected options (important for pre-population)
          let selected = $("#editKelompokRentan").select2("data");

          // Merge selected options with API results, ensuring no duplicates
          let mergedResults = [...selected]; // Start with already selected
          results.forEach((apiItem) => {
            // Important: Use a consistent comparison (e.g., string IDs)
            if (
              !mergedResults.some(
                (selectedItem) =>
                  String(selectedItem.id) === String(apiItem.id),
              )
            ) {
              mergedResults.push(apiItem);
            }
          });

          return {
            results: mergedResults, // Use the *merged* results
            pagination: { more: data.pagination.more },
          };
        },
        cache: true,
      },
      dropdownParent: $("#editDataModal"),
      width: "100%",
      closeOnSelect: false,
      placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
    });

    $("#disabilitas").select2({
      data: disabilitasOptions,
      closeOnSelect: false,
      placeholder:
        '{{ __("global.select") ." ". __("cruds.beneficiary.penerima.disability") }} ...',
      dropdownParent: $("#ModalTambahPeserta"),
      width: "100%",
    });

    $("#editDisabilitas").select2({
      data: disabilitasOptions,
      placeholder:
        '{{ __("global.select") ." ". __("cruds.beneficiary.penerima.disability") }} ...',
      dropdownParent: $("#editDataModal"),
      width: "100%",
    });

    // Desa (Add Modal)
    $(`#desa_id`).select2({
      placeholder:
        '{{ __("global.pleaseSelect") ." ". __("cruds.desa.title") }}',
      ajax: {
        url: '{{ route("api.beneficiary.desa") }}',
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            search: params.term,
            page: params.page || 1,
          };
        },
        processResults: function (data, params) {
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
      dropdownPositionOption: "below",
      dropdownParent: $("#ModalTambahPeserta"),
    });

    // Desa (Edit Modal)
    $(`#editDesa`).select2({
      placeholder:
        '{{ __("global.pleaseSelect") ." ". __("cruds.desa.title") }}',
      ajax: {
        url: '{{ route("api.beneficiary.desa") }}',
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            search: params.term,
            page: params.page || 1,
          };
        },
        processResults: function (data, params) {
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
      dropdownPositionOption: "below",
    });

    // Dusun (Add Modal)
    $("#dusun_id").select2({
      placeholder:
        '{{ __("global.pleaseSelect") ." ". __("cruds.dusun.title") }}',
      ajax: {
        url: '{{ route("api.beneficiary.dusun") }}',
        dataType: "json",
        delay: 250,
        data: (params) => ({
          search: params.term,
          desa_id: $("#desa_id").val(),
          page: params.page || 1,
        }),
        processResults: function (data) {
          return {
            results: data.results,
            pagination: data.pagination,
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
      dropdownPositionOption: "below",
      dropdownParent: $("#ModalTambahPeserta"),
    });

    // Dusun (Edit Modal)
    $("#editDusun").select2({
      placeholder:
        '{{ __("global.pleaseSelect") ." ". __("cruds.dusun.title") }}',
      ajax: {
        url: '{{ route("api.beneficiary.dusun") }}',
        dataType: "json",
        delay: 250,
        data: (params) => ({
          search: params.term,
          desa_id: $("#editDesa").val(),
          page: params.page || 1,
        }),
        processResults: function (data) {
          return {
            results: data.results,
            pagination: data.pagination,
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
      allowClear: true,
      dropdownParent: $("#editDataModal"),
    });
  }

  function updateAgeCheckmarks(usiaCell) {
    const row = usiaCell.closest("tr");
    const ageText = usiaCell.text().trim();
    const age = parseInt(ageText, 10);

    const check0_17 = row.find(".age-0-17");
    const check18_24 = row.find(".age-18-24");
    const check25_59 = row.find(".age-25-59");
    const check60_plus = row.find(".age-60-plus");

    if (isNaN(age)) {
      check0_17.empty();
      check18_24.empty();
      check25_59.empty();
      check60_plus.empty();
      return;
    }

    check0_17.html(
      age >= 0 && age <= 17 ? '<span class="checkmark">✔</span>' : "",
    );
    check18_24.html(
      age > 17 && age <= 24 ? '<span class="checkmark">✔</span>' : "",
    );
    check25_59.html(
      age >= 25 && age <= 59 ? '<span class="checkmark">✔</span>' : "",
    );
    check60_plus.html(age >= 60 ? '<span class="checkmark">✔</span>' : "");
  }

  function closeModal() {
    $("#ModalTambahPeserta").modal("hide");
  }

  function getRandomColor() {
    const colors = [
      "primary",
      "secondary",
      "success",
      "danger",
      "warning",
      "info",
      "light",
      "dark",
    ];
    const randomIndex = Math.floor(Math.random() * colors.length);
    return colors[randomIndex];
  }

  function addRow(data) {
    rowCount++;

    const disabilitasArray = Array.isArray(data.disabilitas)
      ? data.disabilitas
      : [];
    const kelompokRentanArray = Array.isArray(data.kelompok_rentan)
      ? data.kelompok_rentan
      : [];

    const desaText = $("#desa_id option:selected").text();
    const dusunText = $("#dusun_id option:selected").text();

    const disabilitasText = disabilitasArray.map((value) => {
      const option = $(
        '#ModalTambahPeserta select[name="disabilitas"] option[value="' +
          value +
          '"]',
      );
      const text = option.length ? option.text() : "";
      const randomColor = getRandomColor();
      return `<span class="badge badge-${randomColor}">${text}</span>`;
    });

    const kelompokRentanData = kelompokRentanArray.map((value) => {
      const option = $("#kelompok_rentan")
        .select2("data")
        .find((opt) => opt.id === value) || { id: value, text: value };
      return {
        id: option.id,
        text: option.text,
      };
    });

    const kelompokRentanText = kelompokRentanData.map((item) => {
      const randomColor = getRandomColor();
      return `<span class="badge badge-${randomColor}">${item.text}</span>`;
    });

    const genderText = $(
      '#ModalTambahPeserta select[name="gender"] option[value="' +
        data.gender +
        '"]',
    ).text();
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
                <td class="text-center align-middle" id="headerActivityProgram" data-activity-selected="0"></td>
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-info edit-btn" id="edit-btn-${rowCount}"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash3"></i></button>
                </td>
            </tr>
            `;

    $("#tableBody").append(newRow);

    updateAgeCheckmarks(
      $("#dataTable tbody")
        .find(`tr[data-row-id="${rowCount}"]`)
        .find(".usia-cell"),
    );
    resetFormAdd();
  }

  function saveRow() {
    const form = $("#dataForm")[0];
    if (form.checkValidity()) {
      // Serialize the form data.  This handles multiple selects correctly.
      const formData = {};
      $.each($("#dataForm").serializeArray(), function (_, field) {
        if (formData[field.name]) {
          // Handle multiple values
          if (!Array.isArray(formData[field.name])) {
            formData[field.name] = [formData[field.name]];
          }
          formData[field.name].push(field.value);
        } else {
          formData[field.name] = field.value;
        }
      });

      addRow(formData); // Pass the processed formData
      resetFormAdd();
    } else {
      form.reportValidity();
    }
  }

  function resetFormAdd() {
    $("#dataForm")[0].reset();
    $("#kelompok_rentan").val(null).trigger("change");
    $("#kelompok_rentan").select2("destroy"); // Keep destroy/re-init
    initSelect2(); // Reinitialize after destroy.
    $("#disabilitas").val(null).trigger("change");
    $(".select2-multiple").val(null).trigger("change");
    $(".select2").val(null).trigger("change");
    $("#ModalTambahPeserta").modal("hide");
  }

  function editRow(row) {
    const currentRow = $(row).closest("tr");
    const rowId = currentRow.data("row-id");

    const disabilitas = currentRow
      .find("td[data-disabilitas]")
      .attr("data-disabilitas");
    const disabilitasValues = disabilitas ? disabilitas.split(",") : [];

    // IMPORTANT: Get the *FULL* data, not just the values.
    const kelompokRentanFullData = JSON.parse(
      currentRow
        .find("td[data-kelompok_rentan_full]")
        .attr("data-kelompok_rentan_full") || "[]",
    );
    const kelompokRentanValues = kelompokRentanFullData.map((item) => item.id); // Extract IDs

    const desaId = currentRow.find("td[data-desa-id]").data("desa-id");
    const desaNama = currentRow.find("td[data-desa-id]").data("desa-nama");
    const dusunId = currentRow.find("td[data-dusun-id]").data("dusun-id");
    const dusunNama = currentRow.find("td[data-dusun-id]").data("dusun-nama");

    // --- POPULATE EDIT FORM FIELDS ---
    $("#editRowId").val(rowId);
    $("#editNama").val(currentRow.find("td[data-nama]").attr("data-nama"));
    $("#editGender")
      .val(currentRow.find("td[data-gender]").attr("data-gender"))
      .trigger("change");

    $("#editDisabilitas").val(disabilitasValues).trigger("change"); // Set after initialization

    $("#editRt").val(currentRow.find("td[data-rt]").attr("data-rt"));
    $("#editRwBanjar").val(
      currentRow.find("td[data-rw_banjar]").attr("data-rw_banjar"),
    );

    // Destroy and Reinitialize Select2 for editDesa and editDusun BEFORE setting values.
    $("#editDesa").select2("destroy");
    $("#editDesa")
      .append(new Option(desaNama, desaId, true, true))
      .trigger("change"); // Correctly set value
    $("#editDesa").select2({
      // Re-initialize
      placeholder:
        '{{ __("global.pleaseSelect") ." ". __("cruds.desa.title") }}',
      ajax: {
        url: '{{ route("api.beneficiary.desa") }}',
        dataType: "json",
        delay: 250,
        data: function (params) {
          return { search: params.term, page: params.page || 1 };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: data.results,
            pagination: { more: data.pagination.more },
          };
        },
        cache: true,
      },
      dropdownParent: $("#editDataModal"),
      dropdownPositionOption: "below",
    });
    $("#editDusun").select2("destroy"); // Destroy before re-init or setting values
    $("#editDusun")
      .append(new Option(dusunNama, dusunId, true, true))
      .trigger("change"); //Correct way
    $("#editDusun").select2({
      //Reinitialize
      placeholder:
        '{{ __("global.pleaseSelect") ." ". __("cruds.dusun.title") }}',
      ajax: {
        url: '{{ route("api.beneficiary.dusun") }}',
        dataType: "json",
        delay: 250,
        data: (params) => ({
          search: params.term,
          desa_id: $("#editDesa").val(), // Only use editDesa here, not desa_id
          page: params.page || 1,
        }),
        processResults: function (data) {
          return { results: data.results, pagination: data.pagination };
        },
        cache: true,
      },
      minimumInputLength: 0,
      allowClear: true,
      dropdownParent: $("#editDataModal"),
    });

    $("#editNoTelp").val(
      currentRow.find("td[data-no_telp]").attr("data-no_telp"),
    );
    $("#editJenisKelompok").val(
      currentRow.find("td[data-jenis_kelompok]").attr("data-jenis_kelompok"),
    );
    $("#editUsia").val(currentRow.find("td[data-usia]").attr("data-usia"));

    // PRE-POPULATE Kelompok Rentan:  Set the values *AFTER* Select2 is initialized.
    $("#editKelompokRentan").val(kelompokRentanValues).trigger("change");

    $("#editDataModal").modal("show"); // Display the modal
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
      // Correctly serialize form data, handling multiple selects
      const formData = {};
      $.each($("#editDataForm").serializeArray(), function (_, field) {
        if (formData[field.name]) {
          if (!Array.isArray(formData[field.name])) {
            formData[field.name] = [formData[field.name]];
          }
          formData[field.name].push(field.value);
        } else {
          formData[field.name] = field.value;
        }
      });

      const genderText = $("#editGender option:selected").text();

      // Get selected data *after* changes.  Use .select2('data')
      const kelompokRentanData = $("#editKelompokRentan").select2("data");
      const kelompokRentanHtml = kelompokRentanData
        .map((item) => {
          const randomColor = getRandomColor();
          return `<span class="badge badge-${randomColor}">${item.text}</span>`;
        })
        .join(" ");

      // Get selected data *after* changes.  Use .select2('data')
      const disabilitasData = $("#editDisabilitas").select2("data"); // Get *full* data objects
      const disabilitasHtml = disabilitasData
        .map((item) => {
          // Map over the full data objects
          const randomColor = getRandomColor();
          return `<span class="badge badge-${randomColor}">${item.text}</span>`; // Use item.text
        })
        .join(" ");

      const currentRow = $("#dataTable tbody").find(
        `tr[data-row-id="${rowId}"]`,
      );
      if (currentRow.length === 0) {
        console.error("Row not found");
        return;
      }

      // Use formData (the correctly serialized data)
      currentRow
        .find("td[data-nama]")
        .text(formData.nama)
        .attr("data-nama", formData.nama);
      currentRow
        .find("td[data-gender]")
        .text(genderText)
        .attr("data-gender", formData.gender);
      currentRow
        .find("td[data-disabilitas]")
        .html(disabilitasHtml)
        .attr("data-disabilitas", (formData.disabilitas || []).join(",")); // Handle potential undefined
      currentRow
        .find("td[data-kelompok_rentan]")
        .html(kelompokRentanHtml)
        .attr(
          "data-kelompok_rentan",
          (formData.kelompok_rentan || []).join(","),
        ); // Handle potential undefined
      currentRow
        .find("td[data-kelompok_rentan_full]")
        .attr("data-kelompok_rentan_full", JSON.stringify(kelompokRentanData)); // Update full data.
      currentRow
        .find("td[data-rt]")
        .text(formData.rt)
        .attr("data-rt", formData.rt);
      currentRow
        .find("td[data-rw_banjar]")
        .text(formData.rw_banjar)
        .attr("data-rw_banjar", formData.rw_banjar);
      currentRow
        .find("td[data-no_telp]")
        .text(formData.no_telp)
        .attr("data-no_telp", formData.no_telp);
      currentRow
        .find("td[data-jenis_kelompok]")
        .text(formData.jenis_kelompok)
        .attr("data-jenis_kelompok", formData.jenis_kelompok);
      currentRow
        .find("td[data-usia]")
        .text(formData.usia)
        .attr("data-usia", formData.usia);

      currentRow
        .find("td[data-desa-id]")
        .attr("data-desa-id", desaId)
        .attr("data-desa-nama", desaText)
        .text(desaText);
      currentRow
        .find("td[data-dusun-id]")
        .attr("data-dusun-id", dusunId)
        .attr("data-dusun-nama", dusunText)
        .text(dusunText);

      updateAgeCheckmarks(currentRow.find(".usia-cell"));

      $("#editDataModal").modal("hide");

      // Reset form and Select2 instances after update
      $("#editDataForm")[0].reset(); // Reset the form
      $(".select2-multiple").val(null).trigger("change");
      $(".select2").val(null).trigger("change"); // Clear all general Select2
    } else {
      form.reportValidity();
    }
  }

  function deleteRow(row) {
    $(row).closest("tr").remove();
  }

  // --- Event Listeners ---
  initSelect2(); // Call initSelect2() *once*

  $("#desa_id, #editDesa").on("change", function () {
    const targetDusun =
      $(this).attr("id") === "desa_id" ? "#dusun_id" : "#editDusun";
    $(targetDusun).select2("destroy"); // Destroy before re-init
    $(targetDusun).val(null).trigger("change");
    // Re-init with correct ajax options (you were missing this before)
    $(targetDusun).select2({
      placeholder:
        '{{ __("global.pleaseSelect") ." ". __("cruds.dusun.title") }}',
      ajax: {
        url: '{{ route("api.beneficiary.dusun") }}', // Make sure this route is correct
        dataType: "json",
        delay: 250,
        data: (params) => ({
          search: params.term,
          desa_id: $(this).val(), // Use $(this).val() here
          page: params.page || 1,
        }),
        processResults: function (data) {
          return { results: data.results, pagination: data.pagination };
        },
        cache: true,
      },
      minimumInputLength: 0,
      allowClear: true,
      dropdownParent:
        $(this).attr("id") === "desa_id"
          ? $("#ModalTambahPeserta")
          : $("#editDataModal"), // Correct parent
    });
  });

  $("#addDataBtn").on("click", function () {
    let programId = $("#program_id").val();
    if (!programId || programId === "" || programId === undefined) {
      Toast.fire({
        text: '{{ __("global.pleaseSelect") ." ". __("cruds.program.title") }}',
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

      return false; // this mean that the user didn't select a program
    }
    $("#ModalTambahPeserta").modal("show");
  });

  $("#saveDataBtn").on("click", function (e) {
    e.preventDefault();
    saveRow();
  });

  $("#dataTable tbody").on("click", ".edit-btn", function (e) {
    e.preventDefault();
    editRow(this);
    $("#editDataModal").modal("show");
  });

  $("#updateDataBtn").on("click", function (e) {
    e.preventDefault();
    updateRow();
  });

  $("#dataTable tbody").on("click", ".delete-btn", function (e) {
    e.preventDefault();
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
        deleteRow(this);
        Swal.fire("Deleted!", "Your file has been deleted.", "success");
      }
    });
    return false;
  });

  $("#submitDataBtn").on("click", function () {
    let tableData = [];
    $("#dataTable tbody tr").each(function () {
      let row = $(this);
      let rowData = {
        nama: row.find("td[data-nama]").attr("data-nama"),
        gender: row.find("td[data-gender]").attr("data-gender"),
        disabilitas: row
          .find("td[data-disabilitas]")
          .attr("data-disabilitas")
          .split(","),
        kelompok_rentan: row
          .find("td[data-kelompok_rentan]")
          .attr("data-kelompok_rentan")
          .split(","),
        rt: row.find("td[data-rt]").attr("data-rt"),
        rw_banjar: row.find("td[data-rw_banjar]").attr("data-rw_banjar"),
        dusun_id: row.find("td[data-dusun-id]").attr("data-dusun-id"),
        desa_id: row.find("td[data-desa-id]").attr("data-desa-id"),
        no_telp: row.find("td[data-no_telp]").attr("data-no_telp"),
        jenis_kelompok: row
          .find("td[data-jenis_kelompok]")
          .attr("data-jenis_kelompok"),
        usia: row.find("td[data-usia]").attr("data-usia"),
      };
      tableData.push(rowData);
    });

    let jsonData = JSON.stringify(tableData, null, 2);
    $("#modalData").text(jsonData);
    $("#previewModalsData").modal("show");
  });

  $("#sendDataBtn").on("click", function () {
    let finalData = JSON.parse($("#modalData").text());

    $.ajax({
      url: "/beneficiary/kirim-peserta", // Replace with your actual endpoint
      method: "POST",
      data: JSON.stringify(finalData),
      contentType: "application/json",
      success: function (response) {
        alert("Data sent successfully!");
        $("#previewModal").modal("hide");
      },
      error: function (xhr, status, error) {
        alert("Error sending data: " + error);
      },
    });
  });

  $("#ModalTambahPeserta").on("shown.bs.modal", function () {
    $(this).removeAttr("inert");
  });

  $("#editDataModal").on("shown.bs.modal", function () {
    $(this).removeAttr("inert");
  });

  $("#ModalTambahPeserta").on("hide.bs.modal", function (e) {
    $(this).attr("inert", "");
    $(document.activeElement).blur();
  });

  $("#editDataModal").on("hide.bs.modal", function (e) {
    $(this).attr("inert", "");
    $(document.activeElement).blur();
  });
});
