<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/admin/tahun-ajaran', 'GET');
$user = \App\Models\User::where('role', 'admin')->first();
$app->make('auth')->login($user);
$request->setUserResolver(function() use ($user) { return $user; });

// run through http kernel to trigger web middleware
$response = $kernel->handle($request);
echo "STATUS: " . $response->getStatusCode() . "\n";
echo "CONTENT: \n" . substr($response->getContent(), 0, 500) . "...\n";


