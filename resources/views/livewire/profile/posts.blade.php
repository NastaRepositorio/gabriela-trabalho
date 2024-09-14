<?php

use Livewire\Volt\Component;
use App\Models\Post;
use Livewire\Attributes\On;

new class extends Component {
    public array $posts = [];

    public function mount()
    {
        // Carregar os posts iniciais
        $this->posts = Post::where('user_id', auth()->id())->get()->toArray();
    }

    #[On('post-created')]
    public function updatePosts(array $newPost)
    {
        // Adicionar o novo post ao array de posts sem fazer uma nova query
        $this->posts[] = $newPost;
    }

    public function deletePost(int $postId)
    {
        // Excluir o post
        Post::where('id', $postId)->where('user_id', auth()->id())->delete();

        // Remover o post do array de posts localmente
        $this->posts = array_filter($this->posts, fn($post) => $post['id'] !== $postId);
    }
};
?>

<div>
    <!-- Form de criação do post -->
    <livewire:profile.form>
    
    <div class="row mt-4">
        @foreach ($posts as $post)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Título redireciona para a rota show -->
                            <a href="{{ route('post.show', $post['id']) }}" class="card-title text-primary text-decoration-none fw-bold fs-4">{{ $post['title'] }}</a>
                            <!-- Ícone para excluir o post -->
                            <button class="btn btn-link text-danger p-0" wire:click="deletePost({{ $post['id'] }})" wire:confirm="Tem certeza que deseja excluir este post?">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <p class="card-text">{{ Str::limit($post['content'], 150) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (empty($posts))
        <p class="text-center">Você ainda não tem posts.</p>
    @endif
</div>
