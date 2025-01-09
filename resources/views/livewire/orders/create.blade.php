<div>
    
    <div class="flex items-center justify-between">
        <h1 class="mb-0">Order create</h1>
        <button wire:click="createOrder()" class="px-4 border btn bg-blue-500">Create</button>
    </div>
    
    <div class="item-create">
        
        <div class="col-span-5 space-y-4 order-create">

            @if (session()->has('stock'))
                <div class="bg-yellow-600 p-4 rounded-lg flex items-center justify-between">
                    <span>
                        {{ session('stock') }}
                    </span>
                    <span wire:click="clearValidate()" class="i-cancel hover:text-slate-900"></span>
                </div>
            @endif

            <div class="rounded-lg grid grid-cols-3 gap-8">

                <div class="grid grid-cols-1 gap-2 info-input">
                    
                    <div x-data="{ dropdown: false }">
                        <span class="label">Customer</span>
                        <div class="relative flex items-center">
                            <input type="text" wire:model.live.debouce="order.customer_name" @click="dropdown = true"
                            class="form-input bg-transparent w-full rounded-lg" placeholder="Customer">
        
                            @if($order['customer_id'])
                            <button wire:click="clearSelectCustomer()"
                            class="absolute right-2 text-xs w-[20px] aspect-square rounded-full bg-gray-500">x</button>
                            @endif
                            
                            <div x-cloak x-show="dropdown" @click.outside="dropdown = false" class="dropdown">
                                <div class="grid grid-cols-1 gap-2">
                                    @forelse ($customers as $customer)
                                        <div @click="dropdown = false" wire:click="selectCustomer({{ $customer->id }})" class="button">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    {{ $customer->name }}
                                                    <div class="text-gray-400 text-sm">{{ $customer->phone }}</div>
                                                </div>
                                                <div>
                                                    {{ $customer->location }}
                                                    <div class="text-gray-400 text-sm">{{ $customer->market->name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div>Not Found</div>
                                    @endforelse
                                </div>
                            </div>
        
                        </div>
                        @error('order.customer_id')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div x-data="{ dropdown: false }">
                        <span class="label">Salesperson</span>
                        <div class="relative flex items-center">
                            <input type="text" wire:model="order.salesperson_name" @click="dropdown = true"
                            class="form-input bg-transparent w-full rounded-lg" placeholder="Salesperson">
        
                            @if($order['salesperson_id'])
                            <button wire:click="clearSelectSalesperson()"
                            class="absolute right-2 text-xs w-[20px] aspect-square rounded-full bg-gray-500">x</button>
                            @endif
                            
                            <div x-cloak x-show="dropdown" @click.outside="dropdown = false" class="dropdown">
                                <div class="grid grid-cols-1 gap-2">
                                    @forelse ($salespersons as $sale)
                                        <button @click="dropdown = false" wire:click="selectSalesperson({{ $sale->id }})" class="button">
                                            {{ $sale->profile->name }}
                                        </button>
                                    @empty
                                        <div>Not Found</div>
                                    @endforelse
                                </div>
                            </div>
        
                        </div>
                    </div>
                    <div x-data="{ dropdown: false }">
                        <span class="label">Market & Pricing</span>
                        <div class="relative flex items-center ">
                            <input type="text" wire:model.live.debouce="order.market_name" @click="dropdown = true"
                            class="form-input bg-transparent w-full rounded-lg capitalize" placeholder="Market">
        
                            @if($order['market_id'])
                            <button wire:click="clearSelectMarket()"
                            class="absolute right-2 text-xs w-[20px] aspect-square rounded-full bg-gray-500">x</button>
                            @endif
                            
                            <div x-cloak x-show="dropdown" @click.outside="dropdown = false" class="dropdown">
                                <div class="grid grid-cols-1 gap-2">
                                    @forelse ($markets as $market)
                                        <button @click="dropdown = false" wire:click="selectMarket({{ $market->id }})" class="button">
                                            {{ $market->name }}
                                        </button>
                                    @empty
                                        <div>Not Found</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="col-span-2 grid grid-cols-1 gap-2 info-input">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <span class="label">Order Type</span>
                            <select wire:model="order.order_type" wire:change="changeOrderType()" class="form-input bg-transparent w-full rounded-lg">
                                <option value="general">General Customer</option>
                                <option value="tax">Tax Invoice</option>
                            </select>
                        </div>
                        <div>
                            <div class="text-sm label">Order Date</div>
                            <input type="date" wire:model="order.order_date" class="form-input bg-transparent w-full rounded-lg" placeholder="Date">
                        </div>
                        <div>
                            <div class="text-sm label">Order ID</div>
                            <div class="form-input bg-slate-600 w-full rounded-lg border-none">OR{{ sprintf("%06d", $order['order_id']) }}</div>
                            {{-- <input type="text" wire:model="order.customer_remark" class="form-input bg-transparent w-full rounded-lg" placeholder="..."> --}}
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <div class="text-sm label">Discount %</div>
                            <input type="text" wire:model="order.discount" wire:change="calculateAll()" class="form-input bg-transparent w-full rounded-lg" placeholder="Discount">
                        </div>
                        <div>
                            <div class="text-sm label">VAT %</div>
                            <input type="text" wire:model="order.vat" wire:change="calculateAll()" class="form-input bg-transparent w-full rounded-lg" placeholder="VAT">
                        </div>
                        <div>
                            <div class="text-sm label">Terms</div>
                            <select wire:model="order.terms" class="form-input bg-transparent w-full rounded-lg">
                                <option value="1">COD</option>
                                <option value="7">7 Days</option>
                                <option value="15">15 Days</option>
                                <option value="30">30 Days</option>
                                <option value="60">60 Days</option>
                            </select>
                        </div>
                        <div>
                            <div class="text-sm label">Rate </div>
                            <input type="text" wire:model="order.order_rate" wire:change="calculateAll()" class="form-input bg-transparent w-full rounded-lg" placeholder="NBC rate...">
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <span class="label">Transport Fee</span>
                            <select wire:model="order.transport" wire:change="calculateItemList()" class="form-input bg-transparent w-full rounded-lg">
                                <option value="1">Included</option>
                                <option value="0">Excluded</option>
                            </select>
                        </div>
                        <div class="col-span-3">
                            <div class="text-sm label">Remark </div>
                            <input type="text" wire:model="order.remark" class="form-input bg-transparent w-full rounded-lg" placeholder="Remark...">
                        </div>
                    </div>
                    
                </div>

            </div>

            {{-- <div class="text-sm text-gray-500">Choose Market to define item price else default price will be applied.</div> --}}
            <div class="w-full">
                <table class="table w-full text-sm table-input">
                    <thead>
                        <tr class="">
                            <th class="w-[3%]"></th>
                            <th class="w-[20%]">Product</th>
                            <th class="w-[8%]">In Stock</th>
                            <th class="w-[10%]">QTY</th>
                            <th class="w-[10%]">UM</th>
                            <th class="w-[10%]">Price</th>
                            <th class="w-[10%]">Transport</th>
                            <th class="w-[10%]">Dis %</th>
                            <th class="w-[15%]">Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($list as $k => $l)
                            <tr>
                                <td>
                                    <button wire:click="addVariants({{ $k }})" class="hover:text-yellow-500">{{ $k+1 }}</button>
                                </td>
                                <td x-data="{ dropdown: false }" class="relative">
                                    <input type="text" wire:model="list.{{ $k }}.name" @click="dropdown = true" placeholder="..." class="form-input w-full rounded-lg bg-transparent">
                                    
                                    {{-- @if ($list[$k]['item_id'])
                                        <span class="absolute -top-2 right-2 text-xs text-gray-500">{{ $list[$k]['factor'] }} per CTN</span>
                                    @endif --}}

                                    @error('list.'.$k.'.name')
                                        <span class="absolute top-0 right-2 text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                    
                                    <div x-cloak x-show="dropdown" @click.outside="dropdown = false" class="dropdown">
                                        <input type="text" wire:model.live.debounce="findItem" placeholder="Search..." class="form-input bg-transparent w-full rounded-lg mb-2">
                                        @forelse ($items as $item)
                                            <button @click="dropdown = false" wire:click="selectItem({{ $item->id }}, {{ $k }})" class="button w-full mb-2 flex items-center justify-between">
                                                <div>
                                                    <div class="label text-xs">{{ $item->brand->name }}</div>
                                                    <span>{{ $item->name }} </span>
                                                </div>
                                                <div>
                                                    <div class="label text-xs">Available</div>
                                                    <span class="{{ $item->total > 0 ? 'text-green-500' : '' }}">≤ {{ number_format($item->total) }} Ctn</span>
                                                </div>
                                            </button>
                                        @empty
                                            <div>Not Found</div>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $line_stock = $list[$k]['stock'] * ($list[$k]['um'] != 'ctn' ? $list[$k]['factor'] : 1);
                                        $line_qty = $list[$k]['qty'];
                                        $line_um = $list[$k]['um'];
                                    @endphp
                                    @if($line_qty != '')
                                        <div class="{{ ($line_stock - $line_qty) >= 0 ? 'text-yellow-500' : 'text-red-500' }}">
                                            {{ number_format($line_stock - $line_qty) }} <span>{{ $line_um }}</span> 
                                        </div>
                                    @endif
                                </td>
                                <td class="relative">
                                    <input type="number" min="0" wire:model.live.debounce="list.{{ $k }}.qty" wire:change="calculateItemList()" placeholder="0"
                                    class="form-input w-full rounded-lg bg-transparent">
                                    @error('list.'.$k.'.qty')
                                        <span class="absolute top-0 left-2 text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td>
                                    <select wire:model.live.debouce="list.{{ $k }}.um" wire:change="calculateItemList()" class="form-input w-full rounded-lg bg-transparent uppercase">
                                        <option value="{{ $list[$k]['base_um'] }}">{{ $list[$k]['base_um'] }}</option>
                                        @if ($list[$k]['base_um'] != "ctn")
                                        <option value="ctn">CTN</option>
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <span class="form-input border-none text-sm pl-0 text-gray-400 rounded-lg bg-transparent">$ {{ number_format($list[$k]['base_price'], 2) }}</span>
                                    {{-- <input type="text" wire:model="list.{{ $k }}.base_price" wire:change="calculateItemAmount({{ $k }})" placeholder="$ 00.00" 
                                    class="form-input w-full rounded-lg bg-transparent"> --}}
                                </td>
                                <td>
                                    <span class="form-input border-none text-sm pl-0 text-gray-400 rounded-lg bg-transparent">$ {{ number_format($list[$k]['transport'], 2) }}</span>
                                </td>
                                <td>
                                    <input type="text" wire:model="list.{{ $k }}.discount" wire:change="calculateItemList()" placeholder="0 %" 
                                    class="form-input w-full rounded-lg bg-transparent">
                                </td>
                                <td class="relative">
                                    <div class="form-input border-none w-full rounded-lg bg-transparent text-sm">$ {{ number_format($list[$k]['amount'], 2) }}</div>
                                </td>
                                <td>
                                    @if(count($list) > 1)
                                    <button wire:click="removeItem({{ $k }})" class="btn p-1 i-trash text-red-500"></button>
                                    @endif
                                </td>
                            </tr>
                        @empty   
                        <tr>
                            <td colspan="*">No Data</td>
                        </tr>
                        @endforelse
                        <tr class="actions">
                            <td colspan="6">
                                <div class="grid grid-cols-4 gap-2">
                                    <button wire:click="addMoreItem()" class="btn bg-yellow-600 p-2 rounded-lg text-xs"> <span class="i-plus"></span> More Item</button>
                                    <button wire:click="clearList()" class="btn bg-slate-800 p-2 rounded-lg text-xs">Clear List</button>
                                    
                                </div>
                            </td>
                            <td colspan="2" class="text-right">
                                Total {{ count($list) }} Items 
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-5 gap-4 bg-slate-800 p-4 rounded-lg">
                <div>
                    <div class="label text-xs">Subtotal</div> 
                    $ {{ number_format($subtotal, 2) }}
                </div>
                <div>
                    <div class="label text-xs">Discount {{ $order['discount'] }} %</div>
                    $ {{ number_format($discount, 2) }}
                </div>
                <div>
                    <div class="label text-xs">VAT {{ $order['vat'] }} %</div>
                    $ {{ number_format($vat, 2) }}
                </div>
                <div class="bg-slate-600 p-2 rounded">
                    <div class="label text-xs">Grand Total</div>
                    USD {{ number_format($total, 2) }}
                </div>
                <div class="bg-slate-600 p-2 rounded">
                    <div class="label text-xs">Grand Total</div>
                    KHR {{ number_format($totalKhr, 2) }}
                </div>
            </div>
            
        </div>
    </div>

    @if($modal['customer'])
        <x-customers.create :$customer />
    @endif
</div>
