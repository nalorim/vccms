<?php

namespace App\Livewire\Stockins;

use App\Models\Item;
use App\Models\Stockin;
use Livewire\Component;

class Show extends Component
{

    public $po = [];
    public $list = [];

    public $qty = 0;
    public $total = 0;
    
    public $showItem = false;
    public $findItem;
    public $dropdown = '';

    public function mount(Stockin $id)
    {
        $this->po = [
            'id' => $id->id,
            'name' => $id->name,
            'date' => $id->date,
            'contains' => $id->contain,
            'note' => $id->note,
            'qty' => $id->qty,
            'cost' => $id->cost,
            
        ];

        foreach($id->inouts as $inout)
        {
            $this->list[] = [
                'item_id' => $inout->item_id,
                'name' => $inout->item->name,
                'factor' => $inout->item->factor,
                'cost' => $inout->cost,
                'qty' => $inout->qty,
                'qty_ctn' => $inout->qty_ctn,
                'um' => $inout->um,
                'amount' => $inout->amount,
                'brand_name' => $inout->item->brand->name
            ];
        }
        

    }

    public function render()
    {
        return view('livewire.stockins.show', [
            'items' => Item::with('brand')
                        ->where('name', 'like', '%'.$this->findItem.'%')
                        ->orWhereHas('brand', function ($q) {
                            $q->where('name', 'like', '%'.$this->findItem.'%');
                        })
                        ->get()
        ]);
    }
}
