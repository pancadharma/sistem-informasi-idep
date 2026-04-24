@extends('layouts.app')

@section('content_body')
<div class="container-fluid py-4">

    <h4 class="mb-4">
        🧑‍💼 Approval Timesheet
    </h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

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
                                <span class="badge badge-warning">
                                    Submitted
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('approval.show', [$ts, 'from' => 'pending']) }}"
                                class="btn btn-sm btn-primary">
                                    🔍 Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada timesheet menunggu approval
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection