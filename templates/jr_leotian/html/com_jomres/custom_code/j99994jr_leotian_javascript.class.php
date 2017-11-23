<?php
/**
 * Core file
 * @author Vince Wooll <sales@jomres.net>
 * @version Jomres 4 
* @package Jomres
* @copyright	2005-2010 Vince Wooll
* Jomres (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly, however all images, css and javascript which are copyright Vince Wooll are not GPL licensed and are not freely distributable. 
**/

// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( '' );
// ################################################################

class j99994jr_leotian_javascript
	{
	function __construct()
		{
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
		$endrun_javascript_for_eval_by_live_scrolling = get_showtime('endrun_javascript_for_eval_by_live_scrolling');
		$endrun_javascript_for_eval_by_ajax_search = get_showtime('endrun_javascript_for_eval_by_ajax_search');

		$endrun_javascript = "jQuery(document).ready(function () {
			if (addon_animations_enable) {
				if (jQuery().waypoint) {
					jQuery('.appear').waypoint(function () {
					var t = jQuery(this);
					if (jQuery(window).width() < 767) {
						t.delay(jQuery(this).data(1));
						t.toggleClass(jQuery(this).data('animated') + ' animated').removeClass('appear');
					} else {
						t.delay(jQuery(this).data('start')).queue(function () {
							t.toggleClass(jQuery(this).data('animated') + ' animated').removeClass('appear');
						});
					}
				}, {
					offset: '85%',
					triggerOnce: true,
					});
				}
			}
		});";
		
		$endrun_javascript_for_eval_by_live_scrolling[] = $endrun_javascript;
		$endrun_javascript_for_eval_by_ajax_search[] = $endrun_javascript;
		
		set_showtime('endrun_javascript_for_eval_by_live_scrolling',$endrun_javascript_for_eval_by_live_scrolling);
		set_showtime('endrun_javascript_for_eval_by_ajax_search',$endrun_javascript_for_eval_by_ajax_search);
		}
	
	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}
	}

?>