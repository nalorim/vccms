<div>
    <h1 class="title">Stock In detail</h1>

    <div class="rounded-lg bg-slate-700 p-4">
        <div class="flex items-center justify-between">
            <h1>{{ $po['name'] }}</h1>
            <a class="btn px-3 bg-blue-500"
            href="{{ route('stockins.edit', $po['id'] ) }}">Edit</a>
        </div>
        <div class="grid grid-cols-5 gap-4">
            <div>
                <div class="label">Received Date</div>
                <div>
                    {{ date('d-m-Y', strtotime($po['date']) ) }}
                </div>
            </div>
            <div class="col-span-2">
                <div class="label">Contain</div>
                <div>
                    @foreach ($po['contains'] as $contain)
                        <span class="text-xs px-2 py-1 bg-slate-900 rounded">{{ $contain }}</span>
                    @endforeach
                </div>
            </div>
            <div>
                <div class="label">Total QTY</div>
                <div>
                    {{ number_format($po['qty']) }} CTN
                </div>
            </div>
            <div>
                <div class="label">Total Cost</div>
                <div class="text-green-500">
                    $ {{ number_format($po['cost'], 2) }}
                </div>
            </div>
            <div class="col-span-5">
                <div class="label">Remark</div>
                <div class="text-green-500">
                    {{ $po['note'] ? $po['note'] : '...' }}
                </div>
            </div>
        </div>
    </div>

    <h1 class="mt-4">{{ count($list) }} Items in Total</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ItemID</th>
                <th>Brand</th>
                <th>Name</th>
                <th>Cost</th>
                <th>QTY</th>
                <th>UM</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($list as $l)
                <tr>
                    <td>{{ $l['item_id'] }}</td>
                    <td>{{ $l['brand_name'] }}</td>
                    <td>{{ $l['name'] }}</td>
                    <td>$ {{ number_format($l['cost'],2) }}</td>
                    <td>{{ number_format($l['qty']) }}</td>
                    <td>{{ $l['um'] }}</td>
                    <td class="text-green-500">$ {{ number_format($l['amount'],2) }} </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No Data Yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
