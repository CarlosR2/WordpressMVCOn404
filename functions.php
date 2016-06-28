<?php


function plugin_load_view($file,$data){	
	global $wp_query;
	global $tienda_errors;
	foreach($data as $k=>$v){
		$wp_query->query_vars[$k] = $v;
	}
	$ext = end(explode('.',$file));
	if($ext == 'php') $url = __DIR__.'/views/'.$file;
	else $url = __DIR__.'/views/'.$file.'.php';

	load_template($url);

}

function tienda_load_view($file,$data){	
	global $wp_query;
	global $tienda_errors;
	foreach($data as $k=>$v){
		$wp_query->query_vars[$k] = $v;
	}
	$template = locate_template($file);			        					
	if ($template != '') {
		load_template($template);
	}  		
}

function load_model($name){
	$dir = DIR_PLUGIN.'models/model_'.$name.'.php';
	if(!file_exists($dir)){
		//die('no file: '.$dir);
		return false;	
	} 
	require_once($dir);
	$name_model = 'model_'.$name;
	$model = new $name_model();
	return $model;
}

function load_library($name){
	if(!file_exists(DIR_PLUGIN.'libraries/'.$name.'.php')) return false;	
	require_once(DIR_PLUGIN.'libraries/'.$name.'.php');
	$name_model = 'model_'.$name;
	$model = new $name();
	return $model;
}

function clean_title($title){
	return '';
}



function send_mail($asunto,$mensaje,$correo) {

	require_once(DIR_PLUGIN.'libraries/email.php');
	$mailer = new email();
	$res = $mailer->enviar_correo($asunto,$mensaje,$correo);
	return $res;
}



function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	} 
	return $pageURL;
}



function replace_accents($string) 
{ 
	return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string); 
} 



function get_current_url(){
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";	
	return $actual_link;
}

function isValidEmail($email){
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}



function object_to_array($data)
{
	if (is_array($data) || is_object($data))
	{
		$result = array();
		foreach ($data as $key => $value)
		{
			$result[$key] = object_to_array($value);
		}
		return $result;
	}
	return $data;
}


function ok($info = ''){ // for OK ajax calls 
	if($info){
		echo json_encode(array('status'=>'true','info'=>$info))	;
	}else{
		echo json_encode(array('status'=>'true'))	;		
	}
	die();
}

function error($info = ''){ // for KO ajax calls 
	if($info){
		echo json_encode(array('status'=>'false','info'=>$info));
	}else{
		echo json_encode(array('status'=>'false'));		
	}
	die();
}




?>