@extends('layouts.app')

@section('content_body')
<div class="container-fluid py-4">

    <h4 class="mb-3">📤 Export Rekap Timesheet</h4>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('timesheet.export.index') }}">
        <div class="row g-3">
            <div class="col-md-3">
                <label>Staff</label>
                <select name="user_id" class="form-control" required>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}"
                            {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Tahun</label>
                <select name="year" class="form-control">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}"
                            {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <label>Bulan</label>
                <select name="month" class="form-control">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}"
                            {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-5">
                <label>Program</label>
                <select name="program_ids[]" class="form-control select2" multiple>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}"
                            {{ collect(request('program_ids'))->contains($p->id) ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">
                    Kosongkan untuk menampilkan semua program
                </small>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit"
                    name="preview"
                    value="1"
                    class="btn btn-secondary">
                🔍 Preview Rekap
            </button>
        </div>
    </form>

    

    @if(!empty($previewData))
<@foreach($previewData as $pd)
<hr>

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-start mb-2">
    <div>
        <h5 class="mb-1">
            <i class="fas fa-user"></i> {{ $pd['user']->nama }}
        </h5>

        <div class="text-muted">
            {{ \Carbon\Carbon::create($pd['year'], $pd['month'])->translatedFormat('F Y') }}
        </div>

        {{-- STATUS (PINDAH KE KIRI) --}}
        <span class="badge bg-{{ 
            $pd['status']=='approved' ? 'success' : 
            ($pd['status']=='rejected' ? 'danger' : 'secondary') 
        }} mt-1">
            {{ ucfirst($pd['status']) }}
        </span>
    </div>
</div>

{{-- ================= TABLE ================= --}}
<div class="table-responsive mt-3">
<table class="table table-bordered text-center align-middle">

    <thead class="table-light">
        <tr>
            <th rowspan="2">Program</th>
            <th colspan="{{ $pd['daysInMonth'] }}">Calendar Day</th>
            <th rowspan="2">Total</th>
        </tr>
        <tr>
            @for($d = 1; $d <= $pd['daysInMonth']; $d++)
                <th>{{ $d }}</th>
            @endfor
        </tr>
    </thead>

    <tbody>
        {{-- PROGRAM ROW --}}
        @foreach($pd['matrix'] as $program => $days)
        <tr>
            <td class="text-start fw-semibold">{{ $program }}</td>

            @for($d = 1; $d <= $pd['daysInMonth']; $d++)
                <td>
                    {{-- PRIORITAS: MARKER --}}
                    @if(isset($pd['nonWorkingMarker'][$d]))
                        <span class="badge bg-secondary">
                            {{ $pd['nonWorkingMarker'][$d] }}
                        </span>
                    @elseif(isset($days[$d]))
                        {{ $days[$d] }}
                    @endif
                </td>
            @endfor

            <td class="fw-bold">{{ $days['total'] ?? 0 }}</td>
        </tr>
        @endforeach

        {{-- GRAND TOTAL --}}
        <tr class="fw-bold">
            <td>Grand Total</td>

            @for($d = 1; $d <= $pd['daysInMonth']; $d++)
                <td>{{ $pd['grandDaily'][$d] ?? 0 }}</td>
            @endfor

            <td>{{ $pd['grandTotal'] }}</td>
        </tr>
    </tbody>
</table>
</div>

<p class="mt-2">
    <strong>Equivalent Days (÷8):</strong> {{ $pd['equivalent'] }} hari
</p>

{{-- EXPORT --}}
@if($pd['status'] === 'approved')
<form method="POST" action="{{ route('timesheet.export.excel') }}">
    @csrf
    <input type="hidden" name="user_id" value="{{ $pd['user']->id }}">
    <input type="hidden" name="month" value="{{ $pd['month'] }}">
    <input type="hidden" name="year" value="{{ $pd['year'] }}">
    @foreach((array) request('program_ids') as $pid)
        <input type="hidden" name="program_ids[]" value="{{ $pid }}">
    @endforeach
    <button class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export Excel
    </button>
</form>
@else
<button class="btn btn-success" disabled>
    🔒 Export hanya untuk Approved
</button>
@endif

@endforeach
@endif

</div>
@endsection

@push('js')
@section('plugins.Select2', true)
<script>
    $('.select2').select2({ width: '100%' });
</script>
@endpush