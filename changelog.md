8.2.2
- Обновлена библиотека mpdf до 8.2.2
- Добавлены namespace для класса JMpdf

8.0.11
- Обновлена библиотека mpdf до 8.0.11
- Добавлен магический метод __call для прямых обращений к mpdf свойству класса JMpdf
- Не работает больше загрузка неймспейса напрямую mpdf, используйте вместо этого загрузку ```JPATH_LIBRARIES . '/mpdf/libraries/vendor/autoload.php'```
- Удалены все шрифты кроме dejavu sans, при запуске mpdf напрямую будет ругаться на шрифты, вам надо загрузить их самостоятельно при запуске класса mpdf, JMpdf создается по умолчанию только с Dejavu Sans
- Так как удалились все шрифты, то размер библиотеки сократился с ~46мб до ~3.3мб, все остальные шрифты, возможно, будут выложены отдельным расширением
- Добавлен метод загрузки своих шрифтов addFonts(). Смотрите пример в [examples/fonts.php](https://github.com/Delo-Design/jmpdf/blob/master/examples/fonts.php)