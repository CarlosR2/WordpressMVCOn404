<?php


add_action("wp_ajax_actualizar_carrito", "actualizar_carrito");
add_action("wp_ajax_nopriv_actualizar_carrito", "actualizar_carrito");


add_action("wp_ajax_carrito", "carrito");
add_action("wp_ajax_nopriv_carrito", "carrito");









function actualizar_carrito() {

	if(!$_SESSION['carrito_digital'])
		$_SESSION['carrito_digital'] = Array();

	$carrito =  $_SESSION['carrito_digital'];
	$pedido = $_POST['pedido'];  /// ----> COMPROBAR QUE ESTA BIEN
	$carrito = $pedido;
	//print_r($pedido);
	$_SESSION['carrito_digital'] = $carrito;
	ok();
}


function carrito() {
	if(!$_SESSION['carrito_digital']) {
		// Vacio
		ok("");
	}
	$carrito = $_SESSION['carrito_digital'];
	ok($carrito);
}

?>