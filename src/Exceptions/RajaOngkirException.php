<?php

namespace BlissJaspis\RajaOngkir\Exceptions;

use Illuminate\Http\Client\RequestException;
use RuntimeException;
use Throwable;

class RajaOngkirException extends RuntimeException
{
    /**
     * @param  array<string, mixed>|null  $response
     */
    public function __construct(
        string $message,
        public readonly ?int $statusCode = null,
        public readonly ?array $response = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public static function fromRequestException(RequestException $exception): self
    {
        $httpResponse = $exception->response;
        $body = $httpResponse->json();

        $message = $exception->getMessage();

        if (is_array($body)) {
            $message = (string) (
                $body['meta']['message']
                ?? $body['message']
                ?? $body['error']
                ?? $message
            );
        }

        return new self(
            message: $message,
            statusCode: $httpResponse->status(),
            response: is_array($body) ? $body : null,
            previous: $exception,
        );
    }
}
