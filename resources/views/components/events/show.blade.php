@props([
    'forms' => null,
    'onEdit' => null
])

<div wire:transition class="absolute inset-0 flex items-center justify-center showEvent">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative w-[600px] bg-slate-800 p-4 rounded-lg">
        <h1 class="text-center capitalize">{{ $forms['name'] }} </h1>
        <div class="p-4 space-y-2">
            <div class="bg-slate-900 rounded-lg p-4">
                <div class="text-gray-500 text-sm mb-2">Description</div>
                <div>
                    {{ $forms['description'] ? $forms['description'] : 'No Description' }} 
                </div>
            </div>
            <div class="bg-slate-900 rounded-lg p-4 grid grid-cols-4 gap-2">
                <div>
                    <div class="text-gray-500 text-sm mb-2">Priority</div>
                    <span class="capitalize {{ $forms['priority'] }} ">{{ $forms['priority'] }} </span> 
                </div>
                <div>
                    <div class="text-gray-500 text-sm mb-2">Date</div>
                    <span class="capitalize">{{ $forms['date'] }} </span> 
                </div>
                <div>
                    <div class="text-gray-500 text-sm mb-2">Assigned To</div>
                    <span class="capitalize">{{ $forms['assigned_to'] }} </span> 
                </div>
                <div>
                    <div class="text-gray-500 text-sm mb-2">Status</div>
                    <button wire:click="toggleComplete({{ $forms['id'] }} )" class="capitalize hover:underline">
                        <span class="{{ $forms['status'] }}">{{ $forms['status'] }}</span>
                    </button> 
                </div>
                
            </div>
            <div class="grid grid-cols-2">
                <div class="">
                    <button wire:click="showEdit({{ $forms['id'] }} )" class="hover:underline">Edit</button>
                </div>
                <div class="text-right">
                    <button wire:click="delete({{ $forms['id'] }})" class="hover:underline text-red-500">Delete</button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4">
            <div></div>
            <div wire:click="closeModal()" class="btn btn-cancel">Close</div>
        </div>
    </div>
</div>