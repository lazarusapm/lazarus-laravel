<?php

namespace Tombstone\Laravel;

use Zttp\Zttp;

class TombstoneService
{
    private const TOKEN_NAME = 'X-TOMBSTONE-TOKEN';

    protected $config;

    private static $endpoint = 'https://mellow-beijing-wineivpw4qsr.vapor-farm-a1.com/api/log';

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function log(array $params): void
    {
        if ($this->shouldSend($params)) {
            $this->send($this->filterParams($params));
        }
    }

    protected function shouldSend(array $params): bool
    {
        return $this->config['enabled'] === true && !in_array($params['route'], $this->config['exclude'], true);
    }

    protected function filterParams(array $params): array
    {
        if (!$this->config['ips']) {
            unset($params['ips']);
        }

        return $params;
    }

    protected function send(array $params): void
    {
        Zttp::withHeaders([
            'Accept' => 'application/json',
            self::TOKEN_NAME => $this->config['token'],
        ])->post(self::$endpoint, $params);
    }
}
