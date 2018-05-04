<?php
namespace MonitoPdf\Pdf;

use \MonitoPdf\Pdf\Zend\Pdf\Color\Zend_Pdf_Color_Rgb;

class Element
{
	//const BORDER_STYLE_NONE   = null;
	//const BORDER_STYLE_SOLID  = 0;
	//const BORDER_STYLE_DASHED = array(3, 1);
	//const BORDER_STYLE_DOTTED = array(1, 1);
	
	private $value           = '';
	private $positionX       = 0;
	private $positionY       = 0;
	private $positionX1      = null;
	private $positionY1      = null;
	private $width           = 0;
	private $height          = 0;
	private $style           = null;
	private $type            = 'field';
	private $backgroundColor = 'none';
	private $lineColor       = '#000000';
	private $lineStyle       = 'solid'; // solid;
	private $lineWidth       = 0.283465; // 0.1 milimiter
	private $align           = 0;
	private $color           = '#ffffff';
	private $font            = Pdf::FONT_HELVETICA;
	private $fontColor       = '#000000';
	private $fontSize        = 10;
	private $fontStyle       = Pdf::FONT_STYLE_NORMAL;
	private $padding         = 0;
	private $paddingTop      = 0;
	private $paddingRight    = 0;
	private $paddingBottom   = 0;
	private $paddingLeft     = 0;
	private $radius          = array(0, 0, 0, 0);
	private $radiusTop       = 0;
	private $radiusRight     = 0;
	private $radiusBottom    = 0;
	private $radiusLeft      = 0;
	private $verticalAlign   = 1; // middle
	private $file;
	private $pages = array();
	private $encode;

	public function __construct ()
	{
		//$this->positionY = FwPdf::getPositionY();
		$this->setLineColor($this->lineColor);
	}
	/**
	 * getAlign
	 *
	 * @return int Alinhamento do texto
	 */
	public function getAlign ()
	{
		return $this->align;
	}
	/**
	 * getBackgroundColor
	 *
	 * @return string Cor da borda
	 */
	public function getBackgroundColor ()
	{
		if ($this->backgroundColor != 'none')
		{
			return $this->backgroundColor;
		}

		return null;
	}
	/**
	 * getLineColor
	 *
	 * @return string Cor da borda
	 */
	public function getLineColor ()
	{
		if ($this->lineColor != 'none') {
			return $this->lineColor;
		}

		return null;
	}
	/**
	 * getLineWidth
	 *
	 * @return string Tamanho da borda
	 */
	public function getLineWidth ()
	{
		return $this->lineWidth;
	}
	/**
	 * getLineStyle
	 *
	 * @return string Estilo da borda
	 */
	public function getLineStyle ()
	{
		return $this->lineStyle;
	}
	/**
	 * getFont
	 *
	 * @return string Estilo da borda
	 */
	public function getFont ()
	{
		return $this->font;
	}
	/**
	 * getFontColor
	 *
	 * @return string Cor da borda
	 */
	public function getFontColor ()
	{
		// TODO: match hex color
		if ($this->fontColor == '#000000') {
			$this->setFontColor($this->fontColor);
		}
		return $this->fontColor;
	}
	/**
	 * getFontStyle
	 *
	 * @return string Estilo da fonte
	 */
	public function getFontStyle ()
	{
		return $this->fontStyle;
	}
	/**
	 * getFontSize
	 *
	 * @return string Cor da borda
	 */
	public function getFontSize ()
	{
		return $this->fontSize;
	}
	/**
	 * getHeight
	 *
	 * @return int Altura do elemento
	 */
	public function getHeight ()
	{
		return $this->height;
	}
	/**
	 * getPaddingBottom
	 * 
	 * @return float Preenchimento inferior
	 */
	public function getPaddingBottom ()
	{
		return $this->paddingBottom;
	}
	/**
	 * getPaddingLeft
	 * 
	 * @return float Preenchimento esquerdo
	 */
	public function getPaddingLeft ()
	{
		return $this->paddingLeft;
	}
	/**
	 * getPaddingRight
	 * 
	 * @return float Preenchimento direito
	 */
	public function getPaddingRight ()
	{
		return $this->paddingRight;
	}
	/**
	 * getPaddingTop
	 * 
	 * @return float Preenchimento superior
	 */
	public function getPaddingTop ()
	{
		return $this->paddingTop;
	}
	/**
	 * getPages
	 * 
	 * @return array Páginas onde o elemento será impresso
	 */
	public function getPages ()
	{
		return $this->pages;
	}
	/**
	 * getFile
	 * @return string Caminho da imagem
	 */
	public function getFile ()
	{
		return $this->file;
	}
	/**
	 * getPositionX
	 * @return int Posição X do cursor
	 */
	public function getPositionX ()
	{
		return $this->positionX;
	}
	/**
	 * getPositionX1
	 * @return int Posição X1 do cursor
	 */
	public function getPositionX1 ()
	{
		return $this->positionX1;
	}
	/**
	 * getPositionY
	 * @return int Posição Y do cursor
	 */
	public function getPositionY ()
	{
		return $this->positionY;
	}
	/**
	 * getPositionY1
	 * @return int Posição Y1 do cursor
	 */
	public function getPositionY1 ()
	{
		return $this->positionY1;
	}
	/**
	 * getRadius
	 * @return 
	 */
	public function getRadius ()
	{
		return $this->radius;
	}
	/**
	 * getType
	 * @return 
	 */
	public function getType ()
	{
		return $this->type;
	}
	/**
	 * getValue
	 *
	 * @return string Valor do elemento
	 */
	public function getValue ()
	{
		return $this->value;
	}
	/**
	 * getVerticalAlign
	 *
	 * @return int Alinhamento vertical do texto
	 */
	public function getVerticalAlign ()
	{
		return $this->verticalAlign;
	}
	/**
	 * getWidth
	 *
	 * @return int Tamanho do elemento
	 */
	public function getWidth ()
	{
		return $this->width;
	}
	/**
	 * Retorna um objeto
	 *
	 * @param $hexColor Cor no formato hexadecimal
	 * @return Zend_Pdf_Color_Rgb Instância da classe color
	 */
	private function getZendColor ($hexColor)
	{
		return new \MonitoPdf\Pdf\Zend\Pdf\Color\Zend_Pdf_Color_Html($hexColor);
		// $util = new NFeTools;
		//$c = fw_hex_to_float($hexColor);
		// $c = $util->hexToFloat($hexColor);
		// 2018-04-21
		$c = [255,0,0];
		return new Zend_Pdf_Color_Rgb($c[0], $c[1], $c[2]);
	}
	/**
	 * setAlign
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setAlign ($align)
	{
		switch(strtolower($align))
		{
			case 1:
			case 'right':
				$align = 1;
				break;
			case 2:
			case 'center':
				$align = 2;
				break;
			case 3:
			case 'justify':
				$align = 3;
				break;
			default:
				$align = 0;
				break;
		}
		$this->align = $align;
		return $this;
	}
	/**
	 * setBackgroundColor
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setBackgroundColor ($color)
	{
		if (!$color instanceof Zend_Pdf_Color) {
			$color = $this->getZendColor($color);
		}

		$this->backgroundColor = $color;

		return $this;
	}
	/**
	 * setLineColor
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setLineColor ($color)
	{
		if ($color != 'none') {
			$this->lineColor = $color;
		} else {
			$this->lineColor = 'none';
		}
		return $this;
	}
	/**
	 * setLineStyle
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setLineStyle ($style)
	{
		$this->lineStyle = $style;
		return $this;
	}
	/**
	 * setLineWidth
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setLineWidth ($width)
	{
		$this->lineWidth = $width;// * FwPdf::DPIMM;
		return $this;
	}
	/**
	 * setFont
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setFont ($font)
	{
		$this->font = $font;
		return $this;
	}
	/**
	 * setFontColor
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setFontColor ($color)
	{
		$this->fontColor = $this->getZendColor($color);
		return $this;
	}
	/**
	 * setFontSize
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setFontSize ($size)
	{
		$this->fontSize = $size;
		return $this;
	}
	/**
	 * setFontStyle
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setFontStyle ($fontStyle)
	{
		switch ($fontStyle)
		{
			case 1:
			case 'bold':
				$fontStyle = 1;
				break;
			case 2:
			case 'italic':
				$fontStyle = 2;
				break;
			case 3:
			case 'bold_italic':
				$fontStyle = 3;
				break;
			default:
				$fontStyle = 0;
				break;
		}

		$this->fontStyle = $fontStyle;
		return $this;
	}
	/**
	 * setHeight
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setHeight ($height)
	{
		$this->height = $height;// * FwPdf::DPIMM;
		return $this;
	}
	/**
	 * setPage
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPage ($page)
	{
		if (!in_array($page, $this->pages))
		{
			$this->pages[] = $page;
		}

		return $this;
	}
	/**
	 * setPadding
	 *
	 * @param float Padding do campo
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPadding ($padding)
	{
		$this->padding = $padding;

		return $this;
	}
	/**
	 * setPaddingBottom
	 *
	 * @param float Preenchimento inferior do campo
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPaddingBottom ($paddingBottom)
	{
		$this->paddingBottom = $paddingBottom;

		return $this;
	}
	/**
	 * setPaddingLeft
	 *
	 * @param float Preenchimento esquerdo do campo
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPaddingLeft ($paddingLeft)
	{
		$this->paddingLeft = $paddingLeft;

		return $this;
	}
	/**
	 * setPaddingRight
	 *
	 * @param float Preenchimento direito do campo
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPaddingRight ($paddingRight)
	{
		$this->paddingRight = $paddingRight;

		return $this;
	}
	/**
	 * setPaddingTop
	 *
	 * @param float Preenchimento superior do campo
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPaddingTop ($paddingTop)
	{
		$this->paddingTop = $paddingTop;

		return $this;
	}
	/**
	 * setRadius
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setRadius ($radius)
	{
		$this->radius = $radius;
		return $this;
	}
	/**
	 * setPath
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setFile ($file)
	{
		if (!file_exists($file))
		{
			throw new Exception('O arquivo de imagem '. $file . ' não existe! (' . __CLASS__ . ',' . __LINE__ . ')');
		}
		$this->file = $file;
		return $this;
	}
	/**
	 * setPositionX
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPositionX ($x)
	{
		//$this->positionX = $x * FwPdf::DPIMM;
		$this->positionX = $x;
		return $this;
	}
	/**
	 * setPositionX1
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPositionX1 ($x)
	{
		//$this->positionX1 = $x * FwPdf::DPIMM;
		$this->positionX1 = $x;// * FwPdf::DPIMM;
		return $this;
	}
	/**
	 * setPositionY
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPositionY ($x)
	{
		$this->positionY = $x;// * FwPdf::DPIMM;
		return $this;
	}
	/**
	 * setPositionY1
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setPositionY1 ($x)
	{
		$this->positionY1 = $x;// * FwPdf::DPIMM;
		return $this;
	}
	/**
	 * setStyle
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setStyle ($style)
	{
		if (!$style instanceof Style)
		{
			$dt = debug_backtrace();
			//_vde($dt);
			throw new Exception('Estilo inválido!' . "\nLine:" . $dt[0]['line'] . ' (' . __CLASS__ . ',' . __LINE__ . ')');
		}

		$props = $style->expose();

		foreach ($props as $p => $v)
		{
			$g = 'get' . ucfirst($p);
			$s = 'set' . ucfirst($p);

			if (!is_null($style->$g()))
			{
				$this->$s($style->$g());
			}
		}

		//foreach ($style as $k => $v)
		//{
		//	$m = 'set' . ucwords($k);
		//	$this->$m($v);
		//}

		return $this;
	}
	/**
	 * setWidth
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setType ($type)
	{
		$this->type = $type;
		return $this;
	}
	/**
	 * setVerticalAlign
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setVerticalAlign ($verticalAlign)
	{
		switch(strtolower($verticalAlign))
		{
			case 'top':
				$verticalAlign = 0;
				break;
			case 'bottom':
				$verticalAlign = 2;
				break;
			default:
				$verticalAlign = 1;
				break;
		}

		$this->verticalAlign = $verticalAlign;
		return $this;
	}
	/**
	 * setValue
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setValue ($value)
	{
		$this->value = $value;
		return $this;
	}
	/**
	 * setWidth
	 *
	 * @return FwPdfElement Instância da classe
	 */
	public function setWidth ($width)
	{
		$this->width = $width;// * FwPdf::DPIMM;
		return $this;
	}
}