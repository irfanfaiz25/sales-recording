<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class UserCreate extends Component
{
    public $name = '';
    public $email = '';
    public $role = '';
    public $password = '';
    public $password_confirmation = '';

    public function __construct()
    {
        $this->authorize('create-users');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    protected $validationAttributes = [
        'name' => 'nama',
        'email' => 'email',
        'role' => 'role',
        'password' => 'password',
        'password_confirmation' => 'konfirmasi password',
    ];

    public function save()
    {
        $this->validate();

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $user->assignRole($this->role);

            session()->flash('message', 'User berhasil dibuat.');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat user. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function render()
    {
        return view('livewire.users.user-create')
            ->layout('layouts.app', ['title' => 'Tambah User']);
    }
}
