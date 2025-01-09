<?php

namespace App\Livewire\Stockins;

use Livewire\Component;

class Edit extends Component
{
    public $id;
    public $edit = true;

    public function render()
    {
        return view('livewire.stockins.edit');
    }
}
