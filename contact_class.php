<?php
class contact_class {
	function __construct() {
		add_action( 'init', array($this,'contact_form_post') );
		add_filter( 'manage_edit-contact_form_columns', array($this,'show_contact_sc') );
		add_action( 'manage_contact_form_posts_custom_column' , array($this,'display_contact_sc'), 10, 2 );
		add_filter( 'gettext', array($this,'button_text'), 10, 2 );
	}
	
	function contact_form_post() {
		$labels = array(
			'name'               => _x( 'Contact', 'post type general name', 'cfws' ),
			'singular_name'      => _x( 'Contact', 'post type singular name', 'cfws' ),
			'menu_name'          => _x( 'Contacts', 'admin menu', 'cfws' ),
			'name_admin_bar'     => _x( 'Contact', 'add new on admin bar', 'cfws' ),
			'add_new'            => _x( 'Add New', 'contact', 'cfws' ),
			'add_new_item'       => __( 'Add New Contact', 'cfws' ),
			'new_item'           => __( 'New Contact', 'cfws' ),
			'edit_item'          => __( 'Edit Contact', 'cfws' ),
			'view_item'          => __( 'View Contact', 'cfws' ),
			'all_items'          => __( 'All Contacts', 'cfws' ),
			'search_items'       => __( 'Search Contacts', 'cfws' ),
			'not_found'          => __( 'No contact forms found.', 'cfws' ),
			'not_found_in_trash' => __( 'No contact forms found in Trash.', 'cfws' )
		);
	
		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=contact_form',
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => NULL,
			'supports'           => array( 'title' )
		);
	
		register_post_type( 'contact_form', $args );
	}
	
	
	function show_contact_sc($columns) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = __('Title', 'cfws');
		$new_columns['sc'] = __('Shortcode');
		return $new_columns;
	}
	
	function display_contact_sc( $column, $post_id ){
		 switch ( $column ) {
			case 'sc' :
				echo '[contactwid id="'.$post_id.'" title="'.get_the_title($post_id).'" ajax="No"]';
				break;
		}
	}
	
	function button_text( $translation, $text ) {
		if ( 'contact_form' == get_post_type())
		if ( $text == 'Publish' )
		return 'Save';
	
		return $translation;
	}

}

class contact_meta_class {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'contact_form_fields' ) );
		add_action( 'add_meta_boxes', array( $this, 'contact_form_mail_body_fields' ) );
		add_action( 'add_meta_boxes', array( $this, 'contact_form_other_fields' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}
	
	public function contact_form_fields( $post_type ) {
            $post_types = array('contact_form');  
            if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'contact_form_fields'
					,__( 'Contact Form Fields', 'cfws' )
					,array( $this, 'render_contact_form_fields' )
					,$post_type
					,'advanced'
					,'high'
				);
            }
	}

	public function contact_form_mail_body_fields( $post_type ) {
			$post_types = array('contact_form');  
			if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'contact_form_mail_body_fields'
					,__( 'Mail Body', 'cfws' )
					,array( $this, 'render_contact_form_body' )
					,$post_type
					,'advanced'
					,'high'
				);
			}
	}
	
	public function contact_form_other_fields( $post_type ) {
			$post_types = array('contact_form');  
			if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'contact_form_other_fields'
					,__( 'From Settings', 'cfws' )
					,array( $this, 'render_contact_other_fields' )
					,$post_type
					,'side'
					,'high'
				);
			}
	}
	

	public function save( $post_id ) {
	
		if ( ! isset( $_POST['cfws_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['cfws_inner_custom_box_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'cfws_inner_custom_box' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}
		
		$field_names 			= $_REQUEST['field_names'];
		$field_labels 			= $_REQUEST['field_labels'];
		$field_types 			= $_REQUEST['field_types'];
		$field_descs 			= $_REQUEST['field_descs'];
		$field_requireds 		= $_REQUEST['field_requireds'];
		$field_values_array 	= $_REQUEST['field_values_array'];
		$extra_fields = array();
		
		if(is_array($field_names)){
			foreach($field_names as $key => $value){
				if($value){
					$extra_fields[] = array('field_name' => str_replace(" ","_",strtolower(trim($value))), 'field_label' => $field_labels[$key], 'field_type' => $field_types[$key], 'field_desc' => $field_descs[$key], 'field_required' => $field_requireds[$key], 'field_values' => $field_values_array[$key] );
				}
			}
		}
		update_post_meta( $post_id, '_contact_extra_fields', $extra_fields );
		
		$from_name = sanitize_text_field( $_POST['from_name'] );
		update_post_meta( $post_id, '_contact_from_name', $from_name );
		
		$from_mail = sanitize_text_field( $_POST['from_mail'] );
		update_post_meta( $post_id, '_contact_from_mail', $from_mail );
		
		$contact_subject = sanitize_text_field( $_POST['contact_subject'] );
		update_post_meta( $post_id, '_contact_subject', $contact_subject );
		
		$contact_to = sanitize_text_field( $_POST['contact_to'] );
		update_post_meta( $post_id, '_contact_to_mail', $contact_to );
		
		$contact_mail_body =  $_POST['contact_mail_body'] ;
		update_post_meta( $post_id, '_contact_mail_body', $contact_mail_body );
		
	}
	
	public function render_contact_form_fields( $post ) {
		global $cfc;
		wp_nonce_field( 'cfws_inner_custom_box', 'cfws_inner_custom_box_nonce' );
		$extra_fields = get_post_meta( $post->ID, '_contact_extra_fields', true );
		$cfc->LoadFieldJs();
		?>
		<table width="100%" border="0" style="border:1px dotted #999999; background-color:#FFFFFF;">
		   <tr>
			<td><?php echo $cfc->fieldList();?></td>
		  </tr>
		  <tr>
			<td>
			<div id="newFields"><?php $cfc->savedExtraFields($extra_fields);?></div>
			<div id="newFieldForm"></div>
			</td>
		  </tr>
		</table>
		<?php
	}
	
	public function render_contact_form_body( $post ) {
		global $cfc;
		wp_nonce_field( 'cfws_inner_custom_box', 'cfws_inner_custom_box_nonce' );
		$contact_mail_body = get_post_meta( $post->ID, '_contact_mail_body', true );
		?>
		<table width="100%" border="0" style="background-color:#FFFFFF;">
		  <tr>
			<td>
			<textarea name="contact_mail_body" style="width:100%; height:200px;"><?php echo $contact_mail_body;?></textarea></td>
		  </tr>
		  <tr>
			<td>HTML tags can be used in the mail body.</td>
		  </tr>
		</table>
		<?php
	}
	
	public function render_contact_other_fields( $post ) {
		global $cfc;
		wp_nonce_field( 'cfws_inner_custom_box', 'cfws_inner_custom_box_nonce' );
		$contact_subject = get_post_meta( $post->ID, '_contact_subject', true );
		$contact_to = get_post_meta( $post->ID, '_contact_to_mail', true );
		$from_name = get_post_meta( $post->ID, '_contact_from_name', true );
		$from_mail = get_post_meta( $post->ID, '_contact_from_mail', true );
		?>
		<table width="100%" border="0">
		  <tr>
			<td>Subject</td>
		  </tr>
		  <tr>
			<td><input type="text" name="contact_subject" value="<?php echo $contact_subject;?>" /></td>
		  </tr>
		  <tr>
			<td>To</td>
		  </tr>
		  <tr>
			<td><input type="text" name="contact_to" value="<?php echo $contact_to;?>" /></td>
		  </tr>
		   <tr>
			<td>From Name</td>
		  </tr>
		  <tr>
			<td><input type="text" name="from_name" value="<?php echo $from_name;?>" /></td>
		  </tr>
		  <tr>
			<td>From Mail</td>
		  </tr>
		  <tr>
			<td><input type="text" name="from_mail" value="<?php echo $from_mail;?>" /></td>
		  </tr>
		</table>
		<?php
	}
	
}


function call_contact_meta_class() {
    new contact_meta_class();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'call_contact_meta_class' );
    add_action( 'load-post-new.php', 'call_contact_meta_class' );
	new contact_class;
}