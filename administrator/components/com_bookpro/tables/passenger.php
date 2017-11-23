<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: passenger.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class TablePassenger extends JTable {
	
	/**
	 * Construct object.
	 *
	 * @param JDatabaseMySQL $db
	 *        	database connector
	 */
	function __construct(& $db) {
		parent::__construct ( '#__bookpro_passenger', 'id', $db );
	}
	
	/**
	 * Init empty object.
	 */
	function check() {
		
		if(!$this->id){
			$this->pnr=uniqid('T');			
		}
		
		return true;
	}
	public function store($updateNulls = false) {
			
			
		AImporter::helper ( 'date' );
			//echo  $this->start;die;
			
			if($this->birthday){
				
				$this->birthday=DateHelper::createFromFormat($this->birthday)->format('Y-m-d');
				
			}
			$this->start = JFactory::getDate ( $this->start )->toSql ();
			
			if ($this->return_start != null && $this->return_start != $this->_db->getNullDate ())
				
				$this->return_start = JFactory::getDate ( $this->return_start )->toSql ();
								
			
		
		return parent::store ( $updateNulls );
	}
}

?>