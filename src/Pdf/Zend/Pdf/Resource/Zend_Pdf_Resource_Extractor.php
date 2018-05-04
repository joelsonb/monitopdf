<?php
namespace MonitoPdf\Pdf\Zend\Pdf\Resource;

use \MonitoPdf\Pdf\Zend\Pdf\Zend_Pdf_Element;
use \MonitoPdf\Pdf\Zend\Pdf\Element\Zend_Pdf_Element_Array;
use \MonitoPdf\Pdf\Zend\Pdf\Element\String\Zend_Pdf_Element_String_Binary;
use \MonitoPdf\Pdf\Zend\Pdf\Element\Zend_Pdf_Element_Boolean;
use \MonitoPdf\Pdf\Zend\Pdf\Element\Zend_Pdf_Element_Dictionary;
use \MonitoPdf\Pdf\Zend\Pdf\Element\Zend_Pdf_Element_Name;
use \MonitoPdf\Pdf\Zend\Pdf\Element\Zend_Pdf_Element_Null;
use \MonitoPdf\Pdf\Zend\Pdf\Element\Zend_Pdf_Element_Numeric;
use \MonitoPdf\Pdf\Zend\Pdf\Element\Zend_Pdf_Element_String;
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
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id:
 */

/** Internally used classes */


/**
 * Resource extractor class is used to detach resources from original PDF document.
 *
 * It provides resources sharing, so different pages or other PDF resources can share
 * its dependent resources (e.g. fonts or images) or other resources still use them without duplication.
 * It also reduces output PDF size, required memory for PDF processing and
 * processing time.
 *
 * The same extractor may be used for different source documents, several
 * extractors may be used for constracting one target document, but extractor
 * must not be shared between target documents.
 *
 * @package    Zend_Pdf
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_Resource_Extractor
{
    /**
     * PDF objects factory.
     *
     * @var Zend_Pdf_ElementFactory_Interface
     */
    protected $_factory;

    /**
     * Reusable list of already processed objects
     *
     * @var array
     */
    protected $_processed;

    /**
     * Object constructor.
     */
    public function __construct()
    {
        $this->_factory   = Zend_Pdf_ElementFactory::createFactory(1);
        $this->_processed = array();
    }

    /**
     * Clone page, extract it and dependent objects from the current document,
     * so it can be used within other docs
     *
     * return Zend_Pdf_Page
     */
    public function clonePage(Zend_Pdf_Page $page)
    {
        return $page->clonePage($this->_factory, $this->_processed);
    }
}
