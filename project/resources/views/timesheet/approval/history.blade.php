@extends('layouts.app')

@section('content_body')
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
                                {{ number_format($t->total_minutes / 60, 2) }} Jam
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

                                {{-- @can('can_access') --}}
                                    <button class="btn btn-sm btn-warning btn-edit-status"
                                            data-id="{{ $t->id }}"
                                            data-status="{{ $t->status }}">
                                        ✏️ Ubah Status
                                    </button>
                                {{-- @endcan --}}

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

</div>
@endsection


{{-- =======================
    MODAL EDIT STATUS
======================= --}}
{{-- @can('can_access') --}}
<div class="modal fade" id="modalEditStatus">
  <div class="modal-dialog">
    <form id="formEditStatus">
        @csrf

        <input type="hidden" name="timesheet_id" id="edit-timesheet-id">

        <div class="modal-content">

            <div class="modal-header">
                <h5>Ubah Status Timesheet</h5>
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
                              required></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>

        </div>
    </form>
  </div>
</div>
{{-- @endcan --}}


{{-- =======================
    JAVASCRIPT
======================= --}}
@push('js')
<script>
$(function(){

    // OPEN MODAL
    $(document).on('click', '.btn-edit-status', function(){

        $('#edit-timesheet-id').val($(this).data('id'));

        $('#modalEditStatus').modal('show');
    });

    // SUBMIT EDIT STATUS
    $('#formEditStatus').on('submit', function(e){
        e.preventDefault();

        $.post("{{ route('timesheet.admin.changeStatus') }}",
            $(this).serialize()
        )
        .done(function(res){

            Swal.fire(
                'Sukses',
                res.message,
                'success'
            ).then(() => location.reload());

        })
        .fail(function(xhr){

            Swal.fire(
                'Gagal',
                xhr.responseJSON?.message || 'Error',
                'error'
            );

        });

    });

});
</script>
@endpush