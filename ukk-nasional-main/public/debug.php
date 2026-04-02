<?php

use Illuminate\Support\Facades\DB;
use App\Models\Complaint;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h1>Debug Page - Pengaduan Prasarana Sekolah</h1>";

try {
    // Check DB Connection
    $dbName = DB::connection()->getDatabaseName();
    echo "<p style='color: green; font-weight: bold;'>✅ Database connected successfully!</p>";
    echo "<p>Database Type: " . DB::connection()->getDriverName() . "</p>";
    echo "<p>Database Path/Name: " . $dbName . "</p>";

    // Show Last 5 Complaints
    $latest = Complaint::with(['user'])->latest()->take(5)->get();
    echo "<h2>Latest 5 Complaints</h2>";
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%; border: 1px solid #ddd;'>";
    echo "<tr style='background: #f4f4f4;'><th>ID</th><th>User</th><th>Location</th><th>Title</th><th>Status</th></tr>";
    foreach($latest as $c) {
        echo "<tr><td>{$c->id}</td><td>{$c->user->name}</td><td>{$c->location}</td><td>{$c->title}</td><td>{$c->status}</td></tr>";
    }
    echo "</table>";

} catch (\Exception $e) {
    echo "<div style='padding: 1rem; background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c;'>";
    echo "<h3>❌ Database Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
