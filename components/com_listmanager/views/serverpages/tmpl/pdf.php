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
 
function FancyTable($data,$records,$fullexport,$totals){
$result="";
$numcols=1;
$hasTotal=false;
$result.='<table  border="1" cellpadding="0" cellspacing="0" width="100%" repeat_header="1" ><thead><tr>';
  foreach ($data as $cabecera){
	 if($cabecera['type']!='17'&&($fullexport=='1'||$cabecera['exportable']==1)){
	    $result.='<th bgcolor="#e4e4e4"><b>'.$cabecera['name'].'</b></td>';
	  }
  } 
  $result.="</tr></thead><tbody>";
  if ($records!=null){
  foreach ($records as $record){
	   $result.="<tr>";
	   foreach ($data as $titcabecera){
	  	$texto=$record[$titcabecera['id']];
	  	if($titcabecera['total']!='-1') {
	  		$hasTotal=true;	  		
	  	}
	    if($titcabecera['type']!='17'&&($fullexport=='1'||$titcabecera['exportable']==1)){
	     $result.="<td>";     
	      	if($titcabecera['type']=='6'){
	          $texto=$record['_struser']; 
		    } elseif($titcabecera['type']=='11' || $titcabecera['type']=='16' || $titcabecera['type']=='2' || $titcabecera['type']=='10'){
		          //$texto=str_replace('#','<br/>', $texto);
		          $texto=str_replace('#','<br/>', $record[$titcabecera['id'].'m']);
		    } elseif($titcabecera['type']=='15' && $texto!=null && $texto!=''){
		          $texto=number_format($texto,2);
		    }
	    $result.=$texto."</td>";
	    }  
	   }
	   $result.="</tr>";
	  }
  }
  $result.="</tr>";
  if($hasTotal){  
  	$result.="<tr><td colspan='".count($data)."'></td></tr>";
   $result.="<tr><td style='font-weight:bold' colspan='".count($data)."'>".JText::_("LM_TOTALS")."</td></tr>";
   $result.="<tr>";
  foreach ($data as $cabecera){
	  if($cabecera['total']!='-1') 
	  	$result.='<td bgcolor="#e4e4e4"><b>'.$cabecera['name'].'</b></td>';
	  else	  
	  	$result.='<td>&nbsp;</td>';
  }
   $result.="</tr>";
   $result.="<tr>";
   foreach ($data as $cabecera){
  	$result.="<td style='font-weight:bold'>";     
    if($cabecera['total']!='-1') $result.=$totals[$cabecera['id']];
    $result.="</td>";
   }
   $result.="</tr>";
  }
  $result.="</tbody></table>";
return $result;
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


$ret='<html><body style=\"font-family: GB\">';

//$ret.='<div width="100%" align="center" style="color:#444444"><b/>'.$this->title.'<b/></div>';

// Apply date to html code
$preprint=$this->list->preprint;
if (strpos($this->list->preprint, '##date##') !== false):
	$dateformat=$this->list->date_format;
	$date = new DateTime();
	$preprint=str_replace('##date##',$date->format(__jQueryUIDatePickerFormatToPhpFormat($dateformat)),$preprint);
endif;
$ret.=$preprint;
$ret.=FancyTable($this->data,$this->records,$this->fullexport,$this->totals);
$postprint=$this->list->postprint;
if (strpos($this->list->postprint, '##date##') !== false):
	$dateformat=$this->list->date_format;
	$date = new DateTime();
	$postprint=str_replace('##date##',$date->format(__jQueryUIDatePickerFormatToPhpFormat($dateformat)),$this->list->postprint);
endif;
$ret.=$postprint;

$ret.='</body></html>';
$mpdf;
if ($this->list->pdforientation=='0')
	$mpdf=new mPDF('+aCJK','','10','',20,20,20,20,20,5);
elseif ($this->list->pdforientation=='1')
	$mpdf=new mPDF('+aCJK','A4-L','10','',20,20,20,20,20,5);
$mpdf->useAdobeCJK = true;
//$mpdf->autoScriptToLang = true; //Add to chinese language
$mpdf->autoLangToFont = true;
//$mpdf->SetAutoFont(AUTOFONT_ALL);
$mpdf->AliasNbPages('[pagetotal]');
$footer=array();
$footer[]='<table style="width:100%;margin-bottom:10pt;">';
$footer[]='<tr>';
$footer[]='<td colspan="3" style="height:10pt;"><hr /></td>';
$footer[]='</tr>';
$footer[]='<tr>';
$footer[]='<td style="width:80%"></td>';
$footer[]='<td style="width:10%;font-size:9pt;text-align:right;">{PAGENO} / [pagetotal]</td>';
$footer[]='</tr>';
$footer[]='</table>';
$mpdf->SetHTMLFooter(implode('',$footer));
$mpdf->WriteHTML($ret);
$mpdf->Output(); 