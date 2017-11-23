<?php

JToolbarHelper::back();
$this->smsresult=JFactory::getApplication()->getUserState('smsresult');
for ($i = 0; $i < count($this->smsresult); $i++) {
		
	var_dump($this->smsresult[$i]);
	
}