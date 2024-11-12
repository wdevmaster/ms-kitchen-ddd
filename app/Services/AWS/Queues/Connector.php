<?php

namespace App\Services\AWS\Queues;

use Illuminate\Queue\Connectors\SqsConnector;
use Illuminate\Contracts\Queue\Queue as CoreQueue;
use Aws\Sqs\SqsClient;
use App\Services\AWS\Queues\Queue;

class Connector extends SqsConnector
{
    public function connect(array $config): CoreQueue
    {
        $sqs = new SqsClient([
            'region' => $config['region'],
            'version' => $config['version'],
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
        ]);

        return new Queue(
            $sqs,
            $config['queue'],
            $config['prefix'] ?? '',
            $config['routes'] ?? []
        );
    }
}
