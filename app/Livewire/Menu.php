<?php

namespace App\Livewire;

use App\Models\Route;
use Livewire\Component;

class Menu extends Component
{

    public $links;
    public $settings;

    public function mount()
    {
        $this->links = Route::whereNull('parent_id')->where('label', '!=', 'Settings')->get();
        $this->settings = Route::where('label', 'Settings')->first();
    }

    public function render()
    {
        return view('livewire.menu');
    }
}
