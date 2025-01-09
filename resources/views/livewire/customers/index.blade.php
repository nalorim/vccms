<div>
    <h1 class="title">Customers</h1>

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
                <th>Registered</th>
                <th class="w-[20%]">Name</th>
                <th>Location</th>
                <th>Salesperson</th>
                <th>Contact</th>
                <th class="w-[12%]"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $cust)
            <tr>
                <td>{{ date('d-m-Y', strtotime($cust->created_at) ) }}</td>
                <td>{{ $cust->name }} </td>
                <td>{{ $cust->location }} </td>
                <td>{{ $cust->salesperson ? $cust->salesperson->name : '...' }} </td>
                <td>{{ $cust->contact ? $cust->contact : '...' }} </td>
                <th>
                    <a href="#" wire:navigation class="btn text-sm px-2 py-1 bg-slate-900 rounded">View</a>
                    <a href="#" wire:navigation class="btn text-sm px-2 py-1 bg-slate-900 rounded">Edit</a>
                </th>
            </tr>
            @empty 
            <tr>
                <td colspan="5">No Customer Yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($createModal)
        <x-customers.create :$customer :$salespersons :$markets :$dropdown :$cities />
    @endif

</div>
