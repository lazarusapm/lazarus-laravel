<?php

namespace Lazarus\Laravel;

class LazarusLogger
{
    protected $service;

    public function __construct(LazarusService $service)
    {
        $this->service = $service;
    }

    public function log(array $params): void
    {
        $this->service->send($params);
    }
}
