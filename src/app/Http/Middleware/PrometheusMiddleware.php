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

        try {
            // Increment total requests counter
            $counter = $this->registry->getCounter('app', 'http_requests_total');
            $counter->inc($labels);

            // If error occurred, increment error counter
            if ($response instanceof Response && $response->getStatusCode() >= 500) {
                $errorCounter = $this->registry->getCounter('app', 'http_errors_total');
                $errorCounter->inc($labels);
            }

            // Update application uptime
            $uptime = microtime(true) - $this->startTime;
            $gauge = $this->registry->getGauge('app', 'uptime_seconds');
            $gauge->set($uptime, ['instance' => gethostname()]);
        } catch (\Exception $e) {
            // Log error but don't break the application
            error_log("Prometheus error: " . $e->getMessage());
        }

        return $response;
    }
}
