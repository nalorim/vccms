<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Item</th>
            <th>Dimension</th>
            <th>Price</th>
            <th>CTN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $loop->index + 1 }} </td>
                <td>{{ $item->brand->name }} - {{ $item->name }} </td>
                <td>{{ $item->factor }} {{ $item->um }}</td>
                <td>{{ $item->price }} </td>
                <td>{{ $item->total }} </td>
            </tr>
        @endforeach
    </tbody>
</table>