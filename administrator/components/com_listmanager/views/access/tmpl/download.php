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
require_once JPATH_COMPONENT.DS.'views'.DS.'access'.DS.'helper'.DS.'PHPExcel.php';
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
$headers=array('Id','Idlisting','Iduser','Username','Datetime','Data','Idtype','Ip','Idrecord','Type');
$initChar=65;
$prefix='';
for($i=0;$i<count($headers);$i++):
	$header=$headers[$i];
	$name=$header;
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
endfor;

// Data

$data=$this->datahs;
for($i=0;$i<count($data);$i++):
	$initChar=65;
	$prefix='';
	$row=$data[$i];	
	foreach($row as $value) :				
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
	endforeach;	
endfor;

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Historic access');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>
