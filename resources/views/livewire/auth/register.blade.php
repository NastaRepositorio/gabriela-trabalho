<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\User;

new #[Layout('components.layouts.guest')] class extends Component {

    #[Validate('required|string', message: 'Por favor, informe seu nome')]
    public string $name = '';

    #[Validate('required|string|email', message: 'Por favor, informe um email válido')]
    public string $email = '';

    #[Validate('required|string|min:6', message: 'A senha deve ter no mínimo 6 caracteres')]
    public string $password = '';

    #[Validate('required|string|same:password', message: 'As senhas devem coincidir')]
    public string $password_confirmation = '';

    public function submit()
    {
        // Aqui, você implementa a lógica de registro, como criação de usuários.
        // Exemplo:
        $validatedData = $this->validate();
        // Salvar o usuário, por exemplo:
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::attempt(['email' => $this->email, 'password' => $this->password]);
        return redirect()->route('home');
    }
};

?>

<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
        <h1 class="h3 mb-3 fw-normal text-center">Criar Conta</h1>

        <form wire:submit.prevent="submit">
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="floatingName"
                    placeholder="Seu Nome" wire:model="name">
                <label for="floatingName">Nome Completo</label>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingEmail"
                    placeholder="name@example.com" wire:model="email">
                <label for="floatingEmail">Email</label>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword"
                    placeholder="Senha" wire:model="password">
                <label for="floatingPassword">Senha</label>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="floatingPasswordConfirmation" placeholder="Confirme a Senha" wire:model="password_confirmation">
                <label for="floatingPasswordConfirmation">Confirme a Senha</label>
                @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Registrar</button>
            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 py-2 mt-3">Já tem conta? Faça Login</a>
        </form>
    </div>
</div>
