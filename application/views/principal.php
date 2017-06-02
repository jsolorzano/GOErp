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
<script type="text/javascript">
	$(document).ready(function(){
		$('#inventario').click(function(){
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
		})
		
		// Función para ejecutar ir a la url del módulo solicitado en la caja de búsqueda
		$("#buscar").click(function (e) {
			e.preventDefault();  // Para evitar que se envíe por defecto
			
			URL = $("#modulo").val();
			// Apertura de una modal para la carga del reporte
			window.location = URL;
		});
	});
</script>

<style type="text/css">
#global {
	height: 450px;
	width: 200px;
	border: 0px solid #ddd;
	/*background: #f1f1f1;*/
	/*background: #3C8DBC;*/
	background: -webkit-linear-gradient(top left, #00A2DB 0%, #222D32 100%);
	background: linear-gradient(top bottom right, #00A2DB 0%, #222D32 100%);
	overflow-y: scroll;
}
#mensajes {
	height: auto;
}
.texto {
	padding:4px;
	background:#fff;
}
</style>


	
    <?php if ($tipouser == 'Administrador' ): ?>
	<div class="container" style="width: 95%">
		<div class="row-fluid text-center">

			<div class="mainbody-section text-center">
				<div class="container">
					<div class="row">
						
						<div class="col-md-3">
							<div class="menu-item blue_dark">
								<a id="facturas" data-toggle="modal" href="<?php echo base_url(); ?>index.php/factura/ControllersFacturar">
									<i class="glyphicon glyphicon-file"></i>
									<p>Facturación</p>
								</a>
							</div>
							<div class="menu-item brown">
								<a id="productos" data-toggle="modal" href="<?php echo base_url(); ?>index.php/productos/ControllersProductos">
									<i class="glyphicon glyphicon-th-list"></i>
									<p>Productos</p>
								</a>
							</div>
							<div class="menu-item esmeralda">
								<a id="estadisticas" data-toggle="modal" href="<?php echo base_url(); ?>index.php/estadisticas/ControllersEstadisticas">
									<i class="glyphicon glyphicon-stats"></i>
									<p>Estadísticas</p>
								</a>
							</div>
							<div class="menu-item concreto">
								<a id="libro" data-toggle="modal" href="<?php echo base_url(); ?>index.php/libro_ventas/ControllersLibroVentas">
									<i class="glyphicon glyphicon-book"></i>
									<p>Libro de Ventas</p>
								</a>
							</div>
							
						</div>
						<div class="col-md-3">
							<div class="menu-item purple">
								<a id="presupuestos" data-toggle="modal" href="<?php echo base_url(); ?>index.php/presupuesto/ControllersPresupuesto">
									<i class="glyphicon glyphicon-file"></i>
									<p>Presupuesto</p>
								</a>
							</div>
							<div class="menu-item red">
								<a id="clientes" data-toggle="modal" href="<?php echo base_url(); ?>index.php/clientes/ControllersClientes">
									<i class="glyphicon glyphicon-export"></i>
									<p>Clientes</p>
								</a>
							</div>
							<div class="menu-item verde_pastel">
								<a id="saldos" data-toggle="modal" href="<?php echo base_url(); ?>index.php/saldos/ControllersSaldos">
									<i class="glyphicon glyphicon-usd"></i>
									<p>Saldos</p>
								</a>
							</div>
							<div class="menu-item amarillo">
								<a id="inventario" data-toggle="modal">
									<i class="glyphicon glyphicon-list"></i>
									<p>Inventario</p>
								</a>
							</div>
				
						</div>
						<div class="col-md-3">
							<div class="menu-item orange">
								<a id="ajustes" data-toggle="modal" href="<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes">
									<i class="glyphicon glyphicon-file"></i>
									<p>Ajustes</p>
								</a>
							</div>
							<div class="menu-item light-orange">
								<a id="proveedores" data-toggle="modal" href="<?php echo base_url(); ?>index.php/proveedores/ControllersProveedores">
									<i class="glyphicon glyphicon-import"></i>
									<p>Proveedores</p>
								</a>
							</div>
							<div class="menu-item gris">
								<a id="movimientos" data-toggle="modal" href="<?php echo base_url(); ?>index.php/movimientos/ControllersMovimientos">
									<i class="glyphicon glyphicon-usd"></i>
									<p>Movimientos</p>
								</a>
							</div>
							<div class="menu-item plateado">
								<a id="auditoria" data-toggle="modal" href="<?php echo base_url(); ?>index.php/auditoria/ControllersAuditoria">
									<i class="glyphicon glyphicon-eye-open"></i>
									<p>Auditoria</p>
								</a>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="menu-item morado">
								<a id="pedidos" data-toggle="modal" href="<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos">
									<i class="glyphicon glyphicon-file"></i>
									<p>Pedidos</p>
								</a>
							</div>
							<div class="menu-item green_dark">
								<a id="empleados" data-toggle="modal" href="<?php echo base_url(); ?>index.php/bancos/ControllersBancos">
									<i class="glyphicon glyphicon-credit-card"></i>
									<p>Bancos</p>
								</a>
							</div>
							<div class="menu-item green">
								<a id="recivos_p" data-toggle="modal" href="<?php echo base_url(); ?>index.php/cuentas/ControllersCuentas">
									<i class="glyphicon glyphicon-credit-card"></i>
									<p>Cuentas</p>
								</a>
							</div>
							<div class="menu-item blue">
								<a id="usuarios" data-toggle="modal" href="<?php echo base_url(); ?>index.php/usuarios/Usuarios">
									<i class="glyphicon glyphicon-user"></i>
									<p>Gestión de Usuarios</p>
								</a>
							</div>
				
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<?php if ($tipouser == 'Almacen' ): ?>
	<div class="container" style="width: 50%">
		<div class="row-fluid text-center">

			<div class="mainbody-section text-center">
				<div class="container">
					<div class="row">
						
						<div class="col-md-3">
							<div class="menu-item blue_dark">
								<a id="facturas" data-toggle="modal" href="<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/lista_facturas">
									<i class="glyphicon glyphicon-file"></i>
									<p>Facturas</p>
								</a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="menu-item morado">
								<a id="presupuestos" data-toggle="modal" href="<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/lista_pedidos">
									<i class="glyphicon glyphicon-file"></i>
									<p>Pedidos</p>
								</a>
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
</div>
