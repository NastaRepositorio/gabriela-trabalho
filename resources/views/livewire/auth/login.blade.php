<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new #[Layout('components.layouts.guest')] class extends Component {

    #[Validate('required|string|email', message: 'Por favor informe um email válido')]
    public string $email = '';

    #[Validate('required|string|min:6', message: 'A senha deve ter no mínimo 6 caracteres')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    public function submit()
    {
        
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return redirect()->route('home');
        }

        // Se falhar, adicionar erros de validação:
        $this->addError('email', 'As credenciais fornecidas estão incorretas.');
    }
};

?>

<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
        <h1 class="h3 mb-3 fw-normal text-center">Fazer Login</h1>

        <form wire:submit.prevent="submit">
            <div class="form-floating mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput"
                    placeholder="name@example.com" wire:model="email">
                <label for="floatingInput">Email</label>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword"
                    placeholder="Password" wire:model="password">
                <label for="floatingPassword">Senha</label>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="rememberMe" wire:model="remember">
                <label class="form-check-label" for="rememberMe">
                    Manter sessão
                </label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Entrar</button>
            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 py-2 mt-3">Cadastro</a>
        </form>
    </div>
</div>
