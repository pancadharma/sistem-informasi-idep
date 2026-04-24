@extends('layouts.app')

@section('content_body')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>
            📄 Review Timesheet
        </h4>

        @php
            $from = request('from', 'pending');
        @endphp

        <a href="{{ $from === 'history'
                    ? route('approval.history')
                    : route('approval.index') }}"
        class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    {{-- INFO USER --}}
    <div class="card mb-3">
        <div class="card-body">
            <strong>Nama:</strong> {{ $timesheet->user->nama }} <br>
            <strong>Jabatan:</strong> {{ $timesheet->user->jabatan->nama ?? '-' }} <br>
            <strong>Divisi:</strong> {{ $timesheet->user->jabatan->divisi->nama ?? '-' }} <br>
            <strong>Periode:</strong>
            {{ \Carbon\Carbon::create($timesheet->year, $timesheet->month)->translatedFormat('F Y') }}
        </div>
    </div>

    {{-- TABLE DETAIL --}}
    <div class="card mb-3">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Tanggal</th>
                        <th>Status Hari</th>
                        <th>Jam</th>
                        <th>Program</th>
                        <th>Donor</th>
                        <th>Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timesheet->entries as $e)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($e->work_date)->format('d M Y') }}</td>
                            <td class="text-center">
                                @php
                                    $dayBadge = [
                                            'bekerja' => 'primary',
                                            'libur'   => 'secondary',
                                            'cuti'    => 'warning',
                                            'doc'     => 'info',
                                            'kosong'  => 'light'
                                    ];
                                @endphp

                                <span class="badge badge-{{ $dayBadge[$e->day_status] ?? 'light' }}">
                                    {{ ucfirst($e->day_status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ number_format($e->minutes / 60, 2) }}
                            </td>
                            <td>
                                {{ $e->program?->nama ?? ucfirst($e->program_static) ?? '-' }}
                            </td>
                            <td>
                                {{ $e->donor?->nama ?? ucfirst($e->donor_static) ?? '-' }}
                            </td>
                            <td>{{ $e->activity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- APPROVAL ACTION --}}
    <div class="card">
        <div class="card-body">

@if($timesheet->status === 'submitted')
    <form method="POST"
          action="{{ route('approval.approve', $timesheet) }}"
          class="d-inline">
        @csrf
        <button class="btn btn-success">
            ✅ Approve
        </button>
    </form>

    <button class="btn btn-danger"
            data-toggle="modal"
            data-target="#rejectModal">
        ❌ Reject
    </button>
@else
    <span class="badge badge-secondary">
        Read Only (History)
    </span>
@endif

        </div>
    </div>

</div>

{{-- MODAL REJECT --}}
<div class="modal fade" id="rejectModal">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('approval.reject', $timesheet) }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Timesheet</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <textarea name="note"
                              class="form-control"
                              rows="3"
                              required
                              placeholder="Alasan penolakan"></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary"
                            data-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn btn-danger">
                        Tolak
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection