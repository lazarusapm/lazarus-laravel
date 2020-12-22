<?php

namespace Lazarus\Laravel;

use Laravel\Horizon\Events\JobDeleted;

class HorizonHelper
{
    public function buildJobPayload(JobDeleted $event): array
    {
        $timing = $this->buildTiming($event->payload->decoded['uuid']);

        return [
            'queue_name' => $event->queue,
            'connection_name' => $event->connectionName,
            'job_id' => $event->payload->decoded['uuid'],
            'display_name' => $event->payload->decoded['displayName'],
            'type' => $event->payload->decoded['type'],
            'tags' => json_encode($event->payload->decoded['tags']),
            'is_failed' => $event->job->hasFailed(),
            'pushed_at' => $this->formatMicroTime($event->payload->decoded['pushedAt']),
            'started_at' => $timing['started_at'],
            'completed_at' => $timing['completed_at'],
            'runtime' => $timing['runtime'],
            'waittime' => $timing['waittime'],
            'attempts' => $event->payload->decoded['attempts'],
            'backoff' => $event->payload->decoded['backoff'],
            'timeout' => $event->payload->decoded['timeout'],
            'max_tries' => $event->payload->decoded['maxTries'],
            'max_exceptions' => $event->payload->decoded['maxExceptions'],
            'retry_until' => $event->payload->decoded['retryUntil'],
            'command' => json_encode(unserialize($event->payload->decoded['data']['command'])),
            'command_name' => $event->payload->decoded['data']['commandName']
        ];
    }

    private function buildTiming(string $uuid): array
    {
        $redis = resolve(\Laravel\Horizon\Contracts\MetricsRepository::class);

        $times = $redis->connection()->transaction(function ($trans) use ($uuid) {
            $trans->hmget($uuid, ['created_at', 'reserved_at', 'completed_at']);
        });

        $clock = array_values($times[0]);

        return [
            'created_at' => $this->formatMicroTime($clock[0]),
            'started_at' => $this->formatMicroTime($clock[1]),
            'completed_at' => $this->formatMicroTime($clock[2]),
            'runtime' => $this->calculateDiffInMicroSeconds($clock[1], $clock[2]),
            'waittime' => $this->calculateDiffInMicroSeconds($clock[0], $clock[1]),
        ];
    }

    private function formatMicroTime($time)
    {
        $now = DateTime::createFromFormat('U.u', $time);
        return $now->format("Y-m-d H:i:s.u");
    }

    private function calculateDiffInMicroSeconds($start, $end)
    {
        $diff = $end - $start;
        $seconds = intval($diff);
        $micro = $diff - $seconds;

        return strftime('%S', mktime(0, 0, $seconds)) . str_replace('0.', '.', sprintf('%.2f', $micro));
    }
}