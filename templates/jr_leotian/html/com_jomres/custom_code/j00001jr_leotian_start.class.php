<?php
// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( '' );
// ################################################################

class j00001jr_leotian_start {
	function __construct($componentArgs)
		{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return 
		$MiniComponents=jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
		
		$ePointFilepath = get_showtime('ePointFilepath');
		
		$showtime = jomres_getSingleton('showtime');
		$showtime->set_custom_path_for_template("featured_listings_asamodule_1.html",$ePointFilepath . '../../featured_listings_asamodule_1');
		$showtime->set_custom_path_for_template("selector.html",$ePointFilepath . '../../exchange_rate_conversion_selector');
		$showtime->set_custom_path_for_template("ajax_search_composite.html",$ePointFilepath . '../../ajax_search_composite');
		$showtime->set_custom_path_for_template("ajax_search_composite_multiselect.html",$ePointFilepath . '../../ajax_search_composite');
		$showtime->set_custom_path_for_template("regions.html",$ePointFilepath . '../../ajax_search_composite');
		$showtime->set_custom_path_for_template("towns.html",$ePointFilepath . '../../ajax_search_composite');
		$showtime->set_custom_path_for_template("propertylist_custom_property_fields.html",$ePointFilepath . '../../custom_property_fields');
		$showtime->set_custom_path_for_template("tabcontent_01_custom_property_fields.html",$ePointFilepath . '../../custom_property_fields');
		$showtime->set_custom_path_for_template("je_alternative_properties.html",$ePointFilepath . '../../je_alternative_properties');
		$showtime->set_custom_path_for_template("nearby_propertys.html",$ePointFilepath . '../../nearby_propertys');
		$showtime->set_custom_path_for_template("je_top_destinations.html",$ePointFilepath . '../../je_top_destinations');
		$showtime->set_custom_path_for_template("list_properties_with_maps.html",$ePointFilepath . '../../property_list_list_with_maps');
		$showtime->set_custom_path_for_template("list_properties_compact.html",$ePointFilepath . '../../property_list_compact');
		$showtime->set_custom_path_for_template("asamodule_resources.html",$ePointFilepath . '../../asamodule_resources');
		}

	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}
	}
