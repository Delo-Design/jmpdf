# JMpdf
Mpdf for Joomla!

- ✔ Устанавливается в Joomla стандартными средствами;
- ✔ Не вызывает конфликтов зависимостей c ядром Joomla;
- ✔ Оптимизированный загрузчик классов;
- ✔ Содержит облегчённую версию (удалены лишние шрифты)

## Mpdf
- Ссылка на github: https://github.com/mpdf/mpdf
- Ссылка на документацию: https://mpdf.github.io

## Сборки

В релизе содержится 2 сборки (2 архива):
- `lib_jmpdf.zip` - облегчённая сборки библиотеки, из которой удалены все дополнительные шрифты, кроме `DejaVuSans`;
- `lib_jmpdf_with_fonts.zip` - полная сборка, которая содержит абсолютно все шрифты из оригинальной библиотеки `mpdf/mpdf`.

## Изменения
Прочитать изменения вы можете в файле [changelog.md](https://github.com/Delo-Design/jmpdf/blob/master/changelog.md)

## Примеры запуска
В папке [examples](https://github.com/Delo-Design/jmpdf/blob/master/examples) содержатся примеры использования класса JMpdf.
 
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

## Самостоятельная сборка из исходных кодов

В git репозитории отсутствуют дополнительные библиотеки, требующиеся для работы пакета. Они устанавливаются, при помощи менеджера пакетов `composer`. 

Архивы в релизах на github и на сервере обновлений hika.su для Joomla уже содержат все зависимости и готовы к использованию.

Для самостоятельной сборки клонируйте репозиторий себе на компьютер, перейдите в его папку и установите зависимости:

```
git clone https://github.com/Delo-Design/jmpdf.git
cd jmpdf
composer install
```

Чтобы очистить сборку от лишних шрифтов, запускайте скрипт очистки:

```
composer clear-fonts
```

## Методы JMpdf

Аргументы расписаны в phpdoc в классе JMpdf. <br/>
Базовые методы:
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