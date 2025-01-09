<div>

    <h1 class="title">Stock In</h1>

    

    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center justify-start gap-4">
            <x-input.searchform :$search />
            <x-input.perpage :$perPage />
        </div>
        <div class="flex items-center space-x-2 justify-end">
            
            <div wire:click="export()" class="btn bg-green-600 hover:bg-green-500 text-nowrap">
                <i class="i-download"></i> Download
            </div>
            <x-btn.primary >
                <x-slot name="route">{{ route('stockins.create') }}</x-slot>
                <i class="i-plus"></i> New
            </x-btn>
        </div>
    </div>
    
    <div class="grid grid-cols-12 w-full px-4 py-2 bg-slate-900">
        <div class="col-span-2">Date</div>
        <div class="col-span-3">Description</div>
        <div class="col-span-4">Contains</div>
        <div class="col-span-2">Total Cost</div>
        <div></div>
    </div>

    @forelse ($ins as $in) 
    <div x-data="{ show: false }" class="row-strip">
        <div @click="show = !show" class="grid grid-cols-12 w-full px-4 py-2 items-center">
            <div class="col-span-2 flex justify-between items-center">
                <span>
                    {{ date('d M, y', strtotime($in->date)) }} 
                </span>
                @if ($in->inouts()->count())
                    <span :class="show ? 'i-angle-down' : 'i-angle-right'" class="btn p-1 bg-slate-500 hover:bg-slate-800 mr-2"></span>
                @endif
            </div>
            <div class="col-span-3 line-clamp-1">
                
                {{ $in->name }} 
            </div>
            <div class="col-span-4">
                @foreach ($in->contains as $contain)
                    <span class="text-xs bg-black rounded-lg p-1 px-2">{{ $contain }}</span>
                @endforeach
            </div>
            <div class="col-span-2 text-yellow-500">$ {{ number_format($in->cost, 2) }}</div>
            <div class="text-right">
                <x-btn.edit>{{ route('stockins.edit', [ 'id' => $in->id ]) }} </x-btn.edit>
            </div>
        </div>
        <div x-cloak x-show="show" @click.outside="show=false" class="bg-slate-800">
            <div class="grid grid-cols-12 w-full px-4 py-1 bg-gray-900">
                <div class="col-span-2"></div>
                <div class="col-span-4">Item</div>
                <div class="col-span-1">Cost</div>
                <div class="col-span-1">QTY</div>
                <div class="col-span-1">UM</div>
                <div class="col-span-2">Total</div>
                <div></div>
            </div>
            @forelse ($in->inouts->groupBy('brand_name') as $group => $inout)
            <div class="grid grid-cols-12 w-full px-4 py-2 border-b  border-gray-600 text-gray-300">
                <div class="col-span-2"></div>
                <div class="col-span-4">
                    <span class="">{{ $group }}</span>
                </div>
                <div class="col-span-1">$ {{ number_format($inout->sum('amount') / $inout->sum('qty'),2) }} </div>
                <div class="col-span-1">{{ number_format($inout->sum('qty')) }} </div>
                <div class="col-span-1 uppercase">{{ $inout->value('um') }}</div>
                <div class="col-span-2 text-yellow-400">$ {{ number_format($inout->sum('amount'), 2) }} </div>
                <div></div>
            </div>
            @empty
            <div class="w-full px-4 py-2 border-b">
                No Variant Yet
            </div>
            @endforelse
        </div>
    </div>
    @empty
    <div class="px-4 py-2 text-center">No Data Yet</div>
    @endif

    {{-- <table class="table mt-4">
        <thead>
            <tr>
                <th>Date</th>
                <th class="w-[15%]">Purchase Order</th>
                <th >Contains</th>
                <th>QTY</th>
                <th>Cost</th>
                <th class="w-[15%]">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ins as $in)
                <tr>
                    <td>{{ date('d-m-Y', strtotime($in->date)) }} </td>
                    <td>{{ $in->name }} </td>
                    <td class="max-w-[200px]">
                        @foreach ($in->contain as $contain)
                            <span class="text-xs px-2 py-1 bg-slate-900 rounded">{{ $contain }}</span>
                        @endforeach
                    </td>
                    <td class="text-blue-300">{{ number_format($in->qty) }} CTN</td>
                    <td class="text-blue-500">$ {{ number_format($in->cost) }}</td>
                    <td>
                        <a href="{{ route('stockins.show', [ 'id' => $in->id ] ) }}" wire:navigation class="btn text-sm px-2 py-1 bg-slate-900 rounded">View</a>
                        <a href="{{ route('stockins.edit', [ 'id' => $in->id ] ) }}" wire:navigation class="btn text-sm px-2 py-1 bg-slate-900 rounded">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No Data</td>
                </tr>
            @endforelse
        </tbody>
    </table> --}}

</div>
