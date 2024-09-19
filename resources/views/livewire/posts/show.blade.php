<?php

use Livewire\Volt\Component;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

new class extends Component {
    public Post $post;
    public array $comments = [];

    #[Validate('required|string|max:255', message: 'O título é obrigatório e deve ter no máximo 255 caracteres')]
    public string $title;

    #[Validate('required|string', message: 'O conteúdo é obrigatório')]
    public string $content;

    #[Validate('required|string|max:500', message: 'O comentário é obrigatório e deve ter no máximo 500 caracteres')]
    public string $newCommentContent = '';

    public function mount(): void
    {
        $this->fill($this->post);
        $this->comments = Comment::where('post_id', $this->post->id)
            ->latest()
            ->get()
            ->toArray();
    }

    public function updatePost()
    {
        $this->validate();

        Post::where('id', $this->post['id'])->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        return redirect()->route('post.show', $this->post['id']);
    }

    public function deletePost()
    {
        Post::where('id', $this->post['id'])->delete();
        return redirect()->route('my-posts');
    }

    public function submitComment()
    {
        $this->validate();
        // Validar e criar o comentário
        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $this->post->id,
            'content' => $this->newCommentContent,
        ]);

        // Limpar o campo de comentário e atualizar a lista de comentários
        $this->newCommentContent = '';
        $this->comments = Comment::where('post_id', $this->post->id)
            ->latest()
            ->get()
            ->toArray();
    }

    public function deleteComment(int $commentId)
    {
        // Excluir o comentário se for do usuário autenticado
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id === Auth::id()) {
            $comment->delete();
        }

        // Atualizar a lista de comentários
        $this->comments = Comment::where('post_id', $this->post->id)
            ->latest()
            ->get()
            ->toArray();
    }
};
?>

<div class="container mt-4">
    <!-- Mostrar botões de editar e excluir somente se o post for do usuário autenticado -->
    @if ($post['user_id'] === auth()->id())
        <div class="d-flex justify-content-end mb-4">
            <!-- Botão para abrir o modal de edição -->
            <a href="{{ route('my-posts.edit', $post['id']) }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-pencil"></i> Editar
            </a>

            <!-- Botão para excluir o post -->
            <button class="btn btn-outline-danger" wire:click="deletePost"
                wire:confirm="Tem certeza que deseja excluir este post?">
                <i class="bi bi-trash"></i> Excluir
            </button>
        </div>
    @endif

    <!-- Exibir o post -->
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title text-primary">{{ $post['title'] }}</h2>
            <p class="text-dark">Escrito em: {{ $post['created_at']->format('d/m/Y') }}</p>
            @if($post['image'])
            <img src="{{ Storage::url($post['image']) }}" class="img-fluid mb-3">
            @endif
            <p class="card-text">{{ $post['content'] }}</p>
        </div>
    </div>

    <!-- Comentários do post -->
    <div class="mt-4">
        <h4>Comentários</h4>

        @forelse ($comments as $comment)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <strong
                            class="fs-5 fw-bold">{{ $comment['user_id'] === auth()->id() ? 'Você' : 'Usuário ' . $comment['user_id'] }}
                        </strong>
                        <!-- Botão para excluir o comentário se for do usuário autenticado -->
                        @if ($comment['user_id'] === auth()->id())
                            <button class="btn btn-danger btn-sm float-end"
                                wire:click="deleteComment({{ $comment['id'] }})">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        @endif
                    </div>

                    <p>{{ $comment['content'] }}</p>


                </div>
            </div>
        @empty
            <p>Nenhum comentário encontrado.</p>
        @endforelse
    </div>

    <!-- Formulário para criar um novo comentário -->
    <div class="mt-4">
        <form wire:submit.prevent="submitComment">
            <div class="mb-3 d-flex gap-3">
                <input type="text" id="newComment" class="form-control" wire:model="newCommentContent"
                    rows="3"></input>
                <button type="submit" class="btn btn-primary">Comentar</button>
                @error('newCommentContent')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </form>
    </div>
</div>
