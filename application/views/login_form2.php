<html>
    
    <?php
    if (isset($this->session->userdata['logged_in'])) {

        header("location: http://localhost/tesis_erp/index.php/User_Authentication/user_login_process");
    }
    ?>
    <head>
        <title>.:: Tesis ERP ::.</title>
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">-->
        <link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/select2.css"/>
        <link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/select2-bootstrap.css"/>
        <script src="<?=base_url()?>static/js/jquery-1.11.2.min.js"></script>
        <script src="<?=base_url()?>static/js/bootstrap.min.js"></script>

        <script src="<?=base_url()?>static/js/bootstrap-datepicker.js"></script>
        <script src="<?=base_url()?>static/js/bootstrap-datepicker.es.min.js"></script>
        <script src="<?= base_url() ?>static/js/bootbox.js"></script>

		<link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/animate.css"/>
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/apprise.css"/>
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/estilo.css"/>

		<style type="text/css">
		html, body {
				height: 100%;
				background-color: #FFFAF7;
			}
		body{
		font-size: 12px;
		}
		.container {
			width: 40%;
		}
		
		.fancybox-overlay-fixed {
			z-index: 1000;
		}

		a{
			cursor:pointer;
		}
		
		.container > .content {
			background-color: #fff;
			padding: 20px;
			margin: 0 -20px; 
			-webkit-border-radius: 10px 10px 10px 10px;
			-moz-border-radius: 10px 10px 10px 10px;
			border-radius: 10px 10px 10px 10px;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
			-moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
			box-shadow: 0 1px 2px rgba(0,0,0,.15);
			margin-top: 10%;
		}
		.login {
			/*margin-left: ;*/

		}           
		html, body {
			height: 50%;
		
		}
		#contenedor {
		  min-height: 85%;
		  height: auto !important;
		  height: 100%;
		  margin: 0 auto -20px;
		}
		#header{
		  background-color: #FFFFFF;
		  text-align: center;
		}
		#footer{
		  height: 55px;
		  position: fixed;
		  bottom: 0px;
		  width:100% !important;
		  background-color: #FFFFFF;
		}
		.content {
		-moz-animation-duration: 4s;
		-webkit-animation-duration: 4s;
		-op-animation-duration: 4s;
		}
		</style>
			
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('static/css/style.css'); ?>">
		
		<script>
			$(document).ready(function () {
				// Activar modal al hacer click en el enlace de recuoperación
				$("#rec_password").click(function (e) {
					e.preventDefault();  // Para evitar que se envíe por defecto
					$("#modal_clave").modal('show');
				});
				
				// Validar formulario
				$("#generar").click(function (e) {
					e.preventDefault();  // Para evitar que se envíe por defecto
					
					if($("#username_rec").val() == ''){
						bootbox.alert("Indique el usuario a recuperar", function () {
						}).on('hidden.bs.modal', function (event) {
							$("#username_rec").parent('div').addClass('has-error')
							$("#username_rec").val('');
							$("#username_rec").focus();
						});
					} else if($("#password_rec").val() == ''){
						bootbox.alert("Indique la clave maestra", function () {
						}).on('hidden.bs.modal', function (event) {
							$("#password_rec").parent('div').addClass('has-error')
							$("#password_rec").val('');
							$("#password_rec").focus();
						});
					} else {
						//~ alert($("#username_rec").val());
						$.post('<?php echo base_url(); ?>index.php/User_Authentication/recuperar/', $("#f_rec_usuario").serialize(), function(response) {
							//~ alert(response[3]);
							if (response[3] != "U"){
								bootbox.alert("La contraseña para el usuario "+$("#username_rec").val()+" ha sido cambiada exitosamente: "+response, function () {
								}).on('hidden.bs.modal', function (event) {
									location.reload();
								});
							}else{
								bootbox.alert(response, function () {
								}).on('hidden.bs.modal', function (event) {
									
								});
							}
						});
					}
				});
			});
		</script>
    </head>
    <body>

		<div align="center">
			<img src="<?= base_url()?>static/img/tope.jpg" style="width: 100%;"/>
		</div>
        

        <?php
        if (isset($logout_message)) {
            echo "</br><div class='alert alert-dismissible alert-success' style='text-align: center'>";
            echo "<button type='button' class='close' data-dismiss='alert'>X</button>";
            echo $logout_message;
            echo "</div>";
        }
        ?>
        <?php
        if (isset($message_display)) {
            echo "<div class='alert alert-dismissible alert-success' style='text-align: center'>";
            echo "<button type='button' class='close' data-dismiss='alert'>X</button>";
            echo $message_display;
            echo "</div>";
        }
        ?>
        <?php
        
        if (isset($error_message)) {
            echo "<div class='alert alert-dismissible alert-danger' style='text-align: center'>";
            echo "<button type='button' class='close' data-dismiss='alert'>X</button>";
            echo $error_message;
        }
        echo validation_errors();
        echo "</div>";
        ?>


        
		<div style="display: table;clear: both;"></div>
   
            <div class="container" >
				<div class="content animated fadeIn" style='box-shadow: 0 1px 5px rgba(0,0,0,.85) '>
					<div class="row" >
						<div class="login login-form" id="login" >
							<?php echo form_open('User_Authentication/user_login_process'); ?>
							<form id="frmlogin" class="form-horizontal"  method="POST" enctype="multipart/form-data" autocomplete="off" role="form">
								<div class="col-lg-6">
									 <img src="<?= base_url() ?>static/img/FACTURACION.png" style="width: 100%;" /></p>
								</div>
								
								<div class="col-lg-6">
									 <div class="form-group " >
										 <br/>
										 <br/>
										  <br/>
									<div id="div_usuario" class='input-group col-sm-12' >
										<span class="input-group-addon">
											<i class="glyphicon glyphicon-user"></i>
										</span>
										<input type="text" class="form-control input-sm letras" style="width: 90%; " id="username" name="username"placeholder="Usuario" autofocus="true">
									</div>
								</div>
								<div class="form-group">
									<div id="div_contrasena" class="input-group col-sm-12">
										<span class="input-group-addon">
											<i class="glyphicon glyphicon-lock"></i>
										</span>
										<input style="width: 90%;" type="password" class="form-control input-sm letras" id="password" name="password" placeholder="Contrase&ntilde;a"/>
									</div>
								</div>
								
								<input type='hidden' id='user_accion' name='user_accion' readonly value=' <?php echo"$username" ?>'>
								<div class="form-group" style='width:90%'>
									<div class="col-sm-offset-62">
										<input type="submit" value=" Ingresar " name="submit" class="btn btn-block btn-success"/><br />
									</div>
								</div>
								<div class="form-group" style='width:90%'>
									<div>
										<a id="rec_password" name="recpassword">¿Olvidó su contraseña?</a>
									</div>
								</div>
																
								</div>
							   
							</form>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
        
		<div class="modal" id="modal_clave">
		   <div class="modal-dialog">
			  <div class="modal-content">
				 <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">
					   <span class="glyphicon glyphicon-search"></span>
					   &nbsp;Introdúzca el nombre de usuario y la clave maestra para generar la nueva contraseña
					</h4>
				 </div>
				 <div class="modal-body">
					<form id="f_rec_usuario" name="f_rec_usuario" action="" method="post">
					   <div class="form-group">
							<div class="col-sm-12">
								<input type="text" class="form-control" style="width: 100%; " id="username_rec" name="username_rec" placeholder="Usuario" autofocus="true">
							</div>
							</br></br></br>
							<div class="col-sm-12">
								<input style="width: 100%;" type="password" class="form-control" id="password_rec" name="password_rec" placeholder="Contrase&ntilde;a maestra"/>
							</div>
							</br></br></br>
							<div>
								<span class="input-btn">
									<button class="btn btn-primary" type="button" id="generar">
										Generar<span class="glyphicon glyphicon-share-alt"></span>
									</button>
								</span>
							</div>
					   </div>
					</form>
				 </div>
				 
			  </div>
		   </div>
		</div>
        
    </body>
</html>


