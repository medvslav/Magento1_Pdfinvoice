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
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
 /*
 * @category    Medvslav
 * @package     Medvslav_Pdfinvoice
 * @author      Medvslav
 */
 
if(Mage::getModel('cms/block')->load('block_invoice_pdf', 'identifier')){    
    Mage::getModel('cms/block')->load('block_invoice_pdf', 'identifier')->delete();
}

$content = 'Thank you for buying from us';
//echo $content; die();

$block = Mage::getModel('cms/block');
$block->setTitle('Block for invoice PDF');
$block->setIdentifier('block_invoice_pdf');
$block->setStores(array(0));
$block->setIsActive(1);
$block->setContent($content);
$block->save();
