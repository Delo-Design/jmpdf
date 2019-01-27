# JMpdf
Mpdf for Joomla!

## Mpdf
- Ссылка на github: https://github.com/mpdf/mpdf
- Ссылка на документацию: https://mpdf.github.io

## JMpdf
Для удобства написана обертка для mpdf.

Загрузка и пример вызова:

JLoader::register('JMpdf', JPATH_LIBRARIES . '/mpdf/jmpdf.php');

$pdf = new JMpdf('Hello world');
$pdf->stream();
 
## Параметры конструктора JMpdf
1) html - html для записи в pdf
2) $config - параметры для mpdf, список параметров:
- mode                
- format           
- margin_left     
- margin_right     
- margin_top        
- margin_bottom     
- margin_header      
- margin_footer        
- tempDir
- author
- creator
- subject
- keywords
- display_mode
- instanceConfigurator


## Методы JMpdf
- setProtection
- output
- save
- download
- stream
     
     
## Подключить без обертки
JLoader::registerNamespace('Mpdf', JPATH_LIBRARIES . DS . 'mpdf');
