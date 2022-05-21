<?php

namespace Lazarus;

class LazarusLogger
{
    /**
     * @param LazarusService $service
     */
    public function __construct(protected LazarusService $service)
    {
    }

    /**
     * @param array $params
     * @return void
     */
    public function log(array $params): void
    {
        $this->service->send($params);
    }
}
