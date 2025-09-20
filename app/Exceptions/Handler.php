<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    public function register(): void
    {
    }

    public function render($request, Throwable $exception): Response
    {
        // Se for uma requisição para API, retorna JSON padronizado
        if ($request->is('api/*')) {
            $status = 500;
            $message = 'An error occurred';
            
            // Verifica se é uma HttpException para pegar o status code correto
            if ($exception instanceof HttpException) {
                $status = $exception->getStatusCode();
            }
            
            // Pega a mensagem da exceção se existir
            if (!empty($exception->getMessage())) {
                $message = $exception->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ], $status);
        }

        return parent::render($request, $exception);
    }
}
