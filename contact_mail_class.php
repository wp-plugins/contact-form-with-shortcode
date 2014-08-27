<?php
class contact_mail_class {
	function __construct() {}
	
	public function contact_mail_body($id = ''){
		if(!$id){
			return;
		}
		$contact 			= get_post($id); 
		$contact_subject 	= get_post_meta( $contact->ID, '_contact_subject', true );
		$form_name 			= get_post_meta( $contact->ID, '_contact_from_name', true );
		$form_mail 			= get_post_meta( $contact->ID, '_contact_from_mail', true );
		$to_mail 			= get_post_meta( $contact->ID, '_contact_to_mail', true );
		$form_fields 		= get_post_meta( $contact->ID, '_contact_extra_fields', true );
		$body 				= get_post_meta( $contact->ID, '_contact_mail_body', true );
		
		if(is_array($form_fields)){
			foreach($form_fields as $k => $v){
				$body = str_replace('#'.$v['field_name'].'#', $_REQUEST[$v['field_name']], $body);
			}
		}
		
		$multiple_to_recipients = array(
			$to_mail
		);
		
		$headers[] = 'From: ' . $form_name . ' <' . $form_mail . '>';
		
		add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
		$bol = wp_mail( $multiple_to_recipients, $contact_subject ,$body, $headers );
		remove_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
		return $bol;
	}
	
	public function set_html_content_type() {
		return 'text/html';
	}
		
}