<?php

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Mpdf\Mpdf;

class JMpdf
{

    protected $config;


    public function __construct($html = '', $uconfig = [])
    {

        JLoader::registerNamespace('Mpdf', JPATH_LIBRARIES . '/mpdf');
        $config = new Registry();
        $config->loadArray(include __DIR__ . '/config.php');

        foreach ($uconfig as $key => $value) {
            $config->set($key, $value);
        }

        $mpdf_config = [
            'mode'                 =>   $config->get('mode', ''),              // mode - default ''
            'format'               =>   $config->get('format', ''),            // format - A4, for example, default ''
            'margin_left'          =>   $config->get('margin_left', ''),       // margin_left
            'margin_right'         =>   $config->get('margin_right', ''),      // margin right
            'margin_top'           =>   $config->get('margin_top', ''),        // margin top
            'margin_bottom'        =>   $config->get('margin_bottom', ''),     // margin bottom
            'margin_header'        =>   $config->get('margin_header', ''),     // margin header
            'margin_footer'        =>   $config->get('margin_footer', ''),     // margin footer
            'tempDir'              =>   $config->get('tempDir', '')            // margin footer
        ];

        $this->config = $config;
        $this->mpdf = new Mpdf($mpdf_config);
        // If you want to change your document title,
        // please use the <title> tag.
        $this->mpdf->SetTitle('Document');
        $this->mpdf->SetAuthor( $config->get('author', '') );
        $this->mpdf->SetCreator( $config->get('creator', '') );
        $this->mpdf->SetSubject( $config->get('subject', '') );
        $this->mpdf->SetKeywords( $config->get('keywords', '') );
        $this->mpdf->SetDisplayMode( $config->get('display_mode', '') );
        if (!empty($config->get('instanceConfigurator', '')) && is_callable(($this->config->get('instanceConfigurator')))) {
            $this->config->get('instanceConfigurator')($this->mpdf);
        }
        $this->mpdf->WriteHTML($html);
    }

    protected function getConfig($key)
    {
        return $this->config->get($key, '');
    }

    public function setProtection($permisson, $userPassword = '', $ownerPassword = '')
    {
        if (func_get_args()[2] === NULL) {
            $ownerPassword = bin2hex(openssl_random_pseudo_bytes(8));
        };
        return $this->mpdf->SetProtection($permisson, $userPassword, $ownerPassword);
    }

    public function output()
    {
        return $this->mpdf->Output('', 'S');
    }

    public function save($filename)
    {
        return $this->mpdf->Output($filename, 'F');
    }

    public function download($filename = 'document.pdf')
    {
        return $this->mpdf->Output($filename, 'D');
    }

    public function stream($filename = 'document.pdf')
    {
        return $this->mpdf->Output($filename, 'I');
    }

}