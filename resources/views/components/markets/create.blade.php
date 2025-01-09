@props([
    'forms' => null,
])

<div wire:transition class="absolute inset-0 flex items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative w-[600px] bg-slate-800 p-4 rounded-lg">
        <h1 class="text-center">Create Market</h1>
        <div class="p-4 space-y-2">
            
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <div class="label">Name</div>
                    <input type="text" wire:model="forms.name" placeholder="Wholesales..." class="form-input w-full rounded bg-transparent" required>
                </div>
                <div>
                    <div class="label">Short</div>
                    <input type="text" wire:model="forms.slug" placeholder="WS" class="form-input w-full rounded bg-transparent" required>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <div class="label">Vat</div>
                    <input type="text" wire:model="forms.vat" class="form-input w-full rounded bg-transparent" required>
                </div>
                <div>
                    <div class="label">Discount</div>
                    <input type="text" wire:model="forms.discount" class="form-input w-full rounded bg-transparent" required>
                </div>
                <div>
                    <div class="label">Terms</div>
                    <select wire:model.live="forms.terms" class="form-input w-full rounded bg-transparent">
                        <option value="1">COD</option>
                        <option value="7">7 days</option>
                        <option value="15">15 days</option>
                        <option value="30">30 days</option>
                        <option value="60">60 days</option>
                    </select>
                </div>
            </div>
            <div>
                <div class="label">Remark</div>
                <input type="text" wire:model="forms.remark" placeholder="write a short note here..." class="form-input w-full rounded bg-transparent" required>
            </div>
            
            
        </div>
        <div class="grid grid-cols-2 gap-4 mt-2">
            <div wire:click="closeModal()" class="btn btn-cancel">Cancel</div>
            <div wire:click="createMarket()" class="btn btn-create">Create</div>
        </div>
    </div>
</div>