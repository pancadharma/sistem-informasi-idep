<div class="section-title">Hasil Kegiatan Sosialisasi</div>
<table>
    <tr>
        <th style="width: 30%;">Yang Terlibat</th>
        <td>{!! $kegiatan->sosialisasi->sosialisasiyangterlibat ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Temuan</th>
        <td>{!! $kegiatan->sosialisasi->sosialisasitemuan ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Tambahan</th>
        <td>{!! $kegiatan->sosialisasi->sosialisasitambahan ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Keterangan Tambahan</th>
        <td>{!! $kegiatan->sosialisasi->sosialisasitambahan_ket ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Kendala</th>
        <td>{!! $kegiatan->sosialisasi->sosialisasikendala ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{!! $kegiatan->sosialisasi->sosialisasiisu ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{!! $kegiatan->sosialisasi->sosialisasipembelajaran ?? '-' !!}</td>
    </tr>
</table>
