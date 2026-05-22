<?php

return [
    /**
     * RajaOngkir Komerce API Key
     */
    'api_key' => env('RAJAONGKIR_API_KEY', ''),

    /**
     * RajaOngkir Komerce Base URL
     */
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),

    /**
     * HTTP request timeout in seconds
     */
    'timeout' => (int) env('RAJAONGKIR_TIMEOUT', 30),

    /**
     * Number of times to retry failed HTTP requests
     */
    'retry_times' => (int) env('RAJAONGKIR_RETRY_TIMES', 0),

    /**
     * Milliseconds to wait between HTTP retries
     */
    'retry_sleep' => (int) env('RAJAONGKIR_RETRY_SLEEP', 100),

    /**
     * When true, all HTTP requests are faked (useful for local development)
     */
    'fake' => (bool) env('RAJAONGKIR_FAKE', false),
];
