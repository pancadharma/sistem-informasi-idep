<div class="section-title">Hasil Kegiatan Pelatihan</div>
<table>
    <tr>
        <th style="width: 30%;">Pelatih</th>
        <td>{!! $kegiatan->pelatihan->pelatihanpelatih ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Hasil</th>
        <td>{!! $kegiatan->pelatihan->pelatihanhasil ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Distribusi</th>
        <td>{!! $kegiatan->pelatihan->pelatihandistribusi ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Keterangan Distribusi</th>
        <td>{!! $kegiatan->pelatihan->pelatihandistribusi_ket ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Rencana</th>
        <td>{!! $kegiatan->pelatihan->pelatihanrencana ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Unggahan</th>
        <td>{!! $kegiatan->pelatihan->pelatihanunggahan ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{!! $kegiatan->pelatihan->pelatihanisu ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{!! $kegiatan->pelatihan->pelatihanpembelajaran ?? '-' !!}</td>
    </tr>
</table>
