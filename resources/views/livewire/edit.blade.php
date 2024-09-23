<?php

use Livewire\Volt\Component;
use App\Models\InspPost;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads;

    public InspPost $post; // O post que será editado

    #[Validate('required|string|max:255', message: 'O título é obrigatório e deve ter no máximo 255 caracteres')]
    public string $title = '';

    #[Validate('required|string', message: 'O conteúdo é obrigatório')]
    public string $content = '';

    public $image; // Para armazenar a nova imagem temporariamente

    public function mount(InspPost $post)
    {
        // Inicializa os valores com os dados existentes do post
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
    }

    public function submit()
    {
        $this->validate();

        // Verifica se uma nova imagem foi enviada
        if ($this->image) {
            // Salva a nova imagem no storage e deleta a antiga, se necessário
            if ($this->post->image) {
                Storage::disk('public')->delete($this->post->image);
            }
            $imagePath = $this->image->store('images', 'public');
            $this->post->image = $imagePath;
        }

        // Atualiza o post com os novos dados
        $this->post->update([
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->post->image ?? $this->post->image, // Atualiza a imagem apenas se houver nova imagem
        ]);

        // Dispara o evento que atualiza o componente de posts
        $this->dispatch('post-updated', $this->post->toArray());

        // Redireciona para a página de listagem de posts após editar o post
        return redirect()->route('home');
    }
};
?>

<div class="container mt-5">
    <!-- Cabeçalho da página de edição de posts -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Editar Post</h1>

            <!-- Formulário de edição de posts -->
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

                    <!-- Exibe a imagem atual se ela existir -->
                    @if ($post->image)
                        <img src="{{ Storage::url($post->image) }}" class="img-fluid mb-3">
                    @endif

                    <!-- Mostra a nova imagem se o usuário fizer upload -->
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid mb-3">
                    @endif

                    <input type="file" class="form-control @error('image') is-invalid @enderror" wire:model="image">
                    @error('image')
                    <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-primary">Atualizar Post</button>
                </div>
            </form>
        </div>
    </div>
</div>


