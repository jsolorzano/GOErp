<html>
<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
$tipouser = ($this->session->userdata['logged_in']['tipo_usuario']);
} else {
redirect(base_url());
}
?>

<?php if ($tipouser == 'Ventas' || $tipouser == 'Administrador'){
	
 } else {
	 redirect(base_url());
 }?>     
        
 <script>
$(document).ready(function(){
    var Tfacturas = $('#tab_factura').dataTable({
        "iDisplayLength": 10,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [6,8,10],
        //~ dom: 'Bfrtip',
        //~ buttons: [
			//~ {
				//~ extend: 'print',
				//~ text: 'Imprimir',
				//~ autoPrint: true
			//~ }
		//~ ],
        "oLanguage": {"sUrl": "<?= base_url() ?>static/js/es.txt"},
        "aoColumns": [
            {"sClass": "registro center", "sWidth": "3%"},
            {"sClass": "registro center", "sWidth": "8%"},
            {"sClass": "registro center", "sWidth": "8%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    $('#enviar').click(function(){
        url = '<?php echo base_url()?>index.php/factura/ControllersFacturar/factura'
        window.location = url
    })
	
	// Función para preparar la anulación de una factura
	$("table#tab_factura").on('click', 'input.anular', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		//alert(id)
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'anular';
        }else{
			accion = 'activar';
		}
		
		//~ var padre = $(this).closest('tr');
		//~ var nRow  = padre[0];
		bootbox.confirm("¿Desea "+accion+" la Factura?", function(result) {
			if (result) {
				
				if (accion == 'anular') {
					$("#codfactura").val(cod);
					$("#accion").val(accion);
					$.fancybox({
						'autoScale': true, 'href': '#m_anulacion', 'type': 'inline', 'hideOnContentClick': true, 'openSpeed': 1000, 'closeSpeed': 1000, 'maxWidth': 960, 'maxHeight': 600, 'width': '1024%', 'height': '70%',
					});
				}else{
					$("#motivo_anulacion").val('');
					$("#accion").val(accion);
					
					var mensaje = "";
					if ($("#accion").val() == 'anular'){
						mensaje = "anulada";
					}else{
						mensaje = "activada";
					}
					
					//~ alert("código de la factura: "+$("#codfactura").val());
					//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
					
					$.post('<?php echo base_url(); ?>index.php/factura/ControllersFacturar/anular/' + cod, {'accion':accion, 'motivo':$("#motivo_anulacion").val()}, function(response) {
						bootbox.alert("La factura fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/factura/ControllersFacturar'
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	   
	});
	
	
	// Función para ejecutar la anulación de una factura
	$("#anular").on('click', function (e) {
		e.preventDefault();
		
		//~ alert("código de la factura: "+$("#codfactura").val());
		//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
		
		if ($("#motivo_anulacion").val() == ''){
			bootbox.alert("Describa el motivo de la anulación", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#motivo_anulacion").parent('div').addClass('has-error')
				$("#motivo_anulacion").focus();
			});
		}else{
			var mensaje = "";
			if ($("#accion").val() == 'anular'){
				mensaje = "anulada";
			}else{
				mensaje = "activada";
			}
			$.post('<?php echo base_url(); ?>index.php/factura/ControllersFacturar/anular/' + $("#codfactura").val(), {'accion':$("#accion").val(), 'motivo':$("#motivo_anulacion").val()}, function(response) {
				bootbox.alert("La factura fue "+mensaje+" exitosamente", function () {
				}).on('hidden.bs.modal', function (event) {
					//~ url = '<?php echo base_url(); ?>index.php/factura/ControllersFacturar'
					location.reload();
				});
			})
		}
	});
	
	
	// Función para preparar el pago de una factura
	$("table#tab_factura").on('click', 'input.verificar', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		//alert(id)
		var data_factura = cod;
		data_factura = data_factura.split(';');
		var condicion_pago = data_factura[2];
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'verificar';
        }else{
			accion = 'activar';
		}
		
		//~ var padre = $(this).closest('tr');
		//~ var nRow  = padre[0];
		bootbox.confirm("¿Desea "+accion+" la Factura?", function(result) {
			if (result) {
				
				if (accion == 'verificar') {
					$("#codfactura_v").val(cod);
					$("#accion_v").val(accion);
					//~ $.fancybox({
						//~ 'autoScale': true, 'href': '#m_pago', 'type': 'inline', 'hideOnContentClick': true, 'openSpeed': 1000, 'closeSpeed': 1000, 'maxWidth': 960, 'maxHeight': 600, 'width': '1024%', 'height': '70%',
					//~ });
					$("#m_pago").modal('show');
					// Verificamos la condición de pago y activamos el campo correspondiente
					if(condicion_pago.indexOf('3')!=-1){
						//~ $("#span_num_ref").css("display","block");
						$("#monto_efectivo").css("display","block");
					}
					if(condicion_pago.indexOf('1')!=-1){
						//~ $("#span_num_ref").css("display","block");
						$("#num_cheque").css("display","block");
						$("#monto_cheque").css("display","block");
					}
					if(condicion_pago.indexOf('2')!=-1){
						//~ $("#span_num_ref").css("display","block");
						$("#num_recibo").css("display","block");
						$("#monto_recibo").css("display","block");
					}
					if (condicion_pago.indexOf('4')!=-1){
						//~ $("#span_num_ref").css("display","block");
						$("#num_transf").css("display","block");
						$("#monto_transf").css("display","block");
					}
					if (condicion_pago.indexOf('5')!=-1){
						//~ $("#span_num_ref").css("display","block");
						$("#num_deposito").css("display","block");
						$("#monto_deposito").css("display","block");
					}
				}else{
					$("#accion_v").val(accion);
					
					var mensaje = "";
					if ($("#accion_v").val() == 'verificar'){
						mensaje = "verificada";
					}else{
						mensaje = "activada";
					}
					
					//~ alert("código de la factura: "+$("#codfactura").val());
					//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
					
					$.post('<?php echo base_url(); ?>index.php/factura/ControllersFacturar/verificar/' + cod, {'accion':accion, 'condicion':$("#condicion_pago").val()}, function(response) {
						bootbox.alert("La factura fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/factura/ControllersFacturar'
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	});
	
	// Función para ejecutar la verificación de pago de una factura
	$("#verificar").on('click', function (e) {
		e.preventDefault();
		
		var data_factura = $("#codfactura_v").val();
		data_factura = data_factura.split(';');
		var cod_factura = data_factura[0];
		var monto_factura = data_factura[1];
		var condicion_pago = data_factura[2];
		
		var monto_cheque = $("#monto_cheque").val();
		var monto_recibo = $("#monto_recibo").val();
		var monto_efectivo = $("#monto_efectivo").val();
		var monto_transf = $("#monto_transf").val();
		var monto_deposito = $("#monto_deposito").val();
		
		// Reasignamos cero por defecto a los montos vacíos
		if(monto_cheque == ""){
			monto_cheque = 0;
		}else{
			monto_cheque = parseInt(monto_cheque);
		}
		if(monto_recibo == ""){
			monto_recibo = 0;
		}else{
			monto_recibo = parseInt(monto_recibo);
		}
		if(monto_efectivo == ""){
			monto_efectivo = 0;
		}else{
			monto_efectivo = parseInt(monto_efectivo);
		}
		if(monto_transf == ""){
			monto_transf = 0;
		}else{
			monto_transf = parseInt(monto_transf);
		}
		if(monto_deposito == ""){
			monto_deposito = 0;
		}else{
			monto_deposito = parseInt(monto_deposito);
		}
		
		if (monto_cheque+monto_recibo+monto_efectivo+monto_transf+monto_deposito != monto_factura){
			//~ alert(monto_cheque+monto_recibo+monto_efectivo+monto_transf+monto_deposito);
			bootbox.alert("La distribución de los montos no concuerda con el monto total de la factura", function () {
			}).on('hidden.bs.modal', function (event) {
				//~ $("#cuenta").parent('div').addClass('has-error')
				//~ $("#cuenta").focus();
			});
		}else if($("#cuenta").val() == ''){
			// Si el método de pago es '3' (en efectivo) y la opción seleccionada no contiene el texto 'Caja'...
			bootbox.alert("Seleccione la cuenta a acreditar", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#cuenta").parent('div').addClass('has-error')
				$("#cuenta").focus();
			});
		}else{
			var mensaje = "";
			if ($("#accion_v").val() == 'verificar'){
				mensaje = "verificada";
			}else{
				mensaje = "activada";
			}
			$.post('<?php echo base_url(); ?>index.php/factura/ControllersFacturar/verificar/' + cod_factura, {'accion':$("#accion_v").val(), 'cuenta':$("#cuenta").val(), 'monto_f':monto_factura, 'condicion_pago':condicion_pago, '''num_cheque':$("#num_cheque").val(), 'num_recibo':$("#num_recibo").val(), 'num_transf':$("#num_transf").val(), 'num_deposito':$("#num_deposito").val(), 'monto_cheque':$("#monto_cheque").val(), 'monto_recibo':$("#monto_recibo").val(), 'monto_transf':$("#monto_transf").val(), 'monto_deposito':$("#monto_deposito").val(), 'monto_efectivo':$("#monto_efectivo").val()}, function(response) {
				bootbox.alert("La factura fue "+mensaje+" exitosamente", function () {
				}).on('hidden.bs.modal', function (event) {
					//~ url = '<?php echo base_url(); ?>index.php/factura/ControllersFacturar'
					location.reload();
				});
			})
		}
	});
	
	
	// Función para generar una factura en una modal
	$("table#tab_factura").on('click', 'img.generar_pdf', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		
		//~ alert(cod);
		
		//~ return true;
		
		URL = '<?php echo base_url(); ?>index.php/factura/ControllersFacturar/pdf_factura/' + cod + '';
		$.fancybox.open({ padding : 0, href: URL, type: 'iframe',width: 1024, height: 860, });
	});
	
	$("select").select2();

});


</script>

</head>
<body>
    


    </br>

<div class="row-fluid text-center" >
    <div class="mainbody-section">
        

        <div class="container" style="width:90%;">
            <div class="row">
                
                <button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar"  >
                    
                    &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nueva Factura
                </button>
                </br>
                </br>
                <div class="page-header">
              <h3 id="tables" class="lista">Listado de Facturas</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_factura" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
							
                            <th style='text-align: center'>#</th>
                            <th style='text-align: center'>Cod Factura</th>
                            <th style='text-align: center'>Cod Cliente</th>
                            <th style='text-align: center'>Cliente</th>
                            <th style='text-align: center'>BI</th>
                            <th style='text-align: center'>IVA</th>
                            <th style='text-align: center'>Desct</th>
                            <th style='text-align: center'>Total</th>
                            <th style='text-align: center'>Estatus</th>
                            <th style='text-align: center'>Generar</th>
                            <th style='text-align: center'>Verificar</th>
                            <th style='text-align: center'>Condición de pago</th>
                            <th style='text-align: center'>Observaciones</th>
                            <th style='text-align: center'>Motivo Anulación</th>
                            <th style='text-align: center'>Fecha de Emisión</th>
                            <th style='text-align: center'>Hora de Emisión</th>
                            <th style='text-align: center'>Firma almacen</th>
                            <th style='text-align: center'>Fecha de Entrega</th>
                            <th style='text-align: center'>Hora de Entrega</th>
                            <th style='text-align: center'>Editar</th>
                            <th style='text-align: center'>Anular</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar as $facturas) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                           
                            <td>
                                <?php echo $facturas->codfactura; ?>
                            </td>
                            <td>
                             <?php echo $facturas->codcliente; ?>
                            </td>
                            <td>
                             <?php echo $facturas->cliente; ?>
                            </td>
                            <td>
                            <?php echo $facturas->base_imponible; ?> 
                            </td>
                            <td>
                            <?php echo $facturas->monto_iva; ?> 
                            </td>
                            <td>
                            <?php echo $facturas->descuento; ?> 
                            </td>
                            <td>
                            <?php echo $facturas->totalfactura; ?> 
                            </td>
                             <td>
                               <?php 
								if ($facturas->estado == '1'){
									echo "En Proceso";
								}else if($facturas->estado == '2'){
									echo "Verificado";
								}else if($facturas->estado == '3'){
									echo "Anulado";
								}else if($facturas->estado == '4'){
									echo "Entregado";
								}
                               ?>
                            </td>
                            <td>
                            <img id="<?php echo $facturas->codfactura; ?>" class='generar_pdf' style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/pdf.jpg"/>
                            </td>
                            <td style='text-align: center'>
								<?php if ($facturas->estado == 2 || $facturas->estado == 4) {?>
								<input class='verificar' id='<?php echo $facturas->codfactura; ?>' type="checkbox" checked="checked" disabled="disabled"/>
								<?php }else if ($facturas->estado==3){ ?>
								<input class='verificar' id='<?php echo $facturas->codfactura; ?>' type="checkbox" disabled="disabled"/>
								<?php }else{ ?>
								<input class='verificar' id='<?php echo $facturas->codfactura.";".$facturas->totalfactura.";".$facturas->condicion_pago; ?>' type="checkbox" title='verificar la factura <?php echo $facturas->codfactura."(".$facturas->condicion_pago.")";?>'/>
								<?php } ?> 
                            </td>
                            <td>
                            <?php 
								if ($facturas->condicion_pago == '1'){
									echo "Cheque ($facturas->num_cheque)";
								}else if($facturas->condicion_pago == '2'){
									echo "Debito ($facturas->num_recibo)";
								}else if($facturas->condicion_pago == '3'){
									echo "Efectivo";
								}else if($facturas->condicion_pago == '4'){
									echo "Transferencia ($facturas->num_transf)";
								}else if($facturas->condicion_pago == '5'){
									echo "Depósito ($facturas->num_deposito))";
								}else{
									echo "";
								}
							?> 
                            </td>
                            <td>
                            <?php echo $facturas->observaciones; ?> 
                            </td>
                            <td>
                            <?php echo $facturas->motivo_anulacion; ?> 
                            </td>
                            <td>
                            <?php echo $facturas->fecha_emision; ?> 
                            </td>
                            <td>
                            <?php echo $facturas->hora_emision; ?> 
                            </td>
                            <td>
                            <?php echo $facturas->firma_almacen; ?> 
                            </td>
                            <td>
                            <?php echo $facturas->fecha_entrega; ?> 
                            </td>
                            <td>
                            <?php echo $facturas->hora_entrega; ?> 
                            </td>
                            <td style='text-align: center'>
								<?php if ($facturas->estado == 3 || $facturas->estado == 2 || $facturas->estado == 4) {?>
                                <img id="<?php echo $facturas->codfactura; ?>" style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/block.png"/>
                                <?php }else{ ?>
								<a href="<?php echo base_url()?>index.php/factura/ControllersFacturar/editar/<?= $facturas->codfactura; ?>"><i class="glyphicon glyphicon-edit"></i></a>
								<?php } ?> 
                            </td>
                            <td style='text-align: center'>
								<?php if ($facturas->estado == 3) {?>
								<input class='anular' id='<?php echo $facturas->codfactura; ?>' type="checkbox" checked="checked" disabled="disabled"/>
								<?php }else if ($facturas->estado == 2 || $facturas->estado == 4){ ?>
								<input class='anular' id='<?php echo $facturas->codfactura; ?>' type="checkbox" disabled="disabled"/>
								<?php }else{ ?> 
								<input class='anular' id='<?php echo $facturas->codfactura; ?>' type="checkbox" title='Anular la factura <?php echo $facturas->codfactura;?>'/>
								<?php } ?> 
                            </td>
                        </tr>
                        <?php $i++ ?>
                        <?php }?>
                        
                    </tbody>
                </table>                
                
                <!--Contenedor para anulación-->
                <div id="m_anulacion" style="width:100%;display:none;">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<span >Motivo de la Anulación</span>
								<div class="input-group mar-btm">
									<input type="hidden" id="codfactura" name="codfactura"/>
									<input type="hidden" id="accion" name="accion"/>
									<textarea style="width: 100%;" name="motivo_anulacion" id="motivo_anulacion" placeholder="Indique el motivo" class="form-control"></textarea>
									<button type="button" data-toggle="modal" data-target="#modal_cliente" class="btn btn-primary" id="anular" >
										<i class="glyphicon glyphicon-share-alt"></i>&nbsp;&nbsp;Anular
									</button>
								</div>
							</div>
						</div>
				   </div>
				</div>
				
				<!--Contenedor para verificación de pago de factura-->				
				<div class="modal" id="m_pago">
				   <div class="modal-dialog">
					  <div class="modal-content">
						 <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">
							   <span class="glyphicon glyphicon-search"></span>
							   &nbsp;  Indique Los Números de Referencia (Si Aplica) y Seleccione la Cuenta a Acreditar
							</h4>
						 </div>
						 <div class="modal-body" style="height:400px;">
							<form name="f_nueva_venta" action="" method="post" class="form">
							   <input type="hidden" name="cliente"/>
							   <div class="form-group">
									<div class="col-sm-12">
									<span id="span_num_ref">Números de Referencia y montos</span>
									</div>
									</br></br>
									<div class="col-sm-12">
										<div class="input col-sm-6">
											<input type="text" placeholder="Número de cheque" name="num_cheque" id="num_cheque" class="form-control" style="display:none;margin:1%;">
											<input type="text" placeholder="Número de recibo" name="num_recibo" id="num_recibo" class="form-control" style="display:none;margin:1%;">
											<input type="text" placeholder="Número de transferencia" name="num_transf" id="num_transf" class="form-control" style="display:none;margin:1%;">
											<input type="text" placeholder="Número de depósito" name="num_deposito" id="num_deposito" class="form-control" style="display:none;margin:1%;">
										</div>
										<div class="input col-sm-6">
											<input type="text" placeholder="Monto de cheque" name="monto_cheque" id="monto_cheque" class="form-control" style="display:none;margin:1%;">
											<input type="text" placeholder="Monto de recibo" name="monto_recibo" id="monto_recibo" class="form-control" style="display:none;margin:1%;">
											<input type="text" placeholder="Monto de transferencia" name="monto_transf" id="monto_transf" class="form-control" style="display:none;margin:1%;">
											<input type="text" placeholder="Monto de depósito" name="monto_deposito" id="monto_deposito" class="form-control" style="display:none;margin:1%;">
											<input type="text" placeholder="Monto en efectivo" name="monto_efectivo" id="monto_efectivo" class="form-control" style="display:none;margin:1%;">
										</div>
									</div>
									</br></br></br></br></br></br>
									<div class="col-sm-12">
										<input type="hidden" id="codfactura_v" name="codfactura_v"/>
										<input type="hidden" id="accion_v" name="accion_v"/>
										<select id="cuenta" name="cuenta" class="form-control select2 input-sm">
											<option value="">Seleccione</option>
											<?php foreach ($cuentas as $cuenta) { if($cuenta->cuenta != "00000000000000000000"){?>
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
											<?php }}?>
										</select>
									</div>
									</br></br>
									<div>
										<span class="input-btn">
											<button class="btn btn-primary" type="button" id="verificar">
											Verificar <span class="glyphicon glyphicon-share-alt"></span>
											</button>
										</span>
									</div>
							   </div>
							</form>
						 </div>
						 
					  </div>
				   </div>
				</div>
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
