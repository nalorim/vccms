@props([
    'customer' => null,
    'salespersons' => null,
    'markets' => null,
    'dropdown' => null,
    'cities' => null
])

<div wire:transition class="absolute inset-0 flex items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative w-[600px] bg-slate-800 p-4 rounded-lg">
        <h1 class="text-center">Create Customer</h1>
        
        <div class="p-4 space-y-2">
            <div>
                <div>Name *</div>
                <input type="text" wire:model="customer.name" placeholder="New Customer..." 
                class="form-input w-full rounded bg-transparent" required>
                @error('customer.name')
                    <div class="my-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div>Phone *</div>
                    <input type="text" wire:model="customer.phone" placeholder="..." 
                    class="form-input w-full rounded bg-transparent" required>
                    @error('customer.phone')
                        <div class="my-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <div>Location *</div>
                    <select wire:model="customer.location" class="form-input w-full rounded bg-transparent">
                        @foreach ($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                    @error('customer.location')
                        <div class="my-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div>Salesperson *</div>
                    <div class="relative flex items-center">
                        <input type="text" wire:model="customer.salesperson_name" placeholder="..." wire:click="$set('dropdown', 'salesperson')"
                        class="form-input w-full rounded bg-transparent" required>
                        
                        @if($customer['salesperson_id'])
                        <button wire:click="clearSelectSalesperson()"
                        class="absolute right-2 text-xs w-[20px] aspect-square rounded-full bg-gray-500">x</button>
                        @endif
                        
                        @if($dropdown == 'salesperson')
                            <div wire:click.outside="hideDropdown()" class="absolute top-[100%] -left-2 inset-x-0 w-[240px] p-2 max-h-[300px] overflow-scroll z-50">
                                <div class="bg-slate-700 p-2 py-4 shadow-lg">
                                    <div class="mt-2 grid grid-cols-1 gap-2">
                                        @forelse ($salespersons as $sale)
                                            <div wire:click="selectSalesperson({{ $sale->id }})" class="hover:bg-slate-900 transition rounded-lg px-1 cursor-pointer">
                                                <div class="btn border rounded-lg text-left">
                                                    {{ $sale->profile->name }}
                                                </div>
                                            </div>
                                        @empty
                                            <div>Not Found</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
                <div>
                    <div>Market</div>
                    <div class="relative flex items-center">
                        <input type="text" wire:model="customer.market_name" wire:click="$set('dropdown', 'market')" 
                        class="form-input w-full rounded-lg bg-transparent" placeholder="...">
                        
                        @if($customer['market_id'])
                        <button wire:click="clearSelectMarket()"
                        class="absolute right-2 text-xs w-[20px] aspect-square rounded-full bg-gray-500">x</button>
                        @endif
    
                        @if($dropdown == 'market')
                            <div wire:click.outside="hideDropdown()" class="absolute top-[100%] -left-2 inset-x-0 w-[240px] p-2 max-h-[300px] overflow-scroll z-50">
                                <div class="bg-slate-700 p-2 py-4 shadow-lg">
                                    <div class="mt-2 grid grid-cols-1 gap-2">
                                        @forelse ($markets as $market)
                                            <div wire:click="selectMarket({{ $market->id }})" class="hover:bg-slate-900 transition rounded-lg px-1 cursor-pointer">
                                                <div class="btn border rounded-lg text-left">
                                                    {{ $market->name }}
                                                </div>
                                            </div>
                                        @empty
                                            <div>Not Found</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <div>VAT</div>
                    <input type="text" wire:model="customer.vat" placeholder="..." 
                    class="form-input w-full rounded bg-transparent" required>
                    @error('customer.vat')
                        <div class="my-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <div>Discount</div>
                    <input type="text" wire:model="customer.discount" placeholder="..." 
                    class="form-input w-full rounded bg-transparent" required>
                    @error('customer.discount')
                        <div class="my-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <div>Terms</div>
                    <select wire:model="customer.terms" class="form-input w-full rounded bg-transparent">
                        <option value="1">COD</option>
                        <option value="7">7 days</option>
                        <option value="15">15 days</option>
                        <option value="30">30 days</option>
                        <option value="60">60 days</option>
                    </select>
                    @error('customer.terms')
                        <div class="my-1 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div>
                <div>Remark</div>
                <input type="text" wire:model="customer.remark" placeholder="..." 
                class="form-input w-full rounded bg-transparent" required>
                @error('customer.remark')
                    <div class="my-1 text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-2">
            <div wire:click="closeModal()" class="btn btn-cancel">Cancel</div>
            <div wire:click="createCustomer()" class="btn btn-create">Create</div>
        </div>
    </div>
</div>