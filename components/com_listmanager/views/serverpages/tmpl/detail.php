<?php defined('_JEXEC') or die('Restricted access'); // no direct access 
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

// Cambiamos por HTML poscalcprint
//$_poscalcprint=htmlspecialchars($this->detail,ENT_QUOTES,'UTF-8');
$_poscalcprint=$this->detail;
$_poscalcprint=htmlspecialchars($_poscalcprint,ENT_QUOTES,'UTF-8');
$_poscalcprint=preg_replace('/([#]{2})(\w)*([#]{2})/', "'.$$0.'", $_poscalcprint);
$_poscalcprint=preg_replace('/([#]{2})/', "", $_poscalcprint);
// Buscamos los campos de filtro por grupo
//preg_match('/({[#auth](.)*[}]{1})*/', $_poscalcprint, $autharr);
preg_match_all('/\[#auth([^]]+)\]([^]]+)\[#endauth\]/', $_poscalcprint, $autharr,PREG_SET_ORDER);
if (count($autharr)>0):
	$user_  = JFactory::getUser();
	$usergroups=$user_->groups;
	for ($i=0;$i<count($autharr);$i++):
		$occurrence=$autharr[$i];
		$total=$occurrence[0];
		$params=$occurrence[1];
		$content=$occurrence[2];
		preg_match_all('/([yes|no]+)[=]([\d\,]*)/', $params, $gr,PREG_SET_ORDER);
		$result=true;
		if($gr!=null):
			foreach ($gr as $tmpgr):
				$paramsgr=explode(',',$tmpgr[2]);
				foreach ($paramsgr as $pgr):
					if($tmpgr[1]=='yes'):
						if(in_array($pgr, $usergroups)):
							$result=true;
							break;
						endif;
					elseif ($tmpgr[1]=='no'):
						if(in_array($pgr, $usergroups)):
							$result=false;
							break;
						endif;
					endif;
				endforeach;
			endforeach;
		endif;
		if($result):
			$_poscalcprint=str_replace($total, $content, $_poscalcprint);
		else:
			$_poscalcprint=str_replace($total,'', $_poscalcprint);
		endif;		
	endfor;
endif;


$arrMulti=array('2','11','16');
foreach ($this->record[0] as $_key=>$_value):
	foreach ($this->fields as $fields):		
		if ($fields['id']==$_key):
			if(in_array($fields['type'],$arrMulti)) $_value=$this->record[0][$_key.'m'];
			 $_value=str_replace('#', ', ', $_value);
			 $_value=str_replace('\'', '"', $_value);						 
			 eval("\${$fields['innername']}='{$_value}';");
		endif;
	endforeach;
endforeach;

eval("\$_pocp='".$_poscalcprint."';");

$_pocp=html_entity_decode($_pocp,ENT_QUOTES,'UTF-8');


$module_name="mod_listmanager";
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/default2.css','text/css','screen'); 
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/jquery-ui-1.10.0.custom.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/rateit.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/lmbootstrap-default.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/lionbars.css','text/css','screen');

?>
<div id="lm_wrapper">
<?php  echo $_pocp; ?>
</div>

