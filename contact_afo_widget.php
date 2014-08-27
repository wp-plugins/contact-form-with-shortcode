<?php

class contact_form_wid extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'contact_form_wid',
			'Contact Form Widget',
			array( 'description' => __( 'Contact form widget.', 'cfws' ), )
		);
		add_action( 'init', array( $this, 'contact_form_process' ) );
	 }

	public function widget( $args, $instance ) {
		extract( $args );
		
		$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $wid_title ) )
			echo $args['before_title'] . $wid_title . $args['after_title'];
			$this->contactWidBody($instance);
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		$instance['wid_contact_form'] = strip_tags( $new_instance['wid_contact_form'] );
		return $instance;
	}


	public function form( $instance ) {
		$wid_title = $instance[ 'wid_title' ];
		$wid_contact_form = $instance[ 'wid_contact_form' ];
		?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title:'); ?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('wid_title'); ?>" name="<?php echo $this->get_field_name('wid_title'); ?>" type="text" value="<?php echo $wid_title; ?>" />
		</p>
		<p><label for="<?php echo $this->get_field_id('wid_contact_form'); ?>"><?php _e('Form:'); ?> </label>
		<select id="<?php echo $this->get_field_id('wid_contact_form'); ?>" name="<?php echo $this->get_field_name('wid_contact_form'); ?>" class="widefat">
			<option value="">-</option>
			<?php $this->contactFormSelected($wid_contact_form);?>
		</select>
		</p>
		<?php 
	}
	
	public function start_session(){
		if(!session_id()){
			@session_start();
		}
	}
	
	public function current_page_url() {
		$pageURL = 'http';
		if( isset($_SERVER["HTTPS"]) ) {
			if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	public function contactWidBody($instance){
	global $cfc;
	$this->start_session();
	$this->error_message();
	?>
	<div id="cont_forms">
		<form name="con" action="" method="post">
		<ul class="contact_afo id-<?php echo $instance['wid_contact_form'];?>">
			<?php $cfc->contactFormFields($instance['wid_contact_form']); ?>
			<input type="hidden" name="con_form_id" value="<?php echo $instance['wid_contact_form'];?>" />
			<input type="hidden" name="con_form_process" value="do_process" />
			<li><div><input type="submit" name="submit" value="Submit" /></div></li>
		</ul>
		</form>
	</div>
	<?php
	}
	
	public function contactFormSelected($sel){
		$args = array( 'post_type' => 'contact_form', 'posts_per_page' => -1 );
		$c_forms = get_posts( $args );
		foreach ( $c_forms as $c_form ) : setup_postdata( $c_form );
			if($sel == $c_form->ID){
				echo '<option value="'.$c_form->ID.'"  selected="selected">'.$c_form->post_title.'</option>';
			} else {
				echo '<option value="'.$c_form->ID.'">'.$c_form->post_title.'</option>';
			}
		endforeach; 
		wp_reset_postdata();
	}
	
	public function error_message(){
		$this->start_session();
		if($_SESSION['contact_msg']){
			echo '<div class="'.$_SESSION['contact_msg_class'].'">'.$_SESSION['contact_msg'].'</div>';
			unset($_SESSION['contact_msg']);
			unset($_SESSION['contact_msg_class']);
		}
	}
	

	public function contact_form_process(){
		$this->start_session();
		if($_REQUEST['con_form_process'] == 'do_process'){
			$cmc = new contact_mail_class;
			$bol = $cmc->contact_mail_body($_REQUEST['con_form_id']);
			if($bol){
				$_SESSION['contact_msg'] = __('Mail sent successfully.','cfs');
				$_SESSION['contact_msg_class'] = 'cont_success';
			} else {
				$_SESSION['contact_msg'] = __('Mail not sent. Please try again later.','cfs');
				$_SESSION['contact_msg_class'] = 'cont_error';
			}
			wp_redirect( $this->current_page_url() );
			exit;
		}
	}
		
} 

add_action( 'widgets_init', create_function( '', 'register_widget( "contact_form_wid" );' ) );
?>