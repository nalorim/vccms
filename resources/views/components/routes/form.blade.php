@props([
    'form' => null,
    'links' => [],
    'icons' => [],
    'searchIcon' => null,
    'editModal' => null
])

<div wire:transition class="fixed inset-0 flex items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div wire:click.outside="closeModal()" class="relative w-[600px] bg-slate-800 p-4 rounded-lg">
        <h1 class="text-center">Create Route</h1>

        <x-flash.message ></x-flash>
        
        <div class="p-4 space-y-2">
            <div class="grid grid-cols-3 gap-2">
                <div class="col-span-2">
                    <div>Label *</div>
                    <input type="text" wire:model="form.label" placeholder="Label..." 
                    class="form-input w-full rounded-lg bg-transparent" required>
                    @error('form.label')
                        <div class="my-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div x-data="{ dropdown: false }" class="relative">
                    <div>Icon *</div>
                    <div @click="dropdown=!dropdown" class="form-input w-full rounded-lg bg-transparent">
                        <span class="{{ $form['icon'] }}"></span> {{ $form['icon'] }}
                    </div>
                    <div x-cloak x-show="dropdown" @click.outside="dropdown=false" class="dropdown">
                        <input type="text" wire:model.live="searchIcon" placeholder="Icon..."
                            class="form-input bg-transparent w-full p-2 rounded">
                        @foreach ($icons as $icon)
                            <button wire:click="pickIcon('{{ $icon['ico'] }}')" @click="dropdown=false" class="btn rounded-lg w-full text-left">
                                <span class="{{ $icon['ico'] }}"></span> {{ $icon['name'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <div>Route *</div>
                    <input type="text" wire:model="form.route" placeholder="Route..." 
                    class="form-input w-full rounded-lg bg-transparent" required>
                    @error('form.route')
                        <div class="my-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <div>Param</div>
                    <input type="text" wire:model="form.param" placeholder="id" 
                    class="form-input w-full rounded-lg bg-transparent">
                </div>
                <div>
                    <div>Parent</div>
                    <select  wire:model="form.parent_id" class="form-input w-full rounded-lg bg-transparent">
                        <option value="">Choose...</option>
                        @foreach ($links as $link)
                            <option value="{{ $link['id'] }}">{{ $link['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <div>View *</div>
                <select  wire:model="form.view" class="form-input w-full rounded-lg bg-transparent">
                    <option value="">Choose...</option>
                    <option value="index">Index</option>
                    <option value="create">Create</option>
                    <option value="show">Show</option>
                    <option value="edit">Edit</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-2">
            <div wire:click="closeModal()" class="btn btn-cancel">Cancel</div>
            @if ($editModal)
                <div wire:click="updateRecord({{ $form['id'] }})" class="btn btn-create">Update</div>
            @else
                <div wire:click="createRecord()" class="btn btn-create">Create</div>
            @endif
        </div>
    </div>
</div>