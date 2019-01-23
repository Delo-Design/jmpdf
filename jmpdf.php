<?php


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
        JLoader::registerNamespace('Mpdf', JPATH_LIBRARIES . '/mpdf/src');
        return (new PDF($html = '', $config = []));
    }


}