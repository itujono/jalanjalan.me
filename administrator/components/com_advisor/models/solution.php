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

class AdvisorModelSolution extends AdvisorModelWrapper{
    var $_id;
    var $_idflow;
    var $_pageNav;
    
    function __construct(){
        parent::__construct();
        $this->_id =null;
        $array = JRequest::getVar('cid',  -1, '', 'array');
        $this->setId((int)$array[0]);
        $this->_idflow =null;
        $this->setIdFlow(JRequest::getVar('idflow'));
    }
    function setId($id){
        $this->_id = $id;
    }
	function setIdFlow($id){
        $this->_idflow = $id;
    }
    function getIdFlow(){
    	return $this->_idflow;
    }
    function getPageNav(){
      return $this->_pageNav;
    }
    
    /** Database Section **/
    function _buildQuerySelect($id=null){
        $query = "select s.*, p.title as product, p.id as idproduct 
        	from #__advisor_solution s left join #__advisor_product p on s.idproduct=p.id  
    		where s.idflow=".$this->_idflow;
        if ($id!=null) $query .= ' and s.id='.$id;
        $query .=" order by s.idproduct";    	
        return $query;
    }    
	function _buildQuerySelectCount(){
        $query = "select count(*) from #__advisor_solution where idflow=".$this->_idflow;
        return $query;
    }
    
    function _buildQueryProduct(){
    	$query = "select * from #__advisor_product where idflow=".$this->_idflow." order by title";
        return $query;
    }
    function _buildQueryHikaProduct(){
    	$query = "select * from #__hikashop_product order by product_name";
        return $query;
    }
    
    function _buildQueryJoomlaProduct(){
    	$query = "select id as joomla_product_id, title as product_name from #__content order by title";
        return $query;
    }
    
    /*
    function _buildQueryVirtueProduct(){
    	$query = "select * from #__virtuemart_products p,#__virtuemart_products_en_gb pn where p.virtuemart_product_id=pn.virtuemart_product_id order by pn.product_name";
        return $query;
    }*/
    
     
    
	function _buildQueryStep(){
    	$query = "select * from #__advisor_step where idflow=".$this->_idflow." order by name";
        return $query;
    }
    
	function _buildQueryStepOption(){
    	$query = "select o.* 
    		from #__advisor_option o left join #__advisor_step s on o.idstep=s.id 
    		where s.idflow=".$this->_idflow." order by s.id";
        return $query;
    }
	
    function _buildDeleteSolutionOption($idsolution){
    	$query = "delete from #__advisor_solution_option  
    		where idsolution=".$idsolution;
        return $query;
    }
    
    function _buildQuerySolutions($idsolution){
    	$query = "select * from #__advisor_solution_option  
    		where idsolution=".$idsolution;
        return $query;
    }
    
	function _buildQuerySolutionsDefault(){
    	$query = "select so.idsolution, s.name as stepname, o.desc as optionname  
    		from #__advisor_solution_option so left join #__advisor_step s on so.idstep=s.id
    			left join #__advisor_option o on so.idoption=o.id";
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
    
    function getProductList(){
      $db = JFactory::getDBO();
      $query = $this->_buildQueryProduct($this->_id);
      $db->setQuery($query);
      return $db->loadObjectList();
    }
    
    function getHikaProductList(){
    	if ($this->validateComponent('hikashop')){
	      $db = JFactory::getDBO();
	      $query = $this->_buildQueryHikaProduct();
	      $db->setQuery($query);
	      return $db->loadObjectList();
    	} else {
    		return null;
    	}
    }
    
    function getJoomlaProductList(){
    	
	      $db = JFactory::getDBO();
	      $query = $this->_buildQueryJoomlaProduct();
	      $db->setQuery($query);
	      return $db->loadObjectList();
    	
    }
    
    /*
    function getVirtueProductList(){
      $db = JFactory::getDBO();
      $query = $this->_buildQueryVirtueProduct();
      $db->setQuery($query);
      return $db->loadObjectList();
    }*/
    
	function getStepList(){
      $db = JFactory::getDBO();
      $query = $this->_buildQueryStep($this->_id);
      $db->setQuery($query);
      return $db->loadObjectList();
    }
    
	function getSolutionListDefault(){
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySolutionsDefault();
      $db->setQuery($query);
      return $db->loadAssocList();
    }
    
	function getSolutionList(){
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySolutions($this->_id);
      $db->setQuery($query);
      return $db->loadAssocList('idstep');
    }
    
	function getStepOptionList(){
      $db = JFactory::getDBO();
      $query = $this->_buildQueryStepOption($this->_id);
      $db->setQuery($query);
      $data=$db->loadAssocList();
      $result=array();
      for ($i=0; $i<count($data); $i++) {
      	$dataTemp=$data[$i];
      	$option=array();
      	$option['content']=$dataTemp['content'];
      	$option['value']=$dataTemp['value'];
      	$result[$dataTemp['idstep']][]=$dataTemp;
      }
      return $result;
    }

	function store(){
    	$db =& JFactory::getDBO();
        $row =& $this->getTable('solution');        
        $data = JRequest::get( 'post' );
        $data['id']=JRequest::getVar('idsolution');
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
        $idsolution=$row->id;
        
        // Delete all solution-option
        $query = $this->_buildDeleteSolutionOption($idsolution);
		$db->setQuery( $query );
        if (!$db->query()) {
          $this->setError('Delete Error');
          return false;
        }
        
        // Insert all solution-option        
        $step_list=$this->getStepList();
        foreach ($step_list as $step){
        	$row =& $this->getTable('solutionoption');
        	$data_option['idsolution']=$idsolution;
        	$data_option['idstep']=$step->id;
        	$data_option['idoption']=JRequest::getVar('step_'.$step->id);
	        if (!$row->bind($data_option)) {
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
        return $idsolution;
    }
    
	function remove(){
    	$db =& JFactory::getDBO();                
        $cids=JRequest::getVar('cid',array(),'','array');
        foreach ($cids as $id){
        	$row =& $this->getTable('solution');
        	$row->delete( $id );	
        }
        return true;    	
    }
    
    
    
	/**	 
	 * @param string $comString
	 * @param string $validationType:<br>
	 *     100 => 1st char = validate db?<br>
	 *     010 => 2nd char = validate backend folder?<br>
	 *     001 => 3rd char = validate frontend folder?<br>
	 *     111 => validate all example<br>
	 */
	public function validateComponent($comString, $validationType = 100){
	    jimport('joomla.filesystem.folder');
	    // default result
	    $validComponent = false;
	    $validationsDone = 0;
	    // use allways as string
	    $validationType = (string)$validationType;
	    // get required validations
	    $validateDb = (isset($validationType[0]) && $validationType[0] == '1');
	    $validateBackend = (isset($validationType[1]) && $validationType[1] == '1');
	    $validateFrontend = (isset($validationType[2]) && $validationType[2] == '1');
	    // validate: extension is present and enabled on db
	    if (($validationsDone == 0 || ($validComponent)) && $validateDb) {
	        $db = JFactory::getDbo();
	        $query = $db->getQuery(true);
	        $query->select('extension_id, name');
	        $query->from('#__extensions');
	        $query->where('name='.$db->quote($comString));
	        $db->setQuery($query);
	        if($db->loadObject()) {
	            $validComponent =  true;
	        } else {
	            $validComponent =  false;
	        }
	        $validationsDone++;
	    }
	    // validate: backend folder exist
	    if (($validationsDone == 0 || ($validComponent)) && $validateBackend) {
	        $backendPath = JPATH_ADMINISTRATOR
	                        . DS .'components'
	                        . DS . $comString ;
	        $validComponent = JFolder::exists($backendPath);
	        $validationsDone++;
	    }
	    // validate: frontend folder exist
	    if (($validationsDone == 0 || ($validComponent)) && $validateFrontend) {
	        $frontendPath = JPATH_SITE
	                        . DS .'components'
	                        . DS . $comString;
	        $validComponent = JFolder::exists($frontendPath);
	        $validationsDone++;
	    }	    
	    return $validComponent;
	}
    
}


?>
