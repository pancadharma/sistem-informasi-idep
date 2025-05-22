<script>
   $(document).ready(function () {
      $('#benchmarkForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this)[0];
        let formData = new FormData(form);
        let actionUrl = $('#benchmarkForm').attr('action');

        formData.append('_method', 'PUT');
        
        $.ajax({
            url: actionUrl,
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
                }).then(() => {
                    window.location.href = "{{ route('benchmark.index') }}";
                });
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

    $('#jeniskegiatan_id').select2({
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


function fetchKegiatan(programId, jenisId, showModal = false) {
  $('#error-jeniskegiatan').hide().text('');
  $.ajax({
    url: '{{ route("api.benchmark.kegiatan") }}',
    type: 'GET',
    data: {
      program_id: programId,
      jeniskegiatan_id: jenisId
    },
    success: function (response) {
      if (response.length === 0) {
        $('#error-jeniskegiatan').text('Tidak ada kegiatan terkait untuk jenis kegiatan ini.').show();
        $('#list_program_out_activity tbody').html('<tr><td colspan="6">Tidak ada kegiatan.</td></tr>');
        return;
      }
      $('#error-jeniskegiatan').hide().text('');
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
      });
      $('#list_program_out_activity tbody').html(tbody);

      if (showModal) {
        $('#ModalDaftarProgramActivity').modal('show');
      }
    },
    error: function () {
      toastr.error('Gagal mengambil data kegiatan.');
    }
  });
}

// Pas halaman siap, fetch data kegiatan tapi TIDAK buka modal
$(document).ready(function () {
  const programId = $('#program_id').val();
  const jenisId = $('#jeniskegiatan_id').val();

  if (programId && jenisId) {
    fetchKegiatan(programId, jenisId, false);  // false = modal tidak muncul otomatis
  }
});

// Saat select jenis kegiatan berubah (atau klik tombol khusus) baru tampilkan modal
$('#jeniskegiatan_id').on('select2:select', function () {
  const jenisId = $(this).val();
  const programId = $('#program_id').val();

  if (!programId) {
    toastr.warning('Silakan pilih Program terlebih dahulu.');
    $(this).val(null).trigger('change');
    return;
  }

  fetchKegiatan(programId, jenisId, true);  // true = modal tampil saat ini juga
});

    $('#kode_kegiatan').on('click', function (e) {
      const programId = $('#program_id').val();
      const jenisId = $('#jeniskegiatan_id').val();

      if (!programId || !jenisId) {
          e.preventDefault();
          e.stopPropagation();
          toastr.error('Silakan pilih Program dan Jenis Kegiatan terlebih dahulu.');
      }
    });

     function setLokasiWilayah(kegiatanId) {
    $.ajax({
      url: '{{ route("api.benchmark.lokasi") }}',
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
          url: '{{ route("api.benchmark.compiler") }}', // ganti ini dengan URL endpoint getCompilers kamu
          dataType: 'json',
          delay: 250,
          data: function(params) {
              return {
                  search: params.term, // search term dari input select2
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
  const kegiatanId = $('#kegiatan_id').val();
    if (kegiatanId) {
        setLokasiWilayah(kegiatanId);
    }

});
</script>