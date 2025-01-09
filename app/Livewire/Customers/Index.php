<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Models\Market;
use App\Models\Salesperson;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $search;
    public $createModal = false;

    public $customer = [
        'name' => '',
        'phone' => '',
        'contact' => '',
        'location' => 'Phnom Penh',
        'link' => '',
        'salesperson_name' => '',
        'salesperson_id' => 0,
        'market_name' => '',
        'market_id' => 0,
        'vat' => 0,
        'discount' => 0,
        'terms' => 7,
        'remark' => ''
    ];

    public $cities = [
        "Phnom Penh",
        "Siem Reap",
        "Battambang",
        "Sihanoukville",
        "Kampong Cham",
        "Kampot",
        "Takeo",
        "Kandal",
        "Pursat",
        "Kep",
        "Tboung Khmum",
        "Prey Veng",
        "Svay Rieng",
        "Banteay Meanchey",
        "Ratanakiri",
        "Mondulkiri",
        "Stung Treng",
        "Oddar Meanchey",
        "Koh Kong",
        "Kratie",
        "Pailin",
        "Chumkiri",
        "Phnom Srouch",
        "Barkeo",
        "Lvea Aem",
        "Bavet",
        "Poipet",
        "Sisophon",
        "Preah Vihear"
    ];      

    public $dropdown = '';
    public $salespersons;
    public $markets;

    public function showCreateModal()
    {
        $this->createModal = true;
    }

    public function closeModal()
    {
        $this->reset([
            'createModal', 'customer'
        ]);
    }

    public function createCustomer()
    {
        $this->validate([
            'customer.name' => 'required|unique:customers,name',
            'customer.phone' => 'required',
            'customer.location' => 'required'
        ]);

        $customer = Customer::create([
            'name' => $this->customer['name'],
            'phone' => $this->customer['phone'],
            'contact' => $this->customer['contact'],
            'location' => $this->customer['location'],
            'map' => $this->customer['link'],
            'salesperson_id' => $this->customer['salesperson_id'] ? $this->customer['salesperson_id'] : null,
            'market_id' => $this->customer['market_id'] ? $this->customer['market_id'] :  null,
            'vat' => $this->customer['vat'],
            'discount' => $this->customer['discount'],
            'terms' => $this->customer['terms'],
            'remark' => $this->customer['remark']
        ]);

        $this->closeModal();
    }

    public function hideDropdown()
    {
        $this->reset('dropdown');
    }

    public function selectSalesperson(Salesperson $sale)
    {
        $this->customer['salesperson_name'] = $sale->profile->name;
        $this->customer['salesperson_id'] = $sale->id;

        $this->hideDropdown();
    }

    public function selectMarket(Market $market)
    {
        $this->customer['market_name'] = $market->name;
        $this->customer['market_id'] = $market->id;

        $this->customer['vat'] = $market->vat;
        $this->customer['discount'] = $market->discount;
        $this->customer['terms'] = $market->terms;

        $this->hideDropdown();
    }

    public function clearSelectSalesperson()
    {
        $this->customer['salesperson_name'] = '';
        $this->customer['salesperson_id'] = null;
    }

    public function clearSelectMarket()
    {
        $this->customer['market_name'] = '';
        $this->customer['market_id'] = null;

        $this->customer['vat'] = 0;
        $this->customer['discount'] = 0;
        $this->customer['terms'] = 1;
    }

    public function render()
    {

        $this->salespersons = Salesperson::where('status', true)
                            ->whereHas('profile', function ($q) {
                                $q->where('name', 'like', '%'. $this->customer['salesperson_name'] .'%');
                            })
                            ->get();
        $this->markets      = Market::where('status', true)
                            ->where('name', 'like', '%'. $this->customer['market_name'] .'%')
                            ->get();

        return view('livewire.customers.index', [
            'customers' => Customer::where('name', 'like', '%'.$this->search.'%')
                            ->orderBy('name')->paginate(30)
        ]);
    }
}
