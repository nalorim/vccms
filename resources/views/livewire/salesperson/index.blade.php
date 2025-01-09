<div>

    <h1 class="title">Salesperson</h1>

    <div class="flex items-center justify-between mb-4">
        <div>
            <input type="text" wire:model.live="search" placeholder="Search..."
            class="form-input w-[200px] bg-transparent rounded-lg">
        </div>
        <div class="flex items-center space-x-2 justify-end">
            <button wire:click="showCreateModal()" class="btn border">Create</button>
        </div>
    </div>

    <table class="table w-full">
        <thead>
            <tr>
                <th class="w-[20%]">Name</th>
                <th>Market</th>
                <th>Customers</th>
                <th>Orders</th>
                <th>Total Sold</th>
                <th class="w-[12%]"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($salesperson as $sales)
                <tr>
                    <td>{{ $sales->profile->name }} </td>
                    <td>{{ $sales->market ? $sales->market->name : 'General' }} </td>
                    <td>{{ $sales->customers()->count() }}</td>
                    <td>{{ $sales->orders() }}</td>
                    <td>{{ $sales->solds() }} CTN</td>
                    <th>
                        <a href="#" wire:navigation class="btn text-sm px-2 py-1 bg-slate-900 rounded">View</a>
                        <a href="#" wire:navigation class="btn text-sm px-2 py-1 bg-slate-900 rounded">Edit</a>
                    </th>
                </tr>
            @empty
            <tr>
                <td colspan="6">No Data Yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if ($createModal)
        <x-salesperson.create :$form :$users :$markets />
    @endif
</div>
