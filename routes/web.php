<?php

use App\Models\Route as ModelsRoute;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

$links = ModelsRoute::with('children.children')->get();

foreach($links as $link)
{
    if(!isset($link->parent_id))
    {
        Route::view($link->route, 'pages.'. $link->route .'.'. $link->view)->name($link->route.'.'.$link->view);

        if(isset($link->children))
        {
            foreach($link->children as $child)
            {
                Route::view($link->route. '/' .$child->route . '/' .$child->view . '/{id?}', 'pages.'. $child->route .'.'. $child->view)->name($child->route.'.'.$child->view);

                if(isset($child->children))
                {
                    foreach($child->children as $grandchild)
                    {
                        Route::view($link->route. '/' . $grandchild->route . '/' . $grandchild->view . '/{id?}', 'pages.'. $grandchild->route .'.'. $grandchild->view)->name($grandchild->route.'.'.$grandchild->view);
                    }
                }
            }
        }
    } 

}
