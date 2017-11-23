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
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view' );
 
require_once('helper'.DS.'mpdf'.DS.'mpdf.php');
require(JPATH_COMPONENT.DS.'views'.DS.'main.view.php');

class ListmanagerViewServerpages extends ListmanagerViewMain
{
    function display($tpl = null){
    	$layout=JRequest::getVar('layout');
    	$list=$this->get('List');
    	//$this->assignRef('list',$list);    	    	
      switch ($layout) :
        case 'detailrtf':
          	$data=$this->get('DataFields');
          	//$this->assignRef('data',$data);
          	$records=$this->get('Record');          	
          	//$this->assignRef('records',$records);          	
          	$fileContent=$this->setRTF($list,$records,$data);
          	//$file = tmpfile();
          	$tmpFile=JPATH_ROOT.DS."media".DS.'com_listmanager'.DS.rand(1000,9999).'_'.time().".rtf";
          	$file = fopen($tmpFile, "w");
          	fwrite($file, $fileContent);
          	//fseek($file, 0);          	
          	break;
          case 'rtf':
          		$data=$this->get('DataFields');
          		//$this->assignRef('data',$data);
          		$records=$this->get('AllDataRecords');
          		//$this->assignRef('records',$records);
          		$fileContent=$this->setListRTF($list,$records,$data);
          		//$file = tmpfile();
          		$tmpFile=JPATH_ROOT.DS."media".DS.'com_listmanager'.DS.rand(1000,9999).'_'.time().".rtf";
          		$file = fopen($tmpFile, "w");
          		fwrite($file, $fileContent);
          		//fseek($file, 0);
          		break;
          	case 'rtffiltered':
          			$data=$this->get('DataFields');
          			//$this->assignRef('data',$data);
          			$records=$this->get('AllDataRecordsFiltered');
          			//$this->assignRef('records',$records);
          			$fileContent=$this->setListRTF($list,$records,$data);
          			//$file = tmpfile();
          			$tmpFile=JPATH_ROOT.DS."media".DS.'com_listmanager'.DS.rand(1000,9999).'_'.time().".rtf";
          			$file = fopen($tmpFile, "w");
          			fwrite($file, $fileContent);
          			//fseek($file, 0);
          			break;
      endswitch;
      
      // get the document
          $document = JFactory::getDocument();
          $document->setMimeEncoding('application/rtf');
          // set the MIME type
          JResponse::setHeader('Content-type','application/rtf');
          JResponse::setHeader('Content-Disposition', 'attachment; filename=listmanager.rtf');
          JResponse::setHeader('Content-Transfer-Encoding', 'binary');
          JResponse::setHeader('Expires', '0');
          JResponse::setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
          JResponse::setHeader('Pragma', 'public');
          //basename($tmp)
          readfile($tmpFile);
          fclose($file); 
          unlink($tmpFile);
      //parent::display($tpl);       
                    
    }      

    function setListRTF($list,$records,$data){
    	$_list='';    	
    	if ($records!=null){
    		$_list=$this->listRtfHelper($data, $records, JRequest::getVar('fullexport'));
    	}
    	$user = JFactory::getUser();
    	$u_username=$user->username;
    	$u_name=$user->name;
    	$u_id=$user->id;
    	$u_email=$user->email;
    	 
    	$layoutHTML=file_get_contents(JPATH_ROOT.DS."media".DS.'com_listmanager'.DS.$list->listrtf);
    	$_precalcprint=htmlspecialchars($layoutHTML,ENT_QUOTES,'UTF-8');
    	$_precalcprint=preg_replace('/([#]{2})(\w)*([#]{2})/', "'.$$0.'", $_precalcprint);
    	$_precalcprint=preg_replace('/([#]{2})/', "", $_precalcprint);
    	eval("\$_pcp='".$_precalcprint."';");
    	return html_entity_decode($_pcp,ENT_QUOTES,'UTF-8');
    	
    }
    
    private function listRtfHelper($data,$records,$fullexport){
    	$result=array();
    	$numcols=1;
    	$total=array();
    	$hasTotal=false;
    	
    	$result[]=' \trowd';
    	$columns=0;
    	foreach ($data as $cabecera){
    		if($cabecera['type']!='17'&&($fullexport=='1'||$cabecera['visible']==1)){
    			$columns++;
    		}
    	}    	
    	$cells=array();
    	$counter=10000/$columns;
    	foreach ($data as $cabecera){
    		if($cabecera['type']!='17'&&($fullexport=='1'||$cabecera['visible']==1)){
    			$cells[]=' \cellx'.$counter;
    			$counter=$counter+10000/$columns;
    		}
    	}
    	$counter=1;
    	$result[]=implode('', $cells);
    	foreach ($data as $cabecera){
    		if($cabecera['type']!='17'&&($fullexport=='1'||$cabecera['visible']==1)){
    			$result[]=' '.$cabecera['name'].'\intbl\cell';
    			//$result[]='\cellx'.$counter.'000';
    			$counter++;
    		}
    	}
    	$result[]=" \\row";
    	if ($records!=null){
    		foreach ($records as $record){
    			$result[]=' \trowd';
    			$result[]=implode('', $cells);
    			foreach ($data as $titcabecera){
		    	  	$texto=$record[$titcabecera['id']];
		    	  	if($titcabecera['total']=='1') {
		    	  		$hasTotal=true;
		    	  		$total[$titcabecera['id']]=$total[$titcabecera['id']]+$texto;
		    	  	}
		    	  	if($titcabecera['type']!='17'&&($fullexport=='1'||$titcabecera['visible']==1)){
		    	  		if($titcabecera['type']=='6'){
		    	  			$texto=$record['_struser'];
		    	  		} elseif($titcabecera['type']=='11' || $titcabecera['type']=='16' || $titcabecera['type']=='2' || $titcabecera['type']=='10'){
		    	  			$texto=str_replace('#',' ', $record[$titcabecera['id'].'m']);
		    	  		} elseif($titcabecera['type']=='15' && $texto!=null && $texto!=''){
		    	  			$texto=number_format($texto,2);
		    	  		} elseif($titcabecera['type']=='9' && $texto!=null && $texto!=''){
		    	  			$texto=strip_tags($texto);
		    	  		}
		    	    $result[]=" ".$texto."\intbl\cell";
		    	  	}
    			}
    			$result[]=" \\row";
    		}
    	}
    	//$result.="</tr>";
    	/*if($hasTotal){
    		$result.="<tr><td colspan='".count($data)."'></td></tr>";
    		$result.="<tr><td style='font-weight:bold' colspan='".count($data)."'>".JText::_("LM_TOTALS")."</td></tr>";
    		$result.="<tr>";
    		foreach ($data as $cabecera){
    	  if($cabecera['total']=='1')
    	  	$result.='<td bgcolor="#e4e4e4"><b>'.$cabecera['name'].'</b></td>';
    	  else
    	  	$result.='<td>&nbsp;</td>';
    		}
    		$result.="</tr>";
    		$result.="<tr>";
    		foreach ($data as $cabecera){
    			$result.="<td style='font-weight:bold'>";
    			if($cabecera['total']=='1') $result.=$total[$cabecera['id']];
    			$result.="</td>";
    		}
    		$result.="</tr>";
    	}*/
    	$result[]="";
    	return implode('', $result);
    }
    
    
    function setRTF($list,$records,$data){    	
    	if ($records!=null){
    		foreach ($records as $record){
    			foreach ($data as $titcabecera){
    				$texto=$record[$titcabecera['id']];
    				if($titcabecera['type']!='17'){
    					if($titcabecera['type']=='6'){
    						$texto=$record['_struser'];
    					} elseif($titcabecera['type']=='11' || $titcabecera['type']=='16' || $titcabecera['type']=='2' || $titcabecera['type']=='10'){
    						$texto=str_replace('#','<br/>', $record[$titcabecera['id'].'m']);
    					} elseif($titcabecera['type']=='15' && $texto!=null && $texto!=''){
    						$texto=number_format($texto,2);
    					}
    					//$datosFila[$titcabecera['innername']]=$texto;
    					$fld_tmpname=$titcabecera['innername'];
    					${$fld_tmpname}=$texto;
    				}
    			}
    		}
    	}    
    	// User variables
    	$user = JFactory::getUser();
    	$u_username=$user->username;	
    	$u_name=$user->name;
    	$u_id=$user->id;
    	$u_email=$user->email;
    	
    	$layoutHTML=file_get_contents(JPATH_ROOT.DS."media".DS.'com_listmanager'.DS.$list->detailrtf);    	 
    	// Cambiamos por HTML precalcprint
    	$_precalcprint=htmlspecialchars($layoutHTML,ENT_QUOTES,'UTF-8');
    	// Sustituimos por variables
    	// &#35;  #
    	$_precalcprint=preg_replace('/([#]{2})(\w)*([#]{2})/', "'.$$0.'", $_precalcprint);
    	$_precalcprint=preg_replace('/([#]{2})/', "", $_precalcprint);
    	eval("\$_pcp='".$_precalcprint."';");
    	// Cambiamos html por characteres
    	//$_precalcprint=html_entity_decode($_pcp,ENT_QUOTES,'UTF-8');
    	return html_entity_decode($_pcp,ENT_QUOTES,'UTF-8');
    }
}

?>
