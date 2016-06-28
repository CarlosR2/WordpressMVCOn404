<?php



function a_custom_shortcode(){

	$html='';

	$some_var = ' Some var ';

	ob_start();
	include "views/a_shortcode_with_vars.php";
	$html .= ob_get_contents();
	ob_end_clean();

	return $html;	

}

add_shortcode('a_custom_shortcode', 'a_custom_shortcode'); 

?>