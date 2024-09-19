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
                    <p>{{ $post->content }}</p>

                    {{-- Exibir botões de edição/exclusão apenas para o usuário com id 1 --}}
                    @if(Auth::check() && Auth::user()->id === 1)
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-warning btn-sm me-2" wire:click="selectPostForEdit({{ $post->id }})" data-bs-toggle="modal" data-bs-target="#editInspPostModal">
                            Editar
                        </button>
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
            <button class="w-100 text-white btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#createInspPostModal">
                Adicionar Post de Inspiração
            </button>
            @endif

        </div>
    </div>

    {{-- Modal para criar um post --}}
    <div class="modal fade" id="createInspPostModal" tabindex="-1" aria-labelledby="createInspPostModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createInspPostModalLabel">Adicionar Post de Inspiração</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="createPost">
                        <div class="mb-3">
                            <label for="title" class="form-label">Título</label>
                            <input type="text" class="form-control" id="title" wire:model="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Conteúdo</label>
                            <textarea class="form-control" id="content" wire:model="content" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" data-bs-dismiss="modal">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para editar um post --}}
    <div class="modal fade" id="editInspPostModal" tabindex="-1" aria-labelledby="editInspPostModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInspPostModalLabel">Editar Post de Inspiração</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updatePost">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Título</label>
                            <input type="text" class="form-control" id="editTitle" wire:model="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editContent" class="form-label">Conteúdo</label>
                            <textarea class="form-control" id="editContent" wire:model="content" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" data-bs-dismiss="modal">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

