<table class="table table-bordered table-striped">
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
            <tr><td colspan="{{ count($headings) }}" class="text-center">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>

