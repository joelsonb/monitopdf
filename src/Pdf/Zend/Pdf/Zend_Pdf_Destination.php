<?php
namespace MonitoPdf\Pdf\Zend\Pdf;

use \MonitoPdf\Pdf\Zend\Pdf\Zend_Pdf_Element;
use \MonitoPdf\Pdf\Zend\Pdf\Zend_Pdf_Target;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_Named;
use \MonitoPdf\Pdf\Zend\Pdf\Zend_Pdf_Exception;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_Zoom;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_Fit;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_FitHorizontally;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_FitVertically;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_FitRectangle;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_FitBoundingBox;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_FitBoundingBoxHorizontally;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_FitBoundingBoxVertically;
use \MonitoPdf\Pdf\Zend\Pdf\Destination\Zend_Pdf_Destination_Unknown;
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Pdf
 * @subpackage Destination
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Destination.php 23775 2011-03-01 17:25:24Z ralph $
 */


/** Internally used classes */


/** Zend_Pdf_Target */


/**
 * Abstract PDF destination representation class
 *
 * @package    Zend_Pdf
 * @subpackage Destination
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Pdf_Destination extends Zend_Pdf_Target
{
    /**
     * Load Destination object from a specified resource
     *
     * @internal
     * @param Zend_Pdf_Element $resource
     * @return Zend_Pdf_Destination
     */
    public static function load(Zend_Pdf_Element $resource)
    {
        if ($resource->getType() == Zend_Pdf_Element::TYPE_NAME  ||  $resource->getType() == Zend_Pdf_Element::TYPE_STRING) {
            return new Zend_Pdf_Destination_Named($resource);
        }

        if ($resource->getType() != Zend_Pdf_Element::TYPE_ARRAY) {
            throw new Zend_Pdf_Exception('An explicit destination must be a direct or an indirect array object.');
        }
        if (count($resource->items) < 2) {
            throw new Zend_Pdf_Exception('An explicit destination array must contain at least two elements.');
        }

        switch ($resource->items[1]->value) {
            case 'XYZ':
                return new Zend_Pdf_Destination_Zoom($resource);
                break;

            case 'Fit':
                return new Zend_Pdf_Destination_Fit($resource);
                break;

            case 'FitH':
                return new Zend_Pdf_Destination_FitHorizontally($resource);
                break;

            case 'FitV':
                return new Zend_Pdf_Destination_FitVertically($resource);
                break;

            case 'FitR':
                return new Zend_Pdf_Destination_FitRectangle($resource);
                break;

            case 'FitB':
                return new Zend_Pdf_Destination_FitBoundingBox($resource);
                break;

            case 'FitBH':
                return new Zend_Pdf_Destination_FitBoundingBoxHorizontally($resource);
                break;

            case 'FitBV':
                return new Zend_Pdf_Destination_FitBoundingBoxVertically($resource);
                break;

            default:
                return new Zend_Pdf_Destination_Unknown($resource);
                break;
        }
    }
}
