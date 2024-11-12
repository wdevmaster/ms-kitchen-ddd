<?php

namespace App\Services\AWS\Events;

interface EventPublisherInterface
{
    public function publish(string $subject, array $payload);
}
