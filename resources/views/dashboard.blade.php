<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Inovcorp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/js/app.js')
    @livewireStyles
    
    <link rel="icon" href="{{ asset('icons/inovcorp-bg-w.png') }}" type="image/x-icon">
</head>

<body class="bg-gray-100 min-h-screen">
    <x-header />

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
                            <li>Gerir utilizadores (tornar admin/remover admin)</li>
                        </ul>
                        <a href="{{ route('livros.create') }}" class="btn btn-success">Criar Novo Livro</a>
                        <a href="{{ route('users.index') }}" class="btn btn-primary text-white ">Gerir Utilizadores</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>