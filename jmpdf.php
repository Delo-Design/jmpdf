<?php

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Mpdf\Mpdf;

class JMpdf
{

    protected $config;


    public function __construct($html = '', $uconfig = [])
    {

        JLoader::registerNamespace('Mpdf', JPATH_LIBRARIES . DIRECTORY_SEPARATOR . 'mpdf');
        $config = new Registry();
        $config->loadArray(include __DIR__ . DIRECTORY_SEPARATOR . 'config.php');

        foreach ($uconfig as $key => $value) {
            $config->set($key, $value);
        }

        $this->config = $config;
        $this->mpdf = new Mpdf($config->toArray());
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