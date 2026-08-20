<?php

$dir = 'app/Http/Resources';
$files = glob($dir.'/*.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, '@mixin') !== false) {
        continue;
    }

    // Extract class name
    if (preg_match('/class ([A-Za-z0-9_]+Resource)/', $content, $matches)) {
        $className = $matches[1];
        $modelName = str_replace('Resource', '', $className);

        $mixin = "/**\n * @mixin \App\Models\\$modelName\n */\nclass";
        $newContent = preg_replace('/class/', $mixin, $content, 1);
        file_put_contents($file, $newContent);
        echo 'Updated '.$file.PHP_EOL;
    }
}
