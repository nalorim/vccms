@props([
    'route' => null,
    'action' => null
])

<a href="{{ $route }}" wire:click="{{ $action }}" class="btn px-4 w-full bg-blue-500 hover:bg-blue-600">
    {{ $slot }}
</a>