@props([
    'search' => null
])
<div class="w-full relative flex items-center">
    <input type="text" wire:model="search" wire:keydown.enter="$refresh" placeholder="Search..."
    class="form-input w-full min-w-[200px] bg-transparent rounded-lg">
    @if ($search)
    <span wire:click="$set('search', null)" class="absolute right-11 i-cancel btn p-0 bg-slate-600 text-xs"></span>
    @endif
    <button wire:click="$refresh" class="i-search absolute right-2 btn border p-1 text-xs hover:bg-slate-900"></button>
</div>