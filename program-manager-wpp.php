<?php
/*
Plugin Name: Program manager plugin
Author: José  Ángel Galindo Duarte
Version: 1.0 branch javascript
*/

require('metamodel.php');
require('readers.php');
require('writers.php');
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

function write_glance(){
	
	date_default_timezone_set('UTC');
	wp_enqueue_style( 'my-style' );
	wp_enqueue_script( 'my-script' );

	$plugin_options=get_option('program_manager_option');
	$pre_days=explode(",",$plugin_options['pre_days']);	
	$main_days=explode(",",$plugin_options['main_days']);
	$pre_xlsx=str_replace(get_option('siteurl'),".",$plugin_options['pre_days_file']);	
	$main_xlsx=str_replace(get_option('siteurl'),'.',$plugin_options['main_days_file']);		
	
	$reader = new XLSXProgramReader($pre_xlsx,$main_xlsx);
	$program=$reader->parseProgram($pre_days,$main_days);
	$writer=new GlanceProgramWriter($program); 
	$writer->write();
}


function write_full(){
	
	date_default_timezone_set('UTC');
	wp_enqueue_style( 'my-style' );
	wp_enqueue_script( 'my-script' );

	$plugin_options=get_option('program_manager_option');
	$program_data_url=explode(",",$plugin_options['program_data_url']);	
	
	
}




