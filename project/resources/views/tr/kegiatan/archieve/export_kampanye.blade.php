<div class="section-title">Hasil Kegiatan Kampanye</div>
<table>
    <tr>
        <th style="width: 30%;">Yang Dikampanyekan</th>
        <td>{{ $kegiatan->kampanye->kampanyeyangdikampanyekan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Jenis</th>
        <td>{{ $kegiatan->kampanye->kampanyejenis ?? '-' }}</td>
    </tr>
    <tr>
        <th>Bentuk Kegiatan</th>
        <td>{{ $kegiatan->kampanye->kampanyebentukkegiatan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Yang Terlibat</th>
        <td>{{ $kegiatan->kampanye->kampanyeyangterlibat ?? '-' }}</td>
    </tr>
    <tr>
        <th>Yang Disasar</th>
        <td>{{ $kegiatan->kampanye->kampanyeyangdisasar ?? '-' }}</td>
    </tr>
    <tr>
        <th>Jangkauan</th>
        <td>{{ $kegiatan->kampanye->kampanyejangkauan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Rencana</th>
        <td>{{ $kegiatan->kampanye->kampanyerencana ?? '-' }}</td>
    </tr>
    <tr>
        <th>Kendala</th>
        <td>{{ $kegiatan->kampanye->kampanyekendala ?? '-' }}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{{ $kegiatan->kampanye->kampanyeisu ?? '-' }}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{{ $kegiatan->kampanye->kampanyepembelajaran ?? '-' }}</td>
    </tr>
</table>
