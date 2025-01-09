<?php

namespace App\Livewire\Items;

use App\Models\Inout;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{

    use WithPagination;

    public $id;
    public $item;

    public function mount($id = null)
    {
        $this->item = Item::with('brand')->find($id);
    }

    public function render()
    {
        return view('livewire.items.show', [
            'inouts' => Inout::with('stockin')
                        ->where('item_id', $this->id)
                        ->latest()
                        ->paginate(30)
        ]);
    }
}
