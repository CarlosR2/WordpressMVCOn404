<?php



class email{

	var $sender_server;
	var $log_model;
	var $error_mail = '';


	function __construct(){			
		$this->sender_server = 'localhost';
	}




	public function enviar_correo($asunto,$mensaje,$correo,$notificacion='',$servidor=''){
		if($servidor == 'localhost') $this->sender_server = 'localhost';
		else {
			//default
			$this->sender_server = 'localhost';
		}

		$data = Array(
			'destinatario'=>$correo,
			'notificacion'=>$notificacion,
			'mensaje'=>$mensaje,
			'asunto'=>$asunto,
			'server'=>$this->sender_server,
			'retorno'=>''	
		);
		//	$id_log = $this->guardar_log($data);						
		if($this->sender_server =='localhost'){
			$res = $this->enviar_correo_phpmailer($asunto,$mensaje,$correo);
		}/*else if($this->sender_server =='mandrill'){
				// $res = $this->enviar_correo_mandrill($correo, $asunto,$mensaje);			
				return false;
		}*/else{
			$res = $this->enviar_correo_phpmailer($asunto,$mensaje,$correo);			
		}		
		if($res) $enviado = 1;
		else $enviado = 0;
		$error = 0;
		if(!$res) $error = $this->error_mail;
		$data = Array('error'=>$error,'enviado'=>	$enviado);
		//$this->guardar_log($data,$id_log); // result
		return $res;
	}






	function enviar_correo_phpmailer($asunto,$mensaje,$correo) {

		// i know its silly to do this when it comes with wordpress... but was done long time ago


		require_once(DIR_PLUGIN."libraries/mailer/class.phpmailer.php");		
		$mailer = new PHPMailer(); 
		$mailer->CharSet = 'UTF-8';
		$mailer->IsSMTP();
		$mailer->Mailer = "smtp";
		$mailer->Host = EMAIL_SMTP;//"mail.empresa.com";
		$mailer->Port = 25;
		$mailer->SMTPAuth = true;
		$mailer->SMTPKeepAlive = true;
		$mailer->Username = EMAIL_USER;
		$mailer->Password = EMAIL_PASS;
		$mailer->From = EMAIL_FROM;  // This HAVE TO be your gmail adress

		$mailer->FromName = EMAIL_FROM; // This is the from name in the email, you can put anything you like here
		//$mailer->AddBCC('admin@example.com');  // This HAVE TO be your gmail adress
		$mailer->IsHTML(true);
		$mailer->AddAddress($correo);
		$mailer->Body = $mensaje;
		$mailer->Subject = $asunto;
		// This is where you put the email adress of the person you want to mail

		if(!$mailer->Send()) {
			return false;
		}
		else {
			return true;
		}



	}


}


?>