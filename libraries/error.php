<?php

class plugin_error{

	var $errors;
	public function __construct(){
		$this->errors = Array();
		//what if its ajax??
		if(!isset($_SESSION['perrors'])) $_SESSION['perrors'] = Array();
		if(sizeof($_SESSION['perrors'])){
			foreach($_SESSION['perrors'] as $e){
				$this->errors[] = $e;	
			}	
			$_SESSION['perrors'] = Array(); // and empty
		}
	}
	
	function add_error($str){
		$this->errors[] = $str;
	}
	function add_perror($str){
		//persistent error. For the next request
		$_SESSION['perrors'][] = $str;
	}

	function errors(){
		return $this->errors;
	}	
}?>