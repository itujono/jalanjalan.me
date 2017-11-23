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

function FancyTable($data,$records,$layoutHTML){
	$result="";
	$numcols=1;
	//$datosFila=array();
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
  // Cambiamos por HTML precalcprint
  $_precalcprint=htmlspecialchars($layoutHTML,ENT_QUOTES,'UTF-8');
  // Sustituimos por variables
  // &#35;  #
  $_precalcprint=preg_replace('/([#]{2})(\w)*([#]{2})/', "'.$$0.'", $_precalcprint);
  $_precalcprint=preg_replace('/([#]{2})/', "", $_precalcprint);
  $_sol='';
  eval("\$_pcp='".$_precalcprint."';");
  // Cambiamos html por characteres
  $_precalcprint=html_entity_decode($_pcp,ENT_QUOTES,'UTF-8');
  
  return html_entity_decode($_pcp);
}




$ret='<html><body style=\"font-family: GB\">';

//$ret.='<div width="100%" align="center" style="color:#444444"><b/>'.$this->title.'<b/></div>';

$ret.=FancyTable($this->data,$this->records,$this->detailpdf);


$mpdf=new mPDF('+aCJK','','10','',20,20,20,20,20,5);
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
//$mpdf->SetHTMLFooter(implode('',$footer));
$mpdf->WriteHTML($ret);
$mpdf->Output();
