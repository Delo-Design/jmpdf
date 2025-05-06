<?php

$vendor_dir = \dirname(__FILE__, 2) . '/libraries/vendor/';
$fonts_dir  = $vendor_dir . 'mpdf/mpdf/ttfonts';

$exclude    = 'DejaVuSans';

$files = scandir($fonts_dir);
foreach ($files as $file) {
    if (\in_array($file, ['.', '..'])) {
        continue;
    }

    $split = explode('.', $file);
    $ext   = array_pop($split);
    $name  = implode('.', $split);

    if ($name !== $exclude && (strpos($name, $exclude . '-') === false)) {
        unlink($fonts_dir . '/' . $file);
    }
}

echo "Clear fonts - complete" . PHP_EOL;
