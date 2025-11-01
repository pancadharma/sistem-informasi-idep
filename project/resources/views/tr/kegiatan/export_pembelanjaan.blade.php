<div class="section-title">Hasil Kegiatan Pembelanjaan</div>
<table>
    <tr>
        <th style="width: 30%;">Detail Barang</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaandetailbarang ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Mulai</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaanmulai ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Selesai</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaanselesai ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Distribusi Mulai</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaandistribusimulai ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Distribusi Selesai</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaandistribusiselesai ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Terdistribusi</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaanterdistribusi ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Akan Didistribusi</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaanakandistribusi ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Keterangan Akan Didistribusi</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaanakandistribusi_ket ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Kendala</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaankendala ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Isu</th>
        <td>{!! $kegiatan->pembelanjaan->pembelanjaanisu ?? '-' !!}</td>
    </tr>
    <tr>
        <th>Pembelajaran</th>
        <td>{!! $kegiat->pembelanjaan->pembelanjaanpembelajaran ?? '-' !!}</td>
    </tr>
</table>
