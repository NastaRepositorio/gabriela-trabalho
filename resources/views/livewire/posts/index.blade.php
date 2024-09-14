<?php

use Livewire\Volt\Component;
use App\Models\Post;

new class extends Component {
    public array $posts = [];

    public function mount()
    {
        $this->posts = Post::all()->toArray();
    }
};
?>

<div>
    <div class="fs-3">
        Cerrado vivo - Blog
    </div>
    <div class="row mt-4">
        @foreach ($posts as $post)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Título redireciona para a rota show -->
                            <a href="{{ route('post.show', $post['id']) }}" class="card-title text-primary text-decoration-none fw-bold fs-4">{{ $post['title'] }}</a>
                        </div>
                        <p class="card-text">{{ Str::limit($post['content'], 150) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (empty($posts))
        <p class="text-center">Nenhum post encontrado.</p>
    @endif
</div>
