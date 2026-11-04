<?php

$textTitleField = [
    'type' => 'text',
    'norms' => false,
    'fields' => [
        'keyword' => [
            'type' => 'keyword',
            'ignore_above' => 256,
        ],
    ],
];

$textFreqsField = [
    'type' => 'text',
    'norms' => false,
    'index_options' => 'freqs',
];

$keywordField = [
    'type' => 'keyword',
];

$longField = [
    'type' => 'long',
];

$integerField = [
    'type' => 'integer',
];

$floatField = [
    'type' => 'float',
];

$booleanField = [
    'type' => 'boolean',
];

$dateField = [
    'type' => 'date',
    'format' => 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_second||strict_date_optional_time',
];

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
    | Supported: "algolia", "elasticsearch", "elastic", "typesense",
    |            "database", "collection", "null"
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
    | Elasticsearch Configuration (5 Golden Rules Optimized)
    |--------------------------------------------------------------------------
    |
    | 1. Filter Context (bool.filter) cho toàn bộ điều kiện cố định (_score = 0).
    | 2. Mapping chuẩn keyword (ID, mã, email, status, sort) vs text (nội dung).
    | 3. Hỗ trợ phân trang search_after thay vì deep from/size.
    | 4. Giới hạn dữ liệu trả về bằng _source filtering và fields.
    | 5. Tắt norms: false và dùng index_options: "freqs" cho trường text phụ.
    |
    */

    'elasticsearch' => [
        'host' => env('ELASTICSEARCH_HOST', 'http://localhost:9200'),
        'username' => env('ELASTICSEARCH_USERNAME'),
        'password' => env('ELASTICSEARCH_PASSWORD'),
        'api_key' => env('ELASTICSEARCH_API_KEY'),
        'max_from_window' => (int) env('ELASTICSEARCH_MAX_FROM_WINDOW', 1000),
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
                '_source' => ['id', 'name', 'facility_id', 'sport_type_id', 'status', 'district'],
                'fields' => ['id', 'facility_id', 'sport_type_id', 'status', 'district'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'facility_id' => $longField,
                        'sport_type_id' => $longField,
                        'name' => $textTitleField,
                        'description' => $textFreqsField,
                        'facility_name' => $textFreqsField,
                        'sport_type_name' => $textFreqsField,
                        'address' => $textFreqsField,
                        'district' => $keywordField,
                        'status' => $keywordField,
                        'capacity' => $integerField,
                        'created_at' => $dateField,
                    ],
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
                    'slug',
                    'category',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'views',
                    'created_at',
                ],
                '_source' => ['id', 'title', 'slug', 'category', 'status', 'views'],
                'fields' => ['id', 'slug', 'category', 'status', 'views'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'title' => $textTitleField,
                        'summary' => $textFreqsField,
                        'content' => $textFreqsField,
                        'author_name' => $textFreqsField,
                        'slug' => $keywordField,
                        'category' => $keywordField,
                        'status' => $keywordField,
                        'views' => $integerField,
                        'created_at' => $dateField,
                    ],
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
                '_source' => ['id', 'name', 'team', 'season', 'cost_price', 'is_in_stock', 'is_active'],
                'fields' => ['id', 'season', 'cost_price', 'is_in_stock', 'is_active'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'name' => $textTitleField,
                        'description' => $textFreqsField,
                        'team' => [
                            'type' => 'text',
                            'norms' => false,
                            'index_options' => 'freqs',
                            'fields' => [
                                'keyword' => $keywordField,
                            ],
                        ],
                        'season' => $keywordField,
                        'cost_price' => $floatField,
                        'is_in_stock' => $booleanField,
                        'is_active' => $booleanField,
                        'created_at' => $dateField,
                    ],
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
                    'phone',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'created_at',
                ],
                '_source' => ['id', 'name', 'district', 'phone', 'status'],
                'fields' => ['id', 'district', 'phone', 'status'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'name' => $textTitleField,
                        'address' => $textFreqsField,
                        'district' => $keywordField,
                        'phone' => $keywordField,
                        'status' => $booleanField,
                        'created_at' => $dateField,
                    ],
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
                '_source' => ['id', 'name', 'status'],
                'fields' => ['id', 'status'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'name' => $textTitleField,
                        'description' => $textFreqsField,
                        'status' => $booleanField,
                    ],
                ],
            ],
            'users' => [
                'searchableAttributes' => [
                    'name',
                    'email',
                    'phone',
                    'role',
                ],
                'filterableAttributes' => [
                    'id',
                    'email',
                    'phone',
                    'role',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'created_at',
                ],
                '_source' => ['id', 'name', 'email', 'phone', 'role', 'status'],
                'fields' => ['id', 'email', 'phone', 'role', 'status'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'name' => $textTitleField,
                        'email' => $keywordField,
                        'phone' => $keywordField,
                        'role' => $keywordField,
                        'status' => $booleanField,
                        'birthday' => $dateField,
                        'created_at' => $dateField,
                    ],
                ],
            ],
            'price_rules' => [
                'searchableAttributes' => [
                    'day_type',
                    'court_name',
                    'sport_type_name',
                ],
                'filterableAttributes' => [
                    'id',
                    'court_id',
                    'sport_type_id',
                    'day_type',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'price',
                    'created_at',
                ],
                '_source' => ['id', 'court_id', 'sport_type_id', 'day_type', 'price', 'status'],
                'fields' => ['id', 'court_id', 'sport_type_id', 'day_type', 'price', 'status'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'court_id' => $longField,
                        'sport_type_id' => $longField,
                        'day_type' => $keywordField,
                        'start_time' => $keywordField,
                        'end_time' => $keywordField,
                        'court_name' => $textFreqsField,
                        'sport_type_name' => $textFreqsField,
                        'price' => $floatField,
                        'status' => $booleanField,
                        'created_at' => $dateField,
                    ],
                ],
            ],
            'plans' => [
                'searchableAttributes' => [
                    'name',
                ],
                'filterableAttributes' => [
                    'id',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'price',
                    'duration_days',
                    'created_at',
                ],
                '_source' => ['id', 'name', 'duration_days', 'price', 'discount_percent', 'status'],
                'fields' => ['id', 'duration_days', 'price', 'discount_percent', 'status'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'name' => $textTitleField,
                        'duration_days' => $integerField,
                        'price' => $floatField,
                        'discount_percent' => $integerField,
                        'status' => $booleanField,
                        'created_at' => $dateField,
                    ],
                ],
            ],
            'subscriptions' => [
                'searchableAttributes' => [
                    'user_name',
                    'plan_name',
                ],
                'filterableAttributes' => [
                    'id',
                    'user_id',
                    'plan_id',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'price',
                    'created_at',
                ],
                '_source' => ['id', 'user_id', 'plan_id', 'price', 'status'],
                'fields' => ['id', 'user_id', 'plan_id', 'price', 'status'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'user_id' => $longField,
                        'plan_id' => $longField,
                        'user_name' => $textFreqsField,
                        'plan_name' => $textFreqsField,
                        'price' => $floatField,
                        'status' => $booleanField,
                        'starts_at' => $dateField,
                        'expires_at' => $dateField,
                        'created_at' => $dateField,
                    ],
                ],
            ],
            'ads' => [
                'searchableAttributes' => [
                    'name',
                    'description',
                    'link_url',
                ],
                'filterableAttributes' => [
                    'id',
                    'is_active',
                ],
                'sortableAttributes' => [
                    'id',
                    'created_at',
                ],
                '_source' => ['id', 'name', 'link_url', 'is_active'],
                'fields' => ['id', 'link_url', 'is_active'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'name' => $textTitleField,
                        'description' => $textFreqsField,
                        'link_url' => $keywordField,
                        'is_active' => $booleanField,
                        'created_at' => $dateField,
                    ],
                ],
            ],
            'bookings' => [
                'searchableAttributes' => [
                    'customer_name',
                    'customer_phone',
                    'customer_email',
                    'court_name',
                    'note',
                ],
                'filterableAttributes' => [
                    'id',
                    'user_id',
                    'court_id',
                    'customer_phone',
                    'customer_email',
                    'status',
                    'booking_date',
                ],
                'sortableAttributes' => [
                    'id',
                    'total_price',
                    'created_at',
                ],
                '_source' => ['id', 'user_id', 'court_id', 'customer_name', 'customer_phone', 'status', 'booking_date', 'total_price'],
                'fields' => ['id', 'user_id', 'court_id', 'customer_phone', 'status', 'booking_date', 'total_price'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'user_id' => $longField,
                        'court_id' => $longField,
                        'customer_name' => $textFreqsField,
                        'customer_phone' => $keywordField,
                        'customer_email' => $keywordField,
                        'court_name' => $textFreqsField,
                        'note' => $textFreqsField,
                        'status' => $keywordField,
                        'booking_date' => $dateField,
                        'start_time' => $keywordField,
                        'end_time' => $keywordField,
                        'total_price' => $floatField,
                        'created_at' => $dateField,
                    ],
                ],
            ],
            'reviews' => [
                'searchableAttributes' => [
                    'content',
                    'user_name',
                    'court_name',
                ],
                'filterableAttributes' => [
                    'id',
                    'user_id',
                    'court_id',
                    'booking_id',
                    'rating',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'rating',
                    'created_at',
                ],
                '_source' => ['id', 'user_id', 'court_id', 'rating', 'status'],
                'fields' => ['id', 'user_id', 'court_id', 'rating', 'status'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'user_id' => $longField,
                        'court_id' => $longField,
                        'booking_id' => $longField,
                        'content' => $textFreqsField,
                        'user_name' => $textFreqsField,
                        'court_name' => $textFreqsField,
                        'rating' => $integerField,
                        'status' => $booleanField,
                        'created_at' => $dateField,
                    ],
                ],
            ],
            'sales' => [
                'searchableAttributes' => [
                    'customer_name',
                    'customer_phone',
                    'product_name',
                    'status',
                ],
                'filterableAttributes' => [
                    'id',
                    'product_id',
                    'user_id',
                    'customer_phone',
                    'status',
                    'sale_date',
                ],
                'sortableAttributes' => [
                    'id',
                    'total_price',
                    'created_at',
                ],
                '_source' => ['id', 'product_id', 'user_id', 'customer_name', 'customer_phone', 'status', 'total_price'],
                'fields' => ['id', 'product_id', 'user_id', 'customer_phone', 'status', 'total_price'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'product_id' => $longField,
                        'user_id' => $longField,
                        'customer_name' => $textFreqsField,
                        'customer_phone' => $keywordField,
                        'product_name' => $textFreqsField,
                        'status' => $keywordField,
                        'quantity' => $integerField,
                        'total_price' => $floatField,
                        'sale_date' => $dateField,
                        'created_at' => $dateField,
                    ],
                ],
            ],
            'payments' => [
                'searchableAttributes' => [
                    'transaction_code',
                    'payment_method',
                    'user_name',
                    'note',
                    'payable_type',
                    'status',
                ],
                'filterableAttributes' => [
                    'id',
                    'transaction_code',
                    'payable_type',
                    'payable_id',
                    'user_id',
                    'payment_method',
                    'status',
                ],
                'sortableAttributes' => [
                    'id',
                    'amount',
                    'created_at',
                ],
                '_source' => ['id', 'transaction_code', 'payable_type', 'payable_id', 'user_id', 'payment_method', 'status', 'amount'],
                'fields' => ['id', 'transaction_code', 'payable_type', 'payable_id', 'user_id', 'payment_method', 'status', 'amount'],
                'mappings' => [
                    'properties' => [
                        'id' => $longField,
                        'payable_id' => $longField,
                        'user_id' => $longField,
                        'transaction_code' => $keywordField,
                        'payable_type' => $keywordField,
                        'payment_method' => $keywordField,
                        'status' => $keywordField,
                        'user_name' => $textFreqsField,
                        'note' => $textFreqsField,
                        'amount' => $floatField,
                        'paid_at' => $dateField,
                        'created_at' => $dateField,
                    ],
                ],
            ],
        ],
    ],

];
