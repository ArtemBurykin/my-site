<?php

namespace App\Service;

/**
 * The service to provide unique ids, it is here, because we need to mock it in order to get predictable ids in tests.
 * It is used in the file uploader.
 */
interface UniqueIdProviderInterface
{
    public function get(): string;
}
