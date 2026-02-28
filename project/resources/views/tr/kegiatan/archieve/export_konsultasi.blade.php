<div class="section-title">Hasil Kegiatan Konsultasi</div>
<table>
    <tr>
        <th style="width: 30%;">Lembaga</th>
        <td>{{ $kegiatan->konsultasi->konsultasilembaga ?? '-' }}</td>
    </tr>
    <tr>
        <th>Komponen</th>
        <td>{{ $kegiatan->konsultasi->konsultasikomponen ?? '-' }}</td>
    </tr>
    <tr>
        <th>Yang Dilakukan</th>
        <td>{{ $kegiatan->konsultasi->konsultasiyangdilakukan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Hasil</th>
        <td>{{ $kegiatan->konsultasi->konsultasihasil ?? '-' }}</td>
    </tr>
    <tr>
        <th>Potensi Pendapatan</th>
        <td>{{ $kegiatan->konsultasi->konsultasipotensipendapatan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Rencana</th>
        <td>{{ $kegiatan->konsultasi->konsultasirencana ?? '-' }}</td>
    </tr>
    <tr>
        <th>Kendala</th>
        <td>{{ $kegiatan->konsultasi->konsultasikendala ?? '-' }}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{{ $kegiatan->konsultasi->konsultasiisu ?? '-' }}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{{ $kegiatan->konsultasi->konsultasipembelajaran ?? '-' }}</td>
    </tr>
</table>
