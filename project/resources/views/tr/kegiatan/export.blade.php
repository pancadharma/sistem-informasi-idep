<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: "Times New Roman", serif; }
        h1 { font-size: 24px; }
    </style>
</head>
<body>
    <h1>{{ $kegiatan->nama }}</h1>
    <p><strong>Tanggal Mulai:</strong> {{ $kegiatan->tanggalmulai }}</p>
    <p><strong>Tanggal Selesai:</strong> {{ $kegiatan->tanggalselesai }}</p>
    <p>{{ $kegiatan->deskripsi }}</p>
</body>
</html>
