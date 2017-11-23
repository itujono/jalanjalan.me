<?php
/*
 * @component %%COMPONENTNAME%% 
 * @copyright Copyright (C) August 2017. 
 * @license GPL 3.0
 * This program is free software: you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * See <http://www.gnu.org/licenses/>.
 * More info www.moonsoft.es gestion@moonsoft.es 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

require 'main.model.php';

class AdvisorModelAdvisor extends AdvisorMainModel
{
    
    function _buildQuerySelect($id=null){  
    	if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();
        $query = "select * from #__advisor_flow where id=".$id." and published=1";
        return $query;
    }

	function _buildQuerySelectIdNextStep($idcurrentstep=null,$strseleccion=null){  
    	if (!is_numeric($idcurrentstep)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();        
        $query = "select id from #__advisor_step where idprevstep=".$idcurrentstep." and (precondition is null or precondition='' ";        
        $query.= " or precondition in (select value from #__advisor_option ao where ao.id in ".$strseleccion.")";        
        $query.= "  ) order by precondition desc limit 1";
        return $query;
    }
    
	function _buildQuerySelectStep($idstep=null){  
    	if (!is_numeric($idstep)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();
        $query = "select * from #__advisor_step where id=".$idstep;
        return $query;
    }
    
	function _buildQuerySelectSteps($idflow=null){  
    	if (!is_numeric($idflow)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();
        $query = "select * from #__advisor_step where idflow=".$idflow;
        return $query;
    }
    
	function _buildQuerySelectOptions($idstep=null){  
    	if (!is_numeric($idstep)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();
        $query = "select * from #__advisor_option where idstep=".$idstep.' order by `order`';
        return $query;
    }
    
	function _buildQuerySelectLastStep($idflow=null){  
    	if (!is_numeric($idflow)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();
        $query = "select s.* from #__advisor_step s where s.idflow=".$idflow." and s.id not in 
        	(select sin.idprevstep from #__advisor_step sin where sin.idflow=".$idflow.")";
        return $query;
    }
	function _buildQuerySelectFirstStep($idflow=null){  
    	if (!is_numeric($idflow)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();
        $query = "select s.* from #__advisor_step s where s.idflow=".$idflow." and s.idprevstep=0";
        return $query;
    }
	function _buildQuerySelectOption($idoption=null){  
    	if (!is_numeric($idoption)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();
        $query = "select o.*, s.name as stepname from #__advisor_option o left join #__advisor_step s on o.idstep=s.id where o.id=".$idoption;
        return $query;
    }
    
	function _buildQuerySelectSolutions($idflow=null){  
    	if (!is_numeric($idflow)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();
        $query = "select a.id, a.idproduct, a.idhikaproduct,a.idvirtueproduct,a.idjoomlaproduct, p.title, p.content
			from #__advisor_solution a left join #__advisor_product p on a.idproduct=p.id
			where a.idflow=".$idflow;
        return $query;
    }
    
	function _buildQuerySelectSolutionOptions($idsolution=null){  
    	if (!is_numeric($idsolution)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));      
        $db =JFactory::getDBO();
        $query = "select * from #__advisor_solution_option where idsolution=".$idsolution;
        return $query;
    }
    
   function _buildQuerySaveStats($flow,$strseleccion){   
       if (!is_numeric($flow)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
       $query="insert into #__advisor_stats (flow,optionvalues,date) values(".$flow.",'".$strseleccion."',now())";
       return $query;
   }
    
    function getConfigWrapper(){
    	$db = JFactory::getDBO();
    	$flow=JRequest::getVar('idflow');
      	$query = $this->_buildQuerySelect($flow);
      	$db->setQuery($query);
      	return $db->loadAssoc();
    }
    
    function getStepsFlow(){
    	$db = JFactory::getDBO();
    	$flow=JRequest::getVar('idflow');
      	$query = $this->_buildQuerySelectSteps($flow);
      	$db->setQuery($query);
      	return $db->loadAssocList();
    }
    
	function getLastStep(){
    	$db = JFactory::getDBO();
    	$flow=JRequest::getVar('idflow');
      	$query = $this->_buildQuerySelectLastStep($flow);
      	$db->setQuery($query);
      	return $db->loadObject();
    }
    
	function getFirstStep(){
    	$db = JFactory::getDBO();
    	$flow=JRequest::getVar('idflow');
      	$query = $this->_buildQuerySelectFirstStep($flow);
      	$db->setQuery($query);
      	return $db->loadObject();
    }
    
	function getConfig(){
    	$ret=array();
    	$ret['config']=$this->getConfigWrapper();
    	$ret['steps']=$this->getStepsFlow();
    	return $ret;
    }
    
    
    
    
    
   
    
	function getStep($step){
    	$db = JFactory::getDBO();
    	$query = $this->_buildQuerySelectStep($step);
      	$db->setQuery($query);
      	return $db->loadObject();    	
    }
    
	function getOptions($step){
    	$db = JFactory::getDBO();
    	$query = $this->_buildQuerySelectOptions($step);
      	$db->setQuery($query);
      	return $db->loadAssocList();    	
    }
    
	function _getSolutionsFlow($flow){
    	$db = JFactory::getDBO();
    	$query = $this->_buildQuerySelectSolutions($flow);
      	$db->setQuery($query);
      	return $db->loadObjectList();    	
    }
    
	function _getSolutionOptions($solution){
    	$db = JFactory::getDBO();
    	$query = $this->_buildQuerySelectSolutionOptions($solution);
      	$db->setQuery($query);
      	return $db->loadObjectList('idstep');    	
    }    
	
    function _getSolutions(){
    	$flow=JRequest::getVar('idflow');
    	$products=array(); 
    	$ret=array();   	
    	// get session options selected
    	$session =JFactory::getSession();
    	$data_session=$session->get('ad_'.$flow);
    	
       //	echo $data_session;
    	
    	// get solutions and filter 
   	
    	$solutions=$this->_getSolutionsFlow($flow);
    	$hikaload=true;
    	if(!@include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_hikashop'.DS.'helpers'.DS.'helper.php')){ $hikaload=false; }
    	 
    	
    	foreach($solutions as $solution){
    	
    	
    	
    	
    	
    		$solution_option=$this->_getSolutionOptions($solution->id);
    		$in_range=true;
    		
    		foreach ($solution_option as $option){    		
    			if($option->idoption=='0' ) continue;
    			if(!array_key_exists($option->idstep,$data_session)||$option->idoption!=$data_session[$option->idstep]->id){
    				$in_range=false;
    				break;	
    			} 
    		}
    		
    		
    		if ($in_range){
    		
    		  $idfinal=$solution->idproduct;
    		  if($solution->idhikaproduct!=0)
    		    $idfinal="H".$solution->idhikaproduct;
    		  if($solution->idvirtueproduct!=0)
    		    $idfinal="V".$solution->idvirtueproduct;  
    		  if($solution->idjoomlaproduct!=0)
    		    $idfinal="J".$solution->idjoomlaproduct;    
    		    
    		  
    		
    			if (!in_array($idfinal,$products)){
    			
    				$products[]=$idfinal;
    				
    				if($solution->idjoomlaproduct!=0){
    			   				
    				$url=JURI::base()."index.php?option=com_content&view=article&id=".$solution->idjoomlaproduct."&tmpl=component";
    				$curl = curl_init();
             $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
             
             curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
             curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
             curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5); //The number of seconds to wait while trying to connect.	
             
             curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
             curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
             curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
             curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
             curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.	
             
             $contents = curl_exec($curl);
             curl_close($curl);
             $solution->content =$contents;
             }
             
             
    				if($solution->idhikaproduct!=0){
    					$productClass = hikashop_get('class.product');
    					$product=$productClass->get($solution->idhikaproduct);
    					/*$price=$product->prices[0]->price_value_with_tax;
    					 $config = hikashop_config();
    					 $image = reset($product->images);
    					 $path = JPATH_ROOT.DS.$config->get('uploadfolder').$image->file_path;*/
    					$layout      = new JLayoutFile('hika_product_card', $basePath = null);
    					$sidebarHtml = $layout->render($product);
    					$solution->content =$sidebarHtml;
    					
    			/*   				
    			$url=JURI::base()."index.php?option=com_hikashop&ctrl=product&task=show&cid=".$solution->idhikaproduct."&tmpl=component";
    				$curl = curl_init();
             $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
             
             curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
             curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
             curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5); //The number of seconds to wait while trying to connect.	
             
             curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
             curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
             curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
             curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
             curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.	
             
             $contents = curl_exec($curl);
             curl_close($curl);
             $solution->content =$contents;*/
             
             //$solution->content ="Hika Product Here";
             }
             
             if($solution->idvirtueproduct!=0){
    			   		
      			/*$url=JURI::base()."index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=".$solution->idvirtueproduct."&tmpl=component&format=pdf";
      				$curl = curl_init();
               $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
               
               curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
               curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
               curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5); //The number of seconds to wait while trying to connect.	
               
               curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
               curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
               curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
               curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
               curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.	
               
               $contents = curl_exec($curl);
               curl_close($curl);
               $solution->content =$contents;*/
               
               
               
               if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
               VmConfig::loadConfig();
              $productModel = VmModel::getModel('Product');
              
              $virtueproduct=$productModel->getProduct($solution->idvirtueproduct,TRUE,TRUE,TRUE,1);
              $productModel->addImages($virtueproduct);
              //$virtueproduct=$productModel->getProductSingle($solution->idvirtueproduct);
              
               //$solution->content=$virtueproduct->product_name.":".$virtueproduct->product_price.":".$virtueproduct->product_s_desc.":"$virtueproduct->.product_desc;
               //header
               $producttxt='<div class="productdetails-view"><h1>'.$virtueproduct->product_name.'</h1>';
	
            	//image
            	
            	if(!empty($virtueproduct->images) && count($virtueproduct->images)>0) {
            	//$producttxt.=$virtueproduct->images[0]->displayMediaFull('class="product-image"',false);
            	$producttxt.=$virtueproduct->images[0]->displayMediaThumb('class="product-image"',false);
            	
            	/*$producttxt.='<div class="additional-images">';
		
            		foreach ($virtueproduct->images as $image) {
            			$producttxt.= $image->displayMediaThumb('class="product-image"',false); 
            
            		} 
            	$producttxt.=	'</div>';
               */
            	
            	}
            	//Short desc
            	$producttxt.=$virtueproduct->product_s_desc;
            	//Prices
            	
            		$producttxt.=	'<div class="product-price" id="productPrice'.$virtueproduct->virtuemart_product_id .'">';
				
				
				$currency = CurrencyDisplay::getInstance();
			
				if ($virtueproduct->product_unit && VmConfig::get ( 'price_show_packaging_pricelabel' )) {
					$producttxt.= "<strong>" . JText::_ ( 'COM_VIRTUEMART_CART_PRICE_PER_UNIT' ) . ' (' . $virtueproduct->product_unit . "):</strong>";
				} else {
					$producttxt.= "<strong>" . JText::_ ( 'COM_VIRTUEMART_CART_PRICE' ) . "</strong>";
				}
				$producttxt.= $currency->createPriceDiv ( 'variantModification', 'COM_VIRTUEMART_PRODUCT_VARIANT_MOD', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'basePriceWithTax', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'discountedPriceWithoutTax', 'COM_VIRTUEMART_PRODUCT_DISCOUNTED_PRICE', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'taxAmount', 'COM_VIRTUEMART_PRODUCT_TAX_AMOUNT', $virtueproduct->prices ); 
				$producttxt.='</div>';
            	
            	
            	$producttxt.='<a href="'.JURI::base().'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$solution->idvirtueproduct.'" target="_blank"> <input type="button" class="btn btn-primary" value="'.JText::_('AD_VMPRODUCTVIEW').'"></a>';
            	
            	
            	$producttxt.=	'</div>';
            	
            	$solution->content=$producttxt;
            	
            	
	/*
	if(!empty($this->product->images) && count($this->product->images)>0) {
		echo  
		<div class="additional-images">
		<?php // List all Images
		foreach ($this->product->images as $image) {
			echo $image->displayMediaThumb('class="product-image"'); //'class="modal"'

		} 
		</div>
	<?php } // Showing The Additional Images END 

	<?php // Product Short Description
	if (!empty($this->product->product_s_desc)) { 
	<div class="product-short-description">
		
		echo $this->product->product_s_desc; 
	</div>
	php } // Product Short Description END*/ 


	
	
             }
 
 
    				$ret[]=$solution;
    			}    			
    		}
    	}    
    	
    	
    	
    	$options_session=$session->get('adoptions_'.$flow);
    	
    	$strseleccion="#";
    	
    	foreach($options_session as $idoption){
            $strseleccion.=$idoption."#";
    		
    	}
    	
    	
    	//SAVE STATS
    	$db =JFactory::getDBO();
     
      $_query = $this->_buildQuerySaveStats($flow,$strseleccion);
      
      //var_dump($_query);
      $db->setQuery( $_query);
      $db->query();	 
      	//$data_session
      	//$ret[]
      	
      	
      	
      	
    	return $ret;	 
    }
    function getSolutionsPDF(){
    //Modificado para incluir productos Hika y artículos:
    
    
    $flow=JRequest::getVar('idflow');
    	$products=array(); 
    	$ret=array();   	
    	// get session options selected
    	$session =JFactory::getSession();
    	$data_session=$session->get('ad_'.$flow);
    	
       	
    	
    	// get solutions and filter 
   	
    	$solutions=$this->_getSolutionsFlow($flow);
    	
    	 
    	
    	foreach($solutions as $solution){
    	
    	
    	
    	
    	
    		$solution_option=$this->_getSolutionOptions($solution->id);
    		$in_range=true;
    		
    		foreach ($solution_option as $option){    		
    			if($option->idoption=='0' ) continue;
    			if(!array_key_exists($option->idstep,$data_session)||$option->idoption!=$data_session[$option->idstep]->id){
    				$in_range=false;
    				break;	
    			} 
    		}
    		
    		
    		if ($in_range){
    		
    		  $idfinal=$solution->idproduct;
    		  if($solution->idhikaproduct!=0)
    		    $idfinal="H".$solution->idhikaproduct;
    		  if($solution->idvirtueproduct!=0)
    		    $idfinal="V".$solution->idvirtueproduct;  
    		  if($solution->idjoomlaproduct!=0)
    		    $idfinal="J".$solution->idjoomlaproduct;    
    		    
    		  
    		
    			if (!in_array($idfinal,$products)){
    			
    				$products[]=$idfinal;
    				
    				if($solution->idjoomlaproduct!=0){
    			   				
             $db = JFactory::getDBO();
             $query = "SELECT introtext FROM #__content c where c.id=".$solution->idjoomlaproduct; 

              $db->setQuery($query);
              $contents=$db->loadResult();
              $solution->content =$contents."<hr>";
             }
             
             
    				if($solution->idhikaproduct!=0){
    			   				
    			$url=JURI::base()."index.php?option=com_hikashop&ctrl=product&task=show&cid=".$solution->idhikaproduct."&tmpl=component";
    				$curl = curl_init();
             $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
             
             curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
             curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
             curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5); //The number of seconds to wait while trying to connect.	
             
             curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
             curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
             curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
             curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
             curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.	
             
             $contents = curl_exec($curl);
             curl_close($curl);
             
             
             ////////////////////////////////////////
            $bodyandend = stristr($contents."","<form");
            
$positionendstartbodytag = strpos($bodyandend,">") + 1;
$temptofindposition=strtolower($bodyandend);
$positionendendbodytag=strpos($temptofindposition,"</form>")-$positionendstartbodytag; 
             
             $grabbedbody=substr($bodyandend,
     $positionendstartbodytag,
           $positionendendbodytag);
                     $contents = $grabbedbody."</div>";
             
             //////////////////////
            
             
             //$solution->content =str_replace("<", "ht", $contents);
             /*
             
             $contents=preg_replace('/(<head>.+?)+(<\/head>)/i', '', $contents);
             */
             $solution->content=$contents."<hr>"; 
             //$solution->content =strip_tags($contents, '');
             }
             
             if($solution->idvirtueproduct!=0){
    			   		
      		        
               
               if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
               VmConfig::loadConfig();
              $productModel = VmModel::getModel('Product');
              
              $virtueproduct=$productModel->getProduct($solution->idvirtueproduct,TRUE,TRUE,TRUE,1);
              $productModel->addImages($virtueproduct);
              //$virtueproduct=$productModel->getProductSingle($solution->idvirtueproduct);
              
               //$solution->content=$virtueproduct->product_name.":".$virtueproduct->product_price.":".$virtueproduct->product_s_desc.":"$virtueproduct->.product_desc;
               //header
               $producttxt='<div class="productdetails-view"><h1>'.$virtueproduct->product_name.'</h1>';
	
            	//image
            	
            	if(!empty($virtueproduct->images) && count($virtueproduct->images)>0) {
            	//$producttxt.=$virtueproduct->images[0]->displayMediaFull('class="product-image"',false);
            	$producttxt.=$virtueproduct->images[0]->displayMediaThumb('class="product-image"',false);
        
            	
            	}
            	//Short desc
            	$producttxt.=$virtueproduct->product_s_desc;
            	//Prices
            	
            		$producttxt.=	'<div class="product-price" id="productPrice'.$virtueproduct->virtuemart_product_id .'">';
				
				
				$currency = CurrencyDisplay::getInstance();
			
				if ($virtueproduct->product_unit && VmConfig::get ( 'price_show_packaging_pricelabel' )) {
					$producttxt.= "<strong>" . JText::_ ( 'COM_VIRTUEMART_CART_PRICE_PER_UNIT' ) . ' (' . $virtueproduct->product_unit . "):</strong>";
				} else {
					$producttxt.= "<strong>" . JText::_ ( 'COM_VIRTUEMART_CART_PRICE' ) . "</strong>";
				}
				$producttxt.= $currency->createPriceDiv ( 'variantModification', 'COM_VIRTUEMART_PRODUCT_VARIANT_MOD', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'basePriceWithTax', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'discountedPriceWithoutTax', 'COM_VIRTUEMART_PRODUCT_DISCOUNTED_PRICE', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $virtueproduct->prices );
				$producttxt.= $currency->createPriceDiv ( 'taxAmount', 'COM_VIRTUEMART_PRODUCT_TAX_AMOUNT', $virtueproduct->prices ); 
				$producttxt.='</div>';
            	
            	
            	$producttxt.='<a href="'.JURI::base().'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$solution->idvirtueproduct.'" target="_blank"> <input type="button" class="btn btn-primary" value="'.JText::_('AD_VMPRODUCTVIEW').'"></a>';
            	
            	
            	$producttxt.=	'</div>';
            	
            	$solution->content=$producttxt;
            	
            	



	
	
             }
 
            //$solution->content="<p>".$solution->content."</p>";
    				$ret[]=$solution;
    			}    			
    		}
    	}    
    	
    	
    	
      	
      	
    	return $ret;	 
    
    
    
    	//return $this->_getSolutions();
    }
    
	function getOption($option){
    	$db = JFactory::getDBO();
    	$query = $this->_buildQuerySelectOption($option);
      	$db->setQuery($query);
      	return $db->loadObject();    	
    }
    
    
    
   function getIdNextStep($step){
    	$db = JFactory::getDBO();
      
      	
      $flow=JRequest::getVar('idflow');
    
    	   	
    	// get session options selected
    	$session =JFactory::getSession();
    	
    	
    	$options_session=$session->get('adoptions_'.$flow);
    	
    	$strseleccion="(-1";
    	
    	foreach($options_session as $idoption){
            $strseleccion.=",".$idoption;
    		
    	}
    	
    	$strseleccion.=")";
    	
    		
      	$query = $this->_buildQuerySelectIdNextStep($step,$strseleccion);
      	$db->setQuery($query);
      	
      	return $db->loadResult();    	
    }  
	function getNextstep(){
	
  	$session =JFactory::getSession();
  	$flow=JRequest::getVar('idflow');    	
	
	
		  $ret=array();
    	$step=JRequest::getVar('idstep');
    	
    	$idoptionselected=JRequest::getInt('idoptionselected',0);
   
    	$ret['solution_page']='false';
    	
    	    	
    	//Presentacion
    	
    	if($step=="-2"){
      
        $fs=$this->getConfigWrapper();
        $ret['idstep']="0";
        $ret['content']=$fs['firstpage'];
      	$ret['name']=$fs['title'];
      	$ret['text']=''; 
      	$ret['main']='true';   	
        
        
        $options_session=array();
        $session->set('adoptions_'.$flow,$options_session);
      	$data_session=array();    
      	$session->set('ad_'.$flow,$data_session);
          	
      }
      
      
      
      
      
      //ROOT
      
      if($step=="0"){
        $firstStep=$this->getFirstStep();
        
        
        $ret['idstep']=$firstStep->id;
        $ret['text']=$firstStep->text;
      	$ret['name']=$firstStep->name;
      	$ret['options']=$this->getOptions($firstStep->id); 
     	
      	$ret['main']='false';   	
      
      }
      
      
      //interm. steps
      
      if($step!="-2"&&$step!="0"){
      
      
        $options_session=$session->get('adoptions_'.$flow);
        
        array_push($options_session, $idoptionselected);
        
        
        $session->set('adoptions_'.$flow,$options_session);
                    
        
        $data_session=$session->get('ad_'.$flow);
  			     
        $data_session[$step]=$this->getOption($idoptionselected);
  	   	
    			
  			$session->set('ad_'.$flow,$data_session);
        
        
        $ret['resume']=$data_session;
        
      
	    	$nextId=$this->getIdNextStep($step);
	    	
    	//END
	    	
	    	if($nextId==null){
          $nextId=-1;
          $ret['idstep']=$nextId;
          $ret['solution_page']='true';
      		$ret['products']=$this->_getSolutions();
      		$ret['text']=JText::_("RESULTS_TEXT");
      		$ret['name']=JText::_("RESULTS_NAME");
      		$ret['main']='false';
        
        } 
	    	
	    	else{
	    	
  	    	$current_step=$this->getStep($nextId);
  	    	$ret['idstep']=$nextId;
      		$ret['text']=$current_step->text;
      		$ret['name']=$current_step->name;
      		$ret['options']=$this->getOptions($current_step->id);
      		$ret['main']='false';
    		}
      
      }
      
      
    	
      	return $ret;
    }
	
	
	
	function getRewindFlow(){
		$session =JFactory::getSession();
	    $flow=JRequest::getVar('idflow');    		
	
		$ret=array();
		
		
		$options_session=$session->get('adoptions_'.$flow);
        
		

        $idoptionselected=array_pop($options_session);

        
        $session->set('adoptions_'.$flow,$options_session);
                    
        
        $data_session=$session->get('ad_'.$flow);
		
		
		array_pop($data_session);
		
		
		$lastid=0;
		foreach ($data_session as $idstep=>$data){
			$lastid=$idstep;
		}
		
	//var_dump($lastid);
	
	
	   $session->set('ad_'.$flow,$data_session);
		
		
    	$step=$lastid;
    	
  
   
    	$ret['solution_page']='false';
    	
    	$ret['resume']=$data_session;
		
		if($step=="0"){
				$firstStep=$this->getFirstStep();
				
				
				$ret['idstep']=$firstStep->id;
				$ret['text']=$firstStep->text;
				$ret['name']=$firstStep->name;
				$ret['options']=$this->getOptions($firstStep->id); 
				
				$ret['main']='false';   	
      
		}
	  
	    else{
		
		    $nextId=$this->getIdNextStep($step);
		
		   $current_step=$this->getStep($nextId);
  	    	$ret['idstep']=$nextId;
      		$ret['text']=$current_step->text;
      		$ret['name']=$current_step->name;
      		$ret['options']=$this->getOptions($current_step->id);
      		$ret['main']='false';
		}
			
			return $ret;
		
      
	
	
	}
    
    function _getResume(){    	
    	$flow=JRequest::getVar('idflow');    	
    	$step_var=JRequest::getVar('idstep');
    	$session =JFactory::getSession();
    	$prev_step=null;
    	if ($step_var=='-1'){
    		$step_var=$this->getLastStep();
    		$prev_step=$step_var->id;
    	} else if ($step_var=='0') {
    		$session->set('ad_'.$flow,array());
    		$prev_step=$step_var;
    	} else {
    		$step=$this->getStep($step_var);
    		if ($step!=null) $prev_step=$step->idprevstep;
    	}
    	
    	$data_session=array();    	    	
		$data_session=$session->get('ad_'.$flow);
		if ($prev_step!=null){ 
			$idoptionselected=JRequest::getInt('idoptionselected',0);
			$option_temp=$this->getOption($idoptionselected);
			
			if ($option_temp!=null)
      
      { 
      
      $data_session[$prev_step]=$this->getOption($idoptionselected);
	   	}
      	
      	
      	
			
			$session->set('ad_'.$flow,$data_session);
						
      	
      	
		}
		return $data_session;
    }
}


?>
