<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CollectorRegistry::class, function () {
            return new CollectorRegistry(new InMemory());
        });
    }

    public function boot()
    {
        // Initialize metrics collectors
        $registry = $this->app->make(CollectorRegistry::class);
        
        // Counter for total HTTP requests
        $counter = $registry->registerCounter('app', 'http_requests_total', 'Total number of HTTP requests', ['method', 'path']);
        
        // Counter for HTTP errors (500)
        $errorCounter = $registry->registerCounter('app', 'http_errors_total', 'Total number of HTTP 500 errors', ['method', 'path']);
        
        // Gauge for application uptime
        $gauge = $registry->registerGauge('app', 'uptime_seconds', 'Application uptime in seconds', ['instance']);
        $gauge->set(0, ['instance' => gethostname()]);
    }
}
