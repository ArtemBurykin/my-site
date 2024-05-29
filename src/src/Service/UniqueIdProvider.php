<?php

namespace App\Service;

class UniqueIdProvider implements UniqueIdProviderInterface
{
    public function get(): string
    {
        return uniqid();
    }
}
