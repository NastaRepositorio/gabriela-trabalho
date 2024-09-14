<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="container mt-5">
    <div class="row align-items-center">
        <!-- Coluna da Imagem -->
        <div class="col-md-6">
            <img src="logo.jpeg" class="img-fluid" alt="Logo Cerrado Vivo">
        </div>

        <!-- Coluna do Título e Texto -->
        <div class="col-md-6">
            <h2 class="fw-bold">Conheça o projeto Cerrado Vivo</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            
            <!-- Botão para redirecionar ao blog -->
            <a href="{{ route('posts') }}" class="btn btn-primary">
                Visitar o Blog
            </a>
        </div>
    </div>
</div>

