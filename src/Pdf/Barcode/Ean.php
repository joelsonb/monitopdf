<?php
namespace MonitoPdf\Pdf\Barcode;


class Ean
{
	private $code;

    private $encoding = array(
        array('0001101', '0100111', '1110010'),
        array('0011001', '0110011', '1100110'),
        array('0010011', '0011011', '1101100'),
        array('0111101', '0100001', '1000010'),
        array('0100011', '0011101', '1011100'),
        array('0110001', '0111001', '1001110'),
        array('0101111', '0000101', '1010000'),
        array('0111011', '0010001', '1000100'),
        array('0110111', '0001001', '1001000'),
        array('0001011', '0010111', '1110100')
    );

    private $first = array('000000','001011','001101','001110','010011','011001','011100','010101','010110','011010');

	public function __construct ($code)
	{
		$len = strlen($code);
		
		//echo $len;exit;

		if (!in_array($len, array(7, 8, 12, 13)))
		{
			throw new Exception($code . ' é código EAN inválido! (' . __CLASS__ . ',' . __LINE__ . ')');
		}

		if (in_array($len, array(7, 12)))
		{
			$this->code = $code;
		}
		else
		{
			$this->code = substr($code, 0, -1);
		}

		$cChecksum = $this->getChecksum();

		if (in_array($len, array(8, 13)))
		{
			$iChecksum = substr($code, -1, 1);
			
			//echo $iChecksum;exit;

			if ($cChecksum != $iChecksum)
			{
				throw new Exception('Dígito verificador do EAN inválido! (' . __CLASS__ . ',' . __LINE__ . ')');
			}
		}

		$this->checksum = $cChecksum;
	}
	public function __toString ()
	{
		return $this->render();
	}
	public function getString ()
	{
		return $this->render();
	}
	public function getDigit ($code, $type)
	{
        // Check len (12 for ean13, 7 for ean8)
        $len = $type == 'ean8' ? 7 : 12;
        $code = substr($code, 0, $len);
        if (!preg_match('`[0-9]{'.$len.'}`', $code)) return('');

        // get checksum
        $code = self::compute($code, $type);

        // process analyse
        $result = '101'; // start

        if ($type == 'ean8'){
            // process left part
            for($i=0; $i<4; $i++){
                $result .= self::$encoding[intval($code[$i])][0];
            }

            // center guard bars
            $result .= '01010';

            // process right part
            for($i=4; $i<8; $i++){
                $result .= self::$encoding[intval($code[$i])][2];
            }

        } else { // ean13
            // extract first digit and get sequence
            $seq = self::$first[ intval($code[0]) ];

            // process left part
            for($i=1; $i<7; $i++){
                $result .= self::$encoding[intval($code[$i])][ intval($seq[$i-1]) ];
            }

            // center guard bars
            $result .= '01010';

            // process right part
            for($i=7; $i<13; $i++){
                $result .= self::$encoding[intval($code[$i])][ 2 ];
            }
        } // ean13

        $result .= '101'; // stop
        
        //echo $result;exit;
        
        return($result);
    }
	public function render ()
	{
        $code = $this->code . $this->checksum;

        // process analyse
        $result = '101'; // start

        if (strlen($this->code) == 7)
		{
            // process left part
            for ($i = 0; $i < 4; $i++)
			{
                $result .= $this->encoding[intval($code[$i])][0];
            }

            // center guard bars
            $result .= '01010';

            // process right part
            for ($i = 4; $i < 8; $i++)
			{
                $result .= $this->encoding[intval($code[$i])][2];
            }
        }
		else
		{ // ean13
            // extract first digit and get sequence
            $seq = $this->first[intval($code[0])];

            // process left part
            for ($i = 1; $i < 7; $i++)
			{
                $result .= $this->encoding[intval($code[$i])][intval($seq[$i - 1])];
            }

            // center guard bars
            $result .= '01010';

            // process right part
            for ($i = 7; $i < 13; $i++)
			{
                $result .= $this->encoding[intval($code[$i])][2];
            }
        }

        $result .= '101'; // stop

        //echo $code . '<br />';//exit;
		//echo $result;exit;
        
        return $result;
    }

    public function OLDchecksum ($code, $type)
	{
        $len = $type == 'ean13' ? 12 : 7;
        $code = substr($code, 0, $len);
        if (!preg_match('`[0-9]{'.$len.'}`', $code)) return('');
        $sum = 0;
        $odd = true;
        for($i=$len-1; $i>-1; $i--){
            $sum += ($odd ? 3 : 1) * intval($code[$i]);
            $odd = ! $odd;
        }
        return($code . ( (string) ((10 - $sum % 10) % 10)));
    }
    public function getChecksum ()
	{
        $sum = 0;
        $odd = true;

        for ($i = strlen($this->code) -1; $i > -1; $i--)
		{
            $sum += ($odd ? 3 : 1) * intval($this->code[$i]);
            $odd  = !$odd;
        }

        return (10 - $sum % 10) % 10;
    }
}