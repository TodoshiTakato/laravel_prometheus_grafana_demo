<?php

namespace App\Http\Controllers;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;

class MetricsController extends Controller
{
    private $registry;

    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function metrics()
    {
        $renderer = new RenderTextFormat();
        $result = $renderer->render($this->registry->getMetricFamilySamples());

        return response($result, 200, ['Content-Type' => RenderTextFormat::MIME_TYPE]);
    }
}
