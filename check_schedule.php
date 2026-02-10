<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Schedule;
use Carbon\Carbon;

$schedule = Schedule::where('type', 'upload_proposal')->first();

if ($schedule) {
    echo "ID: " . $schedule->id . "\n";
    echo "Name: " . $schedule->name . "\n";
    echo "Type: " . $schedule->type . "\n";
    echo "Start: " . $schedule->start_date . "\n";
    echo "End: " . $schedule->end_date . "\n";
    echo "Active (is_active): " . ($schedule->is_active ? 'Yes' : 'No') . "\n";
    echo "Is Ongoing: " . ($schedule->isOngoing() ? 'Yes' : 'No') . "\n";
    echo "Is Past: " . ($schedule->isPast() ? 'Yes' : 'No') . "\n";
    echo "Now: " . Carbon::now() . "\n";
} else {
    echo "No schedule found\n";
}
