<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;

class Index extends Component
{
    public $show = false;
    public $showEvent = false;
    public $onEdit = false;
    public $forms = [];
    public $events;

    public function mount()
    {
        $this->events = Event::where('date', '>=', today())->get();
    }

    public function openModal()
    {
        $this->show = true;
        $this->forms = [
            'name' => '',
            'description' => '',
            'date' => today()->toDateString(),
            'priority' => 'normal',
            'status' => 'pending'
        ];
    }

    public function closeModal()
    {
        $this->show = false;
        $this->showEvent = false;
        $this->reset('forms');
        $this->mount();
    }

    public function create()
    {
        Event::create([
            'name' => $this->forms['name'],
            'description' => $this->forms['description'],
            'priority' => $this->forms['priority'],
            'date' => $this->forms['date'],
            'created_by' => auth()->check() ? auth()->id() : 1
        ]);

        $this->redirectIntended('/');
    }

    public function showDetail(Event $event)
    {
        $this->showEvent = true;
        $this->forms = [
            'id' => $event->id,
            'name' => $event->name,
            'description' => $event->description,
            'priority' => $event->priority,
            'date' => $event->date,
            'status' => $event->status,
            'assigned_to' => !empty($event->assigned) ? $event->assigned->name : 'N/A',
            'created_by' => !empty($event->admin) ? $event->admin->name : 'N/A'
        ];

    }

    public function delete(Event $event)
    {
        $event->delete();
        $this->redirectIntended('/'); 
    }

    public function showEdit()
    {
        $this->showEvent = false;
        $this->show = true;
        $this->onEdit = true;
    }

    public function update(Event $event)
    {
        $event->update([
            'name' => $this->forms['name'],
            'description' => $this->forms['description'],
            'date' => $this->forms['date'],
            'priority' => $this->forms['priority']
        ]);
        $this->redirectIntended('/'); 
    }

    public function render()
    {
        return view('livewire.events.index');
    }
}
