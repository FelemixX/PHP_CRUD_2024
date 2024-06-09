<?php

namespace App\Response;

use App\Response\Interface\ResponseInterface;
use App\Response\Interface\ResponseTypesInterface;

class JsonResponse implements ResponseInterface, ResponseTypesInterface
{
    private int $statusCode = 200;
    private array $headers = ['Content-Type' => 'application/json'];
    private ?array $body = [
        'success' => null,
        'message' => null,
        'data' => []
    ];

    /**
     * @inheritDoc
     */
    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
    }

    /**
     * @inheritDoc
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * @inheritDoc
     */
    public function setBody(array $body): void
    {
        $this->body = array_merge($this->body, $body);
    }

    /**
     * @inheritDoc
     */
    public function setSuccess(bool $success): void
    {
        $this->body['success'] = $success;
    }

    /**
     * @inheritDoc
     */
    public function setMessage(?string $message): void
    {
        $this->body['message'] = $message;
    }

    /**
     * @inheritDoc
     */
    public function setData(?array $data): void
    {
        $this->body['data'] = $data;
    }

    /**
     * @inheritDoc
     */
    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }

        echo json_encode($this->body);
    }
}
