<?php

namespace App\Livewire\Menu;

use App\Models\Route;
use Livewire\Component;

class Sub extends Component
{

    public $routes;

    public function mount()
    {
        $this->routes = Route::orderBy('order')->get();
    }

    public function render()
    {
        return view('livewire.menu.sub');
    }
}
