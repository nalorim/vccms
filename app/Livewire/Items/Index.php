<?php

namespace App\Livewire\Items;

use App\Exports\itemExport;
use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    use WithPagination;

    public $search;
    public $perPage = 25;
    public $um = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function export() 
    {
        return Excel::download(new itemExport, 'export_items - '. now() .'.xlsx');
    }

    public function render()
    {
        return view('livewire.items.index', [
            'brands' => Brand::with('items')
                        ->where('name', 'like', '%'.$this->search.'%')
                        ->orWhereHas('items', function ($q) {
                            $q->where('sku', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('items', function ($q) {
                            $q->where('barcode', 'like', '%'.$this->search.'%');
                        })
                        ->paginate($this->perPage)
                    
        ]);

    }
}
