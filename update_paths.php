<?php
$files = glob('app/Http/Controllers/Frontend/*.php');
foreach($files as $f) {
    $content = file_get_contents($f);
    $content = str_replace("Inertia::render('Frontend/", "Inertia::render('Public/", $content);
    file_put_contents($f, $content);
    echo "Updated $f\n";
}
