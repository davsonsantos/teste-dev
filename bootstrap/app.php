<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        channels: __DIR__ . '/../routes/channels.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/webhooks/*', // Libera os webhooks da Evolution
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Usamos renderable para capturar antes da resposta ser montada
        $exceptions->render(function (Throwable $e, Request $request) {
            // Se for um erro de validação (422), deixe o Inertia lidar normalmente
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return null;
            }

            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return redirect()->route('login');
            }

            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

            // Tenta renderizar a página de erro se não for debug local intenso
            if (in_array($statusCode, [403, 404, 500, 503])) {
                return Inertia::render('Error', ['status' => $statusCode])
                    ->toResponse($request)
                    ->setStatusCode($statusCode);
            }

            return null;
        });
    })->create();
