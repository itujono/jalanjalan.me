<?php
AImporter::helper('parsecsv.lib','date');
$csv = new parseCSV();
$date = DateHelper::createFromFormat($this->state->get ( 'filter.depart_date' ));

$header=array(JText::_('COM_BOOKPRO_PASSENGER_FIRSTNAME'),
		JText::_('COM_BOOKPRO_PASSENGER_LASTNAME'),
		JText::_('COM_BOOKPRO_PASSENGER_EMAIL'),
		JText::_('COM_BOOKPRO_PASSENGER_PHONE'),
		JText::_('COM_BOOKPRO_PASSENGER_GENDER'),
		JText::_('COM_BOOKPRO_PASSENGER_GROUP'),
		JText::_('COM_BOOKPRO_DEPART_DATE'),
		JText::_('COM_BOOKPRO_ORDER_NUMBER')
);

$data=array();
foreach ($this->items as $item) {
	$data[]=array($item->firstname,
			$item->lastname,
			$item->email,strval($item->phone),
			BookProHelper::formatGender($item->gender),
			$item->group_title,DateHelper::toShortDate($date->format('Y-m-d')),
			strval($item->order_number)
	);

}
$csv->output('passenger'.JFactory::getDate()->format('dm').'.csv', $data, $header, ',');

exit;