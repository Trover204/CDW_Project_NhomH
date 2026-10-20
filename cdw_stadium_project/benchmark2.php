<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Court;

$start = microtime(true);
for ($i=0; $i<100; $i++) {
    User::all();
    Court::all();
}
$timeAll = microtime(true) - $start;

$start = microtime(true);
for ($i=0; $i<100; $i++) {
    User::select('id', 'name', 'email')->get();
    Court::select('id', 'name')->get();
}
$timeSelect = microtime(true) - $start;

echo "Total users: " . User::count() . "\n";
echo "Total courts: " . Court::count() . "\n";
echo "User::all() + Court::all() time: " . ($timeAll * 1000) . " ms\n";
echo "User::select() + Court::select() time: " . ($timeSelect * 1000) . " ms\n";
