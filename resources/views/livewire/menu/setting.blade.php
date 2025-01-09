<div>
    
    <h1 class="title">Navigation Menu </h1>

    <div class="mb-4">
        <span class="i-info text-yellow-500">This area is for IT department only. Do not touch! I'll Bite you</span>
    </div>

    <div class="flex items-center justify-between mb-4">

        <div class="flex items-center gap-4">
            <div class="flex items-center space-x-2">
                <div class="relative flex items-center">
                    <input type="text" wire:model="search" wire:keydown.enter="$refresh"
                    class="form-input bg-transparent w-[200px] rounded-lg" placeholder="Search...">
                    @if ($search)
                    <span wire:click="$set('search', null)" class="i-cancel absolute right-2"></span>
                    @endif
                </div>
                <button wire:click="$refresh" class="btn border i-search"></button>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <select wire:model.live="perPage" class="form-input bg-transparent w-[80px] rounded-lg">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <button wire:click="openModal()" class="btn border">Create</button>
        </div>
    </div>

    <table class="table w-full">
        <thead>
            <tr>
                <th>#</th>
                <th>Icon</th>
                <th>Order</th>
                <th>Name</th>
                <th>Route</th>
                <th>View</th>
                <th>Param</th>
                <th>Parent</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($routes as $i => $link)
                <tr>
                    <td>{{ $loop->iteration + $routes->firstItem() - 1 }} </td>
                    <td x-data="{ dropdown: false }" class="relative">
                        <span @click="dropdown = !dropdown" class="btn p-2 border inline-block w-[80px] {{ $link->icon }}"></span>
                        <div x-cloak x-show="dropdown" @click.outside="dropdown = false" class="dropdown">
                            <input type="text" wire:model.live="searchIcon" placeholder="Icon..."
                            class="form-input bg-transparent w-full p-2 rounded">
                            @foreach ($icons as $icon)
                            <button @click="dropdown=false" wire:click="updateIcon({{ $link->id }}, '{{ $icon['ico'] }}')" class="btn text-nowrap w-full inline-block text-left">
                                <span class="{{ $icon['ico'] }} text-yellow-500"></span> {{ $icon['name'] }}
                            </button>
                            @endforeach
                        </div>
                    </td>
                    <td> 
                        <div class="flex items-center gap-2">
                            <button wire:click="upOrder({{ $link->id }})" class="i-up"></button>
                            <span>{{ $link->order }}</span>
                            <button wire:click="downOrder({{ $link->id }})" class="i-down"></button>
                        </div>
                    </td>
                    <td wire:click="openEditModal({{ $link->id }})">
                        <span>{{ $link->label }}</span>
                    </td>
                    <td wire:click="openEditModal({{ $link->id }})">
                        <span>{{ $link->route }}</span>
                    </td>
                    <td wire:click="openEditModal({{ $link->id }})">
                        <span>{{ $link->view }}</span>
                    </td>
                    <td>
                        <span>{{ $link->param ?? '-' }}</span>
                    </td>
                    <td x-data="{ dropdown: false }" class="relative">
                        <span @click="dropdown = !dropdown" class="btn p-2 border inline-block w-full">{{ isset($link->parent) ?  $link->parent->label : '...' }}</span>
                        <div x-cloak x-show="dropdown" @click.outside="dropdown = false" class="dropdown">
                            @foreach ($links as $l)
                                <button wire:click="updateParent({{ $link->id }}, {{ $l['id'] }})" 
                                    @click="dropdown=false"
                                    class="btn w-full">{{ $l['label'] }}</button>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @empty
                
            @endforelse
        </tbody>
    </table>

    <div class="mt-8">
        <div>{{ $routes->links() }}</div>
    </div>

    @if($modal)
        <x-routes.form :$form :$icons :$links :$searchIcon />
    @endif

    @if($editModal)
        <x-routes.form :$editModal :$form :$icons :$links :$searchIcon />
    @endif

</div>
