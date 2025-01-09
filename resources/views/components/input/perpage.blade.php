@props([
    'perPage' => null
])
<div>
    <select wire:model.live="perPage" class="form-input w-[80px] bg-transparent rounded-lg">
        <option value="1">1</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>
</div>