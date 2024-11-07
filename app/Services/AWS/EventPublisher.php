<?php

namespace App\Services\AWS;

use Aws\Sns\SnsClient;

use Illuminate\Config\Repository;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;


class EventPublisher implements EventPublisherInterface
{
    public function __construct(
        private SnsClient $client,
        private Repository $config
    ){}

    public function publish(string $subject, array $payload)
    {
        try {
            $response = $this->client->publish([
                'Message' => json_encode($payload),
                'Subject' => $subject,
                'TargetArn' => $this->config->get("services.sns.topic"),
                'MessageAttributes' => [
                    'Publisher' => [
                        'DataType' => 'String',
                        'StringValue' => $this->config->get('app.name'),
                    ],
                    'Event' => [
                        'DataType' => 'String',
                        'StringValue' => $subject,
                    ],
                ],
            ]);

            Log::info('Aws::SnsClient => Response', array_merge(
                ['MessageId' => $response['MessageId']], $response['@metadata']
            ));

            return $response['@metadata']['statusCode'] == 200;
        } catch (AwsException $e) {
            return $e->getMessage();
        }
    }
}
