<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{
    public function __invoke(CacheRepository $cache): Response
    {
        $lines = [];
        $now = time();

        $scrapeCount = (int) $cache->increment('metrics:scrape_total');

        $dbUp = 0;
        try {
            DB::select('select 1');
            $dbUp = 1;
        } catch (\Throwable) {
            $dbUp = 0;
        }

        $metrics = [
            [
                'help' => 'Application health status (1 = up)',
                'name' => 'laravel_app_up',
                'type' => 'gauge',
                'value' => 1,
            ],
            [
                'help' => 'Database connectivity status (1 = reachable)',
                'name' => 'laravel_db_up',
                'type' => 'gauge',
                'value' => $dbUp,
            ],
            [
                'help' => 'Total number of /metrics scrapes',
                'name' => 'laravel_metrics_scrape_total',
                'type' => 'counter',
                'value' => $scrapeCount,
            ],
            [
                'help' => 'Unix timestamp when metrics were generated',
                'name' => 'laravel_metrics_generated_at_seconds',
                'type' => 'gauge',
                'value' => $now,
            ],
            [
                'help' => 'Current PHP memory usage in bytes',
                'name' => 'laravel_php_memory_usage_bytes',
                'type' => 'gauge',
                'value' => memory_get_usage(true),
            ],
            [
                'help' => 'Peak PHP memory usage in bytes',
                'name' => 'laravel_php_memory_peak_usage_bytes',
                'type' => 'gauge',
                'value' => memory_get_peak_usage(true),
            ],
            [
                'help' => 'PHP version info label metric',
                'name' => 'laravel_php_info',
                'type' => 'gauge',
                'value' => 1,
                'labels' => [
                    'version' => PHP_VERSION,
                    'sapi' => PHP_SAPI,
                ],
            ],
            [
                'help' => 'Application environment label metric',
                'name' => 'laravel_app_info',
                'type' => 'gauge',
                'value' => 1,
                'labels' => [
                    'env' => (string) config('app.env', 'unknown'),
                    'name' => (string) config('app.name', 'laravel'),
                ],
            ],
        ];

        foreach ($metrics as $metric) {
            $name = $metric['name'];
            $lines[] = sprintf('# HELP %s %s', $name, $metric['help']);
            $lines[] = sprintf('# TYPE %s %s', $name, $metric['type']);

            $labels = $this->buildLabels($metric['labels'] ?? []);
            $lines[] = sprintf('%s%s %s', $name, $labels, $metric['value']);
        }

        return response(implode("\n", $lines), 200)
            ->header('Content-Type', 'text/plain; version=0.0.4; charset=utf-8');
    }

    private function buildLabels(array $labels): string
    {
        if ($labels === []) {
            return '';
        }

        $pairs = [];
        foreach ($labels as $key => $value) {
            $escapedValue = str_replace(
                ['\\', '"', "\n"],
                ['\\\\', '\\"', '\\n'],
                (string) $value
            );
            $pairs[] = sprintf('%s="%s"', $key, $escapedValue);
        }

        return '{'.implode(',', $pairs).'}';
    }
}
