<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="title mb-0">Orders</h1>
        <a href="{{ route('orders.create') }}" class="btn border">Create</a>
    </div>
    
    <div class="bg-slate-700 p-4 rounded-lg mb-8">
        <div class="grid grid-cols-5 gap-4">
            <div>
                <div class="label"># Orders</div>
                # {{ sprintf("%03d", $orders->total()) }}
            </div>
            <div>
                <div class="label">Total Credit</div>
                $ {{ number_format($orders->sum('amount'), 2) }}
            </div>
            <div>
                <div class="label">Pending</div>
                # {{ $orders->where('status', 'pending')->count() }}
            </div>
            <div>
                <div class="label">Cancel</div>
                # {{ $orders->where('status', 'cancel')->count() }}
            </div>
            <div>
                <div class="label">Completed</div>
                # {{ $orders->where('status', 'completed')->count() }}
            </div>
        </div>
    </div>

    <div x-data="{ setting: false, advance: false }" class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-4">
            <div class="relative flex items-center">
                <input type="text" wire:model="search" wire:keydown.enter="$refresh" placeholder="Search..."
                class="form-input w-[200px] bg-transparent rounded-lg">
                <button wire:click="$refresh" class="i-search absolute right-2 btn border p-1 text-xs hover:bg-slate-900"></button>
            </div>
            <div x-cloak x-show="!advance" class="relative">
                <select wire:model="order_date" wire:change="dateOptions()" class="form-input w-[160px] bg-transparent rounded-lg">
                    <option value="all">All</option>
                    <option value="tomorrow">Tomorrow</option>
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                </select>
            </div>
            <div x-cloak x-show="advance" class="relative">
                <input type="date" wire:model="order_from" wire:change="filterDate()"
                class="form-input w-[160px] bg-transparent rounded-lg">
                <span class="absolute left-0 -top-5 text-sm text-gray-500 ">From</span>
            </div>
            <div x-cloak x-show="advance" class="relative">
                <input type="date" wire:model="order_to" wire:change="filterDate()"
                class="form-input w-[160px] bg-transparent rounded-lg">
                <span class="absolute left-0 -top-5 text-sm text-gray-500 ">To</span>
            </div>
            <button x-cloak x-show="advance" wire:click="clearFilter" class="btn border i-history"></button>
        </div>
        <div class="flex items-center space-x-2 justify-end">
            
            <div>
                <select wire:model.live="perPage" class="form-input w-[80px] bg-transparent rounded-lg">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div  class="relative">
                <button @click="setting = !setting" class="i-cog border btn hover:bg-slate-900"></button>
                <div x-cloak x-show="setting" @click.outside="setting = false" class="absolute top-[110%] right-0 z-50">
                    <div class="grid grid-cols-1 gap-1 w-[100px]">
                        <button @click="advance = false" class="btn p-2 bg-slate-500 hover:bg-slate-900">Simple</button>
                        <button @click="advance = true" class="btn p-2 bg-slate-500 hover:bg-slate-900">Advance</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table class="table w-full">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Location</th>
                <th>Salesman</th>
                <th class="w-[20%]">Items</th>
                <th>Credit</th>
                <th>Status</th>
                <th class="w-[3%]"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>
                        {{ $order->order_id }}
                        <div class="label text-sm">{{ date('d/m/Y', strtotime($order->order_date)) }}</div>
                    </td>
                    <td>
                        {{ $order->customer->name }} 
                        <div class="label text-sm">{{ $order->customer->phone }}</div>
                    </td>
                    <td>
                        {{ $order->customer->location }} 
                        <div class="label text-sm">{{ $order->market->name }}</div>
                    </td>
                    <td class="capitalize">
                        {{ $order->salesperson->name }}
                        <div class="label text-sm line-clamp-1">{{ $order->remark ? $order->remark : '...' }}</div>
                    </td>
                    <td class="flex items-start gap-1">
                        @foreach ($order->contain as $contain)
                            <span class="text-xs px-2 py-1 bg-slate-900 rounded">{{ $contain }}</span>
                        @endforeach
                    </td>
                    <td>
                        <span class="bg-green-500 rounded p-1">$ {{ number_format($order->amount,2) }} </span> 
                        <div class="label text-sm mt-1">≤ {{ number_format($order->qty) }} Ctns</div>
                    </td>
                    <td>
                        <span class="{{ $order->status }} cursor-pointer rounded p-1 capitalize">{{ $order->status }}</span>
                        <div class="label text-sm mt-1">By admin</div>
                    </td>
                    <td x-data="{ options: false }" class="relative">
                        <button @click="options = !options" class="i-ellipsis-vert btn hover:bg-slate-600"></button>
                        <div x-cloak x-show="options" @click.outside="options = false" class="absolute top-[100%] right-0 z-50">
                            <div class="grid grid-cols-1 gap-1 w-[100px]">
                                <button class="btn bg-slate-500 hover:bg-slate-900">Edit</button>
                                <button class="btn bg-slate-500 hover:bg-slate-900">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty  
            <tr>
                <td colspan="8">No Order Yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
