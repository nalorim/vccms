<div class="grid grid-cols-1 gap-4">
                
    @foreach ($routes as $link)

        @if ($link->route == request()->segment(1))
            <a wire:navigation href="{{ route($link->route.'.'.$link->view) }}" class="{{ request()->path() == $link->route ? 'active' : '' }}">
                <span class="i-th-large"></span> Report
            </a>
            @foreach ($link->children->sortBy('order') as $child)
                <a wire:navigation href="{{ route($child->route.'.'.$child->view) }}" class="{{ request()->segment(2) == $child->route ? 'active' : '' }}">
                    <span class="{{ $child->icon }}"></span> {{ $child->label }}
                </a>
            @endforeach
        @endif
        
    @endforeach
    
</div>
