<?php


class model_var{

	/*
	
	BEFORE USING THIS: create table
	
	
	CREATE TABLE `var` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(200) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


	*/

	var $error;

	function __construct(){	

	}	

	public function get_error(){
		$err = $this->error;	
		$this->error = '';
		return $err;
	}

	public function aux(){
		return 'hola';	
	}

	public function set($id,$object){
		global $wpdb;
		$exists = $this->get($id);
		$object = serialize($object);
		$object = esc_sql($object);
		if($exists){
			//update	
			$res = $wpdb->query("update var set `value` = '$object' where `key` = '$id' ");					
		}else{
			//create
			$sql = "insert into tienda_var set `key` = '$id', `value` = '$object' ";
			$res =  $wpdb->query($sql);					
		}
		return $res;
	}

	public function get($id){
		global $wpdb;
		$sql = "select * from var where `key` = '$id'";
		$res = $wpdb->get_results($sql,ARRAY_A);				
		if(!$wpdb->num_rows){
			return false;
		}
		$res = $res[0];	
		$res = $res['value'];
		return unserialize($res);
	}

	public function delete($id){
		global $wpdb;
		$res = $wpdb->query("delete from var where `key` = '$id'");						
		return $res;
	}



}




?>