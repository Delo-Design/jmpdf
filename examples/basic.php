<?php

// Подключение неймспейса библиотеки
JLoader::registerNamespace('\\Joomla\\Libraries\\JMpdf', JPATH_LIBRARIES . '/mpdf/src');

$html = '<b>Мой текст какой-то</b>' .
$filename = 'example.pdf';

$pdf = new \Joomla\Libraries\JMpdf\JMpdf($html);
$pdf->stream(dirname(__FILE__) . '/' . $filename);