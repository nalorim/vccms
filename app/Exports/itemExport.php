<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class itemExport implements FromView, ShouldAutoSize
{

    // protected $items;

    // public function forYear($items)
    // {
    //     $this->items = $items;
    // }


    public function view(): View
    {
        return view('exports.items', [
            'items' => Item::with('brand')->get()
        ]);
    }

}
