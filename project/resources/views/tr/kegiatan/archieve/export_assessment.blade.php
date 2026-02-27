<div class="section-title">Hasil Kegiatan Assessment</div>
<table>
    <tr>
        <th style="width: 30%;">Yang Terlibat</th>
        <td>{!! $kegiatan->assessment->assessmentyangterlibat ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Temuan</th>
        <td>{!! $kegiatan->assessment->assessmenttemuan ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Tambahan</th>
        <td>{!! $kegiatan->assessment->assessmenttambahan ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Keterangan Tambahan</th>
        <td>{!! $kegiatan->assessment->assessmenttambahan_ket ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Kendala</th>
        <td>{!! $kegiatan->assessment->assessmentkendala ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{!! $kegiatan->assessment->assessmentisu ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{!! $kegiatan->assessment->assessmentpembelajaran ?? '-' !!}</td>
    </tr>
</table>
