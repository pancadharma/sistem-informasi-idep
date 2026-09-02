@extends('layouts.app')

@section('content_body')
@php
    // Helper untuk mengubah menit menjadi format X jam Y mnt
    function formatJamMenit($menit) {
        if ($menit == 0) return '0';
        $h = floor($menit / 60);
        $m = round($menit % 60);
        $res = [];
        if ($h > 0) $res[] = $h . ' Jam';
        if ($m > 0) $res[] = $m . ' Mnt';
        return implode(' ', $res);
    }
@endphp
<div class="container-fluid py-4">

    <h4 class="mb-4">📚 Riwayat Approval Timesheet</h4>

    {{-- FILTER --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="month" class="form-control">
                <option value="">-- Semua Bulan --</option>
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ $month==$m?'selected':'' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-3">
            <select name="year" class="form-control">
                <option value="">-- Semua Tahun --</option>
                @for($y=now()->year;$y>=now()->year-3;$y--)
                    <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                Filter
            </button>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Periode</th>
                        <th>Total Jam</th>

                        <th>Status</th>
                        <th>Disetujui Oleh</th>
                        <th>Catatan</th>

                        <th width="160">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($timesheets as $t)
                        <tr>

                            <td>{{ $t->user->nama }}</td>

                            <td>{{ $t->user->jabatan->nama ?? '-' }}</td>

                            <td class="text-center">
                                {{ \Carbon\Carbon::create($t->year, $t->month)->translatedFormat('F Y') }}
                            </td>

                            <td class="text-center">
                                {{ formatJamMenit($t->total_minutes) }}
                            </td>

                            {{-- STATUS --}}
                            <td class="text-center">

                                <span class="badge badge-{{ $t->status == 'approved' ? 'success' : 'danger' }}">
                                    {{ $t->approvalLabel() }}
                                </span>

                                @if($t->isAutoApproved())
                                    <span class="badge badge-info ml-1">
                                        Auto
                                    </span>
                                @endif

                            </td>

                            {{-- APPROVER --}}
                            <td>
                                {{ $t->approver?->nama ?? '-' }} <br>

                                <small class="text-muted">
                                    {{ optional($t->approved_at)->format('d M Y H:i') }}
                                </small>
                            </td>

                            {{-- CATATAN --}}
                            <td>
                                @if($t->status == 'rejected')
                                    <div class="alert alert-danger p-1 m-0">
                                        {{ $t->approval_note }}
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">

                                <a href="{{ route('approval.show', [$t, 'from'=>'history']) }}"
                                   class="btn btn-sm btn-info">
                                    🔍 Detail
                                </a>

                                @can('timesheet_ubah_status')
                                    <button class="btn btn-sm btn-warning btn-edit-status"
                                            data-id="{{ $t->id }}"
                                            data-status="{{ $t->status }}">
                                        ✏️ Ubah Status
                                    </button>
                                @endcan

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada data history
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    </div> {{-- /.card --}}

    <div class="d-flex justify-content-center mt-3">
        {{ $timesheets->links('pagination::bootstrap-4') }}
    </div>

</div> {{-- /.container-fluid --}}

{{-- MODAL EDIT STATUS tetap berada di dalam content_body --}}
@can('timesheet_ubah_status')
<div class="modal fade" id="modalEditStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditStatus">
            @csrf

            <input type="hidden" name="timesheet_id" id="edit-timesheet-id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Status Timesheet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Status Baru</label>
                        <select name="status" class="form-control" required>
                            <option value="draft">Draft (bisa diedit staff)</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Alasan</label>
                        <textarea name="note"
                          class="form-control"
                          placeholder="Masukkan catatan atau alasan perubahan status (minimal 5 karakter)"
                          required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="btnSimpanStatus" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

@endsection

@push('js')
<script>
$(function(){

    // OPEN MODAL
    $(document).on('click', '.btn-edit-status', function(){

        $('#edit-timesheet-id').val($(this).data('id'));

        $('#modalEditStatus').modal('show');
    });

    
// SUBMIT EDIT STATUS (Di Halaman Riwayat/History)
    $('#formEditStatus').on('submit', function(e){
        e.preventDefault();

        const note = $.trim($(this).find('[name="note"]').val());

        if (note.length < 5) {
            Swal.fire({
                icon: 'warning',
                title: 'Catatan terlalu pendek',
                text: 'Catatan minimal 5 karakter.',
                confirmButtonText: 'OK',
                allowOutsideClick: false
            });
            return;
        }

        $('#modalEditStatus').modal('hide');

        // 1. 🔥 MUNCULKAN SWAL LOADING PENUH
        Swal.fire({
            title: 'Memproses Perubahan...',
            text: 'Mohon tunggu, sedang memperbarui status dan mengirim email.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // 2. Eksekusi AJAX
        $.post("{{ route('timesheet.admin.changeStatus') }}", $(this).serialize())
        .done(function (res) {
            if (res.success === true && res.email_sent === true) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message || 'Status berhasil diubah dan email terkirim.',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(function () {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: res.message || 'Email gagal dikirim. Status tidak diubah. Coba lagi nanti.',
                    confirmButtonText: 'Tutup',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
                // Tidak reload ketika email gagal.
            }
        })
        .fail(function(xhr){
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: xhr.responseJSON?.message
                    || 'Email gagal dikirim. Status tidak diubah.',
                confirmButtonText: 'Tutup',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });
    });

});
</script>
@endpush