<?php
/*
 * @component List Manager 
 * @copyright Copyright (C) November 2017. 
 * @license GPL 3.0 
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details. 
 * See <http://www.gnu.org/licenses/>. 
 * More info www.moonsoft.es 
 * gestion@moonsoft.es
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );

function ListmanagerBuildRoute( &$query )
{
	   $segments = array();
	   if(isset($query['option'])){
           $segments[] = @$query['controller'];           
           $segments[] = @$query['task'];
           $segments[] = @$query['format'];
           $segments[] = @$query['id'];
           $segments[] = @$query['style'];
           unset($query['style']);
           unset($query['id']);
		   unset($query['format']);
		   unset($query['task']);
	   	   unset( $query['controller'] );
       }
       return $segments;
}

function ListmanagerParseRoute( $segments ){	
       $vars = array();
       $vars['controller'] = @$segments[0];
       $vars['task'] = @$segments[1];
       $vars['format'] = @$segments[2];
       $vars['id'] = @$segments[3];
       $vars['style'] = @$segments[4];      
       return $vars;
}
?>