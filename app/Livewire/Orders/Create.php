<?php

namespace App\Livewire\Orders;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Market;
use App\Models\Order;
use App\Models\Salesperson;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate([
        'order.customer_id' => 'required',
        'order.customer_phone' => 'required',
        'order.customer_location' => 'required',
    ], message: [
        'required' => ':attribute is missing',
    ], attribute: [
        'order.customer_id' => 'Customer',
        'order.customer_phone' => 'Phone number',
        'order.customer_location' => 'Location',
    ])]
    public $order = [
        'order_type' => 'general',
        'order_id' => null,
        'order_rate' => 4100,

        'customer_id' =>  null,
        'customer_name' => '',
        'customer_phone' => null,
        'customer_location' => null,
        'customer_remark' => null,

        'salesperson_name' => null,
        'salesperson_id' => null,

        'market_name' => null,
        'market_id' => null,

        'order_date' => '',
        'vat' => 0,
        'discount' => 0,
        'terms' => 1,
        'transport' => 0
    ];
    public $form = [];

    // dropdown options
    public $dropdown = '';
    public $customers;
    public $salespersons;
    public $markets;

    // modal create
    public $modal = [
        'customer' => false,
        'salesperson' => false,
        'market' => false
    ];

    #[Validate([
        'list.*.name' => 'required',
        'list.*.qty' => 'numeric|min:1',
    ], message: [
        'required' => ':attribute is missing',
        'list.required' => ':attribute are missing',
        'numeric' => '',
        'min' => 'QTY cannot be 0',
    ], attribute: [
        'list.*.name' => 'Item',
        'list.*.qty' => 'Item QTY',
    ])]
    public $list = [];
    public $items;
    public $findItem;


    public $subtotal = 0;
    public $vat = 0;
    public $discount = 0;
    public $qty = 0;
    public $total = 0;
    public $totalKhr = 0;

    public function mount()
    {
        $this->checkOrderID();
        $this->order['order_date'] = today()->toDateString();

        $this->addMoreItem();
    }

    public function changeOrderType()
    {
        $market = Market::where('name', 'tax prices')->first();
        $this->order['market_id'] = $market->id;
        $this->order['market_name'] = $market->name;
    }

    public function selectCustomer(Customer $customer)
    {

        $this->order['customer_name'] = $customer->name;
        $this->order['customer_id'] = $customer->id;
        $this->order['customer_phone'] = $customer->phone;
        $this->order['customer_location'] = $customer->location;
        $this->order['customer_remark'] = $customer->remark;
        
        $this->order['salesperson_name'] = $customer->salesperson_id ? $customer->salesperson->name : '';
        $this->order['salesperson_id'] = $customer->salesperson_id ? $customer->salesperson_id : 0;

        $this->order['market_name'] = $customer->market_id ? $customer->market->name : '';
        $this->order['market_id'] = $customer->market_id ? $customer->market_id : 0;

        $this->order['vat'] = $customer->market_id ? $customer->market->vat : $customer->vat;
        $this->order['discount'] = $customer->market_id ? $customer->market->discount : $customer->discount;
        $this->order['terms'] = $customer->market_id ? $customer->market->terms :$customer->terms;

        $this->calculateItemList();
    }

    public function clearSelectCustomer()
    {
        $this->reset('order');
        $this->checkOrderID();
        $this->order['order_date'] = today()->toDateString();
    }

    public function checkOrderID()
    {
        $id = $this->order['order_type'] == 'general' ? 
                    (Order::whereNotNull('real_id')->latest()->first() ? 
                        Order::whereNotNull('real_id')->latest()->first()->id
                        : 0)
                :   Order::whereNotNull('tax_id')->latest()->first()->id;

        $this->order['order_id'] = $id ? $id+1 : 1;
    }

    public function selectSalesperson(Salesperson $sale)
    {
        $this->order['salesperson_name'] = $sale->profile->name;
        $this->order['salesperson_id'] = $sale->id;
    }

    public function clearSelectSalesperson()
    {
        $this->order['salesperson_name'] = '';
        $this->order['salesperson_id'] = null;
    }

    public function selectMarket(Market $market)
    {
        $this->order['market_name'] = $market->name;
        $this->order['market_id'] = $market->id;

        $this->order['vat'] = $market->vat;
        $this->order['discount'] = $market->discount;
        $this->order['terms'] = $market->terms;

        $this->calculateItemList();
    }

    public function clearSelectMarket()
    {
        $this->order['market_name'] = '';
        $this->order['market_id'] = null;

        $this->order['vat'] = 0;
        $this->order['discount'] = 0;
        $this->order['terms'] = 1;

        $this->calculateItemList();

    }

    public function addMoreItem()
    {
        $this->list[] = [
            'item_id' => null,
            'brand_id' => null,
            'brand_name' => null,
            'name' => null,

            'factor' => null,
            'qty' => null,

            'um' => 'ctn',
            'base_um' => 'unit',

            'ctn_price' => null,
            'base_price' => null,

            'discount' => 0,
            'transport' => 0,
            'amount' => null,

            'stock' => null,
        ];
    }

    public function clearList()
    {
        $this->reset('list');
        $this->addMoreItem();
    }

    public function removeItem($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);
    }

    public function selectItem(Item $item, $index)
    {

        $this->list[$index]['name'] = $item->brand->name . ' - '. $item->name ;
        $this->list[$index]['item_id'] = $item->id;
        $this->list[$index]['brand_id'] = $item->brand_id;
        $this->list[$index]['brand_name'] = $item->brand->name;
        $this->list[$index]['stock'] = $item->total;
        $this->list[$index]['qty'] = 0;

        $this->list[$index]['factor'] = $item->brand->factor;
        $this->list[$index]['um'] = $item->brand->um;
        $this->list[$index]['base_um'] = $item->brand->um;

        $this->list[$index]['transport'] = $this->order['transport'] ? 0.3 : 0;

        $this->list[$index]['ctn_price'] = $item->brand->show_ctn_price($this->order['market_id']);
        $this->list[$index]['base_price'] = $item->brand->show_base_price($this->order['market_id']);
        
        // $this->list[$index]['discount'] = $this->order['discount'];
        $this->list[$index]['amount'] = $this->list[$index]['qty'] * $this->list[$index]['base_price'];
    }

    public function showDropdown($index)
    {
        $this->dropdown = $index;
    }

    public function calculateItemAmount($index)
    {
        $qty = $this->list[$index]['qty'] ? $this->list[$index]['qty'] : 0;
        $price = $this->list[$index]['base_price'];
        $discount = (100 - $this->list[$index]['discount']) / 100;

        $this->list[$index]['amount'] =  ($qty * $price) * $discount;
        
        $this->calculateAll();
    }

    public function calculateItemList()
    {
        
        $fee = $this->order['transport'] ? 0.3 : 0;

        if($this->list[0]['item_id'] != null)
        {
            
            foreach($this->list as $i => $l)
            {
                // get line item collection
                $item = Item::with('brand')->where('id', $this->list[$i]['item_id'])->first();

                // get line [qty] from line input
                $this->list[$i]['qty'] = $this->list[$i]['qty'] ? $this->list[$i]['qty'] : 0;
                $qty = $this->list[$i]['qty'];
                
                // if brand is not null, get [item price] based on brand
                $ctn_price = isset($item->brand) ? $item->brand->show_ctn_price($this->order['market_id']) : 0;
                
                // get line [item factor]
                $factor = isset($item->brand) ? $item->brand->factor : 1;
    
                // update line [unit price] if there is any changes on line [um]
                $this->list[$i]['base_price'] = number_format($this->list[$i]['um'] == 'ctn' ? $ctn_price : ($ctn_price / $factor), 2);
                
                $this->list[$i]['transport'] = $this->list[$i]['um'] == 'ctn' ? $fee : ($fee / $factor);

                // get line [unit price]
                $price = $this->list[$i]['base_price'] + $fee;
    
                // calculate and get line [discount]
                $this->list[$i]['discount'] = $this->list[$i]['discount'] ? $this->list[$i]['discount'] : 0;
                $discount = (100 - $this->list[$i]['discount']) / 100;

                // calculate and update line [amount] based on changes
                $this->list[$i]['amount'] =  ($qty * $price) * $discount;
                
                // calculate subtotal, discount, vat and grand
                $this->calculateAll();
            }
        }
        $this->resetErrorBag();

    }

    public function calculateSubtotal()
    {
        $this->subtotal = collect($this->list)->sum('amount');
    }

    public function calculateDiscount()
    {
        $this->discount = $this->subtotal * ($this->order['discount'] / 100);
    }

    public function calculateVat()
    {
        $this->vat = ($this->subtotal - $this->discount) * ($this->order['vat'] / 100);
    }

    public function calculateTotal()
    {
        $this->total = ($this->subtotal - $this->discount) + $this->vat;
    }

    public function calculateGrandKhr()
    {
        $this->totalKhr = $this->total * $this->order['order_rate'];
    }

    public function calculateAll()
    {
        $this->order['discount'] = $this->order['discount'] ? $this->order['discount'] : 0;
        $this->order['vat'] = $this->order['vat'] ? $this->order['vat'] : 0;
        $this->order['order_rate'] = $this->order['order_rate'] ? $this->order['order_rate'] : 0;

        $this->calculateSubtotal();
        $this->calculateDiscount();
        $this->calculateVat();
        $this->calculateTotal();
        $this->calculateGrandKhr();
    }

    public function clearValidate()
    {
        $this->resetErrorBag();
    }

    public function addVariants($index)
    {
        $fee = $this->order['transport'] ? 0.3 : 0;

        if($this->list[$index]['item_id'])
        {
            // get line item collection
            $item = Item::with('brand')->where('id', $this->list[$index]['item_id'])->first();
            $variants = $item->brand->items;

            $um = $item->brand->um;
            $factor = $item->brand->factor;

            $this->list = array_filter($this->list, function ($value) use ($item) {
                return $value['brand_id'] != $item->brand_id;
            });
            
            foreach($variants as $v)
            {
                $this->list[] = [
                    'item_id' => $v->id,
                    'brand_id' => $item->brand_id,
                    'brand_name' => $item->brand->name,
                    'name' => $item->brand->name . ' - '. $v->name,

                    'factor' => $item->brand->factor,
                    'qty' => 0,
                    'transport' => $um == 'ctn' ? $fee : ($fee / $factor),

                    'um' => $item->brand->um,
                    'base_um' => $item->brand->um,

                    'ctn_price' => $item->brand->show_ctn_price($this->order['market_id']),
                    'base_price' => $item->brand->show_base_price($this->order['market_id']),

                    'discount' => 0,
                    'amount' => null,

                    'stock' => $v->total,
                ];
            }

            $this->list = array_values($this->list);
        }
    }

    public function createOrder()
    {
        $this->validate();

        foreach($this->list as $i => $l)
        {
            $item = Item::find($l['item_id']);
            $qty_base = $l['um'] == 'ctn' ? $l['qty'] * $l['factor'] : $l['qty'];
            $qty_ctn = $l['um'] == 'ctn' ? $l['qty'] : $l['qty'] / $l['factor'];

            if($item->total < $qty_ctn) {
                $row = $i + 1;
                session()->flash('stock', "Row {$row} does not have enough stock. Please check again");
                return ;
            }
        }

        $real_id = $this->order['order_type'] == 'general' ? $this->order['order_id'] : null;
        $tax_id = $this->order['order_type'] == 'tax' ? $this->order['order_id'] : null;

        $order = Order::create([
            'real_id' => $real_id,
            'tax_id' => $tax_id,

            'invoice_type' => $this->order['order_type'],
            'order_type' => 'out',

            'customer_id' => $this->order['customer_id'],
            'phone' => $this->order['customer_phone'],
            'location' => $this->order['customer_location'],
            'remark' => $this->order['customer_remark'],

            'salesperson_id' => $this->order['salesperson_id'],
            'market_id' => $this->order['market_id'],

            'order_date' => $this->order['order_date'],
            'due_date' => Carbon::parse($this->order['order_date'])
                        ->addDays(intval($this->order['terms'])),
            'discount' => $this->order['discount'],
            'vat' => $this->order['vat'],
            'rate' => $this->order['order_rate'],

            'status' => 'pending'
        ]);

        $order->update([
            'order_id' => 'OR'.sprintf('%06d', $order->id)
        ]);

        foreach($this->list as $i => $l)
        {
            if($l['item_id'] && $l['qty'])
            {
                
                $order->inouts()->create([
                    'brand_name' => $l['brand_name'],
                    'item_id' => $l['item_id'],
                    'type' => 'out',
                    'qty' => $l['qty'],
                    'qty_base' => $qty_base,
                    'qty_ctn' => $qty_ctn,
                    'um' => $l['um'],
                    'price' => $l['base_price'],
                    'discount' => $l['discount'],
                    'vat' => $order->vat,
                    'amount' => $l['amount']
                ]);
            }
        }

        return redirect()->route('orders');
    }

    public function render()
    {
        $this->customers    = Customer::where('status', true)
                            ->where('name', 'like', '%'. $this->order['customer_name'] .'%')
                            ->orWhere('phone', 'like', '%'. $this->order['customer_name'] .'%')
                            ->get();
        $this->salespersons = Salesperson::where('status', true)
                            ->whereHas('profile', function ($q) {
                                $q->where('name', 'like', '%'. $this->order['salesperson_name'] .'%');
                            })
                            ->get();
        $this->markets      = Market::where('status', true)
                            ->where('name', 'like', '%'. $this->order['market_name'] .'%')
                            ->get();
        
        $this->items        = Item::where('name', 'like', '%'. $this->findItem .'%')
                            ->orWhereHas('brand', function ($q) {
                                $q->where('name', 'like', '%'. $this->findItem .'%');
                            })
                            ->get();

        return view('livewire.orders.create');
    }
}
