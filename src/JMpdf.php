<?php

namespace Joomla\Libraries\JMpdf;

defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . DIRECTORY_SEPARATOR . '/mpdf/libraries/vendor/autoload.php';

use Joomla\Registry\Registry;
use Mpdf\Mpdf;

class JMpdf
{
    /**
     * Конфигурация для mpdf
     *
     * @var Registry
     */
    protected $config;

    public function __construct($html = '', $uconfig = [])
    {
        $uconfig['html'] = $html;
        $this->getInstance($uconfig);
    }

    /**
     * Создание/Пересоздание объекта mpdf с сохранением прежных настроек
     *
     * @param   array  $uconfig
     *
     * @throws \Mpdf\MpdfException
     */
    protected function getInstance($uconfig = [])
    {
        if (empty($this->config)) {
            $this->config = new Registry();
            $this->config->loadArray(include JPATH_LIBRARIES . DIRECTORY_SEPARATOR . 'mpdf' . DIRECTORY_SEPARATOR . 'config.php');
        }

        if (count($this->config->get('fontdata', [])) === 0) {
            $font_data_default = [
                "dejavusans" => [
                    'R'          => "DejaVuSans.ttf",
                    'B'          => "DejaVuSans-Bold.ttf",
                    'I'          => "DejaVuSans-Oblique.ttf",
                    'BI'         => "DejaVuSans-BoldOblique.ttf",
                    'useOTL'     => 0xFF,
                    'useKashida' => 75,
                ]
            ];
            $font_data         = $this->config->set('fontdata', $font_data_default);
            $this->config->set('default_font', 'dejavusans');
        }

        if (count($this->config->get('fontDir', [])) === 0) {
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
            && is_callable(($this->$this->config->get('instanceConfigurator')))) {
            $this->config->get('instanceConfigurator')($this->mpdf);
        }

        $this->mpdf->WriteHTML($this->config->get('html', ''));
    }

    /**
     * Магический метод, который позволяет напрямую обращаться к mpdf
     **/
    public function __call($name, $arguments)
    {
        if (method_exists($this->mpdf, $name)) {
            call_user_func_array([$this->mpdf, $name], $arguments);
        }
    }

    /**
     * Получение конфигурации mpdf
     *
     * @param   string  $key
     * @param   mixed   $default
     *
     * @return mixed|stdClass
     */
    protected function getConfig($key, $default = '')
    {
        return $this->config->get($key, $default);
    }

    /**
     * Установка новой конфигурации и пересоздается mpdf
     *
     * @param   string  $key
     * @param   mixed   $value
     *
     * @return bool
     */
    protected function setConfig($key, $value)
    {
        $this->config->set($key, $value);
        $this->getInstance(); // пересоздаем mpdf по новой конфигурации

        return true;
    }

    /**
     * Уставливает пароль на pdf
     *
     * @param           $permisson
     * @param   string  $userPassword
     * @param   string  $ownerPassword
     *
     * @return mixed
     */
    public function setProtection($permisson, $userPassword = '', $ownerPassword = '')
    {
        if (func_get_args()[2] === null) {
            $ownerPassword = bin2hex(openssl_random_pseudo_bytes(8));
        }

        return $this->mpdf->SetProtection($permisson, $userPassword, $ownerPassword);
    }

    /**
     * Вовзращает сгенерированный pdf документ в виде строки
     *
     * @return string
     */
    public function output()
    {
        return $this->mpdf->Output('', 'S');
    }

    /**
     * Сохранение в указанный файл
     *
     * @param   string  $filename  - полный путь куда сохранить
     *
     * @return mixed
     */
    public function save($filename)
    {
        return $this->mpdf->Output($filename, 'F');
    }

    /**
     *
     * Проставление заголовков http на скачку pdf
     *
     * @param   string  $filename  - имя файла
     *
     * @return mixed
     *
     */
    public function download($filename = 'document.pdf')
    {
        return $this->mpdf->Output($filename, 'D');
    }

    /**
     * Проставление заголовков http на вывод в браузер
     *
     * @param   string  $filename  - имя файла
     *
     * @return mixed
     */
    public function stream($filename = 'document.pdf')
    {
        return $this->mpdf->Output($filename, 'I');
    }

    /**
     * Добавление новых шрифтов
     *
     * @param   array|string  $font_path
     * @param   array         $fonts
     *
     * @return bool
     * @see Смотрите пример в файле /examples/fonts.php
     *
     */
    public function addFonts($font_path, $fonts)
    {
        if (is_string($font_path)) {
            $font_path = [$font_path];
        }

        if (!is_array($font_path)) {
            return false;
        }

        $config = [
            'fonts'     => $fonts,
            'fonts_dir' => $font_path
        ];
        $this->getInstance($config);

        return true;
    }
}
