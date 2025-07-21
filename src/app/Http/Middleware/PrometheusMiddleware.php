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

        // Increment total requests counter
        $this->registry->getCounter('app', 'http_requests_total')
            ->inc();

        // If error occurred, increment error counter
        if ($response instanceof Response && $response->getStatusCode() >= 500) {
            $this->registry->getCounter('app', 'http_errors_total')
                ->inc();
        }

        // Update application uptime
        $uptime = microtime(true) - $this->startTime;
        $this->registry->getGauge('app', 'uptime_seconds')
            ->set($uptime);

        return $response;
    }
}
