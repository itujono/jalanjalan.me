<?php
//First start with information about the Plugin and yourself. For example:
/*
 * @component List Manager
 * @copyright Copyright (C) 2011 Moonsoft.  
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
 
//To prevent accessing the document directly, enter this code:
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgSearchListmanager extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config){
		parent::__construct($subject, $config);
		
	} 
 
//Define a function to return an array of search areas. Replace 'nameofplugin' with the name of your plugin.
  function onContentSearchAreas()
  {
	static $areas = array(
		'listmanager' => 'Listmanager'
	);
	return $areas;
}
 
//The real function has to be created. The database connection should be made. 
//The function will be closed with an } at the end of the file.
function onContentSearch( $text, $phrase='', $ordering='', $areas=null )
{
	$limit			= $this->params->def('search_limit',		50);
	 $db            = JFactory::getDBO();
    $user  = JFactory::getUser(); 
  
    //If the array is not correct, return it:
    /*if (is_array( $areas )) {
            if (!array_intersect( $areas, array_keys( plgSearchproductoAreas() ) )) {
                    return array();
            }
    } */
   
    //Define the parameters. First get the right plugin; 'search' (the group), 'nameofplugin'. 
    $plugin = JPluginHelper::getPlugin('search', 'listmanager');
     
    //Then load the parameters of the plugin.
    //$pluginParams = new JParameter( $plugin->params );
     
    //Now define the parameters like this:
    //$limit = $pluginParams->def( 'nameofparameter', defaultsetting );
     
    //Use the function trim to delete spaces in front of or at the back of the searching terms
    $text = trim( $text );
     
    //Return Array when nothing was filled in.
    if ($text == '') {
            return array();
    }
  
  //ordering of the results
    switch ( $ordering ) {
  
  //alphabetic, ascending
            case 'alpha':
                    $order = 'v.value ASC';
                    break;
  
  //oldest first
            case 'oldest':
  
  //popular first
            case 'popular':
  
  //newest first
            case 'newest':
  
  //default setting: alphabetic, ascending
            default:
                    $order = 'v.value ASC';
    }
  
  //replace nameofplugin
    $searchtarget = JText::_( 'ListManager' );
  /*
  //the database query; differs per situation! It will look something like this:
    $query = 'select distinct l.name as title,l.id as id,l.info as text,m.params as params, '
    . ' "1" AS browsernav'
    . ' from #__listmanager_listing l,#__listmanager_field f,#__listmanager_values v,#__modules m'  
    . ' WHERE l.id=f.idlisting and f.id=v.idfield and ( '. $where .' )'   
    . ' and m.module=\'mod_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"\',l.id,\'"%\')'
    . ' and m.params like \'%"searchable":"1"%\'' 
    . ' union'
    . ' select distinct l.name as title,l.id as id,l.info as text,m.params as params, '
    . ' "1" AS browsernav'
    . ' from #__listmanager_listing l,#__listmanager_field f,#__listmanager_values v,#__menu m,#__extensions e'  
    . ' WHERE e.extension_id=m.component_id and l.id=f.idlisting and f.id=v.idfield and ( '. $where .' )'   
    . ' and e.element=\'com_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"\',l.id,\'"%\')'
    . ' and m.params like \'%"searchable":"1"%\'' 
    //. ' ORDER BY '. $order
    ;
  
  //Set query
    $db->setQuery( $query, 0, $limit );
    $rows = $db->loadObjectList();
    
    
    // Views    
    $query = 'select distinct l.name as title,l.id as id,l.comments as text,m.params as params, '
    . ' "1" AS browsernav,if(m.params like \'%"access_type":"1"%\',1,0) as access_type, l.idlisting '
    . ' from #__listmanager_view l,#__listmanager_field f,#__listmanager_values v,#__modules m'  
    . ' WHERE l.idlisting=f.idlisting and f.id=v.idfield and ( '. $where .' )'   
    . ' and m.module=\'mod_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"v_\',l.id,\'"%\')'
    . ' and m.params like \'%"searchable":"1"%\'' 
    . ' union'
    . ' select distinct l.name as title,l.id as id,l.comments as text,m.params as params, '
    . ' "1" AS browsernav ,if(m.params like \'%"access_type":"1"%\',1,0) as access_type, l.idlisting ' 
    . ' from #__listmanager_view l,#__listmanager_field f,#__listmanager_values v,#__menu m,#__extensions e'  
    . ' WHERE e.extension_id=m.component_id and l.idlisting=f.idlisting and f.id=v.idfield and ( '. $where .' )'   
    . ' and e.element=\'com_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"v_\',l.id,\'"%\')'
    . ' and m.params like \'%"searchable":"1"%\'' 
    //. ' ORDER BY '. $order
    ;
    
    $db->setQuery( $query, 0, $limit );
    $rowsModule = $db->loadObjectList();
    */
    
    
    switch ($phrase) {
    	case 'exact':
    		$phrase=0;
    		break;
    	case 'all':
    	case 'any':
    	default:
    		$phrase=1;
    		break;
    }
    
    $query = 'select distinct l.id as id from #__listmanager_listing l,#__modules m'  
	    . ' WHERE m.module=\'mod_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"\',l.id,\'"%\')'
	    . ' and m.params like \'%"searchable":"1"%\'' 
	    . ' union select distinct l.id as id from #__listmanager_listing l,#__menu m,#__extensions e'
    	. ' WHERE e.extension_id=m.component_id '
    	. ' and e.element=\'com_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"\',l.id,\'"%\')'
    	. ' and m.params like \'%"searchable":"1"%\'' ;    
    //Set query
    $db->setQuery( $query, 0, $limit );
    $idlistas = $db->loadColumn(0);
    
    $query = 'select distinct l.id as id from #__listmanager_view l,#__modules m'  
    . ' WHERE m.module=\'mod_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"v_\',l.id,\'"%\')'
    . ' and m.params like \'%"searchable":"1"%\'' 
    . ' union select distinct concat("v_",l.id) as id ' 
    . ' from #__listmanager_view l,#__menu m,#__extensions e'  
    . ' WHERE e.extension_id=m.component_id '   
    . ' and e.element=\'com_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"v_\',l.id,\'"%\')'
    . ' and m.params like \'%"searchable":"1"%\'';
   //Set query
   $db->setQuery( $query, 0, $limit );
   $idvistas = $db->loadColumn(0);
   $idlistasvistas=array_merge($idlistas,$idvistas);
   
    
    $allfilters=array();    
    foreach($idlistasvistas as $id) :    	
    	$searchFilter=$this->getDataRecordsPreWrapper($id,$text,$phrase);
    	$allfilters=array_merge($allfilters,$searchFilter);
    endforeach;
    
    $allfilters=array_unique($allfilters);
    
    $rows=array();
    //var_dump($allfilters);
    foreach ($allfilters as $idviewlist):
    	if ($this->isView($idviewlist)): //view
    		$idviewlist=substr($idviewlist, 2);
    		$query = 'select distinct l.name as title,l.id as id,l.comments as text,m.params as params, '
    			. ' "1" AS browsernav,if(m.params like \'%"access_type":"1"%\',1,0) as access_type, l.idlisting, "" as section, "" as created '
    			. ' from #__listmanager_view l,#__listmanager_field f,#__listmanager_values v,#__modules m'
    			. ' WHERE l.idlisting=f.idlisting and f.id=v.idfield and l.id='. $idviewlist
    			. ' and m.module=\'mod_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"v_\',l.id,\'"%\')'
    			. ' and m.params like \'%"searchable":"1"%\''
    			. ' union'
    			. ' select distinct l.name as title,l.id as id,l.comments as text,m.params as params, '
    			. ' "1" AS browsernav ,if(m.params like \'%"access_type":"1"%\',1,0) as access_type, l.idlisting, "" as section, "" as created '
    			. ' from #__listmanager_view l,#__listmanager_field f,#__listmanager_values v,#__menu m,#__extensions e'
    			. ' WHERE e.extension_id=m.component_id and l.idlisting=f.idlisting and f.id=v.idfield and l.id='. $idviewlist
    			. ' and e.element=\'com_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"v_\',l.id,\'"%\')'
    			. ' and m.params like \'%"searchable":"1"%\'';
    		$db->setQuery( $query, 0, $limit );
    		$rowsTmp = $db->loadObjectList();
    	else: //list    	
		    $query = 'select distinct l.name as title,l.id as id,l.info as text,m.params as params, '
		    		. ' "1" AS browsernav, "" as section, "" as created'
		    		. ' from #__listmanager_listing l,#__listmanager_field f,#__listmanager_values v,#__modules m'
		    		. ' WHERE l.id=f.idlisting and f.id=v.idfield and l.id='. $idviewlist
		    		. ' and m.module=\'mod_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"\',l.id,\'"%\')'
		    		. ' and m.params like \'%"searchable":"1"%\''
		    		. ' union'
		    		. ' select distinct l.name as title,l.id as id,l.info as text,m.params as params, '
		    		. ' "1" AS browsernav, "" as section, "" as created'
		    		. ' from #__listmanager_listing l,#__listmanager_field f,#__listmanager_values v,#__menu m,#__extensions e'
		    		. ' WHERE e.extension_id=m.component_id and l.id=f.idlisting and f.id=v.idfield and l.id='. $idviewlist
		    		. ' and e.element=\'com_listmanager\' and m.published=1 and m.params like CONCAT(\'%"prefsids":"\',l.id,\'"%\')'
		    		. ' and m.params like \'%"searchable":"1"%\'';
    		$db->setQuery( $query, 0, $limit );
    		$rowsTmp = $db->loadObjectList();
    	endif;
    	if ($rowsTmp!=null) $rows=array_merge($rows,$rowsTmp);    	
    endforeach;    
    
    //The 'output' of the displayed link
    
    foreach($rows as $key => $row) {    		
            $temp1=str_replace("{","",$rows[$key]->params);
            $temp2=str_replace("}","",$temp1);
            $p=explode(",",$temp2);
            
            $linki="";
            foreach($p as $param){
            
            $fparam=explode(':',$param);
			$name=$fparam[0];
             
              if($name=="\"link_search\""&&strlen($param)>16){
                $linki=substr($param,15,strlen($param)-16);
                //$linki=$param;
              }  
            }
            $linki=str_replace('\\', '', $linki);  
            if($linki!="")
              $rows[$key]->href = $linki; 
    } 
    
    //Return the search results in an array
    return $rows;
}

function _buildQueryView($idview){
  	 if (!is_numeric($idview)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select * from #__listmanager_field_view where idview=".$idview;
      return $query;
  }
function _buildQueryRecords($id){    
      if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));    
      $query = "SELECT v.id,v.idfield,v.value,v.idrecord,f.type,f.decimal,f.order,f.size,f.name,f.visible,f.total, f.autofilter,f.defaulttext      
      			FROM #__listmanager_values v,#__listmanager_field f
      			where f.id=v.idfield and  idlisting=".$id;     
      return $query;
  }
function getFieldsView($idview){
  	$db = JFactory::getDBO();
  	$query = $this->_buildQueryView($idview);
  	$db->setQuery($query);
    return $db->loadObjectList('idfield');
  }  
  
function getDataRecordsPreWrapper($id,$filter,$phrase) {
  	$db = JFactory::getDBO();
  	$isView=$this->isView($id);
  
  	$fieldsView=array();
  	if ($isView){
  		$idview=substr($id, 2);
  		$fieldsView=$this->getFieldsView($idview);
  		$list_view_data=$this->getListView($idview,$isView);
  	} else {
  		$list_view_data=$this->getListView($id,$isView);
  	}
  	$originid=$id;
  	$id=$this->checkId($id);
  
  	// List Config
  	$lstConfig=$this->_getListing($id);
  
  	// Filters
  	//$allfilters=array();
  	$viewfilters=array();
  	$filters=array();
  	$applyFilters=false;
  	// View Filter
  	if($isView){
  		$spdate_format=$lstConfig['date_format_bbdd'];
  		$otherparams=array('dateformat'=>$spdate_format);
  		foreach ($fieldsView as $fview){
  			if($fview->filter_type!='-1'){
  				$applyFilters=true;
  				$fv=$this->getFieldById($fview->idfield);
  				$otherparams['type']=$fv->type;
  				$query=$this->_buildQuerySelectRecordsApplyFilterView($id, $fview->idfield, $fview->filter_value, $fview->filter_type,$otherparams);
  				$db->setQuery($query);  				
  				$arrIntFilter=$db->loadColumn();
  				//var_dump($arrIntFilter);
  				if(count($arrIntFilter)>0) $filters[]=$originid;
  				//$viewfilters=$arrIntFilter;
  				//$filters[]=$arrIntFilter;
  				//$allfilters=array_merge($allfilters,$arrIntFilter);
  				unset($arrIntFilter);
  			}
  		}
  	}  	
  	// Query filters
  	//if($filter!=null && count(json_decode(stripslashes($filter)))>0 && $isFilter){
  	if($filter!=null){
  		$applyFilters=true;
  		//$filter_dec=json_decode(stripslashes($filter));
  		//$filter_dec=json_decode($filter);
  		$filter_dec=explode(' ',$filter);
  		//foreach ($filter_dec as $key=>$value){  		
  			for($i=0;$i<count($filter_dec);$i++){
  				$tmp_filter=$filter_dec[$i];
  				
  				//$tmp_filter=$filter_dec;
  				$query="";
  				$tmpField=0;
  				$searchFilter=false;  				 
  					// Global search
  					$querySearch=$this->_buildQuerySelectRecordsApplyFilterGlobal($id, $tmp_filter,0,$phrase);
  					$db->setQuery($querySearch);
  					$restemp=$db->loadColumn();  					
  					if(count($restemp)>0)$searchFilter=true;
  					//foreach ($restemp as $rst){$searchFilter[]=$rst;}
  					// Buscar todos los mv que son válidos
  					$querySearch=$this->_buildQuerySelectSearchMultivalue($id, $tmp_filter,0,$phrase);
  					$db->setQuery($querySearch);
  					$mvs=$db->loadColumn();
  					// Seleccionar todos los registros
  					$querySearch=$this->_buildQuerySelectSearchMultivalueAllValues($id);
  					$db->setQuery($querySearch);
  					$all=$db->loadObjectList();
  					if ($mvs):
	  					foreach ($all as $item){
	  						// Implode # y recopilar los que tengan ese mv
	  						$arrItemTemp=explode('#', $item->value);
	  						foreach ($arrItemTemp as $itemInArray){
	  							if (in_array($itemInArray, $mvs)){
	  								// 	Añadir a la lista
	  								//$searchFilter[]=$item->idrecord;
	  								$searchFilter=true;
	  								break;
	  							}
	  						}
	  					}
  					endif;
  					// TODO Buscar los option que se forman por SQL
  					// Buscar todos los de usuario
  					$querySearch=$this->_buildQuerySelectRecordsApplyFilterGlobalUser($id, $tmp_filter);
  					$db->setQuery($querySearch);
  					$restemp=$db->loadColumn();
  					//foreach ($restemp as $rst){$searchFilter[]=$rst;}
  					if(count($restemp)>0)$searchFilter=true;
  					
  					if ($searchFilter){
  						//$filters[]=$searchFilter;
  						$filters[]=$originid;
  						//$allfilters=array_merge($allfilters,$searchFilter);
  						//unset($searchFilter);
  					}
  				
  				unset($arrIntFilter);
  			}
  		}  		
  		return $filters;  		
  
  	}

  	function getListView($id,$isView){
  		$db = JFactory::getDBO();
  		if($isView){
  			$query=$this->_buildQuerySelectViewConf($id);
  		} else {
  			$query=$this->_buildQuerySelect($id);
  		}
  		$db->setQuery($query);
  		return $db->loadObject();
  	}
  	function _buildQuerySelectViewConf($id=null){
  		$db =JFactory::getDBO();
  		$query = "select l.*, v.name, v.comments, v.default_order
        	from #__listmanager_listing l left join #__listmanager_view v  on l.id=v.idlisting ";
  		if ($id!=null){
  			if (is_numeric($id)) $query .= ' where v.id ='.$db->quote($id);
  			else JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
  		}
  		return $query;
  	}
  	
  	
  	function checkId($id){
  		if (!$this->isView($id))
  			return $id;
  		else
  			return $this->getFieldView(substr($id, 2));
  	}
  	function isView($id){
  		return (strrpos($id,'v_')!==false);
  	}
  	function getFieldView($id) {
  		$db = JFactory::getDBO();
  		$query = $this->_buildQuerySelectView($id);
  		$db->setQuery($query);
  		return $db->loadResult();
  	}
  	function _getListing($idlisting) {
  		$db = JFactory::getDBO();
  		$query = $this->_buildQuerySelect($idlisting);
  		$db->setQuery($query);
  		return $db->loadAssoc();
  	}
  	function _buildQuerySelect($id=null){
  		$query = "select * from #__listmanager_listing ";
  		if ($id!=null) $query .= 'where id='.$id;
  		return $query;
  	}
  	function getFieldById($id) {
  		$db = JFactory::getDBO();
  		$query = $this->_buildQuerySelectFieldById($id);
  		$db->setQuery($query);
  		return $db->loadObject();
  	}
  	function _buildQuerySelectFieldById($id){
  		if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
  		$query = "select * from #__listmanager_field where id=".$id;
  		return $query;
  	}
  	function _buildQuerySelectRecordsApplyFilterView($id, $idfield, $values, $type,$otherparams){
  		if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
  		if (!is_numeric($idfield)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
  		$db = JFactory::getDBO();
  		$values_none=$values;
  		if (preg_match('((\w)+(\(){1}(\w)*(\)){1})',$values)!==1)
  			$values_none=$db->quote($values);
  		$values_start=$this->getEscapedWrapper($values,true).'%';
  		$values_like='%'.$this->getEscapedWrapper($values,true).'%';
  		$query = "select v.idrecord
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield
			where f.idlisting=".$id." and v.idfield=".$idfield." and ";
  		$vvalue=' v.value ';
  		if ($otherparams['type']==1 || $otherparams['type']==12)
  			$vvalue = " STR_TO_DATE(v.value,'".$this->__jQueryUIDatePickerFormatToDateFormat($otherparams['dateformat'])."') ";
  	
  		switch ($type){
  			case 0: // Equal
  				$query .= $vvalue." = ".$values_none; break;
  			case 1: // Less
  				$query .= $vvalue." < ".$values_none; break;
  			case 2: // More
  				$query .= $vvalue." > ".$values_none; break;
  			case 3: // Starts with
  				$query .= $vvalue." like ".$db->quote($values_start); break;
  			case 4: // Contains
  				$query .= $vvalue." like ".$db->quote($values_like); break;
  		}
  		return $query;
  	}
  	function getEscapedWrapper($text, $extra = false){
  		$db = JFactory::getDBO();
  		return $db->escape($text,$extra);
  	}
  	function __jQueryUIDatePickerFormatToDateFormat($dateFormat) {
  		$chars = array(
  				// Day
  				'dd' => '%e', 'd' => '%d', 'DD' => '%W', 'D' => '%D',
  				// Month
  				'mm' => '%m', 'm' =>'%m', 'MM' => '%M', 'M' => '%M',
  				// Year
  				'yy' => '%Y', 'y' => '%y',
  		);
  		return strtr((string)$dateFormat, $chars);
  	}
  	function _buildQuerySelectRecordsApplyFilterGlobal($id, $values, $search_type=0,$phrase){
  		if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
  		$db = JFactory::getDBO();
  		$query = "select distinct(v.idrecord)
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield
			where f.idlisting=".$id." and f.searchable=1 and ";
  		if ($values!=null){  			 				
  			$valueslike = '%'.$this->getEscapedWrapper($values,true).'%';
  			if ($phrase!=0) $query .= " v.value=".$db->quote($values)." ";
  			else $query .= " v.value like ".$db->quote($valueslike)." ";  			  			
  		}
  		$query .= "  ";
  		//var_dump($query);
  		return $query;
  	}
  	function _buildQuerySelectSearchMultivalue($id, $value, $search_type=0,$phrase){
  		$db = JFactory::getDBO();
  		if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
  		if($phrase==0) $value=$this->getEscapedWrapper($value,true);
  		else $value='%'.$this->getEscapedWrapper($value,true).'%';
  		$query = "select v.id from #__listmanager_field f,
    		#__listmanager_field_multivalue v where f.idlisting=".$id." and  f.id=v.idfield and ";
  		$query .="v.name like ".$db->quote($value);
  		return $query;
  	}
  	function _buildQuerySelectSearchMultivalueAllValues($id){
  		if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
  		$query = "select v.idrecord,v.value from #__listmanager_field f,
    		#__listmanager_values v where f.idlisting=".$id." and  f.id=v.idfield
        	and f.type in (2,10,11,16) and v.value is not null and v.value !=''";
  		return $query;
  	}
  	function _buildQuerySelectRecordsApplyFilterGlobalUser($id, $values, $idfield=null){
  		if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
  		$db = JFactory::getDBO();
  		$query = "select distinct(v.idrecord)
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield
        		left join #__users u on v.value=u.id
			where f.type=6 and f.idlisting=".$id."  ";
  		if ($idfield!=null){
  			$query .= " and v.idfield = ".$idfield;
  		}
  		if ($values!=null){
  			if (is_array($values)){
  				for($i=0;$i<count($values);$i++){
  					if ($i>0) $query .= " or ";
  					else $query .= " and ";
  					$values_pre = '%#'.$this->getEscapedWrapper($values[$i],true).'%';
  					$values_all = '%#'.$this->getEscapedWrapper($values[$i],true).'#%';
  					$values_post = '%'.$this->getEscapedWrapper($values[$i],true).'#%';
  					$query .= " (u.name like ".$db->quote($values_pre)." or u.name like ".$db->quote($values_all)."
		         					or u.name like ".$db->quote($values_post)." or u.name = ".$db->quote($values[$i]).")";
  				}
  			} else {
  				$values = '%'.$this->getEscapedWrapper($values,true).'%';
  				$query .= " and u.name like ".$db->quote($values);
  			}
  		}
  		$query .= "  ";
  		return $query;
  	}
  	function _buildQuerySelectView($id){
  		$query = "select idlisting from #__listmanager_view where id=".$id;
  		return $query;
  	}
}
