<?php

// Run: php check-users.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$users = User::with('roles')->get();

echo "Total users in database: " . $users->count() . "\n\n";

if ($users->count() > 0) {
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
        echo "---\n";
    }
} else {
    echo "No users found. Please register first:\n";
    echo "POST http://127.0.0.1:8000/api/register\n";
}
