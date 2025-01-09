<div>
    <h1 class="title">Item Edit</h1>

    <div class="grid grid-cols-4 gap-8">
    
        {{-- <livewire:items.brands /> --}}
    
        <div class="col-span-4">

            <livewire:items.variants :$edit :$id />

        </div>
    
    </div>

</div>
