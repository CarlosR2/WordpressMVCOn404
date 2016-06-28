<?php

//sfds

class notification{

	var $email;

	function __construct(){
		require_once(DIR_PLUGIN.'libraries/email.php');
		$this->email = new email();
	}

	private function template_content($template,$vars){

		//lets tests with
		extract($vars);
		ob_start();
		include DIR_CORREOS.$template;
		$email = ob_get_contents();
		ob_end_clean();

		return $email;		
	}


	#############################################


	function notification($correo, $asunto, $mensaje){		
		$return = $this->email->enviar_correo($asunto,$mensaje,$correo);				
		return $return;		
	}
	

	 




	function recuperar_contrasenya($correo,$url_recupera){

		$asunto = 'Email de recuperación contraseña ';   	
		$mensaje = 'Hola, <br /> <br />';
		$mensaje.= 'Has solicitado recuperar la contraseña. Para ello dirígete a la siguiente dirección donde podrás cambiarla:';
		$mensaje.= '<br /><br />';
		$mensaje.= '<a href="'.$url_recupera.'">'.$url_recupera.'</a>';
		$mensaje.= '<br /><br />';	
		$mensaje.= 'Y si tienes algún problema, no dudes en contactar con nosotros, un saludo';
		$mensaje.= '<br /><br />';				
		$mensaje.= '--<br />';							
		$return = $this->email->enviar_correo($asunto,$mensaje,$correo);		
		return $return;	
	}





}
?>