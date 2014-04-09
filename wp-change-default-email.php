<?php
/*
Plugin Name: WP Change Default Email
Plugin URI: http://www.techeach.com/wordpress-plugins/
Description: WP Change Default Email allows you to change the default from email and name.
Version: 0.2
License: GPLv2
Author: Vijay Sharma
Author URI: http://www.techeach.com/
*/


$wcdeOptions = get_option('wp_change_default_email_options');

function wp_change_default_email() {
        add_filter('wp_mail_from', 'wp_change_default_email_change_from_email');
		add_filter('wp_mail_from_name', 'wp_change_default_email_change_from_name');
}
add_filter('init','wp_change_default_email');

function wp_change_default_email_change_from_email($email_old) { 
	global $wcdeOptions;
	if ( isset($wcdeOptions['from']) && is_email($wcdeOptions['from'])) {
	    return $wcdeOptions['from'];	
	} else {
	    return $email_old;	
	}
}

function wp_change_default_email_change_from_name($fromname) {
    global $wcdeOptions;
    if ( !empty($wcdeOptions['fromname'])) {
	 	return $wcdeOptions['fromname'];
	} else {
		return $fromname;
	}
}

function wp_change_default_email_activate(){
	$wcdeOptions = array();
	$wcdeOptions["from"] = "";
	$wcdeOptions["fromname"] = "";
	$wcdeOptions["deactivate"] = "";
	add_option("wp_change_default_email_options",$wcdeOptions);
}
register_activation_hook( __FILE__ , 'wp_change_default_email_activate' );
	
if($wcdeOptions["deactivate"]=="yes"){
	register_deactivation_hook( __FILE__ , create_function('','delete_option("wp_change_default_email_options");') );
}

function wp_change_default_email_settings_link($action_links,$plugin_file){
	if($plugin_file==plugin_basename(__FILE__)){
		$ws_settings_link = '<a href="options-general.php?page=' . dirname(plugin_basename(__FILE__)) . '/wp_change_default_email_admin.php">' . __("Settings") . '</a>';
		array_unshift($action_links,$ws_settings_link);
	}
	return $action_links;
}
add_filter('plugin_action_links','wp_change_default_email_settings_link',10,2);

//Load Translations
function wcde_load_lang()
{
    load_plugin_textdomain('wp-change-default-email', false, basename( dirname( __FILE__ ) ) . '/lang' );
}
add_action('init', 'wcde_load_lang');

//If this is admin load the settings page in Admin
if(is_admin()) require_once('wp_change_default_email_admin.php');
