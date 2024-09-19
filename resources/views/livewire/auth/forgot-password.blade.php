<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

new #[Layout('components.layouts.guest')] class extends Component {
    
    #[Validate('required|string|email', message: 'Por favor informe um email válido')]
    public string $email = '';

    public function sendResetLink()
    {
        $this->validate();
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __("O seu link de recuperação foi enviado com sucesso!"));
    }
}; ?>

<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
        <h1 class="h3 mb-3 fw-normal text-center">Recuperar Senha</h1>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="sendResetLink">
            <div class="form-floating mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="emailInput"
                    placeholder="name@example.com" wire:model="email">
                <label for="emailInput">Email</label>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Enviar link de recuperação</button>
            <a href="{{ route('login') }}" class="btn btn-link w-100 py-2 mt-3">Voltar para login</a>
        </form>
    </div>
</div>

