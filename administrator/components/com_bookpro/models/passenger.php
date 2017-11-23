   <?php
/**
* @version		$Id:passenger.php  1 2014-03-15 18:20:26Z Quan $
* @package		Bookpro1
* @subpackage 	Models
* @copyright	Copyright (C) 2014, Ngo Van Quan. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
 defined('_JEXEC') or die('Restricted access');
 
class BookproModelPassenger  extends JModelAdmin { 


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_bookpro.passenger', 'passenger', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
	
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_bookpro.edit.passenger.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}
		
		
		return $data;
	}
	function getItem($pk= null){
		$item = parent::getItem($pk);
				
		return $item;
	}
	

	function deletePassenger($order_id){
		if ($order_id) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$query->delete('#__bookpro_passenger AS passenger');
			$query->where('passenger.order_id='.$order_id);
			$db->setQuery($query);
			$db->execute();
		}
	}
	function deleteByRoute($route_id){
		AImporter::model('order');
		
		if ($route_id) {

			$model = new BookProModelOrder();
			$model->deleteByRoute($route_id);
			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->delete('#__bookpro_passenger AS passenger');
			$query->where('passenger.route_id='.$route_id.' OR passenger.return_route_id='.$route_id);
			$db->setQuery($query);
			$db->execute();
			
		}
	}
	
	function getSeatById(){
		$input=JFactory::getApplication()->input->get('id', 0);
		$db = $this->getDbo ();
		$query = $db->getQuery( true );
		$query->select('a.*');
		$query->from('#__bookpro_passenger as a');
		$query->where('a.id='.$input);
		$db->setQuery($query);
		$result=$db->loadObject();
		return $result;
	}
	
}
?>