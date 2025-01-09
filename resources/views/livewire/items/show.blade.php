<div>
    <h1>Item Detail</h1>
    <div class="rounded-lg bg-slate-700 p-4">
        <div class="flex items-center justify-between">
            <h1>{{ $item->brand->name }} - {{ $item->name }}</h1>
            <div class="space-x-4">
                <a class=" inline-block btn px-3 py-1 bg-blue-500 text-base"
                href="{{ route('items.edit', $item['id'] ) }}">Edit</a>
            </div>
        </div>
        <div class="grid grid-cols-6 gap-4">
            <div>
                <div class="label">Unit Barcode</div>
                <div>{{ $item->sku ? $item->sku : 'NA' }}</div>
            </div>
            <div>
                <div class="label">CTN Barcode</div>
                <div>{{ $item->barcode ? $item->barcode : 'NA' }}</div>
            </div>
            <div>
                <div class="label">Dimension</div>
                x {{ $item->factor }} {{ $item->um }}</div>
            <div>
                <div class="label">Total Stock-In</div>
                <div class="in">
                    {{ number_format($item->ins) }} CTN
                </div>
            </div>
            <div>
                <div class="label">Sold</div>
                <div class="out">
                    {{ number_format($item->outs) }} CTN
                </div>
            </div>
            <div>
                <div class="label">Available</div>
                <span class="text-green-500">{{ number_format($item->total) }} CTN</span>
            </div>
            <div>
                <div class="label">Remark</div>
                <div>{{ $item->remark ? $item->remark : '...' }}</div>
            </div>
        </div>
    </div>
    <h1 class="mt-4">Inouts</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Flow</th>
                <th>Qty</th>
                <th>Um</th>
                <th>Cost</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inouts as $io)
                <tr>
                    <td>{{ date('d-m-Y', strtotime($io->order_id ? $io->order_id : $io->stockin->date)) }}</td>
                    <td>{{ $io->stockin_id ? 'Stock-In' : 'Sales' }}</td>
                    <td>
                        {{ $io->stockin_id ? $io->stockin->name : 'OR0000001' }}
                    </td>
                    <td>{{ $io->type }} </td>
                    <td class="{{ $io->type }}">{{ $io->qty }} </td>
                    <td>{{ $io->um }} </td>
                    <td>$ {{ number_format($io->cost * $io->qty, 2) }}</td>
                    <td>$ {{ number_format($io->price, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No Records Yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
