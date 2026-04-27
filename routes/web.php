<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleBooksController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\EncomendaController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\SalaController;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

Route::middleware(['auth', 'admin'])->prefix('testes')->name('testes.')->group(function () {
    Route::get('/', [TesteController::class, 'index'])->name('index');
    Route::post('/criar-requisicao', [TesteController::class, 'testarCriacaoRequisicao'])->name('criar-requisicao');
    Route::post('/validacao', [TesteController::class, 'testarValidacao'])->name('validacao');
    Route::post('/devolucao', [TesteController::class, 'testarDevolucao'])->name('devolucao');
    Route::post('/listagem', [TesteController::class, 'testarListagem'])->name('listagem');
    Route::post('/stock', [TesteController::class, 'testarStock'])->name('stock');
    Route::post('/todos', [TesteController::class, 'testarTodos'])->name('todos');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
    Route::get('/livros/{id}', [LivroController::class, 'show'])->name('livros.show');
    Route::get('/autores', [AutorController::class, 'index'])->name('autores.index');
    Route::get('/editoras', [EditorController::class, 'index'])->name('editoras.index');

    Route::get('/requisicoes', [RequisicaoController::class, 'index'])->name('requisicoes.index');
    Route::get('/requisicoes/create', [RequisicaoController::class, 'create'])->name('requisicoes.create');
    Route::post('/requisicoes', [RequisicaoController::class, 'store'])->name('requisicoes.store');
    Route::get('/requisicoes/{id}', [RequisicaoController::class, 'show'])->name('requisicoes.show');
    Route::delete('/requisicoes/{id}', [RequisicaoController::class, 'destroy'])->name('requisicoes.destroy');
    Route::get('/requisicoes/{requisicao}/devolver', [RequisicaoController::class, 'showDevolucaoForm'])->name('requisicoes.devolver-form');
    Route::post('/requisicoes/{requisicao}/confirmar-devolucao', [RequisicaoController::class, 'confirmarDevolucao'])->name('requisicoes.confirmar-devolucao');
    Route::get('/verificar-disponibilidade', [RequisicaoController::class, 'verificarDisponibilidade'])->name('requisicoes.verificar');

    Route::get('/carrinho', [App\Http\Controllers\CarrinhoController::class, 'index'])->name('carrinho.index');
    Route::post('/carrinho/adicionar/{livro}', [App\Http\Controllers\CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
    Route::put('/carrinho/atualizar/{item}', [App\Http\Controllers\CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');
    Route::delete('/carrinho/remover/{item}', [App\Http\Controllers\CarrinhoController::class, 'remover'])->name('carrinho.remover');
    Route::get('/carrinho/checkout', [App\Http\Controllers\CarrinhoController::class, 'checkout'])->name('carrinho.checkout');
    Route::post('/carrinho/processar', [App\Http\Controllers\CarrinhoController::class, 'processarCheckout'])->name('carrinho.processar');
    Route::get('/carrinho/sucesso/{encomenda}', [App\Http\Controllers\CarrinhoController::class, 'sucesso'])->name('carrinho.sucesso');
    Route::get('/carrinho/cancelar/{encomenda}', [App\Http\Controllers\CarrinhoController::class, 'cancelar'])->name('carrinho.cancelar');

    Route::get('/carrinho/total-itens', [App\Http\Controllers\CarrinhoController::class, 'getTotalItens'])->name('carrinho.total-itens');

    Route::get('/encomendas', [App\Http\Controllers\EncomendaController::class, 'index'])->name('encomendas.index');
    Route::get('/encomendas/{id}', [App\Http\Controllers\EncomendaController::class, 'show'])->name('encomendas.show');

    Route::middleware(['admin'])->group(function () {

        Route::get('/livros/create', [LivroController::class, 'create'])->name('livros.create');
        Route::post('/livros', [LivroController::class, 'store'])->name('livros.store');
        Route::get('/livros/{id}/edit', [LivroController::class, 'edit'])->name('livros.edit');
        Route::put('/livros/{id}', [LivroController::class, 'update'])->name('livros.update');
        Route::delete('/livros/{id}', [LivroController::class, 'destroy'])->name('livros.destroy');

        Route::get('/editoras/create', [EditorController::class, 'create'])->name('editoras.create');
        Route::post('/editoras', [EditorController::class, 'store'])->name('editoras.store');
        Route::get('/editoras/{id}/edit', [EditorController::class, 'edit'])->name('editoras.edit');
        Route::put('/editoras/{id}', [EditorController::class, 'update'])->name('editoras.update');
        Route::delete('/editoras/{id}', [EditorController::class, 'destroy'])->name('editoras.destroy');

        Route::get('/autores/create', [AutorController::class, 'create'])->name('autores.create');
        Route::post('/autores', [AutorController::class, 'store'])->name('autores.store');
        Route::get('/autores/{id}/edit', [AutorController::class, 'edit'])->name('autores.edit');
        Route::put('/autores/{id}', [AutorController::class, 'update'])->name('autores.update');
        Route::delete('/autores/{id}', [AutorController::class, 'destroy'])->name('autores.destroy');

        Route::post('/requisicoes/{id}/status', [RequisicaoController::class, 'updateStatus'])->name('requisicoes.status');
        Route::patch('/requisicoes/{requisicao}/status', [RequisicaoController::class, 'updateStatus'])->name('requisicoes.status');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::patch('/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('toggle-admin');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('google-books')->name('google-books.')->group(function () {
        Route::get('/search', [GoogleBooksController::class, 'index'])->name('search');
        Route::post('/search', [GoogleBooksController::class, 'search'])->name('do-search');
        Route::post('/import', [GoogleBooksController::class, 'import'])->name('import');
    });

    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::post('/requisicao/{requisicaoId}', [ReviewController::class, 'store'])->name('store');
        Route::get('/check/{requisicaoId}', [ReviewController::class, 'checkReview'])->name('check');
        Route::get('/livro/{livroId}', [ReviewController::class, 'livroReviews'])->name('livro-reviews');
        Route::get('/{id}', [ReviewController::class, 'show'])->name('show');

        Route::middleware(['admin'])->group(function () {
            Route::get('/pending/all', [ReviewController::class, 'pendingReviews'])->name('pending');
            Route::get('/admin/index', [ReviewController::class, 'index'])->name('index');
            Route::put('/{id}/status', [ReviewController::class, 'updateStatus'])->name('status');
        });
    });

    Route::get('/livros/{livro}/recommendations', [LivroController::class, 'recommendations'])->name('livros.recommendations');

    Route::post('/livros/{livro}/notificar', [App\Http\Controllers\LivroNotificationController::class, 'subscribe'])->name('livros.notificar');
    Route::post('/livros/{livro}/cancelar-notificacao', [App\Http\Controllers\LivroNotificationController::class, 'unsubscribe'])->name('livros.cancelar-notificacao');
    Route::get('/livros/{livro}/check-subscription', [App\Http\Controllers\LivroNotificationController::class, 'checkSubscription'])->name('livros.check-subscription');
});

Route::get('/carrinho/pagamento/{encomenda}', [App\Http\Controllers\CarrinhoController::class, 'mostrarPagamento'])->name('carrinho.pagamento');
Route::post('/carrinho/processar-pagamento', [App\Http\Controllers\CarrinhoController::class, 'processarPagamento'])->name('carrinho.processar-pagamento');

Route::post('/carrinho/adicionar-ajax/{livro}', [App\Http\Controllers\CarrinhoController::class, 'adicionarAjax'])->name('carrinho.adicionar-ajax');

Route::middleware(['auth', 'admin'])->prefix('logs')->name('logs.')->group(function () {
    Route::get('/', [App\Http\Controllers\LogController::class, 'index'])->name('index');
    Route::get('/{id}', [App\Http\Controllers\LogController::class, 'show'])->name('show');
    Route::delete('/limpar', [App\Http\Controllers\LogController::class, 'limpar'])->name('limpar');
});

Route::get('/meus-logs', [App\Http\Controllers\LogController::class, 'meusLogs'])->name('logs.meus-logs')->middleware('auth');

Route::middleware(['auth'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [SalaController::class, 'index'])->name('index');
    Route::get('/conversa/{id}', [SalaController::class, 'show'])->name('conversa');
    
    Route::middleware(['admin'])->group(function () {
        Route::get('/criar-sala', [SalaController::class, 'create'])->name('criar-sala');
        Route::post('/salas', [SalaController::class, 'store'])->name('salas.store');
        Route::delete('/sala/{id}', [SalaController::class, 'destroy'])->name('sala.destroy');
    });
});

