<div class="section-title">Hasil Kegiatan Monitoring</div>
<table>
    <tr>
        <th style="width: 30%;">Yang Dipantau</th>
        <td>{{ $kegiatan->monitoring->monitoringyangdipantau ?? '-' }}</td>
    </tr>
    <tr>
        <th>Data</th>
        <td>{{ $kegiatan->monitoring->monitoringdata ?? '-' }}</td>
    </tr>
    <tr>
        <th>Yang Terlibat</th>
        <td>{{ $kegiatan->monitoring->monitoringyangterlibat ?? '-' }}</td>
    </tr>
    <tr>
        <th>Metode</th>
        <td>{{ $kegiatan->monitoring->monitoringmetode ?? '-' }}</td>
    </tr>
    <tr>
        <th>Hasil</th>
        <td>{{ $kegiatan->monitoring->monitoringhasil ?? '-' }}</td>
    </tr>
    <tr>
        <th>Kegiatan Selanjutnya</th>
        <td>{{ $kegiatan->monitoring->monitoringkegiatanselanjutnya ?? '-' }}</td>
    </tr>
    <tr>
        <th>Keterangan Kegiatan Selanjutnya</th>
        <td>{{ $kegiatan->monitoring->monitoringkegiatanselanjutnya_ket ?? '-' }}</td>
    </tr>
    <tr>
        <th>Kendala</th>
        <td>{{ $kegiatan->monitoring->monitoringkendala ?? '-' }}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{{ $kegiatan->monitoring->monitoringisu ?? '-' }}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{{ $kegiatan->monitoring->monitoringpembelajaran ?? '-' }}</td>
    </tr>
</table>
