<div>
    <div class="item-create">
    
        <div>
            <div class="mb-2">Details</div>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <div class="label">Supply Order / PO</div>
                    <input type="text" wire:model="po.name" placeholder="# ..." class="form-input w-full rounded bg-transparent">
                    @error('po.name')
                        <span class="text-sm text-red-500">{{ $message }} </span>
                    @enderror
                </div>
                <div>
                    <div class="label">Received Date</div>
                    <input type="date" wire:model="po.date" class="form-input w-full rounded bg-transparent">
                </div>
                <div>
                    <div class="label">Short Note</div>
                    <textarea wire:model="po.note" placeholder="Write a short note..." class="form-input w-full rounded bg-transparent h-[150px]"></textarea>
                </div>
            </div>
            
        </div>
        <div class="col-span-3">
            
            <div class="flex items-center justify-between mb-4">
                <span>
                    <a href="{{ route('stockins.index') }}" class="btn">Index</a>
                    <span class="i-angle-right"></span>
                    @if ($edit)
                        <a href="{{ route('stockins.create') }}" class="btn">New</a>
                    @endif
                    <span class="i-angle-right"></span>
                    <span wire:click="initPage({{ $po['id'] }})" class="btn">
                        <i class="i-arrows-cw"></i> Refresh
                    </span>
                </span>
                <div class="flex items-center gap-4">
                    @if ($edit)
                        <button wire:click="update({{ $po['id'] }})" class="btn px-4 bg-orange-500 hover:bg-orange-600">
                            <span class="i-save"></span> Update
                        </button>
                        <button wire:click="delete({{ $po['id'] }})" class="btn px-4 bg-red-500 hover:bg-red-600">
                            <span class="i-trash"></span> Delete
                        </button>
                    @else
                        <button wire:click="save()" class="btn px-4 bg-blue-500 hover:bg-blue-600">
                            <span class="i-check"></span> Save
                        </button>
                    @endif
        
                    
                </div>
            </div>

            @if (session()->has('message'))
            <div class="bg-emerald-600 px-4 py-2 rounded-lg mb-2 flex items-center justify-between">
                <div>
                    <span class="i-thumbs-up"></span> {{ session('message') }}
                </div>
                <button wire:click="clearSessionMsg()" class="btn i-cancel p-1"></button>
            </div>
            @endif
            
            <table class="table w-full">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th class="w-[15%]">Cost</th>
                        <th class="w-[15%]">QTY</th>
                        <th class="w-[15%]">UM</th>
                        <th class="w-[20%]">Total Cost</th>
                        <th class="w-[5%]"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $i => $l)
                    <tr class="text-xs even:bg-slate-700 odd:bg-slate-600">
                        <td>{{ $i+1 }} </td>
                        <td x-data="{ dropdown: false }" class="relative">
                            @error('list.'.$i.'.name')
                                <span class="absolute right-3 text-red-500 i-attention-circled"></span>
                            @enderror
        
                            <input type="text" wire:model="list.{{ $i }}.name" wire:change="calculateAmount({{ $i }})" @click="dropdown = true" 
                            placeholder="Choose Item..."
                            class="form-input w-full rounded-lg bg-transparent">
        
                            <div x-cloak x-show="dropdown" @click.outside="dropdown = false" class="dropdown">
                                <input type="text" wire:model.live.debounce.500ms="findItem" placeholder="Search..." class="form-input w-full rounded-lg bg-transparent mb-2">
                                @forelse ($items as $item)
                                    <div wire:click="selectItem({{ $item->id }},{{ $i }})" @click="dropdown = false" class="btn flex items-center justify-between">
                                        <div class="text-left">
                                            <div class="label">{{ $item->brand->name }}</div>
                                            <div>{{ $item->name }}</div>
                                        </div>
                                        <div>
                                            <div>≤ {{ number_format($item->in_stock) }} </div>
                                            <span>Ctn</span>
                                        </div>
                                    </div>
                                @empty
                                    <div>Not Found</div>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <input type="text" wire:model="list.{{ $i }}.cost" wire:change="calculateAmount({{ $i }})" class="form-input w-full rounded-lg bg-transparent">
                        </td>
                        <td class="relative">
                            @error('list.'.$i.'.name')
                                <span class="absolute right-3 text-red-500 i-attention-circled"></span>
                            @enderror
                            <input type="number" min="0" wire:model="list.{{ $i }}.qty" wire:change="calculateAmount({{ $i }})" class="form-input w-full rounded-lg bg-transparent">
                        </td>
                        <td>
                            <select wire:model.live="list.{{ $i }}.um" wire:change="calculateAmount({{ $i }})" class="form-input w-full rounded-lg bg-transparent uppercase">
                                <option value="{{ $list[$i]['um_option'] }}">{{ $list[$i]['um_option'] }}</option>
                                @if ($list[$i]['um_option'] != "ctn")
                                    <option value="ctn">CTN</option>
                                @endif
                            </select>
                        </td>
                        <td>
                            <span class="block form-input w-full border-none py-3 rounded-lg bg-transparent">
                                $ {{ number_format($list[$i]['amount'],2) }}
                            </span>
                        </td>
                        <td>
                            @if(count($list) > 0)
                            <button class="btn border text-sm i-trash p-1" wire:click="removeItem({{ $i }})"></button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        
            <div class="my-2">
                <button wire:click="addMoreItem()" class="bg-cyan-600 p-2 rounded-lg">+ Add More Item</button>
            </div>
        
            <div class="flex items-center justify-between bg-emerald-900 rounded-lg p-4">
                <div>
                    <div class="text-sm">Items</div>
                    <span>{{ count($list) }}</span>
                </div>
                <div>
                    <div class="text-sm">Total QTY</div>
                    <span>{{ number_format($qty) }} CTN</span>
                </div>
                <div>
                    <div class="text-sm">Total Cost</div>
                    <span>USD {{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
    
    </div>
</div>
