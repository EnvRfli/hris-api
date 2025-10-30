<?php

// Run: php assign-super-admin.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$email = $argv[1] ?? null;

if (!$email) {
    echo "Usage: php assign-super-admin.php <email>\n";
    echo "Example: php assign-super-admin.php admin@hris.com\n";
    exit(1);
}

$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User with email '{$email}' not found.\n";
    echo "Please register first with POST /api/register\n";
    exit(1);
}

// Remove all existing roles
$user->roles()->detach();

// Assign super_admin role
$user->assignRole('super_admin');

echo "✅ Super admin role assigned successfully to: {$user->email}\n";
echo "   User ID: {$user->id}\n";
echo "   Name: {$user->name}\n";
echo "\n";
echo "Now you can login with:\n";
echo "POST http://127.0.0.1:8000/api/login\n";
echo "{\n";
echo "  \"email\": \"{$user->email}\",\n";
echo "  \"password\": \"your_password\"\n";
echo "}\n";
