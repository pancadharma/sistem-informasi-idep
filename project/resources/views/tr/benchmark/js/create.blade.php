<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    $(document).ready(function () {
      $('#benchmarkForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this)[0];
        let formData = new FormData(form);
        
        $.ajax({
            url: "{{ route('benchmark.api.benchmark.store') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
  });

  $(document).ready(function () {
    // 1) Inisialisasi DataTable Modal Program
    let programTable = $('#list_program_kegiatan').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route("benchmark.api.benchmark.programs") }}',
      columns: [
        { data: 'kode', name: 'kode' },
        { data: 'nama', name: 'nama' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
      ]
    });

    // 2) Inisialisasi Select2 Jenis Kegiatan
    $('#jeniskegiatan_id').select2({
      allowClear: true,
      placeholder: "Pilih Jenis Kegiatan",
      ajax: {
        url: "{{ route('benchmark.api.benchmark.jenis-kegiatan') }}",
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

    // 3) Pilih Program
    $(document).on('click', '.select-program', function () {
      const id   = $(this).data('id');
      const kode = $(this).data('kode');
      const nama = $(this).data('nama');

      $('#ModalDaftarProgram').modal('hide');
      $('#program_id').val(id);
      $('#kode_program').val(kode);
      $('#nama_program').val(nama);
    });

    // 4) Pilih Jenis Kegiatan → fetch modal kegiatan
    $('#jeniskegiatan_id').on('select2:select', function () {
      const jenisId   = $(this).val();
      const programId = $('#program_id').val();

      // Validasi: Harus pilih program dulu
      if (!programId) {
        toastr.warning('Silakan pilih Program terlebih dahulu.');
        $(this).val(null).trigger('change');
        return;
      }

      // Fetch kegiatan via AJAX
      $.ajax({
        url: '{{ route("benchmark.api.benchmark.kegiatan") }}',
        type: 'GET',
        data: {
          program_id: programId,
          jeniskegiatan_id: jenisId
        },
        success: function (response) {
          let tbody = '';
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
            console.log(
              'kegiatan : ', activity, 
              'id : ', activity.id, 
              'kode : ', activity.kode
            )
          });

          $('#list_program_out_activity tbody').html(
            tbody || '<tr><td colspan="6">Tidak ada kegiatan.</td></tr>'
          );
          $('#ModalDaftarProgramActivity').modal('show');
        },
        error: function () {
          toastr.error('Gagal mengambil data kegiatan.');
        }
      });
    });

    // 5) Pilih Kegiatan
    $(document).on('click', '.pilih-kegiatan', function () {
      const id = $(this).data('id');
      const kode = $(this).data('kode');
      const nama = $(this).data('nama');

      $('#kegiatan_id').val(id);
      $('#kode_kegiatan').val(kode);
      $('#nama_kegiatan').val(nama);
      $('#ModalDaftarProgramActivity').modal('hide');
    });

    $('#kode_kegiatan').on('click', function (e) {
      const programId = $('#program_id').val();
      const jenisId = $('#jeniskegiatan_id').val();

      if (!programId || !jenisId) {
          e.preventDefault();     // cegah default hanya kalau belum lengkap
          e.stopPropagation();    // cegah bubbling hanya kalau belum lengkap
          toastr.error('Silakan pilih Program dan Jenis Kegiatan terlebih dahulu.');
      }
      // kalau programId dan jenisId sudah ada → biarkan default jalan (modal terbuka)
    });

    function setLokasiWilayah(kegiatanId) {
    $.ajax({
      url: '{{ route("benchmark.api.benchmark.lokasi") }}',
      type: 'GET',
      data: { kegiatan_id: kegiatanId },
      success: function (data) {
  console.log('FULL RESPONSE : ', data);
  if (!data || data.length === 0) {
    toastr.warning('Lokasi tidak ditemukan.');
    return;
  }

  // Clear select2 sebelum tambah baru
  $('#provinsi_id').empty();
  $('#kabupaten_id').empty();
  $('#kecamatan_id').empty();
  $('#desa_id').empty();

  const provinsiSet = new Set();
  const kabupatenSet = new Set();
  const kecamatanSet = new Set();
  const desaSet = new Set();

  // Loop semua lokasi yang dikembalikan dari server
  data.forEach((lokasi, index) => {
    if (!provinsiSet.has(lokasi.provinsi_id)) {
      setSelect2Value('#provinsi_id', lokasi.provinsi_id, lokasi.provinsi_nama);
      provinsiSet.add(lokasi.provinsi_id);
    }

    if (!kabupatenSet.has(lokasi.kabupaten_id)) {
      setSelect2Value('#kabupaten_id', lokasi.kabupaten_id, lokasi.kabupaten_nama);
      kabupatenSet.add(lokasi.kabupaten_id);
    }

    if (!kecamatanSet.has(lokasi.kecamatan_id)) {
      setSelect2Value('#kecamatan_id', lokasi.kecamatan_id, lokasi.kecamatan_nama);
      kecamatanSet.add(lokasi.kecamatan_id);
    }

    if (!desaSet.has(lokasi.desa_id)) {
      setSelect2Value('#desa_id', lokasi.desa_id, lokasi.desa_nama);
      desaSet.add(lokasi.desa_id);
    }
  });

  // Pilih yang pertama (default select)
  $('#provinsi_id').val(data[0].provinsi_id).trigger('change');
  $('#kabupaten_id').val(data[0].kabupaten_id).trigger('change');
  $('#kecamatan_id').val(data[0].kecamatan_id).trigger('change');
  $('#desa_id').val(data[0].desa_id).trigger('change');
  },
      error: function () {
        toastr.error('Gagal mengambil data lokasi.');
      }
    });
  }

  // Fungsi bantu untuk set dan trigger Select2
  function setSelect2Value(selector, value, text) {
    const newOption = new Option(text, value, true, true);
    $(selector).append(newOption).trigger('change');
  }

  $(document).on('click', '.pilih-kegiatan', function () {
    const id   = $(this).data('id');
    const kode = $(this).data('kode');
    const nama = $(this).data('nama');

    $('#kode_kegiatan').val(kode);
    $('#nama_kegiatan').val(nama);
    $('#kegiatan_id').val(id);
    $('#ModalDaftarProgramActivity').modal('hide');

    setLokasiWilayah(id);
  });

  $('#provinsi_id, #kabupaten_id, #kecamatan_id, #desa_id').select2({
    allowClear: true,
    width: '100%'
  });

  $('#usercompiler_id').select2({
      placeholder: 'Pilih Compiler',
      ajax: {
          url: '{{ route("benchmark.api.benchmark.compiler") }}',
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


  });

</script>
