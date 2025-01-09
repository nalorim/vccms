<div class="grid grid-cols-1 gap-4 my-4">
    <div class="flex items-center justify-between">
        <h1>Up Coming Events</h1>
        <div>
            <div wire:click="openModal()" class="cursor-pointer hover:underline">Create Event</div>
        </div>
    </div>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Assigned To</th>
                    <th>Priority</th>
                    <th>Posted By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $e)
                    <tr wire:click="showDetail({{ $e->id }})" class="hover:bg-slate-700 cursor-pointer transition">
                        <td>{{ date('d-m-Y', strtotime($e->date)) }}</td>
                        <td>{{ $e->name }}</td>
                        <td>{{ $e->description }}</td>
                        <td>{{ $e->assigned_to }}</td>
                        <td><span class="{{ $e->priority }}">{{ $e->priority }}</span></td>
                        <td>{{ $e->created_by }}</td>
                        <td><span class="{{ $e->status }}">{{ $e->status }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>{{ $events->links() }} </div>

    @if($show)
        <x-events.create :$forms :$onEdit />
    @endif

    @if($showEvent)
        <x-events.show :$forms />
    @endif

</div>
