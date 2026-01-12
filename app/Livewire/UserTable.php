<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function delete(int $id): void
    {
        if (!auth()->user()->isSuperAdmin()) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Anda tidak memiliki akses untuk menghapus user!'
            ]);
            return;
        }

        $user = User::find($id);
        
        if (!$user) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'User tidak ditemukan!'
            ]);
            return;
        }

        if ($user->isSuperAdmin()) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Super admin tidak dapat dihapus!'
            ]);
            return;
        }

        if ($user->id === auth()->id()) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri!'
            ]);
            return;
        }

        $user->delete();
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'User berhasil dihapus!'
        ]);
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.user-table', [
            'users' => $users,
        ]);
    }
}
