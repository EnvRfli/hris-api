<?php

// Run: php check-role.php <email>

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$email = $argv[1] ?? null;

if (!$email) {
    echo "Usage: php check-role.php <email>\n";
    echo "Example: php check-role.php admin@hris.com\n";
    exit(1);
}

$user = User::with('roles')->where('email', $email)->first();

if (!$user) {
    echo "❌ User with email '{$email}' not found.\n";
    exit(1);
}

echo "✅ User found!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "ID:    {$user->id}\n";
echo "Name:  {$user->name}\n";
echo "Email: {$user->email}\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if ($user->roles->count() > 0) {
    echo "Roles:\n";
    foreach ($user->roles as $role) {
        $icon = $role->name === 'super_admin' ? '👑' : 
                ($role->name === 'hr' ? '👔' : 
                ($role->name === 'manager' ? '📊' : '👤'));
        echo "  {$icon} {$role->display_name} ({$role->name})\n";
    }
    
    echo "\n";
    
    // Check permissions
    if ($user->hasRole('super_admin')) {
        echo "✅ This user is a SUPER ADMIN - has full access!\n";
    } elseif ($user->hasRole('hr')) {
        echo "✅ This user is HR - can manage employees\n";
    } elseif ($user->hasRole('manager')) {
        echo "✅ This user is a MANAGER - can approve leaves\n";
    } elseif ($user->hasRole('employee')) {
        echo "ℹ️  This user is an EMPLOYEE - has basic access\n";
    }
} else {
    echo "⚠️  No roles assigned yet!\n";
    echo "Run: php assign-super-admin.php {$email}\n";
}
