<?php

namespace App\Tests\Utils;

use App\Service\UniqueIdProviderInterface;

class UniqueIdProviderStub implements UniqueIdProviderInterface
{
    private string $id;

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function get(): string
    {
        if (isset($this->id)) {
            return $this->id;
        }

        return uniqid();
    }
}
