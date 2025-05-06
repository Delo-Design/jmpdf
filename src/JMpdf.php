<?php

namespace Joomla\Libraries\JMpdf;

\defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . DIRECTORY_SEPARATOR . '/mpdf/libraries/vendor/autoload.php';

use Joomla\Registry\Registry;
use Mpdf\Mpdf;

/**
 * @method float toFloat(string $num) Преобразует число из строки, учитывая запятые и точки как десятичные разделители.
 * @method void SetDocTemplate(string $file = '', int $continue = 0, int $continue2pages = 0) Устанавливает шаблон документа для импорта страниц из существующего PDF.
 * @method void SetJS(string $script) Устанавливает JavaScript-код, который будет внедрён в PDF.
 * @method string OutputBinaryData() Возвращает данные PDF в виде бинарной строки.
 * @method string OutputHttpInline() Отправляет PDF в браузер с заголовком Content-Disposition: inline.
 * @method string OutputHttpDownload(string $fileName) Заставляет браузер начать загрузку файла PDF.
 * @method mixed OutputFile(string $fileName) Сохраняет PDF-файл на диск.
 * @method void SetSubstitutions() Устанавливает правила замены шрифтов для символов, отсутствующих в текущем шрифте.
 * @method string SubstituteChars(string $html) Заменяет специальные символы согласно заданным правилам подстановки.
 * @method void setHiEntitySubstitutions() Устанавливает замены для HTML-сущностей.
 * @method void AddFontDirectory(string $directory) Добавляет новую директорию шрифтов для поиска.
 * @method void AddFont(string $family, string $style = '') Добавляет новый шрифт в список доступных.
 * @method void RestartDocTemplate() Перезапускает обработку шаблона документа с текущей страницы.
 * @method array docPageSettings(int $num = 0) Возвращает настройки конкретной страницы (тип нумерации, скрытие, сброс).
 * @method mixed formatPageNumber(int $ppgno, string $lowertype, bool $reverse = false) Форматирует номер страницы (например, римские цифры).
 * @method bool isCJK(int $char) Проверяет, является ли символ частью CJK-скрипта (Китайский, Японский, Корейский).
 * @method bool isPunctuationCJK(int $char) Проверяет, является ли символ знаком препинания в CJK-контексте.
 */
class JMpdf
{
    /**
     * Конфигурация для mpdf
     *
     * @var null|Registry
     */
    protected ?Registry $config;

    /**
     * Экземпляр класса mpdf
     *
     * @var null|\Mpdf\Mpdf
     */
    protected ?Mpdf $mpdf;

    /**
     * @throws \Mpdf\MpdfException
     */
    public function __construct(string $html = '', array $uconfig = [])
    {
        $uconfig['html'] = $html;
        $this->getInstance($uconfig);
    }

    /**
     * Создание/Пересоздание объекта mpdf с сохранением прежних настроек
     *
     * @param   array  $uconfig
     *
     * @throws \Mpdf\MpdfException
     * @return \Mpdf\Mpdf
     */
    protected function getInstance(array $uconfig = []): Mpdf
    {
        if (empty($this->config)) {
            $this->config = new Registry();
            $this->config->loadArray(include JPATH_LIBRARIES . DIRECTORY_SEPARATOR . 'mpdf' . DIRECTORY_SEPARATOR . 'config.php');
        }

        if (\count($this->config->get('fontdata', [])) === 0) {
            $font_data_default = [
                "dejavusans" => [
                    'R'          => "DejaVuSans.ttf",
                    'B'          => "DejaVuSans-Bold.ttf",
                    'I'          => "DejaVuSans-Oblique.ttf",
                    'BI'         => "DejaVuSans-BoldOblique.ttf",
                    'useOTL'     => 0xFF,
                    'useKashida' => 75,
                ],
            ];

            $this->config->set('fontdata', $font_data_default);
            $this->config->set('default_font', 'dejavusans');
        }

        if (\count($this->config->get('fontDir', [])) === 0) {
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $this->config->set('fontDir', $defaultConfig['fontDir']);
        }

        foreach ($uconfig as $key => $value) {
            if ($key === 'fonts') {
                $font_data = $this->config->get('fontdata');
                $this->config->set('fontdata', array_merge($font_data, $value));
                continue;
            }

            if ($key === 'fonts_dir') {
                $font_dir = $this->config->get('fontDir');
                $this->config->set('fontDir', array_merge($font_dir, $value));
                continue;
            }

            $this->config->set($key, $value);
        }


        $this->mpdf = new Mpdf($this->config->toArray());

        // If you want to change your document title,
        // please use the <title> tag.
        $this->mpdf->SetTitle('Document');
        $this->mpdf->SetAuthor($this->config->get('author', ''));
        $this->mpdf->SetCreator($this->config->get('creator', ''));
        $this->mpdf->SetSubject($this->config->get('subject', ''));
        $this->mpdf->SetKeywords($this->config->get('keywords', ''));
        $this->mpdf->SetDisplayMode($this->config->get('display_mode', ''));

        if (
            !empty($this->config->get('instanceConfigurator', ''))
            && \is_callable(($this->$this->config->get('instanceConfigurator')))) {
            $this->config->get('instanceConfigurator')($this->mpdf);
        }

        $this->mpdf->WriteHTML($this->config->get('html', ''));

        return $this->mpdf;
    }

    /**
     * Магический метод, который позволяет напрямую обращаться к mpdf
     **/
    public function __call($name, $arguments)
    {
        if (method_exists($this->mpdf, $name)) {
            \call_user_func_array([$this->mpdf, $name], $arguments);
        }
    }

    /**
     * Получение конфигурации mpdf
     *
     * @param   string  $key
     * @param   mixed   $default
     *
     * @return mixed|\stdClass
     */
    protected function getConfig(string $key, $default = '')
    {
        return $this->config->get($key, $default);
    }

    /**
     * Установка новой конфигурации и пересоздается mpdf
     *
     * @param   string  $key
     * @param   mixed   $value
     *
     * @throws \Mpdf\MpdfException
     * @return bool
     */
    protected function setConfig(string $key, $value): bool
    {
        $this->config->set($key, $value);
        $this->getInstance(); // пересоздаем mpdf по новой конфигурации

        return true;
    }

    /**
     * Уставливает пароль на pdf
     *
     * @param   array        $permission
     * @param   string|null  $userPassword
     * @param   string|null  $ownerPassword
     */
    public function setProtection(array $permission = [], ?string $userPassword = '', ?string $ownerPassword = '')
    {
        if (\func_get_args()[2] === null) {
            $ownerPassword = bin2hex(openssl_random_pseudo_bytes(8));
        }

        $this->mpdf->SetProtection($permission, $userPassword, $ownerPassword);
    }

    /**
     * Возвращает сгенерированный pdf документ в виде строки
     *
     * @throws \Mpdf\MpdfException
     * @return string
     */
    public function output(): string
    {
        return (string) $this->mpdf->Output('', 'S');
    }

    /**
     * Сохранение в указанный файл
     *
     * @param   string  $filename  - полный путь куда сохранить
     *
     * @throws \Mpdf\MpdfException
     * @return string
     */
    public function save(string $filename): string
    {
        return (string) $this->mpdf->Output($filename, 'F');
    }

    /**
     * Проставление заголовков http на скачку pdf
     *
     * @param   string  $filename  - имя файла
     *
     * @throws \Mpdf\MpdfException
     * @return string
     *
     */
    public function download(string $filename = 'document.pdf'): string
    {
        return (string) $this->mpdf->Output($filename, 'D');
    }

    /**
     * Проставление заголовков http на вывод в браузер
     *
     * @param   string  $filename  - имя файла
     *
     * @throws \Mpdf\MpdfException
     * @return string
     */
    public function stream(string $filename = 'document.pdf'): string
    {
        return (string) $this->mpdf->Output($filename, 'I');
    }

    /**
     * Добавление новых шрифтов
     *
     * @param   array|string  $font_path
     * @param   array         $fonts
     *
     * @throws \Mpdf\MpdfException
     * @return bool
     * @see Смотрите пример в файле /examples/fonts.php
     *
     */
    public function addFonts($font_path, array $fonts): bool
    {
        if (\is_string($font_path)) {
            $font_path = [$font_path];
        }

        if (!\is_array($font_path)) {
            return false;
        }

        $config = [
            'fonts'     => $fonts,
            'fonts_dir' => $font_path,
        ];
        $this->getInstance($config);

        return true;
    }
}
