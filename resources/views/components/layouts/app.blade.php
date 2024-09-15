<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cerrado Vivo</title>

    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Layout do template -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            padding-bottom: 20px;
        }

        footer {
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">Cerrado Vivo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts') }}">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('my-posts') }}">Meus Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">Perfil</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo da página -->
    <div class="container mt-4 content">
        {{ $slot }}
    </div>

    <!-- Footer -->
    <footer class="text-white bg-primary">
        <div class="container text-center">
            <p>Siga-nos no Instagram: <a href="https://www.instagram.com/vivo.cerrado" class="text-white">@vivo.cerrado</a></p>
            <p>Entre em contato: <a href="mailto:gabriela.souza1@estudante.ifgoiano.edu.br" class="text-white">gabriela.souza1@estudante.ifgoiano.edu.br</a> |
                <a href="mailto:mariana.botelho@estudante.ifgoiano.edu.br" class="text-white">mariana.botelho@estudante.ifgoiano.edu.br</a></p>
            <p class="mt-3">&copy; {{ date('Y') }} Cerrado Vivo. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
