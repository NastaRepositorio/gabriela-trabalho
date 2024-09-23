<?php

use Livewire\Volt\Component;
use App\Models\InspPost;

new class extends Component {
    public string $title = '';
    public string $content = '';
    public ?InspPost $selectedPost = null;

    public function with()
    {
        return [
            'inspPosts' => InspPost::all(),
        ];
    }

    public function createPost()
    {
        InspPost::create([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        // Limpa os campos após criação
        $this->resetInputFields();
    }

    public function selectPostForEdit(InspPost $post)
    {
        $this->selectedPost = $post;
        $this->title = $post->title;
        $this->content = $post->content;
    }

    public function updatePost()
    {
        $this->selectedPost->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        // Limpa os campos após edição
        $this->resetInputFields();
    }

    public function deletePost(InspPost $post)
    {
        $post->delete();
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->content = '';
        $this->selectedPost = null;
    }
}; ?>

<div class="container mt-5">
    <div class="row align-items-top">
        <!-- Coluna da Imagem -->
        <div class="col-md-4">
            <img src="logo.jpeg" class="img-fluid" alt="Logo Cerrado Vivo">
        </div>

        <!-- Coluna do Título e Texto -->
        <div class="col-md-8 mt-md-0 mt-5">
            <div class="d-flex justify-content-between mb-4">
                <h2 class="fw-bold">Conheça o projeto Cerrado Vivo</h2>
                <a href="{{ route('posts') }}" class="btn btn-primary">
                    Visitar o Blog
                </a>
            </div>
            
            {{-- Posts de inspiração --}}
            <ul class="list-group">
                @forelse($inspPosts as $post)
                <li class="list-group-item">
                    <h5 class="fw-bold mt-2 text-primary fw-semibold">{{ $post->title }}</h5>
                    @if($post->image)
                    <img src="{{ Storage::url($post->image) }}" class="img-fluid mb-3">
                    @endif
                    <p>{{ $post->content }}</p>

                    {{-- Exibir botões de edição/exclusão apenas para o usuário com id 1 --}}
                    @if(Auth::check() && Auth::user()->id === 1)
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('edit', $post->id) }}" class="btn btn-warning btn-sm me-2">
                            Editar
                        </a>
                        <button class="btn btn-danger btn-sm" wire:confirm="Tem certeza que deseja excluir este post?" wire:click="deletePost({{ $post->id }})">
                            Excluir
                        </button>
                    </div>
                    @endif
                </li>
                @empty
                <li class="list-group-item">
                    <h5 class="fw-bold mt-2 fw-semibold">Nenhum post de inspiração foi adicionado ainda</h5>
                </li>
                @endforelse
              </ul>

            {{-- Criar um post de inspiração --}}
            @if(Auth::check() && Auth::user()->id === 1)
            <a href="{{ route('create') }}" class="w-100 text-white btn btn-primary mt-4">
                Adicionar Post de Inspiração
            </a>
            @endif

        </div>
    </div>
</div>

