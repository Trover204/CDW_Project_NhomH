<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$start = microtime(true);
for ($i=0; $i<100; $i++) {
    User::all();
}
$timeAll = microtime(true) - $start;

$start = microtime(true);
for ($i=0; $i<100; $i++) {
    User::select('id', 'name', 'email')->get();
}
$timeSelect = microtime(true) - $start;

echo "Total users: " . User::count() . "\n";
echo "User::all() time: " . ($timeAll * 1000) . " ms\n";
echo "User::select() time: " . ($timeSelect * 1000) . " ms\n";
