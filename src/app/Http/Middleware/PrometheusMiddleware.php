<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMiddleware
{
    private $startTime;
    private $registry;

    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;
        $this->startTime = microtime(true);
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Get labels for metrics
        $labels = [
            'method' => $request->method(),
            'path' => $request->route() ? $request->route()->uri() : 'unknown'
        ];

        // Increment total requests counter
        $counter = $this->registry->getOrRegisterCounter('app', 'http_requests_total', 'Total number of HTTP requests', ['method', 'path']);
        $counter->inc(1, $labels);

        // If error occurred, increment error counter
        if ($response instanceof Response && $response->getStatusCode() >= 500) {
            $errorCounter = $this->registry->getOrRegisterCounter('app', 'http_errors_total', 'Total number of HTTP 500 errors', ['method', 'path']);
            $errorCounter->inc(1, $labels);
        }

        // Update application uptime
        $uptime = microtime(true) - $this->startTime;
        $gauge = $this->registry->getOrRegisterGauge('app', 'uptime_seconds', 'Application uptime in seconds', ['instance']);
        $gauge->set($uptime, ['instance' => gethostname()]);

        return $response;
    }
}
