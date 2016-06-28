<?php


get_header();

?>
<style>

	.bloque{
		border:0px;
	}
	table, tr td, td{
		border:0px;
		vertical-align: middle;
	}

	.bloque_login{
		background:#f7f7f7;
		padding:30px;
		border-radius:8px;
		border:1px solid #ccc;
	}

	.entry-content .bloque_login input{
		margin:3px;	
	} 

	.bloque_login input{
		border:1px solid #ccc;	
	}

	.bloque_login a.button{
		background:#ccc;	

	}
	.bloque_login a.button:hover{
		text-decoration:none; 
	}

	.bloque_login.columna_izquierda{
		float:left;width:45%;
	}
	.bloque_login.columna_derecha{
		float:right;width:45%;

	}



</style>
<div class="centercolumn">
	<div id="maincontent">	
		<!-- CONTENT -->
		<div id="content" class="full">		
			<?php if ( function_exists('yoast_breadcrumb') && !is_front_page() ) {
	yoast_breadcrumb('<div id="breadcrumbs">','</div>');
} ?>

			<div id="post" <?php post_class(); ?>>
				<div class="entry-content">




					<div class="container_12">
						<div class="grid_12">
							<div class="">
								<h1>Conectar cuenta usuario</h1>
								<br />
								<div style="width:80%;margin:auto;text-align:center;">
									Regístrate o accede a tu cuenta para acceder a las descargas y a contenido privilegiado en la web. 
								</div>	
							</div>
							<br /><br />
							<?if(isset($code_recupera) && $code_recupera==1){?>
							<div style="margin-bottom:150px;text-align:center" class="subForm" >
								<h3>Has solicitado recuperar tu contraseña</h3>
								<br /><br />
								Introduce aquí tu nueva contraseña
								<br /><br />
								<input type="password" id="nueva_contrasenya"> <a href="#a" type="button" class="button dark medium" id="resetear_pass">Resetear</a>
							</div>
							<script>
								jQuery(document).ready(function(){		
									jQuery('#resetear_pass').click(function(){
										jQuery(this).html('Reseteando...');
										var anchor = jQuery(this);
										pass= jQuery('#nueva_contrasenya').val();
										if(!pass || pass.length<5){
											anchor.html("Resetear");							
											alert("Por favor, introduce una contraseña con más de 4 carácteres");
											return;	
										}
										var params = {};
										params.email = '<?=$correo?>';
										params.id = '<?=$id_wp?>';                        
										params.cod = '<?=$cod?>';                                                
										//  params.email_marketing = email_marketing;                      
										// params.url = document.URL;
										params.pass = pass;
										callback =function(){
											alert("Contraseña cambiada, ya puedes acceder con tu nueva contraseña");
											window.location = '/cuenta-usuario-acceso/';
										}
										callback_false = function(e){
											alert('Hubo un error: ' +e);	
											anchor.html("Resetear");
										}
										app.post_wp('set_contra_ajax',params,callback,callback_false);		

									});
								});
							</script>
							<?}else if($code_recupera==-1){?>
							<div style="margin-bottom:50px;text-align:center" class="subForm" >
								<h3>Hubo un error con la dirección de recuperación, intenta recuperarla de nueo</h3> 
							</div>	
							<?}?>
							<div style="clear:both"> </div>
							<div style=""  class="bloque_login columna_izquierda" >
								<h2>Iniciar sesión</h2>	           
								Si ya tienes cuenta
								<div class="bloque subForm" style="text-align:center">
									<table style="text-align:left">

										<tr><td>Correo &nbsp;&nbsp;</td><td><input type="text" id="login_correo"></td></tr>
										<tr><td>Contraseña &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="password" id="login_pass"></td></tr>
									</table>
								</div>

								<div style="text-align:center">									
									<a href="#a" type="button" class="button dark medium" id="iniciar_sesion">&nbsp; Entrar &nbsp;</a>
									<br />									<br />
									<br />									<br />
									<a href="#a" id="pass_olvidado" onClick="jQuery(this).remove();jQuery('#recuperar_pass').fadeIn();">Si has olvidado tu contraseña,<br /> haz click aquí</a>	
									<div style="display:none;margin-top:30px" class="subForm" id="recuperar_pass">
										Escribe tu correo y te enviaremos 			<br />un email para resetear la contraseña	
										<br />
										<br />			
										<input type="text" id="correo_olvidado"> <a href="#a" type="button" class="button dark medium" id="pass_olvidado_confirm">Enviar correo recuperacion</a>

									</div>
								</div>
							</div>
							<div style=""  class="bloque_login columna_derecha">				 	
								<h2>Registrar nueva cuenta</h2>
								Si no tienes cuenta 
								<div class="bloque subForm" style="text-align:center" id="bloque_registrar">

									<table style="text-align:left">
										<tr><td>Nombre</td><td><input type="text" id="nombre"></td></tr>
										<!--<tr><td>Login</td><td><input type="text"></td></tr>-->
										<tr><td>Correo</td><td><input type="text" id="correo"></td></tr>
										<tr><td>Contraseña &nbsp;&nbsp;</td><td><input type="password" id="pass"></td></tr>
										<tr><td>Repite Contraseña &nbsp;&nbsp;</td><td><input type="password" id="pass2"></td></tr>		
									</table>
									<div style="text-align:left">
										Acepto las condiciones de uso <br />
										y política de privacidad 
										&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
										&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
										<input type="checkbox" id="acepto">
									</div>
								</div>

								<div style="text-align:center">													
									<a href="#a" class="button dark medium" id="registrar_cuenta">&nbsp; Registrar &nbsp;</a>
								</div>										
							</div>
							<div style="clear:both"> </div>
							<br />	           
							<br />	         

							<br />	           



						</div>
					</div>
					<div class="clear"></div>




















				</div><!-- .entry-content -->
			</div><!-- #post-## -->

		</div><!-- end #content -->
		<!-- END CONTENT -->
		<div class="clr" style="clear:both">&nbsp;</div>
	</div><!-- end #maincontent -->
</div>
<script>

	jQuery(document).ready(function(){		


		jQuery('#pass_olvidado_confirm').click(function(){
			jQuery(this).html("Enviando peticion...");
			var user = jQuery('#correo_olvidado').val();
			var params = {}
			params.correo = user;
			if(!params.correo || !validate_email(params.correo)){
				jQuery('#correo_olvidado').css('border','1px solid red');	
				return;
			}		
			anchor =jQuery(this);
			callback = function(){
				jQuery('#recuperar_pass').html('Te hemos enviado un correo para recuperar tu contraseña');
			}
			callback_false = function(info){
				alert('Hubo un problema: '+info);
				anchor.html("Enviar correo recuperación");
			}
			app.post_wp('request_recupera_pass_ajax',params,callback,callback_false);			
		});

		jQuery('#iniciar_sesion').click(function(){
			jQuery(this).html('validando...');
			var params = {}
			jQuery('input[type=text]').css('border','');

			params.user= jQuery('#login_correo').val();
			params.pass= jQuery('#login_pass').val();
			if(!params.user || !validate_email(params.user)){
				jQuery('#login_correo').css('border','1px solid red');	
			}
			if(!params.pass){
				jQuery('#login_pass').css('border','1px solid red');	
			}			
			if(!params.user || !params.pass){
				jQuery(this).html('Entrar');				
				return;
			}
			anchor = jQuery(this);
			var callback = function(){
				//				alert('Has salido de tu cuenta. Gracias por tu visita');
				anchor.html('entrando a tu cuenta...');
				window.location.reload();
			}
			var callback_false = function(){
				anchor.html('Entrar');			
				alert('No se pudo conectar a tu cuenta. Revisa tu contraseña');
			}

			app.post_wp('login',params,callback,callback_false);
		});

		jQuery('#registrar_cuenta').click(function(){
			var error = '';
			jQuery('.warning_reg').remove();	
			jQuery('input[type=text]').css('border','');			
			jQuery(this).html('Registrando...');
			var nombre = jQuery('#nombre').val();
			if(!nombre || nombre.length<3){
				jQuery('#nombre').css('border','2px solid red');	
				error += 'El nombre es incorrecto';				
			}
			var correo = jQuery('#correo').val();
			if(!correo || !validate_email(correo)){
				jQuery('#correo').css('border','2px solid red');	
				error += 'El correo es incorrecto';				
			}			

			
			var pass = jQuery('#pass').val();						
			if(!pass || pass<3){
				jQuery('#pass').css('border','2px solid red');	
				error += 'El pass es incorrecto';				
			}

			var pass2 = jQuery('#pass2').val();						
			if(!error && (!pass2 || pass2<3 || pass2!=pass)){
				jQuery('#pass2').css('border','2px solid red');	
				error += 'Las contraseñas no coinciden';				
				alert('Las contraseñas no coinciden');
			}


			if(error!=''){
				jQuery(this).html('Registrar')
				return;	

			}
			if(!jQuery('#acepto').is(':checked')){
				alert('Debes aceptar las condiciones de uso para poder obtener tu librería');	
				jQuery(this).html('Registrar')
				return;
			}

			var anchor = jQuery(this);
			var callback = function(){
				jQuery('#bloque_registrar').html("<div style='text-align:Center;font-size:18px;'>Ya estás registrado<br /><br />Ya puedes comprar artículos y acceder a tu espacio personal</div>");	
				anchor.remove();
			}

			var callback_false = function(error){
				jQuery('#bloque_registrar').after("<div class='warning_reg' style='color:red;margin-top:20px;text-align:center'>"+error+"</div>");			
				alert(error);
				anchor.html('Registrar');				
			}

			var params = {};
			params.nombre = nombre;
			params.email = correo;
			//  params.email_marketing = email_marketing;                      
			// params.url = document.URL;
			params.pass = pass;
			params.pass_again = pass;						  
			app.post_wp('register',params,callback,callback_false);						  


		});

	});

	function validate_email(email){
		var x = email;
		var atpos=x.indexOf("@");
		var dotpos=x.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
			return false;
		}
		return true;
	}


</script>
<script>
	var ajax_url = "<?=AJAX_URL?>";
</script>
<script type='text/javascript' src='<?=URL_PLUGIN_JS?>/app.js'></script>			
<div class="clr" style="clear:both">&nbsp;</div>
<?php get_footer(); ?>