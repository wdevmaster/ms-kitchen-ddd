<?php

namespace App\Services\AWS;

interface EventPublisherInterface
{
    public function publish(string $subject, array $payload);
}
