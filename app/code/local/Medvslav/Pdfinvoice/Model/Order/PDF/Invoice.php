<?php
/**
 * Medvslav_Pdfinvoice extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Medvslav
 * @package        Medvslav_Pdfinvoice
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Section model
 *
 * @category    Medvslav
 * @package     Medvslav_Pdfinvoice
 * @author      Medved Yaroslav
 */

class Medvslav_Pdfinvoice_Model_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Invoice
{
    
    /**
    * Return PDF document
    *
    * @param  array $invoices
    * @return Zend_Pdf
    */
    public function getPdf($invoices = array())
    {
       $this->_beforeGetPdf();
       $this->_initRenderer('invoice');

       $pdf = new Zend_Pdf();
       $this->_setPdf($pdf);
       $style = new Zend_Pdf_Style();
       $this->_setFontBold($style, 10);

       foreach ($invoices as $invoice) {
           if ($invoice->getStoreId()) {
               Mage::app()->getLocale()->emulate($invoice->getStoreId());
               Mage::app()->setCurrentStore($invoice->getStoreId());
           }
           $page  = $this->newPage();
           $order = $invoice->getOrder();
           /* Add image */
           $this->insertLogo($page, $invoice->getStore());
           /* Add address */
           $this->insertAddress($page, $invoice->getStore());
           /* Add head */
           $this->insertOrder(
               $page,
               $order,
               Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId())
           );
           /* Add document text and number */
           $this->insertDocumentNumber(
               $page,
               Mage::helper('sales')->__('Invoice # ') . $invoice->getIncrementId()
           );
           /* Add table */
           $this->_drawHeader($page);
           /* Add body */
           foreach ($invoice->getAllItems() as $item){
               if ($item->getOrderItem()->getParentItem()) {
                   continue;
               }
               /* Draw item */
               $this->_drawItem($item, $page, $order);
               $page = end($pdf->pages);
           }
           /* Add totals */
           $this->insertTotals($page, $invoice);

           $textBlock = '';
           $textBlock = strip_tags(Mage::app()->getLayout()->createBlock('cms/block')->setBlockId('block_invoice_pdf')->toHtml());
           //$page->drawText(Mage::helper('sales')->__('Medvslav'), 35, $this->y, 'UTF-8');
           $page->drawText(Mage::helper('sales')->__($textBlock), 35, $this->y, 'UTF-8');

           if ($invoice->getStoreId()) {
               Mage::app()->getLocale()->revert();
           }
       }
       $this->_afterGetPdf();
       return $pdf;
    }

}