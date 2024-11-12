<?php

namespace App\Services\AWS\Queues;

use Illuminate\Queue\Jobs\SqsJob;
use Illuminate\Container\Container;
use Illuminate\Queue\CallQueuedHandler;

class Job extends SqsJob
{
    protected $routes;

    public function __construct(Container $container, $sqs, array $job, $connectionName, $queue, $routes)
    {
        parent::__construct($container, $sqs, $job, $connectionName, $queue);
        $this->routes = $routes;
        $this->job = $this->resolveSnsSubscription($this->job, $routes);
    }

    protected function resolveSnsSubscription(array $job, array $routes)
    {
        $body = json_decode($job['Body'], true);
        $commandName = $routes[$body['Subject']] ?? null;

        if ($commandName) {
            $command = $this->makeCommand($commandName, $body);

            $job['Body'] = json_encode([
                'uuid' => $body['MessageId'],
                'displayName' => $commandName,
                'job' => CallQueuedHandler::class . '@call',
                'data' => compact('commandName', 'command'),
            ]);
        }

        return $job;
    }

    protected function makeCommand($commandName, $body)
    {
        $payload = json_decode($body['Message'], true);

        return serialize($this->container->make($commandName, [
            'subject' => $body['Subject'] ?? '',
            'payload' => $payload
        ]));
    }
}
