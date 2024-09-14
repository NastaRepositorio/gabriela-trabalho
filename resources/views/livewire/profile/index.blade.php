<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

new class extends Component {
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        // Carregar os dados atuais do usuário
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfile()
    {
        // Validação dos dados
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Atualizar os dados do usuário autenticado
        $user = Auth::user();
        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password); // Atualizar a senha somente se preenchida
        }

        $user->save();

        // Mensagem de sucesso
        session()->flash('message', 'Perfil atualizado com sucesso!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
};
?>

<div>
    <!-- Exibir mensagem de sucesso -->
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="updateProfile">
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" wire:model="name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" wire:model="email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" id="password" wire:model="password">
            <label class="form-label mt-3">Confirme a Nova Senha</label>
            <input type="password" class="form-control" wire:model="password_confirmation">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
        <button type="button" class="btn btn-danger" wire:click="logout">
            <i class="bi bi-door-open"></i> Fazer Logout
        </button>
    </form>
</div>
