<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background: #efefef; }
    </style>
    <title>{{ $title ?? 'Report' }}</title>
    </head>
<body>
    <h2>{{ $title ?? 'Report' }}</h2>
    <table>
        <thead>
            <tr>
                @foreach($headings as $h)
                    <th>{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $r)
                <tr>
                    @foreach($r as $v)
                        <td>{{ $v }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($headings) }}" style="text-align:center">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

