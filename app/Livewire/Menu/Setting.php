<?php

namespace App\Livewire\Menu;

use App\Constants\Helper;
use App\Models\Icon;
use App\Models\Route;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;

class Setting extends Component
{

    use WithPagination;
    public $perPage = 25;

    public $links = [];
    public $icons = [];

    public $search;
    public $searchIcon;

    public $label;
    public $route;
    public $view;

    public $modal = false;
    public $editModal = false;
    public $form = [
        'label' => '',
        'icon' => 'i-home',
        'route' => '',
        'view' => '',
        'param' => null,
        'parent_id' => null
    ];

    public function mount()
    {
        $this->links = Route::select('id', 'label')->orderBy('parent_id')->get()->toArray();
    }

    public function updateIcon(Route $route, $icon)
    {
        $route->update([
            'icon' => $icon
        ]);
    }

    public function updateParent(Route $route, $parent_id)
    {
        $route->update([
            'parent_id' => $parent_id
        ]);
    }

    public function openModal()
    {
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->reset([
            'form', 'modal', 'editModal'
        ]);
    }

    public function pickIcon($icon)
    {
        $this->form['icon'] = $icon;
    }

    public function createRecord()
    {
        $this->validate([
            'form.label' => 'required|unique:routes,label',
            'form.route' => 'required',
            'form.view' => 'required'
        ]);

        Route::create([
            'label' => $this->form['label'],
            'icon' => $this->form['icon'],
            'route' => $this->form['route'],
            'view' => $this->form['view'],
            'parent_id' => $this->form['parent_id']
        ]);

        if(!file_exists(resource_path('views/pages/'.$this->form['route'])))
        {
            File::copyDirectory(
                resource_path('views/pages/template'), 
                resource_path('views/pages/'.$this->form['route']
                )
            );
        }

        $this->closeModal();

    }

    public function openEditModal(Route $route)
    {
        $this->form = [
            'id' => $route->id,
            'label' => $route->label,
            'icon' => $route->icon,
            'route' => $route->route,
            'view' => $route->view,
            'param' => $route->param,
            'parent_id' => $route->parent_id
        ];
        $this->editModal = true;
    }

    public function updateRecord(Route $route)
    {
        $this->validate([
            'form.label' => 'required|unique:routes,label,'.$route->id,
            'form.route' => 'required',
            'form.view' => 'required'
        ]);

        $route->update([
            'label' => $this->form['label'],
            'icon' => $this->form['icon'],
            'route' => $this->form['route'],
            'view' => $this->form['view'],
            'param' => $this->form['param'],
            'parent_id' => $this->form['parent_id']
        ]);

        $this->closeModal();
    }

    public function upOrder(Route $route)
    {
        $route->update([
            'order' => $route->order + 1
        ]);
    }

    public function downOrder(Route $route)
    {
        $route->update([
            'order' => $route->order >= 2 ? $route->order - 1 : 1
        ]);
    }

    public function render()
    {
        $this->icons = Icon::where('name', 'like', '%'.$this->searchIcon.'%')->limit(10)->get();

        return view('livewire.menu.setting', [
            'routes' => Route::where('label', 'like', '%'.$this->search.'%')
                                ->orWhereHas('parent',  function ($q) {
                                    $q->where('label', 'like', '%'.$this->search.'%');
                                })
                                ->orderBy('parent_id')->paginate($this->perPage)
        ]);
    }

}
