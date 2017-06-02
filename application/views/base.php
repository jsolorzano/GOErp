<html>
    <?php
    if (isset($this->session->userdata['logged_in'])) {
        $username = ($this->session->userdata['logged_in']['username']);
        $email = ($this->session->userdata['logged_in']['email']);
        $tipouser = ($this->session->userdata['logged_in']['tipo_usuario']);
        $id = ($this->session->userdata['logged_in']['id']);
    } else {
        header("location: GOErp");
    }
    ?>
    <head>
        <meta charset="utf-8">
        <title>GOErp Intranet</title>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/select2.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/select2-bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/dataTables.bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/dataTables.responsive.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/buttons.dataTables.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/fancybox/jquery.fancybox.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/bootstrap-datepicker.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/animate.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/css/style.css"/>
        <script src="<?= base_url() ?>static/js/jquery-1.12.3.js"></script>
        <script src="<?= base_url() ?>static/js/fancybox/jquery.fancybox.js"></script>
        <script src="<?= base_url() ?>static/js/bootstrap.min.js"></script>
        <script src="<?= base_url() ?>static/js/select2.js"></script>
        <script src="<?= base_url() ?>static/js/select2_locale_es.js"></script>
        <script src="<?= base_url() ?>static/js/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() ?>static/js/dataTables.bootstrap.min.js"></script>
        <script src="<?= base_url() ?>static/js/dataTables.responsive.min.js"></script>
<!--
        <script src="<?= base_url() ?>static/js/dataTables.buttons.min.js"></script>
        <script src="<?= base_url() ?>static/js/buttons.print.min.js"></script>
-->
        <script src="<?= base_url() ?>static/js/bootstrap-datepicker.js"></script>
        <script src="<?= base_url() ?>static/js/bootstrap-datepicker.es.min.js"></script>
        <script src="<?= base_url() ?>static/js/jquery.numeric.js"></script>
        <script src="<?= base_url() ?>static/js/jquery.alphanumeric.js"></script>
        <script src="<?= base_url() ?>static/js/bootbox.js"></script>
        <script src="<?= base_url() ?>static/Highcharts/js/highcharts.js"></script>
        <script src="<?= base_url() ?>static/Highcharts/js/modules/exporting.js"></script>
       
		<script>
			$(document).ready(function () {
				// Aplicamos select2() a todos los combos select
				$("select").select2();
				
				// Ejecución del reporte de inventario general en una ventana modal
				$("#rep_inventario").click(function (e) {
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    
					URL = '<?php echo base_url(); ?>index.php/productos/ControllersProductos/pdf_inventario_general/';
					// Apertura de una modal para la carga del reporte
					$.fancybox.open({ 
						padding : 0, 
						href: URL, 
						type: 'iframe',
						width: 1024, 
						height: 860,
						//~ afterClose : function() {  // Para recargar la página al cerrar la modal
							//~ window.location.reload();
							//~ url = '<?php echo base_url(); ?>';
							//~ window.location = url;
						//~ }
					});
				});
				
				// Ejecución del reporte de inventario de terminales en una ventana modal
				$("#rep_inventario_terminales").click(function (e) {
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    
					URL = '<?php echo base_url(); ?>index.php/productos/ControllersProductos/pdf_inventario_terminales/';
					// Apertura de una modal para la carga del reporte
					$.fancybox.open({ 
						padding : 0, 
						href: URL, 
						type: 'iframe',
						width: 1024, 
						height: 860,
						//~ afterClose : function() {  // Para recargar la página al cerrar la modal
							//~ window.location.reload();
							//~ url = '<?php echo base_url(); ?>';
							//~ window.location = url;
						//~ }
					});
				});
				
				
				
			});
		</script>
		
        <style>
			
            .fancybox-overlay-fixed {
                z-index: 1000;
            }

            a{
                cursor:pointer;
            }

            .navbar-inverse .navbar-nav > li > a:hover {
                color: #fff;
            }
            /*html, body {
                /*backgroud-color: #FFFAF7*/
                background-image: url(<?= base_url() ?>static/img/fondo1.jpg); background-size: 100%; height:100%; width: 100%; position: absolute; background-repeat: no-repeat;
            }*/
            h3 {
                color:000000;
			}
			.lista{
                color:FFFFFF;
                font-weight:bold;
			}
            .navbar-default .navbar-brand {
                color: #fff;
            }
            .navbar-default .navbar-nav > li > a {
                color: #fff;
            }
            
            #footer{
                /*height: 30px;*/
                /*position: absolute;*/
                /*bottom: 0px;*/
                position: fixed;
				bottom: 0;
				width: 50%;
				text-align:center;
				margin-left: 30%;
            }
            
            .dropdown-menu > li > a {

                color: #000000;

            }
            .dropdown-submenu{position:relative;}
            .dropdown-submenu>.dropdown-menu{top:0;left:100%;max-width:100px;margin-top:-6px;margin-right:-1px;-webkit-border-radius:6px 6px 6px 6px;-moz-border-radius:6px 6px 6px 6px;border-radius:6px 6px 6px 6px;}
            .dropdown-submenu:hover>.dropdown-menu{display:block;}
            .dropdown-submenu>:{display:inline;content:" ";float:right;width:0;height:0;border-color:transparent;border-style:solid;border-width:5px 5px 5px 0;border-right-color:#999;margin-top:5px;margin-right:-10px;}
            .dropdown-submenu:hover>a:after{border-right-color:#ffffff;}
            .dropdown-submenu.pull-right{float:none;}.dropdown-submenu.pull-right>.dropdown-menu{right:-100%;margin-left:10px;-webkit-border-radius:6px 6px 6px 6px;-moz-border-radius:6px 6px 6px 6px;border-radius:6px 6px 6px 6px;}
            .dropdown-menu-right {margin-left:0;}
			
			
        </style>
      </head>
    <body>
        <nav role="navigation" class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo base_url(); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-home"></i>&nbsp;GOErp&nbsp;&nbsp;&nbsp;</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
					<!--<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>-->
                        
                        <?php if ($tipouser == 'Administrador'): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="glyphicon glyphicon-plus"></i>&nbsp;Registro <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li class="dropdown-submenu dropdown-menu-right">
                                    <a tabindex="-1" href="#"><i class="glyphicon glyphicon-plus"></i>&nbsp;Terceros<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>&gt;</i></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo base_url(); ?>index.php/clientes/ControllersClientes"><i class="glyphicon glyphicon-export"></i>&nbsp;Clientes</a></li>
										<li><a href="<?php echo base_url(); ?>index.php/proveedores/ControllersProveedores"><i class="glyphicon glyphicon-import"></i>&nbsp;Proveedores</a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo base_url(); ?>index.php/productos/ControllersProductos"><i class="glyphicon glyphicon-th-list"></i>&nbsp;Productos</a></li>
                                <!--<li><a href='<?php echo base_url(); ?>index.php/servicios/ControllersServicios'><i class="fa fa-cogs"></i>&nbsp;Servicios</a></li>-->
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($tipouser == 'Administrador'): ?>
<!--
                        <li><a href="#"><i class="fa fa-file"></i>&nbsp;Nota de Entrega</a></li>
-->
						<!--Sección de Facturación y presupuesto-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="glyphicon glyphicon-shopping-cart"></i>&nbsp;Comercial <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>index.php/presupuesto/ControllersPresupuesto"><i class="glyphicon glyphicon-file"></i>&nbsp;Presupuesto Clientes</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/factura/ControllersFacturar"><i class="glyphicon glyphicon-file"></i>&nbsp;Factura Clientes</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes"><i class="glyphicon glyphicon-file"></i>&nbsp;Ajustes</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos"><i class="glyphicon glyphicon-file"></i>&nbsp;Pedidos Proveedores</a></li>
                            </ul>
                        </li>
                        
                        <!--Sección de Finanzas-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="glyphicon glyphicon-usd"></i>&nbsp;Finanzas <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>index.php/estadisticas/ControllersEstadisticas"><i class="glyphicon glyphicon-stats"></i>&nbsp;Estadísticas</a></li>
<!--
                                <li><a href="<?php echo base_url(); ?>index.php/factura/ControllersFacturar"><i class="glyphicon glyphicon-credit-card"></i>&nbsp;Cuentas por pagar</a></li>
-->
                                <li><a href="<?php echo base_url(); ?>index.php/transferencias/ControllersTransferencias"><i class="glyphicon glyphicon-credit-card"></i>&nbsp;Transferencias</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/pago_proveedores/ControllersPagoProveedores"><i class="glyphicon glyphicon-credit-card"></i>&nbsp;Pago a Proveedores</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/saldos/ControllersSaldos"><i class="glyphicon glyphicon-usd"></i>&nbsp;Saldos</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/movimientos/ControllersMovimientos"><i class="glyphicon glyphicon-usd"></i>&nbsp;Movimientos</a></li>
                            </ul>
                        </li>
                        
                        <!--Sección de Recursos Humanos-->
<!--
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="glyphicon glyphicon-user"></i>&nbsp;RRHH <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>index.php/empleados/ControllersEmpleados"><i class="glyphicon glyphicon-user"></i>&nbsp;Empleados</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/recibos/ControllersRecibo"><i class="glyphicon glyphicon-file"></i>&nbsp;Recibos de Pago</a></li>
                            </ul>
                        </li>
-->
                        <?php endif; ?>
                        
                        <?php if ($tipouser == 'Administrador' ): ?>
                        <!--Sección de Reportes-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="glyphicon glyphicon-file"></i>&nbsp;Reportes <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>index.php/libro_ventas/ControllersLibroVentas"><i class="glyphicon glyphicon-book"></i>&nbsp;Libro de Ventas</a></li>
                                <!--<li><a href="<?php echo base_url(); ?>index.php/rep_autoconsumo/ControllersRepAutoconsumo"><i class="fa fa-file"></i>&nbsp;Auto-consumo y averías</a></li>-->
                                <li class="dropdown-submenu dropdown-menu-right">
                                    <a tabindex="-1" id="rep_inventario"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Inventario de Productos</a>
                                    <!--<ul class="dropdown-menu" role="menu">
                                        <li><a id="rep_inventario"><i class="glyphicon glyphicon-file"></i>&nbsp;Para servicios</a></li>
										<li><a id="rep_inventario_terminales"><i class="glyphicon glyphicon-file"></i>&nbsp;Para terminales </a></li>
                                    </ul>-->
                                </li>
                                <li><a href="<?php echo base_url(); ?>index.php/auditoria/ControllersAuditoria"><i class="glyphicon glyphicon-eye-open"></i>&nbsp;Auditoria</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($tipouser == 'Administrador'): ?>
                        <!--Sección de Configuración-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="glyphicon glyphicon-wrench"></i>
                                &nbsp;Configuración   <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
								<?php if ($tipouser == 'Administrador'): ?>
                                <li><a href='<?php echo base_url(); ?>index.php/tipo_cliente/ControllersTipoCliente'><i class="glyphicon glyphicon-export"></i>&nbsp;Tipos de Cliente</a></li>
                                <li><a href='<?php echo base_url(); ?>index.php/tipo_proveedor/ControllersTipoProveedor'><i class="glyphicon glyphicon-import"></i>&nbsp;Tipos de Proveedor</a></li>
                                <li><a href='<?php echo base_url(); ?>index.php/tipo_producto/ControllersTipoProducto'><i class="glyphicon glyphicon-import"></i>&nbsp;Tipos de Producto</a></li>
<!--
                                <li><a href='<?php echo base_url(); ?>index.php/cargos/ControllersCargos'><i class="glyphicon glyphicon-bookmark"></i>&nbsp;Cargos</a></li>
-->
                                <li><a href='<?php echo base_url(); ?>index.php/bancos/ControllersBancos'><i class="glyphicon glyphicon-credit-card"></i>&nbsp;Bancos</a></li>
                                <li><a href='<?php echo base_url(); ?>index.php/cuentas/ControllersCuentas'><i class="glyphicon glyphicon-credit-card"></i>&nbsp;Cuentas</a></li>
<!--
                                <li><a href='<?php echo base_url(); ?>index.php/conceptos/ControllersConceptos'><i class="glyphicon glyphicon-credit-card"></i>&nbsp;Salarios</a></li>
-->
                                <li><a href='<?php echo base_url(); ?>index.php/ganancia/ControllersGanancia'><i >%</i>&nbsp;Porcentaje de Ganancia</a></li>
                                <li><a href='<?php echo base_url(); ?>index.php/impuesto/ControllersImpuesto'><i >%</i>&nbsp;Impuesto</a></li>
                                <li><a href='<?php echo base_url(); ?>index.php/unidad_medida/ControllersUnidadMedida'><i class="glyphicon glyphicon-resize-horizontal"></i>&nbsp;Unidad de Medida</a></li>
                                <li class="dropdown-submenu dropdown-menu-right">
                                    <a tabindex="-1" href="#"><i class="glyphicon glyphicon-globe"></i>&nbsp;Topología&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>&gt;</i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href='<?php echo base_url(); ?>index.php/topologia/ControllersEstado' ><i class="glyphicon glyphicon-globe"></i>&nbsp;Estados</a></li>
                                        <li><a href='<?php echo base_url(); ?>index.php/topologia/ControllersMunicipio'><i class="glyphicon glyphicon-globe"></i>&nbsp;Municipios</a></li>
                                        <li><a href='<?php echo base_url(); ?>index.php/topologia/ControllersParroquia'><i class="glyphicon glyphicon-globe"></i>&nbsp;Parroquias</a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo base_url(); ?>index.php/usuarios/usuarios"><i class="glyphicon glyphicon-user"></i>&nbsp;Usuarios</a></li>
                                <li><a href="<?php echo base_url(); ?>index.php/respaldos/ControllersRespaldos"><i class="glyphicon glyphicon-floppy-save"></i>&nbsp;Respaldos</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($tipouser == 'Almacen'): ?>
                        <!--Sección de Almacen-->
                        <script>
							
							$(document).ready(function () {
								// Ejecutamos el contador de facturas verificadas por entregar
								$.post('<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/contar/', function(response) {
									var respuesta = response.split('<html>');
									//~ alert(respuesta[0]);
									if(respuesta[0] > 0){
										$("#num_f_p").text('('+respuesta[0]+')');
									}
								});
								
								// Ejecutamos el contador de pedidos aprobados por ingresar
								$.post('<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/contar_pedidos/', function(response) {
									var respuesta = response.split('<html>');
									//~ alert(respuesta[0]);
									if(respuesta[0] > 0){
										$("#num_p_a").text('('+respuesta[0]+')');
									}
								});
							});
                        </script>
                        <li class="dropdown-submenu dropdown-menu-right">
							<a><i class=" glyphicon glyphicon-shopping-cart"></i>
								&nbsp;Almacen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li><a id="salidas" href="<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/lista_facturas"><i class="glyphicon glyphicon-file"></i>&nbsp;Facturas <span id="num_f_p" title="Número de facturas por entregar" style="color:red;font-weight:bold;"></span></a></li>
								<li><a id="ingresos" href="<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/lista_pedidos"><i class="glyphicon glyphicon-file"></i>&nbsp;Pedidos <span id="num_p_a" title="Número de pedidos por ingresar" style="color:red;font-weight:bold;"></span></a></li>
							</ul>
						</li>
                        <?php endif; ?>
                        <a class="navbar-brand" href="<?php echo base_url(); ?>index.php/ayuda/ControllersAyuda"><i class="glyphicon glyphicon-question-sign"></i><font size="3">&nbsp;&nbsp;Ayuda</a>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php echo base_url(); ?>index.php/User_Authentication/logout/<?php echo $id ?>"><i class="glyphicon glyphicon-log-out"></i>&nbsp;Cerrar Sesión:&nbsp; <?php echo"$username ($tipouser)" ?></a></li>
                    </ul>
                </div>
            </div>
        </nav>
		
        <div id="footer">
            <img style="width: 100%;" src="<?= base_url() ?>/static/img/footer.png"/>
        </div>
        
        
        <!-- Funciones para el cambio de contraseña -->
		<script type="text/javascript">
        $(document).ready(function() {
            
            $("a.cambio").click(function () {
                $('div#div_cambio_user').modal('show');
            });
            
            var change_id = '<?php echo $this->session->userdata['logged_in']['change_id']; ?>';
            
            if(change_id == 0){
                $('div#div_cambio_user').modal({backdrop: 'static', keyboard: false})
            }

            $(".actualizar_passwd").click(function () {
            var $password = $("#password_f").val();
            var $password_new = $("#password_new").val();
            var $password_new_r = $("#password_new_r").val();
            var formData = new FormData(document.getElementById("frmpassword"));
            if ($password == "") {
                bootbox.alert("Debe ingresar su contraseña anterior", function () {
				}).on('hidden.bs.modal', function (event) {
					
				});
                
            } else if ($password_new == "") {
                bootbox.alert("Debe ingresar su contraseña nueva", function () {
				}).on('hidden.bs.modal', function (event) {
					
				});
				
            } else if ($password_new_r == "") {
                bootbox.alert("Ingrese de nuevo su contraseña", function () {
				}).on('hidden.bs.modal', function (event) {
					
				});
                
            } else if ($password_new != $password_new_r) {
                bootbox.alert("Disculpe, las contraseñas no coinciden", function () {
				}).on('hidden.bs.modal', function (event) {
					
				});
                
            } else {

                bootbox.confirm("¿Desea cambiar la contraseña?", function(result) {
					if (result) {
					  $.ajax({
							url: "<?php echo base_url(); ?>index.php/User_Authentication/cambio_password",
							type: "post",
							dataType: "html",
							data: formData,
							cache: false,
							contentType: false,
							processData: false
						}).done(function(res){
							if (res == 1) {
								bootbox.alert("Registro actualizado... Haga click en el botón para cerrar", function () {
								}).on('hidden.bs.modal', function (event) {
									
								});
								$.ajax({
									url: '<?php echo base_url(); ?>index.php/User_Authentication/logout/<?php echo $id?>',
									type: "post",
									dataType: "html",
									//data: formData,
									cache: false,
									contentType: false,
									processData: false
								}).done(function(res){
								  if (res) {
									  window.location = '<?php echo base_url(); ?>';
								  }
							});
							}else if( res == 2){
							  bootbox.alert("Disculpe, la contraseña anterior no es correcta... Haga click en el botón para cerrar", function () {
								}).on('hidden.bs.modal', function (event) {
									
								});
							}
						});
					}
				});
            }
        });

        });
    </script>

    <!-- FORMULARIO DE CAMBIO DE CONTRASEÑA  -->
    <div class="modal fade" id="div_cambio_user" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog" style="width: 40%;">
            <div class="modal-content form-horizontal">
                <div class="modal-header">
                    <div class="titulo" id='div_name'>
                        <?php echo $this->session->userdata['logged_in']['first_name']." ".$this->session->userdata['logged_in']['last_name']; ?>
                    </div>
                </div>
                <div class="modal-body" style="height:400px !important;">
                    <form method="post" enctype="multipart/form-data" id="frmpassword">
                        <div class="col-xs-12">
                            <div class="form-group col-xs-10" style="margin:1%;">
                               <label>Contraseña anterior</label>
                               <input style="text-transform: lowercase;" type="password" id='password_f' name='password_f' class="form-control" placeholder="Contraseña anterior" autofocus='autofocus'/>
                            </div>
                            <div class="form-group col-xs-10" style="margin:1%;">
                               <label>Contraseña nueva</label>
                               <input style="text-transform: lowercase;" type="password" id='password_new' name='password' class="form-control" placeholder="Contraseña nueva"/>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group col-xs-10" style="margin:1%;">
                               <label>Ingrese de nuevo su Contraseña</label>
                               <input style="text-transform: lowercase;" type="password" id='password_new_r' class="form-control" placeholder="Repita su contraseña"/>
                               <input type="hidden" name='id' value="<?php echo $id;?>"/>
                               <input type="hidden" name='username' value="<?php echo $username;?>"/>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group col-xs-12" style="margin:1%;">
                               <button type="button" class="btn btn-primary actualizar_passwd">Cambiar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN DE FORMULARIO -->
        
    </body>
</html>
