<?php

/*
   Plugin Name: MVC Controller on 404
   Plugin URI: http://horadeempezar.com
   Description: Tienda:  MVC Controller on 404
   Version: 1.3
   Author: Carlos
   Author URI: http://horadeempezar.com
   License: GPL2
   */

//session_start();



include_once('constants.php');
include_once('functions.php');

require_once(DIR.'wp-includes/pluggable.php');

include_once('libraries/error.php');
include_once('libraries/email.php');
include_once('libraries/cesta.php');
include_once('libraries/cache_imagen.php');
include_once('libraries/notification.php');
include_once('libraries/usuario.php');


include_once('ajax/ajax-carrito.php');
include_once('ajax/ajax-user.php');

include_once('shortcodes.php');





global $usuario;
$usuario = new usuario();










// This is optional but recommended for this plugin. We deactive the guess redrection // https://wordpress.org/support/topic/301-redirect-instead-of-404-when-url-is-a-prefix-of-a-post-or-page-name
add_filter('redirect_canonical', 'no_redirect_on_404');
function no_redirect_on_404($redirect_url) {
	if (is_404()) {
		return false;
	}
	return $redirect_url;
}



// and here the real meat. Capture 404 paths and if we create an endpoint in the controller, we'll capture it
add_filter('template_redirect', 'my_404_override' );
function my_404_override() {
	global $subdominio;
	global $wp_query;
	if ($wp_query->is_404) {    		
		//
		$controller__ = new _404_controller();      	   	
		$controller__->_execute_current_url();  		

	}

}


////////// NOW THE REAL PLUGIN



global $tienda_errors;
//$tienda_errors = new plugin_error();





class _404_controller{


	//ejecutado en 404


	var $is_logged;
	
	var $data;
	var $titulo_pagina;
	var $segment_url;
	var $errors;

	public function __construct(){
		
		$this->data = Array();
		$this->errors = new plugin_error();
		
		$this->is_logged = false;
		$this->titulo_pagina = '';
		$user = object_to_array(wp_get_current_user());
		
		if($user && $user['data']['ID']!=0){
			$this->is_logged = true;
			$id_wordpress = $user['data']['ID'];
			// impersonation
			if($id_wordpress == 1){
				//						$id_wordpress = 30;
			}
			$this->data['user'] = $cliente;					
			$this->data['display_name'] = $user['data']['display_name'];
			$this->data['email_user'] = $user['data']['email_user'];					
		}					
		
		$this->data['mensaje'] = Array();				
		$this->data['id_wordpress'] = $id_wordpress;
		$this->data['is_logged'] = $this->is_logged;											
	}




	public function _execute_current_url(){
		global $wp_query;
		$url = curPageURL();

		//$url = parse_url($url, PHP_URL_PATH);		


		$url = str_replace(URL,'',$url);
		$url = str_replace('http://','',$url);
		$url = str_replace('https://','',$url);

		$url = explode('/',$url);
		$this->segment_url = $url;


		$first = $this->segment_url[0];
		$first = str_replace('-','_',$first);;
		$second = isset($this->segment_url[1]) ? $this->segment_url[1]: false;		    	
		$second = str_replace('-','_',$second);;


		// ROUTER

		if(isset($second) && $second){			
			$aux = $first.'_'.$second;				  	     
			if(method_exists($this,$aux)){
				status_header( 200 );	
				//add_filter( 'wp_title', 'clean_title',10,2);	
				$wp_query->is_404 = false;
				$this->$aux();
				die;			    		 
			}else if(method_exists($this,$first)){
				status_header( 200 );			
				//add_filter( 'wp_title', 'clean_title',10,2);	
				$wp_query->is_404 = false;
				$this->$first();
				die;			    		 			    					    		
			}else $this->_404();			    	
		}else{
			if(method_exists($this,$first)){	
				status_header(200 );			
				//add_filter( 'wp_title', 'clean_title',10,2);	
				$wp_query->is_404 = false;			    				    
				$this->$first();
				die;			    		 
			}
			else $this->_404();			    				
		}
	}




	//////////////// CREATE URL's


	public function this_is_an_example(){
		$data =$this->data;
		plugin_load_view('an_example.php',$data);		
	}
	

	public function cuenta_usuario_acceso(){	
		//another example
		$data = $this->data;

		$data['code_recupera'] = false;
		if(isset($_GET['recupera']) && $_GET['recupera']==1){
			$code_recupera = -1;
			$data['code_recupera'] = $code_recupera;
			$correo = isset($_GET['correo'])? $_GET['correo']: '';
			$id = isset($_GET['id'])?  $_GET['id']: '';	
			$cod = isset($_GET['cod'])?  $_GET['cod']: '';			

			if($correo && $id && $cod){					
				//comprobemos que existe


				$usuario_model = load_model('usuario');
				$user = $usuario_model->usuario_por_correo($correo);
				if(!$user || ($user['ID'] != $id)){
					die('error comprobando el usuario');	
				} 

				$cod_ = $user['user_pass'];
				$cod_ = substr($cod_,5,9); 
				if($cod==$cod_){
					$code_recupera = 1;
					$id_wp = $id;
					$data['code_recupera'] = 1;
					$data['correo'] = $correo;
					$data['id_wp'] = $id_wp;
					$data['cod'] = $cod;																										 								 
				}


			}
		}

		if($this->is_logged) header('location: cuenta-usuario');	 	
		plugin_load_view('plugin_cuenta_usuario_login.php',$data);		
	}



	public function cuenta_usuario(){
		//and another example
		global $wpdb;

		$data = $this->data;				
		if(!$this->is_logged){
			header('location: cuenta-usuario-acceso');	 		
		} 
		$id_wordpress = $data['id_wordpress'];
		$impersonation = false;
		if($id_wordpress==1 && $impersonation){
			if($id_wordpress == 1){
				echo 'impersonation';
				$id_wordpress = 597;	
			}
		}
		$usuario_model = load_model('usuario');		
				
		plugin_load_view('plugin_cuenta_usuario.php',$data);		
	}



	






	public function _404(){
		// Do nothing, wordpress will execute 404
	}



}



?>