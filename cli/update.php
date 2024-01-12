<?php
# аргументы --composer /path/to
//error_reporting(0);

$path_plugin   = __DIR__ . '/../libraries';
$path          = __DIR__ . '/../libraries/vendor/mpdf/mpdf/ttfonts';
$manifest_path = __DIR__ . '/../jmpdf.xml';

$manifest          = file_get_contents($manifest_path);
$composer_lock     = json_decode(file_get_contents($path_plugin . '/composer.lock'), JSON_OBJECT_AS_ARRAY);
$mpdf_find_version = '';

foreach ($composer_lock['packages'] as $package)
{
	if ($package['name'] === 'mpdf/mpdf')
	{
		$mpdf_find_version = str_replace('v', '', $package['version']);
	}
}

$manifest = preg_replace_callback("#\<version\>.*?\<\/version\>#isu", function ($matches) use ($mpdf_find_version) {
	return '<version>' . $mpdf_find_version . '</version>';
}, $manifest);

file_put_contents($manifest_path, $manifest);

unlink($path_plugin . '/composer.lock');
unlink($path_plugin . '/composer.json');

echo "update end\n";

include __DIR__ . '/clearFonts.php';