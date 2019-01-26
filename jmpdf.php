<?php

defined('_JEXEC') or die;

class JMpdf
{

    /**
     * @param string $html
     * @param array $config
     *
     * @return PDF
     *
     * @since version
     */
    public static function getPDF($html = '', $config = [])
    {
        JLoader::register('PDF', JPATH_LIBRARIES . '/mpdf/pdf.php');
        return (new PDF($html, $config));
    }


}