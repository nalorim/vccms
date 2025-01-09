<div>
    <h1 class="title">Markets & Price</h1>

    <div class="grid grid-cols-4 gap-4 justify-start items-start">

        <div class="bg-slate-700 p-4 rounded-lg">
            <div class="grid grid-cols-5 gap-4">
                <input type="text" wire:model.live.debounce="searchMarket" placeholder="Search..."
                class="w-full form-input bg-transparent rounded-lg col-span-4">
                <button wire:click="showCreateMarketModal()" class="btn border">+</button>
            </div>
            <div class="max-h-[400px] overflow-y-scroll flex flex-col gap-2 my-4">
                @forelse ($markets as $m)
                    <div wire:click="selectMarket({{ $m->id }})" class="btn bg-slate-800 hover:bg-slate-900 rounded-lg px-4 py-2 flex items-center justify-between capitalize select-none">
                        <span>{{ $m->name }}</span>
                        @if($market['id'] == $m->id)
                        <span>ok</span>
                        @endif
                    </div>
                @empty
                    <span>No Market Yet</span>
                @endforelse
            </div>
        </div>

        <div class="col-span-3">
            <div class="flex items-center justify-between mb-4">
                @if(!$edit)
                    <div class="text-xl uppercase">{{ $market['name'] }} Price</div>
                @else
                    <input type="text" wire:model="market.name" class="form-input w-full bg-transparent rounded-lg mr-8">
                @endif
                <div>
                    @if($market['id'])
                    <button wire:click="delete({{ $market['id'] }})" class="btn bg-red-500 hover:bg-red-600 px-3 py-1">Delete</button>
                    @endif
                </div>
            </div>
            @if($market['id'])
            <div class="bg-slate-700 p-4 rounded-lg grid grid-cols-4 gap-4">
                <div>
                    <div class="label">Short</div>
                    @if(!$edit)
                        <div>{{ $market['slug'] }}</div>
                    @else
                        <input type="text" wire:model="market.slug" class="form-input w-full bg-transparent rounded-lg">
                    @endif
                </div>
                <div>
                    <div class="label">VAT</div>
                    @if(!$edit)
                        <div>{{ $market['vat'] ? $market['vat'] : 0 }} %</div>
                    @else
                        <input type="text" wire:model="market.vat" class="form-input w-full bg-transparent rounded-lg">
                    @endif
                </div>
                <div>
                    <div class="label">Discount</div>
                    @if(!$edit)
                        <div>{{ $market['discount'] }} %</div>
                    @else
                        <input type="text" wire:model="market.discount" class="form-input w-full bg-transparent rounded-lg">
                    @endif
                </div>
                <div>
                    <div class="label">Terms</div>
                    @if(!$edit)
                        <div>{{ $market['terms'] }} Days</div>
                    @else
                        <select wire:model="market.terms" class="form-input w-full rounded bg-transparent">
                            <option value="1">COD</option>
                            <option value="7">7 days</option>
                            <option value="15">15 days</option>
                            <option value="30">30 days</option>
                            <option value="60">60 days</option>
                        </select>
                    @endif
                </div>
            </div>
            @endif
            <div class="my-4 flex items-center justify-between">
                <div>
                    <input type="text" wire:model.live="searchItem" placeholder="Search..." class="form-input w-[200px] bg-transparent rounded-lg">
                </div>
                <div>
                    @if(!$edit)
                        <button wire:click="editPrice()" class="btn border">+</button>
                    @else
                        <button wire:click="$set('edit', false)" class="btn border">cancel</button>
                        <button wire:click="update()" class="btn border">Update</button>
                    @endif
                </div>
            </div>
            <table class="table w-full my-4">
                <thead>
                    <tr>
                        <th>Brand</th>
                        <th>Dimension</th>
                        <th class="w-[20%]">Base Price</th>
                        <th class="w-[20%]">CTN Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $i => $brand)
                        <tr>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->factor }} {{ $brand->um }}</td>
                            <td class="">
                                @if($market['id'])
                                    <span class="text-gray-500 text-xs">
                                        $ {{ number_format($brand->base_price, 4) }} 
                                    </span>
                                @endif
                                @if(!$edit)
                                <span class="{{ $brand->show_base_price($market['id']) != 0 ? 'text-yellow-500' : 'text-gray-400' }}">
                                    $ {{ number_format($brand->show_base_price($market['id']) != 0 ? $brand->show_base_price($market['id']) : $brand->base_price, 2) }} 
                                </span>
                                @else
                                <input type="text" wire:model="formPrice.{{ $i }}.base_price" class="form-input w-full bg-transparent rounded-lg">
                                @endif
                            </td>
                            <td>
                                @if($market['id'])
                                    <span class="text-gray-500 text-xs">
                                        $ {{ number_format($brand->ctn_price, 2) }} 
                                    </span>
                                @endif
                                @if(!$edit)
                                <span class="{{ $brand->show_ctn_price($market['id']) != 0 ? 'text-yellow-500' : 'text-gray-400' }}">
                                    $ {{ number_format($brand->show_ctn_price($market['id']) != 0 ? $brand->show_ctn_price($market['id']) : $brand->ctn_price, 2) }} 
                                </span>
                                @else
                                <input type="text" wire:model="formPrice.{{ $i }}.ctn_price" wire:change="calculateBasePrice({{ $i }})" class="form-input w-full bg-transparent rounded-lg">
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No Item Yet</td>
                        </tr>                        
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($createMarketModal)
        <x-markets.create :$forms />
    @endif
</div>
