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

        // Redireciona para a página de listagem de posts após criar o post
        return redirect()->route('my-posts');
    }
};
?>

<div class="container mt-5">
    <!-- Cabeçalho da página de criação de posts -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Criar Novo Post</h1>

            <!-- Formulário de criação de posts -->
            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Título do Post</label>
                    <input type="text" id="title" class="form-control @error('title') is-invalid @enderror"
                           wire:model="title" placeholder="Digite o título do post">
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Conteúdo</label>
                    <textarea id="content" class="form-control @error('content') is-invalid @enderror" rows="6"
                              wire:model="content" placeholder="Escreva o conteúdo do post"></textarea>
                    @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Imagem do Post</label>
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid mb-3">
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror" wire:model="image">
                    @error('image')
                    <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('my-posts') }}" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-primary">Criar Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
