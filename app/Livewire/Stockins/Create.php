<?php

namespace App\Livewire\Stockins;

use Livewire\Component;

class Create extends Component
{

    public $edit = false;

    public function render()
    {
        return view('livewire.stockins.create');
    }
}
