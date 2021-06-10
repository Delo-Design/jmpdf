<?php

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Mpdf\Mpdf;

include_once JPATH_LIBRARIES . DIRECTORY_SEPARATOR . '/mpdf/libraries/vendor/autoload.php';

class JMpdf
{

	protected $config;


	public function __construct($html = '', $uconfig = [])
	{
		$uconfig['html'] = $html;
		$this->getInstance($uconfig);
	}


	protected function getInstance($uconfig = [])
	{
		if (empty($this->config))
		{
			$this->config = new Registry();
			$this->config->loadArray(include __DIR__ . DIRECTORY_SEPARATOR . 'config.php');
		}

		if(count($this->config->get('fontdata', [])) === 0)
		{
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
			$font_data = $this->config->set('fontdata', $font_data_default);
			$this->config->set('default_font', 'dejavusans');
		}

		if(count($this->config->get('fontDir', [])) === 0)
		{
			$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
			$this->config->set('fontDir', $defaultConfig['fontDir']);
		}

		foreach ($uconfig as $key => $value)
		{

			if ($key === 'fonts')
			{
				$font_data = $this->config->get('fontdata');
				$this->config->set('fontdata', array_merge($font_data, $value));
				continue;
			}

			if($key === 'fonts_dir')
			{
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
			!empty($this->config->get('instanceConfigurator', '')) &&
			is_callable(($this->$this->config->get('instanceConfigurator'))))
		{
			$this->config->get('instanceConfigurator')($this->mpdf);
		}

		$this->mpdf->WriteHTML($this->config->get('html', ''));
	}

	/*
	 * Magical method for Mpdf
	 *
	 */
	public function __call($name, $arguments)
	{
		if (method_exists($this->mpdf, $name))
		{
			call_user_func_array([$this->mpdf, $name], $arguments);
		}
	}


	protected function getConfig($key)
	{
		return $this->config->get($key, '');
	}


	public function setProtection($permisson, $userPassword = '', $ownerPassword = '')
	{
		if (func_get_args()[2] === null)
		{
			$ownerPassword = bin2hex(openssl_random_pseudo_bytes(8));
		}

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


	public function addFonts($font_path, $fonts)
	{
		$config = [
			'fonts' => $fonts,
			'fonts_dir' => [$font_path]
		];
		$this->getInstance($config);
	}

}