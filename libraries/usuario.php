<?php


class usuario{

	var $logged = false;
	var $id_wordpress = false;
	var $user = false;
	public function __construct(){
		$user = object_to_array(wp_get_current_user());
		if($user && $user['data']['ID']!=0){
			$this->logged = true;						
			$this->user = $user['data'];
			$this->id_wordpress = $user['data']['ID'];
		}		

	}

	public function is_logged(){
		return $this->logged;
	}
	public function user(){
		return $this->user;
	}
	public function nombre(){
		return $this->user['display_name'];				
	}
	public function correo(){
		return $this->user['user_email'];							
	}
	public function id_wordpress(){
		return $this->id_wordpress;	
	}

}


?>