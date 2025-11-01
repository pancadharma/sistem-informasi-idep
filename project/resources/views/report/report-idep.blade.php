@extends('layouts.app')

@section('subtitle', __('Report IDEP'))
@section('content_header_title')
    {{-- @can('export_report') --}}
        <a class="btn-success btn" href="#" title="Export Report">
            <i class="fas fa-file-export"></i> Export Report
        </a>
    {{-- @endcan --}}
@endsection
@section('sub_breadcumb', __('Export Report IDEP'))

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Report</h3>
        </div>
        <div class="card-body table-responsive">
            <form method="POST" action="{{ route('report.generate') }}" id="report-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="type_laporan">Type Laporan</label>
                                <select class="form-control" id="type_laporan" name="type_laporan" required>
                                    <option value="" disabled {{ old('type_laporan') ? '' : 'selected' }}>-- Pilih --
                                    </option>
                                    <option value="kegiatan" {{ old('type_laporan')=='kegiatan' ? 'selected' : '' }}>
                                        Kegiatan</option>
                                    <option value="program" {{ old('type_laporan')=='program' ? 'selected' : '' }}>
                                        Program</option>
                                    <option value="meals" {{ old('type_laporan')=='meals' ? 'selected' : '' }}>Meals
                                    </option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="program_id">Program</label>
                                <select class="form-control select2" id="program_id" name="program_id"
                                    style="width: 100%"></select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="jeniskegiatan_id">Jenis Kegiatan</label>
                                <select class="form-control select2" id="jeniskegiatan_id" name="jeniskegiatan_id"
                                    style="width: 100%"></select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="kegiatan_id">Kegiatan</label>
                                <select class="form-control" id="kegiatan_id" name="kegiatan_id" style="width: 100%">
                                    <option value="all">Semua</option>
                                </select>
                                <small class="form-text text-muted">Pilih "Semua" untuk semua kegiatan atau pilih salah
                                    satu dari hasil filter.</small>
                            </div>
                            
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="draft" {{ old('status')=='draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="ongoing" {{ old('status')=='ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status')=='completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="output_format">Format Output</label>
                                <select id="output_format" name="output_format" class="form-control">
                                    <option value="html" {{ old('output_format')=='html' ? 'selected' : '' }}>HTML Preview</option>
                                    <option value="xlsx" {{ old('output_format')=='xlsx' ? 'selected' : '' }}>Excel (.xlsx)</option>
                                    <option value="csv"  {{ old('output_format')=='csv'  ? 'selected' : '' }}>CSV</option>
                                    <option value="pdf"  {{ old('output_format')=='pdf'  ? 'selected' : '' }}>PDF</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="group_by">Group By (opsional)</label>
                                <select id="group_by" name="group_by" class="form-control">
                                    <option value="">Tanpa Pengelompokan</option>
                                    <option value="program" {{ old('group_by')=='program' ? 'selected' : '' }}>Per Program</option>
                                    <option value="jenis"   {{ old('group_by')=='jenis'   ? 'selected' : '' }}>Per Jenis Kegiatan</option>
                                    <option value="provinsi" {{ old('group_by')=='provinsi' ? 'selected' : '' }}>Per Provinsi</option>
                                    <option value="bulan"   {{ old('group_by')=='bulan'   ? 'selected' : '' }}>Per Bulan</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Generate</button>
            </form>
        </div>
    </div>
    <div id="report-preview-card" class="card mt-3 d-none">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Preview Laporan</h3>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-close-preview">Tutup</button>
        </div>
        <div class="card-body table-responsive" id="report-preview-body"></div>
    </div>
    @if(!empty($preview))
    <div class="card mt-3">
        <div class="card-header"><h3 class="card-title">Preview Laporan</h3></div>
        <div class="card-body table-responsive">
            @if($reportType === 'kegiatan')
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Jenis Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Lokasi Kegiatan</th>
                        <th>Status Kegiatan</th>
                        <th>Fase Kegiatan</th>
                        <th>Total Penerima</th>
                        <th>Perempuan</th>
                        <th>Laki-laki</th>
                        <th>Dewasa</th>
                        <th>Remaja</th>
                        <th>Anak</th>
                        <th>Disabilitas</th>
                        <th>Kelompok Marjinal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        <tr>
                            <td>{{ $r['program'] }}</td>
                            <td>{{ $r['jenis'] }}</td>
                            <td>{{ $r['tanggal_mulai'] }}</td>
                            <td>{{ $r['tanggal_selesai'] }}</td>
                            <td>{{ $r['lokasi'] }}</td>
                            <td>{{ $r['status'] }}</td>
                            <td>{{ $r['fase'] }}</td>
                            <td class="text-right">{{ $r['total'] }}</td>
                            <td class="text-right">{{ $r['perempuan'] }}</td>
                            <td class="text-right">{{ $r['laki_laki'] }}</td>
                            <td class="text-right">{{ $r['dewasa'] }}</td>
                            <td class="text-right">{{ $r['remaja'] }}</td>
                            <td class="text-right">{{ $r['anak'] }}</td>
                            <td class="text-right">{{ $r['disabilitas'] }}</td>
                            <td class="text-right">{{ $r['marjinal'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
            @elseif($reportType === 'program')
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Total Kegiatan</th>
                        <th>Total Beneficiaries</th>
                        <th>Budget</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        <tr>
                            <td>{{ $r['kode'] }}</td>
                            <td>{{ $r['nama'] }}</td>
                            <td>{{ $r['tanggal_mulai'] }}</td>
                            <td>{{ $r['tanggal_selesai'] }}</td>
                            <td class="text-right">{{ $r['total_kegiatan'] }}</td>
                            <td class="text-right">{{ $r['total_beneficiaries'] }}</td>
                            <td class="text-right">{{ $r['budget'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
            @elseif($reportType === 'meals')
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Komponen Model</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Provinsi</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                        <th>Long</th>
                        <th>Lat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        <tr>
                            <td>{{ $r['program'] }}</td>
                            <td>{{ $r['komponen'] }}</td>
                            <td class="text-right">{{ $r['jumlah'] }}</td>
                            <td>{{ $r['satuan'] }}</td>
                            <td>{{ $r['provinsi'] }}</td>
                            <td>{{ $r['kabupaten'] }}</td>
                            <td>{{ $r['kecamatan'] }}</td>
                            <td>{{ $r['desa'] }}</td>
                            <td>{{ $r['long'] }}</td>
                            <td>{{ $r['lat'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
            @endif
        </div>
    </div>
    @endif
@stop

@push('css')

@endpush

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

<script>
    $(function() {
        const programsUrl = "{{ route('report.api.programs') }}";
        const jenisUrl = "{{ route('report.api.jenis_kegiatan') }}";
        const kegiatanUrl = "{{ route('api.benchmark.kegiatan') }}";

        function initProgramSelect() {
            $('#program_id').select2({
                placeholder: 'Pilih Program',
                allowClear: true,
                ajax: {
                    url: programsUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: data.pagination
                        };
                    },
                    cache: true
                }
            });

            // Restore old value if any
            const oldProgram = "{{ old('program_id') }}";
            const oldProgramText = "{{ old('program_nama') }}";
            if (oldProgram) {
                const option = new Option(oldProgramText || ('#' + oldProgram), oldProgram, true, true);
                $('#program_id').append(option).trigger('change');
            }
        }

        function initJenisSelect() {
            $('#jeniskegiatan_id').select2({
                placeholder: 'Pilih Jenis Kegiatan',
                allowClear: true,
                ajax: {
                    url: jenisUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: data.pagination
                        };
                    },
                    cache: true
                }
            });

            const oldJenis = "{{ old('jeniskegiatan_id') }}";
            const oldJenisText = "{{ old('jeniskegiatan_nama') }}";
            if (oldJenis) {
                const option = new Option(oldJenisText || ('#' + oldJenis), oldJenis, true, true);
                $('#jeniskegiatan_id').append(option).trigger('change');
            }
        }

        function initKegiatanSelect() {
            const programId = $('#program_id').val();
            const jenisId = $('#jeniskegiatan_id').val();

            $('#kegiatan_id').empty().append('<option value="all" selected>Semua</option>');

            $('#kegiatan_id').select2({
                placeholder: 'Pilih Kegiatan (opsional)',
                allowClear: true,
                ajax: {
                    transport: function(params, success, failure) {
                        const q = {
                            program_id: programId,
                            jeniskegiatan_id: jenisId
                        };
                        return $.ajax({
                            url: kegiatanUrl,
                            data: q,
                            dataType: 'json',
                            success: function(data) {
                                const results = (data || []).map(function(item) {
                                    const text = (item.kode ? (item.kode + ' - ') : '') + (item.nama || '');
                                    return {
                                        id: item.id,
                                        text: text
                                    };
                                });
                                // Prepend the "Semua" option
                                results.unshift({
                                    id: 'all',
                                    text: 'Semua'
                                });
                                success({
                                    results: results,
                                    pagination: {
                                        more: false
                                    }
                                });
                            },
                            error: failure
                        });
                    }
                }
            });

            const oldKegiatan = "{{ old('kegiatan_id') }}";
            if (oldKegiatan && oldKegiatan !== 'all') {
                // Let AJAX load, or you could create a selected option stub
            } else if (oldKegiatan === 'all') {
                $('#kegiatan_id').val('all').trigger('change');
            }
        }

        initProgramSelect();
        initJenisSelect();
        initKegiatanSelect();

        $('#program_id, #jeniskegiatan_id').on('change', function() {
            // Re-initialize kegiatan when dependencies change
            initKegiatanSelect();
        });

        // AJAX submit for HTML preview
        $('#report-form').on('submit', function(e) {
            const format = $('#output_format').val();
            if (format === 'html' || !format) {
                e.preventDefault();
                const $btn = $(this).find('button[type="submit"]');
                const oldText = $btn.html();
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memuat...');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: { 'X-CSRF-TOKEN': window._token },
                    success: function(resp) {
                        $('#report-preview-body').html(resp.html || '');
                        $('#report-preview-card').removeClass('d-none');
                        $('html, body').animate({ scrollTop: $('#report-preview-card').offset().top - 80 }, 300);
                    },
                    error: function(xhr) {
                        let msg = 'Gagal memuat preview laporan';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        Toast.fire({ icon: 'error', title: msg });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(oldText);
                    }
                });
            }
        });

        // Close preview
        $(document).on('click', '#btn-close-preview', function() {
            $('#report-preview-body').empty();
            $('#report-preview-card').addClass('d-none');
        });
    });
</script>
@endpush
