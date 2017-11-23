<?php
AImporter::helper('parsecsv.lib','date');
$csv = new parseCSV();
$date = DateHelper::createFromFormat($this->state->get ( 'filter.depart_date' ));

$header=array(JText::_('COM_BOOKPRO_ORDER_NUMBER'),
		JText::_('COM_BOOKPRO_BUSTRIP'),
		JText::_('COM_BOOKPRO_DEPART_DATE'),
		JText::_('COM_BOOKPRO_CUSTOMER'),
		JText::_('COM_BOOKPRO_ORDER_TOTAL'),
		JText::_('COM_BOOKPRO_ORDER_PAY_METHOD'),
		JText::_('COM_BOOKPRO_ORDER_PAY_STATUS'),
		JText::_('COM_BOOKPRO_ORDER_ORDER_STATUS'),
		JText::_('COM_BOOKPRO_CREATED_BY'),
		JText::_('COM_BOOKPRO_ORDER_CREATED')
);

$data=array();
foreach ($this->items as $item) {
	$data[]=array($item->order_number,
			
			$item->fromName.'-'.$item->toName,
			DateHelper::toShortDate($item->start),
			$item->firstname,
			$item->total,$item->pay_method,strval($item->pay_status),
			$item->order_status,
			$item->created_firstname.' '.$item->created_lastname,
			JHtml::_('date',$subject->created,'d-m-Y H:i')
	);

}
$csv->output('orders'.JFactory::getDate()->format('dm').'.csv', $data, $header, ',');

exit;