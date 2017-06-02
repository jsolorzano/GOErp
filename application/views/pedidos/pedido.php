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
        
	var Tpro = $('#tab_pedido').dataTable({
		"iDisplayLength": 5,
		"iDisplayStart": 0,
		"sPaginationType": "full_numbers",
		"aLengthMenu": [5, 10],
		"oLanguage": {"sUrl": "<?= base_url() ?>static/js/es2.txt"},
		"aoColumns": [
			{"sClass": "registro center", "sWidth": "8%"},
			//~ {"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "registro center", "sWidth": "15%"},
			{"sClass": "registro center", "sWidth": "15%"},
			{"sClass": "registro center", "sWidth": "15%"},
		]
	});

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
	$('#proveedor').alpha({allow: " "});
	$('#cantidad').numeric();

	$("select").select2();
	
	$('#volver').click(function () {
		url = '<?php echo base_url() ?>index.php/pedidos/ControllersPedidos/'
		window.location = url
	})

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Al cargar la página, mostrar en la lista de productos aquellos con o sin existencia en su stock.
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//~ $('#id_servicio').find('option:gt(0)').remove().end().select2('val', '0');
	//~ $.get('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/producto/', function (data) {
		//~ alert(data);
		//~ var option = "";
		//~ $.each(data, function (i) {
			//~ option += "<option value=" + data[i]['codigo'] + ">" + data[i]['nombre'] + "</option>";
		//~ });
		//~ $('#id_servicio').append(option);
//~ 
	//~ }, 'json');


	$('#id_servicio').change(function () {

		$.post('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/datos_ps/', {'tabla':'producto', 'campo':'codigo', 'valor':$("#id_servicio").val()}, function (data) {
			
			// Verificamos si la disponibilidad del producto es menor al mínimo establecido para alertar al usuario
			if (parseInt(data['existencia']) < parseInt(data['stock_min'])){
				bootbox.alert("La disponibilidad del producto es menor al mínimo estipulado", function () {
				}).on('hidden.bs.modal', function (event) {
				});
			}
			
			// Cargamos los datos necesarios del producto
			$('#exist').val(data['existencia']);  // Para un registro
			$('#span_exist').text("Disp. "+data['existencia']);  // Para un registro
			$('#min').val(data['stock_min']);  // Para un registro

		}, 'json');

	});
	

	$("#i_new_line").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		$("#modal_servicios").modal('show');
	});

	$("#agregar").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		
		if ($('#id_servicio').val() == null || $('#id_servicio').val() == 0) {
			bootbox.alert("Seleccione el Producto", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#id_servicio").parent('div').addClass('has-error')
				  $('#id_servicio').select2('val', '0');
				$("#id_servicio").focus();
			});
		} else if ($('#cantidad').val().trim() == '' || $('#cantidad').val().trim() == 0) {
			bootbox.alert("Introdúzca la Cantidad", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#cantidad").parent('div').addClass('has-error')
				$('#cantidad').val('')
				$("#cantidad").focus();
			});

		} else {
			
			var id = $("#id_servicio").val();
			var tipo = "Producto";
			var producto = $("#id_servicio").find('option').filter(':selected').text();
			var cantidad = parseFloat($("#cantidad").val());
	  
			if( id!='' & producto!='' & cantidad!='' ){
				var detalle = new Array();
				var obj = 	{
				'id':id,
				'tipo':tipo,
				'producto':producto,
				'cantidad':cantidad.toFixed(2)
				};
				console.log(obj);
													
				var aPos = Tpro.fnGetPosition(this.parentNode.parentNode);
				
				var botonQuitar = "<a class='quitar'><i class='glyphicon glyphicon-trash'></i></a>";
							
				var newRow = Tpro.fnAddData([id,producto,cantidad,botonQuitar]);

			}else{
				console.log("No se admite campos vacios");
			}

		   $("#modal_servicios").modal('hide');
		   //~ $('#tipo').select2('val', '0');
		   $('#id_servicio').select2('val', '0');
		   $('#cantidad').val('');
	   }

	});
	
	// Función para quitar un elemento de la lista y recalcular los montos de la factura
	$("table#tab_pedido").on('click', 'a.quitar', function (e) {
		//~ alert("alert");
		var aPos = Tpro.fnGetPosition(this.parentNode.parentNode);
		Tpro.fnDeleteRow(aPos);
	} );


	$("#pedir").click(function (e) {
		
		if ($('#proveedor').val().trim() == '' || $('#proveedor').val().trim() == 'Seleccione') {
			bootbox.alert("Seleccione o indique un proveedor", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#proveedor").parent('div').addClass('has-error')
				$('#proveedor').val('')
				$("#proveedor").focus();
			});
		}else{		

			// Armar data de los productos
			var campos= "";
			var data = [];
			$("#tab_pedido tbody tr").each(function (index){
			var campo1, campo2, campo3;
			var campo4;
			campo4 = String($("#codpedido").val().trim());
			
				$(this).children("td").each(function (index2) 
				{
					switch (index2) 
					{
						case 0: 
							campo1 = $(this).text();
							break;
						case 1: 
							campo2 = $(this).text();
							break;
						case 2:
							campo3 = $(this).text();
							break;
					}
					$(this).css("background-color", "#ECF8E0");
				})               

				campos = {"id" : campo1, "id_servicio" : campo2, "cantidad" : campo3, "cod_pedido" : campo4},
				data.push(campos);                
				
			})
			console.log(data);
			if(data[0]['id'] != "Ningún dato disponible en esta tabla"){
				$.post('<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos/guardar/', $('#form_pedido').serialize()+'&'+$.param({'data':data}), function (response) {
					//~ alert($('#form_pedido').serialize());

					bootbox.alert("Se registró con exito", function () {
					}).on('hidden.bs.modal', function (event) {
						url = '<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos'
						window.location = url
					});

				});
			}else{
				bootbox.alert("Complete la carga de productos", function () {
				}).on('hidden.bs.modal', function (event) {
					
				});
			}
		}
	});
	
	 $("#modal_proveedor").modal('show');
	 
	 $("#hola").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
				

		var proveedor = $("#id_proveedor").find('option').filter(':selected').text();
		$("#codproveedor").val($('#id_proveedor').val());
		$("#proveedor").val(proveedor);
		$("#modal_proveedor").modal('hide');
			
	 });
	 
	// Función para registrar un nuevo producto en una modal
	$("#nuevo_producto").on('click', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		
		//~ alert(cod);
		
		//~ return true;
		
		URL = '<?php echo base_url()?>index.php/productos/ControllersProductos/registrar';
		$.fancybox.open({ padding : 0, href: URL, type: 'iframe',width: 1024, height: 860, });
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


<div class="modal" id="modal_servicios">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <span class="glyphicon glyphicon-search"></span>
                    &nbsp;  Buscar Producto
                </h4>
            </div>
            <div class="modal-body">
                <form name="f_nueva_venta" action="" method="post" class="form">
                    <p>
                        <label>Producto:</label>  
                        <select id="id_servicio" name="id_servicio" class="form-control" >
                            <option value="0" selected="">Seleccione</option>
                            <?php foreach ($productos as $producto) { ?>
								<option value="<?php echo $producto->codigo?>"><?php echo $producto->nombre?></option>
							<?php }?>
                        </select>
                    </p>
                    <p>
                        <label>Cantidad: (<span id='span_exist' style="width:20%"></span>)</label><br clear="all"> <input type="text" style="width:100%"  class="form-control" id="cantidad" name="cantidad">
                        <input type="hidden" style="width:100%"  class="form-control" id="exist">
                        <input type="hidden" style="width:100%"  class="form-control" id="min">
                    </p>

                    <div class="input-group">

                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" id="agregar">
                                Aceptar
                            </button>
                        </span>
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
                <form id="form_pedido" class="form" method="post">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
<!--
                                <legend>Pedido (
                                <?php
									// Construcción del código de la factura para mostrar en el encabezado (Todo lo comentado es la versión anterior)
									//~ $pre_cod = "00";
									if((int)$ultimo_id + (int)1 == 1){
										//~ printf("$pre_cod %08d", (int)$ultimo_id + (int)1);
										printf("%08d", (int)$ultimo_id + (int)1);
									}else{
										//~ $c_pedido = (int)substr($cod_pedido->codpedido, 2);
										$c_pedido = (int)$cod_pedido->codpedido;
										//~ $pre_cod = $cod_pedido->pre_cod_factura;
										
										if ($c_pedido + (int)1 > 99999999){
											//~ printf("%02d %08d",($pre_cod+1), 1);
											printf("%08d", 1);
										}else{
											printf("%08d", $c_pedido + (int)1);
										}
									}
								?>
                                )</legend>
-->
                                <input type="hidden" id="codpedido" name="codpedido" value="
                                <?php
									// Construcción del código de la factura para mostrar en el campo de cod_factura (Todo lo comentado es la versión anterior)
									//~ $pre_cod = "00";
									if((int)$ultimo_id + (int)1 == 1){
										//~ printf(trim("$pre_cod%08d"), (int)$ultimo_id + (int)1);
										printf(trim("%08d"), (int)$ultimo_id + (int)1);
									}else{
										//~ $cod_pedido = (int)$cod_pedido->codpedido;
										if ($c_pedido + (int)1 > 99999999){
											//~ $pre_cod = (int)$cod_pedido->pre_cod_factura + (int)1;
											//~ printf("%02d%08d",$pre_cod, 1);
											printf("%08d", 1);
										}else{
											//~ $pre_cod = (int)$cod_pedido->pre_cod_factura;
											//~ printf("%02d%08d",$pre_cod, $c_pedido + (int)1);
											printf("%08d", $c_pedido + (int)1);
										}
									}
								?>
                                ">
                                <input type="hidden" id="pre_cod_factura" name="pre_cod_factura" value="
                                <?php
									// Construcción del pre-código de la factura para mostrar en el campo de pre_cod_factura (Todo lo comentado es la versión anterior)
									//~ $pre_cod = "00";
									//~ if((int)$ultimo_id + (int)1 == 1){
										//~ print trim($pre_cod);
									//~ }else{
										//~ if ((int)$c_pedido + (int)1 > 99999999){
											//~ $pre_cod = (int)$cod_pedido->pre_cod_factura + (int)1;
											//~ printf("%02d", $pre_cod);
										//~ }else{
											//~ $pre_cod = (int)$cod_pedido->pre_cod_factura;
											//~ printf("%02d", $pre_cod);
										//~ }
									//~ }
								?>
                                ">
                                
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-5">
								<div style="margin-left: 0.2%;margin-bottom: -1%;" class="form-group">
									<span >Código y nombre del proveedor</span>
									<div class="input-group mar-btm">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" style="width: 20%;" readonly="true" name="codproveedor" id="codproveedor" class="form-control">
										<input type="text" style="width: 80%;" name="proveedor" id="proveedor" placeholder="proveedor" class="form-control">
										<span class="input-group-btn">
											<button type="button" data-toggle="modal" data-target="#modal_proveedor" class="btn btn-primary" type="submit" id="modal_proveedor">
												<i class="glyphicon glyphicon-search"></i>
											</button>
										</span>
									</div>
								</div>
                            </div>
                            <div class="col-sm-2">
								<span >Fecha de emisión</span>
								<div class="input-group mar-btm">
									<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
									<input type="text" data-original-title="Fecha Emision" value="<?php echo date('d-m-Y'); ?>" data-toggle="tooltip" data-placement="bottom" readonly="true" name="fecha_emision" id="fecha_emision" placeholder="Fecha Emisión" class="form-control add-tooltip">
								</div>
                            </div>
<!--
                            <div class="col-sm-3">
								<span >Condición de pago</span>
								<div class="input-group mar-btm">
									<select id="condicion_pago" name="condicion_pago" style="width:100%;" class="form-control">
										<option value="1">Cheque</option>
										<option value="2">Debito</option>
										<option value="3">Efectivo</option>
										<option value="4">Transferencia</option>
										<option value="5">Deposito</option>
									</select>
								</div>
                            </div>
-->
                            
                        </div>
                        
                    </div>
                    <br/>
                    
                    <div role="tabpanel">
                        <ul class="nav nav-tabs" role="tablist">
                        </ul>
                        <br/>
                        <div class="tab-content">
                            <div class="row"></div>
                            <div class="form-group col-xs-4">
                                <button  class="btn btn-primary btn-labeled" id="i_new_line"><i class="glyphicon glyphicon-plus"></i>&nbsp;Agregar Producto</button>
                                &nbsp;&nbsp;&nbsp;
<!--
                                <button class="btn btn-primary btn-labeled" id="nuevo_producto"><i class="glyphicon glyphicon-plus"></i>&nbsp;Nuevo Producto</button>
-->
                            </div>
                            <br/>
                            <br/>
                            <br/>

                            <input type="hidden" id="cant_row" value="1"/>
                            
                             <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_pedido" align="center"
								   class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
								<thead style="font-size: 14px">
									<tr class="info">
										<th style='text-align: center'>Item</th>
										<!--<th style='text-align: center'>Tipo</th>-->
										<th style='text-align: center'>Producto</th>
										<!--<th style='text-align: center'>Precio</th>-->
										<!--<th style='text-align: center'>Monto IVA</th>-->
										<th style='text-align: center'>Cantidad</th>
										<!--<th style='text-align: center'>Importe</th>-->
										<th style='text-align: center'>Quitar</th>
									</tr>
								</thead>
								<tbody >    
								  
								</tbody>
							</table>

                        </div>
                    </div>
                    </br>

                    <div class="container-fluid" style="margin-top: 10px;">
                        <div class="row">
                            <div class="col-sm-12">
								<input type="hidden" id="num_product" placeholder="Número de productos">
                                <div class="form-group">
                                    Observaciones:
                                    <textarea class="form-control" name="observaciones" rows="3"></textarea>
                                </div>
                            </div>
                            <br/>
                            <div class="col-sm-12 text-center">
								<button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning" >
								&nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
								</button>
                                <button type="button" id="pedir" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
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
