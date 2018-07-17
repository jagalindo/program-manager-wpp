<?php
/*
Plugin Name: Program manager plugin
Author: José  Ángel Galindo Duarte
Version: 1.0 branch javascript
*/

require('settings.php');

add_shortcode( 'write_full_program', 'write_full' );

add_action( 'wp_enqueue_scripts', 'my_plugin_register_scripts' );

wp_enqueue_script('media-upload'); 

//This creates a page for settings
if( is_admin() )
    $my_settings_page = new MySettingsPage();

function my_plugin_register_scripts(){
	wp_register_script('my-script',plugins_url( '/my-script.js', __FILE__ ), false, '1.0', 'all' );
     wp_register_style( 'my-style', plugins_url( '/my-style.css', __FILE__ ), false, '1.0', 'all' );
}

function write_full(){
	
	date_default_timezone_set('UTC');
	wp_enqueue_style( 'my-style' );
	wp_enqueue_script( 'my-script' );

	$plugin_options=get_option('program_manager_option');
	$program_data_url=explode(",",$plugin_options['program_data_url']);	
	echo "<div id=\"selection\">
  		<select id=\"day\">
    		<option selected=\"selected\" value=\"Lunes\">Lunes</option>
   			<option value=\"Martes\">Martes</option>
    		<option value=\"Miércoles\">Miércoles</option>
  		</select>
    
  		<select id=\"conf\">
    		<option selected=\"selected\" value=\"1\">PROLE</option>
    		<option value=\"2\">JISBD</option>
    		<option value=\"3\">JCIS</option>
  		</select>
  
  		<button onclick=\"listjson('".$program_data_url[0]."')\" type=\"button\">Filtrar</button>
  	</div>
  	<div id=\"program\"></div>";

}




