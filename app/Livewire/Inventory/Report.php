<?php

namespace App\Livewire\Inventory;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class Report extends Component
{

    public $daysInMonth = [];

    public function mount()
    {
        // $days = Carbon::now()->daysInMonth();
        // $day = 1;
        // while($days >= $day)
        // {
        //     $this->daysInMonth[] = $day;
        //     $day++;
        // }

        $this->daysInMonth = [1,2,3];
    }

    public function render()
    {
        return view('livewire.inventory.report');
    }
}
