<div class="section-title">Hasil Kegiatan Pemetaan</div>
<table>
    <tr>
        <th style="width: 30%;">Yang Dihasilkan</th>
        <td>{{ $kegiatan->pemetaan->pemetaanyangdihasilkan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Luasan</th>
        <td>{{ $kegiatan->pemetaan->pemetaanluasan ?? '-' }}</td>
    </tr>
    <tr>
        <th>Unit</th>
        <td>{{ $kegiatan->pemetaan->pemetaanunit ?? '-' }}</td>
    </tr>
    <tr>
        <th>Yang Terlibat</th>
        <td>{{ $kegiatan->pemetaan->pemetaanyangterlibat ?? '-' }}</td>
    </tr>
    <tr>
        <th>Rencana</th>
        <td>{{ $kegiatan->pemetaan->pemetaanrencana ?? '-' }}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{{ $kegiatan->pemetaan->pemetaanisu ?? '-' }}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{{ $kegiatan->pemetaan->pemetaanpembelajaran ?? '-' }}</td>
    </tr>
</table>
