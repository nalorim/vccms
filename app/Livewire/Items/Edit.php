<?php

namespace App\Livewire\Items;

use App\Models\Brand;
use App\Models\Inout;
use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\On; 

class Edit extends Component
{

    public $edit = true;
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        return view('livewire.items.edit');
    }
}
