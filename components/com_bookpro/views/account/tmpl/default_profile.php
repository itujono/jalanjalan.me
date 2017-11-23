
<?php
defined('_JEXEC') or die('Restricted access');
$reseller=1;
?>
 <?php 
		$layout = new JLayoutFile('menuaccount', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
		$html = $layout->render($this->customer);
		echo $html;
	?>
      

<div class="row-fluid">
  <div class="row-fluid">
		<form action="<?php echo  JRoute::_('index.php'); ?>" method="post" class='form-horizontal'
			name="registerform">
		 
		 	<div class="span6">
			<?php echo $this->loadTemplate('customer') ?>
			
				
			 <div class="control-group">
		    <div class="controls">
		    		<input
				type="submit" class="btn btn-primary" name="submit" id="submit"	value="<?php echo JText::_('COM_BOOKPRO_SAVE_CHANGE');?>" onclick="return validation()"
				/>
		    </div>
		    </div>
			 
			<input type="hidden" name="option" value="com_bookpro" />  <input
				type="hidden" name="return" value="<?php echo $this->return?>" /> <input
				type="hidden" name="controller" value="customer" /> <input
				type="hidden" name="task" value="save" />
		
			<?php echo JHTML::_( 'form.token'); ?>
			
			</div>
			
			
			<div class="span6">
			<?php if($reseller){
				
				$db=JFactory::getDbo();
				$query=$db->getQuery(true);
				$query->select('sum(total)')->from('#__bookpro_orders')->where('created_by='.$this->account->id)
				->where('order_status='.$db->quote('CONFIRMED'));
				$db->setQuery($query);
				$outstanding=$db->loadResult();
				
			?>
					<legend> <?php echo JText::_('COM_BOOKPRO_CREDIT')?></legend>
		<dl class="dl-horizontal">
        <dt> <?php echo JText::_('COM_BOOKPRO_CREDIT_LIMIT') ?></dt>
        <dd><?php echo CurrencyHelper::formatprice($this->account->credit) ?></dd>
        <dt><?php echo JText::_('COM_BOOKPRO_CREDIT_OUTSTANDING') ?></dt>
        <dd><?php echo CurrencyHelper::formatprice($outstanding) ?></dd>
        <dt><?php echo JText::_('COM_BOOKPRO_CREDIT_AVAILABLE') ?></dt>
        <dd><?php echo CurrencyHelper::formatprice($this->account->credit-$outstanding) ?></dd>
        </dl>
				
				<?php 
			}?>
			
			</div>
			
			
		
		</form>
		</div>
		<div class="row-fluid">
		
		<form action="<?php echo  JRoute::_('index.php'); ?>" method="post" name="registerform" class="form-validate">
	
	  	<div class="form-horizontal">
		<fieldset>
		<legend> <?php echo JText::_('COM_BOOKPRO_PASSWORD')?></legend>	  	
		  	<div class="control-group">
				<label class="control-label" for="username"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_USERNAME' ); ?>
				</label>
				<div class="controls">
					
					<input class="inputbox required" type="text" name="username" disabled   size="30" maxlength="50" value="<?php echo $this->account->juser->username?>" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_USERNAME' ); ?>" />
		      
				</div>
			</div>
		    
		    <div class="control-group">
				<label class="control-label" for="password"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_PASSWORD' ); ?>
				</label>
		    	
				<div class="controls">
					<input class="inputbox required" type="password" name="password" id="pass" size="30" maxlength="50" value="" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_PASSWORD' ); ?>" />
				</div>
		    </div>
		    
			
		    <div class="control-group">
		    <div class="controls">
		    	<input type="submit" class="btn btn-primary" name="submit" value="<?php echo JText::_('COM_BOOKPRO_SAVE_CHANGE');?>" />
		    </div>
		    </div>
	    </fieldset>
	    </div>
	
	
	  <input type="hidden" name="option" value="com_bookpro" />
	   
	  <input type="hidden" name="controller" value="customer"/>
	
	  <input type="hidden" name="task" value="changepassword" />
	
	  <?php echo JHTML::_( 'form.token' ); ?>
	
	</form>
		
		</div>
		
	</div>	
