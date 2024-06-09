<?php

namespace App\Response;

use App\Response\Interface\ResponseInterface;
use App\Response\Interface\ResponseTypesInterface;

class ResponseFactory
{
    /**
     * @param string $type
     * @return ResponseInterface
     */
    public static function create(string $type = ResponseTypesInterface::JSON): ResponseInterface
    {
        if (!in_array($type, (new \ReflectionClass(ResponseTypesInterface::class))->getConstants())) {
            throw new \InvalidArgumentException('Response type must be instance of ' . ResponseTypesInterface::class);
        }

        return match ($type) {
            'json' => new JsonResponse(),
            default => throw new \InvalidArgumentException('Unknown response type'),
        };
    }
}
