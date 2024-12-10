<?php

use App\Http\Middleware\RequestLogger;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(function (Router $router) {
        // API Routes
        $router->middleware([RequestLogger::class, 'api'])
            ->prefix('api')
            ->group(function () {
                require base_path('routes/api/users/auth.php'); // Ensure the file exists
            });

        // Web Routes
        $router->middleware(RequestLogger::class)
            ->group(function () {
                require base_path('routes/web.php'); // Ensure the file exists
            });
    })
    ->withMiddleware(function ($middleware) {
        // Add global middleware if needed
    })
    ->withExceptions(function ($exceptions) {
        $exceptions->render(function (Throwable $e) {
            if ($e instanceof ValidationException) {
                return response()->json([
                    'error' => 'Validation Error',
                    'details' => $e->errors(),
                ], 422);
            } elseif ($e instanceof AuthenticationException) {
                return response()->json([
                    'error' => 'Unauthenticated.',
                    'message' => 'You must be logged in to access this resource.',
                ], 401);
            } else {
                Log::channel('server_errors')->error('Unhandled Exception', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json(['error' => 'Internal Server Error.', 'details' => $e->getMessage()], 500);
            }
        });
    })
    ->create();
