# jmpdf
Mpdf for Joomla!

## Mpdf
- Ссылка на github: https://github.com/mpdf/mpdf
- Ссылка на документацию: https://mpdf.github.io

## JMpdf
Для удобства написана обертка для mpdf.

Загрузка и вызов:

JLoader::register('JMpdf', JPATH_LIBRARIES . '/mpdf/jmpdf.php');

$pdf = JMpdf::getPdf('Hello world');


## Методы JMpdf
- static::getPDF($html = '', $config = [])

## Параметры для обертки JMpdf::getPDF($html, $config);
Параметры
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


Метод возвращает класс PDF с методами:
- setProtection
- output
- save
- download
- stream
     
     
## Подключить без обертки
JLoader::registerNamespace('Mpdf', JPATH_LIBRARIES . '/mpdf/src');