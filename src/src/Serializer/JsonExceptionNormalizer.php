<?php

namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JsonExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param FlattenException $exception
     */
    public function normalize($exception, ?string $format = null, array $context = []): array
    {
        return ['message' => $exception->getMessage()];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException && 'json' === $format;
    }

    public function getSupportedTypes(?string $format): array
    {
        return ['*' => true];
    }
}
