# JMpdf
Mpdf for Joomla!

## Mpdf
- Ссылка на github: https://github.com/mpdf/mpdf
- Ссылка на документацию: https://mpdf.github.io

## Примеры запуска
Вы можете посмотреть в папке examples примеры запуска класса JMpdf.
 
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


## Сборка из исходного кода
В git репозитории нет всех библиотек. Они выкачиваются из composer. Полные дампы лежат в релизах на github и на сервере обновлений hika.su для Joomla!
Чтобы получить конечный архив надо чтобы у вас на той машине где вы собираете должен быть установлен composer. Пропишите название композера вашего в cli/config.php
После этого запустите скрипт обновления: <br/>
```php cli/update.php```

После этого скрипт сборки архива: <br/>
```php cli/build.php```

Чтобы очистить от лишних шрифтов, запускайте (этого делать не надо, если запускаете update.php):
```php cli/clearFonts.php```


## Методы JMpdf
Аргументы расписаны в phpdoc в классе JMpdf.

- setProtection - установить пароль
- output - возвращает строку 
- save - сохранить в файл
- download - скачать
- stream - отобразить в браузере
- addFonts - добавить шрифты


## Доступ ко всему объекту mpdf
Есть магический метод __call в JMpdf.
Работает следующим образом: когда вы вызываете метод которого не существует в классе JMpdf, то магический метод переадресует вызов метода к объекту mpdf внутри JMpdf и метод вызывается, если он существует у mpdf.<br/><br/>
Смотрите на официальной документации: https://mpdf.github.io <br/>
Список функций: https://mpdf.github.io/reference/mpdf-functions/overview.html