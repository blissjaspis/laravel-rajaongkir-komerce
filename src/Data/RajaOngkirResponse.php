<?php

namespace BlissJaspis\RajaOngkir\Data;

final class RajaOngkirResponse
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public readonly array $meta,
        public readonly mixed $data,
    ) {}

    /**
     * @param  array<string, mixed>  $response
     */
    public static function fromArray(array $response): self
    {
        if (isset($response['meta']) && is_array($response['meta'])) {
            return new self(
                meta: $response['meta'],
                data: $response['data'] ?? null,
            );
        }

        $meta = [];

        foreach (['message', 'code', 'status'] as $key) {
            if (array_key_exists($key, $response)) {
                $meta[$key] = $response[$key];
            }
        }

        return new self(
            meta: $meta,
            data: $response['data'] ?? null,
        );
    }

    public function successful(): bool
    {
        return $this->status() === 'success';
    }

    public function status(): ?string
    {
        $status = $this->meta['status'] ?? null;

        return is_string($status) ? $status : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'meta' => $this->meta,
            'data' => $this->data,
        ];
    }
}
