<?php
class contact_settings {
	public $plugin_folder_name = 'contact-form-with-shortcode';
	
	function __construct() {
		$this->load_settings();
	}
	
	function  contact_widget_afo_options () {
	global $wpdb;
	$this->contact_wid_pro_add();
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
				<p>1. Create multiple contact forms for your site.</p>
				<p>2. Contact forms can be displayed using widgets and shortcodes in your theme. Unlimited number of dynamic fields can be created for contact forms.</p>
				<p>3. Dynamic fields can be easily included in the mail template.</p>
				<p>4. Files can be uploaded as attachment in contact forms. Supported file types are <strong>jpg, jpeg, png, gif, doc, docx, pdf</strong></p>
				
				<strong>Mail Body Example</strong>
				<p> For example you have created two contact form fields</p>
				<p> 1. "name"</p>
				<p> 2. "phone"</p> 
				<p> then in the email body you should use,
				<p> 
				<div style="border:1px solid #999999; padding:5px;">
					<strong>Contact us mail</strong><br /><br />
					Name: #name#<br />
					Phone No: #phone#
				</div>
				</p>
				<p> This way users Name and Phone both will be included in the e-mail body.</p>
		 </td>
			  </tr>
			  <tr>
				<td><h2>Pro Version Features</h2></td>
			  </tr> 
			  <tr>
				<td>
				<p>The PRO version of this plugin supports Newsletter subscription. You can get it from 
				<a href="http://aviplugins.com/contact-form-with-shortcode-pro/" target="_blank">here</a>.</p>
  <p>1. Create unlimited newsletter templats.</p>
  <p>2. Send recent posts in the newsletter email. There are different options available to choose from to create your desired newsletter   Email. Checkout the options <a href="http://aviplugins.com/contact-form-with-shortcode-pro/" target="_blank">here</a>.</p>
  <p>3. Send Custom post types in the Newsletter Email.</p>
  <p>4. Choose Theme for your newsletter. Downloadable themes are available to download for Newsletter Templates. <a href="http://aviplugins.com/contact-form-with-shortcode-pro/" target="_blank">Checkout available themes</a></p>
  <p>5. Send bulk Emails to the subscribers.</p>
				
				</td>
			  </tr> 
			</table>
		</td>
	  </tr>
	  
	</table>
	<?php }
	
	function contact_wid_pro_add(){ ?>
	<table width="98%" border="0" style="background-color:#FFFFD2; border:1px solid #E6DB55; padding:0px 0px 0px 10px; margin:2px;">
  <tr>
    <td><p>The PRO version of this plugin supports <strong>Newsletter Subscription</strong> and additional contact form settings. with option to choose different newsletter <strong>Themes</strong>. You can get it <a href="http://aviplugins.com/contact-form-with-shortcode-pro/" target="_blank">here</a> in <strong>USD 2.00</strong></p></td>
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