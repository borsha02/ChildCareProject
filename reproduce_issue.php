<?php

use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Starting reproduction...\n";

// Clean up previous test data if any (optional, be careful)
// User::where('email', 'testA@example.com')->delete();
// User::where('email', 'testB@example.com')->delete();

// 1. Create Test Child
$child = Child::first(); // Just pick the first child
if (!$child) {
    die("No child found.");
}
echo "Using Child: {$child->id} ({$child->first_name})\n";

// 2. Find two caregivers or create dummy ones (simpler to pick existing)
$caregivers = User::where('role', 'caregiver')->take(2)->get();
if ($caregivers->count() < 2) {
    die("Need at least 2 caregivers.");
}

$staffA = $caregivers[0];
$staffB = $caregivers[1];

echo "Staff A: {$staffA->id} ({$staffA->name})\n";
echo "Staff B: {$staffB->id} ({$staffB->name})\n";

// 3. Setup Initial State: A=Morning, B=Evening
$staffA->shift = 'morning';
$staffA->save();
$staffB->shift = 'evening';
$staffB->save();

// Assign both to child
$child->caregivers()->syncWithoutDetaching([$staffA->id, $staffB->id]);

echo "Assigned both. Initial check:\n";
foreach ($child->caregivers as $cg) {
    echo "- {$cg->name} ({$cg->shift})\n";
}

// 4. Simulate the Update: Change B to Morning
echo "\nChanging Staff B to Morning...\n";
$staffB->shift = 'morning';
$staffB->save(); // Save to DB so query can find it? 
// WAIT: In the controller code, we do $staff->update($validated) BEFORE the check.
// So yes, it is saved.

// 5. Run the Conflict Logic
echo "Running conflict logic for Staff B ({$staffB->shift})...\n";

$conflict = false;
$staff = $staffB;

// LOGIC FROM CONTROLLER
if ($staff->shift === 'full-time') {
    if ($child->caregivers()->where('users.id', '!=', $staff->id)->exists()) {
        $conflict = true;
        echo "Conflict: Full-time block.\n";
    }
} else {
    $fullTimeExists = $child->caregivers()->where('users.id', '!=', $staff->id)->where('shift', 'full-time')->exists();
    
    // Debug the query
    $query = $child->caregivers()->where('users.id', '!=', $staff->id)->where('shift', $staff->shift);
    echo "Query SQL: " . $query->toSql() . "\n";
    echo "Bindings: " . json_encode($query->getBindings()) . "\n";
    
    $sameShiftExists = $query->exists();
    
    echo "FullTimeExists: " . ($fullTimeExists ? 'yes' : 'no') . "\n";
    echo "SameShiftExists: " . ($sameShiftExists ? 'yes' : 'no') . "\n";

    if ($fullTimeExists || $sameShiftExists) {
        $conflict = true;
    }
}

if ($conflict) {
    echo "CONFLICT DETECTED! Would detach.\n";
    // $child->caregivers()->detach($staff->id);
} else {
    echo "NO CONFLICT DETECTED.\n";
}
