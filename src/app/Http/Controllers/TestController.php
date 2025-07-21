<?php

namespace App\Http\Controllers;

use Prometheus\CollectorRegistry;

class TestController extends Controller
{
    public function test(CollectorRegistry $registry)
    {
        dd(get_class_methods($registry));
    }
} 