@extends('layouts.app')

@section('content_body')
@php
    // Helper untuk mengubah menit menjadi format X jam Y mnt
    function formatJamMenit($menit) {
        if ($menit <= 0) return '0';
        $h = floor($menit / 60);
        $m = $menit % 60;
        $res = [];
        if ($h > 0) $res[] = $h . ' Jam';
        if ($m > 0) $res[] = $m . ' Mnt';
        return empty($res) ? '0' : implode(' ', $res);
    }

    // Helper untuk mengubah menit menjadi format X hari Y jam Z mnt
    function formatHariJam($menit) {
        if ($menit <= 0) return '0';
        $d = floor($menit / 480);
        $sisa = $menit % 480;
        $h = floor($sisa / 60);
        $m = $sisa % 60;
        $res = [];
        if ($d > 0) $res[] = $d . ' Hari';
        if ($h > 0) $res[] = $h . ' Jam';
        if ($m > 0) $res[] = $m . ' Mnt';
        return empty($res) ? '0' : implode(' ', $res);
    }
@endphp
<div class="container-fluid py-4">

    <h4 class="mb-3">📤 Export Rekap Timesheet</h4>

    {{-- ================= FILTER FORM ================= --}}
    <form method="GET" action="{{ route('timesheet.export.index') }}">
        <div class="row g-3">

            {{-- STAFF --}}
            <div class="col-md-4">
                <label>Staff</label>
                <select name="user_ids[]"
                        class="form-control select2"
                        multiple
                        data-placeholder="Semua staff (kosongkan jika ingin semua)">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}"
                            {{ collect(request('user_ids'))->contains($u->id) ? 'selected' : '' }}>
                            {{ $u->nama }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Biarkan kosong untuk menampilkan semua staff</small>
            </div>

            {{-- TAHUN --}}
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

            {{-- BULAN --}}
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

            {{-- PROGRAM --}}
            <div class="col-md-4">
                <label>Program</label>
                <select name="program_ids[]"
                        class="form-control select2"
                        multiple
                        data-placeholder="Semua program">

                    {{-- ===== STATIC PROGRAM ===== --}}
                    <optgroup label="Program Umum">
                        @php
                            $staticPrograms = [
                                'benih_alami'    => 'Benih Alami',
                                'sapi_bergulir'=> 'Sapi Bergulir',
                                'train_with_idep'=> 'Train With IDEP',
                                'visit_idep'    => 'Visit IDEP',
                                'consult_idep'  => 'Consult IDEP',
                                'bwp_project'   => 'BWP Project',
                                'kios_idep'     => 'Kios IDEP',
                                'capacity_building'=> 'Capacity Building',
                                'garden_day'    => 'Garden Day',
                            ];
                        @endphp

                        @foreach($staticPrograms as $key => $label)
                            <option value="{{ $key }}"
                                {{ collect(request('program_ids'))->contains($key) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </optgroup>

                    {{-- ===== PROGRAM DATABASE ===== --}}
                    <optgroup label="Program Database">
                        @foreach($programs as $p)
                            <option value="{{ $p->id }}"
                                {{ collect(request('program_ids'))->contains($p->id) ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </optgroup>

                </select>
            </div>

        </div>

        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-secondary" name="preview" value="1">
                🔍 Preview
            </button>
        </div>
    </form>

    {{-- ================= PREVIEW ================= --}}
    @if(!empty($previewData))
@foreach($previewData as $pd)
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

    <thead class="table-light" style="white-space: nowrap;">
        <tr>
            <th rowspan="2" class="text-start" style="width: 250px;">Program</th>
            <th colspan="{{ $pd['daysInMonth'] }}">Calendar Day</th>
            <th rowspan="2">Total Hari</th>
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
                <td style="white-space: nowrap;">
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

            <td class="fw-bold" style="white-space: nowrap;">{{ $days['total'] ?? 0 }}</td>
        </tr>
        @endforeach

        {{-- GRAND TOTAL --}}
        <tr class="fw-bold">
            <td class="text-start" style="white-space: nowrap;">Total Jam</td>

            @for($d = 1; $d <= $pd['daysInMonth']; $d++)
                <td style="white-space: nowrap;">{{ $pd['grandDaily'][$d] ?? 0 }}</td>
            @endfor

            <td style="white-space: nowrap;">{{ $pd['grandTotal'] }}</td>
        </tr>
    </tbody>
</table>
</div>

<p class="mt-2">
    <strong>Equivalent Days (8 Jam/Hari):</strong> {{ $pd['equivalent'] }}
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
$('.select2').select2({
    width: '100%',
    allowClear: true
});
</script>
@endpush