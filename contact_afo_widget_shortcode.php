<?php
function contact_widget_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
		  'id' => '',
     ), $atts ) );
    
	if(!$id)
	return;
	
	ob_start();
	$cfw = new contact_form_wid;
	if($title){
		echo '<h2>'.$title.'</h2>';
	}
	$cfw->contactWidBody(array('wid_contact_form' => $id));
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}
add_shortcode( 'contactwid', 'contact_widget_shortcode' );
?>