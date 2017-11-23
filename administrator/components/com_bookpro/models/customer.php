<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: customer.php 108 2012-09-04 04:53:31Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
use Joomla\Registry\Registry;
class BookProModelCustomer extends JModelAdmin {
	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm ( 'com_bookpro.customer', 'customer', array (
				'control' => 'jform',
				'load_data' => $loadData 
		) );
		
		if (empty ( $form ))
			return false;
		return $form;
	}
	
	/*
	 * function getIdByUserId() { $user = &JFactory::getUser(); @var $user JUser $customer_id=null; if ($user->id) { $query = 'SELECT `customer`.`id` '; $query .= 'FROM `' . $this->_table->getTableName() . '` AS `customer` '; $query .= 'LEFT JOIN `#__users` AS `user` ON `customer`.`user` = `user`.`id` '; // is active customer $query .= 'WHERE `customer`.`user` = ' . $user->id; // juser is active //$query .= ' AND `user`.`block` = 0'; $this->_db->setQuery($query); $customer_id = (int) $this->_db->loadResult(); } return $customer_id; }
	 */
	protected function loadFormData() {
		$data = JFactory::getApplication ()->getUserState ( 'com_bookpro.edit.customer.data', array () );
		if (empty ( $data )) {
			$data = $this->getItem ();
		}
		return $data;
	}
	public function getComplexItem($pk) {
		$db = JFactory::getDBO ();
		$query = $db->getQuery ( true );
		$query->select ( 'a.*,c.country_name,u.username' );
		$query->from ( '#__bookpro_customer AS a' );
		$query->leftJoin ( '#__bookpro_country AS c ON a.country_id = c.id' );
		$query->leftJoin ( '#__users AS u ON a.user = u.id' );
		$query->where ( 'a.id = ' . ( int ) $pk );
		$db->setQuery ( $query );
		$item = $db->loadObject ();
		return $item;
	}
	function getItemByUser() {
		$user = JFactory::getUser ();
		
		if ($user->id) {
			$db = JFactory::getDBO ();
			$query = $db->getQuery ( true );
			
			$query->select ( 'c.*' )->from ( '#__bookpro_customer AS c' )->leftJoin ( '#__users AS u ON u.id=c.user' )->where ( 'u.id=' . $user->id );
			$db->setQuery ( $query );
			$item = $db->loadObject ();
		} else {
			
			$customer = JTable::getInstance ( 'Customer', 'Table' );
			$customer->load ();
			
			$item = $customer;
		}
		
		return $item;
	}
	public function getTable($type = 'Customer', $prefix = 'Table', $config = array()) {
		return JTable::getInstance ( $type, $prefix, $config );
	}
	public function save($data) {
		
		
		if (isset($data['params']) && is_array($data['params']))
		{
			$registry = new Registry;
			$registry->loadArray($data['params']);
			$data['params'] = (string) $registry;
		}
		
		if ($data ['id'] == 0) {
			
			if ($data ['createacc'] == 1) {
				$user = JUser::getInstance ( $data ['user'] );
				$data ['email'] = $user->email;
				
			}
			
			if ($data ['createacc'] == 0) {
				
				$user = JFactory::getUser(0);
				$user->password_clear=$data ['password'];
				$user->email = $data ['email'];
				$user->name= $data ['firstname'].' '. $data ['lastname'];
				$user->username = $data ['email'];
				$user->groups=array(2);
				$user->password =JUserHelper::getCryptedPassword($data ['password']);
				
				$user->block=0;
				try {
					
					if(!$user->save ()){
						return false;
					}
					//var_dump($user);die;
					$data['user']=$user->id;
					
				} catch (Exception $e) {
					JFactory::getApplication()->enqueueMessage($e->getMessage());
					return false;
				}
				//var_dump($user);die;
				
				
			}
			return parent::save ( $data );
			
		}else {
			
			return parent::save ( $data );
			
		}
	}
	
	/**
	 * Save customer.
	 *
	 * @param array $data
	 *        	request data
	 * @return customer id if success, false in unsuccess
	 */
	public function publish(&$pks, $value = 1) {
		$user = JFactory::getUser ();
		$table = $this->getTable ();
		$pks = ( array ) $pks;
		
		// Attempt to change the state of the records.
		if (! $table->publish ( $pks, $value, $user->get ( 'id' ) )) {
			$this->setError ( $table->getError () );
			
			return false;
		}
		
		return true;
	}
}

?>