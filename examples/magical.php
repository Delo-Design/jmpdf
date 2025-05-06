<?php

/**
 * Обращение напрямую к mpdf
 * Полное API смотрите на официальной документации: https://mpdf.github.io/reference/mpdf-functions/overview.html
 */

$html     = '<b>Мой текст какой-то с шрифтом alaruss</b>';
$filename = 'example.pdf';
JLoader::registerNamespace('JMpdf', JPATH_LIBRARIES . '/mpdf/jmpdf.php');
$pdf = new \Joomla\Libraries\JMpdf\JMpdf($html);

$pdf->SetAuthor('Я автор этого pdf'); // обращаемся напрямую к mpdf с помощью магического метода __call, который реализован в JMpdf

$pdf->download($filename);
