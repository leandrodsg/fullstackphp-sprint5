<?php

// Simple health check endpoint
header('Content-Type: application/json');

try {
    // Check if the application is up
    $status = 'ok';
    $checks = [];

    // Check database connection if configured
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require __DIR__ . '/../vendor/autoload.php';
        
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        try {
            DB::connection()->getPdo();
            $checks['database'] = 'connected';
        } catch (Exception $e) {
            $checks['database'] = 'disconnected';
            $status = 'degraded';
        }
    }

    echo json_encode([
        'status' => $status,
        'timestamp' => date('c'),
        'checks' => $checks
    ]);
    
    http_response_code(200);
} catch (Exception $e) {
    http_response_code(503);
    echo json_encode([
        'status' => 'error',
        'message' => 'Service unavailable',
        'timestamp' => date('c')
    ]);
}
