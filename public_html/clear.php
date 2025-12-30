<?php
require __DIR__.'/laravel-wa-marketplace/vendor/autoload.php';
$app = require __DIR__.'/laravel-wa-marketplace/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Clearing config cache...<br>";
$kernel->call('config:clear');

echo "Clearing view cache...<br>";
$kernel->call('view:clear');

echo "Clearing route cache...<br>";
$kernel->call('route:clear');

echo "<br>Done! Sekarang refresh website Anda.";
?>