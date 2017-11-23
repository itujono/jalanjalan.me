<?php
/**
 * @version     1.0.0
 * @package     com_bookpro
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ngo <quannv@gmail.com> - http://joombooking.com
 */
// no direct access
defined ( '_JEXEC' ) or die ();

JHtml::addIncludePath ( JPATH_COMPONENT . '/helpers/html' );
JHtml::_ ( 'behavior.formvalidation' );
JHtml::_ ( 'behavior.keepalive' );

?>


<form 	action="<?php echo JRoute::_('index.php?option=com_bookpro&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post"  name="adminForm"
	id="adminForm" class="form-validate">

        <div class="row-fluid">
			<div class="span10 form-horizontal">

				
					<?php echo $this->form->renderField('title')?>
				<?php echo $this->form->renderField('message')?>
					
		<?php echo $this->form->renderField('state')?>
					
				

			</div>
		</div>
     

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

</form>