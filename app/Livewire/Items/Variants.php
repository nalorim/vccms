<?php

namespace App\Livewire\Items;

use App\Models\Brand;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class Variants extends Component
{
    use WithFileUploads;

    public $edit;
    public $modify = false;

    public $selectedBrand;
    public $selectedBrandName;
    
    public $items = [];
    public $brand = [];
    public $factor = 1;
    public $image;
    public $removeItems = [];

    public $colors = [
        'brown',
        'blue',
        'orange',
        'yellow',
        'green',
        'red',
        'pink',
        'purple',
        'emerald'
    ];
    public $fiveFlavors = [
        'Chocolate', 'Vanilla', 'Strawberry', 'Orange', 'Banana'
    ];
    public $twoFlavors = [
        'Chocolate', 'Milk'
    ];

    public function mount(Brand $id)
    {
        $this->selectBrand($id);
    }

    public function refreshVariant($brand)
    {
        $this->dispatch("select-brand", $brand);
    }

    #[On('brand-selected')]
    public function selectBrand(Brand $brand, $refresh = null)
    {
        $this->modify = false;
        $this->reset('brand');

        if(($this->selectedBrand == $brand->id) && ($this->selectedBrand != '') && empty($refresh))
        {
            $this->selectedBrand = '';
            $this->selectedBrandName = '';
            
        } else {

            $this->selectedBrand = $brand->id;
            $this->selectedBrandName = $brand->name;
            $this->factor = $brand->factor;
            
            $this->brand = [
                'id' => $brand->id,
                'image' => $brand->image,
                'name' => $brand->name,
                'factor' => $brand->factor,
                'um' => $brand->um,
                'ctn_price' => $brand->ctn_price,
                'base_price' => $brand->base_price,
                'history' => $brand->history,
                'variant' => $brand->items()->count(),
                'created_at' => $brand->created_at,
                'status' => $brand->status,
                'remark' => $brand->remark
            ];

            $this->reset('items');
            foreach($brand->items as $item)
            {
                $this->items[] = [
                    'brand_id' => $brand->id,
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'color' => $item->color,
                    'remark' => $item->remark,
                    'factor' => $item->factor,
                    'um' => $item->um,
                    'sku' => $item->sku,
                    'barcode' => $item->barcode,
                    'price' => 0,
                    'stock' => $item->in_stock,
                    'new_stock' => $item->in_stock * $item->factor,
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
            'color' => '',
            'remark' => '',
            'factor' => 1,
            'um' => 'ctn',
            'sku' => '',
            'barcode' => '',
            'price' => 0,
            'stock' => 0,
            'new_stock' => 0
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

        $this->removeItems[] = $this->items[$index]['id'];
        // Delete row from database
        // if($this->items[$index]['id']) Item::find($this->items[$index]['id'])->delete();
        
        // Remove row from list array and reset index
        unset($this->items[$index]);
        $this->items = array_values($this->items);

        $this->modify = true;
        
    }

    public function createItem(Brand $brand)
    {
        $this->modify = true;
        // remove empty array
        $this->items = collect($this->items)->filter(function ($value) {
            return $value['name'] != '';
        });

        // validate
        $this->validate([
            'selectedBrand' => 'required',
            'items.*.name' => 'required'
        ]); 

        DB::transaction(function () use ($brand) {

            if($this->edit)
            {
                $this->validate([
                    'brand.name' => 'required|unique:brands,name,'.$this->brand['id'],
                    'brand.factor' => 'required',
                    'brand.ctn_price' => 'required'
                ]);

                $image_location = $this->brand['image'];
                if(!empty($this->image)) 
                {
                    $image_name = $this->image->getClientOriginalName();
                    $this->image->storeAs('brands', $image_name);
                    $image_location = 'brands/'. $image_name;
                }

                $b = Brand::find($this->brand['id']);

                $b_base_price = $this->brand['base_price'] ? $this->brand['base_price'] : 0;
                $b_ctn_price = $b_base_price * $this->brand['factor'];
                $b->update([
                    'name' => $this->brand['name'],
                    'factor' => $this->brand['factor'],
                    'um' => $this->brand['um'],
                    'image' => $image_location,
                    'base_price' => $b_base_price,
                    'ctn_price' => $b_ctn_price,
                    'remark' => $this->brand['remark']
                ]);
            }

            if(!empty($this->removeItems))
            {
                foreach($this->removeItems as $item_id)
                {
                    Item::where('id', $item_id)->delete();
                }
            }

            foreach($this->items as $i => $item)
            {
                $p = Item::updateOrCreate(
                    [
                        'brand_id' => $brand['id'],
                        'name' => $item['name'], 
                    ],
                    [
                        'factor' => $this->brand['factor'],
                        'um' => $this->brand['um'],
    
                        'sku' => $item['sku'],
                        'barcode' => $item['barcode'],
    
                        'color' => $item['color'],
                        'remark' => $item['remark'],
                        'created_by' => 1,
                        
                        'description' => $item['description'],
                        'price' => $this->brand['base_price']
                    ]
                );

                // *** important
                $p->refreshInoutQTY($this->brand['factor']);

            }

        });

        if(!$this->edit)
        {
            session()->flash('message', 'Saved Successfully!');
        } else {
            session()->flash('message', 'Updated Successfully!');
            $this->redirect(route('items.edit', [ 'id' => $this->brand['id'] ]));
        }

        $this->modify = false;

    }

    public function addFiveVariants()
    {
        $this->modify = true;

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
                'color' => '',
                'remark' => '',
                'factor' => 1,
                'um' => 'ctn',
                'sku' => '',
                'barcode' => '',
                'price' => 0,
                'stock' => 0,
                'new_stock' => 0
            ];
        }

    }

    public function clearList()
    {
        $this->reset('items');
        $this->addMoreVariant();
    }

    public function cancel()
    {
        $this->reset([
            'brand', 'selectedBrand', 'selectedBrandName', 'items'
        ]);
    }

    public function deleteBrand(Brand $brand)
    {
        $brand->items()->delete();
        $brand->delete();

        $this->redirect(route('items.index'));
    }

    public function clearSessionMsg()
    {
        $this->resetErrorBag();
    }

    public function pickColor($color, $index)
    {
        $this->items = collect($this->items)->toArray();
        $this->items[$index]['color'] = $color;

        $this->modify = true;
    }

    public function updateInStockByUM($changeUm = false)
    {
        // Get default factor
        $this->brand['factor'] = $changeUm ? $this->factor : $this->brand['factor'];

        // set default null to 1
        $this->brand['factor'] = $this->brand['factor'] ? $this->brand['factor'] : 1;

        $this->brand['base_price'] = $this->brand['um'] == "ctn" ?
                                        $this->brand['ctn_price']
                                        :
                                        $this->brand['ctn_price'] / $this->brand['factor'];

        foreach($this->items as $i => $item)
        {
            if( $this->brand['um'] == "ctn")
            {
                $this->items[$i]['new_stock'] = $this->items[$i]['stock'];
            } else {
                $this->items[$i]['new_stock'] = $this->items[$i]['stock'] * $this->brand['factor'];
            }
        }
        $this->modify = true;
    }

    public function umCTN()
    {
        $this->updateInStockByUM(true);
        $this->brand['factor'] = $this->brand['um'] == 'ctn' ? 1 : $this->brand['factor'];
    }

    public function render()
    {
        return view('livewire.items.variants');
    }
}
