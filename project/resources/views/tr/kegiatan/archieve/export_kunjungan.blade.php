<div class="section-title">Hasil Kegiatan Kunjungan</div>
<table>
    <tr>
        <th style="width: 30%;">Lembaga</th>
        <td>{{ $kegiatan->kunjungan->kunjunganlembaga ?? '-' }}</td>
    </tr>
    <tr>
        <th>Peserta</th>
        <td>{{ $kegiatan->kunjungan->kunjunganpeserta ?? '-' }}</td>
    </tr>
    <tr>
        <th>Yang Dilakukan</th>
        <td>{{ $kegiatan->kunjungan->kunjunganyangdilakukan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Hasil</th>
        <td>{{ $kegiatan->kunjagan->kunjunganhasil ?? '-' }}</td>
    </tr>
    <tr>
        <th>Potensi Pendapatan</th>
        <td>{{ $kegiatan->kunjungan->kunjunganpotensipendapatan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Rencana</th>
        <td>{{ $kegiatan->kunjungan->kunjunganrencana ?? '-' }}</td>
    </tr>
    <tr>
        <th>Kendala</th>
        <td>{{ $kegiatan->kunjungan->kunjungankendala ?? '-' }}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{{ $kegiatan->kunjungan->kunjunganisu ?? '-' }}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{{ $kegiatan->kunjungan->kunjunganpembelajaran ?? '-' }}</td>
    </tr>
</table>
