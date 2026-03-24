<?php

namespace App\Services;

class MetricsService
{
    protected array $counters = [];

    public function increment(string $name, int $value = 1): void
    {
        if (! isset($this->counters[$name])) {
            $this->counters[$name] = 0;
        }

        $this->counters[$name] += $value;
    }

    public function addToCounter(string $name, int|float $value): void
    {
        if (! isset($this->counters[$name])) {
            $this->counters[$name] = 0;
        }

        $this->counters[$name] += $value;
    }

    public function all(): array
    {
        return $this->counters;
    }
}
