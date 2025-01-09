@props([
    'forms' => null,
])

<div class="absolute inset-0 flex items-center justify-center z-50">
    <div wire:click="closeModal()" class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative w-[600px] bg-slate-800 p-4 rounded-lg">
        <h1 class="text-center">Create New Brand</h1>
        
        <div class="p-4 space-y-2">
            <div class="relative">
                <div>Name *</div>
                <input type="text" wire:model="forms.name" placeholder="New Brand Name..." 
                class="form-input w-full rounded bg-transparent" required>
                @error('forms.name')
                    <div class="absolute top-0 right-0 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <div>Image</div>
                <x-input.file>forms.image</x-input.file>
            </div>
            <div class="grid grid-cols-3 gap-4">
                
                <div>
                    <div>Base UM *</div>
                    <select wire:model="forms.um" 
                    wire:change="umCTN()"
                    class="form-input w-full rounded bg-transparent">
                        <option value="unit">Unit</option>
                        <option value="box">Box</option>
                        <option value="ctn">CTN</option>
                    </select>
                </div>
                <div>
                    <div>Factor *</div>
                    <input type="text" wire:model="forms.factor" 
                    {{ $forms['um'] == 'ctn' ? 'disabled' : '' }}
                    class="{{ $forms['um'] == 'ctn' ? 'disabled:bg-gray-500 cursor-not-allowed' : '' }} form-input w-full rounded bg-transparent">
                    @if ($forms['um'] != 'ctn')
                        <div class="suggestions">
                            <span wire:click="$set('forms.factor', 1)">1</span>
                            <span wire:click="$set('forms.factor', 6)">6</span>
                            <span wire:click="$set('forms.factor', 12)">12</span>
                            <span wire:click="$set('forms.factor', 24)">24</span>
                            <span wire:click="$set('forms.factor', 48)">48</span>
                        </div>
                    @endif
                </div>
                <div>
                    <div>Base Price *</div>
                    <input type="text" wire:model="forms.base_price"
                    class="form-input w-full rounded bg-transparent">
                    @if ($forms['um'] == 'ctn')
                        <div class="suggestions">
                            <span wire:click="$set('forms.base_price', 13)">13.00</span>
                            <span wire:click="$set('forms.base_price', 13.5)">13.50</span>
                            <span wire:click="$set('forms.base_price', 15)">15.00</span>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <div>Remark</div>
                <input type="text" wire:model="forms.remark" placeholder="..." class="form-input w-full rounded bg-transparent">
            </div>
            
            
        </div>
        <div class="grid grid-cols-2 gap-4 mt-2">
            <div wire:click="closeModal()" class="btn btn-cancel">Cancel</div>
            <div wire:click="createBrand()" class="btn btn-create">Create</div>
        </div>
    </div>
</div>