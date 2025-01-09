<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;
    public $perPage = 50;
    public $search;
    public $advance = false;

    public $invoice_type = 'general';
    public $order_from;
    public $order_to;
    public $order_date = "today";

    public function mount()
    {
        $today = Carbon::today()->toDateString();
        $this->order_from = $today;
        $this->order_to = $today;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->mount();
    }

    public function filterDate()
    {
        $this->order_from = Carbon::parse($this->order_from)->startOfDay()->toDateString();
        $this->order_to = Carbon::parse($this->order_to)->endOfDay()->toDateString();
    }

    public function dateOptions()
    {
        $today = Carbon::today();

        if($this->order_date === 'all')
        {
            $this->order_from = null;
            $this->order_to = null;
        }

        if($this->order_date === 'today')
        {
            $this->order_from = $today->startOfDay()->toDateString();
            $this->order_to = $today->endOfDay()->toDateString();
        }
        if($this->order_date === 'yesterday')
        {
            $this->order_from = $today->subDay()->startOfDay()->toDateString();
            $this->order_to = $today->endOfDay()->toDateString();
        }

        if($this->order_date === 'tomorrow')
        {
            $this->order_from = $today->addDay()->startOfDay()->toDateString();
            $this->order_to = $today->endOfDay()->toDateString();
        }
    }

    public function render()
    {
        $start = $this->order_from;
        $end = $this->order_to;

        return view('livewire.orders.index', [
            'orders' => Order::

                        // Filter Date from and to
                        when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('order_date', [$start, $end]);
                        })

                        // Filter search
                        ->where(function ($q) {
                            $q->where('order_id', 'like', '%'. $this->search .'%')
                            ->orWhereHas('customer', function ($q) { $q->where('name', 'like', '%'. $this->search .'%' ); })
                            ->orWhereHas('customer', function ($q) { $q->where('phone', 'like', '%'. $this->search .'%' ); })
                            ->orWhereHas('customer', function ($q) { $q->where('location', 'like', '%'. $this->search .'%' ); })
                            ->orWhereHas('market', function ($q) { $q->where('name', 'like', '%'. $this->search .'%' ); });
                        })
                        
                        ->latest()
                        ->paginate($this->perPage)
        ]);
    }
}
