<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
class AdminUIHelper {


	public static function startAdminArea($backEnd=true) {
		$uri = (string) JUri::getInstance();
		$return = urlencode(base64_encode($uri));
		$configRoute['route'] = 'index.php?option=com_config&view=component&component=' . OPTION . '&return=' . $return;
		$configRoute['params'] = array();
		AImporter::helper('route');
		$document=JFactory::getDocument();
		echo '<div id="j-sidebar-container" class="span2">';
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_DASHBOARD'),'index.php?option=com_bookpro');
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_ACCOUNT'),'index.php?option=com_bookpro&view=customers');
		//JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_CONFIGURATION'),JRoute::_(ARoute::view(VIEW_CONFIG)));
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_APPLICATION'),'index.php?option=com_bookpro&view=application&layout=edit&id=1');
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_ORDERS'),'index.php?option=com_bookpro&view=orders');
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_AIRPORTS'),'index.php?option=com_bookpro&view=airports');
		JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_ADDONS'),'index.php?option=com_bookpro&view=addons');
        //JHtmlSidebar::addEntry(JText::_('Wiretransfers'),'index.php?option=com_bookpro&view=wiretransfers');
		
		AImporter::model('applications');
		$omodel = new BookProModelApplications();
		$items = $omodel->getData();
		foreach ($items as $item){
			if($item->state==1){
				$views=explode(';', $item->views);
				
				if(count($views))
					for ($j=0;$j < count($views);$j++){
					
					JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_'.strtoupper($views[$j])),JRoute::_(ARoute::view($views[$j])));
				}
			}
		}
		if(JPluginHelper::isEnabled('bookpro','product_sms')){
			JHtmlSidebar::addEntry(JText::_('COM_BOOKPRO_SMSS'),'index.php?option=com_bookpro&view=smss');
		}
		echo JHtmlSidebar::render();
		
		//echo '<nav class="sidebar clearfix">'; 
		/*
		AImporter::helper('jbmenu');
		$menu=new JBAdminCssMenu();
		$menu->addChild(new JBMenuNode(JText::_('Geography'), '#',null,null,null,null,'map-marker'), true);
		$menu->addChild(new JBMenuNode( JText::_('COM_BOOKPRO_AIRPORTS'),'index.php?option=com_bookpro&view=airports'));
		$menu->addChild(new JBMenuNode(JText::_('COM_BOOKPRO_COUNTRIES'),'index.php?option=com_bookpro&view=countries'));
		$menu->getParent();
		
		$menu->addChild(new JBMenuNode(JText::_('COM_BOOKPRO_AGENTS'), 'index.php?option=com_bookpro&view=agents',null,null,null,null,'building-o'));
		$menu->addChild(new JBMenuNode(JText::_('COM_BOOKPRO_BUSES'), 'index.php?option=com_bookpro&view=buses',null,null,null,null,'bus'));
		$menu->addChild(new JBMenuNode(JText::_('COM_BOOKPRO_SEATTEMPLATES'), 'index.php?option=com_bookpro&view=seattemplates',null,null,null,null,'bus'));
		$menu->addChild(new JBMenuNode(JText::_('COM_BOOKPRO_BUSTRIPS'), 'index.php?option=com_bookpro&view=routes',null,null,null,null,'arrows-h'));
		
		
		$menu->addChild(new JBMenuNode(JText::_('COM_BOOKPRO_ORDERS'), 'index.php?option=com_bookpro&view=orders',null,null,null,null,'list'));
		$menu->addChild(new JBMenuNode(JText::_('COM_BOOKPRO_ADDONS'), 'index.php?option=com_bookpro&view=addons',null,null,null,null,'suitcase'));
		$menu->addChild(new JBMenuNode(JText::_('COM_BOOKPRO_APPLICATION'), 'index.php?option=com_bookpro&view=application&layout=edit&id=1',null,null,null,null,'envelope-o'));
		
		$menu->addChild(new JBMenuNode(JText::_('COM_BOOKPRO_ACCOUNT'),'index.php?option=com_bookpro&view=countries',null,null,null,null,'users'));
		
		
		//echo "<pre>";print_r($menu);die;
		//$menu->getParent();
		
		
		
		echo '<div id="sidebar">';
		
		echo '<div class="sidebar-toggler hidden-phone"></div>';
		$menu->renderMenu('','sidebar-menu');
		
		//echo "</nav>";
		echo "</div>";
		*/
		echo '</div>';
		
		
		


	}

}

