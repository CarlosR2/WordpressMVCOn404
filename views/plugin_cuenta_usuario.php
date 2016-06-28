<?php

get_header();

?><style> 

#tabla_contacto, #tabla_contacto tr, #tabla_contacto tr td{
	border:0;
}
#tabla_contacto:hover, #tabla_contacto tr:hover, #tabla_contacto tr td:hover{
	background:none;
}

table.estadisticas_afiliado td{
	text-align:left;	
}

.cuenta .columna_izquierda{
	float:left;width:200px;
}
.cuenta .columna_derecha{
	float:right;width:650px;	
}


.cuenta ul {
	list-style:none;

}

.cuenta a.cuenta_menu{
	display:block;
	min-width:140px;
	margin:10px;
	padding:10px;
	background:#eee;
}
a.cuenta_menu:hover{
	text-decoration:none;	
}

a.cuenta_menu.selected{
	background:#bbb;
	color:#555;
}

.cuenta .cuenta_content{
	display:none;	
}

</style>

<div class="centercolumn">
	<div id="maincontent">	
		<!-- CONTENT -->
		<div id="content" class="full cuenta">		

			<div id="post" <?php post_class(); ?>>
				<div class="entry-content">

					<div style="float:right">
						<a href="#a" id="logout_ajax">Salir de la cuenta</a>
					</div>



					<div class="cuenta_content" id="cuenta_content_descargas" style="display:block;">

						<h2>Tus datos de usuario</h2>
						<div class=" subForm" id="datos_usuario" style="width: 80%;">
							<table >									
								<tr><td>Nombre</td><td><?=$display_name?></td></tr>
								<tr><td>Correo</td><td><?=$usuario_['user_email'];?></td></tr>									
								<tr><td> Contraseña &nbsp;&nbsp;&nbsp;&nbsp;</td><td>*********</td></tr>																												
							</table>			
							<br />										<br />								
							<div style="text-align:center">										
								<input type="button" class="button dark medium" value="Editar datos" onClick="jQuery('#datos_usuario').hide();jQuery('#datos_usuario_editar').show();"/>
							</div>	
						</div>
						<div class=" subForm" id="datos_usuario_editar" style="display:none;width:80%">
							<table>
								<tr><td>Nombre</td><td><input type="text" value="<?=$display_name?>" id="editar_nombre"></td></tr>
								<!--<tr><td>Login</td><td><input type="text"></td></tr>-->
								<tr><td>Correo</td><td><input type="text" value="<?=$usuario_['user_email'];?>" id="editar_correo" readonly ></td></tr>
								<tr><td>Nueva Contraseña &nbsp;&nbsp;</td><td><input type="password" placeholder="******" id="editar_pass"></td></tr>
								<tr><td>Repite Contraseña &nbsp;&nbsp;</td><td><input type="password" placeholder="******" id="editar_pass2"></td></tr>		
								
								
							</table>

							<br />										<br />																		
							<div style="text-align:center">
								Escribe la contraseña solo si quieres cambiarla<br /><br /> 
								<input type="button" id="editar_datos_usuario" class="button dark medium" value="Guardar"/>
							</div>
						</div>
					</div>
					<div class="cuenta_content" id="cuenta_content_contacto">
						<h2>Contacto</h2>
						
						<br />			<br />
						<table style="border:0" id="tabla_contacto">
							<tr><td>Asunto: </td><td><input type="text" name="contacto_asunto" id="contacto_asunto" /></td></tr>
							<tr><td>Mensaje: &nbsp; &nbsp; &nbsp; </td><td> <textarea id="contacto_mensaje" name="contacto_mensaje"></textarea></td></tr>
							<tr><td>&nbsp; &nbsp; &nbsp; </td><td> </td></tr>			
							<tr><td>&nbsp; &nbsp; &nbsp; </td><td>			<input type="button" class="button dark medium" value="Enviar" id="enviar_mensaje" onClick=""/> </td></tr>						
						</table>
						<br />
						<br />
					</div>

					<div style="clear:both"> </div>

					<div style="clear:both"> </div>





				</div><!-- .entry-content -->
			</div><!-- #post-## -->
		</div><!-- end #content -->
		<!-- END CONTENT -->
		<div class=" " style="clear:both">&nbsp;</div>
	</div><!-- end #maincontent -->
</div>

<script>
	jQuery(document).ready(function(){



		jQuery('#editar_datos_usuario').click(function(){

			var error = "";
			var params = {}
			params.nombre = jQuery('#editar_nombre').val();
			params.correo = jQuery('#editar_correo').val();		
			params.pass = jQuery('#editar_pass').val();		
			params.pass2 = jQuery('#editar_pass2').val();						

			
			jQuery('.warning_reg').remove();	
			jQuery('input[type=text]').css('border','');			

			if(!params.nombre || params.nombre.length<3){
				jQuery('#editar_nombre').css('border','2px solid red');	
				error += 'El nombre es incorrecto';				
			}
			if(!params.correo || !validate_email(params.correo)){
				jQuery('#editar_correo').css('border','2px solid red');	
				error += 'El correo es incorrecto';				
			}			

			if(params.pass && params.pass<3){
				jQuery('#editar_pass').css('border','2px solid red');	
				error += 'El pass es incorrecto';				
			}
			if(params.pass && (!params.pass2 || params.pass2<3 || params.pass2!=params.pass)){
				jQuery('#editar_pass2').css('border','2px solid red');	
				error += 'Las contraseñas no coinciden';				
				alert('Las contraseñas no coinciden');
			}

			anchor = jQuery(this);
			jQuery(this).html('guardando...');


			callback = function(){
				alert('Se ha guardado correctamente');
				anchor.html('recargando la página...');
				window.location.reload();
			}
			callback_false = function(error){
				anchor.after("<div class='warning_reg' style='color:red;margin-top:20px;text-align:center'>"+error+"</div>");			
				alert('No se pudo guardar');
				anchor.html('Guardar');
				//				window.location.reload();				
			}

			app.post_wp('editar_datos_usuario_ajax',params,callback,callback_false);	
		});

		jQuery('#logout_ajax').click(function(){
			jQuery(this).html('desconectando...'); 
			params = {}
			callback = function(){
				alert('Has salido de tu cuenta. Gracias por tu visita');
				jQuery(this).html('recargando la página...');
				window.location.reload();
			}
			callback_false = function(){
				alert('No se pudo desconectar');
				//				window.location.reload();				
			}

			app.post_wp('desconectar',params,callback,callback_false);		
		});





		if(window.location.hash) {
			var hash = window.location.hash.replace('#','');
			jQuery('a.cuenta_menu[data-id='+hash+']').click();
		} else {
			// Fragment doesn't exist
		}



	});

</script>
<script>
	var ajax_url = "<?=AJAX_URL?>";
</script>
<script type='text/javascript' src='<?=URL_PLUGIN_JS?>/app.js'></script>			

<div class=" " style="clear:both">&nbsp;</div>
<?php get_footer(); ?>