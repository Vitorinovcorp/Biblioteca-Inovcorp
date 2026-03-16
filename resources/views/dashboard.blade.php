<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-azul-tailwind {
            background-color: #2563eb !important; 
        }
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9);
            transition: all 0.2s ease;
        }
        .navbar-dark .navbar-nav .nav-link:hover {
            background-color: #1d4ed8; 
            border-radius: 0.375rem;
            color: white;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-azul-tailwind sticky-top shadow-md">
        <div class="container">
            <a class="navbar-brand" href="/">InovCorp Biblioteca</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->is('livros') ? 'bg-azul-700' : '' }}" href="{{ route('livros.index') }}">Livros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->is('editoras') ? 'bg-azul-700' : '' }}" href="{{ route('editoras.index') }}">Editoras</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->is('autores') ? 'bg-azul-700' : '' }}" href="{{ route('autores.index') }}">Autores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->is('requisicoes') ? 'bg-azul-700' : '' }}" href="{{ route('requisicoes.index') }}">Requisições</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link px-3 py-2">Sair</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>Bem-vindo, {{ Auth::user()->name }}!</h1>
                <p>Role: {{ Auth::user()->role }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Livros</h5>
                        <p class="card-text">Gerir o catálogo de livros</p>
                        <a href="{{ route('livros.index') }}" class="btn btn-primary">Ver Livros</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Autores</h5>
                        <p class="card-text">Gerir os autores</p>
                        <a href="{{ route('autores.index') }}" class="btn btn-primary">Ver Autores</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Editoras</h5>
                        <p class="card-text">Gerir as editoras</p>
                        <a href="{{ route('editoras.index') }}" class="btn btn-primary">Ver Editoras</a>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->role === 'admin')
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h5>Área de Administrador</h5>
                        </div>
                        <div class="card-body">
                            <p>Tem permissões de administrador. Pode:</p>
                            <ul>
                                <li>Criar, editar e eliminar livros</li>
                                <li>Gerir requisições (aprovar/rejeitar)</li>
                                <li>Ver todas as requisições do sistema</li>
                            </ul>
                            <a href="{{ route('livros.create') }}" class="btn btn-success">Criar Novo Livro</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>