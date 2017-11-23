<?php defined('_JEXEC') or die('Restricted access'); 
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


require_once JPATH_COMPONENT.DS.'views'.DS.'serverpages'.DS.'helper'.DS.'PHPExcel.php';

// Initiate cache
/*$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
$cacheSettings = array( 'dir'  => JPATH_CACHE);
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
*/
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();


// Set document properties
$objPHPExcel->getProperties()->setCreator("List Manager (www.moonsoft.com)")
							 ->setLastModifiedBy("")
							 ->setTitle("")
							 ->setSubject("")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Header
//$headers=$this->table_fields_csv;
$headers=$this->data;
$initChar=65;
$prefix='';
for($i=0;$i<count($headers);$i++):
	$header=$headers[$i];	
	if($header['type']!='17'&&($this->fullexport=='1'||$header['visible']==1)):
		//$name=utf8_decode($header['name']);
		$name=$header['name'];
		if ($prefix!=''):
	    	$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($prefix).chr($initChar++).'1', $name);
	    else:
	    	$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($initChar++).'1', $name);
	    endif;
	    if ($initChar==91):
	    	$initChar=65;
			if($prefix!='') $prefix++;
			else $prefix=65;		
		endif;	
	endif;	
endfor;


// Data
//$data=$this->all_data_fields;
$data=$this->records;
for($i=0;$i<count($data);$i++):
	$initChar=65;
	$prefix='';	
	$record=$data[$i];
	foreach ($this->data as $titcabecera):
		if($titcabecera['type']!='17'&&($this->fullexport=='1'||$titcabecera['visible']==1)):
			$texto=isset($record[$titcabecera['id']])?$record[$titcabecera['id']]:'';			
			if($titcabecera['type']=='6'):
				$texto=isset($record['_struser'])?$record['_struser']:'';
			elseif($titcabecera['type']=='9'):
				$texto=strip_tags($texto);
			elseif($titcabecera['type']=='11' || $titcabecera['type']=='16' || $titcabecera['type']=='2' || $titcabecera['type']=='10'):
				$texto=str_replace('#','&nbsp;&nbsp;', $record[$titcabecera['id'].'m']);		
			endif;
			//$value=html_entity_decode(mb_convert_encoding($texto, "HTML-ENTITIES","UTF-8"));			
			$value=str_replace('&nbsp;', ',', $texto) ;			
			
			if ($prefix!=''):
	    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($prefix).chr($initChar++).($i+2), $value);
	    	else:
	    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($initChar++).($i+2), $value);
	    	endif;    	
	    	if ($initChar==91):
	    		$initChar=65;
				if($prefix!='') $prefix++;
				else $prefix=65;			
			endif;		
		endif;
	endforeach;	
endfor;

// Rename worksheet
//$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

