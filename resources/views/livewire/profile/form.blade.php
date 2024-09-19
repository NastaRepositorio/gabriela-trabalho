<?php

use Livewire\Volt\Component;
use App\Models\Post;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use WithFileUploads;

    #[Validate('required|string|max:255', message: 'O título é obrigatório e deve ter no máximo 255 caracteres')]
    public string $title = '';

    #[Validate('required|string', message: 'O conteúdo é obrigatório')]
    public string $content = '';

    public $image; // Para armazenar a imagem temporariamente

    public function submit()
    {
        $this->validate();

        // Verifica se uma imagem foi enviada
        $imagePath = null;
        if ($this->image) {
            // Salva a imagem no storage
            $imagePath = $this->image->store('images', 'public');
        }

        // Criação do post
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'content' => $this->content,
            'image' => $imagePath,
        ]);

        // Dispara o evento que atualiza o component de posts
        $this->dispatch('post-created', $post->toArray());

        // Limpa os campos após o envio
        $this->title = '';
        $this->content = '';
        $this->image = null;
    }
};
?>

<div class="container mt-4">
    <!-- Cabeçalho com título da página e botão para abrir o modal -->
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-4">Meus Posts</h1>
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#createPostModal">
            <i class="bi bi-plus me-2"></i>Escrever um novo post
        </button>
    </div>

    <!-- Formulário de criação de posts no modal -->
    <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Cabeçalho do modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="createPostModalLabel">Criar Novo Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Corpo do modal com o formulário -->
                <div class="modal-body">
                    <form wire:submit.prevent="submit" enctype="multipart/form-data"> <!-- enctype adicionado -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Título do Post</label>
                            <input type="text" id="title"
                                class="form-control @error('title') is-invalid @enderror" wire:model="title"
                                placeholder="Digite o título do post">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Conteúdo</label>
                            <textarea id="content" class="form-control @error('content') is-invalid @enderror" rows="6" wire:model="content"
                                placeholder="Escreva o conteúdo do post"></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Imagem do Post</label>
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}">
                            @endif

                            <input type="file" wire:model="image">

                            @error('image')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>

                <!-- Rodapé do modal com os botões "Fechar" e "Criar Post" alinhados -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button wire:click='submit' data-bs-dismiss="modal" class="btn btn-primary">Criar Post</button>
                </div>
            </div>
        </div>
    </div>

</div>
