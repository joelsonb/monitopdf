<?php
namespace MonitoPdf\Pdf\Barcode;

class Code128C
{
	private $code;
	private $pattern = array(
						212222, // 00
						222122, // 01
						222221, // 02
						121223, // 03
						121322, // 04
						131222, // 05
						122213, // 06
						122312, // 07
						132212, // 08
						221213, // 09
						221312, // 10
						231212, // 11
						112232, // 12
						122132, // 13
						122231, // 14
						113222, // 15
						123122, // 16
						123221, // 17
						223211, // 18
						221132, // 19
						221231, // 20
						213212, // 21
						223112, // 22
						312131, // 23
						311222, // 24
						321122, // 25
						321221, // 26
						312212, // 27
						322112, // 28
						322211, // 29
						212123, // 30
						212321, // 31
						232121, // 32
						111323, // 33
						131123, // 34
						131321, // 35
						112313, // 36
						132113, // 37
						132311, // 38
						211313, // 39
						231113, // 40
						231311, // 41
						112133, // 42
						112331, // 43
						132131, // 44
						113123, // 45
						113321, // 46
						133121, // 47
						313121, // 48
						211331, // 49
						231131, // 50
						213113, // 51
						213311, // 52
						213131, // 53
						311123, // 54
						311321, // 55
						331121, // 56
						312113, // 57
						312311, // 58
						332111, // 59
						314111, // 60
						221411, // 61
						431111, // 62
						111224, // 63
						111422, // 64
						121124, // 65
						121421, // 66
						141122, // 67
						141221, // 68
						112214, // 69
						112412, // 70
						122114, // 71
						122411, // 72
						142112, // 73
						142211, // 74
						241211, // 75
						221114, // 76
						413111, // 77
						241112, // 78
						134111, // 79
						111242, // 80
						121142, // 81
						121241, // 82
						114212, // 83
						124112, // 84
						124211, // 85
						411212, // 86
						421112, // 87
						421211, // 88
						212141, // 89
						214121, // 90
						412121, // 91
						111143, // 92
						111341, // 93
						131141, // 94
						114113, // 95
						114311, // 96
						411113, // 97
						411311, // 98
						113141, // 99
						114131, // 100
						311141, // 101
						411131, // 102
						211412, // 103
						211214, // 104
						211232, // 105 - start C
						);
	private $start = 211232;
	private $stop  = 2331112;
	
	public function __construct ($code)
	{
		$this->code = $code;
	}
	public function getString ()
	{
		return $this->render();
	}
	public function render ()
	{
		
	
		$fb = array();

		$code = $this->code;
		$bar = $this->start;
		$fb[] = 105;

		for ($i = 0; $i < strlen($code); $i += 2)
		{
			$bar .= $this->pattern[(int)substr($code, $i, 2)];
			$fb[] = (int)substr($code, $i, 2);
		}

		$checksumValue = $fb[0];
		$count = count($fb);

		for ($i = 1; $i < $count; $i++)
		{
			$checksumValue += $fb[$i] * $i;
		}

		// calcula o digito do código de barras
		$bar .= $this->pattern[$checksumValue % 103];
		$bar .= $this->stop;

		//echo $bar;exit;
		return $bar;

		$barLength = strlen($bar);
		

		$sum = 0;
		for($i = 0; $i < $barLength; $i++)
		{
			$sum += substr($bar, $i, 1);
		}

		$x     = $e->getPositionX();
		$width = $e->getWidth() / $sum;
		
		//echo $x;exit;
		//echo $width;exit;
		//echo $bar;exit;

		$result = '';

		for ($i = 0; $i < $barLength; $i++)
		{
			$w = substr($bar, $i, 1) * $width;
			//for ($j = 0; $j < $i; $j++)
			//{
			//	if ($i % 2 == 0)
			//	{
			//		
			//	}
			//	else
			//	{
			//		
			//	}
			//}
			if ($i % 2 == 0)
			{
				/*$p = array('label' => 'barcode',
						   'position' => array(
											   'x' => $x,
											   'y' => $params['y'],
											   'w' => $w,
											   'h' => $params['h'],
											   ),
						   'style' => array(
											'bgcolor' => '#000',
											'border'  => array(
															   'style'  => 'none',
															   ),
											'radius' => array(
															  't' => 0,
															  'r' => 0,
															  'b' => 0,
															  'l' => 0,
															  ),
											),
						   );*/

				$this->field()->setPositionX($x)
							  ->setPositionY($e->getPositionY())
							  ->setWidth($w)
							  ->setHeight($e->getHeight())
							  ->setBackgroundColor('#000');
							  //->setLineColor('#000');
							  //->setValue($w);
				//
				//$this->field()->setValue('barcode')
				//			  ->setPositionX($x)
				//			  ->setPositionY($e->getPositionY())
				//			  ->setWidth($w)
				//			  ->setHeight($e->getHeight())
				//			  ->setBackgroundColor('#000')
				//			  ->setLineStyle('none')
				//			  ->setRadius(0);
			}

			$x = $x + $w;
		}
	}
}