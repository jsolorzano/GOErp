<!--// si el usuario no esta logueado lo envia al logueo //-->
<?php
if (isset($this->session->userdata['logged_in'])) {
    $username = ($this->session->userdata['logged_in']['username']);
    $email = ($this->session->userdata['logged_in']['email']);
    $id = ($this->session->userdata['logged_in']['id']);
} else {
    header("location: ../../");
}
?>
<script type="text/javascript">


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
	
	// Validaciones de tipos de datos
	$('#monto').numeric({allow: "."});

	$("select").select2();
	
	// Carga de datos de los campos de selección
	$("#condicion_pago").select2('val', $("#id_condicion_pago").val());
	$("#cuenta_origen").select2('val', $("#id_cuenta_origen").val());
	
	$('#volver').click(function () {
		url = '<?php echo base_url() ?>index.php/pago_proveedores/ControllersPagoProveedores/'
		window.location = url
	})

	$("#pagar").click(function (e) {
		
		if ($('#proveedor').val().trim() == '' || $('#proveedor').val().trim() == 'Seleccione') {
			bootbox.alert("Seleccione o indique un proveedor", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#proveedor").parent('div').addClass('has-error')
				$('#proveedor').val('')
				$("#proveedor").focus();
			});
		}else if ($('#monto').val().trim() == '') {
			bootbox.alert("Indique el monto", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#monto").parent('div').addClass('has-error')
				$('#monto').val('');
				$("#monto").focus();
			});
		}
		else if ($('#cuenta_origen').val() == '') {
			bootbox.alert("Seleccione la cuenta de origen", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#cuenta_origen").parent('div').addClass('has-error')
				$("#cuenta_origen").focus();
			});
		}else{		
			
			$.post('<?php echo base_url(); ?>index.php/pago_proveedores/ControllersPagoProveedores/actualizar/', $('#form_pago').serialize(), function (response) {
				//~ alert($('#form_pago').serialize());

				bootbox.alert("Se actualizó con exito", function () {
				}).on('hidden.bs.modal', function (event) {
					url = '<?php echo base_url(); ?>index.php/pago_proveedores/ControllersPagoProveedores'
					window.location = url
				});

			});
		}
	});
	
	 //~ $("#modal_proveedor").modal('show');
	 
	 $("#hola").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		
		var proveedor = $("#id_proveedor").find('option').filter(':selected').text();
		$("#codproveedor").val($('#id_proveedor').val());
		$("#proveedor").val(proveedor);
		$("#modal_proveedor").modal('hide');
			
	 });

});

	// FUNCIONES EXTRA
	function hora() {
		var fecha = new Date()
		var hora = fecha.getHours()
		var minuto = fecha.getMinutes()
		var segundo = fecha.getSeconds()
		if (hora < 10) {
			hora = "0" + hora
		}
		if (minuto < 10) {
			minuto = "0" + minuto
		}
		if (segundo < 10) {
			segundo = "0" + segundo
		}
		var horita = hora + ":" + minuto + ":" + segundo
		document.getElementById('hora').firstChild.nodeValue = horita
		tiempo = setTimeout('hora()', 1000)
	}
            
        
    function inicio() {
        document.write('<span id="hora">')
        document.write('000000</span>')
        hora()
    }

    function date() {
        document.write('<span id="fecha">')
        document.write('000000</span>')
        horita()
    }
    
</script>
<div class="modal" id="modal_proveedor">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
               <span class="glyphicon glyphicon-search"></span>
               &nbsp;  Busca y selecciona el proveedor 
            </h4>
         </div>
         <div class="modal-body">
            <form name="f_nueva_venta" action="" method="post" class="form">
               <input type="hidden" name="proveedor"/>
               <div class="form-group">
                  <div class="input-group">
                    <select id="id_proveedor" name="proveedor" class="form-control select2 input-sm" >
						<!--<option value="">Seleccione</option>-->

						<?php foreach ($listar as $proveedor) { ?>
							<option value="<?php echo $proveedor->codigo?>"><?php echo $proveedor->nombre?></option>
						<?php }?>

					</select>
         <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="hola">
                           <span class="glyphicon glyphicon-share-alt"></span>
                        </button>
                     </span>
                  </div>
               </div>
            </form>
         </div>
         
      </div>
   </div>
</div>

<div class="container" style="width:90%;">
    <div class="row">
        <div class="col-lg-12">
            <div class="well bs-component">
                <form id="form_pago" class="form" method="post">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
<!--
                                <legend>Pago a Proveedores (
                                <?php
									echo $editar->codpago;
								?>
                                )</legend>
-->
                                <input type="hidden" id="codpago" name="codpago" value="
                                <?php
									echo $editar->codpago;
								?>
								">
                                
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-4">
								<div style="margin-left: 0.2%;margin-bottom: -1%;" class="form-group">
									<span >Código y nombre del proveedor</span>
									<div class="input-group mar-btm">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" style="width: 20%;" readonly="true" name="codproveedor" id="codproveedor" class="form-control" value="<?php echo $editar->codproveedor; ?>">
										<input type="text" style="width: 80%;" name="proveedor" id="proveedor" placeholder="Proveedor" class="form-control" value="<?php echo $editar->proveedor; ?>">
										<span class="input-group-btn">
											<button type="button" data-toggle="modal" data-target="#modal_proveedor" class="btn btn-primary" type="submit" id="modal_proveedor">
												<i class="glyphicon glyphicon-search"></i>
											</button>
										</span>
									</div>
								</div>
                            </div>
                            <div class="col-sm-4">
								<span >Fecha de emisión</span>
								<div class="input-group mar-btm">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input type="text" data-original-title="Fecha de Pago" value="<?php echo $editar->fecha_pago; ?>" data-toggle="tooltip" data-placement="bottom" readonly="true" name="fecha_pago" id="fecha_pago" placeholder="Fecha de Pago" class="form-control add-tooltip">
								</div>
                            </div>
                            <div class="col-sm-4">
								<span >Condición de pago</span>
								<div>
									<select id="condicion_pago" name="condicion_pago" style="width:100%;" class="form-control">
										<option value="1">Cheque</option>
										<option value="2">Debito</option>
										<option value="3">Efectivo</option>
										<option value="4">Transferencia</option>
										<option value="5">Deposito</option>
									</select>
								</div>
                            </div>
                            
                        </div>
                        </br>
                        
                        <div class="row">
                            <div class="col-sm-4">
								<span >Num Factura</span>
								<div>
									<input type="text" style="width:100%;" data-original-title="Número de factura" name="num_factura" id="num_factura" value="<?php echo $editar->num_factura; ?>" class="form-control" maxlength="20">
								</div>
                            </div>
                            <div class="col-sm-4">
								<span >Monto</span>
								<div>
									<input type="text" style="width:100%;" data-original-title="Monto" name="monto" id="monto" value="<?php echo $editar->monto; ?>" class="form-control">
								</div>
                            </div>
                            <div class="col-sm-4">
								<span >Cuenta Origen</span>
								<div>
									<select id="cuenta_origen" name="cuenta_origen" class="form-control input-sm">
										<option value="">Seleccione</option>
										<?php foreach ($cuentas as $cuenta) { ?>
											<option value="<?php echo $cuenta->codigo?>">
											<?php 
											foreach ($bancos as $banco) {
												if($banco->cod_banco == $cuenta->cod_banco){
													echo $banco->banco;
												}
											}
											?>
											 - <?php echo $cuenta->cuenta?>
											</option>
										<?php }?>
									</select>
								</div>
                            </div>
                            
                        </div>
                        
                    </div>
                    </br>

                    <div class="container-fluid" style="margin-top: 10px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    Observaciones:
                                    <textarea class="form-control" name="observaciones" rows="3"><?php echo $editar->observaciones; ?></textarea>
                                </div>
                            </div>
                            <br/>
                            <div class="col-sm-12 text-center">
								<button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning" >
								&nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
								</button>
                                <button type="button" id="pagar" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Guardar
                                </button>
                            </div>
                            <input id="id_condicion_pago" type="hidden" value="<?php echo $editar->condicion_pago; ?>"/>
							<input id="id_cuenta_origen" type="hidden" value="<?php echo $editar->cuenta_origen; ?>"/>

                        </div>
                    </div>
				
				</form>
       
            </div>
        </div>
    </div>
</div>
</body>
</html>
