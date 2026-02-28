<div class="section-title">Hasil Kegiatan Lainnya</div>
<table>
    <tr>
        <th style="width: 30%;">Mengapa Dilakukan</th>
        <td>{{ $kegiatan->lainnya->lainnyamengapadilakukan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Dampak</th>
        <td>{{ $kegiatan->lainnya->lainnyadampak ?? '-' }}</td>
    </tr>
    <tr>
        <th>Sumber Pendanaan (Ket)</th>
        <td>{{ $kegiatan->lainnya->lainnyasumberpendanaan_ket ?? '-' }}</td>
    </tr>
    <tr>
        <th>Yang Terlibat</th>
        <td>{{ $kegiatan->lainnya->lainnyayangterlibat ?? '-' }}</td>
    </tr>
    <tr>
        <th>Rencana</th>
        <td>{{ $kegiatan->lainnya->lainnyarencana ?? '-' }}</td>
    </tr>
    <tr>
        <th>Kendala</th>
        <td>{{ $kegiatan->lainnya->lainnyakendala ?? '-' }}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{{ $kegiatan->lainnya->lainnyaisu ?? '-' }}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{{ $kegiatan->lainnya->lainnyapembelajaran ?? '-' }}</td>
    </tr>
</table>
