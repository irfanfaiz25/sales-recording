<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;


class UserIndex extends Component
{
    public function __construct()
    {
        $this->authorize('view-users');
    }

    public function delete($id)
    {
        $this->authorize('delete-users');

        try {
            $user = User::findOrFail($id);

            // Check if user has sales
            if ($user->sales()->exists()) {
                session()->flash('error', 'User tidak dapat dihapus karena memiliki data penjualan.');
                return;
            }

            $user->delete();
            session()->flash('message', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus user: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function render()
    {
        $users = User::orderBy('name', 'asc')->get();

        return view('livewire.users.user-index', compact('users'))
            ->layout('layouts.app', ['title' => 'Manajemen User']);
    }
}
