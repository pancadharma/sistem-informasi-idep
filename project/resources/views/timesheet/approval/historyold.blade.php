@extends('layouts.app')

@section('content_body')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>📚 Approval History</h4>

        <a href="{{ route('approval.index') }}"
           class="btn btn-secondary btn-sm">
            ← Approval Pending
        </a>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="mb-3">
        <div class="d-flex gap-2">
            <select name="status" class="form-control w-auto">
                <option value="">Semua Status</option>
                <option value="approved" {{ $status=='approved'?'selected':'' }}>
                    Approved
                </option>
                <option value="rejected" {{ $status=='rejected'?'selected':'' }}>
                    Rejected
                </option>
            </select>

            <button class="btn btn-primary btn-sm">
                Filter
            </button>
        </div>
    </form>

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
                        <th>Disetujui / Ditolak</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($timesheets as $ts)
                        <tr>
                            <td>{{ $ts->user->nama }}</td>
                            <td>{{ $ts->user->jabatan->nama ?? '-' }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::create($ts->year, $ts->month)->translatedFormat('F Y') }}
                            </td>
                            <td class="text-center">
                                {{ number_format($ts->total_minutes / 60, 2) }} Jam
                            </td>
                            <td class="text-center">
                                @if($ts->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($ts->approved_at)->format('d M Y H:i') }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('approval.show', [$ts, 'from' => 'history']) }}"
                                class="btn btn-sm btn-info">
                                    🔍 Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="text-center text-muted py-4">
                                Belum ada riwayat approval
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection