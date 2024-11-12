<?php

namespace App\Services\AWS\Queues;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SqsQueue;
use App\Services\AWS\Queues\Job;

class Queue extends SqsQueue
{
    protected $routes;

    public function __construct($sqs, $default, $prefix = '', $routes = [])
    {
        parent::__construct($sqs, $default, $prefix);
        $this->routes = $routes;
    }

    /**
     * Extrae el siguiente trabajo de la cola.
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);

        $response = $this->sqs->receiveMessage([
            'QueueUrl' => $queue,
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);

        if (is_array($response['Messages']) && count($response['Messages']) > 0) {
            $message = $response['Messages'][0];

            if ($this->routeExists($message) || $this->classExists($message)) {
                return new Job(
                    $this->container,
                    $this->sqs,
                    $message,
                    $this->connectionName,
                    $queue,
                    $this->routes
                );
            } else {
                $this->sqs->deleteMessage([
                    'QueueUrl' => $queue, // REQUIRED
                    'ReceiptHandle' => $response['Messages'][0]['ReceiptHandle'] // REQUIRED
                ]);
            }
        }
    }

    protected function skipSourceMessage($message)
    {
        return isset($message['MessageAttributes']['Publisher']['StringValue']) &&
                $message['MessageAttributes']['Publisher']['StringValue'] === Config::get('app.name');
    }

    protected function routeExists($message)
    {
        $body = json_decode($message['Body'], true);
        return isset($this->routes[$body['Subject'] ?? '']);
    }

    protected function classExists($message)
    {
        $body = json_decode($message['Body'], true);
        return isset($body['data']['commandName']) && class_exists($body['data']['commandName']);
    }
}
