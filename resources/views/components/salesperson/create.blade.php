@props([
    'form' => null,
    'markets' => null,
    'users' => null
])

<div wire:transition class="absolute inset-0 flex items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative w-[600px] bg-slate-800 p-4 rounded-lg">
        <h1 class="text-center">Create Salesperson</h1>
        <div class="p-4 space-y-2">
        
            <div class="">
                <div class="label">Name</div>
                <select wire:model="form.user_id" class="form-input w-full rounded bg-transparent">
                    <option value="">Choose User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <div class="label">Market</div>
                <select wire:model="form.market_id" class="form-input w-full rounded bg-transparent">
                    <option value="">Choose User</option>
                    @foreach($markets as $market)
                    <option value="{{ $market->id }}">{{ $market->name }} </option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="grid grid-cols-2 gap-4 mt-2">
            <div wire:click="closeModal()" class="btn btn-cancel">Cancel</div>
            <div wire:click="createSales()" class="btn btn-create">Create</div>
        </div>
    </div>
</div>