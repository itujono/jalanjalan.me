<?php
/**
defined ( '_JEXEC' ) or die ( 'Restricted access' );


BookProHelper::setSubmenu ( 4 );
JToolBarHelper::title ( JText::_ ( 'COM_BOOKPRO_AGENT_MANAGER' ), 'user' );
$itemsCount = count ( $this->items );
$pagination = &$this->pagination;
$listOrder = $this->escape ( $this->state->get ( 'list.ordering' ) );
$listDirn = $this->escape ( $this->state->get ( 'list.direction' ) );
?>