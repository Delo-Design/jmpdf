<?php

$vendor_dir = dirname(__FILE__, 2) . '/libraries/vendor/';
$root_dir   = dirname(__FILE__, 2);

$lockfile_path = $root_dir . '/composer.lock';
$manifest_path = $root_dir . '/jmpdf.xml';

$lockfile = json_decode(file_get_contents($lockfile_path), true, 128, JSON_OBJECT_AS_ARRAY);
$manifest = file_get_contents($manifest_path);

$find_version = '';

foreach ($lockfile['packages'] as $package) {
    if ($package['name'] === 'mpdf/mpdf') {
        $find_version = str_replace('v', '', $package['version']);
    }
}

$manifest = preg_replace("#(<version>).*?(</version>)#isu", '${1}' . $find_version . '${2}', $manifest);

file_put_contents($manifest_path, $manifest);

echo "Manifest update - complete" . PHP_EOL;
