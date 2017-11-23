<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Mainbody 1 columns, content only
 */
?>

<div class="container">
	<div class="row">
		<div id="t3-breadcrumbs" class="t3-breadcrumbs">
			<jdoc:include type="modules" name="<?php $this->_p('breadcrumbs') ?>" style="raw" />
		</div>
	</div>
</div>

<div id="t3-mainbody" class="t3-mainbody">
	<div class="container">
		<div class="row">
	
			<!-- MAIN CONTENT -->
			<div id="t3-content" class="t3-content col-md-12">
				<?php if($this->hasMessage()) : ?>
				<jdoc:include type="message" />
				<?php endif ?>
				
				<?php $this->loadBlock('spotlight-3') ?>
				
				<jdoc:include type="component" />
				
				<?php $this->loadBlock('spotlight-4') ?>
				
			</div>
			<!-- //MAIN CONTENT -->
	
		</div>
	</div>
</div>
