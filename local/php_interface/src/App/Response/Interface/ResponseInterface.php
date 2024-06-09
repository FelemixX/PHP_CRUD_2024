<?php

namespace App\Response\Interface;

interface ResponseInterface
{
    /**
     * @param int $code
     * @return void
     */
    public function setStatusCode(int $code): void;

    /**
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers): void;

    /**
     * @param array $body
     * @return void
     */
    public function setBody(array $body): void;

    /**
     * @param bool $success
     * @return void
     */
    public function setSuccess(bool $success): void;

    /**
     * @param string|null $message
     * @return void
     */
    public function setMessage(?string $message): void;

    /**
     * @param array|null $data
     * @return void
     */
    public function setData(?array $data): void;

    /**
     * @return void
     */
    public function send(): void;
}
