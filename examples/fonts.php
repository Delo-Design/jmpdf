<?php

/**
 * Пример загрузки своих шрифтов
 * Шрифты должны загружаться только TrueType
 * Полное описание можете получить на официальной документации mpdf: https://mpdf.github.io/fonts-languages/fonts-in-mpdf-7-x.html
 */


$html = '<span style="font-family: alaruss">Мой текст какой-то с шрифтом alaruss</span> ' .
	'<span style="font-family: other">Мой текст какой-то с шрифтом other</span>';

$filename = 'example.pdf';

// регистрируем класс JMpdf
JLoader::register('JMpdf', JPATH_LIBRARIES . '/mpdf/jmpdf.php');
$pdf = new JMpdf($html);

$pdf->addFonts(
	JPATH_THEMES . '/mytemplate/fonts', //путь до шрифтов. Допустимо передать массив путей

	// карта шрифтов в виде массива
	[
		"alaruss" => [
			'R'  => "A_La_Russ.ttf", // regular
			'B'  => "A_La_Russ.ttf", // bold
			'I'  => "A_La_Russ.ttf", // italic
			'BI' => "A_La_Russ.ttf", // bold italic
		],
		"other"   => [
			'R'  => "Other_TruType.ttf", // regular
			'B'  => "Other_TruType_Bold.ttf", // bold
			'I'  => "Other_TruType_Italic.ttf", // italic
			'BI' => "Other_TruType_Bold_Italic.ttf", // bold italic
		]
	]
);


$pdf->stream($filename);