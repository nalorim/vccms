<div class="bg-slate-700 mb-8">

    <div class="max-w-screen-xl mx-auto flex items-center justify-between">
        <div class="flex items-center">
            @foreach ($links as $link)
                <a wire:navigation href="{{ route($link->route.'.'.$link->view) }}" class="{{ request()->segment(1) == $link->route ? 'navbar-active' : '' }}
                py-4 px-3 min-w-[150px] text-center hover:bg-slate-800 transition font-bold">
                    <span class="{{ $link->icon }}"></span> {{ $link->label }}
                </a>
            @endforeach
        </div>
        <div class="flex items-center">
            <a wire:navigation href="{{ route($settings->route.'.'.$settings->view) }}" class="{{ request()->segment(1) == $settings->route ? 'navbar-active' : '' }}
                 py-4 px-3 min-w-[150px] text-center hover:bg-slate-800 transition font-bold">
                <span class="{{ $settings->icon }}"></span> {{ $settings->label }}
            </a>
        </div>
    </div>

</div>
