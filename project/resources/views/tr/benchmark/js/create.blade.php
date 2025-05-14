<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    $(document).ready(function () {
        $('#benchmarkForm').on('submit', function (e) {
            e.preventDefault();

            let form = $(this)[0];
            let formData = new FormData(form);
            
            $.ajax({
                url: "{{ route('api.benchmark.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Benchmark berhasil disimpan!',
                    });
                    $('#benchmarkForm')[0].reset();
                    $('.select2').val(null).trigger('change'); // reset select2
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    $.each(errors, function (key, value) {
                        errorMessage += `- ${value.join(', ')}\n`;
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal menyimpan!',
                        text: errorMessage,
                        customClass: {
                            popup: 'text-start'
                        }
                    });
                }
            });
        });


    $('#kabupaten_id').select2({
        allowClear: true,
        placeholder: "Pilih Kabupaten",
        ajax: {
            url: "{{ route('api.benchmark.kabupaten') }}",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return {
                    search: params.term, // kirim parameter pencarian
                    page: params.page || 1 // pagination
                };
            },
            processResults: function(response) {
                // console.log("Data Model dari API:", response); // Debugging
                return {
                    results: response.results,
                    pagination: {
                        more: response.pagination.more
                    }
                };
            },
            cache: true
        }
    });

    $('#kecamatan_id').select2({
        allowClear: true,
        placeholder: "Pilih Kecamatan",
        ajax: {
            url: "{{ route('api.benchmark.kecamatan') }}",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return {
                    search: params.term, // kirim parameter pencarian
                    page: params.page || 1 // pagination
                };
            },
            processResults: function(response) {
                // console.log("Data Model dari API:", response); // Debugging
                return {
                    results: response,
                    pagination: {
                        more: response.pagination.more
                    }
                };
            },
            cache: true
        }
    });

    $('#desa_id').select2({
        allowClear: true,
        placeholder: "Pilih Desa",
        ajax: {
            url: "{{ route('api.benchmark.desa') }}",   
            dataType: "json",
            delay: 250,
            data: function(params) {
                return {
                    search: params.term, // kirim parameter pencarian
                    page: params.page || 1 // pagination
                };
            },
            processResults: function(response) {
                // console.log("Data Model dari API:", response); // Debugging
                return {
                    results: response.results,
                    pagination: {
                        more: response.pagination.more
                    }
                };
            },
            cache: true
        }
    });
});
</script>

<script>
    $(document).ready(function () {
  // 1) Inisialisasi DataTable Modal Program
  let programTable = $('#list_program_kegiatan').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route("api.benchmark.programs") }}',
    columns: [
      { data: 'kode', name: 'kode' },
      { data: 'nama', name: 'nama' },
      { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
    console.log(ajax)
  });

  // 2) Inisialisasi Select2 Jenis Kegiatan
  $('#jenis_kegiatan_id').select2({
    allowClear: true,
    placeholder: "Pilih Jenis Kegiatan",
    ajax: {
      url: "{{ route('api.benchmark.jenis-kegiatan') }}",
      dataType: "json",
      delay: 250,
      data: params => ({
        search: params.term,
        page: params.page || 1
      }),
      processResults: resp => ({
        results: resp.results.map(i => ({ id: i.id, text: i.nama })),
        pagination: { more: resp.pagination.more }
      }),
      cache: true
    }
  });

  // 3) Pilih Program → simpan ke form, baru allow memilih Jenis Kegiatan
  $(document).on('click', '.select-program', function () {
    const id   = $(this).data('id');
    const kode = $(this).data('kode');
    const nama = $(this).data('nama');

    $('#ModalDaftarProgram').modal('hide');
    $('#program_id').val(id);
    $('#kode_program').val(kode);
    $('#nama_program').val(nama);

    // reset jenis & kegiatan
    $('#jenis_kegiatan_id').val(null).trigger('change');
    $('#kegiatan_id').val(null).trigger('change');
  });

  // 4) Setelah pilih Jenis Kegiatan → fetch & tampilkan modal Kegiatan
  $('#jenis_kegiatan_id').on('change', function () {
    const jenisId   = $(this).val();
    const programId = $('#program_id').val();

    if (!programId) {
      alert('Silakan pilih Program terlebih dahulu.');
      // reset pilihan jenis kembali
      $('#jenis_kegiatan_id').val(null).trigger('change');
      return;
    }

    // panggil API untuk get kegiatan
    $.ajax({
      url: '{{ route("api.benchmark.kegiatan") }}',
      type: 'GET',
      data: {
        program_id: programId,
        jenis_kegiatan_id: jenisId
      },
      success: function (response) {
        let tbody = '';
        // misal struktur response langsung activities array
        response.forEach(activity => {
          tbody += `<tr>
            <td>${activity.kode}</td>
            <td>${activity.nama}</td>
            <td>${activity.deskripsi ?? '-'}</td>
            <td>${activity.indikator ?? '-'}</td>
            <td>${activity.target ?? '-'}</td>
            <td>
              <button class="btn btn-sm btn-success pilih-kegiatan"
                      data-id="${activity.id}"
                      data-kode="${activity.kode}"
                      data-nama="${activity.nama}">
                <i class="bi bi-check"></i>
              </button>
            </td>
          </tr>`;
        });

        $('#list_program_out_activity tbody').html(
          tbody || '<tr><td colspan="6">Tidak ada kegiatan.</td></tr>'
        );
        $('#ModalDaftarProgramActivity').modal('show');
      },
      error: function () {
        alert('Gagal mengambil kegiatan');
      }
    });
  });

  // 5) Pilih Kegiatan → simpan ke form
  $(document).on('click', '.pilih-kegiatan', function () {
    const kode = $(this).data('kode');
    const nama = $(this).data('nama');

    $('#kode_kegiatan').val(kode);
    $('#nama_kegiatan').val(nama);
    $('#ModalDaftarProgramActivity').modal('hide');
  });
});





//    $(function () {
//     let programTable;
//     const searchingText = @json(__('cruds.activity.search'));

//     const showToast = (icon, title, text = '', timer = 2000) => {
//         Toast.fire({ icon, title, text, timer, position: "top-end", timerProgressBar: true });
//     };

//     const fetchKegiatanByProgramJenis = () => {
//         const programId = $('#program_id').val();
//         const jenisKegiatanId = $('#jenis_kegiatan_id').val();

//         if (!programId || !jenisKegiatanId) return;

//         $.ajax({
//             url: '{{ route("api.kegiatan.jenis_kegiatan") }}', 
//             method: 'GET',
//             data: {
//                 program_id: programId,
//                 jenis_kegiatan_id: jenisKegiatanId
//             },
//             beforeSend: () => showToast("info", "Mengambil daftar kegiatan..."),
//             success: (data) => {
//                 const kegiatanSelect = $('#kegiatan_id');
//                 kegiatanSelect.empty();

//                 if (data.length === 0) {
//                     kegiatanSelect.append(`<option value="">Tidak ada kegiatan</option>`);
//                 } else {
//                     kegiatanSelect.append(`<option value="">Pilih Kegiatan</option>`);
//                     $.each(data, (i, item) => {
//                         kegiatanSelect.append(`<option value="${item.id}">${item.nama}</option>`);
//                     });
//                 }
//             },
//             error: () => showToast("error", "Gagal mengambil data kegiatan.")
//         });
//     };

//     // Trigger saat select berubah
//     $('#program_id, #jenis_kegiatan_id').on('change', fetchKegiatanByProgramJenis);


//     const renderActivities = (data) => {
//         const tbody = $('#list_program_out_activity tbody').empty();
//         if (data.length) {
//             data.forEach(a => {
//                 tbody.append(`
//                     <tr data-id="${a.id}" data-kode="${a.kode}" data-nama="${a.nama}">
//                         <td>${a.kode}</td><td>${a.nama}</td><td>${a.deskripsi}</td>
//                         <td>${a.indikator}</td><td>${a.target}</td>
//                         <td class="text-center">
//                             <button class="btn btn-sm btn-info select-activity"><i class="bi bi-plus-lg"></i></button>
//                         </td>
//                     </tr>
//                 `);
//             });
//         } else {
//             tbody.append(`<tr><td colspan="6" class="text-center">{{ __('global.no_results') }}</td></tr>`);
//         }
//         $('#ModalDaftarProgramActivity').modal('show');
//     };

//     $('#kode_kegiatan').click(function (e) {
//         e.preventDefault();
//         const programId = $('#program_id').val();
//         if (!programId) {
//             showToast("warning", "Program belum dipilih", "Pilih kode program terlebih dahulu.");
//             return;
//         }
//         fetchProgramActivities(programId);
//      });

//     $(document).on('click', '.select-activity', function () {
//         const tr = $(this).closest('tr');
//         $('#programoutcomeoutputactivity_id').val(tr.data('id')).trigger('change');
//         $('#kode_kegiatan').val(tr.data('kode'));
//         $('#nama_kegiatan').val(tr.data('nama')).prop('disabled', true).focus();
//         setTimeout(() => $('#ModalDaftarProgramActivity').modal('hide'), 200);
//     });

//     const clearTable = () => $("#dataTable tbody").empty();

//     const confirmProgramChange = () => {
//         Swal.fire({
//             title: 'Change Program Confirmation',
//             text: 'Changing the program will clear all current entries. Do you want to proceed?',
//             icon: 'warning', showCancelButton: true,
//             confirmButtonText: 'Yes, Clear All', cancelButtonText: 'No, Cancel'
//         }).then(res => {
//             if (res.isConfirmed) {
//             clearTable();
//             $('#kode_kegiatan').val('').prop('disabled', true);
//             $('#nama_kegiatan').val('').prop('disabled', false);
//             $('#programoutcomeoutputactivity_id').val('');
//         }
//         });
//     };

//     $('#kode_program').click(function (e) {
//         e.preventDefault();
//         if ($("#dataTable tbody tr").length > 0) return confirmProgramChange();

//         setTimeout(() => {
//             if (!programTable) {
//                 programTable = $('#list_program_kegiatan').DataTable({
//                     processing: true, serverSide: true, responsive: true, bDestroy: true,
//                     ajax: "{{ route('api.beneficiary.program') }}",
//                     columns: [
//                         { data: 'kode', name: 'kode', width: "20%" },
//                         { data: 'nama', name: 'nama', width: "70%" },
//                         { data: 'action', name: 'action', orderable: false, searchable: false, width: "10%", className: "text-center" }
//                     ],
//                     language: { processing: " Memuat..." },
//                     lengthMenu: [5, 10, 25, 50, 100]
//                 });
//             }
//         }, 500);
//         $('#ModalDaftarProgram').removeAttr('inert').modal('show');
//     });

//     $('#provinsi_id').select2({
//     allowClear: true,
//     placeholder: "Pilih Provinsi",
//     ajax: {
//         url: "{{ route('api.benchmark.provinsi') }}",
//         dataType: "json",
//         delay: 250,
//         data: function(params) {
//             return {
//                 search: params.term,
//                 page: params.page || 1,
//                 kegiatan_id: $('#programoutcomeoutputactivity_id').val()
//             };
//         },
//         processResults: function(response) {
//             return {
//                     results: response.results,
//                     pagination: {
//                         more: response.pagination.more
//                     }
//                 };
//             },
//             cache: true
//         }
//     });

//     $('#ModalDaftarProgram').on('hidden.bs.modal', function () {
//         if (programTable) {
//             programTable.destroy();
//             programTable = null;
//         }
//         $(this).attr('inert', '');
//     });

//     $(document).on('click', '.select-program', function () {
//         $('#program_id').val($(this).data('id'));
//         $('#kode_program').val($(this).data('kode'));
//         $('#nama_program').val($(this).data('nama')).prop('disabled', true);
//         $('#ModalDaftarProgram').modal('hide');
//     }); 
    
//     const fetchProgramActivities = (id) => {
//         $.ajax({
//             url: '{{ route("api.program.kegiatan", ":id") }}'.replace(':id', id),
//             method: 'GET',
//             dataType: 'JSON',
//             beforeSend: () => showToast("info", searchingText + '...'),
//             success: (data) => setTimeout(() => renderActivities(data), 500),
//             error: () => showToast("error", "Failed to fetch activities.")
//         });
//     };
// });

</script>
