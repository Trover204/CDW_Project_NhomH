<?php

declare(strict_types=1);

return [
    'default' => env('ELASTIC_CONNECTION', 'default'),
    'connections' => [
        'default' => array_filter([
            'hosts' => [
                env('ELASTIC_HOST', env('ELASTICSEARCH_HOST', 'http://elasticsearch:9200')),
            ],
            'basicAuthentication' => env('ELASTICSEARCH_USERNAME') ? [
                env('ELASTICSEARCH_USERNAME'),
                (string) env('ELASTICSEARCH_PASSWORD', ''),
            ] : null,
            'apiKey' => env('ELASTICSEARCH_API_KEY') ?: null,
        ], static fn ($value) => $value !== null),
    ],
];
