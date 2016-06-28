<?php



class Model_usuario{


	var $connection_wp;
	var $connection_res;
	var $error;

	public function __construct(){

		
	}

	
 
	

	public function usuario_por_correo($correo){
		global $wpdb;
		$res = $wpdb->get_results("select * from wp_users where user_email = '$correo'",ARRAY_A);
		if(!$wpdb->num_rows) return false;		
		return $res;
	}

	public function usuario_por_id($id){
		global $wpdb;
		$res = $wpdb->get_results("select * from wp_users where ID = '$id'",ARRAY_A);
		if(!$wpdb->num_rows) return false;		
		return $res;
	}




}




?>