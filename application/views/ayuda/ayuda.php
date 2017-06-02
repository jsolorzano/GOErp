    <?php
    if (isset($this->session->userdata['logged_in'])) {
        $username = ($this->session->userdata['logged_in']['username']);
        $email = ($this->session->userdata['logged_in']['email']);
        $tipouser = ($this->session->userdata['logged_in']['tipo_usuario']);
        $id_user = ($this->session->userdata['logged_in']['id']);
    } else {
        redirect(base_url());
    }
    ?>
        <script>
            $(document).ready(function () {

                $('select').on({
                    change: function () {
                        $(this).parent('div').removeClass('has-error');
                    }
                });
                $('input').on({
                    keypress: function () {
                        $(this).parent('div').removeClass('has-error');
                    }
                });

                $("select").select2();
                
				// Funciones para mostrar la información de ayuda de los distintos módulos
                
                $("#facturas").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_facturacion/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#presupuestos").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_presupuesto/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				 
				$("#ajustes").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_ajustes/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#pedidos").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_pedidos/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#productos").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_productos/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#clientes").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_clientes/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#proveedores").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_proveedores/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#bancos").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_bancos/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#estadisticas").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_estadisticas/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#saldos").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_saldos/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#movimientos").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_movimientos/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#cuentas").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_cuentas/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#libro").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_libro/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#inventario").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_inventario/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#auditoria").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_auditoria/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#usuarios").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_usuarios/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#facturas_almacen").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_facturas_almacen/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
				});
				
				$("#pedidos_almacen").click(function(){
					$.post("<?php echo base_url(); ?>index.php/ayuda/ControllersAyudaModulos/ayuda_pedidos_almacen/", function(htmlexterno){
						$("#modal_content").html(htmlexterno);
					});
					$("#ayudas").modal('show');
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

    </head>
    <body>

        <?php if ($tipouser == 'Administrador' ): ?>
		<div class="container" style="width: 95%">
			<div class="row-fluid text-center">

				<div class="mainbody-section text-center">
					<div class="container">
						<div class="row">
							
							<div class="col-md-3">
								<div class="menu-item blue_dark">
									<a id="facturas" data-toggle="modal">
										<i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Facturación</p>
									</a>
								</div>
								<div class="menu-item brown">
									<a id="productos" data-toggle="modal">
										<i class="glyphicon glyphicon-th-list"></i>&nbsp;<i class="glyphicon glyphicon-question-sign"></i>
										<p>Productos</p>
									</a>
								</div>
								<div class="menu-item esmeralda">
									<a id="estadisticas" data-toggle="modal">
										<i class="glyphicon glyphicon-stats"></i>&nbsp;<i class="glyphicon glyphicon-question-sign"></i>
										<p>Estadísticas</p>
									</a>
								</div>
								<div class="menu-item concreto">
									<a id="libro" data-toggle="modal">
										<i class="glyphicon glyphicon-book"></i>&nbsp;<i class="glyphicon glyphicon-question-sign"></i>
										<p>Libro de Ventas</p>
									</a>
								</div>
								
							</div>
							<div class="col-md-3">
								<div class="menu-item purple">
									<a id="presupuestos" data-toggle="modal">
										<i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Presupuesto</p>
									</a>
								</div>
								<div class="menu-item red">
									<a id="clientes" data-toggle="modal">
										<i class="glyphicon glyphicon-export"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Clientes</p>
									</a>
								</div>
								<div class="menu-item verde_pastel">
									<a id="saldos" data-toggle="modal">
										<i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Saldos</p>
									</a>
								</div>
								<div class="menu-item amarillo">
									<a id="inventario" data-toggle="modal">
										<i class="glyphicon glyphicon-list"></i>&nbsp;<i class="glyphicon glyphicon-question-sign"></i>
										<p>Inventario</p>
									</a>
								</div>
					
							</div>
							<div class="col-md-3">
								<div class="menu-item orange">
									<a id="ajustes" data-toggle="modal">
										<i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Ajustes</p>
									</a>
								</div>
								<div class="menu-item light-orange">
									<a id="proveedores" data-toggle="modal">
										<i class="glyphicon glyphicon-import"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Proveedores</p>
									</a>
								</div>
								<div class="menu-item gris">
									<a id="movimientos" data-toggle="modal">
										<i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Movimientos</p>
									</a>
								</div>
								<div class="menu-item plateado">
									<a id="auditoria" data-toggle="modal">
										<i class="glyphicon glyphicon-eye-open"></i>&nbsp;<i class="glyphicon glyphicon-question-sign"></i>
										<p>Auditoria</p>
									</a>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="menu-item morado">
									<a id="pedidos" data-toggle="modal">
										<i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Pedidos</p>
									</a>
								</div>
								<div class="menu-item green_dark">
									<a id="bancos" data-toggle="modal">
										<i class="glyphicon glyphicon-credit-card"></i>&nbsp;<i class="glyphicon glyphicon-question-sign"></i>
										<p>Bancos</p>
									</a>
								</div>
								<div class="menu-item green">
									<a id="cuentas" data-toggle="modal">
										<i class="glyphicon glyphicon-credit-card"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Cuentas</p>
									</a>
								</div>
								<div class="menu-item blue">
									<a id="usuarios" data-toggle="modal">
										<i class="glyphicon glyphicon-user"></i><i class="glyphicon glyphicon-question-sign"></i>
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
									<a id="facturas_almacen" data-toggle="modal">
										<i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i>
										<p>Facturas</p>
									</a>
								</div>
							</div>
							<div class="col-md-3">
								<div class="menu-item morado">
									<a id="pedidos_almacen" data-toggle="modal">
										<i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i>
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
        
        

		<!-- Modal para la visualización de la información de ayuda de los diferenctes módulos -->
		  <div class="modal fade bd-example-modal-lg" id="ayudas" role="document">
			<div class="modal-dialog modal-lg">
			
			  <!-- Modal content-->
			  <div id="modal_content" class="modal-content">
								  
			  </div>
			  
			</div>
		  </div>
		<!--Fin del contenedor para información de ayuda -->
		

    </body>
</html>


</body>
</html>
