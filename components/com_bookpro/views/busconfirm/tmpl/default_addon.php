<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );


?>


<?php if(count($this->addons)>0){?>
<h2 class="block_head"><span><?php echo JText::_('COM_BOOKPRO_ADDONS_INFO')?></span>  </h2>
<table class="table table-condensed">
			<thead>
				<tr >
					<td><?php echo JText::_('COM_BOOKPRO_ADDON_NAME')?>
					
					</td>	
					
					<td><?php echo JText::_('COM_BOOKPRO_QUANTITY')?>
					
					</td>		
					</tr>
					</thead>
					
				
				<tbody>
				<?php foreach ( $this->addons as $addon ) {		?>
				
		<tr><td>
		<input type="hidden" name="addon[id][]"	value="<?php echo $addon->id; ?>"  /> <span
		class="item-title">	
				<?php echo $addon->title; ?>								  </span>
					                    		
		</td>
		<td>
		
		<?php
		
		echo JText::sprintf ( 'COM_BOOKPRO_PRICE_TXT', CurrencyHelper::displayPrice ( $addon->price, 0 ) );
		?>
		<?php 
		
		echo JHtmlSelect::integerlist ( 0, 10, 1, 'addon[qty][]', 'class="input-mini"' );
		?>
		</td></tr>
		<?php 
		 }?>
			          	</tbody>
					</table>
<?php }?>
