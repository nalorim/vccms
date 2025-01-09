<div>

    <div class="flex items-center justify-between mb-4">
        <div>Brand</div>
        <button wire:click="showCreateBrand()" class=" btn p-1 px-2 text-xs bg-blue-500 hover:bg-blue-600">
            <i class="i-plus"></i> New
        </button>
    </div>
    <x-input.searchform :search="$search" />
    <div class="mt-2 space-y-2 scrollbar-none">
        @forelse ($brands as $brand)
            <div wire:click="selectBrand({{ $brand->id }})" class="brand-option hover:bg-blue-900 {{ $selectedBrand == $brand->id ? 'bg-blue-900' : '' }}">
                <div>
                    <img class="object-cover w-[50px] aspect-square rounded-lg bg-slate-800" 
                    src="{{ $brand->image ? url($brand->image) : url('/img/vc_logo_light.png') }}" alt="Brand">
                </div>
                <div class="flex-grow flex items-center justify-between">
                    <div>
                        <div>{{ $brand->name }}</div>
                        <div class="label">
                            {{ $brand->items->count() }} items .
                            <span>{{ $brand->factor }} {{ $brand->um }} {{ $brand->um == "ctn" ? '' : ' / ctn' }} </span>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div>No Brand Yet</div>
        @endforelse
        @if ($perPage < $brands->total())
        <div wire:click="viewMore()" class="btn bg-slate-600 hover:bg-slate-700">View More</div>
        @endif
        
    </div>

    @if ($createBrandModal)
        <x-items.create :$forms />
    @endif

</div>
