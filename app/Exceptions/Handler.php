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
        if ($request->expectsJson() || $request->is('api/*')) {
            $status = 500;
            
            if ($exception instanceof HttpException) {
                $status = $exception->getStatusCode();
            }
            
            $message = $exception->getMessage() ?: 'An error occurred';

            return response()->json([
                'success' => false,
                'message' => $message
            ], $status);
        }

        return parent::render($request, $exception);
    }
}
