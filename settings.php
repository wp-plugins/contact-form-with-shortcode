<?php
class contact_settings {
	public $plugin_folder_name = 'contact-form-with-shortcode';
	
	function __construct() {
		$this->load_settings();
	}
	
	function  contact_widget_afo_options () {
	global $wpdb;
	?>
	<table width="100%" border="0">
	  <tr>
		<td colspan="2"><h1>Usage</h1></td>
	  </tr>
	<tr>
		<td colspan="2">
			<table width="100%" border="0" style="background-color:#FFFFFF; padding:10px; border:1px dotted #999999;">
			  <tr>
				<td>
				<p>This is a contact form plugin. You can use widgets and shortcodes to display contact form in your theme. Unlimited number of dynamic fields can be created for contact forms.</p>
				<p> Unlimited number of contact forms can be created.</p>
				<p> Dynamic fields can be easily included in the mail template.</p>
		 </td>
			  </tr>
			  <tr>
				<td><h2>Pro Version Features</h2></td>
			  </tr> 
			  <tr>
				<td>The pro version comes with Newsletter Modules</td>
			  </tr> 
			</table>
		</td>
	  </tr>
	  
	</table>
	<?php }
	
	function contact_widget_afo_menu () {
		add_menu_page( 'Contact Form Usage', 'Contact Form Usage', 10, 'contact_form_settings', array( $this,'contact_widget_afo_options' ) );	
		add_submenu_page('contact_form_settings', 'Contact Forms', 'Contact Forms', 10 , 'edit.php?post_type=contact_form');
	}
	
		
	function load_settings(){
		add_action( 'admin_menu' , array( $this, 'contact_widget_afo_menu' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'contact_plugin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'contact_plugin_styles' ) );
	}
	
	public function contact_plugin_styles() {
		wp_enqueue_style( 'jquery-ui', plugins_url( $this->plugin_folder_name . '/css/jquery-ui.css' ) );
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery.ptTimeSelect', plugins_url( $this->plugin_folder_name . '/css/jquery.ptTimeSelect.js' ));
		wp_enqueue_style( 'jquery.ptTimeSelect', plugins_url( $this->plugin_folder_name . '/css/jquery.ptTimeSelect.css' ) );
		wp_enqueue_style( 'style_contact_widget', plugins_url( $this->plugin_folder_name . '/style_contact_widget.css' ) );
	}
}

new contact_settings;