<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booking = new \App\Models\Lab\Booking(['status' => \App\Enums\RequestStatus::Submitted]);

// Test class_exists
$guessed = 'App\\Policies\\' . class_basename($booking) . 'Policy';
echo "Guessed class: $guessed" . PHP_EOL;
echo "Class exists: " . (class_exists($guessed) ? 'yes' : 'no') . PHP_EOL;

// Explicit policy registration
\Illuminate\Support\Facades\Gate::policy(\App\Models\Lab\Booking::class, \App\Policies\BookingPolicy::class);
$policy = \Illuminate\Support\Facades\Gate::getPolicyFor($booking);
echo "After explicit registration, Policy: " . ($policy ? get_class($policy) : 'null') . PHP_EOL;
