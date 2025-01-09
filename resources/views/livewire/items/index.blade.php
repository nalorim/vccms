<div>

    <div class="flex items-center justify-between mb-4">
        <h1 class="title mb-0">Items</h1>
        
    </div>
    
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center justify-start gap-4">
            <x-input.searchform :$search />
            <x-input.perpage :$perPage />
            <div wire:ignore>
                <select wire:model.live="um" class="form-input bg-transparent rounded-lg w-[120px]">
                    <option value="">Base UM</option>
                    <option value="ctn">CTN</option>
                </select>
            </div>
        </div>
        <div class="flex items-center space-x-2 justify-end">
            
            <div wire:click="export()" class="btn bg-green-600 hover:bg-green-500 text-nowrap">
                <i class="i-download"></i> Download
            </div>
            <x-btn.primary >
                <x-slot name="route">{{ route('items.create') }}</x-slot>
                <i class="i-plus"></i> New
            </x-btn>
        </div>
    </div>

    <div>
        <div class="grid grid-cols-12 w-full px-4 py-2 bg-slate-900">
            <div class="">#</div>
            <div class="col-span-4">Product</div>
            <div class="col-span-1">In-Stock</div>
            <div class="col-span-1">UM</div>
            <div class="col-span-1">Factor</div>
            <div class="col-span-1">Price</div>
            <div class="col-span-1">Cost</div>
            <div class="col-span-1">Margin</div>
            <div></div>
        </div>
    
        @forelse ($brands as $brand)    
        <div x-data="{ show: false, highlight: false }" class="row-strip">
            <div @click="highlight=!highlight; show = !show" @click.outside="highlight=false" :key="@{$brand->id}" class="grid grid-cols-12 items-center w-full px-4 py-2 " :class="highlight ? '' : ''">
                <div class="grid grid-cols-2 gap-2 items-center">
                    <span>
                        {{ $loop->index + 1 }} 
                    </span>
                    @if (count($brand->items))
                    <span :class="show ? 'i-angle-down' : 'i-angle-right'" class="btn p-1 bg-slate-500 hover:bg-slate-800 mr-2"></span>
                    @endif
                </div>
                <div class="col-span-4">{{ $brand->name }}</div>
                <div class="col-span-1">
                    <span class="text-blue-300">
                        @php
                            $brand_stock = $um != "ctn" ? $brand->stock_base : ($brand->stock_base / $brand->factor)
                            @endphp
                        {{ number_format($brand_stock) }}
                    </span>
                </div>
                <div class="col-span-1 uppercase">{{ $um == "ctn" ? $um : $brand->um }}</div>
                <div class="col-span-1">{{ $brand->factor }}</div>
                <div class="col-span-1 text-blue-300">
                    @php
                        $price = $um != "ctn" ? $brand->base_price : $brand->ctn_price
                    @endphp
                    $ {{ number_format($price, 2) }}
                </div>
                <div class="col-span-1">
                    @php
                        $cost = $um != "ctn" ? $brand->avg_cost : ($brand->avg_cost * $brand->factor)
                    @endphp
                    $ {{ number_format($cost, 2) }}
                </div>
                <div class="col-span-1 text-green-500">
                    $ {{ number_format( ($price - $cost) , 2) }}
                </div>
                <div class="text-right">
                    <x-btn.edit>{{ route('items.edit', [ 'id' => $brand->id ]) }} </x-btn.edit>
                </div>
            </div>
            <div x-cloak x-show="show" @click.outside="show=false" class="bg-slate-800">
                <div class="grid grid-cols-12 w-full px-4 py-1 bg-gray-900 text-gray-400 border-b border-gray-600">
                    <div></div>
                    <div class="col-span-3">Variant Name</div>
                    <div class="col-span-2">Unit Barcode</div>
                    <div class="col-span-2">CTN Barcode</div>
                    <div class="col-span-2">In-Stock</div>
                    <div class="col-span-2">Remark</div>
                </div>
                @forelse ($brand->items as $item)
                <div class="grid grid-cols-12 w-full px-4 py-2 border-b border-gray-600 text-gray-300">
                    <div></div>
                    <div class="col-span-3">
                        <span class="">{{ $brand->name }} - {{ $item->name }}</span>
                    </div>
                    <div class="col-span-2">
                        {{ $item->sku ? preg_replace('/(?<=\d)(?=(\d{4})+$)/', ' ', $item->sku) : '...' }}
                    </div>
                    <div class="col-span-2">
                        {{ $item->barcode ? preg_replace('/(?<=\d)(?=(\d{4})+$)/', ' ', $item->barcode) : '...' }}
                    </div>
                    <div class="col-span-2">
                        @php
                            $total_qty = $um != "ctn" ? $item->total_base : ($item->total_base / $item->factor);
                        @endphp
                        <span class="bg-{{ $item->color }}-500 px-2 py-1 rounded uppercase">
                            {{ number_format($total_qty) }} {{ $um != "ctn" ? $item->um : 'CTN' }}
                        </span>
                    </div>
                    <div class="col-span-2 line-clamp-1">
                        {{ $item->remark ?? '...' }}
                    </div>
                </div>
                @empty
                <div class="w-full px-4 py-2 border-b text-center">
                    No Variant Yet
                </div>
                @endforelse
            </div>
        </div>
        @empty
        <div class="px-4 py-2 text-center">No Data Yet</div>
        @endforelse

    </div>

    <div class="mt-6">{{ $brands->links() }} </div>
</div>
