<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Search Engine
    |--------------------------------------------------------------------------
    |
    | This option controls the default search connection that gets used while
    | using Laravel Scout. This connection is used when syncing all models
    | to the search service. You should adjust this based on your needs.
    |
    | Supported: "algolia", "elasticsearch", "typesense", "database",
    |            "collection", "null"
    |
    */

    'driver' => env('SCOUT_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Index Prefix
    |--------------------------------------------------------------------------
    |
    | Here you may specify a prefix that will be applied to all searchable
    | index names used by Scout. This prefix may be useful if you have
    | multiple "tenants" or applications sharing the same search.
    |
    */

    'prefix' => env('SCOUT_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Queue Data Syncing
    |--------------------------------------------------------------------------
    |
    | This option allows you to control if the operations that sync your data
    | with your search engines are queued. When this is set to "true" then
    | all automatic data syncing will get queued for better performance.
    |
    */

    'queue' => env('SCOUT_QUEUE', false),

    /*
    |--------------------------------------------------------------------------
    | Database Transactions
    |--------------------------------------------------------------------------
    |
    | This configuration option determines if your data will be synced within
    | a database transaction. Setting this to "true" means Scout will wait
    | until all open database transactions are committed before syncing.
    |
    */

    'after_commit' => false,

    /*
    |--------------------------------------------------------------------------
    | Chunk Sizes
    |--------------------------------------------------------------------------
    |
    | These options allow you to control the maximum chunk size when you are
    | importing data into the search engine. This allows you to fine-tune
    | the batch sizes based on the memory limits of your server.
    |
    */

    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Soft Deletes
    |--------------------------------------------------------------------------
    |
    | This option allows to control whether to keep soft deleted records in
    | the search indexes. Maintaining soft deleted records can be useful
    | if your application still needs to search for the records later.
    |
    */

    'soft_delete' => false,

    /*
    |--------------------------------------------------------------------------
    | Identify User
    |--------------------------------------------------------------------------
    |
    | This option allows you to control whether to notify the search engine
    | of the current user performing the search. This is sometimes useful
    | for analytics and personalization on engines that support it.
    |
    */

    'identify' => env('SCOUT_IDENTIFY', false),

    /*
    |--------------------------------------------------------------------------
    | Elasticsearch Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Elasticsearch settings. Below, you can state
    | the host, authentication, and index settings for your own Elasticsearch
    | installation.
    |
    */

    'elasticsearch' => [
        'host' => env('ELASTICSEARCH_HOST', 'http://localhost:9200'),
        'username' => env('ELASTICSEARCH_USERNAME'),
        'password' => env('ELASTICSEARCH_PASSWORD'),
        'api_key' => env('ELASTICSEARCH_API_KEY'),
        'index-settings' => [
            'courts' => [
                'searchableAttributes' => [
                    'name',
                    'sport_type_name',
                    'facility_name',
                    'district',
                    'address',
                    'description',
                ],
                'filterableAttributes' => [
                    'id',
                    'facility_id',
                    'sport_type_id',
                    'status',
                    'district',
                ],
                'sortableAttributes' => [
                    'id',
                    'capacity',
                    'created_at',
                ],
            ],
            'news' => [
                'searchableAttributes' => [
                    'title',
                    'summary',
                    'content',
                    'category',
                    'author_name',
                ],
                'filterableAttributes' => [
                    'id',
                    'category',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'views',
                    'created_at',
                ],
            ],
            'products' => [
                'searchableAttributes' => [
                    'name',
                    'team',
                    'season',
                    'description',
                ],
                'filterableAttributes' => [
                    'id',
                    'team',
                    'season',
                    'is_in_stock',
                    'is_active',
                ],
                'sortableAttributes' => [
                    'id',
                    'cost_price',
                    'created_at',
                ],
            ],
            'facilities' => [
                'searchableAttributes' => [
                    'name',
                    'district',
                    'address',
                    'phone',
                ],
                'filterableAttributes' => [
                    'id',
                    'district',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'created_at',
                ],
            ],
            'sport_types' => [
                'searchableAttributes' => [
                    'name',
                    'description',
                ],
                'filterableAttributes' => [
                    'id',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                ],
            ],
        ],
    ],

];
