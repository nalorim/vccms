@props([
    'forms' => null,
    'onEdit' => null
])

<div wire:transition class="absolute inset-0 flex items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative w-[600px] bg-slate-800 p-4 rounded-lg">
        <h1 class="text-center">Create New Event</h1>
        <div class="p-4 space-y-2">
            <div>
                <div>Event Name</div>
                <input type="text" wire:model="forms.name" class="form-input w-full rounded bg-transparent" required>
            </div>
            <div>
                <div>Description (Optional)</div>
                <textarea id="mytextarea" type="text" wire:model="forms.description" class="form-input w-full rounded bg-transparent h-[80px]"></textarea>
            </div>
            <div>
                <div>Priority</div>
                <select wire:model="forms.priority" class="form-input w-full rounded bg-transparent">
                    <option value="normal">Normal</option>
                    <option value="notice">Notice</option>
                    <option value="important">Important</option>
                </select>
            </div>
            <div>
                <div>Event Date</div>
                <input type="date" wire:model="forms.date" class="form-input w-full rounded bg-transparent">
            </div>
            
        </div>
        <div class="grid grid-cols-2 gap-4 mt-2">
            <div wire:click="closeModal()" class="btn btn-cancel">Cancel</div>
            @if(!$onEdit)
                <div wire:click="create()" class="btn btn-create">Create</div>
            @else
                <div wire:click="update({{ $forms['id'] }})" class="btn bg-green-500 hover:bg-green-600">Update</div>
            @endif
        </div>
    </div>
</div>