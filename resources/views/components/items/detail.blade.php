@props([
    'selectedBrandName' => null,
    'items' => null,
    'edit' => null,
    'brand' => null,
    'selectedBrand' => null,
])

<div>

    @if (session()->has('message'))
        <div class="bg-green-500 p-4 rounded-lg mb-2 flex items-center justify-between">
            {{ session('message') }} 
            <div>
                <a href="{{ route('items.show', $item['id'] ) }}" class="bg-green-700 p-2 rounded ml-8 text-xs">See Update</a>
                <button wire:click="clearMessage()" class="btn border py-1 text-xs">x</button>
            </div>
        </div>
    @endif

    @if (session()->has('fail'))
        <div class="bg-yellow-600 p-4 rounded-lg mb-2">
            {{ session('fail') }} 
        </div>
    @endif

    <div class=" grid grid-cols-1 gap-4">
        @if($selectedBrand == '')
            <h1 class="text-gray-500">Please Create or Select the brand on the leftside.</h1>
        @else
            @if ($edit)
            <div class="grid grid-cols-4 gap-4 p-4 rounded-lg bg-slate-600">
                <div class="col-span-4 grid grid-cols-2 gap-4">
                    <div>
                        <div class="label">Brand</div>
                        <input type="text" wire:model="selectedBrandName" class="form-input w-full rounded-lg bg-transparent">
                    </div>
                    <div>
                        <div class="label">Image</div>
                        <x-input.file ></x-input.file>
                    </div>
                </div>
                <div>
                    <div class="label">Factor</div>
                    <input type="text" wire:model="brand.factor" class="form-input w-full rounded-lg bg-transparent text-sm">
                </div>
                <div>
                    <div class="label">UM</div>
                    <select wire:model="brand.um" class="form-input w-full rounded-lg bg-transparent text-sm">
                        <option value="unit">Unit</option>
                        <option value="box">Box</option>
                        <option value="ctn">CTN</option>
                    </select>
                </div>
                <div>
                    <div class="label">CTN Price</div>
                    <input type="text" wire:model="brand.ctn_price" class="form-input w-full rounded-lg bg-transparent text-sm">
                </div>
                <div>
                    <div class="label">Base Price</div>
                    <input type="text" wire:model="brand.base_price" class="form-input w-full rounded-lg bg-transparent text-sm">
                </div>
            </div>
            @endif
            <h1>{{ $brand['variant'] }} Variants</h1>
            <table class="table w-full table-input">
                <thead>
                    <tr>
                        <td></td>
                        <th class="w-[30%]">Variant</th>
                        <th>Unit Barcode</th>
                        <th>CTN Barcode</th>
                        <th>Remark</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $i => $item)
                        <tr class="text-xs">
                            <td>{{ $i+1 }} </td>
                            <td class="relative flex items-center">
                                @if($items[$i]['id'])
                                    <span class="absolute right-3 text-xs text-green-500">{{ $items[$i]['stock'] }} in stock</span>
                                @endif
                                <input type="text" wire:model="items.{{ $i }}.name" class="form-input w-full bg-transparent rounded-lg">
                                @error('items.'. $i .'.name')
                                <div class="text-xs text-red-500">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="text" wire:model="items.{{ $i }}.sku" class="form-input w-full bg-transparent rounded-lg">
                            </td>
                            <td>
                                <input type="text" wire:model="items.{{ $i }}.barcode" class="form-input w-full bg-transparent rounded-lg">
                            </td>
                            <td>
                                <input type="text" wire:model="items.{{ $i }}.remark" class="form-input w-full bg-transparent rounded-lg">
                            </td>
                            <td>
                                @if($items[$i]['stock'] == 0)
                                <button wire:click="removeVariant({{ $i }})" class="btn border px-2">x</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="nobackground">
                        <td colspan="6" class="p-4 bg-transparent text-xs ">
                            <button wire:click="addMoreVariant()" class="bg-yellow-600 p-2 rounded-lg">+ Add More Item</button>
                            @if(count($items) < 5)
                            <button wire:click="addFiveVariants()" class="bg-blue-600 p-2 rounded-lg">Has 5 flavors</button>
                            @endif
                            <button wire:click="clearList()" class="bg-slate-600 p-2 rounded-lg">Clear List</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
    @if($selectedBrandName)
        @if($edit)
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('items.index') }}" class="btn w-full mt-4 bg-gray-700">Cancel</a>
            <button wire:click="createItem({{ $item['id'] }})" class="btn w-full mt-4 bg-green-700">Update</button>
        </div>
        @else
        <div class="grid grid-cols-2 gap-4">
            <button wire:click="cancel()" class="btn w-full mt-4 bg-slate-700">Cancel</button>
            <button wire:click="createItem({{ $selectedBrand }})" class="btn w-full mt-4 bg-blue-700">Create or Update</button>
        </div>
        @endif
    @endif
</div>