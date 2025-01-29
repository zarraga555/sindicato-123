<?php

namespace App\Livewire;


use App\Models\User;
use Livewire\WithPagination;
use Livewire\Component;

class UserComponent extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.user-component', compact('users'));
    }
}
