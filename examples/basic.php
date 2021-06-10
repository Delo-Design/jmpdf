<?php

// Базовый запуск mpdf

$html = '<b>Мой текст какой-то</b>' .
$filename = 'example.pdf';
JLoader::register('JMpdf', JPATH_LIBRARIES . '/mpdf/jmpdf.php');
$pdf = new JMpdf($html);
$pdf->download($filename);