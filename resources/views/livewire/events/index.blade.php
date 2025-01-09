<div>
    <div class="flex items-center justify-between">
        <h1>Up Coming Event</h1>
        <div class="flex items-center space-x-4">
            <div wire:click="openModal()" class="cursor-pointer hover:underline">Create Event</div>
            <a href="{{ route('events.index') }}" class="hover:underline">View All</a>
        </div>
    </div>
    <div class="flex items-stretch space-x-4 py-4 overflow-scroll">
        
        @forelse ($events->groupBy('date') as $event => $e)
            <div class="card-event">
                <div class="text-right text-gray-300 ">{{ date('d M', strtotime($event)) }}</div>
                <ul class="list-event">
                    @foreach ($e->take(3) as $i)
                    <li wire:click="showDetail({{ $i->id }})" class="{{ $i->priority }}">
                        {{ $i->name }}
                    </li>
                    @endforeach
                </ul>
            </div>
        @empty
        <div wire:click="openModal()" class="card-event hover:border transition cursor-pointer">
            <div class="text-right text-gray-300 "></div>
            <ul class="list-event">
                <div class="">New Schedule</div>
            </ul>
        </div>
        @endforelse
        

    </div>

    @if($show)
        <x-events.create :$forms :$onEdit />
    @endif

    @if($showEvent)
        <x-events.show :$forms />
    @endif

</div>
