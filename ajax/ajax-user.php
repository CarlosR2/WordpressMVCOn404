<?php

add_action("wp_ajax_login", "login");
add_action("wp_ajax_nopriv_login", "login");

add_action("wp_ajax_desconectar", "logout");
add_action("wp_ajax_nopriv_desconectar", "logout");


add_action("wp_ajax_editar_datos_usuario_ajax", "editar_datos_usuario_ajax");
add_action("wp_ajax_nopriv_editar_datos_usuario_ajax", "editar_datos_usuario_ajax");

add_action("wp_ajax_request_recupera_pass_ajax", "request_recupera_pass_ajax");
add_action("wp_ajax_nopriv_request_recupera_pass_ajax", "request_recupera_pass_ajax");

add_action("wp_ajax_set_contra_ajax", "set_contra_ajax");
add_action("wp_ajax_nopriv_set_contra_ajax", "set_contra_ajax");

add_action("wp_ajax_register", "register");
add_action("wp_ajax_nopriv_register", "register");

add_action("wp_ajax_enviar_mensaje_usuario_ajax", "enviar_mensaje_usuario_ajax");
add_action("wp_ajax_nopriv_enviar_mensaje_usuario_ajax", "enviar_mensaje_usuario_ajax");


add_action("wp_ajax_esta_usuario_logeado", "esta_usuario_logeado");
add_action("wp_ajax_nopriv_esta_usuario_logeado", "esta_usuario_logeado");


add_action("wp_ajax_is_user_connected", "is_user_connected");
add_action("wp_ajax_nopriv_is_user_connected", "is_user_connected");






function is_user_connected(){

	$current_user = wp_get_current_user(); 

	$current_user =object_to_array($current_user);
	//	 print_r($current_user);
	if(!$current_user ||!$current_user['data']['ID']){
		ok(array('status'=>false,'info'=>'No user'));
	}
	$result = array();
	$result['user_login'] = $current_user['data']['user_login'];
	$result['user_email'] = $current_user['data']['user_email'];
	$result['admin'] = $current_user['data']['admin'];
	$result['display_name'] = $current_user['data']['display_name'];	 	 
	$result['ID'] = $current_user['data']['ID'];	 	 
	$result['id_wordpress'] = $current_user['data']['ID'];	 	  
	ok($result);
}




function set_contra_ajax(){
	global $wpdb;
	if(!isset($_POST['email'])) error('falta el email');
	if(!isset($_POST['id'])) error('falta el id');
	if(!isset($_POST['cod'])) error('falta el cod');
	if(!isset($_POST['pass'])) error('falta el pass');		
	$email = $_POST['email'];
	$id = $_POST['id'];
	$cod = $_POST['cod'];
	$pass = $_POST['pass'];
	if(!email_exists( $email )){
		//		error('no existe el correo: '.$email);	
	}

	$res = $wpdb->get_results("select user_pass from wp_users where ID = '$id' and user_email='$email'",ARRAY_A);
	if(!$wpdb->num_rows) error('Hubo un error, no existe el usuario '.$wpdb->last_error);  	
	$cod2 = $res[0];
	$cod2 = $cod2['user_pass'];
	$cod2 = substr($cod2,5,9); 
	if($cod!=$cod2) error("Error de código");

	//cambiar
	if(strlen($pass)<5) error('La contraseña requiere 5 carácteres');
	wp_set_password( $pass, $id );
	ok();		


}






function request_recupera_pass_ajax(){
	global $wpdb;

	if(!isset($_POST['correo'])) {
		error('Falta el correo');
	}
	$correo  = $_POST['correo'];
	if(!email_exists( $correo )) {
		error('El correo no existe en nuestra base de datos');
	}
	if(!get_user_by('email',$correo)) error('No existe el usuario');			
	$user_o = get_user_by('email',$correo);
	$user = object_to_array($user_o);	
	$id_wp = $user['data']['ID'];

	$res = $wpdb->get_results("select user_pass from wp_users where ID = '$id_wp'",ARRAY_A);
	if(!$wpdb->num_rows) error('Hubo un error, no existe el usuario '.$wpdb->last_error);  	
	$id= $id_wp;   	
	$cod = $res[0];
	//	echo $cod.'sss';
	$cod = $cod['user_pass'];
	//	echo $cod;
	$cod = substr($cod,5,9);    	
	$url_recupera = URL.'cuenta-usuario-acceso/?recupera=1&correo='.$correo.'&id='.$id.'&cod='.$cod;   	
	$asunto = 'Email de recuperación contraseña';   	
	$mensaje = 'Hola, <br /> <br />';
	$mensaje.= 'Has solicitado recuperar la contraseña. Para ello dirígete a la siguiente dirección donde podrás cambiarla:';
	$mensaje.= '<br /><br />';
	$mensaje.= '<a href="'.$url_recupera.'">'.$url_recupera.'</a>';
	$mensaje.= '<br /><br />';	
	$mensaje.= 'Y si tienes algún problema, no dudes en contactar con nosotros, un saludo';
	$mensaje.= '<br /><br />';				
	$mensaje.= '--<br />';					
		
	if(send_mail($asunto,$mensaje,$correo))	   	      
		ok();
	else error("No se pudo enviar el mensaje");	
}


function editar_datos_usuario_ajax(){
	$current_user = wp_get_current_user(); 
	if(!$current_user) error('No usuario');
	$current_user =object_to_array($current_user);		 	
	$result['user_login'] = $current_user['data']['user_login'];
	$result['user_email'] = $current_user['data']['user_email'];
	$result['admin'] = $current_user['data']['admin'];
	$result['ID'] = $current_user['data']['ID'];	 	 

	$usuario_model = load_model('usuario');


	$id = $id_wordpress = $result['ID'];

	if(isset($_POST['pass']) && strlen($_POST['pass'])>1){
		if(strlen($_POST['pass'])<4){
			error('La contraseña debe tener al menos 4 carácteres');
		}
		$pass = $_POST['pass'];
		wp_set_password( $pass, $id );		 
	}

	$correo = ''; $nombre = '';	
	if(!isset($_POST['nombre'])){
		//	error('falta nombre');
	}else{
		$nombre = $_POST['nombre'];		
	}


	if(!isset($_POST['correo']) || !isValidEmail($_POST['correo']) ){
		//	error('falta correo o es incorrecto');
	}else{
		$correo = $_POST['correo'];		
	}

	$data_usuario = [];	
	if($data_usuario) $usuario_model->usuario_datos_editar($id_wordpress,$data_usuario);

	if($nombre && $correo){
		wp_update_user( array ( 'ID' => $id, 'user_nicename' => $nombre,  'display_name' => $nombre, 'user_email'=>$correo ) ) ;	
	}
	ok();


}





function login(){

	//carlos2
	//hl8e3e93XSwA

	if(!isset($_POST['user'])) error('falta el user');
	if(!isset($_POST['pass'])) error('falta el pass');
	//escape'm
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	if(!$user || !$pass) error('Faltan los datos');

	if(strpos($user,'@')!==false){
		if(!get_user_by('email',$user)) error('No existe el usuario');			
		//		if(!is_object($user)) error('No existe el usuario');
		$user_o = get_user_by('email',$user);
		//		print_r($user);
		$user = $user_o->data->user_login;
		//		echo $user;
	}


	$creds = array();
	$creds['user_login'] = $user;
	$creds['user_password'] = $pass;
	$creds['remember'] = false;

	$user = wp_signon($creds,false);
	if ( is_wp_error($user) ){
		error($user->get_error_message());
	}

	$usuario_model = load_model('usuario');	
	$id_wordpress = $user->ID;
	

	$data = array('id_wordpress'=> $user->ID);
	ok($data);	
}

function logout(){

	wp_logout(); 	
	ok();
}



function register(){
	$userdata = array();

	if(!isset($_POST['nombre'])) error('falta el nombre');
	if(!isset($_POST['email'])) error('falta el email');
	if(!isset($_POST['login'])){
		$login = $_POST['email'];
		$login = explode('@',$login);
		$login = $login[0].'_'.rand(0,999);
		// error('falta el login');
	}else $login =$_POST['login'];

	if(!isset($_POST['pass'])) error('falta la contraseña');
	if(!isset($_POST['pass_again'])) error('falta la contraseña');
	$conectar = 0;
	if(isset($_POST['conectar'])) $conectar = true;
	$data_usuario = [];
	


	$nombre = $_POST['nombre'];
	$email = $_POST['email'];
	//	$login = $_POST['login'];
	$pass = $_POST['pass'];
	$pass_again = $_POST['pass_again'];				

	if($pass!=$pass_again){
		error("Las contraseñas no coinciden");
	}

	if(!isValidEmail($email)){
		error('El nombre no es válido');	
	}
	if(strlen($pass)<4){
		error('Contraseña demasiado corta');	
	}
	if(strlen($login)<4){
		error('Nombre usuario demasiado corto '.$login);	
	}




	$userdata['display_name']= $nombre;
	$userdata['user_login'] = $login;
	$userdata['user_pass'] = $pass;
	$userdata['user_email'] = $email;	


	$result = wp_insert_user( $userdata ); 
	if ( is_wp_error($result) ){
		error($result->get_error_message());
	}

	$user = get_userdatabylogin( $login );
	$user_id = $user->ID;	
	if($conectar){	
		wp_set_current_user( $user_id, $login );
		wp_set_auth_cookie( $user_id );					
	}

	if(sizeof($data_usuario)){
		
		$usuario_model = load_model('usuario');
		$id_wordpress = $user_id;
		$usuario_model->usuario_datos_editar($id_wordpress,$data_usuario);		
	}

	ok($result);
}



function esta_usuario_logeado(){
	global $usuario;
	if($usuario->is_logged()){
		if(isset($_GET['pedir_datos'])){

			$usuario_model = load_model('usuario');	
			$id_wordpress = $usuario->id_wordpress();
			$datos = $usuario_model->usuario_datos($id_wordpress);			
			ok($datos);

		}else{
			ok($usuario->id_wordpress);	
		}
	} 
	else error();

}


function enviar_mensaje_usuario_ajax(){
	$current_user = wp_get_current_user(); 
	if(!$current_user) error('No usuario');
	$current_user =object_to_array($current_user);		 		
	$login = $current_user['data']['user_login'];
	$correo_user = $current_user['data']['user_email'];
	$id = $current_user['data']['ID'];	



	if(!isset($_POST['mensaje']) ){
		error('falta mensaje ');
	} 
	if(!isset($_POST['asunto']) ){
		error('falta asunto ');
	} 		
	$asunto_user = $_POST['asunto'];
	$mensaje_user = $_POST['mensaje'];	


	
	$correo = CONTACT_EMAIL;



	$asunto = 'Nuevo mensaje desde el panel de control de usuario';
	$mensaje = '';
	$mensaje .= 'Mensaje de '.$login.' ('.$correo_user.')';		
	$mensaje .= '<br /><br />';		
	$mensaje .= 'Asunto: '.$asunto_user.'';						
	$mensaje .= '<br /><br />';		
	$mensaje .= 'Mensaje: '.$mensaje_user.'';						
	$res = send_mail($asunto,$mensaje,$correo);
	if($res) ok('enviado');
	error('error enviando el correo');

}









?>