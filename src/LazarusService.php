<?php

namespace Lazarus;

use Exception;

class LazarusService
{
    private const TOKEN_LENGTH = 60;

    private const TOKEN_NAME = 'X-Lazarus-Token';

    /**
     * @var string
     */
    private static string $endpoint = 'https://heylazarus.com/api/log';

    /**
     * @param array $config
     */
    public function __construct(protected array $config = [])
    {
    }

    /**
     * @param array $params
     * @return void
     */
    public function send(array $params): void
    {
        if ($this->canSend($params)) {
            $this->fireData($params);
        }
    }

    /**
     * @param array $params
     * @return bool
     */
    public function canSend(array $params): bool
    {
        return $this->enabled()
            && $this->configured()
            && $this->hasValidData($params);
    }

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return isset($this->config['enabled']) && $this->config['enabled'] === true;
    }

    /**
     * @return bool
     */
    public function configured(): bool
    {
        return isset($this->config['token']) && strlen($this->config['token']) === self::TOKEN_LENGTH;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function hasValidData(array $params): bool
    {
        return isset($params['route'])
            && !empty($params['route'])
            && !in_array($params['route'], $this->config['exclude'] ?? [], true);
    }

    /**
     * @param array $params
     * @return array
     */
    protected function filterParams(array $params): array
    {
        if (isset($this->config['ips']) && $this->config['ips'] === false) {
            unset($params['ips']);
        }

        return $params;
    }


    /**
     * Fire and forget data.
     *
     * @param array $params
     * @return void
     */
    protected function fireData(array $params): void
    {
        try {
            $endpoint = config('lazarus.endpoint') ?? self::$endpoint;

            $parts = parse_url($endpoint);
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
