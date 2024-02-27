<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts extends Component
{
    use WithPagination;

    public $search;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $listeners = ['sortBy'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.show-products', compact('products'))->layout('layouts.admin');
    }

    public function sortBy($column, $direction)
    {
        $this->sortBy = $column;
        $this->sortDirection = $direction === 'asc' ? 'desc' : 'asc';
    }
}
