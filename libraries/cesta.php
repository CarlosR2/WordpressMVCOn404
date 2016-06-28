<?php

class cesta {
			
			var $cesta;
			
			function __construct(){	
				if(isset($_SESSION['cesta'])){

					$cesta = $_SESSION['cesta'];		

				}else{
					$cesta = $this->restaurar();
				}
				$this->cesta = $cesta;				
			}
			
			function crear_cesta(){
						$id = uniqid();
						$cesta = Array();
						$cesta['id'] = 'cesta_'.$id;
						$cesta['lineas'] = Array();
						$this->cesta = $cesta;
						return $this->cesta;				
			}
			
			function restaurar(){
					$cesta = false;				
					$cesta_id  = $this->get_cookie_id_cesta();
					if($cesta_id){
							//si está en cookies
							$var_model = load_model('var');				
							$id = $cesta_id;
							// serializar en bd
							$res = $var_model->get($id);
							if($res){
								 $cesta = $res;
							}
					}										
					if(!$cesta){
						//la creamos
						//no cesta previa. Creamos
						$cesta = $this->crear_cesta();
					}					
					$this->cesta = $cesta;					
					$_SESSION['cesta'] = $this->cesta;						
					$this->guardar();					
					return $cesta;				
			}
			
			function guardar(){	
				$_SESSION['cesta'] = $this->cesta;			
				$var_model = load_model('var');				
				if(!$var_model) return false;
				// serializar en bd
				$res = $var_model->set($this->cesta['id'],$this->cesta);
				if(!$res){					
					 return false;
				}
				$res = $this->set_cookie_id_cesta($this->cesta['id']);			
				if(!$res) return false;
			}
			
			function borrar_cesta(){								
				$_SESSION['cesta'] = Array();
				$var_model = load_model('var');				
				if($this->cesta['id']) $res = $var_model->delete($this->cesta['id']);				
				$this->delete_cookie_id_cesta();	
				$this->cesta = Array();
				return;
			}
			
			function get_cookie_id_cesta(){
				if(isset($_COOKIE['cesta_id'])) return $_COOKIE['cesta_id'];
				else return false;
			} 
			
			function set_cookie_id_cesta($id){		
				$res = setcookie('cesta_id',$id,(time() + (86400 * 7)),'/'); // 1 semana
				if(!$res){
						return false;
				}
				return $res;
			}
			
			function delete_cookie_id_cesta(){
				if(isset($_COOKIE['cesta_id'])) unset($_COOKIE['cesta_id']);
				setcookie('cesta_id',$id,-10); // 1 semana				
			}
			 
			
			
			// cookies
			
			
			function cesta(){				
				return $this->cesta;				
			}

			
			
			function anyadir_cesta($id_linea = false, $data,$cantidad=1){			
				$id_linea = trim(rtrim($id_linea));
				if(!$id_linea) $id_linea = uniqid();
				$cesta = $this->cesta;	
				if($cesta===false){
					$this->crear_cesta();
				}
				$this->cesta['lineas'][$id_linea] = $data; 
				$this->cesta['lineas'][$id_linea]['cantidad'] = $cantidad;				
				$this->guardar();						
				return true;
			}
			
			function quitar_cesta($id_linea){
				if($this->cesta===false){
					 return false;
				}				

				if(isset($this->cesta['lineas'][$id_linea])) unset($this->cesta['lineas'][$id_linea]);
				$this->guardar();	
				return true;
			}
			function editar_cesta($id_linea,$datos){
				if($this->cesta===false){
					 return false;
				}		
				if(!isset($this->cesta['lineas'][$id_linea])){
					return false;
				}
				foreach($datos as $k=>$v){
							 $this->cesta['lineas'][$id_linea][$k]=$v;					
				}
				$this->guardar();	
				return true;
			}
								

			
			function numero_articulos(){
				return sizeof($this->cesta);
			}
			
			function esta_articulo_incluido($codigo_articulo){
				if(isset($this->cesta['lineas'][$codigo_articulo])) return true;
				return false;	
			}
			
			function lineas(){
				if(!$this->cesta) return false;
				return $this->cesta['lineas'];	
			}
			
			function total_cesta($campo_total = ''){				
					$lineas = $this->cesta['lineas'];
					if(!$campo_total) $campo_total = 'pvp';
					$total = 0;
					foreach($lineas as $l){
							if(isset($l[$campo_total]) && $l[$campo_total]){
								$total+=$l[$campo_total];	
							}else{
								return false; //si uno falla	
							}					        
					}	
					return $total;
			}
			
						
			function vaciar_cesta(){
				return $this->borrar_cesta();
			}

}
?>