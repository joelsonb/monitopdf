<?php
namespace MonitoPdf\Pdf;

use \MonitoPdf\Pdf\Barcode\Code128C;
use \MonitoPdf\Pdf\Pdf_Element;
use \MonitoPdf\Pdf\Zend\Zend_Pdf;
use \MonitoPdf\Pdf\Zend\Pdf\Zend_Pdf_Color;
use \MonitoPdf\Pdf\Zend\Pdf\Zend_Pdf_Font;
use \MonitoPdf\Pdf\Zend\Pdf\Zend_Pdf_Page;
use \MonitoPdf\Pdf\Zend\Pdf\Resource\Zend_Pdf_Resource_Font;
/**
 * Classe de geração de PDF com Zend_Pfd
 * @author Joelson Batista <joelsonb@msn.com>
 * @since 2011-12-06
 * @copyright Copyright &copy; 2011-2017
 * @package classes
 */



class Pdf
{
	const VERSION = '1.2.0';
	/**
	 * 1.2.0 - 2017-02-09
	 * new: método join para juntar arquivos PDF
	 *
	 * 1.0.1 - 2015-04-27
	 * fix: correção exibição número de página/paginas
	 * 
	 * 1.0.0 - 2015-03-30
	 * release inicial
	 */
	const DPIMM   = 2.83465;

	// Alignment
	const ALIGN_LEFT    = 0;
	const ALIGN_RIGHT   = 1;
	const ALIGN_CENTER  = 2;
	const ALIGN_JUSTIFY = 3;

	// Borders
	const BORDER_STYLE_NONE   = 'none';
	const BORDER_STYLE_SOLID  = 'solid';
	const BORDER_STYLE_DASHED = 'dashed';
	const BORDER_STYLE_DOTTED = 'dotted';

	// Fonts
	const FONT_COURIER      = Zend_Pdf_Font::FONT_COURIER;
	const FONT_TIMES        = Zend_Pdf_Font::FONT_TIMES;
	const FONT_HELVETICA    = Zend_Pdf_Font::FONT_HELVETICA;
	const FONT_SYMBOL       = Zend_Pdf_Font::FONT_SYMBOL;
	const FONT_ZAPFDINGBATS = Zend_Pdf_Font::FONT_ZAPFDINGBATS;
	
	// Font's Styles
	const FONT_STYLE_NORMAL      = 0;
	const FONT_STYLE_BOLD        = 1;
	const FONT_STYLE_ITALIC      = 2;
	const FONT_STYLE_BOLD_ITALIC = 3;

	// Vertical Alignment
	const VERTICAL_ALIGN_TOP    = 0;
	const VERTICAL_ALIGN_MIDDLE = 1;
	const VERTICAL_ALIGN_BOTTOM = 2;

	private $pages     = [];
	private $elements  = [];
	private $barcodes  = [];
	private $fields    = [];
	private $images    = [];
	private $lines     = [];
	private $texts     = [];
	private $pageIndex = 0;

	private $currentPage;
	private $pageWidth;
	private $pageHeight;
	
	private $fileName = 'pdfzoo';
	
	private $path;
	
	private $dgcPage;
	private $dgcPages;
	
	// Objeto base
	private $defaultObject;
	
	private $index = 0;
	
	public function __construct ()
	{
		$this->Zend_Pdf      = new Zend_Pdf;
		$this->defaultObject = new Element;
	}

    /**
     * Add new page to document
     * @param float $size Tamanho da página
     * @return object
     */
	public function addPage ($size = Zend_Pdf_Page::SIZE_A4)
	{
		$page  = new Zend_Pdf_Page($size);
		$count = count($this->pages) + 1;

		$this->pages[$count]           = $page;
		$this->Zend_Pdf->pages[$count] = $page;
		$this->currentPage = $page;

		return $page;
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
		return $this->backgroundColor;
	}
	/**
	 * getCurrentPageHeight
	 *
	 * @return float Altura da página atual em mm
	 */
	public function getCurrentPageHeight ()
	{
		return $this->currentPage->getHeight() / self::DPIMM;
	}
	/**
	 * getCurrentPageWidth
	 *
	 * @return float Largura da página atual em mm
	 */
	public function getCurrentPageWidth ()
	{
		return $this->currentPage->getWidth() / self::DPIMM;
	}
	/**
	 * getLineColor
	 *
	 * @return string Cor da borda
	 */
	public function getLineColor ()
	{
		return $this->lineColor;
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
		return Zend_Pdf_Font::fontWithName($this->font);
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
	 * @return float Recuo inferior
	 */
	public function getPaddingBottom ()
	{
		return $this->paddingBottom;
	}
	/**
	 * getPaddingLeft
	 * 
	 * @return float Recuo esquerdo
	 */
	public function getPaddingLeft ()
	{
		return $this->paddingLeft;
	}
	/**
	 * getPaddingRight
	 * 
	 * @return float Recuo direito
	 */
	public function getPaddingRight ()
	{
		return $this->paddingRight;
	}
	/**
	 * getPaddingTop
	 * 
	 * @return float Recuo superior
	 */
	public function getPaddingTop ()
	{
		return $this->paddingTop;
	}
	/**
	 * getPath
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
	public function getTextWidth ($text, Zend_Pdf_Resource_Font $font, $font_size) 
	{
		$drawing_text = iconv('', 'UTF-16BE', $text);
		$characters   = [];

		for ($i = 0; $i < strlen($drawing_text); $i++) {
			$characters[] = (ord($drawing_text[$i++]) << 8) | ord ($drawing_text[$i]);
		}

		$glyphs     = $font->glyphNumbersForCharacters($characters);
		$widths     = $font->widthsForGlyphs($glyphs);
		$text_width = (array_sum($widths) / $font->getUnitsPerEm()) * $font_size;
		return $text_width;	
	}
	function getTextWidth1($text, $font, $font_size)
	{
		$drawing_text = iconv('', 'UTF-8', $text);
		$characters    = array();
		for ($i = 0; $i < strlen($drawing_text); $i++) {
			$characters[] = (ord($drawing_text[$i++]) << 8) | ord ($drawing_text[$i]);
		}
		$glyphs        = $font->glyphNumbersForCharacters($characters);
		$widths        = $font->widthsForGlyphs($glyphs);
		$text_width   = (array_sum($widths) / $font->getUnitsPerEm()) * $font_size;
		return $text_width;
	}
	/**
	* Return length of generated string in points
	*
	* @param string $string
	* @param Zend_Pdf_Resource_Font $font
	* @param int $fontSize
	* @return double
	*/
	private function getTextWidthOLD($text, Zend_Pdf_Resource_Font $font, $fontSize)
	{
		//echo $text . '<br />' . PHP_EOL;
		$drawing_text = iconv('', 'UTF-16BE', $text);
		$characters   = array();

		for($i = 0; $i < strlen($drawing_text); $i++)
		{
			$characters[] = (ord($drawing_text[$i++]) << 8) | ord ($drawing_text[$i]);
		}

		$glyphs     = $font->glyphNumbersForCharacters($characters);
		$widths     = $font->widthsForGlyphs($glyphs);
		$text_width = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;
		
		//$ln = $font->getLineHeight();
		
		//echo $ln;exit;

		return $text_width;
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
		$c = fw_hex_to_float($hexColor);
		return new Zend_Pdf_Color_Rgb($c[0], $c[1], $c[2]);
	}
	/**
	 * Junta vários arquivos PDF em um
	 * @return void
	 */
	public function join ($files)
	{
		$extractor = new Zend_Pdf_Resource_Extractor();
		$pages     = array();

		foreach ($files as $fileName)
		{
			if (file_exists($fileName))
			{
				$pdf = Zend_Pdf::load($fileName);

				foreach ($pdf->pages as $page)
				{
					$this->Zend_Pdf->pages[] = $extractor->clonePage($page);
				}
			}
		}
	}
	/**
	 *
	 * @return PdfElement Instância da classe
	 */
	public function element ()
	{
		//$this->index++;
		return $this->elements[] = clone $this->defaultObject;
	}
	/**
	 *
	 * @return PdfElement Instância da classe
	 */
	public function field ()
	{
		//$this->index++;
		return $this->element()->setType('field');
	}
	/**
	 *
	 * @return PdfElement Instância da classe
	 */
	public function text ()
	{
		//$this->index++;
		return $this->element()->setType('text');
	}
	/**
	 *
	 * @return PdfElement Instância da classe
	 */
	public function image ()
	{
		//$this->index++;
		return $this->element()->setType('image');
	}
	/**
	 *
	 * @return PdfElement Instância da classe
	 */
	public function barcode ()
	{
		//$this->index++;
		return $this->element()->setType('barcode');
	}
	/**
	 *
	 * @return PdfElement Instância da classe
	 */
	public function line ()
	{
		//$this->index++;
		return $this->element()->setType('line');
	}
	public function printFields ()
	{
		echo '<pre>';var_dump($this->elements);
	}
	/**
	 * Renderiza os elementos do PDF
	 */
	private function render ()
	{
		//echo count($this->pages);//exit;
		// TODO: fazer melhor para imprimir os códigos de barra
		// Itera as páginas do documento
		foreach ($this->pages as $pk => $pv) {
			// Itera os elementos da página
			foreach ($this->elements as $e) {
				// Barcodes
				if ($e->getType() == 'barcode') {
					$this->renderBarcode($e);
				}
			}
		}
		
		//echo $this->index;exit;

		// Itera as páginas do documento
		foreach ($this->pages as $pk => $pv) {
			$this->pageIndex   = $pk;
			$this->currentPage = $pv;
			$this->pageWidth   = $this->currentPage->getWidth();
			$this->pageHeight  = $this->currentPage->getHeight();

			//echo $this->pageHeight . '<br />';

			// Itera os elementos da página
			foreach ($this->elements as $e) {
				if (count($e->getPages()) == 0 or in_array($pk, $e->getPages())) {
					//echo $e->getPositionY() . '<br />';
					// Converte o valor de Y
	
					$this->x = $e->getPositionX() * Pdf::DPIMM;
					$this->y = $this->pageHeight - (($e->getPositionY() * Pdf::DPIMM) + ($e->getHeight() * Pdf::DPIMM));
					$this->w = ($e->getPositionX() * Pdf::DPIMM) + ($e->getWidth() * Pdf::DPIMM);
					$this->h = $this->y + ($e->getHeight() * Pdf::DPIMM);
	
					//echo $e->getHeight() . '<br />';
					//echo $e->getPositionY() . '<br />';
	
					// Barcodes
					//if ($e->getType() == 'barcode')
					//{
						//$this->renderBarcode($e);
					//}

					// Fields
					if ($e->getType() == 'field') {
						//echo $e->getBackgroundColor();
						
						//if ($e->getValue() == 'barcode')
						//{
							//echo '<pre>';print_r($e);exit;

						// if (!is_null($e->getBackgroundColor())) {
						// 	\MonitoLib\Dev::vde($e->getBackgroundColor());
						// }

						// Verifica se o campo tem background ou borda
						if ($e->getBackgroundColor() instanceof Zend_Pdf_Color or $this->getZendColor($e->getLineColor()) instanceof Zend_Pdf_Color) {
							// \MonitoLib\Dev::e('ok');
							$this->renderField($e);
						}
						//}
					}
	
					// Images
					if ($e->getType() == 'image') {
						$this->renderImage($e);
					}
	
					// Lines
					if ($e->getType() == 'line') {
						$this->renderLine($e);
					}
	
					// Texts
					if ($e->getType() == 'text' or ($e->getType() == 'field' and $e->getValue() != '')) {
						//if ($e->getValue() == 'barcode')
						//{
							$this->renderText($e);
						//}
					}
				}
			}
		}
		//echo 'no error?';exit;
	}
	/**
	 * Renderiza os elementos do tipo barcode
	 */
	private function renderBarcode ($e)
	{
		$barcode = new Code128C($e->getValue());
		$barcode = $barcode->getString();
		
		//echo $barcode;exit;

		$length = strlen($barcode);

		$sum = 0;

		for ($i = 0; $i < $length; $i++) {
			$sum += substr($barcode, $i, 1);
		}

		$x = $e->getPositionX();
		$w = $e->getWidth() / $sum;

		//echo $w;exit;

		$y = $e->getPositionY();
		$h = $e->getHeight();
		
		//_vde($e->getRadius());

		//echo "x: $x - y: $y - w: $w - h: $h";exit;

		for ($i = 0; $i < $length; $i++) {
			$w1 = $w * $barcode[$i];

			//if ($barcode[$i])
			// TODO: se tiver background, tem que imprimir
			if ($i % 2 == 0) {
				//$this->index++;
				$this->field()->setPositionX($x)
							  ->setPositionY($y)
							  ->setWidth($w1)
							  ->setHeight($h)
							  ->setBackgroundColor($e->getLineColor())
							  ->setLineStyle('none')
							  ->setRadius(0)
							  ;
			}

			$x += $w1;
		}
	}
	/**
	 * Renderiza os elementos do tipo field
	 */
	private function renderField ($e)
	{
		//$x = $e->getPositionX();
		//$y = $e->getPositionY();
		//$w = $e->getWidth();
		//$h = $e->getHeight();
		
		
		//echo "\$x: {$this->x} - \$y: {$this->y} - \$w: {$this->w} - \$h: {$this->h}";exit;

		//$lineColor       = $this->getZendColor();
		//$backgroundColor = $this->getZendColor();
		
		//echo $this->lineStyle . '<br />';
		//echo '<pre>';print_r($e->getLineStyle());//exit;
		//echo '<br />';
		
		//echo '<pre>';print_r($e);//exit;
		
		//echo $e->getLineStyle();exit;




		$this->currentPage->setLineWidth($e->getLineWidth());
		

		// b1l0
		// b1l1
		// b0l1

		//$shapeDraw = Zend_Pdf_Page::SHAPE_DRAW_STROKE;
		
		//var_dump($e->getLineStyle());
		
		//var_dump($lineStyle);

		//$lineStyle = $e->getLineStyle() == 'none' ? null : $this->styleToDashingPattern($e->getLineStyle());
		
		//var_dump($lineStyle);
		//echo '<br />';
		
		//echo '$e->getBackgroundColor()';
		
		//_vde($e->getBackgroundColor());
		

		//_vde($e->getBackgroundColor());
		
		

		
		$drawFill   = $e->getBackgroundColor() instanceof Zend_Pdf_Color ? true : false;
		$drawStroke = (string)$e->getLineStyle() == 'none' ? false : true;
		
		
		//if ($drawStroke)
		//{
		//	echo $e->getLineStyle();
		//	echo $this->styleToDashingPattern($e->getLineStyle());
		//	echo '<br />';
		//}
		
		//var_dump($this->styleToDashingPattern($e->getLineStyle()));

		// Verifica se a cor do background é válida
		if ($drawFill == true && $drawStroke == true) {
			$this->currentPage->setLineColor($e->getLineColor());
			$this->currentPage->setLineDashingPattern($this->styleToDashingPattern($e->getLineStyle()));
			$this->currentPage->setFillColor($e->getBackgroundColor());
	
			$shapeDraw = Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE;
		}

		// Verifica se a cor do background é válida
		if ($drawFill == true && $drawStroke == false) {
			$this->currentPage->setFillColor($e->getBackgroundColor());
			$shapeDraw = Zend_Pdf_Page::SHAPE_DRAW_FILL;
		}

		if ($drawFill == false && $drawStroke == true) {
			$this->currentPage->setLineColor($this->getZendColor($e->getLineColor()));
			$this->currentPage->setLineDashingPattern($this->styleToDashingPattern($e->getLineStyle()));
			$shapeDraw = Zend_Pdf_Page::SHAPE_DRAW_STROKE;
		}
		
		if ($drawFill == true || $drawStroke == true) {
			$this->currentPage->drawRoundedRectangle($this->x, $this->y, $this->w, $this->h, $e->getRadius(), $shapeDraw);
		}
	}
	private function renderImage ($e)
	{
		$x = $e->getPositionX() * self::DPIMM;
		//$y = $this->pageHeight - ($e->getPositionY() * self::DPIMM);
		$y = $e->getPositionY() * self::DPIMM;
		$w = $e->getWidth() * self::DPIMM;
		$h = $e->getHeight() * self::DPIMM;
		
		

		$imageDimensions = getimagesize($e->getFile());

		$imageWidth      = $imageDimensions[0];
		$imageHeight     = $imageDimensions[1];
		$imageProportion = $imageWidth / $imageHeight;

		$imageWidth  *= 0.264583333; // pixels to points
		$imageHeight *= 0.264583333; // pixels to points
		
		//echo "\$imageWidth: $imageWidth<br />";
		//echo "\$imageHeight: $imageHeight<br />";

		if ($w == 0)
		{
			if ($h == 0)
			{
				$w = $imageWidth;
			}
			else
			{
				$w = $h * $imageProportion;
			}
		}
		else
		{
			if ($h == 0)
			{
				//$h = $ih;
			}
		}

		//echo "\$x: $x - \$y: $y - \$w: $w - \$h: $h<br />";

		if ($h == 0)
		{
			if ($w == 0)
			{
				$h = $imageHeight;
			}
			else
			{
				$h = $w / $imageProportion;
			}
		}

		$w += $x;
		$h += $y;
		
		$y  = $this->pageHeight - $y;

		if ($w != $imageWidth and $h == $imageHeight)
		{
			$h *= $w / $imageWidth;
		}

		if ($w == $imageWidth and $h != $imageHeight)
		{
			$w *= $h / $imageHeight;
		}

		$h = $this->pageHeight - $h;
		
		//echo "\$x: $x - \$y: $y - \$w: $w - \$h: $h<br />";exit;

		$image = Zend_Pdf_Image::imageWithPath($e->getFile());
		//
		//echo '<pre>';//print_r($imageDimensions);
		//echo "\$x: $x - \$y: $y - \$w: $w - \$h: $h";exit;
		//var_dump($image);
		//exit;
		
		//echo "\$x: {$this->x} - \$y: {$this->y} - \$w: {$this->w} - \$h: {$this->h}";exit;
 
		$this->currentPage->drawImage($image, $x, $h, $w, $y);
	}
	/**
	 * Renderiza os elementos do tipo line
	 */
	private function renderLine ($e)
	{
		//echo '<pre>';print_r($e);exit;
		$x  = $e->getPositionX() * self::DPIMM;
		$y  = $e->getPositionY() * self::DPIMM;
		$x1 = is_null($e->getPositionX1()) ? $x : $e->getPositionX1() * self::DPIMM;
		$y1 = is_null($e->getPositionY1()) ? $y : $e->getPositionY1() * self::DPIMM;
		
		
		$y  = $this->pageHeight - $y;
		$y1 = $this->pageHeight - $y1;
		
		//$x2 = ($x2 == 0 ? $x1 : $x2);
		//$y2 = ($y2 == 0 ? $y1 : $y2);
		
		//var_dump($this->styleToDashingPattern($e->getLineStyle()));

		//$color = new Zend_Pdf_Color_Rgb($c[0], $c[1], $c[2]);
		$this->currentPage->setLineDashingPattern($this->styleToDashingPattern($e->getLineStyle()));
		$this->currentPage->setLineColor($this->getZendColor($e->getLineColor()));
		$this->currentPage->setLineWidth($e->getLineWidth() * self::DPIMM);
		$this->currentPage->drawLine($x, $y, $x1, $y1);
	}
	/**
	 * Renderiza os elementos do tipo text
	 */
	private function renderText ($e)
	{
		$x = $e->getPositionX() * self::DPIMM;
		$y = $e->getPositionY() * self::DPIMM;
		$w = $e->getWidth() * self::DPIMM;
		$h = $e->getHeight() * self::DPIMM;

		//$x += $e->getPaddingLeft();
		//$w += $x - $e->getPaddingRight();

		$page = $this->pageIndex;
		$pages = count($this->pages);
		
		$this->dgcPage  = $page;
		$this->dgcPages = $pages;

		//echo "\$x: $x - \$y: $y - \$w: $w - \$h: $h";exit;

		//return true;
		//$e->setValue(preg_replace('/\$\[(.+?)\]/ei', '$$1', $e->getValue()));
		$text = preg_replace_callback('/\$\[(.+?)\]/i', "self::dgc", $e->getValue());

		$font      = $e->getFont();
		$fontStyle = $e->getFontStyle();

		switch ($fontStyle)
		{
			case self::FONT_STYLE_BOLD:
				$font .= '-Bold';
				break;
			case self::FONT_STYLE_ITALIC:
				$font .= $font == self::FONT_TIMES ? '-Oblique' : '-Italic';
				break;
			case self::FONT_STYLE_BOLD_ITALIC:
				$font .= $font == self::FONT_TIMES ? '-BoldOblique' : '-BoldItalic';
				break;
		}

		$this->currentPage->setFillColor($e->getFontColor());
		$this->currentPage->setFont(Zend_Pdf_Font::fontWithName($font), $e->getFontSize());

		// Imprime as linhas do texto
		$textLines   = explode("\n", utf8_decode($text));
		$lineCount   = count($textLines);
		$lineSpacing = 0;
		$fontHeight  = ($e->getFontSize() * .34) * self::DPIMM; // índice de conversão baseado em testes
		$textHeight  = ($fontHeight * $lineCount) + ($lineSpacing * ($lineCount - 1));
		
		// TODO: definir comportamento padrão para transbordamento de texto
		//       - lançar exceção
		//       - truncar
		//       - reduzir
		//       - transbordar
		
		$paddingBottom  = $e->getPaddingBottom() * self::DPIMM;

		switch ($e->getVerticalAlign())
		{
			case self::VERTICAL_ALIGN_BOTTOM:
				//$y += $h;// - $pb;;
				//$y = ($y + $h);// - ($fontHeight * $lineCount);// /*+ ($lineSpacing * ($lineCount - 1))*/));
				$y = ($y + $h) - $paddingBottom;// - ($fontHeight * $lineCount);// /*+ ($lineSpacing * ($lineCount - 1))*/));
				break;
			case self::VERTICAL_ALIGN_TOP:
				$y += $fontHeight + $e->getPaddingTop();
				break;
			default:
				//$y = $y + ($h / 2) - ($textHeight / 2);
				//if ($lineCount > 1)
				//{
					//$y += ($h / 2) + ((($fontHeight * 0.7738095) * count($textLines)) / 2) ;// + $pt - $pb); // quase ok 1 linha
					$y += ((($h - $textHeight + $fontHeight) / 2) + ($fontHeight / 4));// + $pt - $pb); // 
				//}
				//else
				//{
				//	$y += ($h / 2) + ((($fontHeight * 0.7738095) * count($textLines)) / 2) ;// + $pt - $pb); // quase ok 1 linha
				//}
				break;
		}

		$y = $this->pageHeight - $y;
		
		//echo $e->getFont()->getLineHeight();exit;
		
		if ($e->getVerticalAlign() == self::VERTICAL_ALIGN_BOTTOM)
		{
			//$y += (($fontHeight * $lineCount) + ($lineSpacing * ($lineCount - 1)));
		}

		foreach ($textLines as $l)
		{
			$paddingLeft  = $e->getPaddingLeft() * self::DPIMM;
			$paddingRight = $e->getPaddingRight() * self::DPIMM;

			$xOriginal = $e->getPositionX() * self::DPIMM;
			$yOriginal = $e->getPositionY() * self::DPIMM;
			$wOriginal = $e->getWidth() * self::DPIMM;
			$hOriginal = $e->getHeight() * self::DPIMM;


		//$x += $e->getPaddingLeft();
		//$w += $x - $e->getPaddingRight();

			$tw = $this->getTextWidth(utf8_decode($l), $this->currentPage->getFont(), $this->currentPage->getFontSize());

			//echo '<pre>';print_r($s);//exit;

			$x = $xOriginal + $paddingLeft;

			if($e->getAlign() == self::ALIGN_CENTER)
			{
				//$x = $xOriginal + (($w - $tw) / 2);
				$x = $xOriginal + (($wOriginal - $tw) / 2);
			}

			if($e->getAlign() == self::ALIGN_RIGHT)
			{
				//$x += $w + $bw - $tw;
				$x = ($xOriginal + $wOriginal) - ($tw + $paddingRight);
			}

			//$l = "x:$xOriginal|y$yOriginal|w:$wOriginal|h:$hOriginal|tw:$tw|x:$x";
	
			$this->currentPage->drawText($l, $x, $y,'ISO-8859-1');
			$y -= $fontHeight + $lineSpacing;;
			$x = $x + $e->getPaddingLeft();
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/*
		
		
		$bw = $e['style']['border']['width'];
		$c     = fw_hex_to_float($e['style']['color']);
		$color = new Zend_Pdf_Color_Rgb($c[0], $c[1], $c[2]);
		$this->currentPage->setFillColor($color);

		$p    = $e['position'];
		$s    = $e['style'];

		$tc   = array(0,0,0);//fw_hex_to_float($s['value']['color']);
		//$font = $this->getFont($s['value']['font'], 'CP1252');
		$font = Zend_Pdf_Font::fontWithName($s['font']);

		$x = $p['x'] + $this->pageMarginLeft;// * self::DPIMM;
		$y = $p['y'] + $this->pageMarginTop;// * self::DPIMM;
		$w = $p['w'];// * self::DPIMM;
		$h = $p['h'];// * self::DPIMM;

		$pt = $s['padding']['t'] * self::DPIMM;
		$pr = $s['padding']['r'] * self::DPIMM;
		$pb = $s['padding']['b'] * self::DPIMM;
		$pl = $s['padding']['l'] * self::DPIMM;

		$x += $pl;
		$x -= $pr;
		//$y -= $pt;
		//$w += $pr;
		//$h -= $pb;

		//$y = $this->currentPage->getHeight() - ($y + $h + $s['fontSize']);
		
		
		

		$this->currentPage->setFont($font, $s['fontSize']);
		
		//echo utf8_decode($e['value']) . ' - ' . fn_removeAccents($e['value']) . '<br />' . PHP_EOL;
		



		/*$fs  = $this->pages[$k]->getFontSize();

		$fs *= 0.352777778;
		$fs *= self::DPIMM;

		//echo $fs;exit;

		if($h > $fs)
		{
			//throw new Exception('Texto não cabe no campo!');
		}

		if($s['value']['valign'] == 'top')
		{
			$y += $h - $fs;
		}

		if($s['value']['valign'] == 'middle')
		{
			$y += (($h - $fs) / 2);// - $fs;
		}*/

		/*
		$textLines   = explode("\n", utf8_decode($e['value']));
		$lineCount   = count($textLines);
		$lineSpacing = 1.2;
		$fontHeight  = $s['fontSize'] / self::DPIMM * 2;
		$textHeight  = ($fontHeight * $lineCount) + ($lineSpacing * ($lineCount - 1));

		if ($e['label'] == 'xsyz')
		{
			echo 'y: ' . $y . '<br />';
			echo 'h: ' . $h . '<br />';
			echo 'textHeight: ' . $textHeight . '<br />';
			//exit;
			//echo '<pre>';print_r($e);exit;
		}

		switch($s['valign'])
		{
			case 'bottom':
				$y += $h - $pb;;
				break;
			case 'top':
				$y += $fontHeight + $pt;
				break;
			default:
				//$y = $y + ($h / 2) - ($textHeight / 2);
				if ($lineCount > 1)
				{
					
					//$y += ($h / 2) + ((($fontHeight * 0.7738095) * count($textLines)) / 2) ;// + $pt - $pb); // quase ok 1 linha
					$y += ($h + $textHeight) / 2;// + $pt - $pb); // 
				}
				else
				{
					$y += ($h / 2) + ((($fontHeight * 0.7738095) * count($textLines)) / 2) ;// + $pt - $pb); // quase ok 1 linha
				}

				break;
		}

		if ($e['label'] == 'field')
		{
			//echo 'y: ' . $y . '<br />';
			//echo 'h: ' . $h . '<br />';
			//echo 'textHeight: ' . $textHeight . '<br />';
			//exit;
			echo '<pre>';print_r($e);exit;
		}

		// Converte a coordenada Y
		$y = $this->currentPage->getHeight() - $y;

		foreach ($textLines as $l)
		{
			$tw = $this->getTextWidth(utf8_decode($l), $this->currentPage->getFont(), $this->currentPage->getFontSize());

			//echo '<pre>';print_r($s);//exit;

			if($s['align'] == 'center' or $s['align'] == self::ALIGN_CENTER)
			{
				$x += ($w - $tw) / 2;
			}

			if($s['align'] == 'right' or $s['align'] == self::ALIGN_RIGHT)
			{
				$x += $w + $bw - $tw;
			}
	
			$this->currentPage->drawText($l, $x, $y,'ISO-8859-1');
			$y -= $fontHeight + $lineSpacing;;
			$x = $p['x'] + $this->pageMarginLeft + $pl;
		}*/
	}
	private function dgc ($m)
	{
		$fck = 'dgc' . ucfirst($m[1]);
		return $this->$fck;
	}
	public function saveToFile ($file)
	{
		$this->render();
		$this->Zend_Pdf->save($file);
	}
	/**
	 * setAlign
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setAlign ($align)
	{
		switch(strtolower($align)) {
			case 'right':
				$align = 1;
				break;
			case 'center':
				$align = 2;
				break;
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
	 * @return PdfElement Instância da classe
	 */
	public function setBackgroundColor ($color)
	{
		$this->backgroundColor = $this->getZendColor($color);
		return $this;
	}
	/**
	 * setLineColor
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setLineColor ($color)
	{
		$this->defaultObject->setLineColor($color);
		return $this;
	}
	/**
	 * setLineStyle
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setLineStyle ($style)
	{
		$this->lineStyle = $this->styleToDashingPattern($style);
		return $this;
	}
	/**
	 * setLineWidth
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setLineWidth ($width)
	{
		$this->lineWidth = $width * self::DPIMM;
		return $this;
	}
	/**
	 * setColor
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setColor ($color)
	{
		$this->color = $this->getZendColor($color);
		return $this;
	}
	/**
	 * setFont
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setFont ($font)
	{
		$this->defaultObject->setFont($font);
		return $this;
	}
	/**
	 * setFontColor
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setFontColor ($color)
	{
		$this->fontColor = $this->getZendColor($color);
		return $this;
	}
	/**
	 * setFontSize
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setFontSize ($size)
	{
		$this->defaultObject->setFontSize($size);
		return $this;
	}
	/**
	 * setHeight
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setHeight ($height)
	{
		$this->height = $height * self::DPIMM;
		return $this;
	}
	/**
	 * setRadius
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setRadius ($radius)
	{
		$this->defaultObject->setRadius($radius);
		return $this;
	}
	/**
	 * setPath
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setFile ($file)
	{
		if (!file_exists($file)) {
			throw new Exception("O arquivo de imagem $file não existe! (" . __CLASS__ . ',' . __LINE__ . ')');
		}
		$this->file = $file;
		return $this;
	}
	/**
	 * setPositionX
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setPositionX ($x)
	{
		$this->positionX = $x * self::DPIMM;
		return $this;
	}
	/**
	 * setPositionX1
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setPositionX1 ($x)
	{
		$this->positionX1 = $x * self::DPIMM;
		return $this;
	}
	/**
	 * setPositionY
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setPositionY ($x)
	{
		$this->positionY = $x * self::DPIMM;
		return $this;
	}
	/**
	 * setPositionY1
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setPositionY1 ($x)
	{
		$this->positionY1 = $x * self::DPIMM;
		return $this;
	}
	/**
	 * setWidth
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setType ($type)
	{
		$this->type = $type;
		return $this;
	}
	/**
	 * setVerticalAlign
	 *
	 * @return PdfElement Instância da classe
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
	 * @return PdfElement Instância da classe
	 */
	public function setValue ($value)
	{
		$this->value = $value;
		return $this;
	}
	/**
	 * setWidth
	 *
	 * @return PdfElement Instância da classe
	 */
	public function setWidth ($width)
	{
		$this->width = $width * self::DPIMM;
		return $this;
	}
	/**
	 * Gera PDF e exibe no navegador
	 */
	public function show ()
	{
		$this->render();
		//echo 'aqui';exit;
		header('Content-type: application/pdf');
		//header('Content-Disposition: filename=' . $this->fileName . '.pdf;');
		//$filter = new Zend_Filter_Compress('Lzf');
		//echo $filter->filter($this->Zend_Pdf->render());
		echo $this->Zend_Pdf->render();
	}
	protected function styleToDashingPattern ($pattern)
	{
		switch ($pattern)
		{
			case 'dashed':
				$r = array(3, 1);
				break;
			case 'dotted':
				$r = array(1, 1);
				break;
			default:
				$r = 0;
				break;
		}

		return $r;
	}
}