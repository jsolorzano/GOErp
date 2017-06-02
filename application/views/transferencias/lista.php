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

<?php if ($tipouser == 'Administrador'){
	
 } else {
	 redirect(base_url());
 }?>     
        
 <script>
$(document).ready(function(){
    var Ttransferencias = $('#tab_transferencias').dataTable({
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
            {"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
			{"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
			{"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
        ]
    });
    
    $("#num_tranferencia").numeric(); //Sólo permite números
    
    $('#enviar').click(function () {
		url = '<?php echo base_url() ?>index.php/transferencias/ControllersTransferencias/registrar';
		window.location = url;
	});
	
	// Función para preparar la anulación de una Transferencia
	$("table#tab_transferencias").on('click', 'input.anular', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'anular';
        }else{
			accion = 'activar';
		}
		
		bootbox.confirm("¿Desea "+accion+" la Transferencia?", function(result) {
			if (result) {
				
				if (accion == 'anular') {
					$("#codigo").val(cod);
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
					
					//~ alert("código de la Transferencia: "+$("#codTransferencia").val());
					//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
					
					$.post('<?php echo base_url(); ?>index.php/transferencias/ControllersTransferencias/anular/' + cod, {'accion':accion, 'motivo':$("#motivo_anulacion").val()}, function(response) {
						bootbox.alert("La transferencia fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/Transferencia/ControllersTransferenciar'
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	   
	});
	
	
	// Función para ejecutar la anulación de una Transferencia
	$("#anular").on('click', function (e) {
		e.preventDefault();
		
		//~ alert("código de la Transferencia: "+$("#codTransferencia").val());
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
			$.post('<?php echo base_url(); ?>index.php/transferencias/ControllersTransferencias/anular/' + $("#codigo").val(), {'accion':$("#accion").val(), 'motivo':$("#motivo_anulacion").val()}, function(response) {
				bootbox.alert("La transferencia fue "+mensaje+" exitosamente", function () {
				}).on('hidden.bs.modal', function (event) {
					//~ url = '<?php echo base_url(); ?>index.php/Transferencia/ControllersTransferenciar'
					location.reload();
				});
			})
		}
	});
	
	// Función para preparar la validación de la transferencia
	$("table#tab_transferencias").on('click', 'input.verificar', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		//alert(id)
		var data_Transferencia = cod;
		data_Transferencia = data_Transferencia.split(';');
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'verificar';
        }else{
			accion = 'activar';
		}
		
		bootbox.confirm("¿Desea "+accion+" la Transferencia?", function(result) {
			if (result) {
				
				if (accion == 'verificar') {
					$("#codigo_v").val(cod);
					$("#accion_v").val(accion);
					//~ $.fancybox({
						//~ 'autoScale': true, 'href': '#m_validar', 'type': 'inline', 'hideOnContentClick': true, 'openSpeed': 1000, 'closeSpeed': 1000, 'maxWidth': 960, 'maxHeight': 600, 'width': '1024%', 'height': '70%',
					//~ });
					$("#m_validar").modal('show');
				}else{
					$("#accion_v").val(accion);
					
					var mensaje = "";
					if ($("#accion_v").val() == 'verificar'){
						mensaje = "validada";
					}else{
						mensaje = "activada";
					}
					
					$.post('<?php echo base_url(); ?>index.php/Transferencia/ControllersTransferenciar/verificar/' + cod, {'accion':accion, 'condicion':$("#condicion_pago").val()}, function(response) {
						bootbox.alert("La Transferencia fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/Transferencia/ControllersTransferenciar'
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	});
	
	// Función para ejecutar la validación de la transferencia
	$("#verificar").on('click', function (e) {
		e.preventDefault();
		
		var data_Transferencia = $("#codigo_v").val();
		data_Transferencia = data_Transferencia.split(';');
		var cod_Transferencia = data_Transferencia[0];
		var monto_Transferencia = data_Transferencia[1];
		var origen_Transferencia = data_Transferencia[2];
		var destino_Transferencia = data_Transferencia[3];
		
		if ($("#num_tranferencia").val() == ''){
			bootbox.alert("Indique el número de referencia de la transferencia", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#num_tranferencia").parent('div').addClass('has-error')
				$("#num_tranferencia").val('');
				$("#num_tranferencia").focus();
			});
		}else{
			var mensaje = "";
			if ($("#accion_v").val() == 'verificar'){
				mensaje = "validada";
			}else{
				mensaje = "activada";
			}
			$.post('<?php echo base_url(); ?>index.php/transferencias/ControllersTransferencias/verificar/' + cod_Transferencia, {'accion':$("#accion_v").val(), 'origen':origen_Transferencia, 'destino':destino_Transferencia, 'monto':monto_Transferencia, 'num_referencia':$("#num_transferencia").val()}, function(response) {
				bootbox.alert("La Transferencia fue "+mensaje+" exitosamente", function () {
				}).on('hidden.bs.modal', function (event) {
					//~ url = '<?php echo base_url(); ?>index.php/Transferencia/ControllersTransferenciar'
					location.reload();
				});
			})
		}
	});

});

</script>

</head>
<body>
    


    </br>

<div class="row-fluid text-center" >
    <div class="mainbody-section">
        

        <div class="container" style="width:90%;">
            <div class="row">
				<button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px" id="enviar" >
					&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nueva transferencia
				</button>
				</br>
				</br>
				<h3 id="tables" class="lista">Listado de transferencias</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_transferencias" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
							<th style='text-align: center'>Item</th>
							<th style='text-align: center'>Código</th>
							<th style='text-align: center'>Origen</th>
							<th style='text-align: center'>Destino</th>
							<th style='text-align: center'>Monto</th>
							<th style='text-align: center'>Num Referencia</th>
							<th style='text-align: center'>Fecha</th>
							<th style='text-align: center'>Hora</th>
							<th style='text-align: center'>Usuario</th>
							<th style='text-align: center'>Estatus</th>
							<th style='text-align: center'>Validar</th>
							<th style='text-align: center'>Editar</th>
							<th style='text-align: center'>Anular</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i = 1; ?>

						<?php foreach ($listar as $transferencia) { ?>
							<tr style="font-size: 16px;text-align: center" class="{% cycle 'impar' 'par' %}" >
								<td>
									<?php echo $i; ?>
								</td>
								<td>
									<?php echo $transferencia->codigo; ?>
								</td>
								<td>
									<?php 
									foreach ($list_cuentas as $cuenta) {
										if($cuenta->codigo == $transferencia->origen){
											foreach ($list_bancos as $banco) {
												if($banco->cod_banco == $cuenta->cod_banco){
													echo $banco->banco;
												}
											}
											echo "-".$cuenta->cuenta;
										}
									}
									?>
								</td>
								<td>
									<?php 
									foreach ($list_cuentas as $cuenta2) {
										if($cuenta2->codigo == $transferencia->destino){
											foreach ($list_bancos as $banco) {
												if($banco->cod_banco == $cuenta2->cod_banco){
													echo $banco->banco;
												}
											}
											echo "-".$cuenta2->cuenta;
										}
									}
									?>
								</td>
								<td>
									<?php echo $transferencia->monto; ?>
								</td>
								<td>
									<?php echo $transferencia->num_referencia; ?>
								</td>
								<td>
									<?php echo $transferencia->fecha; ?>
								</td>
								<td>
									<?php echo $transferencia->hora; ?>
								</td>
								<td>
									<?php 
									foreach ($list_usuarios as $usuario){
										if($usuario->id == $transferencia->user_create){
											echo $usuario->username;
										}else{
											echo "";
										}
									}
									?>
								</td>
								<td>
									<?php 
									if($transferencia->estatus == 1){
										echo "En proceso"; 
									}else if($transferencia->estatus == 2){
										echo "Validado";
									}else if($transferencia->estatus == 3){
										echo "Anulado";
									}else{
										echo "";
									}
									?>
								</td>
								<td style='text-align: center'>
									<?php if ($transferencia->estatus == 2) {?>
									<input class='verificar' id='<?php echo $transferencia->codigo; ?>' type="checkbox" checked="checked" disabled="disabled"/>
									<?php }else if ($transferencia->estatus==3){ ?>
									<input class='verificar' id='<?php echo $transferencia->codigo; ?>' type="checkbox" disabled="disabled"/>
									<?php }else{ ?>
									<input class='verificar' id='<?php echo $transferencia->codigo.";".$transferencia->monto.";".$transferencia->origen.";".$transferencia->destino; ?>' type="checkbox" title='verificar la Transferencia <?php echo $transferencia->codigo;?>'/>
									<?php } ?> 
								</td>
								<td style='text-align: center'>
									<?php if ($transferencia->estatus == 3 || $transferencia->estatus == 2) {?>
									<img id="<?php echo $transferencia->codigo; ?>" style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/block.png"/>
									<?php }else{ ?>
									<a href="<?php echo base_url()?>index.php/transferencias/ControllersTransferencias/editar/<?= $transferencia->id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
									<?php } ?> 
								</td>
								<td style='text-align: center'>
									<?php if ($transferencia->estatus == 3) {?>
									<input class='anular' id='<?php echo $transferencia->codigo; ?>' type="checkbox" checked="checked" disabled="disabled"/>
									<?php }else if ($transferencia->estatus == 2){ ?>
									<input class='anular' id='<?php echo $transferencia->codigo; ?>' type="checkbox" disabled="disabled"/>
									<?php }else{ ?> 
									<input class='anular' id='<?php echo $transferencia->codigo; ?>' type="checkbox" title='Anular la transferencia <?php echo $transferencia->codigo;?>'/>
									<?php } ?> 
								</td>
							</tr>
							<?php $i++ ?>
						<?php } ?>
                        
                    </tbody>
                </table>
                
                <!--Contenedor para anulación-->
                <div id="m_anulacion" style="width:100%;display:none;">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<span >Describa el motivo de la anulación</span>
								<div class="input-group mar-btm">
									<input type="hidden" id="codigo" name="codigo"/>
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
				
				<!--Contenedor para validar la transferencia-->
				<div class="modal" id="m_validar">
				   <div class="modal-dialog">
					  <div class="modal-content">
						 <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">
							   <span></span>
							   &nbsp; Indique el número de referencia
							</h4>
						 </div>
						 <div class="modal-body">
							<form name="f_nueva_venta" action="" method="post" class="form">
							   <input type="hidden" name="cliente"/>
							   <div class="form-group">
								  <div>
									<input type="hidden" id="codigo_v" name="codigo_v"/>
									<input type="hidden" id="accion_v" name="accion_v"/>
									<input type="text" id="num_transferencia" name="num_transferencia" placeholder="Número de referencia" class="form-control" autofocus="true"/>
								  </div>
								  </br>
								  <div>
									<span class="input-btn">
										<button class="btn btn-primary" type="button" id="verificar">
										   Validar <span class="glyphicon glyphicon-share-alt"></span>
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
