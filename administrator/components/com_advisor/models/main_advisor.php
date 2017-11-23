<?php
/*
* @component %%COMPONENTNAME%% 
 * @copyright Copyright (C) August 2017. 
 * @license GPL 3.0
 * This program is free software: you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * See <http://www.gnu.org/licenses/>.
 * More info www.moonsoft.es gestion@moonsoft.es 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

require 'main.model.php';

class AdvisorModelWrapper extends AdvisorMainModel{
	
	function __construct(){
        parent::__construct();
    }
    
	function __buildQuerySelectSteps($idflow){
        $query = "select s.*,(select n.name from #__advisor_step n where n.id=s.idprevstep) as prevstep
          from #__advisor_step s where s.idflow=".$idflow;
        return $query;
    }
    
	function __buildQuerySelectStepOptions($idflow){
        $query = "select o.*
          from #__advisor_option o left join #__advisor_step s on o.idstep=s.id 
          where s.idflow=".$idflow;
        return $query;
    }
    
    function __buildQueryNumberOfFlows(){
        $query = "select count(*) as counter from #__advisor_flow";
        return $query;
    }
    
	function __buildQueryFlows($idflow){
        $query = "select * from #__advisor_flow where id=".$idflow;
        return $query;
    }
    
	function __buildQueryNumberOfSteps($idflow){
        $query = "select count(*) as counter from #__advisor_step where idflow=".$idflow;
        return $query;
    }
    
	function __buildQuerySteps($idstep){
        $query = "select * from #__advisor_step where id=".$idstep;
        return $query;
    }
    
	function __buildQueryNumberOfOptions($idstep){
        $query = "select count(*) as counter from #__advisor_option where idstep=".$idstep;
        return $query;
    }
    
    function __buildQueryNumberOfProducts($idflow){
    	$query = "select count(*) as counter from #__advisor_product where idflow=".$idflow;
        return $query;
    }
    
	function __buildQueryNumberOfSolutions($idflow){
    	$query = "select count(*) as counter from #__advisor_solution where idflow=".$idflow;
        return $query;
    }
        
	function get_AllSteps(){
      $db = JFactory::getDBO();
      $array = JRequest::getVar('cid',  -1, '', 'array');
      $query = $this->__buildQuerySelectSteps((int)$array[0]);
      $db->setQuery($query);
      return $db->loadObjectList();
    }
	function get_AllSteps2(){
      $db = JFactory::getDBO();
      $query = $this->__buildQuerySelectSteps(JRequest::getVar('idflow'));
      $db->setQuery($query);
      return $db->loadObjectList();
    }
	function get_AllStepOptions(){
      $db = JFactory::getDBO();
      $query = $this->__buildQuerySelectStepOptions(JRequest::getVar('idflow'));
      $db->setQuery($query);
      return $db->loadAssocList();
    }
	function get_NumberOfFlows(){
      $db = JFactory::getDBO();
      $query = $this->__buildQueryNumberOfFlows();
      $db->setQuery($query);
      return $db->loadResult();
    }
	function get_NumberOfSteps(){
      $db = JFactory::getDBO();
      $flow=JRequest::getVar('idflow');
      if ($flow==null){
      	$array=JRequest::getVar('cid',  -1, '', 'array');
      	$flow=(int)$array[0];
      } 
      $query = $this->__buildQueryNumberOfSteps($flow);
      $db->setQuery($query);
      return $db->loadResult();
    }    
	function get_NameOfFlow(){
      $db = JFactory::getDBO();
	  $flow=JRequest::getVar('idflow');
      if ($flow==null){
      	$array=JRequest::getVar('cid',  -1, '', 'array');
      	$flow=(int)$array[0];
      }
      $query = $this->__buildQueryFlows($flow);
      $db->setQuery($query);
      return $db->loadObject();
    }
	function get_NumberOfOptions(){
      $db = JFactory::getDBO();
      $step=JRequest::getVar('idstep');
      if ($step==null){
      	$array=JRequest::getVar('cid',  -1, '', 'array');
      	$step=(int)$array[0];
      } 
      $query = $this->__buildQueryNumberOfOptions($step);
      $db->setQuery($query);
      return $db->loadResult();
    }    
	function get_NameOfStep(){
      $db = JFactory::getDBO();
      $step=JRequest::getVar('idstep');
      if ($step==null){
      	$array=JRequest::getVar('cid',  -1, '', 'array');
      	$step=(int)$array[0];
      } 
      $query = $this->__buildQuerySteps($step);
      $db->setQuery($query);
      return $db->loadObject();
    }
	function get_NumberOfProducts(){
      $db = JFactory::getDBO();
      $flow=JRequest::getVar('idflow');
      if ($flow==null){
      	$array=JRequest::getVar('cid',  -1, '', 'array');
      	$flow=(int)$array[0];
      } 
      $query = $this->__buildQueryNumberOfProducts($flow);
      $db->setQuery($query);
      return $db->loadResult();
    }
	function get_NumberOfSolutions(){
      $db = JFactory::getDBO();
      $flow=JRequest::getVar('idflow');
      if ($flow==null){
      	$array=JRequest::getVar('cid',  -1, '', 'array');
      	$flow=(int)$array[0];
      } 
      $query = $this->__buildQueryNumberOfSolutions($flow);
      $db->setQuery($query);
      return $db->loadResult();
    }
    function get_helperContent(){
    	$ret=array();
    	$controller=JRequest::getVar('controller');
    	$task=JRequest::getVar('task');
    	if ($controller=='step' && $task=='edit') $controller='option';
    	if ($controller=='advisor' && $task=='edit') $controller='step';
		switch ($controller){
			case 'option':
				$ret['num_options']=$this->get_NumberOfOptions();
				$ret['name_step']=$this->get_NameOfStep()->name;
			case 'step':
			case 'solution':
			case 'product':
				$ret['num_steps']=$this->get_NumberOfSteps();
				$ret['name_flow']=$this->get_NameOfFlow()->title;
				$ret['num_solutions']=$this->get_NumberOfSolutions();
				$ret['num_products']=$this->get_NumberOfProducts();
			default:
				$ret['num_flows']=$this->get_NumberOfFlows();
					
		}
		return $ret;
    }
    
}
