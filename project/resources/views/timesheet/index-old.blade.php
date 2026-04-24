@extends('layouts.app')

@section('content_body')

<div class="container-fluid py-4">
{{-- FEEDBACK UX --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        {{ $errors->first() }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📅 Timesheet Bulanan</h4>

        <form method="GET" class="d-flex gap-2">
            <select name="month" class="form-control">
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ $month==$m?'selected':'' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="year" class="form-control">
                @for($y=now()->year;$y>=now()->year-3;$y--)
                    <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button class="btn btn-primary">Terapkan</button>
        </form>
    </div>

    {{-- SUMMARY --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="p-3 bg-primary text-white rounded">
                <small>Total Jam Bulan Ini</small>
                <h3>{{ number_format($totalMinutes/60,2) }} Jam</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-3 bg-primary text-white rounded">
                <small>Total Hari (8 Jam)</small>
                <h3>{{ number_format($totalMinutes/480,2) }} Hari</h3>
            </div>
        </div>
    </div>

    {{-- STATUS BULANAN --}}
    <div class="mb-3">
        @php
            $badge = [
                'draft'     => 'secondary',
                'submitted' => 'warning',
                'approved'  => 'success',
                'rejected'  => 'danger'
            ];
        @endphp

        @if($timesheet)
            <span class="badge badge-{{ $badge[$timesheet->status] ?? 'secondary' }}">
                Status: {{ ucfirst($timesheet->status) }}
            </span>
        @else
            <span class="badge badge-secondary">
                Belum Ada Timesheet
            </span>
        @endif
    </div>

    {{-- TABLE --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Tanggal</th>
                        <th>Total Jam</th>
                        <th>Status Hari</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($days as $day)
                        @php
                            $isWeekend = $day['date']->isWeekend(); // Sabtu / Minggu
                        @endphp

                        <tr class="{{ $isWeekend ? 'table-weekend' : '' }} {{ $isWeekend && $day['status']=='kosong' ? 'table-weekend-empty' : '' }}">
                            <td>
                                {{ $day['date']->format('d M Y') }}

                                @if($isWeekend)
                                    {{-- <span class="badge badge-info ml-2">Weekend</span> --}}
                                @endif
                            </td>

                            <td>{{ number_format($day['minutes']/60,2) }} Jam</td>

                            <td class="text-center">
                                @php
                                    $dayBadge = [
                                        'bekerja' => 'primary',
                                        'libur'   => 'secondary',
                                        'cuti'    => 'warning',
                                        'doc'     => 'info',
                                        'sakit'   => 'danger',
                                        'kosong'  => 'light'
                                    ];
                                @endphp

                                <span class="badge badge-{{ $dayBadge[$day['status']] ?? 'light' }}">
                                    {{ ucfirst($day['status']) }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if(!$timesheet || $timesheet->isEditable())
                                    <button class="btn btn-sm btn-primary btn-input-day"
                                            data-date="{{ $day['date']->toDateString() }}">
                                        ✏️ Input
                                    </button>
                                @else
                                    <span class="badge badge-secondary">
                                        Terkunci
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- SUBMIT BULANAN --}}
            @if($timesheet && $timesheet->isEditable())
                <form action="{{ route('timesheet.submit', $timesheet) }}"
                    method="POST"
                    class="text-right p-3">
                    @csrf
                    <button class="btn btn-success"
                            onclick="return confirm('Submit timesheet bulan ini? Setelah submit tidak bisa diubah.')">
                        📤 Submit Timesheet Bulanan
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>

@include('timesheet.partials.modal-day')

@endsection

@push('css')
<style>
    /* WEEKEND ROW */
    .table-weekend {
        background-color: #e9ecef !important; /* abu gelap halus */
    }

    .table-weekend td {
        color: #495057; /* text tetap jelas */
    }

    .table-weekend:hover {
        background-color: #dee2e6 !important;
    }
    /* WEEKEND KOSONG ROW */
    .table-weekend-empty {
        background-color: #dee2e6 !important;
    }

    /* BADGE WEEKEND */
    .table-weekend .badge-info {
        background-color: #6c757d; /* abu gelap */
        color: #fff;
    }
</style>
@endpush

@push('js')
    @include('timesheet.js.index')
    @section('plugins.Select2', true)
@endpush