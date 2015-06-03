<?php
/*
Plugin Name: Contact Form With Shortcode
Plugin URI: http://aviplugins.com/
Description: This is a contact form plugin. You can use widgets and shortcodes to display contact form in your theme. Unlimited number of dynamic fields can me created for contact froms.
Version: 2.2.1
Author: avimegladon
Author URI: http://avifoujdar.wordpress.com/
*/

/**
	  |||||   
	<(`0_0`)> 	
	()(afo)()
	  ()-()
**/

include_once dirname( __FILE__ ) . '/settings.php';
include_once dirname( __FILE__ ) . '/fields_class.php';
include_once dirname( __FILE__ ) . '/contact_class.php';
include_once dirname( __FILE__ ) . '/contact_afo_widget.php';
include_once dirname( __FILE__ ) . '/contact_afo_widget_shortcode.php';
include_once dirname( __FILE__ ) . '/contact_mail_class.php';

$sup_attachment_files_array = array( 
'image/jpeg',  
'image/png', 
'image/gif', 
'application/msword', 
'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
'application/pdf', 
);