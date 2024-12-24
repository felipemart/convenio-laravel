<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};

/**
 * @property-read LengthAwarePaginator | User[] $users
 */
class Index extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public function render(): View
    {
        return view('livewire.users.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::orderBy(...array_values($this->sortBy))->paginate($this->perPage);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'roles', 'label' => 'Nivel'],
        ];
    }
}
