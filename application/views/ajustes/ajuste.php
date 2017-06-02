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
	$('#base_imponible').numeric({allow: "."});
	$('#monto_exento').numeric({allow: "."});

	$("select").select2();
	
	$('#volver').click(function () {
		url = '<?php echo base_url() ?>index.php/ajustes/ControllersAjustes/'
		window.location = url
	})


	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Al cambiar de option en el combo tipo (producto o servicio)
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//~ $('#tipo_ajuste').change(function () {
//~ 
		//~ if ($('#tipo').val() == '1') {
			//~ $('#id_servicio').find('option:gt(0)').remove().end().select2('val', '0');
			//~ $.get('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/productos_existencia/', function (data) {
				//~ var option = "";
				//~ $.each(data, function (i) {
					//~ option += "<option value=" + data[i]['codigo'] + ">" + data[i]['nombre'] + "</option>";
				//~ });
				//~ $('#id_servicio').append(option);
//~ 
			//~ }, 'json');
//~ 
		//~ } else {
			//~ $('#id_servicio').find('option:gt(0)').remove().end().select2('val', '0');
			//~ $.get('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/servicios/', function (data) {
				//~ var option = "";
				//~ $.each(data, function (i) {
					//~ option += "<option value=" + data[i]['codigo'] + ">" + data[i]['descripcion'] + "-" + data[i]['tipo_servicio'] + "</option>";
				//~ });
				//~ $('#id_servicio').append(option);
//~ 
			//~ }, 'json');
//~ 
		//~ }
	//~ });		
		
	//Método para el envío de datos 
	$("#consumir").click(function (e) {
		
		if ($('#subtotal').val().trim() == '' || $("#totalajuste").val().trim() == '' || $('#subtotal').val().trim() == 0 || $("#totalajuste").val().trim() == 0) {
			bootbox.alert("Complete la carga de los montos", function () {
			}).on('hidden.bs.modal', function (event) {
				
			});
		}else if($('#concepto').val().trim() == '' || $('#concepto').val().trim() == 0){
			bootbox.alert("Especifique el concepto del ajuste", function () {
			}).on('hidden.bs.modal', function (event) {
				$('#concepto').val('');
				$('#concepto').focus();
			});
		}else{
			
			if ($("#tipo_ajuste").val() == '2'){
				// Cuando sea nota de debito se valida que el monto del ajuste no sea mayor al total de la factura
				if(parseFloat($("#totalajuste").val()) <= parseFloat($("#totalfactura").val())){
					$.post('<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes/guardar/', $('#form_ajustes').serialize(), function (response) {

						bootbox.alert("Se registró con exito", function () {
						}).on('hidden.bs.modal', function (event) {
							url = '<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes'
							window.location = url
						});

					});
				}else{
					bootbox.alert("El ajuste es superior al monto de la factura", function () {
					}).on('hidden.bs.modal', function (event) {
						$("#base_imponible").parent('div').addClass('has-error')
						$("#monto_exento").parent('div').addClass('has-error')
					});
				}
			}else{
				// Cuando sea nota de credito se registra el ajuste sin validaciones extra
				$.post('<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes/guardar/', $('#form_ajustes').serialize(), function (response) {

					bootbox.alert("Se registró con exito", function () {
					}).on('hidden.bs.modal', function (event) {
						url = '<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes'
						window.location = url
					});

				});
			}
		
		}
	});
        
        
	$("#modal_factura").modal('show');
	//~
		 
	$("#hola").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto

		var factura = $("#id_factura").find('option').filter(':selected').text();
		factura = factura.split('-')
		$("#codfactura").val($('#id_factura').val());
		$("#cliente").val(factura[1]);
		$("#modal_factura").modal('hide');
		
		// Procedimiento para la carga de los datos de la factura seleccionada
		$.post('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/datos_ps/', {'tabla':'facturas', 'campo':'codfactura', 'valor':$("#codfactura").val()}, function (data) {
			//~ console.log(data)
			//~ alert(data);
			
			// Procedimiento para la carga del rif del cliente de la factura
			$.post('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/datos_ps/', {'tabla':'cliente', 'campo':'codigo', 'valor':data['codcliente']}, function (data_c) {
				$('#rifcliente').val(data_c['tipocliente']+"-"+data_c['cirif']);  // Para un registro
			}, 'json');
			
			// Cargamos los datos necesarios de la factura
			$('#totalfactura').val(data['totalfactura']);

		}, 'json');
	});
       

	// Función para el cálculo del iva, subtotal y ajuste total
	$('#iva,#base_imponible,#monto_exento').change(function () {
		var bi = $("#base_imponible").val();
		var be = $("#monto_exento").val();
		var iva = $('#iva').find('option').filter(':selected').text();
        iva = iva.split("%");
        iva = iva[0];
        
        // Reasignamos valores de ser necesario
        if(bi == ''){
			bi = 0;
		}
		
		if(be == ''){
			be = 0;
		}
		
		// Cálculo del IVA
		$("#monto_iva").val((parseFloat(bi)*parseFloat(iva))/100);  // Calculamos el iva
		
		// Cálculo del subtotal
		$("#subtotal").val(parseFloat(bi)+parseFloat($("#monto_iva").val()));  // Calculamos el subtotal
		
		// Cálculo del Total
		$("#totalajuste").val(parseFloat($("#subtotal").val())+parseFloat(be));  // Cargamos el total en el campo oculto para guardarlo en base de datos
		
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

<div class="modal" id="modal_factura">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
               <span class="glyphicon glyphicon-search"></span>
               &nbsp;  Busca y selecciona la factura 
            </h4>
         </div>
         <div class="modal-body">
            <form name="f_nueva_venta" action="" method="post" class="form">
               <input type="hidden" name="ente"/>
               <div class="form-group">
                  <div class="input-group">
                    <select id="id_factura" name="factura" class="form-control select2 input-sm" >
						<?php foreach ($listar as $factura) { ?>
							<option value="<?php echo $factura->codfactura?>"><?php echo $factura->codfactura?>-<?php echo $factura->cliente?></option>
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
                <form id="form_ajustes" class="form" method="post">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
<!--
                                <legend>Ajuste (
                                <?php
									// Construcción del código de la factura para mostrar en el encabezado (Todo lo comentado es la versión anterior)
									//~ $pre_cod = "00";
									if((int)$ultimo_id + (int)1 == 1){
										//~ printf("$pre_cod %08d", (int)$ultimo_id + (int)1);
										printf("%08d", (int)$ultimo_id + (int)1);
									}else{
										//~ $c_factura = (int)substr($cod_autoconsumo->codautoconsumo, 2);
										$cod_ajuste = (int)$cod_ajuste->codajuste;
										//~ $pre_cod = $cod_factura->pre_cod_factura;
										
										if ($cod_ajuste + (int)1 > 99999999){
											//~ printf("%02d %08d",($pre_cod+1), 1);
											printf("%08d", 1);
										}else{
											printf("%08d", $cod_ajuste + (int)1);
										}
									}
								?>
                                )</legend>
-->
                                <input type="hidden" id="codajuste" name="codajuste" value="
                                <?php
									// Construcción del código de la factura para mostrar en el campo de cod_factura (Todo lo comentado es la versión anterior)
									//~ $pre_cod = "00";
									if((int)$ultimo_id + (int)1 == 1){
										//~ printf(trim("$pre_cod%08d"), (int)$ultimo_id + (int)1);
										printf(trim("%08d"), (int)$ultimo_id + (int)1);
									}else{
										//~ $cod_factura = (int)$cod_factura->codfactura;
										if ($cod_ajuste + (int)1 > 99999999){
											//~ $pre_cod = (int)$cod_factura->pre_cod_factura + (int)1;
											//~ printf("%02d%08d",$pre_cod, 1);
											printf("%08d", 1);
										}else{
											//~ $pre_cod = (int)$cod_factura->pre_cod_factura;
											//~ printf("%02d%08d",$pre_cod, $c_factura + (int)1);
											printf("%08d", $cod_ajuste + (int)1);
										}
									}
								?>
                                ">
                                
                            </div>

                        </div>
                        
                        <div class="row">
                            <div class="col-sm-5">
								<div style="margin-left: 0.2%;margin-bottom: -1%;" class="form-group">
									<span >Código de factura y nombre del cliente</span>
									<div class="input-group mar-btm">
										<span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
										<input type="text" style="width: 18%;" name="codfactura" id="codfactura" class="form-control" readonly="true">
										<input type="text" style="width: 22%;" name="rifcliente" id="rifcliente" placeholder="RIF Cliente" class="form-control" readonly="true">
										<input type="text" style="width: 60%;" name="cliente" id="cliente" placeholder="Cliente" class="form-control" readonly="true">
										<span class="input-group-btn">
											<button type="button" data-toggle="modal" data-target="#modal_factura" class="btn btn-primary" type="submit" id="modal_ente">
												<i class="glyphicon glyphicon-share-alt"></i>
											</button>
										</span>
									</div>
								</div>
                            </div>
                            <div class="col-sm-2">
								<span >Fecha de emisión</span>
								<div class="input-group mar-btm">
										<span class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></span>
										<input type="text" data-original-title="Fecha Emision" value="<?php echo date('d-m-Y'); ?>" data-toggle="tooltip" data-placement="bottom" readonly="true" name="fecha_ajuste" id="fecha_ajuste" placeholder="Fecha Emisión" class="form-control add-tooltip">
								</div>
                            </div>
							<div class="col-sm-1">
								<span >IVA</span>
								<div class="input-group mar-btm">
									<select id="iva" name="iva" class="form-control select2 input-sm">
										<?php foreach ($list_iva as $iva) { ?>
											<option value="<?php echo $iva->id?>"><?php echo $iva->valor?>%</option>
										<?php }?>
									</select>
								</div>
                            </div>
                            <div class="col-sm-2">
								<span >Tipo de ajuste</span>
								<select id="tipo_ajuste" name="tipo_ajuste" class="form-control select2 input-sm" style="width:100%;">
									<option value="1">Nota de crédito</option>
									<option value="2">Nota de débito</option>
								</select>
                            </div>
                            
                        </div>
                        </br></br>
                        
                        <div class="row">
                            <div class="col-sm-2">
								<span >Base Imponible</span>
								<div class="input-group mar-btm">
									<input type="text" style="width: 100%;" name="base_imponible" id="base_imponible" placeholder="000.00" class="form-control">
								</div>
                            </div>
                            <div class="col-sm-2">
								<span >Monto IVA</span>
								<div class="input-group mar-btm">
									<input type="text" style="width: 100%;" id="monto_iva" name="monto_iva" class="form-control" readonly="true">
								</div>
                            </div>
							<div class="col-sm-2">
								<span >Monto Exento</span>
								<div class="input-group mar-btm">
									<input type="text" style="width: 100%;" id="monto_exento" name="monto_exento" placeholder="000.00" class="form-control">
								</div>
                            </div>
                            <div class="col-sm-2">
								<span >Sub-total</span>
								<div class="input-group mar-btm">
									<input type="text" style="width: 100%;" id="subtotal" name="subtotal" placeholder="000.00" class="form-control" readonly="true">
								</div>
                            </div>
                            <div class="col-sm-2">
								<span >Ajuste Total</span>
								<input type="text" style="width: 100%;" id="totalajuste" name="totalajuste" placeholder="000.00" class="form-control" readonly="true">
                            </div>
                            
                        </div>
                        
                    </div>
                    <br/>
                    </br>

                    <div class="container-fluid" style="margin-top: 10px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    Concepto:
                                    <textarea class="form-control" id="concepto" name="concepto" rows="3" style="width:100%"></textarea>
                                    <input type="hidden" id="totalfactura">
                                </div>
                            </div>
                            <br/>
                            <div class="col-sm-12 text-center">
								<button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning" >
								&nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
								</button>
                                <button type="button" id="consumir" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Guardar
                                </button>
                            </div>

                        </div>
                    </div>
				</form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
