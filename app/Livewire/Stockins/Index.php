<?php

namespace App\Livewire\Stockins;

use App\Models\Stockin;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $search;
    public $perPage = 25;

    public function render()
    {
        return view('livewire.stockins.index', [
            'ins' => Stockin::where('name', 'like', '%'.$this->search.'%')
                    ->latest()
                    ->paginate($this->perPage)
        ]);
    }
}
