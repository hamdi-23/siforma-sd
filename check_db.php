<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "Users: " . \App\Models\User::count() . "\n";
echo "Teachers: " . \App\Models\Teacher::count() . "\n";
echo "Attendances: " . \App\Models\Attendance::count() . "\n";
echo "Daily Reports: " . \App\Models\DailyReport::count() . "\n";
echo "\n✅ Database setup completed!\n";
