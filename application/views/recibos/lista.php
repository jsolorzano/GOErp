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
    var Trecibos = $('#tab_empleado').dataTable({
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
            {"sClass": "registro center", "sWidth": "20%"},
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
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    $('#enviar').click(function(){
        url = '<?php echo base_url()?>index.php/recibos/ControllersRecibo/recibo'
        window.location = url
    })
	
	// Función para preparar la anulación de una recibo
	$("table#tab_empleado").on('click', 'input.anular', function (e) {
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
		bootbox.confirm("¿Desea "+accion+" el recibo?", function(result) {
			if (result) {
				
				if (accion == 'anular') {
					$("#codrecibo").val(cod);
					$("#accion").val(accion);
					$.fancybox({
						'autoScale': true, 'href': '#m_anulacion', 'type': 'inline', 'hideOnContentClick': true, 'openSpeed': 1000, 'closeSpeed': 1000, 'maxWidth': 960, 'maxHeight': 600, 'width': '1024%', 'height': '70%',
					});
				}else{
					$("#motivo_anulacion").val('');
					$("#accion").val(accion);
					
					var mensaje = "";
					if ($("#accion").val() == 'anular'){
						mensaje = "anulado";
					}else{
						mensaje = "activado";
					}
					
					//~ alert("código de la recibo: "+$("#codrecibo").val());
					//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
					
					$.post('<?php echo base_url(); ?>index.php/recibo/ControllersRecibo/anular/' + cod, {'accion':accion, 'motivo':$("#motivo_anulacion").val()}, function(response) {
						bootbox.alert("La recibo fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/recibo/ControllersRecibo'
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	   
	});
	
	
	// Función para ejecutar la anulación de una recibo
	$("#anular").on('click', function (e) {
		e.preventDefault();
		
		//~ alert("código de la recibo: "+$("#codrecibo").val());
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
				mensaje = "anulado";
			}else{
				mensaje = "activado";
			}
			$.post('<?php echo base_url(); ?>index.php/recibos/ControllersRecibo/anular/' + $("#codrecibo").val(), {'accion':$("#accion").val(), 'motivo':$("#motivo_anulacion").val()}, function(response) {
				bootbox.alert("El recibo fue "+mensaje+" exitosamente", function () {
				}).on('hidden.bs.modal', function (event) {
					//~ url = '<?php echo base_url(); ?>index.php/recibo/ControllersRecibo'
					location.reload();
				});
			})
		}
	});
	
	
	// Función para preparar el pago de una recibo
	$("table#tab_empleado").on('click', 'input.verificar', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		//alert(id)
		var data_recibo = cod;
		data_recibo = data_recibo.split(';');
		var condicion_pago = data_recibo[2];
		
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
		bootbox.confirm("¿Desea "+accion+" el recibo?", function(result) {
			if (result) {
				
				if (accion == 'verificar') {
					$("#codrecibo_v").val(cod);
					$("#accion_v").val(accion);
					//~ $.fancybox({
						//~ 'autoScale': true, 'href': '#m_pago', 'type': 'inline', 'hideOnContentClick': true, 'openSpeed': 1000, 'closeSpeed': 1000, 'maxWidth': 960, 'maxHeight': 600, 'width': '1024%', 'height': '70%',
					//~ });
					$("#m_pago").modal('show');
					// Verificamos la condición de pago y activamos el campo correspondiente
					if(condicion_pago == '3'){
						$("#span_num_ref").css("display","none");
						$("#num_deposito").css("display","none");
						$("#num_recibo").css("display","none");
						$("#num_cheque").css("display","none");
						$("#num_transf").css("display","none");
						// Si el método de pago es en efectivo marcamos por defecto la cuenta de caja
						for (var i = 0;i < document.getElementById('cuenta').options.length;i++) {
							if (document.getElementById('cuenta').options[i].text.indexOf('Caja')!=-1) {
								var valor = document.getElementById('cuenta').options[i].value;
								//~ alert(valor);
								$('#cuenta').select2('val',valor);
							}
						}
					}else if(condicion_pago == '1'){
						$("#span_num_ref").css("display","block");
						$("#num_deposito").css("display","none");
						$("#num_transf").css("display","none");
						$("#num_recibo").css("display","none");
						$("#num_cheque").css("display","block");
					}else if(condicion_pago == '2'){
						$("#span_num_ref").css("display","block");
						$("#num_deposito").css("display","none");
						$("#num_recibo").css("display","block");
						$("#num_cheque").css("display","none");
						$("#num_transf").css("display","none");
					}else if (condicion_pago == '4'){
						$("#span_num_ref").css("display","block");
						$("#num_deposito").css("display","none");
						$("#num_recibo").css("display","none");
						$("#num_cheque").css("display","none");
						$("#num_transf").css("display","block");
					}else if (condicion_pago == '5'){
						$("#span_num_ref").css("display","block");
						$("#num_deposito").css("display","block");
						$("#num_recibo").css("display","none");
						$("#num_cheque").css("display","none");
						$("#num_transf").css("display","none");
					}else{
						$("#span_num_ref").css("display","none");
						$("#num_deposito").css("display","none");
						$("#num_recibo").css("display","none");
						$("#num_cheque").css("display","none");
						$("#num_transf").css("display","none");
					}
				}else{
					$("#accion_v").val(accion);
					
					var mensaje = "";
					if ($("#accion_v").val() == 'verificar'){
						mensaje = "verificado";
					}else{
						mensaje = "activado";
					}
					
					//~ alert("código de la recibo: "+$("#codrecibo").val());
					//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
					
					$.post('<?php echo base_url(); ?>index.php/recibos/ControllersRecibo/verificar/' + cod, {'accion':accion, 'condicion':$("#condicion_pago").val()}, function(response) {
						bootbox.alert("La recibo fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/recibo/ControllersRecibo'
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	});
	
	// Función para ejecutar la verificación de pago de una recibo
	$("#verificar").on('click', function (e) {
		e.preventDefault();
		
		var data_recibo = $("#codrecibo_v").val();
		data_recibo = data_recibo.split(';');
		var cod_recibo = data_recibo[0];
		var monto_recibo = data_recibo[1];
		var condicion_pago = data_recibo[2];
		
		if ($("#cuenta").val() == ''){
			bootbox.alert("Seleccione la cuenta a acreditar", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#cuenta").parent('div').addClass('has-error')
				$("#cuenta").focus();
			});
		}else if(condicion_pago == '3' && $("#cuenta option:selected").text().trim().indexOf('Caja')==-1){
			// Si el método de pago es '3' (en efectivo) y la opción seleccionada no contiene el texto 'Caja'...
			bootbox.alert("El recibo fue pagado en efectivo, debe seleccionar la cuenta de caja", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#cuenta").parent('div').addClass('has-error')
				$("#cuenta").focus();
			});
		}else{
			$.post('<?php echo base_url(); ?>index.php/recibos/ControllersRecibo/saldo_cuenta/'+$("#cuenta").val(), function (resp) {
				var saldo_cuenta = resp.split('<html>');
				saldo_cuenta = parseFloat(saldo_cuenta[0]);
				if(saldo_cuenta >= parseFloat(monto_recibo)){
					var mensaje = "";
					if ($("#accion_v").val() == 'verificar'){
						mensaje = "verificado";
					}else{
						mensaje = "activado";
					}
					$.post('<?php echo base_url(); ?>index.php/recibos/ControllersRecibo/verificar/' + cod_recibo, {'accion':$("#accion_v").val(), 'cuenta':$("#cuenta").val(), 'monto_r':monto_recibo, 'num_cheque':$("#num_cheque").val(), 'num_recibo':$("#num_recibo").val(), 'num_transf':$("#num_transf").val(), 'num_deposito':$("#num_deposito").val()}, function(response) {
						bootbox.alert("El recibo fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/recibo/ControllersRecibo'
							location.reload();
						});
					});
				}else{
					bootbox.alert("La cuenta elegida no tiene disponibilidad suficiente, seleccione otra cuenta.", function () {
					}).on('hidden.bs.modal', function (event) {
						$("#cuenta").parent('div').addClass('has-error')
						$("#cuenta").focus();
					});
				}
			});
		}
	});
	
	
	// Función para generar una recibo en una modal
	$("table#tab_empleado").on('click', 'img.generar_pdf', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		
		//~ alert(cod);
		
		//~ return true;
		
		URL = '<?php echo base_url(); ?>index.php/recibos/ControllersRecibo/pdf_recibo/' + cod + '';
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
                    
                    &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Recibo
                </button>
                </br>
                </br>
                <div class="page-header">
              <h3 id="tables" class="lista">Listado de recibos</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_empleado" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
                            <th style='text-align: center'>#</th>
                            <th style='text-align: center'>Cod Recibo</th>
                            <th style='text-align: center'>Empleado</th>
                            <th style='text-align: center'>Salario</th>
                            <th style='text-align: center'>Salario Base</th>
                            <th style='text-align: center'>Abonos</th>
                            <th style='text-align: center'>Desct</th>
                            <th style='text-align: center'>Total</th>
                            <th style='text-align: center'>Estatus</th>
                            <th style='text-align: center'>Condición de pago</th>
                            <th style='text-align: center'>Observaciones</th>
                            <th style='text-align: center'>Motivo Anulación</th>
                            <th style='text-align: center'>Fecha de Emisión</th>
                            <th style='text-align: center'>Hora de Emisión</th>
                            <th style='text-align: center'>Emitido por</th>
                            <th style='text-align: center'>Fecha de Entrega</th>
                            <th style='text-align: center'>Hora de Entrega</th>
                            <th style='text-align: center'>PDF</th>
                            <th style='text-align: center'>Verificar</th>
                            <th style='text-align: center'>Editar</th>
                            <th style='text-align: center'>Anular</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar as $recibo) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                            <td>
                                <?php echo $recibo->codrecibo; ?>
                            </td>
                            <td title="<?php echo $recibo->codempleado; ?>">
                             <?php echo $recibo->empleado; ?>
                            </td>
                            <td>
                            <?php echo $recibo->salario; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->monto_salario; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->abonos; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->descuento; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->totalrecibo; ?> 
                            </td>
                             <td>
                               <?php 
								if ($recibo->estado == '1'){
									echo "En Proceso";
								}else if($recibo->estado == '2'){
									echo "Verificado";
								}else if($recibo->estado == '3'){
									echo "Anulado";
								}
                               ?>
                            </td>
                            <td>
                            <?php 
								if ($recibo->condicion_pago == '1'){
									echo "Cheque ($recibo->num_cheque)";
								}else if($recibo->condicion_pago == '2'){
									echo "Debito ($recibo->num_recibo)";
								}else if($recibo->condicion_pago == '3'){
									echo "Efectivo";
								}else if($recibo->condicion_pago == '4'){
									echo "Transferencia ($recibo->num_transf)";
								}else if($recibo->condicion_pago == '5'){
									echo "Depósito ($recibo->num_deposito))";
								}else{
									echo "";
								}
							?> 
                            </td>
                            <td>
                            <?php echo $recibo->observaciones; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->motivo_anulacion; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->fecha_emision; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->hora_emision; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->firma_rrhh; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->fecha_entrega; ?> 
                            </td>
                            <td>
                            <?php echo $recibo->hora_entrega; ?> 
                            </td>
                            <td>
                            <img id="<?php echo $recibo->codrecibo; ?>" class='generar_pdf' style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/pdf.jpg"/>
                            </td>
                            <td style='text-align: center'>
								<?php if ($recibo->estado == 2) {?>
								<input class='verificar' id='<?php echo $recibo->codrecibo; ?>' type="checkbox" checked="checked" disabled="disabled"/>
								<?php }else if ($recibo->estado==3){ ?>
								<input class='verificar' id='<?php echo $recibo->codrecibo; ?>' type="checkbox" disabled="disabled"/>
								<?php }else{ ?>
								<input class='verificar' id='<?php echo $recibo->codrecibo.";".$recibo->totalrecibo.";".$recibo->condicion_pago; ?>' type="checkbox" title='verificar el recibo <?php echo $recibo->codrecibo;?>'/>
								<?php } ?> 
                            </td>
                            <td style='text-align: center'>
								<?php if ($recibo->estado == 3 || $recibo->estado == 2) {?>
                                <img id="<?php echo $recibo->codrecibo; ?>" style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/block.png"/>
                                <?php }else{ ?>
								<a href="<?php echo base_url()?>index.php/recibos/ControllersRecibo/editar/<?= $recibo->codrecibo; ?>"><i class="glyphicon glyphicon-edit"></i></a>
								<?php } ?> 
                            </td>
                            <td style='text-align: center'>
								<?php if ($recibo->estado == 3) {?>
								<input class='anular' id='<?php echo $recibo->codrecibo; ?>' type="checkbox" checked="checked" disabled="disabled"/>
								<?php }else if ($recibo->estado == 2){ ?>
								<input class='anular' id='<?php echo $recibo->codrecibo; ?>' type="checkbox" disabled="disabled"/>
								<?php }else{ ?> 
								<input class='anular' id='<?php echo $recibo->codrecibo; ?>' type="checkbox" title='Anular la recibo <?php echo $recibo->codrecibo;?>'/>
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
								<span >Describa el motivo de la anulación</span>
								<div class="input-group mar-btm">
									<input type="hidden" id="codrecibo" name="codrecibo"/>
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
				
				<!--Contenedor para verificación de pago de recibo-->				
				<div class="modal" id="m_pago">
				   <div class="modal-dialog">
					  <div class="modal-content">
						 <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">
							   <span class="glyphicon glyphicon-search"></span>
							   &nbsp;  Indique el número de referencia si aplica y seleccione la cuenta a acreditar
							</h4>
						 </div>
						 <div class="modal-body">
							<form name="f_nueva_venta" action="" method="post" class="form">
							   <input type="hidden" name="cliente"/>
							   <div class="form-group">
									<div class="col-sm-12">
										<span id="span_num_ref">Número de referencia</span>
										<div class="input">
											<input type="text" placeholder="Número de cheque" name="num_cheque" id="num_cheque" class="form-control">
											<input type="text" placeholder="Número de recibo" name="num_recibo" id="num_recibo" class="form-control">
											<input type="text" placeholder="Número de transferencia" name="num_transf" id="num_transf" class="form-control">
											<input type="text" placeholder="Número de depósito" name="num_deposito" id="num_deposito" class="form-control">
										</div>
									</div>
									</br></br></br></br>
									<div class="col-sm-12">
										<span id="span_num_ref">Seleccione la cuenta</span>
										<input type="hidden" id="codrecibo_v" name="codrecibo_v"/>
										<input type="hidden" id="accion_v" name="accion_v"/>
										<select id="cuenta" name="cuenta" class="form-control select2 input-sm">
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
									</br>
									<div class="col-sm-12" style="display:none;">
										<span id="span_num_ref">Firma Entrega</span>
										<div class="input">
											<input type="text" placeholder="Firma Entrega" name="firma_rrhh" id="firma_rrhh" class="form-control">
										</div>
									</div>
									</br></br></br></br>
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
