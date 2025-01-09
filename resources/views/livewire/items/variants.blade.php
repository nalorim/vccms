<div class="grid grid-cols-4 gap-8 items-start">

    <span class="hidden bg-brown-500">brown</span>
    <span class="hidden bg-green-500">green</span>
    <span class="hidden bg-yellow-500">yellow</span>
    <span class="hidden bg-pink-500">pink</span>
    <span class="hidden bg-purple-500">purple</span>
    <span class="hidden bg-emerald-500">Emerald</span>
    
    @if ($edit)
    <div>
        <div class="flex justify-center mb-4">
            <img class="object-cover rounded-full w-2/3 bg-slate-700 aspect-square" 
            src="{{ !empty($brand['image']) ? ($image ? $image->temporaryUrl() : url($brand['image']) ) : url('img/vc_logo_light.png')  }}" alt="">
        </div>
        <div class="mb-4 grid grid-cols-2 gap-2">
            <div class="label text-base">Status</div>
            <div>
                {{ $brand['status'] ? 'Active' : 'Disabled' }}
            </div>
            <div class="label text-base">History</div>
            <div>{{ number_format($brand['history']) }} logs</div>
            <div class="label text-base">Created On</div>
            <div>{{ date('d-m-Y', strtotime($brand['created_at'])) }}</div>
        </div>
        <div class="grid grid-cols-1 gap-2 p-4 rounded-lg bg-slate-700">
            <div class="mb-0 pb-0">
                <div class="label">Brand Name</div>
                <input type="text" wire:model="selectedBrandName" 
                wire:change="$set('modify', true)"
                class="form-input w-full rounded-lg bg-transparent">
            </div>
            <div>
                <div class="label">Image</div>
                <x-input.file>image</x-input.file>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <div class="label">Base UM</div>
                    <select wire:model.live="brand.um" 
                    wire:change="umCTN()"
                    class="form-input w-full rounded-lg bg-transparent text-sm">
                        <option value="unit">Unit</option>
                        <option value="box">Box</option>
                        <option value="ctn">CTN</option>
                    </select>
                </div>
                <div>
                    <div class="label">Factor</div>
                    <input type="number" min="1" wire:model="brand.factor" 
                    wire:change="updateInStockByUM()"
                    {{ $brand['um'] == 'ctn' ? 'disabled' : '' }} 
                    class="{{ $brand['um'] == 'ctn' ? 'disabled:bg-gray-500' : '' }} form-input w-full rounded-lg bg-transparent text-sm">
                </div>
            </div>
            <div>
                <div class="label">Base Price</div>
                <input type="text" wire:model="brand.base_price" 
                wire:change="$set('modify', true)"
                class="form-input w-full rounded-lg bg-transparent text-sm">
            </div>
            <div>
                <div class="label">Remark</div>
                <input type="text" wire:model="brand.remark" 
                wire:change="$set('modify', true)"
                class="form-input w-full rounded-lg bg-transparent text-sm">
            </div>
        </div>
        
    </div>
    @endif

    <div class="{{ $edit ? 'col-span-3' : 'col-span-4' }} grid grid-cols-1 gap-4">
        @if($selectedBrand == '')
            <h1 class="text-gray-500">Please Create or Select the brand on the leftside.</h1>
        @else
            <div class="flex items-center justify-between">
                <span>
                    
                    <a href="{{ route('items.index') }}" class="btn">Index</a>
                    <span class="i-angle-right"></span>
                    @if ($edit)
                        <a href="{{ route('items.create') }}" class="btn">New</a>
                    @else
                        <a href="{{ route('items.edit', [ 'id' => $brand['id'] ]) }}" class="btn">Edit</a>
                    @endif
                    <span class="i-angle-right"></span>
                    <span wire:click="selectBrand({{ $brand['id'] }}, 1)" class="btn">
                        <i class="i-arrows-cw"></i> Refresh
                    </span>
                </span>
                <div class="flex items-center gap-4">
                    @if ($edit)
                        <button wire:click="createItem({{ $brand['id'] }})" 
                        {{ $modify ? '' : 'disabled' }} 
                        class="btn {{ $modify ? 'bg-orange-500 hover:bg-orange-600' : 'disabled:bg-gray-500' }}">
                            <span class="i-plus-circled"></span> Update
                        </button>
                    @else
                        <button wire:click="createItem({{ $selectedBrand }})" 
                        {{ $modify ? '' : 'disabled' }} 
                        class="btn px-4 {{ $modify ? 'bg-blue-500 hover:bg-blue-600' : 'disabled:bg-gray-500' }}">
                            <span class="i-check"></span> Save
                        </button>
                    @endif
                    @if ($brand['history'] == 0)
                        <button wire:click="deleteBrand({{ $brand['id'] }})" class="btn bg-red-500 hover:bg-red-600">
                            <span class="i-trash"></span> Delete
                        </button>
                    @endif
                </div>
            </div>
            @if (session()->has('message'))
            <div class="bg-green-500 px-4 py-2 rounded-lg my-1 flex items-center justify-between">
                <div>
                    <span class="i-thumbs-up"></span> {{ session('message') }}
                </div>
                <button wire:click="clearSessionMsg()" class="btn i-cancel p-1"></button>
            </div>
            @endif
            <table class="table w-full">
                <thead>
                    <tr class="text-base">
                        <td></td>
                        <th class="w-[30%]">Variant</th>
                        <th class="w-[15%]">In-Stock</th>
                        <th>Unit Barcode</th>
                        <th>CTN Barcode</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $i => $item)
                        <tr class="text-xs even:bg-slate-700 odd:bg-slate-600">
                            <td class="text-nowrap">
                                {{ $i+1 }} 
                            </td>
                            <td class="relative flex items-center">
                                <input type="text" wire:model="items.{{ $i }}.name" wire:change="$set('modify', true)" class="form-input w-full bg-transparent rounded-lg">
                                @error('items.'. $i .'.name')
                                    <div class="text-xs text-red-500 absolute top-0 right-4">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <div x-data="{ dropdown: false }" class="relative">
                                    <div @click="dropdown = !dropdown" class="hover:border rounded-lg p-1 bg-{{ $items[$i]['color'] }}-500">
                                        {{ number_format($items[$i]['new_stock']) }} {{ $brand['um'] }} 
                                    </div>
                                    <div x-cloak x-show="dropdown" @click.outside="dropdown=false" class="dropdown space-y-2">
                                        @foreach ($colors as $color)
                                        <div wire:click="pickColor('{{ $color }}', {{ $i }})" @click="dropdown = false" class="flex items-center gap-2 btn bg-slate-600 rounded-lg px-3 py-2 hover:bg-slate-700">
                                            <div class="w-[20px] aspect-square border rounded-full bg-{{ $color }}-500 mr-2"></div>
                                            <div>{{ $color }}</div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="text" wire:model="items.{{ $i }}.sku" wire:change="$set('modify', true)"
                                placeholder="0000 0000 0000"
                                class="form-input w-full bg-transparent rounded-lg">
                            </td>
                            <td>
                                <input type="text" wire:model="items.{{ $i }}.barcode" wire:change="$set('modify', true)"
                                placeholder="0000 0000 0000"
                                class="form-input w-full bg-transparent rounded-lg">
                            </td>
                            <td>
                                @if($items[$i]['stock'] == 0)
                                <button wire:click="removeVariant({{ $i }})" class="btn p-1 border i-trash "></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="nobackground">
                        <td colspan="6" class="p-4 bg-transparent text-xs ">
                            <button wire:click="addMoreVariant()" class="bg-cyan-600 p-2 rounded-lg">+ Add More Item</button>
                            @if(count($items) < 5)
                            <button wire:click="addFiveVariants()" class="bg-blue-600 p-2 rounded-lg">Has 5 flavors</button>
                            @endif
                            {{-- <button wire:click="clearList()" class="bg-slate-600 p-2 rounded-lg">Clear List</button> --}}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>

</div>
