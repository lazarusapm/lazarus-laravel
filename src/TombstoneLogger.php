<?php

namespace Tombstone\Laravel;

class TombstoneLogger
{
    protected $service;

    public function __construct(TombstoneService $service)
    {
        $this->service = $service;
    }

    public function log(array $params): void
    {
        $this->service->send($params);
    }
}
