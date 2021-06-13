<?php
# аргументы --composer /path/to
error_reporting(0);

$config        = include __DIR__ . '/config.php';
$path_plugin   = __DIR__ . '/../libraries';
$path          = __DIR__ . '/../libraries/vendor/mpdf/mpdf/ttfonts';
$manifest_path = __DIR__ . '/../jmpdf.xml';


if(isset($argv[2]))
{
	$composer_path = $argv[2];
}
else
{
	$composer_path = $config['composer_path'];
}

shell_exec('cd ' . $path_plugin . '; php ' . $composer_path . ' require mpdf/mpdf');


if (!file_exists($path_plugin))
{
	if (!mkdir($path_plugin) && !is_dir($path_plugin))
	{
		echo "failed to create libraries folder \n";
		die();
	}
}

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

echo "update end\n";

include __DIR__ . '/clearFonts.php';
//include __DIR__ . '/build.php';