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

{{-- SUMMARY HEADER --}}
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
            Status: {{ $timesheet->approvalLabel() }}
        </span>

        {{-- INFO APPROVAL --}}
        @if($timesheet->status === 'approved')
            <div class="mt-2 text-success">
                <i class="fas fa-check-circle"></i>
                Di-approve oleh:
                <strong>{{ $timesheet->approver?->nama }}</strong>
                pada
                {{ optional($timesheet->approved_at)->format('d M Y H:i') }}

                @if($timesheet->isAutoApproved())
                    <span class="badge badge-info ml-2">
                        Auto
                    </span>
                @endif
            </div>
        @endif

        {{-- INFO REJECT --}}
        @if($timesheet->status === 'rejected')
            <div class="mt-2 text-danger">
                <i class="fas fa-times-circle"></i>
                Ditolak oleh:
                <strong>{{ $timesheet->approver?->nama }}</strong>
                pada
                {{ optional($timesheet->approved_at)->format('d M Y H:i') }}

                <div class="alert alert-danger mt-2 p-2">
                    <strong>Catatan:</strong><br>
                    {{ $timesheet->approval_note }}
                </div>
            </div>
        @endif

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

    <th>Kantor</th>
    <th>Lapangan</th>
    <th>Rumah</th>
    <th>Other</th>

    <th>Total Jam</th>
    <th>Status Hari</th>
    <th width="120">Aksi</th>
</tr>
</thead>

<tbody>
@foreach($days as $day)

@php
$isWeekend = $day['date']->isWeekend();

$dayBadge = [
    'bekerja' => 'primary',
    'libur'   => 'secondary',
    'cuti'    => 'warning',
    'doc'     => 'info',
    'kosong'  => 'light',
    'sakit'   => 'danger',
];
@endphp

<tr class="{{ $isWeekend ? 'table-weekend' : '' }}">

<td>{{ $day['date']->format('d M Y') }}</td>

{{-- 🔥 PER LOKASI --}}
<td>{{ number_format($day['byLocation']['kantor'], 2) }}</td>
<td>{{ number_format($day['byLocation']['lapangan'], 2) }}</td>
<td>{{ number_format($day['byLocation']['rumah'], 2) }}</td>
<td>{{ number_format($day['byLocation']['other'], 2) }}</td>

<td>
<strong>{{ number_format($day['minutes']/60,2) }} Jam</strong>
</td>

<td class="text-center">
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
<span class="badge badge-secondary">Terkunci</span>
@endif
</td>

</tr>
@endforeach
</tbody>
</table>

{{-- 🔥 SUMMARY PER LOKASI --}}
<div class="p-3">

<h5>📊 Summary Per Lokasi</h5>

<table class="table table-bordered w-50">

<tr class="table-warning">
    <th>Lokasi</th>
    <th>Total Jam</th>
    <th>Total Hari (÷8)</th>
</tr>

@foreach(['kantor','lapangan','rumah','other'] as $loc)
<tr>
    <td>{{ ucfirst($loc) }}</td>

    <td>{{ number_format($summary[$loc],2) }} Jam</td>

    <td>
        <strong>
        {{ number_format($summary[$loc] / 8, 2) }} Hari
        </strong>
    </td>
</tr>
@endforeach

<tr class="table-primary">
<th>TOTAL</th>

<th>{{ number_format($totalMinutes/60,2) }} Jam</th>

<th>{{ number_format($totalMinutes/480,2) }} Hari</th>
</tr>

</table>
</div>

{{-- SUBMIT BULANAN --}}
@if($timesheet && $timesheet->isEditable())
    <form id="formSubmitTimesheet"
        action="{{ route('timesheet.submit', $timesheet) }}"
        method="POST"
        class="text-right p-3">
        @csrf

        <button type="button"
                id="btnSubmitTimesheet"
                class="btn btn-success">
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
.table-weekend {
background-color: #e9ecef !important;
}

.table-weekend td {
color: #495057;
}

.table-weekend:hover {
background-color: #dee2e6 !important;
}
</style>
@endpush

@push('js')
@include('timesheet.js.index')
@section('plugins.Select2', true)
@endpush