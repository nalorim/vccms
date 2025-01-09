<?php

namespace App\Livewire\Stockins;

use App\Models\Inout;
use App\Models\Item;
use App\Models\Stockin;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Ramsey\Uuid\Type\Integer;

class Data extends Component
{

    public $edit;

    public $showItem = false;
    public $findItem;
    public $dropdown = '';
    public $total = 0;
    public $qty = 0;

    #[Validate([
        'po.name' => 'required',
    ], message: [
        'required' => ':attribute is missing',
    ], attribute: [
        'po.name' => 'PO#',
    ])]
    public $po = [];

    #[Validate([
        'list.*.name' => 'required',
        'list.*.qty' => 'numeric|min:1',
    ], message: [
        'required' => ':attribute is missing',
        'min' => ':attribute cannot be 0',
    ], attribute: [
        'list.*.name' => 'Item',
        'list.*.qty' => 'Qty'
    ])]
    public $list = [];
    public $removeItems = [];

    public function mount($id = null)
    {   
        $this->initPage($id);
    }

    public function initPage($id = null)
    {
        $this->resetList();

        if($id == null)
        {  
            $this->po = [
                'id' => null,
                'name' => '',
                'note' => '',
                'date' => today()->toDateString()
            ];
            $this->addMoreItem();
        } else {
            $id = Stockin::find($id);
            $this->populate($id);
        }
    }

    public function populate(Stockin $id)
    {
        $this->resetList();

        $this->po = [
            'id' => $id->id,
            'name' => $id->name,
            'note' => $id->note,
            'date' => $id->date
        ];

        foreach($id->inouts as $i => $inout)
        {
            $this->list[$i] = [
                'id' => $inout->id,
                'item_id' => $inout->item_id,
                'name' => $inout->brand_name. ' - ' . $inout->item->name,
                'factor' => $inout->item->factor,
                'cost' => $inout->cost,
                'qty' => $inout->qty,
                'um' => $inout->um,
                'um_option' => $inout->item->um,
                'amount' => $inout->amount,
                'brand_name' => $inout->item->brand->name,
                'qty_base' => $inout->qty_base
            ];

            $this->calculateAmount($i);
        }
    }

    public function selectItem(Item $item, $index)
    {
        $this->list[$index] = [
            'name' => $item->brand->name .' - '. $item->name,
            'item_id' => $item->id,
            'um' => $item->um,
            'um_option' => $item->brand->um,
            'qty' => 0,
            'factor' => $item->factor,
            'cost' => $item->cost,
            'brand_name' => $item->brand->name,
            'amount' => 0
        ];
    }

    public function addMoreItem()
    {
        $this->list[] = [
            'item_id' => 0,
            'name' => '',
            'factor' => 1,
            'cost' => 0,
            'qty' => 0,
            'qty_ctn' => 0,
            'um' => 'box',
            'um_option' => 'box',
            'amount' => 0,
            'brand_name' => ''
        ];
    }

    public function calculateAmount($index = null)
    {
        $items = $this->list;
        foreach($items as $i => $line)
        {
            $cost = $line['cost'] ? $line['cost'] : 0;
            $qty = $line['qty'] ? $line['qty'] : 0;

            $items[$i]['amount'] = $cost * $qty;
            $items[$i]['qty_ctn'] = $line['um'] == "ctn" ? $qty : ($qty / $line['factor']);
        }
        $this->list = $items;

        $this->total = collect($this->list)->sum('amount');
        $this->qty = collect($this->list)->sum('qty_ctn');
        
    }

    public function removeItem($index)
    {
        $this->removeItems[] = $this->list[$index]['item_id'];
        unset($this->list[$index]);
        $this->list = collect($this->list)->toArray();
        
        $this->calculateAmount();
    }

    public function resetList()
    {
        $this->reset([
            'po', 'list', 'removeItems', 'total', 'qty'
        ]);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        // final recalculate to prevent mistake amount
        foreach($this->list as $i => $list)
        {
            $this->calculateAmount($i);
        }

        DB::transaction(function() {

            $po = Stockin::create([
                'name' => $this->po['name'],
                'date' => $this->po['date'],
                'note' => $this->po['note'],
                'qty' => $this->qty,
                'cost' => $this->total,
                'created_by' => 1
            ]);

            foreach($this->list as $list)
            {
                if( ($list['name'] != '') && ($list['cost'] > 0))
                {
                    Inout::create([
                        'stockin_id' => $po->id,
                        'item_id' => $list['item_id'],
                        'type' => 'in',
                        'cost' => $list['cost'],
                        'um' => $list['um'],
                        'qty' => $list['qty'],
                        'qty_base' => $list['um'] == 'ctn' ? ($list['qty'] * $list['factor']) : $list['qty'],
                        'qty_ctn' => $list['um'] == 'ctn' ? $list['qty'] : ($list['qty']  / $list['factor']),
                        'amount' => $list['cost'] * $list['qty'],
                        'brand_name' => $list['brand_name']
                    ]);
                }
            }

        });

    }

    public function update(Stockin $stockin)
    {
        // remove empty array
        $this->list = collect($this->list)->filter(function ($value) {
            return $value['name'] != '';
        });
        
        // validate input
        $this->validate();

        // perform line calculation
        // $this->calculateAmount();

        if(!empty($this->removeItems))
        {
            foreach($this->removeItems as $item_id)
            {
                Inout::where('stockin_id', $stockin->id)
                ->where('item_id', $item_id)
                ->delete();
            }
        }

        DB::transaction(function() use ($stockin) {

            $updatedStockin = Stockin::updateOrCreate([
                'id' => $stockin->id
            ],[
                'name' => $this->po['name'],
                'date' => $this->po['date'],
                'note' => $this->po['note'],
                'qty' => $this->qty,
                'cost' => $this->total,
                'created_by' => 1
            ]);

            

            foreach($this->list as $list)
            {
                Inout::updateOrCreate([
                    'stockin_id' => $updatedStockin->id,
                    'item_id' => $list['item_id'],
                ],[
                    'type' => 'in',
                    'cost' => $list['cost'],
                    'um' => $list['um'],
                    'qty' => $list['qty'],
                    'qty_base' => $list['um'] == 'ctn' ? ($list['qty'] * $list['factor']) : $list['qty'],
                    'amount' => $list['cost'] * $list['qty'],
                    'brand_name' => $list['brand_name']
                ]);
            }

        });

        session()->flash('message', 'Done Successfully!');
    }

    public function delete(Stockin $stockin)
    {
        $stockin->inouts()->delete();
        $stockin->delete();

        $this->redirect(route('stockins.index'));
    }

    public function clearSessionMsg()
    {
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.stockins.data', [
            'items' => Item::with('brand')
                        ->where('name', 'like', '%'.$this->findItem.'%')
                        ->orWhereHas('brand', function ($q) {
                            $q->where('name', 'like', '%'.$this->findItem.'%');
                        })
                        ->orderBy('brand_id')
                        ->limit(10)
                        ->get()
        ]);
    }
}
