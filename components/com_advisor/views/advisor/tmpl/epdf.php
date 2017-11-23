<?php defined('_JEXEC') or die('Restricted access');
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
$ret='<html>';
$ret.='<body>';
$ret.=$this->flow['prehtml'];
$ret.='<h3>'.JText::_('YOUR_SELECTION').'</h3>';
$ret.='<br />';
$ret.='<table width="100%">';
$columns_resume=$this->flow['container']+1;
$counter=0;
$ret.='<tr>';


foreach ($this->resume as $option){	
	$ret.='<td>';
	$ret.='<div><b>'.$option->stepname.'</b></div>';
	$ret.='<div>'.$option->desc.'</div>';
	$ret.='<div>'.$option->content.'</div>';
	$ret.='<td>';
	if(($counter+1)%$columns_resume==0){
		$ret.='</tr><tr>';
	}
	$counter++;	
}
$ret.='</tr>';
$ret.='</table>';
$ret.='<br /><br />';
$ret.='<h3>'.JText::_('RESULTS_TEXT').'</h3>';
$ret.='<br /><br />';
$ret.='<table width="100%">';
//$columns_resume=$this->flow['container']+1;
$counter=0;
$ret.='<tr>';

foreach ($this->solutions as $solution){	
	$ret.='<td>';
	$ret.='<div><b>'.$solution->title.'</b></div>';
	$ret.='<div>'.$solution->content.'</div>';
	$ret.='<td>';	
	if(($counter+1)%$columns_resume==0){
		$ret.='</tr><tr>';
	}
	$counter++;	
}
$ret.='</tr>';
$ret.='</table>';

$ret.='<br /><br />';
$ret.=$this->flow['posthtml'];
$ret.='</body></html>';


$mpdf=new mPDF('','A4');
$mpdf->WriteHTML($ret);
$mpdf->Output();