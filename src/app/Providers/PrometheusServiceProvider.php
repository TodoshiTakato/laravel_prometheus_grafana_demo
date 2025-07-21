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
        $registry->getOrRegisterCounter('app', 'http_requests_total', 'Total number of HTTP requests')
            ->inc(0);
        
        // Counter for HTTP errors (500)
        $registry->getOrRegisterCounter('app', 'http_errors_total', 'Total number of HTTP 500 errors')
            ->inc(0);
        
        // Gauge for application uptime
        $registry->getOrRegisterGauge('app', 'uptime_seconds', 'Application uptime in seconds')
            ->set(0);
    }
}
