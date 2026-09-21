CDW Stadium Project
│
├── Cách 1: Docker 
│   ├── docker compose up -d
│   ├── docker compose exec app composer install
│   ├──  docker compose exec app php artisan key:generate
│   └──  docker compose exec app php artisan migrate --seed
│
└── Cách 2: WAMP/XAMPP
    ├── composer install
    ├── Tạo database laravel13
    ├── cấu hình .env
    ├── php artisan key:generate
    ├── php artisan migrate --seed
    └── php artisan serve

    cấu hình docker:
        .env
            DB_CONNECTION=mysql
            DB_HOST=db
            DB_PORT=3306
            DB_DATABASE=laravel13
            DB_USERNAME=root
            DB_PASSWORD=root