@extends('layouts.app')

@section('content_body')
<div class="container-fluid py-4" id="printArea">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>📄 Review Timesheet</h4>

        <div>
            {{-- <button onclick="printTimesheet()" class="btn btn-primary btn-sm">
                🖨 Print
            </button> --}}

            @php $from = request('from', 'pending'); @endphp

            <a href="{{ $from === 'history'
                        ? route('approval.history')
                        : route('approval.index') }}"
               class="btn btn-secondary btn-sm">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- ================= INFO USER ================= --}}
    <div class="card mb-3">
        <div class="card-body">
            <strong>Nama:</strong> {{ $timesheet->user->nama }} <br>
            <strong>Jabatan:</strong> {{ $timesheet->user->jabatan->nama ?? '-' }} <br>
            <strong>Divisi:</strong> {{ $timesheet->user->jabatan->divisi->nama ?? '-' }} <br>
            <strong>Periode:</strong>
            {{ \Carbon\Carbon::create($timesheet->year, $timesheet->month)->translatedFormat('F Y') }}
        </div>
    </div>

    {{-- ================= LOGIC SUMMARY ================= --}}
    @php
        $totalJam = $timesheet->entries->sum('minutes') / 60;

        $summary = [
            'kantor'   => 0,
            'lapangan' => 0,
            'rumah'    => 0,
            'other'    => 0,
        ];

        foreach($timesheet->entries as $e) {
            $loc = $e->work_location ?? 'other';

            if(!isset($summary[$loc])) {
                $loc = 'other';
            }

            $summary[$loc] += $e->minutes / 60;
        }

        $totalHariKerja = $timesheet->entries
            ->where('day_status','bekerja')
            ->groupBy('work_date')
            ->count();

        function pct($v, $t) {
            return $t > 0 ? round(($v/$t)*100,2) : 0;
        }
    @endphp

    {{-- ================= TABLE DETAIL ================= --}}
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
                    @php $group = $timesheet->entries->groupBy('work_date'); @endphp

                    @foreach($group as $date => $rows)

                        @foreach($rows as $i => $e)

                            @php
                                $rowClass = match($e->day_status) {
                                    'libur' => 'table-secondary',
                                    'cuti'  => 'table-warning',
                                    'doc'   => 'table-info',
                                    'sakit' => 'table-danger',
                                    default => ''
                                };
                            @endphp

                            <tr class="{{ $rowClass }}">

                                @if($i == 0)
                                    <td rowspan="{{ count($rows) }}" class="text-center align-middle">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d M Y') }}
                                    </td>

                                    <td rowspan="{{ count($rows) }}" class="text-center align-middle">
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

                                        <span class="badge badge-{{ $dayBadge[$e->day_status] ?? 'light' }}">
                                            {{ ucfirst($e->day_status) }}
                                        </span>
                                    </td>
                                @endif

                                <td class="text-center">
                                    {{ number_format($e->minutes / 60, 2) }}
                                </td>

                                <td>
                                    {{ $e->program?->nama ?? ucfirst($e->program_static) ?? '-' }}
                                </td>

                                <td>
                                    {{ $e->donor?->nama ?? ucfirst($e->donor_static) ?? '-' }}
                                </td>

                                <td>
                                    {{ $e->activity }}
                                </td>

                            </tr>

                        @endforeach
                    @endforeach
                </tbody>

                {{-- ================= TOTAL ================= --}}
                <tfoot class="table-primary">
                    <tr>
                        <th colspan="2">TOTAL</th>

                        <th>
                            {{ number_format($totalJam,2) }} Jam
                        </th>

                        <th colspan="3">
                            Total Hari Kerja:
                            <strong>{{ $totalHariKerja }} Hari</strong>
                        </th>
                    </tr>
                </tfoot>

            </table>
        </div>
    </div>

    {{-- ================= SUMMARY + PIE CHART ================= --}}
    <div class="row">

        {{-- SUMMARY TABLE --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">

                    <h5 class="mb-3">📊 Summary Lokasi</h5>

                    <table class="table table-sm">
                        <tr>
                            <th>Lokasi</th>
                            <th class="text-center">Jam</th>
                            <th class="text-center">%</th>
                        </tr>

                        @foreach($summary as $loc => $jam)
                        <tr>
                            <td class="text-capitalize">{{ $loc }}</td>

                            <td class="text-center">
                                {{ number_format($jam,2) }}
                            </td>

                            <td class="text-center">
                                {{ pct($jam,$totalJam) }} %
                            </td>
                        </tr>
                        @endforeach

                    </table>

                    <div class="mt-2">
                        <strong>Total Hari Kerja:</strong>
                        {{ $totalHariKerja }} Hari
                    </div>

                </div>
            </div>
        </div>

        {{-- PIE CHART --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">

                    <h5 class="mb-2">📈 Persentase Lokasi Kerja</h5>

                    <div style="height: 420px; width: 100%; position: relative;">
                        <canvas id="pieLokasi"></canvas>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- ================= ACTION ================= --}}
    {{-- <div class="card mt-3">
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

</div> --}}

{{-- ================= MODAL REJECT ================= --}}
{{-- 
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('approval.reject', $timesheet) }}">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tolak Timesheet</h5>

                    <button type="button"
                            class="close"
                            data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Alasan Penolakan</label>

                        <textarea name="note"
                                  class="form-control"
                                  rows="4"
                                  required
                                  placeholder="Tuliskan alasan penolakan minimal 5 karakter"></textarea>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-danger">
                        ❌ Tolak Timesheet
                    </button>

                </div>

            </div>
        </form>
    </div>
</div> --}}

{{-- ================= ACTION ================= --}}
    <div class="card mt-3">
        <div class="card-body">

            @if($timesheet->status === 'submitted')

                {{-- FORM APPROVE --}}
                <form id="formApprove" class="d-inline"
                      action="{{ route('approval.approve', $timesheet) }}">
                    @csrf
                    <button type="submit" id="btnApprove" class="btn btn-success">
                        ✅ Approve
                    </button>
                </form>

                {{-- TOMBOL MUNCULKAN MODAL REJECT --}}
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

{{-- ================= MODAL REJECT ================= --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        {{-- FORM REJECT --}}
        <form id="formReject" action="{{ route('approval.reject', $timesheet) }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Timesheet</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan</label>
                        <textarea name="note" class="form-control" rows="4" required
                                  placeholder="Tuliskan alasan penolakan minimal 5 karakter"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    
                    {{-- TOMBOL SUBMIT REJECT --}}
                    <button type="submit" id="btnReject" class="btn btn-danger">
                        ❌ Tolak Timesheet
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
// ==========================================
// HANDLE APPROVE & REJECT VIA AJAX + SWAL LOADING
// ==========================================
$(function() {

    // 1. PROSES APPROVE
    $('#formApprove').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Approve Timesheet?',
            text: 'Timesheet akan disetujui dan karyawan akan menerima email.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Approve',
            cancelButtonText: 'Batal',
            reverseButtons: true // Opsional: Bikin tombol 'Ya' di kanan
        }).then((result) => {
            if (result.isConfirmed) {
                
                // 🔥 MUNCULKAN SWAL LOADING
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu, sedang memproses data dan mengirim email.',
                    allowOutsideClick: false, // Kunci layar agar tidak bisa diklik di luar
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        showEmailStatusSwal(res);
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan sistem', 'error');
                    }
                });
            }
        });
    });

    // 2. PROSES REJECT (Dari Modal)
    $('#formReject').on('submit', function(e) {
        e.preventDefault();
        
        // Tutup modal form reject-nya dulu agar tidak menumpuk dengan Swal
        $('#rejectModal').modal('hide');
        
        // 🔥 MUNCULKAN SWAL LOADING
        Swal.fire({
            title: 'Menolak Timesheet...',
            text: 'Mohon tunggu, sedang memproses dan mengirim email penolakan.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                showEmailStatusSwal(res);
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan sistem', 'error');
            }
        });
    });

    // 3. FUNGSI HELPER UNTUK RESULT SWAL
    function showEmailStatusSwal(res) {
        // Swal.fire baru ini akan otomatis menimpa/menutup Swal Loading sebelumnya!
        if (res.email_sent) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "{{ route('approval.index') }}";
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Tersimpan (Email Gagal)',
                text: res.message,
                footer: '<small class="text-danger">Log error email telah dicatat sistem.</small>'
            }).then(() => {
                window.location.href = "{{ route('approval.index') }}";
            });
        }
    }

});

    const ctx = document.getElementById('pieLokasi');

    const total = {{ $totalJam }};

    const dataJam = [
        {{ $summary['kantor'] }},
        {{ $summary['lapangan'] }},
        {{ $summary['rumah'] }},
        {{ $summary['other'] }}
    ];

    const labels = ['Kantor','Lapangan','Rumah','Other'];

    // KONVERSI KE PERSEN
    const dataPersen = dataJam.map(v => {
        return total > 0 ? ((v / total) * 100).toFixed(2) : 0;
    });

    new Chart(ctx, {
        type: 'pie',

        data: {
            labels: labels,

            datasets: [{
                data: dataPersen,

                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#6c757d'
                ],

                borderWidth: 2,
                borderColor: '#fff'
            }]
        },

        options: {
            responsive: true,
            aspectRatio: 1.4,
            maintainAspectRatio: true,

            layout: {
                padding: 20
            },

            plugins: {

                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        boxWidth: 12
                    }
                },

                tooltip: {
                    callbacks: {
                        label: function(ctx) {

                            const idx = ctx.dataIndex;

                            const jam = dataJam[idx];
                            const persen = dataPersen[idx];

                            return `${labels[idx]}: ${persen}% (${jam.toFixed(2)} Jam)`;
                        }
                    }
                },

                // =============================
                // 🔥 LEADER LINE LABEL
                // =============================
                datalabels: {

                    // warna teks di luar
                    color: '#333',

                    font: {
                        weight: 'bold',
                        size: 12
                    },

                    // 👉 INI INTINYA BRO
                    anchor: 'end',
                    align: 'end',
                    offset: 8,

                    // garis penunjuk
                    borderRadius: 4,
                    backgroundColor: 'rgba(255,255,255,0.85)',
                    borderWidth: 1,
                    borderColor: '#ccc',

                    formatter: (value, ctx) => {

                        // ❌ sembunyikan 0%
                        if (parseFloat(value) <= 0) {
                            return null;
                        }

                        const idx = ctx.dataIndex;
                        const jam = dataJam[idx].toFixed(2);

                        return `${labels[idx]}\n${value}% (${jam} Jam)`;
                    },

                    // 🔥 garis connector
                    display: function(ctx) {
                        return parseFloat(ctx.dataset.data[ctx.dataIndex]) > 0;
                    }
                }
            }
        },

        plugins: [ChartDataLabels]
    });

</script>

@endpush