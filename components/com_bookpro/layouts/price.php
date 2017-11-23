<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$prices = $displayData->prices;
$roundtrip = JFactory::getApplication ()->getUserState ( 'filter.roundtrip' );

?>

<style>

.dropbtn {
    font-size: 16px;
    border: none;
    cursor: pointer;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
    position: relative;
    
    display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    z-index: 99999;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
}

/* Links inside the dropdown */
.dropdown-content span {
    color: black;
    padding: 5px 5px;
    text-decoration: none;
    display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
    display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
  
}



</style>

<div class="dropdown">
  <label class="dropbtn" for="bustrip<?php echo $displayData->ord ?>"><span class="jbprice"> <?php 
						   
						   if($roundtrip)
						   	echo CurrencyHelper::formatprice($prices[0]->adult_roundtrip);
						   else 
						   	echo CurrencyHelper::formatprice($prices[0]->adult);
						   	
						   	
						   	?></span><span class="caret"></span></label>
  <div class="dropdown-content">
  
  
  <?php
																							for($i = 0; $i < count ( $prices ); $i ++) {
																								?>
			                    		<span><?php echo $prices[$i]->title ?>:&nbsp;<?php   if($roundtrip)
									   	echo CurrencyHelper::formatprice($prices[$i]->adult_roundtrip);
									   else 
									   	echo CurrencyHelper::formatprice($prices[$i]->adult);
						   	 ?></span>
						   	 <?php }?>
  
  
    
  </div>
</div>



	
