<?php

namespace Lazarus\Laravel;

use Exception;

class LazarusService
{
    private const TOKEN_LENGTH = 60;

    private const TOKEN_NAME = 'X-Lazarus-Token';

    private static $endpoint = 'https://heylazarus.com/api/log';

    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function send(array $params)
    {
        if ($this->canSend($params)) {
            $this->fireData($params);
        }
    }

    public function canSend(array $params)
    {
        return $this->enabled()
            && $this->configured()
            && $this->hasValidData($params);
    }

    public function enabled(): bool
    {
        return isset($this->config['enabled']) && $this->config['enabled'] === true;
    }

    public function configured(): bool
    {
        return isset($this->config['token']) && strlen($this->config['token']) === self::TOKEN_LENGTH;
    }

    public function hasValidData(array $params): bool
    {
        return isset($params['route'])
            && !empty($params['route'])
            && !in_array($params['route'], $this->config['exclude'] ?? [], true);
    }

    protected function filterParams(array $params): array
    {
        if (isset($this->config['ips']) && $this->config['ips'] === false) {
            unset($params['ips']);
        }

        return $params;
    }

    /**
     * Fire and forget data.
     */
    protected function fireData(array $params): void
    {
        try {
            $parts = parse_url(self::$endpoint);
            $data = json_encode($this->filterParams($params), 0);

            $fp = fsockopen('tls://'.$parts['host'], 443, $errno, $errstr, 30);

            $out = 'POST '.$parts['path']." HTTP/1.1\r\n";
            $out .= 'Host: '.$parts['host']."\r\n";
            $out .= "Content-Type: application/json\r\n";
            $out .= 'Content-Length: '.strlen($data)."\r\n";
            $out .= self::TOKEN_NAME.': '.$this->config['token']."\r\n";
            $out .= "Connection: Close\r\n\r\n";
            $out .= $data;

            fwrite($fp, $out);
            fclose($fp);
        } catch (Exception $e) {
            // fail silently for now
        }
    }
}
