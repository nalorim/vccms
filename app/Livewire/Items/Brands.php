<?php

namespace App\Livewire\Items;

use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class Brands extends Component
{

    use WithFileUploads;

    public $search;
    public $perPage = 6;
    public $createBrandModal = false;

    #[Validate([
        'forms.name' => 'required',
    ], message: [
        'required' => ':attribute is required',
    ], attribute: [
        'forms.name' => 'Brand Name',
    ])]

    public $forms = [];
    public $selectedBrand;

    public function viewMore()
    {
        $this->perPage += 6;
    }

    public function initForm()
    {
        $this->forms = [
            'name' => '',
            'description' => '',
            'remark' => '',
            'image' => '',
            'factor' => 1,
            'um' => 'ctn',
            'ctn_price' => 0,
            'base_price' => 0
        ];
    }

    public function showCreateBrand()
    {
        $this->initForm();
        $this->createBrandModal = true;
    }

    public function closeModal()
    {
        $this->createBrandModal = false;
        $this->reset('forms');
    }

    public function createBrand()
    {

        $this->validate([
            'forms.image' => 'nullable|image|max:5120', // 5MB max
            'forms.name' => 'required|unique:brands,name',
        ]);

        DB::transaction(function () {

            $image_location = '';
            if(!empty($this->forms['image'])) 
            {
                $image_name = $this->forms['image']->getClientOriginalName();
                $this->forms['image']->storeAs('brands', $image_name);
                $image_location = 'brands/'. $image_name;
            }
    
            $ctn_price = $this->forms['um'] == 'ctn' ? 
                        $this->forms['base_price'] : 
                        ($this->forms['base_price'] * $this->forms['factor']);
    
            $brand = Brand::create([
                'name' => $this->forms['name'],
                'remark' => $this->forms['remark'],
                'image' => $image_location,
                'factor' => $this->forms['factor'],
                'um' => $this->forms['um'],
                'base_price' => $this->forms['base_price'],
                'ctn_price' => $ctn_price,
            ]);

            $brand->items()->create([
                'name' => 'All Flavors',
                'factor' => $this->forms['factor'],
                'um' => $this->forms['um'],
                'color' => 'brown',
                'price' => $this->forms['base_price']
            ]);

            $this->selectBrand($brand->id);

        });

    
        $this->reset('forms');
        $this->createBrandModal = false;
        $this->resetErrorBag();
        
    }

    public function selectBrand($brand)
    {
        $this->dispatch("brand-selected", $brand);
        $this->selectedBrand = $brand;
    }

    public function umCTN()
    {
        $this->forms['factor'] = $this->forms['um'] == "ctn" ? 1 : $this->forms['factor'];
    }

    public function render()
    {

        return view('livewire.items.brands', [
            'brands' => Brand::with('items')
                                ->where('name', 'like', '%'.$this->search.'%')
                                ->orderBy('name')
                                ->paginate($this->perPage)
        ]);
    }
}
