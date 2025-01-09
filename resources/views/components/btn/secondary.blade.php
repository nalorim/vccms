@props([
    'route' => null,
    'action' => null
])

<a href="{{ $route }}" wire:click="{{ $action }}" class="btn w-full min-w-[100px] bg-orange-500 hover:bg-orange-600">
    {{ $slot }}
</a>