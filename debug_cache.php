<?php

use App\Models\Setting;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$obj = Setting::all();
var_dump(unserialize(serialize($obj)));
