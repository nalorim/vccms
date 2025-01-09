<?php

namespace Database\Seeders;

use App\Constants\Helper;
use App\Models\Icon;
use App\Models\Market;
use App\Models\Route;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'test@example.com',
            'role' => 'admin',
            'password' => bcrypt('Newings55$$')
        ]);

        Market::create([
            'name' => 'tax prices',
            'vat' => 10,
            'terms' => 30
        ]);

        $links = [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'view' => 'index',
                'children' => [
                    [
                        'label' => 'Assignment',
                        'route' => 'events',
                        'view' => 'index'
                    ],
                ]
            ],
            [
                'label' => 'Inventory',
                'route' => 'inventory',
                'view' => 'index',
                'children' => [
                    [
                        'label' => 'Items',
                        'route' => 'items',
                        'view' => 'index',
                        'children' => [
                            [
                                'label' => 'Item-Create',
                                'route' => 'items',
                                'view' => 'create'
                            ],
                            [
                                'label' => 'Item-Show',
                                'route' => 'items',
                                'view' => 'show',
                                'param' => 'id'
                            ],
                            [
                                'label' => 'Item-Edit',
                                'route' => 'items',
                                'view' => 'edit',
                                'param' => 'id'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Stock Supply',
                        'route' => 'stockins',
                        'view' => 'index',
                        'children' => [
                            [
                                'label' => 'Stock-Create',
                                'route' => 'stockins',
                                'view' => 'create'
                            ],
                            [
                                'label' => 'Stock-Show',
                                'route' => 'stockins',
                                'view' => 'show',
                                'param' => 'id'
                            ],
                            [
                                'label' => 'Stock-Edit',
                                'route' => 'stockins',
                                'view' => 'edit',
                                'param' => 'id'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Markets & Pricing',
                        'route' => 'markets',
                        'view' => 'index'
                    ]
                ]
            ],
            
            [
                'label' => 'Sales',
                'route' => 'sale',
                'view' => 'index',
                'children' => [
                    [
                        'label' => 'Customers',
                        'route' => 'customers',
                        'view' => 'index'
                    ],
                    [
                        'label' => 'Salesperson',
                        'route' => 'salesperson',
                        'view' => 'index'
                    ],
                    [
                        'label' => 'Orders',
                        'route' => 'orders',
                        'view' => 'index',
                        'children' => [
                            [
                                'label' => 'Order-Create',
                                'route' => 'orders',
                                'view' => 'create'
                            ],
                        ]
                    ]
                ]
            ],
            [
                'label' => 'Settings',
                'route' => 'settings',
                'view' => 'index',
                'children' => [
                    [
                        'label' => 'Menu',
                        'route' => 'routes',
                        'view' => 'index'
                    ],
                ]
            ],
            
        ];

        foreach($links as $i => $link)
        {
            $parent = Route::create([
                'label'     => $link['label'],
                'route'     => $link['route'],
                'view'      => $link['view']
            ]);

            if(isset($link['children']))
            {
                foreach($link['children'] as $j => $child)
                {
                    $dad = Route::create([
                        'parent_id' => $parent->id,
                        'label'     => $child['label'],
                        'route'     => $child['route'],
                        'view'      => $child['view'],
                        'param'     => isset($child['param']) ? $child['param'] : null
                    ]);

                    if(isset($child['children']))
                    {
                        foreach($child['children'] as $grandchild)
                        {
                            Route::create([
                                'parent_id' => $dad->id,
                                'label'     => $grandchild['label'],
                                'route'     => $grandchild['route'],
                                'view'      => $grandchild['view'],
                                'param'     => isset($grandchild['param']) ? $grandchild['param'] : null
                            ]);
                        }
                    }
                }
            }
        }

        $icons = Helper::icons();

        foreach($icons as $c)
        {
            Icon::create([
                'name' => $c['name'],
                'ico' => $c['ico']
            ]);
        }
    }
}
