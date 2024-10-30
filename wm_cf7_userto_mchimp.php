<?php
/**
 * @link
 * @since             1.0.0
 * @package           contact-form-user-to-mailchimp-audience
 *
 * @wordpress-plugin
 * Plugin Name:       Contact Form user to Mailchimp Audience
 * Plugin URI:        
 * Description:       Transfer of users from Contact Form 7 to Mailchimp Audience.
 * Version:           1.0.0
 * Author:            superpuperlesha@gmail.com
 * Author URI:        
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       contact-form-user-to-mailchimp-audience
 */


// Exit if accessed directly
if(!defined('ABSPATH'))exit;


define('wm_cf7_userto_mchimp', '1.0.0');


function WMufn_USRTOMC_i18n_init() {
    $loaded = load_plugin_textdomain('contact-form-user-to-mailchimp-audience', false, dirname(__FILE__).'/languages/');

    if(!$loaded){
       $loaded = load_muplugin_textdomain('contact-form-user-to-mailchimp-audience', '/languages/');
    }

    if(!$loaded){
        $loaded = load_theme_textdomain('contact-form-user-to-mailchimp-audience', get_stylesheet_directory().'/languages/');
    }

    if(!$loaded){
        $locale = apply_filters('plugin_locale', function_exists('determine_locale') ?determine_locale() :get_locale(), 'contact-form-user-to-mailchimp-audience');
        $mofile = dirname( __FILE__ ).'/languages/contact-form-user-to-mailchimp-audience-'.$locale.'.mo';
        load_textdomain('contact-form-user-to-mailchimp-audience', $mofile);
    }
}
add_action('init','WMufn_USRTOMC_i18n_init');


include_once(dirname(__FILE__).'/class_wm_cf7_userto_mchimp.php');


//===add link to setup plugin===
function WMufn2_plugin_settings_link($links) { 
	$settings_link = '<a href="options-general.php?page='.esc_url( \WM_USRTOMC_ns\WM_USRTOMC::$suf ).'">'.__('Settings', 'contact-form-user-to-mailchimp-audience').'</a>'; 
	array_unshift($links, $settings_link); 
	return $links; 
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'WMufn2_plugin_settings_link' );


//===main admin menu===
add_action('admin_menu', 'WMufn_wmcf7tomch_menu');
function WMufn_wmcf7tomch_menu(){
	add_submenu_page('options-general.php', __('Mail Chimp', 'contact-form-user-to-mailchimp-audience'), __('Mail Chimp', 'contact-form-user-to-mailchimp-audience'), 'administrator', esc_url( \WM_USRTOMC_ns\WM_USRTOMC::$suf ), 'WMufn_submenuPlug');
}


function WMufn_submenuPlug(){
	include_once(dirname(__FILE__).'/admin_menu.php');
}


\WM_USRTOMC_ns\WM_USRTOMC::wm_cf7_start();
