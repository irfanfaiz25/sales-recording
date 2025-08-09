<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserEdit extends Component
{
    public User $user;
    public $name = '';
    public $email = '';
    public $role = '';
    public $password = '';
    public $password_confirmation = '';

    public function __construct()
    {
        $this->authorize('edit-users');
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()->first();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'role' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    protected $validationAttributes = [
        'name' => 'nama',
        'email' => 'email',
        'role' => 'role',
        'password' => 'password',
        'password_confirmation' => 'konfirmasi password',
    ];

    public function update()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        // Only update password if provided
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        try {
            $this->user->update($data);

            $this->user->syncRoles([$this->role]);

            session()->flash('message', 'User berhasil diperbarui.');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui user: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function render()
    {
        return view('livewire.users.user-edit')
            ->layout('layouts.app', ['title' => 'Edit User']);
    }
}
