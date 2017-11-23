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
include 'main_advisor.php';

class AdvisorModelAdvisor extends AdvisorModelWrapper{
    var $_id;
    var $_pageNav;
    
    function __construct(){
        parent::__construct();
        $this->_id =null;
        $array = JRequest::getVar('cid',  -1, '', 'array');
        $this->setId((int)$array[0]);
    }
    function setId($id){
        $this->_id = $id;
    }
    function getPageNav(){
      return $this->_pageNav;
    }
    
    /** Database Section **/
    function _buildQuerySelect($id=null){
        $query = "select f.*, (select count(*) from #__advisor_step s where s.idflow=f.id) as steps
          from #__advisor_flow f ";
        if ($id!=null) $query .= 'where f.id='.$id;        
        return $query;
    }    
	function _buildQuerySelectCount(){
        $query = "select count(*) from #__advisor_flow ";
        return $query;
    }
     
    function _buildQuerySelectDataFlow($id=null){
    	$query = "select * from #__advisor_flow ";
        if ($id!=null) $query .= 'where id='.$id;        
        return $query;
    }
    
	function _buildQuerySelectDataProducts($id){
    	$query = "select * from #__advisor_product where idflow=".$id;        
        return $query;
    }
    
	function _buildQuerySelectDataSolutions($id){
    	$query = "select * from #__advisor_solution where idflow=".$id;        
        return $query;
    }
    
	function _buildQuerySelectDataSolutionsOptions($id){
    	$query = "select so.* from #__advisor_solution_option so left join #__advisor_solution s on so.idsolution=s.id where s.idflow=".$id;        
        return $query;
    }
    
	function _buildQuerySelectDataSteps($id){
    	$query = "select * from #__advisor_step where idflow=".$id;        
        return $query;
    }
    
	function _buildQuerySelectDataOptions($id){
    	$query = "select o.* from #__advisor_option o left join #__advisor_step s on o.idstep=s.id where s.idflow=".$id;        
        return $query;
    }
    
    
  function _buildQuerySelectFlowStats($id){
     $query="select * FROM #__advisor_stats where flow=".$id." order by date desc";
     return $query;
  
  }
  
  function _buildQuerySelectOptionStats($idoption){
  
     $query="select * FROM #__advisor_option where id=".$idoption;
     return $query;
  }
  

  
  
  
  function _buildQueryRemoveStats(){
  
    $query="delete  FROM #__advisor_stats ";
    return $query;
  }
    
    
    /** Function Section **/
	function getData(){
      jimport('joomla.html.pagination');
      $mainframe = JFactory::getApplication();
      $limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit'));
      $limitstart = JRequest::getVar('limitstart', 0);
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectCount();
      $db->setQuery( $query );
      $total = $db->loadResult();
      $query = $this->_buildQuerySelect();
      $db->setQuery( $query, $limitstart, $limit );
      $this->_pageNav = new JPagination($total, $limitstart, $limit);
      return $db->loadObjectList();      
    }
    
    function getDataOne() {       
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelect($this->_id);
      $db->setQuery($query);
      return $db->loadAssoc();
    } 

    function store(){
    	$db =& JFactory::getDBO();
        $row =& $this->getTable('flow');        
        $data = JRequest::get( 'post' );
        $data['firstpage'] = JRequest::getVar('firstpage', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $data['prehtml'] = JRequest::getVar('prehtml', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $data['posthtml'] = JRequest::getVar('posthtml', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $data['id']=JRequest::getVar('idflow');
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }     
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }     
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return $row->id;
    }
    
    function publish($publish){
    	//global $this->option;
	    $cid = JRequest::getVar('cid',array(),'','array');	    
	    $flowTable =& $this->getTable('flow');
	    $flowTable->publish($cid, $publish);
	    return $cid;
    }
    
    function remove(){
    	$db =& JFactory::getDBO();                
        $cids=JRequest::getVar('cid',array(),'','array');
        foreach ($cids as $id){
        	$row =& $this->getTable('flow');
        	$row->delete( $id );	
        }
        return true;    	
    }
    
	function getSteps($idflow){
    	$db = JFactory::getDBO();
      	$query = $this->_buildQuerySelectDataSteps($idflow);
      	$db->setQuery($query);
      	return $db->loadAssocList();
    }
    
	// Import functions
	function importsave(){
		$flow=array();
		$arrSteps=array();
		$arrOptions=array();		
		$arrProducts=array();
		$arrSolutions=array();
		$arrSolutionOptions=array();
		$importfile = JRequest::getVar( 'datafile', null, 'files', 'array' );
        if(isset($importfile)){
          $filename = JFile::makeSafe($importfile['name']);      
          if ($filename){
          	$xml=simplexml_load_file($importfile['tmp_name']);
          	//$xml=simplexml_load_string(JFile::read($importfile['tmp_name']));
          	strlen((string)$xml->flow->published)>0?$flow['published']=(string)$xml->flow->published:'';
            strlen((string)$xml->flow->container)>0?$flow['container']=(string)$xml->flow->container:'';
            strlen((string)$xml->flow->containerstep)>0?$flow['containerstep']=(string)$xml->flow->containerstep:'';
            strlen((string)$xml->flow->containerwidth)>0?$flow['containerwidth']=(string)$xml->flow->containerwidth:'';
            strlen((string)$xml->flow->containerstepwidth)>0?$flow['containerstepwidth']=(string)$xml->flow->containerstepwidth:'';
            strlen((string)$xml->flow->containerstepresume)>0?$flow['containerstepresume']=(string)$xml->flow->containerstepresume:'';
            strlen((string)$xml->flow->containerheight)>0?$flow['containerheight']=(string)$xml->flow->containerheight:'';
            strlen((string)$xml->flow->title)>0?$flow['title']=(string)$xml->flow->title:'';
            strlen((string)$xml->flow->firstpage)>0?$flow['firstpage']=(string)$xml->flow->firstpage:'';
            strlen((string)$xml->flow->prehtml)>0?$flow['prehtml']=(string)$xml->flow->prehtml:'';
            strlen((string)$xml->flow->posthtml)>0?$flow['posthtml']=(string)$xml->flow->posthtml:'';
            strlen((string)$xml->flow->viewresume)>0?$flow['viewresume']=(string)$xml->flow->viewresume:'';
            strlen((string)$xml->flow->viewpdf)>0?$flow['viewpdf']=(string)$xml->flow->viewpdf:'';
            
            $arrTmp=array();
            foreach ($xml->steps->children() as $step){ 
            	strlen((string)$step->id)>0?$arrTmp['idant']=(string)$step->id:'';
            	strlen((string)$step->idprevstep)>0?$arrTmp['idprevstep']=(string)$step->idprevstep:'';
            	strlen((string)$step->name)>0?$arrTmp['name']=(string)$step->name:'';
            	strlen((string)$step->text)>0?$arrTmp['text']=(string)$step->text:'';            	
            	strlen((string)$step->precondition)>0?$arrTmp['precondition']=(string)$step->precondition:'';
            	
            	$arrSteps[]=$arrTmp;
            	unset($arrTmp);
            }
          	foreach ($xml->options->children() as $option){ 
            	strlen((string)$option->id)>0?$arrTmp['idant']=(string)$option->id:'';
            	strlen((string)$option->idstep)>0?$arrTmp['idstepant']=(string)$option->idstep:'';
            	strlen((string)$option->content)>0?$arrTmp['content']=(string)$option->content:'';
            	strlen((string)$option->value)>0?$arrTmp['value']=(string)$option->value:'';            	
            	strlen((string)$option->desc)>0?$arrTmp['desc']=(string)$option->desc:'';
            	$arrOptions[]=$arrTmp;
            	unset($arrTmp);
            } 
            foreach ($xml->products->children() as $product){ 
            	strlen((string)$product->id)>0?$arrTmp['idant']=(string)$product->id:'';
            	strlen((string)$product->order)>0?$arrTmp['order']=(string)$product->order:'';            	
            	strlen((string)$product->title)>0?$arrTmp['title']=(string)$product->title:'';
            	strlen((string)$product->content)>0?$arrTmp['content']=(string)$product->content:'';
            	$arrProducts[]=$arrTmp;
            	unset($arrTmp);
            }
          	foreach ($xml->solutions->children() as $solution){ 
            	strlen((string)$solution->id)>0?$arrTmp['idant']=(string)$solution->id:'';
            	strlen((string)$solution->idproduct)>0?$arrTmp['idproductant']=(string)$solution->idproduct:'';
            	$arrSolutions[]=$arrTmp;
            	unset($arrTmp);
            }
          	foreach ($xml->solutionsoptions->children() as $solutionsoption){ 
            	strlen((string)$solutionsoption->id)>0?$arrTmp['idant']=(string)$solutionsoption->id:'';
            	strlen((string)$solutionsoption->idsolution)>0?$arrTmp['idsolutionant']=(string)$solutionsoption->idsolution:'';
            	strlen((string)$solutionsoption->idstep)>0?$arrTmp['idstepant']=(string)$solutionsoption->idstep:'';
            	strlen((string)$solutionsoption->idoption)>0?$arrTmp['idoptionant']=(string)$solutionsoption->idoption:'';
            	$arrSolutionOptions[]=$arrTmp;
            	unset($arrTmp);
            }                       
          }
        }    
        $db =& JFactory::getDBO();
        $row =& $this->getTable('flow');        
        // Bind the form fields to the hello table
        if (!$row->bind($flow)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
     
        // Make sure the hello record is valid
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
     
        // Store the web link table to the database
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $idobj=$row->id;
		$steps_ant=array();		
		foreach ($arrSteps as $fieldelem){
			$row =& $this->getTable('step');
        	$fieldelem['idflow']=$idobj;
        	if (!$row->bind($fieldelem)) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$row->check()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$row->store()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }	  
	        $steps_ant[$fieldelem['idant']]=$row->id;	                   	
        }
        
		// Select all steps and update prevsteps
        $step_update=$this->getSteps($idobj);
        foreach ($step_update as $steptemp){
        	$steptemp['idprevstep']=$steps_ant[$steptemp['idprevstep']];
        	$row =& $this->getTable('step');
        	$row->load($steptemp['id']);
        	if (!$row->bind($steptemp)) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$row->check()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$row->store()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }	  
        }
                
        $products_ant=array();
        foreach ($arrProducts as $product){
	        $rowEvent =& $this->getTable('product');
        	$product['idflow']=$idobj;
        	if (!$rowEvent->bind($product)) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$rowEvent->check()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$rowEvent->store()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }	
	        $products_ant[$product['idant']]=$rowEvent->id;        	
        }
        
        $solutions_ant=array();
        foreach ($arrSolutions as $solution){
	        $rowEvent =& $this->getTable('solution');
        	$solution['idflow']=$idobj;
        	$solution['idproduct']=$products_ant[$solution['idproductant']];
        	if (!$rowEvent->bind($solution)) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$rowEvent->check()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$rowEvent->store()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }	
	        $solutions_ant[$solution['idant']]=$rowEvent->id;        	
        }

		$options_ant=array();
        foreach ($arrOptions as $option){
	        $rowEvent =& $this->getTable('option');
        	$option['idstep']=$steps_ant[$option['idstepant']];
        	if (!$rowEvent->bind($option)) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$rowEvent->check()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$rowEvent->store()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }	
	        $options_ant[$option['idant']]=$rowEvent->id;        	
        }
        
	    foreach ($arrSolutionOptions as $solutionOption){
	        $rowEvent =& $this->getTable('solutionoption');
        	$solutionOption['idstep']=$steps_ant[$solutionOption['idstepant']];
        	$solutionOption['idsolution']=$solutions_ant[$solutionOption['idsolutionant']];
        	$solutionOption['idoption']=$options_ant[$solutionOption['idoptionant']];
        	if (!$rowEvent->bind($solutionOption)) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$rowEvent->check()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }
	        if (!$rowEvent->store()) {
	            $this->setError($this->_db->getErrorMsg());
	            return false;
	        }        	
        }
        
        return $idobj;
	}
    
    // Export functions
    function getDataFlows(){
    	$db = JFactory::getDBO();
      	$query = $this->_buildQuerySelectDataFlow($this->_id);
      	$db->setQuery($query);
      	return $db->loadAssoc();
    }
	function getDataProducts(){
    	$db = JFactory::getDBO();
      	$query = $this->_buildQuerySelectDataProducts($this->_id);
      	$db->setQuery($query);
      	return $db->loadAssocList();
    }
	function getDataSolutions(){
    	$db = JFactory::getDBO();
      	$query = $this->_buildQuerySelectDataSolutions($this->_id);
      	$db->setQuery($query);
      	return $db->loadAssocList();
    }
	function getDataSolutionsOptions(){
    	$db = JFactory::getDBO();
      	$query = $this->_buildQuerySelectDataSolutionsOptions($this->_id);
      	$db->setQuery($query);
      	return $db->loadAssocList();
    }
	function getDataSteps(){
    	$db = JFactory::getDBO();
      	$query = $this->_buildQuerySelectDataSteps($this->_id);
      	$db->setQuery($query);
      	return $db->loadAssocList();
    }
	function getDataOptions(){
    	$db = JFactory::getDBO();
      	$query = $this->_buildQuerySelectDataOptions($this->_id);
      	$db->setQuery($query);
      	return $db->loadAssocList();
    }
    
 function getDataStats(){
    
    $db = JFactory::getDBO();
    
    $removestats = JRequest::getVar( 'removestats');
    
    if($removestats=="1"){
        $query=$this->_buildQueryRemoveStats();
      
      
         $db->setQuery($query);
         $db->query(); 
       
    
    }
    
    
    	
      $query=$this->_buildQuerySelectDataFlow();
      
      
      $db->setQuery($query);
      
      $arrFlows=array();
      $lstFlows=$db->loadAssocList();
        foreach ($lstFlows as $flow){
          $statflow=array();
          
          $statflow['id']=$flow['id'];
          $statflow['title']=$flow['title'];
          
          
          
          
          $query=$this->_buildQuerySelectFlowStats($flow['id']);
          $countvalues=array();
          
          $db->setQuery($query);
          $statoptions=$db->loadAssocList();
          $resultstatoptions=array();
          foreach($statoptions as $statoption){
                  $idsoptions=$statoption["optionvalues"];
                  
                  $arridsoptions=explode("#",$idsoptions);
                  
                  $arrvalues=array();
                  
                  for($i=0;$i<count($arridsoptions);$i++){
                  
                   if($arridsoptions[$i]!=""){
                     $query=$this->_buildQuerySelectOptionStats($arridsoptions[$i]);
                     
                     $db->setQuery($query);
                     $arrvalues[]=$db->loadAssoc();
                   }
                      
                  }
                  
                  
                  
                  for($c=0;$c<count($arrvalues);$c++){
                  
                  
                    if($countvalues[$arrvalues[$c]['value']]==null)
                       $countvalues[$arrvalues[$c]['value']]=0;
                       
                    $countvalues[$arrvalues[$c]['value']]=$countvalues[$arrvalues[$c]['value']]+1;  
                       
                       
                    
                    
                  }
                  
                  $statoption['lstvalues']=$arrvalues;
                  
                  $resultstatoptions[]=$statoption;
          }
          //_buildQuerySelectOptionStats
          
          $statflow['datos']=$resultstatoptions;
          
          $statflow['countvalues']=$countvalues;
          
          $arrFlows[]=$statflow;
        }
        
        
        
        
        return $arrFlows;
      
      
    }
    
    function getFlowStatsCsv(){
    
    $db = JFactory::getDBO();
    
    $idflow = JRequest::getVar( 'idflow');
    
    $query=$this->_buildQuerySelectFlowStats($idflow);
          
          $db->setQuery($query);
          $statoptions=$db->loadAssocList();
          $resultstatoptions=array();
          
          $arrcabecera=array();
          $arrcabecera[]="Date";
          $arrcabecera[]="Steps";
          
          $resultstatoptions[]=$arrcabecera;
          
          foreach($statoptions as $statoption){
                  $idsoptions=$statoption["optionvalues"];
                  
                  $arridsoptions=explode("#",$idsoptions);
                  
                  $arrvalues=array();
                  $arrvalues[]=$statoption['date'];
                  for($i=0;$i<count($arridsoptions);$i++){
                  
                   if($arridsoptions[$i]!=""){
                     $query=$this->_buildQuerySelectOptionStats($arridsoptions[$i]);
                     
                     $db->setQuery($query);
                     $resultoption=$db->loadAssoc();
                     $arrvalues[]=$resultoption['desc']." (".$resultoption['value'].")";
                   }
                      
                  }
        
                  //$statoption['lstvalues']=$arrvalues;
                  
                  $resultstatoptions[]=$arrvalues;
          }
       
          
          return $resultstatoptions;
          
        
    }
}


?>
