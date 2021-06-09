<?php

$path  = __DIR__ . '/../libraries/vendor/mpdf/mpdf/ttfonts';
$saved = 'DejaVuSans';

$files = scandir($path);

foreach ($files as $file)
{

	if (in_array($file, ['.', '..']))
	{
		continue;
	}

	$split = explode('.', $file);
	$ext   = array_pop($split);
	$name  = implode('.', $split);

	if ($name !== $saved && (strpos($name, $saved . '-') === false))
	{
		unlink($path . '/' . $file);
	}

}

echo "clear fonts end \n";