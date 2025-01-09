<?php

namespace App\Livewire\Salesperson;

use App\Models\Market;
use App\Models\Salesperson;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $search;
    public $createModal = false;
    public $form = [];
    public $markets;
    public $users;

    public function mount()
    {
        $this->users = User::get();
        $this->markets = Market::where('status', true)->get();
    }

    public function showCreateModal()
    {
        $this->createModal = true;
        $this->form = [
            'user_id' => null,
            'market_id' => null
        ];
    }

    public function closeModal()
    {
        $this->reset([
            'createModal', 'form'
        ]);
    }

    public function createSales()
    {
        Salesperson::create([
            'user_id' => $this->form['user_id'],
            'market_id' => $this->form['market_id']
        ]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.salesperson.index', [
            'salesperson' => Salesperson::with('profile')->paginate(30)
        ]);
    }
}
