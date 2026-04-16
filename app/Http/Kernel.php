protected $routeMiddleware = [
    'admin' => \App\Http\Middleware\CheckAdmin::class,
    'log.acesso' => \App\Http\Middleware\LogAcessoMiddleware::class,
];

