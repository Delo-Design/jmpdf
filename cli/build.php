<?php

$pathdir = __DIR__ . '/../';

// Enter the name to creating zipped directory
$zipcreated = "lib_jmpdf.zip";

$full_path = $pathdir . '/' . $zipcreated;

if (file_exists($full_path))
{
	unlink($full_path);
}

// Create new zip class
$zip = new ZipArchive;

if ($zip->open($full_path, ZipArchive::CREATE) === true)
{

	// Store the path into the variable
	$dir = opendir($pathdir);

	while ($file = readdir($dir))
	{
		if (is_file($pathdir . $file))
		{
			$zip->addFile($pathdir . $file, $file);
		}
	}

	$zip->close();
}


echo "build end \n";