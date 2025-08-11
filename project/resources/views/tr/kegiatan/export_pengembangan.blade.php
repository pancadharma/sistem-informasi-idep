<div class="section-title">Hasil Kegiatan Pengembangan</div>
<table>
    <tr>
        <th style="width: 30%;">Jenis Komponen</th>
        <td>{!! $kegiatan->pengembangan->pengembanganjeniskomponen ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Berapa Komponen</th>
        <td>{!! $kegiatan->pengembangan->pengembanganberapakomponen ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Lokasi Komponen</th>
        <td>{!! $kegiatan->pengembangan->pengembanganlokasikomponen ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Yang Terlibat</th>
        <td>{!! $kegiatan->pengembangan->pengembanganyangterlibat ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Rencana</th>
        <td>{!! $kegiatan->pengembangan->pengembanganrencana ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Kendala</th>
        <td>{!! $kegiatan->pengembangan->pengembangankendala ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{!! $kegiatan->pengembangan->pengembanganisu ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{!! $kegiatan->pengembangan->pengembanganpembelajaran ?? '-' !!}</td>
    </tr>
</table>
