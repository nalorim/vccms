<?php

namespace App\Livewire\Markets;

use App\Models\Brand;
use App\Models\Item;
use App\Models\Market;
use App\Models\Price;
use Livewire\Component;

class Index extends Component
{

    public $searchMarket;
    public $createMarketModal = false;
    public $forms = [];

    public $market = [
        'id' => '',
        'name' => 'general',
        'slug' => '',
        'vat' => 0,
        'discount' => 0,
        'terms' => 0
    ];

    public $searchItem = '';
    public $edit = false;

    public $brands; 
    public $formPrice = [];

    public function showCreateMarketModal()
    {
        $this->createMarketModal = true;
        $this->forms = [
            'image' => '',
            'name' => '',
            'remark' => '',
            'terms' => 7,
            'vat' => 0,
            'slug' => '',
            'discount' => 0,
            'status' => true
        ];
    }

    public function closeModal()
    {
        $this->reset([
            'createMarketModal',
            'forms'
        ]);
    }

    public function createMarket()
    {
        $this->validate([
            'forms.name' => 'required|unique:markets,name'
        ]);

        $market = Market::create([
            'name' => $this->forms['name'],
            'remark' => $this->forms['remark'],
            'slug' => $this->forms['slug'],
            'vat' => $this->forms['vat'],
            'discount' => $this->forms['discount'],
            'terms' => $this->forms['terms'],
            'image' => $this->forms['image']
        ]);

        $this->market = $market->toArray();
        $this->redirect(route('markets'));

    }

    public function selectMarket(Market $market)
    {
        if(isset($this->market['id']) && ($this->market['id'] == $market->id) )
        {
            $this->reset(['market', 'edit']);
        } else {
            $this->market = $market->toArray();
        }
        $this->edit = false;
    }

    public function editPrice()
    {
        $this->edit = true;
        foreach($this->brands as $i => $brand)
        {
            $this->formPrice[$i]['ctn_price'] = $brand->show_ctn_price($this->market['id']);
            $this->formPrice[$i]['base_price'] = $brand->show_base_price($this->market['id']);
            $this->formPrice[$i]['factor'] = $brand->factor;
        }
    }

    public function update()
    {
        Market::where('id', $this->market['id'])->update([
            'name' => $this->market['name'],
            'slug' => $this->market['slug'],
            'vat' => $this->market['vat'] ? $this->market['vat'] : 0,
            'discount' => $this->market['discount'] ? $this->market['discount'] : 0,
            'terms' => $this->market['terms'] ? $this->market['terms'] : 0,
        ]);

        if($this->market['id'] == '')
        {
            foreach($this->brands as $i => $brand)
            {
                $brand->update([
                    'ctn_price' => $this->formPrice[$i]['ctn_price'],
                    'base_price' => $this->formPrice[$i]['base_price'],
                ]);
            }
        } else {
            foreach($this->brands as $i => $brand)
            {
                $this->calculateBasePrice($i);
                Price::updateOrCreate(
                        [
                            'market_id' => $this->market['id'],
                            'brand_id' => $brand->id
                        ],
                        [
                            'ctn_price' => $this->formPrice[$i]['ctn_price'] ? $this->formPrice[$i]['ctn_price'] : 0,
                            'base_price' => $this->formPrice[$i]['base_price'] ? $this->formPrice[$i]['base_price'] : 0
                        ]);
            }
        }
        $this->edit = false;
    }

    public function calculateBasePrice($index)
    {
        // Validate values
        $ctn_price = $this->formPrice[$index]['ctn_price'] != '' ? $this->formPrice[$index]['ctn_price'] : 0;

        // Calculate 
        $this->formPrice[$index]['base_price'] = 
        number_format($ctn_price / $this->formPrice[$index]['factor'], 4);

    }

    public function delete(Market $market)
    {
        $market->prices()->delete();
        $market->delete();
        
        $this->reset([
            'forms', 'market', 'formPrice'
        ]);
        return redirect()->back();
    }

    public function render()
    {
        $this->brands = Brand::where('name', 'like', '%' . $this->searchItem . '%')->get();

        return view('livewire.markets.index', [
            'markets' => Market::where('status', true)
                        ->where('name', 'like', '%'.$this->searchMarket.'%')
                        ->get(),
        ]);
    }
}
