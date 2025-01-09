<?php

namespace App\Livewire\Items;

use App\Models\Brand;
use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\On; 

class Create extends Component
{

    public $search;

    public $selectedBrand;
    public $selectedBrandName;
    public $forms = [];
    public $brand = [];

    public $items = [];
    public $fiveFlavors = [
        'Chocolate', 'Vanilla', 'Strawberry', 'Orange', 'Banana'
    ];
    public $twoFlavors = [
        'Chocolate', 'Milk'
    ];

    public function mount()
    {
        $this->addMoreVariant();
    }

    #[On('brand-selected')]
    public function selectBrand(Brand $brand)
    {
        $this->reset('brand');

        if(($this->selectedBrand == $brand->id) && ($this->selectedBrand != ''))
        {
            $this->selectedBrand = '';
            $this->selectedBrandName = '';

        } else {

            $this->selectedBrand = $brand->id;
            $this->selectedBrandName = $brand->name;
            
            $this->brand = [
                'id' => $brand->id,
                'name' => $brand->name,
                'factor' => $brand->factor,
                'um' => $brand->um,
                'ctn_price' => $brand->ctn_price,
                'base_price' => $brand->base_price,
                'history' => $brand->history,
                'variant' => $brand->items()->count()
            ];

            $this->reset('items');
            foreach($brand->items as $item)
            {
                $this->items[] = [
                    'brand_id' => $brand->id,
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'remark' => $item->remark,
                    'factor' => $item->factor,
                    'um' => $item->um,
                    'sku' => $item->sku,
                    'barcode' => $item->barcode,
                    'price' => 0,
                    'stock' => $item->ins
                ];
            }
            if(count($brand->items) == 0)
            {
                $this->addMoreVariant();
            }
        }

    }

    public function addMoreVariant()
    {
        $this->items[] = [
            'brand_id' => null,
            'id' => null,
            'name' => null,
            'description' => '',
            'remark' => '',
            'factor' => 1,
            'um' => 'ctn',
            'sku' => '',
            'barcode' => '',
            'price' => 0,
            'stock' => 0
        ];
    }

    public function removeVariant($index)
    {
        // Check if row item has any stock; if has, show warning
        $ins = number_format($this->items[$index]['stock'] );
        if($ins > 0 )
        {
            session()->flash('fail', 'You cannot delete item with stock history.');
            return ;
        }

        // Delete row from database
        if($this->items[$index]['id']) Item::find($this->items[$index]['id'])->delete();
        
        // Remove row from list array and reset index
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        
    }

    public function createItem(Brand $brand)
    {
        $this->validate([
            'selectedBrand' => 'required',
            'items.*.name' => 'required'
        ]);

        foreach($this->items as $i => $item)
        {
            Item::updateOrCreate(
                [
                    'brand_id' => $item['brand_id'],
                    'name' => $item['name'], 
                ],
                [
                    'factor' => $this->brand['factor'],
                    'um' => $this->brand['um'],

                    'sku' => $item['sku'],
                    'barcode' => $item['barcode'],

                    'remark' => $item['remark'],
                    'created_by' => 1,
                    
                    'description' => $item['description'],
                    'price' => $this->brand['ctn_price']
                ]
            );
        }

        $this->reset([
            'items', 'forms', 'selectedBrand', 'selectedBrandName'
        ]);
        return redirect()->back();
    }

    public function addFiveVariants()
    {
        $items = Brand::find($this->selectedBrand)->items()->pluck('name')->toArray();

        $news = collect($this->fiveFlavors)->map(function ($v) use ($items) {
            if(!in_array($v, $items)) return $v;
        });
        $news = collect($news)->filter();

        if(count($items) == 0) $this->reset('items');

        foreach($news as $new)
        {
            $this->items[] = [
                'brand_id' => $this->selectedBrand,
                'id' => null,
                'name' => $new,
                'description' => '',
                'remark' => '',
                'factor' => 1,
                'um' => 'ctn',
                'sku' => '',
                'barcode' => '',
                'price' => 0,
                'stock' => 0
            ];
        }

    }

    public function clearList()
    {
        $this->reset('items');
        $this->addMoreVariant();
    }

    public function deleteBrand(Brand $brand)
    {
        $brand->items()->delete();
        $brand->delete();

        $this->cancel();
    }

    public function cancel()
    {
        $this->reset([
            'brand', 'selectedBrand', 'selectedBrandName', 'items'
        ]);
    }

    public function render()
    {
        
        return view('livewire.items.create');
    }

}
