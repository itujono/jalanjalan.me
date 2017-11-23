<?php
/*
 * @component List Manager 
 * @copyright Copyright (C) November 2017. 
 * @license GPL 3.0 
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details. 
 * See <http://www.gnu.org/licenses/>. 
 * More info www.moonsoft.es 
 * gestion@moonsoft.es
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
include JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'main.model.php';
//JLoader::register('ListmanagerMain',  JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'main.model.php');

class ListmanagerModelServerpages extends ListmanagerMain
{
    
    var $_data;
    var $_prefs=array();
    var $_log=array();
 
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuerySelect($id=null){
           
        $db =JFactory::getDBO();
        $query = "select * from #__listmanager_listing ";
        if ($id!=null){
          if (is_numeric($id)) $query .= 'where id ='.$db->quote($id);
          else JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        }        
        return $query;
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

    
    
    
 function _buildQuerySelectFieldsOne2View($id,$idview,$export=false){  
     if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
     if (!is_numeric($idview)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    
     $defaultcondition="ifnull(v.defaulttext,f.defaulttext)";
     if(!defined('DS')){
     	define('DS',DIRECTORY_SEPARATOR);
	 }
     if(file_exists (dirname(__FILE__).DS.'customserverpages.php')){
     	include(dirname(__FILE__).DS.'customserverpages.php');
     }
     $query = "select f.id,f.mandatory, f.idlisting,f.type, f.`decimal`,f.`order`,
                f.size,f.name,f.innername,f.limit0,f.limit1,f.multivalue,f.sqltext,f.total, f.readmore, f.readmore_word_count,
                f.link_type, f.link_width, f.link_height,f.innername,f.bulk,f.link_url,f.link_id,f.link_detail,f.css,
                ifnull(v.visible,f.visible) as visible,
                ifnull(v.autofilter,f.autofilter) as autofilter,
                ifnull(v.showorder,f.showorder) as showorder,
                ".$defaultcondition." as defaulttext,
                ifnull(v.order,f.order) as `order`,
                f.exportable                
            from #__listmanager_field f left join #__listmanager_field_view v on f.id=v.idfield and v.idview=".$idview."
                where f.idlisting=".$id;     
      if ($export) $query.=" and f.exportable='1' ";
      $query.=" order by v.`order`, f.`order` asc";      
      return $query;
  }
    
 
    
    
    /**
     *Cabecera de la lista
     */         
    protected function _buildQuery($id){
      if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select u.id, u.name, u.info 
          from #__listmanager_listing u ";
      if ($id!=null) $query .= 'where u.id='.$id;        
      return $query;      
      
   }
   
   
    function _buildQuerySelectFieldsOne($id,$type=null){
        if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "select *
          from #__listmanager_field  where idlisting=".$id;
        if ($type!=null) $query .= ' and type='.$type;
        $query.=" order by `order` asc";
        return $query;
    }
    
    function _buildSubqueryRecords($id,$from=null,$rp=null){
    	if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	if ($from!=null && !is_numeric($from)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	if ($rp!=null && !is_numeric($from)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query="select distinct(v.idrecord) 
    			from #__listmanager_values v left join #__listmanager_field f on f.id=v.idfield
    			where f.idlisting=".$id." order by idrecord
    			limit ".$from.",".$rp;
    	return $query;
    }
    
    function _buildQueryRecords($id,$records=null){    
      if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));    
		$query = "SELECT v.id,v.idfield, v.value as value, v.idrecord as record, v.idrecord,f.type,
			f.decimal,f.order,f.size,f.name,f.innername,f.visible,f.sqltext,f.validate,f.limit0, f.limit1, f.exportable, 
			f.link_type, f.link_width, f.link_height,f.bulk,f.link_url,f.link_id,f.css
		  FROM #__listmanager_values v,#__listmanager_field f 
		  where f.id=v.idfield 
		  and idlisting=".$id;
		  if ($records!=null && count($records)>0 && !empty($records)) {
	      	$query.=" and v.idrecord in (".implode(',', $records).")";
	      }
		$query .= " union
			SELECT r.id,f.id, sum(r.rate)/count(*) as value, r.idrecord as record, r.idrecord,f.type,f.decimal,
		    f.order,f.size,f.name,f.innername,f.visible,f.sqltext,f.validate,f.limit0, f.limit1 , f.exportable, 
		    f.link_type, f.link_width, f.link_height,f.bulk,f.link_url,f.link_id,f.css
		  FROM #__listmanager_rate r,#__listmanager_field f 
		  where f.id=r.idfield 
		  and f.idlisting=".$id;
    	if ($records!=null && count($records)>0 && !empty($records)) {
	      	$query.=" and r.idrecord in (".implode(',', $records).")";
	      }    	  
		  $query .= " group by r.idrecord";
    	if ($records!=null && count($records)>0 && !empty($records)) {
	      	$query.=" order by find_in_set(record,'".implode(',', $records)."')";  
	      }     
      return $query;
  }
  
	function _buildQueryRecordsByRecordsid($id,$records){    
      if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));    
      $query = "SELECT v.id,v.idfield,
      				IF(f.type<>15,v.value,
			      		(select sum(r.rate)/count(*) from #__listmanager_rate r where r.idlisting=".$id." and r.idrecord=v.idrecord and r.idfield=f.id)) as value,
			      v.idrecord,f.type,f.decimal,f.order,f.size,f.name,f.visible,f.total, f.autofilter,f.defaulttext, f.exportable, 
			      f.link_type, f.link_width, f.link_height,f.bulk,f.link_url,f.link_id
      			FROM #__listmanager_values v,#__listmanager_field f
      			where f.id=v.idfield and  idlisting=".$id.' and v.idrecord in (\''.str_replace('#','\',\'',$records).'\') 
      			ORDER BY find_in_set(v.idrecord, \''.str_replace('#',',',$records).'\');';           
      return $query;
  }
  
  
   function _buildQuerySelectFieldsOne2($id,$export=false){
   
     if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select * from #__listmanager_field  where idlisting=".$id;
      if ($export) $query.=" and exportable='1' ";
      $query.=" order by `order` asc";
      return $query;
  }
  
  function _buildQueryView($idview){
  	 if (!is_numeric($idview)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select * from #__listmanager_field_view where idview=".$idview;
      return $query;
  }
  
  
  function _buildQuerySelectView($id){
        $query = "select idlisting from #__listmanager_view where id=".$id;        
        return $query;
    }
    
  function _buildQuerySelectViewAll($id){
        $query = "select * from #__listmanager_view where id=".$id;        
        return $query;
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
	function __jQueryUIDatePickerFormatToPhpFormat($dateFormat) {
		$chars = array(
				// Day
				'dd' => 'd', 'd' => 'j', 'DD' => 'l', 'D' => 'D',
				// Month
				'mm' => 'm', 'm' =>'n', 'MM' => 'F', 'M' => 'M',
				// Year
				'yy' => 'Y', 'y' => 'y',
		);
		return strtr((string)$dateFormat, $chars);
	}
    
function _buildQuerySelectRecordsOrderWrapper($id,$arrSorts,$arrIds){  
   //var_dump($arrSorts);  
  	$query = "select distinct(v.idrecord)";  	
  	$multivalueSort=null;
  	$field_id='';	
	//var_dump($arrSorts);
	//if (count($arrSorts)>1):
	  	for($i=0;$i<count($arrSorts);$i++){	
	  		$sort=$arrSorts[$i];
		  	if ($sort['type']=='15'){
		      	$query .= ",(select IFNULL(sum(r.rate)/count(*),0) from #__listmanager_rate r 
								where r.idlisting=".$sort['idlisting']." and r.idrecord=v.idrecord 
	         					and r.idfield=".$sort['id'].") as values".$i." ";
		    } elseif ($sort['type']=='1' || $sort['type']=='12'){
		      	$query .= ",(select STR_TO_DATE(v".$i.".value,'".$this->__jQueryUIDatePickerFormatToDateFormat($sort['date_format'])."') from #__listmanager_values v".$i." where v".$i.".idfield=".$sort['id']." and v".$i.".idrecord=v.idrecord) as values".$i." ";
		      	//$query .= ",STR_TO_DATE(ifnull(v.value,''),'".$this->__jQueryUIDatePickerFormatToDateFormat($sort['date_format'])."') as values".$i." ";
		    } elseif (($sort['type']=='2' || $sort['type']=='10' || $sort['type']=='11')&&$sort['sql']==''){
		    	$multivalueSort="values".$i;
				
				//TWEAK PARA ORDENAR POR VALOR DEL COMBO EN VEZ DEL NAME!
		      	//$query .= ",(select mi".$i.".name from #__listmanager_field_multivalue mi".$i." where mi".$i.".id=v.value and mi".$i.".idfield=f.id) as values".$i." ";
				//$query .= ",(select mi".$i.".value from #__listmanager_field_multivalue mi".$i." where mi".$i.".id=v.value and mi".$i.".idfield=f.id and f.id=".$sort['id'].") as values".$i." ";
				
				//2015/09/26. Cambio query para multiple order
				$query.=",(select mi".$i.".value from #__listmanager_field_multivalue mi".$i.",#__listmanager_values val".$i." where
	   mi".$i.".id=val".$i.".value and mi".$i.".idfield=".$sort['id']." and val".$i.".idrecord=v.idrecord limit 1) as values".$i." ";
				
				/*
	
	(select mi1.value from ojes9_listmanager_field_multivalue mi1,ojes9_listmanager_values val1 where
	   mi1.id=val1.value and mi1.idfield=88 and val1.idrecord=v.idrecord) as values1 */
				
		    } else {
		      	$query .= ",(select v".$i.".value from #__listmanager_values v".$i." where v".$i.".idfield=".$sort['id']." and v".$i.".idrecord=v.idrecord limit 1) as values".$i." ";
		    	//$query .= ",(select distinct(v".$i.".value) from #__listmanager_values v".$i." where v".$i.".idfield=".$sort['id']." and v".$i.".idrecord=v.idrecord) as values".$i." ";
		      	//$query .= ",v.value as values".$i." ";
		    } 
		    // 20150225 Lo quitamos pero en algún caso podría ser necesario
		    //$field_id=$sort['id'];
	  	}  
	/*elseif (count($arrSorts)==1):
		$sort=$arrSorts[0];
		if ($sort['type']=='15'){
			$query .= ",(select IFNULL(sum(r.rate)/count(*),0) from #__listmanager_rate r
									where r.idlisting=".$sort['idlisting']." and r.idrecord=v.idrecord
		         					and r.idfield=".$sort['id'].") as values0 ";
		} elseif ($sort['type']=='1' || $sort['type']=='12'){
			$query .= ",STR_TO_DATE(v.value,'".$this->__jQueryUIDatePickerFormatToDateFormat($sort['date_format'])."') as values0 ";
		} elseif (($sort['type']=='2' || $sort['type']=='10' || $sort['type']=='11')&&$sort['sql']==''){
			$multivalueSort="values0";
			$query.=",(select mi0.value from #__listmanager_field_multivalue mi0,#__listmanager_values val0 where
		   		mi0.id=val0.value and mi0.idfield=".$sort['id']." and val0.idrecord=v.idrecord limit 1) as values0 ";		
		} else {
			//$query .= ",v.value as values0 ";
			$query .= ",(select v0.value from #__listmanager_values v0 where v0.idfield=".$sort['id']." and v0.idrecord=v.idrecord) as values0 ";
		}
	endif;*/
  	if ($multivalueSort==null):
  		//$query.=" from #__listmanager_field f left join #__listmanager_values v on v.idfield=f.id  where f.idlisting=".$id.' and f.id in ('.implode(',', $field_id_arr).') ' ;
  		$query.=" from #__listmanager_field f left join #__listmanager_values v on v.idfield=f.id  where f.idlisting=".$id;
  		if ($field_id!=''): 
  			$query.=' and f.id = '.$field_id;
  		endif;
  	else :
  		// having values0 is not null  		 
  		//$query.=" from #__listmanager_field f left join #__listmanager_values v on v.idfield=f.id  where f.idlisting=".$id.' and f.id in ('.implode(',', $field_id_arr).') ';
  		$query.=" from #__listmanager_field f left join #__listmanager_values v on v.idfield=f.id  where f.idlisting=".$id;
  		if ($field_id!=''): 
  			$query.=' and f.id = '.$field_id;
			
  		endif;
  		$query.=" having ".$multivalueSort." is not null ";
  	endif;
  	if ($arrIds!=null && isset($arrIds) && count($arrIds)>0)
  		$query.=" and v.idrecord in (".implode(',', array_unique($arrIds)).")";
  	if (count($arrSorts)>0)
  		$query.=" order by ";
  	for($i=0;$i<count($arrSorts);$i++){
  		$sort=$arrSorts[$i];
  		if ($i!=0) $query.=" , ";
  		if ($sort['type']=='14'||$sort['type']=='0'||$sort['type']=='8'||$sort['type']=='19') 
  			$query.=" CAST(values".$i." AS SIGNED) ".$sort['order'];
  		else
  			$query.=" values".$i." ".$sort['order'];
  	}  	
  	//var_dump($query);
  	return $query;
  }    
  function _buildQuerySelectRecordsFiltered($id,$allfilters,$from,$rp){
        $query = "select distinct(v.idrecord) 
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield 
			where f.idlisting=".$id;
        if ($allfilters!=null && isset($allfilters) && count($allfilters)>0)
        	$query.=" and v.idrecord in (".implode(',', $allfilters).")";
        if ($from!=null && $rp!=null)
        	$query .= " limit ".$from.",".$rp;       
        return $query;
    } 
    
    function _buildQuerySelectRecordsApplyFilterUser($id, $iduser){
        $query = "select v.idrecord 
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield 
			where f.idlisting=".$id." and f.type=6";
        $query .= " and v.value ='".$iduser."' ";
        return $query;
    } 
  
  function _buildQuerySelectRecordsFilteredCount($id,$allfilters){
        $query = "select count(distinct(v.idrecord)) 
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield 
			where f.idlisting=".$id;
 		if ($allfilters!=null && isset($allfilters) && count($allfilters)>0)
        	$query.=" and v.idrecord in (".implode(',', $allfilters).")"; 
        return $query;
    } 

  function _buildQuerySelectRecordsApplyFilter($id, $idfield, $values=null, $search_type=0){
  		$db = JFactory::getDBO();
        $query = "select v.idrecord 
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield 
			where f.idlisting=".$id." and v.idfield=".$idfield." and ";
  		if ($values!=null){
  			switch($search_type):
  				case 0:
  					//$values = $this->getEscapedWrapper($values,true);
  					$valueslike= '%'.$this->getEscapedWrapper($values,true).'%';
        			//$query .= " value like ".$db->quote($values);
        			$query .= " ((f.searchtype=1 and v.value=".$db->quote($values).") or (f.searchtype=0 and v.value like ".$db->quote($valueslike)."))";
  					break;
  				case 1:
  					$values_arr = preg_split('/\s+/', $values);
  					$query_arr=array();
  					foreach ($values_arr as $val_item):
  						$valueslike = '%'.$this->getEscapedWrapper($val_item,true).'%';
  						//$query_arr[]= " value like ".$db->quote($valueslike)." ";
  						$query_arr[]= " ((f.searchtype=1 and v.value=".$db->quote($values).") or (f.searchtype=0 and v.value like ".$db->quote($valueslike)."))";
  					endforeach;
  					$query .='('.implode(' or ',$query_arr).')';
  					break;
  			endswitch;
        }
        return $query;
    } 
    function _buildQuerySelectRecordsApplyFilterStrict($id, $idfield, $values=null){
    	$db = JFactory::getDBO();
        $query = "select v.idrecord 
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield 
			where f.idlisting=".$id." and v.idfield=".$idfield." and ";
  		if ($values!=null){
  			$values=$db->quote($values);
        	$query .= " v.value = ".$values." ";
        }
        return $query;
    } 
    function _buildQuerySelectRecordsApplyFilterRangeDate($id, $idfield, $values, $date_format){
    	$db = JFactory::getDBO();
    	$query = "select v.idrecord
    	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield
    	where f.idlisting=".$id." and v.idfield=".$idfield." and ";
    	if ($values!=null){    		
    		$query .= "( STR_TO_DATE(v.value,'".$this->__jQueryUIDatePickerFormatToDateFormat($date_format)."') >= STR_TO_DATE(".$db->quote($values->f).",'".$this->__jQueryUIDatePickerFormatToDateFormat($date_format)."') ";
    		$query .= " and STR_TO_DATE(v.value,'".$this->__jQueryUIDatePickerFormatToDateFormat($date_format)."') <= STR_TO_DATE(".$db->quote($values->t).",'".$this->__jQueryUIDatePickerFormatToDateFormat($date_format)."') )";
    	}
    	return $query;	
    }
    function _buildQuerySelectRecordsApplyFilterRangeText($id, $idfield, $values){
    	$db = JFactory::getDBO();
    	$query = "select v.idrecord
    	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield
    	where f.idlisting=".$id." and v.idfield=".$idfield." and ";
    	if ($values!=null){
    		$query .= "( v.value >= ".$db->quote($values->f)." ";
    		$query .= " and v.value <= ".$db->quote($values->t)." )";
    	}
    	return $query;
    }
    function _buildQuerySelectRecordsApplyFilterRangeNumber($id, $idfield, $values){
    	$db = JFactory::getDBO();
    	$query = "select v.idrecord
    	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield
    	where f.idlisting=".$id." and v.idfield=".$idfield." and ";
    	if ($values!=null){
    		$query .= "( CAST(v.value AS SIGNED) >= ".$db->quote($values->f)." ";
    		$query .= " and CAST(v.value AS SIGNED) <= ".$db->quote($values->t)." )";
    	}
    	return $query;
    }
    function _buildQuerySelectRecordsApplyFilterMultioption($id, $idfield, $values=null, $isStrict=false){
    	$db = JFactory::getDBO();
    	$query = "select val.idrecord
        	from #__listmanager_field f left join #__listmanager_field_multivalue v on f.id=v.idfield 
        	left join #__listmanager_values val on val.value REGEXP CONCAT('(^|#) ?',v.id,' ?($|#)') and val.idfield=f.id
			where f.idlisting=".$id." and v.idfield=".$idfield." and ";
  		if ($values!=null){
  			//$values=$db->quote($values);
  			if (!$isStrict):
	  			$values = '%'.$this->getEscapedWrapper($values,true).'%';
	        	$query .= " v.value like ".$db->quote($values)." ";
	        else:
	        	$query .= " v.id = ".$db->quote($values)." ";
	        endif;
	        
        }
        return $query;
    } 
    function _buildQuerySelectRecordsApplyFilterRate($id, $idfield, $values=null){
    	$db = JFactory::getDBO();
        $query = "select r.idrecord from #__listmanager_rate r 		
				where r.idlisting=".$id." and r.idfield=".$idfield." 
					and ( ";
       	if ($values!=null){
  			$values_p0=$db->quote($values);
  			$values_p1=$db->quote($values+1);
        	$query .= " (select sum(r1.rate)/count(*) from #__listmanager_rate r1 where r1.idlisting=r.idlisting and r1.idrecord=r.idrecord and r.idfield=r1.idfield) between ".$values_p0." and ".$values_p1." ";
        	if ($values=='0')
        	$query .=" or (select sum(r1.rate)/count(*) from #__listmanager_rate r1 where r1.idlisting=r.idlisting and r1.idrecord=r.idrecord and r.idfield=r1.idfield) is null"; 
        }        
		$query .= " ) ";
        return $query;
    }
    
   
   function _buildQuerySelectRecordsApplyFilterMultivalue($id, $idfield, $values){
   		$db = JFactory::getDBO();
        $query = "select v.idrecord 
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield        		 
			where f.idlisting=".$id." and v.idfield=".$idfield." and ( ";
        for($i=0;$i<count($values);$i++){
        	if ($i>0) $query .= " or ";
        	$values_pre = '%#'.$this->getEscapedWrapper($values[$i],true).'%';
        	$values_all = '%#'.$this->getEscapedWrapper($values[$i],true).'#%';
        	$values_post = '%'.$this->getEscapedWrapper($values[$i],true).'#%';
        	$query .= " (v.value like ".$db->quote($values_pre)." or v.value like ".$db->quote($values_all)." 
         					or v.value like ".$db->quote($values_post)." or v.value = ".$db->quote($values[$i]).")";
		}         
		$query .= " ) ";
        return $query;
    }
    function _buildQuerySelectRecordsApplyFilterMultivalueRate($id, $idfield, $values){
    	if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
    	if (!is_numeric($idfield)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
    	$db = JFactory::getDBO();
        $query = "select rate.idrecord 
        	from #__listmanager_field f left join #__listmanager_rate rate on f.id=rate.idfield        		 
			where f.idlisting=".$id." and rate.idfield=".$idfield." and ( ";
        	for($i=0;$i<count($values);$i++){
	        	$value=$db->quote($values[$i]);
	        	$value_p1=$db->quote(($values[$i]+1));
	        	if ($i>0) $query .= " or ";
	        	$query .= "  ((select sum(r.rate)/count(*) 
	         								from #__listmanager_rate r 
	         								where r.idlisting=".$id." and r.idrecord=rate.idrecord 
	         								and r.idfield=f.id) 
	         					between ".$value." and ".$value_p1." )";
	         	if ($values[$i]=='0'){
	        		$query .=" or ((select sum(r.rate)/count(*) 
	        						from #__listmanager_rate r where r.idlisting=".$id." 
	        						and r.idrecord=rate.idrecord and r.idfield=f.id) is null)";
	         	}
	         	$query .= "  ";         	
			}         
		$query .= " ) ";
        return $query;
    }  
    
    function _buildQuerySelectRecordsApplyFilterGlobal($id, $values, $search_type=0){
    	if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
    	$db = JFactory::getDBO();
    	$query = "select distinct(v.idrecord) 
        	from #__listmanager_field f left join #__listmanager_values v on f.id=v.idfield
			where f.idlisting=".$id." and f.searchable=1 and ";
  		if ($values!=null){
  			switch($search_type):
  				case 0:
  					$valueslike = '%'.$this->getEscapedWrapper($values,true).'%';
  					$query .= " ((f.searchtype=1 and v.value=".$db->quote($values).") or (f.searchtype=0 and v.value like ".$db->quote($valueslike)."))";
  					break;
  				case 1:
  					$values_arr = preg_split('/\s+/', $values);
  					$query_arr=array();
  					foreach ($values_arr as $val_item):
  						$valueslike = '%'.$this->getEscapedWrapper($val_item,true).'%';
  						$query_arr[]= " ((f.searchtype=1 and v.value=".$db->quote($values).") or (f.searchtype=0 and v.value like ".$db->quote($valueslike)."))";
  					endforeach;
  					$query .='('.implode(' or ',$query_arr).')';
  					break;
  			endswitch;  		
  			// zenzozo 20/08/2014
  			/*$values = $this->getEscapedWrapper($values,true);
  			$values = preg_replace("/\s+/u", '', $values);
  			$values = preg_replace("/\/+/u", '', $values);
  			$values = preg_replace("/\-+/u", '', $values);
  			$query .= " CONVERT(REPLACE(REPLACE(replace(v.value,'/',''),'-',''),' ','') USING utf8) = _utf8 ".$db->quote($values)." COLLATE utf8_general_ci";
  			*/
        }        
		$query .= "  ";
		//var_dump($query);		
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
    
    function _buildQuerySelectFieldById($id){
    	if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "select * from #__listmanager_field where id=".$id;
        return $query;
    } 
	function _buildQuerySelectValuesFilter($idfield,$recordsid=null){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select distinct(v.value) as fval from #__listmanager_values v left join #__listmanager_field_multivalue mv on (v.value=mv.id) where v.idfield=".$idfield;
    	if ($recordsid!=null){        
        	$query.=" and v.idrecord in (".implode(',', $recordsid).")";
    	}
    	$query.=" order by mv.ord, fval asc";
    	return $query;
    }
	function _buildQuerySelectValuesFilterMultiOption($idfield,$recordsid=null){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	/*$query = "select value from #__listmanager_values where idfield=".$idfield;
    	if ($recordsid!=null){        
        	$query.=" and idrecord in (".implode(',', $recordsid).")";
    	}*/
		$query = "select v.value as fval,v.id as id from #__listmanager_values v left join #__listmanager_field_multivalue mv on (v.value like concat(mv.id,'#%') or v.value like concat('%#',mv.id,'#%') or v.value like concat('%#',mv.id) or v.value =mv.id ) where v.idfield=".$idfield;
		if ($recordsid!=null){
			$query.=" and v.idrecord in (".implode(',', $recordsid).")";
		}
		$query.=" order by mv.ord, fval asc";
        return $query;
    }
    function _buildQuerySelectValuesFilterMultiOptionOrder($mvfields){
    	$query = "select id from #__listmanager_field_multivalue mv where id in ('".implode('\',\'', $mvfields)."')";
    	$query.=" order by ord, value asc";
    	return $query;
    }
    /**
     * @deprecated
     */
	function _buildQuerySelectValuesFilterGroup($idfield,$recordsid=null){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select distinct(v.value) as fval,(select fm.name from #__listmanager_field_multivalue fm where fm.id=v.value) as name
    				from #__listmanager_values v where v.idfield=".$idfield;    	
    	if ($recordsid!=null){        
        	$query.=" and idrecord in (".implode(',', $recordsid).")";
    	}
    	$query.=" order by fval asc";
        return $query;
    }
    
	function _buildQuerySelectValuesFilterMultiOptionName($movalue){
		if (!is_numeric($movalue)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select fm.name from #__listmanager_field_multivalue fm where fm.id=".$movalue;    	
    	return $query;
    }
    
	function _buildQuerySelectTotal_0($id,$idfield,$recordsid=null,$params){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select sum(replace(replace(value,'".$params['thousand']."',''),'".$params['decimal']."','.')) from #__listmanager_values where idfield=".$idfield;
    	if ($recordsid!=null){        
        	$query.=" and idrecord in (".implode(',', $recordsid).")";
    	} 
    	return $query;
    }
	function _buildQuerySelectTotal_1($id,$idfield,$recordsid=null,$params){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select ceil(sum(replace(replace(value,'".$params['thousand']."',''),'".$params['decimal']."','.'))) from #__listmanager_values where idfield=".$idfield;
    	if ($recordsid!=null){        
        	$query.=" and idrecord in (".implode(',', $recordsid).")";
    	}
        return $query;
    }
	function _buildQuerySelectTotal_2($id,$idfield,$recordsid=null,$params){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select floor(sum(replace(replace(value,'".$params['thousand']."',''),'".$params['decimal']."','.'))) from #__listmanager_values where idfield=".$idfield;
    	if ($recordsid!=null){        
        	$query.=" and idrecord in (".implode(',', $recordsid).")";
    	}    	
        return $query;
    }
	function _buildQuerySelectTotal_3($id,$idfield,$recordsid=null,$params){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select round(sum(replace(replace(value,'".$params['thousand']."',''),'".$params['decimal']."','.'))) from #__listmanager_values where idfield=".$idfield;
    	if ($recordsid!=null){        
        	$query.=" and idrecord in (".implode(',', $recordsid).")";
    	}
        return $query;
    }
	function _buildQuerySelectTotal_4($id,$idfield,$recordsid=null,$params){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select max(replace(replace(value,'".$params['thousand']."',''),'".$params['decimal']."','.')) from #__listmanager_values where idfield=".$idfield;
    	if ($recordsid!=null){        
        	$query.=" and idrecord in (".implode(',', $recordsid).")";
    	}
    	return $query;
    }
	function _buildQuerySelectTotal_5($id,$idfield,$recordsid=null,$params){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select min(replace(replace(value,'".$params['thousand']."',''),'".$params['decimal']."','.')) from #__listmanager_values where idfield=".$idfield;
    	if ($recordsid!=null){        
        	$query.=" and idrecord in (".implode(',', $recordsid).")";
    	}
    	$query.=" and value is not null and trim(value)<>''";
        return $query;        
    }
	function _buildQuerySelectTotal_6($id,$idfield,$recordsid=null,$params){
		if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select avg(replace(replace(value,'".$params['thousand']."',''),'".$params['decimal']."','.')) from #__listmanager_values where idfield=".$idfield;
    	if ($recordsid!=null){        
        	$query.=" and idrecord in (".implode(',', $recordsid).")";
    	}
    	$query.=" and value is not null and trim(value)<>''";
        return $query;
    }
    
	function _buildQuerySelectMultivalueById($id){
		//if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
		$db = JFactory::getDBO();
    	$query = "select * from #__listmanager_field_multivalue where id=".$db->quote($id);
        return $query;
    }
    
	function _buildQuerySelectSearchMultivalue($id, $value, $search_type=0){
		$db = JFactory::getDBO();
		if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
		$value='%'.$this->getEscapedWrapper($value,true).'%';
    	$query = "select v.id from #__listmanager_field f, 
    		#__listmanager_field_multivalue v where f.idlisting=".$id." and  f.id=v.idfield and ";
    		switch ($search_type): // Search de la lista
				case 0:
					$query .="v.name like ".$db->quote($value);  
					break;
				case 1:
					$values_arr = preg_split('/\s+/', $value);
  					$query_arr=array();
  					foreach ($values_arr as $val_item):
  						$valueslike = '%'.$this->getEscapedWrapper($val_item,true).'%';
  						$query_arr[]= " ((f.searchtype=1 and v.name = ".$db->quote($val_item).") or (f.searchtype=0 and v.name like ".$db->quote($valueslike)."))";
  					endforeach;
  					$query .='('.implode(' or ',$query_arr).')';
					break;
    		endswitch;
    	  	
    	return $query;
    }
    
	function _buildQuerySelectSearchMultivalueAllValues($id){
		if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
		$query = "select v.idrecord,v.value from #__listmanager_field f, 
    		#__listmanager_values v where f.idlisting=".$id." and  f.id=v.idfield 
        	and f.type in (2,10,11,16) and v.value is not null and v.value !=''"; 
    	return $query;
    }
     function _buildQuerySelectCountValuesFilterMultiOption($dt,$idfield,$recordsid,$type=null){
     	if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
     	$db = JFactory::getDBO();
     	$query = "select count(*) from #__listmanager_values where idfield=".$idfield." ";
     	if($type!=null && $type=="0"): //Multivalues
     		$query .= "and (value like '".$dt."' || value like '".$dt."#%' || value like '%#".$dt."#%' || value like '%#".$dt."')";
     	else:
     		$query .= "and value =".$db->quote($dt);
     	endif; 
     	if ($recordsid!=null) $query.=" and idrecord in (".implode(',', $recordsid).")";
    	return $query;
     	
     }
     
     function _buildQuerySelectRecordsOrderSort($sort){
     	$query = "select v.idrecord ";  	
  	  	if ($sort['type']=='15'){
	      	$query .= ",(select IFNULL(sum(r.rate)/count(*),0) from #__listmanager_rate r 
							where r.idlisting=".$sort['idlisting']." and r.idrecord=v.idrecord 
         					and r.idfield=".$sort['id'].") as value".$sort['id'];
	    } elseif ($sort['type']=='1' || $sort['type']=='12'){
	      	//$query .= ",(select STR_TO_DATE(v".$i.".value,'".$this->__jQueryUIDatePickerFormatToDateFormat($sort['date_format'])."') from #__listmanager_values v".$i." where v".$i.".idfield=".$sort['id']." and v".$i.".idrecord=v.idrecord) as values".$i." ";
	      	$query .= ",STR_TO_DATE(ifnull(v.value,''),'".$this->__jQueryUIDatePickerFormatToDateFormat($sort['date_format'])."') as value".$sort['id'];
	    } elseif (($sort['type']=='2' || $sort['type']=='10' || $sort['type']=='11')&&$sort['sql']=''){
	      	$query .= ",(select mi".$i.".name from #__listmanager_field_multivalue mi".$i." where mi".$i.".id=v.value) as value".$sort['id'];
	    } else {
	      	//$query .= ",(select v".$i.".value from #__listmanager_values v".$i." where v".$i.".idfield=".$sort['id']." and v".$i.".idrecord=v.idrecord) as values".$i." ";
	      	$query .= ",v.value as value".$sort['id'];
	    }   
	  	$query.=" from #__listmanager_values v  where v.idfield=".$sort['id'];
	  	//var_dump($query);
	  	return $query;
	  	
     }
     function _buildQueryUpdateRecord($idfield,$idrecord,$value){
     	if (!is_numeric($idfield)||!is_numeric($idrecord)) JError::raiseError(507,JText::_('INTERNAL SERVER ERROR'));
     	$db = JFactory::getDBO();
     	$value=$db->quote($value);
     	$query = "update #__listmanager_values set value=".$value." where idfield=".$idfield." and idrecord=".$idrecord;
     	return $query;
     }
     function _buildQueryDeleteRecords($id,$idrecord,$fields=null){
     	if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
     	if (!is_numeric($idrecord)) JError::raiseError(502,JText::_('INTERNAL SERVER ERROR'));
     	$query = "delete from #__listmanager_values where idrecord=".$idrecord." and idfield in (select f.id from #__listmanager_field f where f.idlisting=".$id.")";
     	if ($fields!=null && count($fields)>0){
     		$query.=" and idfield not in (".implode(',', $fields).")";
     	}
     	return $query;
     }
     
     function _buildQuerySelectRecordsApplySpecialFilter($id, $idfield, $val,$sftype,$otherparams){
      $query=null;
      switch($sftype):
        case '1': // Date monthly
          $query = "select v.idrecord from #__listmanager_field f, #__listmanager_values v where f.idlisting=".$id." and  f.id=v.idfield 
        	and f.id =".$idfield." and month(STR_TO_DATE(ifnull(v.value,''),'".$this->__jQueryUIDatePickerFormatToDateFormat($otherparams['dateformat'])."'))=".$val;
        break;
      endswitch;
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
    
  function getFieldById($id) {
  	  $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectFieldById($id);
      $db->setQuery($query);
      return $db->loadObject();
    }
  
  function getList(){  	
  	$db = JFactory::getDBO();
    $id =JRequest::getVar('id');
    $id=$this->checkId($id); 
    $query = $this->_buildQuerySelect($id);
    $db->setQuery($query);
    return $db->loadObject();
  }
  
  function getDetailPDF(){  	
  	$db = JFactory::getDBO();
    $id =JRequest::getVar('id');
    $detailpdf=null;
    if ($this->isView($id)):
    	$idview=substr($id, 2);
      	$query = $this->_buildQuerySelectViewAll($idview);
      	$db->setQuery($query);
    	$lv=$db->loadObject();
    	$detailpdf=$lv->detailpdf;    	    	
    endif;
    if($detailpdf==null || trim($detailpdf)==''):
	    $id=$this->checkId($id);
	    $query = $this->_buildQuerySelect($id);
	    $db->setQuery($query);
    	$lv=$db->loadObject();
    	$detailpdf=$lv->detailpdf;
	endif;    
    return $detailpdf;
  }
  
	function getListOne($id){  	
  	$db = JFactory::getDBO();
    $query = $this->_buildQuerySelect($id);
    $db->setQuery($query);
    return $db->loadObject();
  }
	
  function getMultivalueById($id){  	
  	$db = JFactory::getDBO();
    $query = $this->_buildQuerySelectMultivalueById($id);
    $db->setQuery($query);
    return $db->loadObject();
  }
  
  function getMultivalueSQLById($sqltext,$id){
  	if ($sqltext!=null && count($sqltext)>0){  	
	  	$db = JFactory::getDBO();
	  	$userdata=JFactory::getUser();
	  	// agenius
        $sqltext=str_replace('##userid##', $userdata->id, $sqltext);
        //$sqltext=str_replace('##userid##', 994, $sqltext);
	    $db->setQuery($sqltext);	    
	    $res=$db->loadRowList();
	    $fin=null;
	    $finres = new stdClass();
	    for($i=0;$i<count($res);$i++){
	    	$tmpres=$res[$i];
	    	if ($tmpres[0]==$id){	    		
	    		$fin=$tmpres[1];
	    		break;
	    	}
	    }
	    $finres->name=$fin;
	    return $finres; 
  	}
  	return null;
  }
  
  function getFieldsView($idview){
  	$db = JFactory::getDBO();
  	$query = $this->_buildQueryView($idview);
  	$db->setQuery($query);
    return $db->loadObjectList('idfield');
  }
   

  function getDataFields() {  	
  	return $this->getDataFieldsWrapper(false);
  }
  
  function getDataFieldsExport() {       
    return $this->getDataFieldsWrapper(true);
  }
  
  function getDataFieldsWrapper($export=false) {       
    $db = JFactory::getDBO();
    $id =JRequest::getVar('id');
    if ($this->isView($id)){
    	$idview=substr($id, 2);
	    $id=$this->checkId($id);	    
	    $query = $this->_buildQuerySelectFieldsOne2View($id,$idview,$export);
    } else {
    	$query = $this->_buildQuerySelectFieldsOne2($id,$export);    	
    }    
    $db->setQuery($query);
    // Todo use Jtext to field name
    return $db->loadAssocList();
  }
  
  function setDataPrefs($name,$value){
  	$var=$value;
  	if (is_array($value)) $var=json_encode($value);
  	$this->_prefs[$name]=$var;
  }
  
  function setInSession($list,$count){
  	$session = JFactory::getSession();
	$session->set('lm_'.$list, $count);
  }
  function getFromSession($list){
  	$session = JFactory::getSession();
	return $session->get('lm_'.$list);
  }
  
  function getDataPrefs(){  	  	
  	return $this->_prefs;
  }
  
  function getDataFilter($id,$recordsid){
  	$retorno=array();
  	$db = JFactory::getDBO();  	
  	$arrfields=array();  	
  	$allFields=$this->getDataFields();
  	foreach ($allFields as $field){
  		if ($field['autofilter']!=-1)
  			$arrfields[]=$field;
  	}
  	
  	foreach ($arrfields as $field){
  		$distinctFields=array();  		
  		// Select on each field for distinct values
  		if (($field['type']=='11' || $field['type']=='16' || $field['type']=='10' || $field['type']=='2')){
  			$query=$this->_buildQuerySelectValuesFilterMultiOption($field['id'],$recordsid);
	  		$db->setQuery($query);
	  		$arrMO=$db->loadColumn();
	  		$distinctFieldsTmp=array();
	  		foreach ($arrMO as $MO){
	  			$MOExploded=explode('#', $MO);	  			
	  			foreach ($MOExploded as $innerVal){	  				
	  				if (!in_array($innerVal, $distinctFieldsTmp)){
	  					$distinctFieldsTmp[]=$innerVal;
	  				}
	  			}
	  		}	  	
	  		if (!($field['sqltext']!=null||$field['sqltext']!='')):
		  		$query=$this->_buildQuerySelectValuesFilterMultiOptionOrder($distinctFieldsTmp);
		  		$db->setQuery($query);
		  		$distinctFieldsTmp=$db->loadColumn();	
		  	endif;
	  		//sort($distinctFieldsTmp);	  		
	  		$distinctArr=array();
	  		foreach ($distinctFieldsTmp as $dt){	  			
	  			if (count($dt)>0&&$dt!=''){
		  			$distinctArr['fval']=$dt;
		  			if (!($field['sqltext']!=null||$field['sqltext']!='')):		  			
			  			$query=$this->_buildQuerySelectValuesFilterMultiOptionName($dt);
			  			$db->setQuery($query);
			  			$tmpName=JText::_($db->loadResult());
			  		else:			  			
			  			$db->setQuery($field['sqltext']);	  				  			
			  			$options=$db->loadRowList();
			  			foreach ($options as $option):
			  				if ($dt==$option[0]):
			  					$tmpName=JText::_($option[1]);
			  					break;
			  				endif;
			  			endforeach;
			  		endif; 
		  			if($field['autofilter']=='2'):	
		  				$query=$this->_buildQuerySelectCountValuesFilterMultiOption($dt,$field['id'],$recordsid,'0');
		  				$db->setQuery($query);		  				
			  			$number=$db->loadResult();
			  			if ($number<=0) continue;
			  			$dtTmpEv=$dt;
			  			$dtTmp=$this->getMultivalueById($dt);			  			
			  			if ($dtTmp!=null):			  				
			  				$dtTmpEv=$dtTmp->name;
			  			endif;			  			
			  			$distinctArr['name']=JText::_($dtTmpEv).' <span class="badge">'.$number.'</span>';
		  			else:
		  				//$distinctArr['name']=$db->loadResult();
		  				$distinctArr['name']=$tmpName;
		  			endif;
		  			$distinctFields[]=$distinctArr;
	  			}
	  		}	  		
  		} else {  		
	  		$query=$this->_buildQuerySelectValuesFilter($field['id'],$recordsid);
	  		$db->setQuery($query);
	  		$distinctFieldsTmp=$db->loadColumn();	  			  			  
	  		if ($field['type']=='6'){
	  			$distinctFieldsUsers=array();
	  			foreach ($distinctFieldsTmp as $userid){	  				
	  					$user_tmp=JFactory::getUser($userid);
	  					$distinctFieldsUsers[]=$user_tmp->name;
	  			}
	  			$distinctFieldsTmp=$distinctFieldsUsers;
	  		}
	  		$distinctArr=array();
	  		//$querydata=null;
	  		foreach ($distinctFieldsTmp as $dt){
	  			$distinctArr['fval']=$dt;	  
	  			$badge='';			
		  		if($field['autofilter']=='2'):
			  		$query=$this->_buildQuerySelectCountValuesFilterMultiOption($dt,$field['id'],$recordsid);
			  		$db->setQuery($query);
			  		$number=$db->loadResult();
			  		$badge=' <span class="badge">'.$number.'</span>';
		  		endif;
		  		/*if($querydata!=null&&in_array($dt,$querydata)):
	  				$distinctArr['name']=$querydata[$dt].$badge;	
	  			else:*/		  		
	  				$distinctArr['name']=JText::_($dt).$badge;
	  			//endif;	  			
	  			$distinctFields[]=$distinctArr;
	  		}
  		}
  		if(file_exists (dirname(__FILE__).DS.'datafilterorder.php')){
  			include(dirname(__FILE__).DS.'datafilterorder.php');
  		}  		
  		$retorno[$field['id']]=$distinctFields;
  	}
  	return $retorno;
  }
  
  
  
  function getDataTotals($id,$recordsid,$params,$applyFilters){
  	$retorno=array();
  	$db = JFactory::getDBO();  	
  	$arrfields=array();  	
  	$allFields=$this->getDataFields();
  	foreach ($allFields as $field){
  		if ($field['total']!=-1)
  			$arrfields[]=$field;
  	}
  	$totals=0; 
  	$totals_count=count($arrfields);  	
  	foreach ($arrfields as $field){
  		if ($applyFilters&&count($recordsid)<=0):
  			$retorno[$field['id']]=0;
  		else:
	  		$distinctFields=array();  				
	  		$query='';
	  		// Select on each field for distinct values
	  		switch ($field['total']){
	  			case '0': // Sum
	  				$query=$this->_buildQuerySelectTotal_0($id,$field['id'],$recordsid,$params);
	  				break;
	  			case '1': // Ceil
	  				$query=$this->_buildQuerySelectTotal_1($id,$field['id'],$recordsid,$params);
	  				break;
	  			case '2': // Floor
	  				$query=$this->_buildQuerySelectTotal_2($id,$field['id'],$recordsid,$params);
	  				break;
	  			case '3': // Round
	  				$query=$this->_buildQuerySelectTotal_3($id,$field['id'],$recordsid,$params);
	  				break;
	  			case '4': // Max
	  				$query=$this->_buildQuerySelectTotal_4($id,$field['id'],$recordsid,$params);
	  				break;
	  			case '5': // Min
	  				$query=$this->_buildQuerySelectTotal_5($id,$field['id'],$recordsid,$params);
	  				break;
	  			case '6': // Mean
	  				$query=$this->_buildQuerySelectTotal_6($id,$field['id'],$recordsid,$params);
	  				break;
	  		}
	  		$db->setQuery($query);  		
	  		$retorno[$field['id']]=number_format($db->loadResult(),2,$params['decimal'],$params['thousand']);
	  	endif;
  	}
  	return $retorno;
  }
  
  function getLog(){
  	$use_log=true;
  	if ($use_log) return $this->_log;
  	else return null;
  }
  function addLog($data){
  	$this->_log[]=$data;
  }
  
  function getFieldByIdListView($id,$idfield){
  	$isView=$this->isView($id);
  	if ($isView){
  		$db = JFactory::getDBO();
  		$idview=substr($id, 2);
  		$id=$this->checkId($id);
  		$query=$this->_buildQuerySelectFieldsOne2View($id,$idview);
  		$db->setQuery($query);
  		$listFields=$db->loadObjectList('id');
  		return $listFields[$idfield];
  	} else {
  		return $this->getFieldById($idfield);
  	}
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
  
  function getDataRecords() {
  	$from =JRequest::getVar('from',null);
    $rp =JRequest::getVar('rp',null);
  	return $this->getDataRecordsPreWrapper($from,$rp,true,false);
  }
  
  function getAllDataRecords() {
  	$arr_res=$this->getDataRecordsPreWrapper(null,null,false,true);
  	return $arr_res['v'];  	
  }
  
  function getAllDataRecordsFiltered() {
  	$arr_res=$this->getDataRecordsPreWrapper(null,null,true,true);
  	return $arr_res['v'];
  }
  
  function getDataRecordsPreWrapper($from=null,$rp=null,$isFilter=false,$export=false) {   
      $db = JFactory::getDBO();
      $id=$id_orginal=JRequest::getVar('id');      
      $filter =JRequest::getVar('filter',null, 'post', 'string', JREQUEST_ALLOWRAW);
      $sort =JRequest::getVar('sort',null);      
      $recalc =JRequest::getVar('recalc',0);
      $isView=$this->isView($id);
            
      $fieldsView=array();      
      if ($isView){
      	$idview=substr($id, 2);
      	$fieldsView=$this->getFieldsView($idview);    
      	$list_view_data=$this->getListView($idview,$isView);  	
      } else {
      	$list_view_data=$this->getListView($id,$isView);	
      }
      $id=$this->checkId($id);
      
      // List Config
      $lstConfig=$this->_getListing($id);

      // Filters 
      $allfilters=array();
      $viewfilters=array();
	  $filters=array();
	  $applyFilters=false;
      // View Filter 
      if($isView){
	    if(!defined('DS')){
	        define('DS',DIRECTORY_SEPARATOR);
	    } 
	    if(file_exists (dirname(__FILE__).DS.'customserverpages_fl.php')){	       
	    	include(dirname(__FILE__).DS.'customserverpages_fl.php');	     
	    }
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
  				$viewfilters=$arrIntFilter;
      			$filters[]=$arrIntFilter;
      			$allfilters=array_merge($allfilters,$arrIntFilter);
      			unset($arrIntFilter);
      		}      						
      	}      	
      }
     //var_dump(count(json_decode($filter)));
      // Query filters
	  //if($filter!=null && count(json_decode(stripslashes($filter)))>0 && $isFilter){
	  $_filterValues=array();
      if($filter!=null && count(json_decode($filter))>0 && $isFilter){      	
	  	$applyFilters=true;
	  	//$filter_dec=json_decode(stripslashes($filter));
	  	$filter_dec=json_decode($filter);
	  	//var_dump($filter_dec);
	  	//foreach ($filter_dec as $key=>$value){	
	  	
	  	for($i=0;$i<count($filter_dec);$i++){
	  		$tmp_filter=$filter_dec[$i];
	  		$query="";
	  		$tmpField=0;
	  		$searchFilter=null;
	  		
	  		if ($tmp_filter[0]!='search' && !$this->startsWith($tmp_filter[0],'spfilter_')){
        //if ($tmp_filter[0]!='search'){
	  			$tmpField=$this->getFieldByIdListView($id_orginal,$tmp_filter[0]);
	  		}
	  		if (is_array($tmp_filter[1]) && $tmpField->autofilter!='3'){
	  			$_filterValues[$tmpField->name]=implode(',', $tmp_filter[1]);
	  			// Multiple Value one field	
	  			if ($tmpField->type!='15') 
	  				if ($tmpField->type!='6') 			 
	  					$query=$this->_buildQuerySelectRecordsApplyFilterMultivalue($id, $tmp_filter[0], $tmp_filter[1]);
	  				else // User
	  					$query=$this->_buildQuerySelectRecordsApplyFilterGlobalUser($id, $tmp_filter[1], $tmp_filter[0]);
	  			else 
	  				$query=$this->_buildQuerySelectRecordsApplyFilterMultivalueRate($id, $tmp_filter[0], $tmp_filter[1]);	  			
	  		} elseif ($tmp_filter[0]=='search'){
	  			$_filterValues['global search']=$tmp_filter[1];
	  			// Global search	  			
	  			$querySearch=$this->_buildQuerySelectRecordsApplyFilterGlobal($id, $tmp_filter[1],$lstConfig['search_type']);
	  			$db->setQuery($querySearch);
	  			$restemp=$db->loadColumn();
	  			foreach ($restemp as $rst){$searchFilter[]=$rst;}	  			
	  			// Buscar todos los mv que son válidos
	  			$querySearch=$this->_buildQuerySelectSearchMultivalue($id, $tmp_filter[1],$lstConfig['search_type']);
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
		  						$searchFilter[]=$item->idrecord;
		  						break;
		  					}	  						
		  				}
		  			}
		  		endif;
	  			// TODO Buscar los option que se forman por SQL
	  			// Buscar todos los de usuario
	  			$querySearch=$this->_buildQuerySelectRecordsApplyFilterGlobalUser($id, $tmp_filter[1]);
	  			$db->setQuery($querySearch);
	  			$restemp=$db->loadColumn();
	  			foreach ($restemp as $rst){$searchFilter[]=$rst;}	  			
	  		} elseif ($this->startsWith($tmp_filter[0],'spfilter_')){
          $spfilterid=substr($tmp_filter[0],9);
          $spfilter=$this->searchSpfilter($spfilterid,$lstConfig['specialfilters']);
          $spdate_format=$lstConfig['date_format_bbdd'];
          $otherparams=array('dateformat'=>$spdate_format);
          $query=$this->_buildQuerySelectRecordsApplySpecialFilter($id, $spfilter->idfield, $tmp_filter[1],$spfilter->sftype,$otherparams);
          	            
        } else {
        	$_filterValues[$tmpField->name]=$tmp_filter[1];
	  			// One Field, one value
	  			if ($tmpField->type=='15'){
	  				// Rating
	  				$query=$this->_buildQuerySelectRecordsApplyFilterRate($id, $tmp_filter[0], $tmp_filter[1]);	  				
	  			} elseif ($tmpField->type=='6'){ // User
	  				 $query=$this->_buildQuerySelectRecordsApplyFilterGlobalUser($id, $tmp_filter[1], $tmp_filter[0]);
	  			} else {
		  			if ($tmpField->autofilter=='0' || $tmpField->autofilter=='2') {
		  				// Select & Multiple 
		  				if (($tmpField->type=='2'||$tmpField->type=='11'||$tmpField->type=='16'||$tmpField->type=='10')&&
		  					($tmpField->sqltext==null||$tmpField->sqltext=='')){
		  					// Option list -SQL
		  					$query=$this->_buildQuerySelectRecordsApplyFilterMultioption($id, $tmp_filter[0], $tmp_filter[1],true);		  					
		  				} else{
		  					$query=$this->_buildQuerySelectRecordsApplyFilterStrict($id, $tmp_filter[0], $tmp_filter[1]);
		  				}
		  			} elseif($tmpField->autofilter=='3'){
		  				switch($tmpField->type){
		  					case '1' : // Date
		  						$date_format=$list_view_data->date_format_bbdd;
		  						$query=$this->_buildQuerySelectRecordsApplyFilterRangeDate($id, json_decode($tmp_filter[0]), $tmp_filter[1], $date_format);
		  						break;	
		  					case '0' : //Number
		  					case '14' : //Number Slider
		  					case '19' : //Autoincrement
		  						$query=$this->_buildQuerySelectRecordsApplyFilterRangeNumber($id, json_decode($tmp_filter[0]), $tmp_filter[1]);
		  						break;	  	
		  					case '4' : // Text
		  						$query=$this->_buildQuerySelectRecordsApplyFilterRangeText($id, json_decode($tmp_filter[0]), $tmp_filter[1]);
		  						break;
		  				} 
		  			} else {
		  				// Text		  						  				
		  				if ($tmpField->type=='2'||$tmpField->type=='11'||$tmpField->type=='16'||$tmpField->type=='10'){
		  					// Option list -SQL
		  					if ($tmpField->sqltext==null||$tmpField->sqltext==''):
		  						$query=$this->_buildQuerySelectRecordsApplyFilterMultioption($id, $tmp_filter[0], $tmp_filter[1]);
		  					else:
		  						$db->setQuery($tmpField->sqltext);	  			
					  			$options=$db->loadRowList();
					  			$filter1=$tmp_filter[1];	  			
					  			foreach ($options as $option):
					  				if (strtoupper($option[1])==strtoupper($tmp_filter[1])):
					  					$filter1=$option[0];
					  					break;
					  				endif;
					  			endforeach;
		  						$query=$this->_buildQuerySelectRecordsApplyFilter($id, $tmp_filter[0],$filter1,$lstConfig['search_type']);
		  					endif;
		  				} else{
		  					$query=$this->_buildQuerySelectRecordsApplyFilter($id, $tmp_filter[0], $tmp_filter[1],$lstConfig['search_type']);
		  				}
		  			}
	  			}	 
	  			//$this->addLog($query);	  			
	  		}	  		
	  		if ($tmp_filter[0]!='search'){
		  		$db->setQuery($query);
		  		$arrIntFilter=$db->loadColumn();
	      		$filters[]=$arrIntFilter;
	      		$allfilters=array_merge($allfilters,$arrIntFilter);
	  		} else {
	  			if ($searchFilter!=null){
		  			$filters[]=$searchFilter;
		  			$allfilters=array_merge($allfilters,$searchFilter);
		  			unset($searchFilter);
	  			}
	  		}      		
      		unset($arrIntFilter);
	  	}
	  	$this->_addSearch($id,json_encode($_filterValues));
	  }
	  
	  //filtro permisos
      $access_type=JRequest::getVar('access_type');
      $user_on=JRequest::getVar('user_on');
      
      if ($access_type==1){
      	$applyFilters=true;
      	$query=$this->_buildQuerySelectRecordsApplyFilterUser($id, $user_on);
      	$db->setQuery($query);
	  	$arrIntFilter=$db->loadColumn();
      	$filters[]=$arrIntFilter;
      	$allfilters=array_merge($allfilters,$arrIntFilter);
      	unset($arrIntFilter);     	
      }      
      
	  if($applyFilters){
		for($i=0;$i<count($filters);$i++){
		  	$allfilters=array_intersect($allfilters, $filters[$i]);
		}	  
	  }
	  $dataTotalsParams=array();
	  $dataTotalsParams['thousand']=$list_view_data->thousand;
	  $dataTotalsParams['decimal']=$list_view_data->decimal;
	  // Count Records
	  switch($recalc){
	  	case 0:
	  		$dc=$this->getFromSession('dc_'.$id_orginal);
	  		if ($dc==null || !isset($dc)){
	  			$query = $this->_buildQuerySelectRecordsFilteredCount($id,$allfilters);      
	      		$db->setQuery($query);
	      		$cnt=$db->loadResult();
	      		$this->setDataPrefs('datacount', $cnt);
	      		$this->setInSession('dc_'.$id_orginal,$cnt);
	      		$dataTotals=$this->getDataTotals($id, $allfilters,$dataTotalsParams,$applyFilters);
	      		$this->setInSession('dt_'.$id_orginal,$dataTotals);
	      		$this->setDataPrefs('datatotal', $dataTotals);
	      		$dataFilter=$this->getDataFilter($id, $allfilters);
      			$this->setInSession('df_'.$id_orginal,$dataFilter);
      			$this->setDataPrefs('datafilter', $dataFilter);
	  		} else {
		  		$this->setDataPrefs('datacount', $this->getFromSession('dc_'.$id_orginal));
	      		$this->setDataPrefs('datafilter', $this->getFromSession('df_'.$id_orginal));
	      		$dataTotals=$this->getDataTotals($id, $allfilters,$dataTotalsParams,$applyFilters);
	      		$this->setInSession('dt_'.$id_orginal,$dataTotals);
	      		//$this->setDataPrefs('datatotal', $this->getFromSession('dt_'.$id_orginal));
	      		$this->setDataPrefs('datatotal', $dataTotals);
	  		}
	  		break;
	  	case 2:
	  		$dataFilter=$this->getDataFilter($id, $allfilters);
      		$this->setInSession('df_'.$id_orginal,$dataFilter);      		      		
	  	case 1:
	  		$query = $this->_buildQuerySelectRecordsFilteredCount($id,$allfilters);      
      		$db->setQuery($query);
      		$cnt=$db->loadResult();
      		$this->setDataPrefs('datacount', $cnt);
      		$this->setInSession('dc_'.$id_orginal,$cnt);
      		//$this->setDataPrefs('datafilter', $this->getFromSession('df_'.$id_orginal));
      		$dataFilter=$this->getDataFilter($id, $allfilters);
      		$this->setInSession('df_'.$id_orginal,$dataFilter);
      		$this->setDataPrefs('datafilter', $this->getFromSession('df_'.$id_orginal));
      		$dataTotals=$this->getDataTotals($id, $allfilters,$dataTotalsParams,$applyFilters);
      		$this->setInSession('dt_'.$id_orginal,$dataTotals);
      		$this->setDataPrefs('datatotal', $this->getFromSession('dt_'.$id_orginal));
      		break;
	  }  

	  if($applyFilters && count($allfilters)<=0){
	  	$this->setDataPrefs('datacount', 0);
	  }
      
      $arrIds=array(-1);
            
      if($applyFilters && count($allfilters)<=0){ 
      	return null;
      }
      
	  // Order	  
	  if ($sort==null){
	  	//if($list_view_data->default_order!=null && count($list_view_data->default_order)>2){	  	
	  	if($list_view_data->default_order!=null){
	  		$sort=$list_view_data->default_order;
	  	}
	  }
	  
	  //$this->addLog($sort);
      if ($sort!=null){
      	      	
      	$sort_dec=json_decode(stripslashes($sort));
      	//$this->addLog($sort_dec);
      	$arrSorts=array();
      	$list_data=$this->getListOne($id);
      	foreach ($sort_dec as $sort_elem){
      		$tmpField=$this->getFieldByIdListView($id_orginal,$sort_elem->headerId);
      		if($tmpField!=null){      		
	      		$arrElemTemp=array();
	      		$arrElemTemp['id']=$sort_elem->headerId;
	      		$arrElemTemp['order']=$sort_elem->order;
	      		$arrElemTemp['type']=$tmpField->type;
	      		$arrElemTemp['date_format']=$list_data->date_format_bbdd;
	      		$arrElemTemp['idlisting']=$id;      
	      		$arrElemTemp['sql']=$tmpField->sqltext;		
	      		$arrSorts[]=$arrElemTemp;
	      		unset($arrElemTemp);
      		}
      	}
      	$query = $this->_buildQuerySelectRecordsOrderWrapper($id,$arrSorts,$allfilters);      	
      	//$this->addLog($query);
      	$db->setQuery($query);
      	$arrIds=$db->loadColumn();
      	if($from!=null && $rp!=null) {$arrIds=array_slice($arrIds,$from,$rp);}  
      	
      	/*
      	 * Solución para que haga los cálculos de ordenar en php y no en bbdd
      	 */
      	/*
      	$sort_dec=json_decode(stripslashes($sort));
      	$arrLists=array();
      	foreach ($sort_dec as $sort_elem):
      		$list_data=$this->getListOne($id);
      		$tmpField=$this->getFieldByIdListView($id_orginal,$sort_elem->headerId);
      		$arrElemTemp=array();
      		if($tmpField!=null){ 
      			$sortIds[]=$sort_elem->headerId;
	      		$arrElemTemp['id']=$sort_elem->headerId;
	      		$arrElemTemp['order']=$sort_elem->order;
	      		$arrElemTemp['type']=$tmpField->type;
	      		$arrElemTemp['date_format']=$list_data->date_format;
	      		$arrElemTemp['idlisting']=$id;      
	      		$arrElemTemp['sql']=$tmpField->sqltext;
      		}
      		//get value de cada sort sin ordenar      		
      		$query = $this->_buildQuerySelectRecordsOrderSort($arrElemTemp);
      		$db->setQuery($query);
      		$resTmp=$db->loadAssocList();
      		$arrLists=$arrLists+$db->loadAssocList();
      		$arrTmp=array();      		      		
      		foreach ($resTmp as $key => $row) {
      			$arrTmp[$key] = $row['value'.$sort_elem->headerId];
			}			
			eval("\$arrTmp".$sort_elem->headerId."=\$arrTmp;");
			$arrMSParams[]="\$arrTmp".$sort_elem->headerId;
			if ($sort_elem->order=='asc') $arrMSParams[]=SORT_ASC;//SORT_ASC:4
			else $arrMSParams[]=SORT_DESC;//SORT_DESC:3     		
      	endforeach;
      	$arrMSParams[]='$arrLists';
		$mustring=implode(",",$arrMSParams);
      	eval("array_multisort($mustring);");
      	if($from!=null && $rp!=null) {$arrLists=array_slice($arrLists,$from,$rp);}
      	foreach ($arrLists as $list):
      		$arrIds[]=$list['idrecord'];
      	endforeach;
		*/
      	
      } else {
      	  if (count($allfilters)>0){
      	  	if($from!=null && $rp!=null) $arrIds=array_slice($allfilters,$from,$rp);
      	  	else $arrIds=$allfilters;
      	  } else {
		      // Query de ids record       
		      $query = $this->_buildQuerySelectRecordsFiltered($id,$allfilters,$from,$rp);      
		      $db->setQuery($query);
		      $arrIds=$db->loadColumn();
      	  }
      }      
      unset($allfilters);
      
      // Query record data
      if (empty($arrIds) || count($arrIds)<=0 || (count($arrIds)==1 && $arrIds[0]==null)) return null;   
      $arrIds = array_filter($arrIds, 'strlen');   
      $query = $this->_buildQueryRecords($id,$arrIds);
      $db->setQuery($query);
      $arrRecords=$db->loadAssocList();
      
      return $this->getDataRecordsWrapper($arrRecords,$export);
      
    }
    
    
    function getDataRecordsWrapper($arrRecords,$export) {
    	$base	= JURI::base(true).'/';
      $db = JFactory::getDBO();
      $id =JRequest::getVar('id');
      $isView=$this->isView($id);
      $fieldsView=array();
      if ($isView){
      	$idview=substr($id, 2);
      	$fieldsView=$this->getFieldsView($idview);
      }
      $id=$this->checkId($id); 
      $format = JRequest::getVar('format'); //task  
      
      //filtro permisos
      $arrdescartes=array(-1);
      $access_type=JRequest::getVar('access_type');
      $user_on=JRequest::getVar('user_on');
      $result=array();
      foreach ($arrRecords as $rec) {      	
      	  if($export && !$rec['exportable']) continue;
      	  $rec_value=$rec['value'];            
      	  $rec_valuem='';
	      if($rec['type']=='6'){	      
	      	$user_tmp=JFactory::getUser($rec['value']);	      	
	      	if ($user_tmp!=null):
	        	$result[$rec['idrecord']]['_struser']=$user_tmp->name;
	        	$result[$rec['idrecord']][$rec['idfield'].'_str']=$user_tmp->name;
	        else:
	        	$result[$rec['idrecord']]['_struser']='';
	        	$result[$rec['idrecord']][$rec['idfield'].'_str']='';
	        endif;
	      } elseif($rec['type']=='10'||$rec['type']=='11'||$rec['type']=='16'||$rec['type']=='2'){ //Multivalues
	      	if($rec_value!=null && count($rec_value)>0){
		      	$multikeys=explode('#', $rec_value);	      	
		      	$multival=array();
		      	foreach ($multikeys as $key){
		      		if ($rec['sqltext']!=null):
		      			$multiTmp=$this->getMultivalueSQLById($rec['sqltext'],$key);
		      		else:
		      			$multiTmp=$this->getMultivalueById($key);
		      		endif;
		      		if ($multiTmp!=null && isset($multiTmp->name)) $multival[]=JText::_($multiTmp->name); //i18n		      				      				      		
		      	}		      	
		      	$rec_valuem=implode('#', $multival);
	      	}
	      	$result[$rec['idrecord']][$rec['idfield'].'m']=$rec_valuem; 
	      }elseif ($rec['type']=='9'){
		      $protocols	= '[a-zA-Z0-9]+:'; //To check for all unknown protocals (a protocol must contain at least one alpahnumeric fillowed by :
			  $regex		= '#(src|href|poster)="(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
			  $rec_value		= preg_replace($regex, "$1=\"$base\$2\"", $rec_value);
	      } else{
	      	  $rec_value=JText::_($rec['value']);
	      }
	      
	      $result[$rec['idrecord']]['id']=$rec['idrecord'];	      
	      $result[$rec['idrecord']][$rec['idfield']]=$rec_value;	      
      }      
      // JSON order issue
      $res=array();
      $res['k']=array_keys($result);
      $res['v']=array_values($result);
      //return $result;
      unset($result);
      return $res;
  }  
  
  public function getRecord(){
  	$db = JFactory::getDBO();
  	$id=$id_orginal=JRequest::getVar('id');
  	$idrecord=$id_orginal=JRequest::getVar('idrecord');
  	$id=$this->checkId($id);
  	$arrIds=array();
  	$arrIds[]=$idrecord;
  	$query = $this->_buildQueryRecords($id,$arrIds);
  	$db->setQuery($query);
  	$arrRecords=$db->loadAssocList();  	  	  	
  	$res=$this->getDataRecordsWrapper($arrRecords,false);
  	return $res['v'];  	
  }
    
  public function getDataFieldsParam($id) {   
     	$id=$this->checkId($id);     
    $db = JFactory::getDBO();
    $query = $this->_buildQuerySelectFieldsOne2($id);
    $db->setQuery($query);
    return $db->loadAssocList();
  }
  
  public function getDataParam($id) {   
  	$id=$this->checkId($id);     
    $db = JFactory::getDBO();
    $query = $this->_buildQuery($id);
    $db->setQuery($query);
    return $db->loadAssoc();
  }
  
  public function saveBulk(){
  	$db = JFactory::getDBO();
  	$records=json_decode(JRequest::getVar('bulkids'));
  	$values=json_decode(JRequest::getVar('newval'));
  	$_idant = JRequest::getVar('id'); //idlisting
  	$_id=$this->checkId($_idant);
  	$query = $this->_buildQuerySelectFieldsOne2($_id);
  	$db->setQuery($query);
  	$arrfields= $db->loadAssocList();
  	foreach($arrfields as $field){
  		foreach ($values as $val):
  			if ($val->name!='fld_'.$field['id'] && $val->name!='fld_'.$field['id'].'[]') continue; 
	  		$tmp_value=$val->value;
	  		if($tmp_value!=''):
		  		if ($field['type']=='6'){
		  			$user = JFactory::getUser();
		  			$tmp_value=$user->id;
		  		} elseif ($field['type']=='11' || $field['type']=='16'){
		  			$arrMulti=array();
		  			foreach ($values as $multival):
		  				if($val->name==$multival->name) $arrMulti[]=$multival->value;
		  			endforeach;
		  			$tmp_value=implode("#", array_unique($arrMulti));
		  		} 	  		
	  			foreach ($records as $_idrecord):	  				
		  			$query = $this->_buildQueryUpdateRecord($field['id'],$_idrecord,$tmp_value);
		  			$db->setQuery($query);
		  			$result = $db->query();
		  		endforeach;		  		
	  		endif;
	  	endforeach;
  	}
  }
  public function deleteBulk(){
  	$db = JFactory::getDBO();
  	$records=json_decode(JRequest::getVar('bulkids'));
  	$_idant = JRequest::getVar('id'); //idlisting
  	$_id=$this->checkId($_idant);
  	foreach ($records as $_idrecord):
	  	$query = $this->_buildQueryDeleteRecords($_id,$_idrecord);
	  	$db->setQuery($query);
	  	$db->query();
	  	$query = $this->_buildDeleteRates($_idrecord,$_id);
	  	$db->setQuery($query);
	  	$db->query();
	  endforeach;
  }
  
	public function sendEmail(){  	   
       return $this->sendEmailWrapper(false);		 	
  }
	public function sendEmailFiltered(){  	   
       return $this->sendEmailWrapper(true);		 	
  }
  
  public function sendEmailWrapper($isFiltered){
  	   $email =JRequest::getVar('email');
	   try {
	   	  $config =& JFactory::getConfig();	   	  
		  $mailer =& JFactory::getMailer();
	      $mailer->setSubject($config->get( 'config.sitename' ));	      
	      $jversion = new JVersion ();
		  if (version_compare ( $jversion->getShortVersion (), 3, 'lt' )) :
		  	$sender = array(
		  	$config->getValue( 'config.mailfrom' ),
		  	$config->getValue( 'config.fromname' ) );
		  else:
		  	$sender = array(
		  	$config->get( 'mailfrom' ),
		  	$config->get( 'fromname' ) );
		  endif;	    
	      $mailer->setSender($sender);
	      $arr_emails=explode(";", $email);
	      foreach ($arr_emails as $emails){
	      	$mailer->addRecipient($emails);	
	      }			            
	      $mailer->isHTML(true);
	      $list=$this->getList();
	      $mail_content=$list->preprint;
	      if ($isFiltered)
	      	$mail_content.=$this->FancyTable($this->getDataFields(), $this->getAllDataRecordsFiltered(), JRequest::getVar('fullexport'));
	      else	      	
	      	$mail_content.=$this->FancyTable($this->getDataFields(), $this->getAllDataRecords(), JRequest::getVar('fullexport'));
	      $mail_content.=$list->postprint;
	      $mail_content='<html><body>'.$mail_content.'</body></html>';
	      $mailer->setBody($mail_content);
	      $send =& $mailer->Send();	   	  
	      return $send; 	      
	   } catch (Exception $e) {	   						
	      return $e->getMessage();
	   }
       return true;		 	
  }
  
  
	function FancyTable($data,$records,$fullexport){
		$result="";
		$numcols=0;
		$total=array();
		$hasTotal=false;
		$result.='<table border="1" cellpadding="0" cellspacing="0" width="100%"><tr>';
		  foreach ($data as $cabecera){
			  if($cabecera['type']!='17'&&($fullexport=='1'||$cabecera['visible']==1)){
			    $result.='<td bgcolor="#EEEEff"><b>'.$cabecera['name'].'</b></td>';
			  }
		  } 
		  $result.="</tr>";
		  foreach ($records as $record){
		   $result.="<tr border='1'>";
		   foreach ($data as $titcabecera){
		   	if (!isset($record[$titcabecera['id']])) continue;
		  	$texto=$record[$titcabecera['id']];
		  	if($titcabecera['total']=='1') {
		  		$hasTotal=true;
		  		if (isset($total[$titcabecera['id']]))
		  			$total[$titcabecera['id']]=$total[$titcabecera['id']]+$texto;
		  	}
		    if($titcabecera['type']!='17'&&($fullexport=='1'||$titcabecera['visible']==1)){
		     $result.="<td border='1'>&nbsp;";     
		      if($titcabecera['type']=='6'){
		          $texto=$record['_struser']; 
		    	} elseif($titcabecera['type']=='11' || $titcabecera['type']=='16' || $titcabecera['type']=='2' || $titcabecera['type']=='10'){
		          	$texto=str_replace('#','&nbsp;&nbsp;', $record[$titcabecera['id'].'m']);
		    	} 
		    $result.=$texto."</td>";
		    }  
		   }
		   $result.="</tr>";
		  }
		
		  $result.="</tr>";
		  if($hasTotal){  
		  	$result.="<tr><td colspan='".count($data)."'></td></tr>";
		   $result.="<tr><td style='font-weight:bold' colspan='".count($data)."'>".JText::_("LM_TOTALS")."</td></tr>";
		   $result.="<tr>";
		  foreach ($data as $cabecera){
			  if($cabecera['total']=='1') 
			  	$result.='<td bgcolor="#EEEEff" border="1"><b>'.$cabecera['name'].'</b></td>';
			  else	  
			  	$result.='<td>&nbsp;</td>';
		  }
		   $result.="</tr>";
		   $result.="<tr>";
		   foreach ($data as $cabecera){
		  	$result.="<td style='font-weight:bold' border='1'>";     
		    if($cabecera['total']=='1'&&isset($total[$titcabecera['id']])) $result.=$total[$cabecera['id']];
		    $result.="</td>";
		   }
		   $result.="</tr>";
		  }
		  $result.="</table>";
		return $result;
	}
	
	private function _buildQueryDetailList($id){
		$db = JFactory::getDBO();
		return  "select detail from #__listmanager_listing where id=".$db->quote($id);
	}
	private function _buildQueryDetailView($id){
   		$db = JFactory::getDBO();
   		return  "select detail from #__listmanager_view where id=".$db->quote($id);
   }
	public function getDetail($id){
		$db = JFactory::getDBO();
		$isList=strrpos($id,'v_')===false;
		$idlist=$this->checkId($id);
		$query = $this->_buildQueryDetailList($idlist);
		$db->setQuery($query);
		$result=$db->loadResult();
		if (!$isList):			
    		$query = $this->_buildQueryDetailView(substr($id, 2));
    		$db->setQuery($query);
			$resultview=$db->loadResult();
			if($resultview!=null && $resultview!=''){$result=$resultview;}
    	endif;
		return $result; 
    }
	  function startsWith($haystack, $needle) {
      // search backwards starting from haystack length characters from the end
      return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    function endsWith($haystack, $needle) {
      // search forward starting from end minus needle length characters
      return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }
  function searchSpfilter($idsf,$filters_json){
    $ret=null;
    $filters=json_decode($filters_json);
    foreach($filters as $filter):
      if($filter->sfid==$idsf):
        $ret=$filter;
        break;  
      endif;
    endforeach;
    return $ret;
  } 
}


?>
