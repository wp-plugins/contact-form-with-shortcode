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
		$from_mail 			= get_post_meta( $contact->ID, '_contact_from_mail', true );
		$to_mail 			= get_post_meta( $contact->ID, '_contact_to_mail', true );
		$form_fields 		= get_post_meta( $contact->ID, '_contact_extra_fields', true );
		$body 				= get_post_meta( $contact->ID, '_contact_mail_body', true );
		$attachments = array();
		$att_msg = '';
		
		if(is_array($form_fields)){
			foreach($form_fields as $k => $v){
				if($v['field_type'] == 'file'){
					$a_file = $this->get_file_attachments($v['field_name']);
					if(is_array($a_file) and $a_file['file']){
						$attachments[] = $a_file['file'];
						$attachments_db[] = $a_file['url'];
					} else {
						$att_msg = __(' File not uploaded.');
					}
					
				} else {
					$body = str_replace('#'.$v['field_name'].'#', $_REQUEST[$v['field_name']], $body);
				}
			}
		}
		
		do_action( 'contact_store_db', $id, $attachments_db, $_REQUEST );
		
		$multiple_to_recipients = array(
			$to_mail
		);
		
		$headers[] = 'From: ' . $form_name . ' <' . $from_mail . '>';
		
		add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
		$bol = wp_mail( $multiple_to_recipients, $contact_subject ,$body, $headers, $attachments );
		remove_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
		
		if($bol){
			return array('msg' => __('Mail sent successfully.','cfs') . $att_msg, 'error' => 0);
		} else {
			return array('msg' => __('Mail not sent. Please try again later.','cfs') . $att_msg, 'error' => 1);
		}
	}
	
	public function get_file_attachments($name){
		global $sup_attachment_files_array;
		if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$uploadedfile = $_FILES[$name];
		$upload_overrides = array( 'test_form' => false );
		$arr_file_type = wp_check_filetype(basename($_FILES[$name]['name']));
		$uploaded_type = $arr_file_type['type'];
		if(in_array($uploaded_type, $sup_attachment_files_array)) {
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			if ( $movefile ) {
				return array('file' => $movefile['file'], 'url' => $movefile['url'] );
			}
		} else {
			return false;
		}
	}
	
	public function set_html_content_type() {
		return 'text/html';
	}
		
}