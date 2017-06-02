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
    var Tfacturas = $('#tab_pedido').dataTable({
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
        url = '<?php echo base_url()?>index.php/pedidos/ControllersPedidos/pedido'
        window.location = url
    })
	
	// Función para preparar la anulación de una factura
	$("table#tab_pedido").on('click', 'input.anular', function (e) {
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
		bootbox.confirm("¿Desea "+accion+" el Pedido?", function(result) {
			if (result) {
				
				if (accion == 'anular') {
					$("#codpedido").val(cod);
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
					
					//~ alert("código de la factura: "+$("#codpedido").val());
					//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
					
					$.post('<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos/anular/' + cod, {'accion':accion, 'motivo':$("#motivo_anulacion").val()}, function(response) {
						bootbox.alert("El pedido fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos'
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
		
		//~ alert("código de la factura: "+$("#codpedido").val());
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
			$.post('<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos/anular/' + $("#codpedido").val(), {'accion':$("#accion").val(), 'motivo':$("#motivo_anulacion").val()}, function(response) {
				bootbox.alert("El pedido fue "+mensaje+" exitosamente", function () {
				}).on('hidden.bs.modal', function (event) {
					//~ url = '<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos'
					location.reload();
				});
			})
		}
	});
	
	
	// Función para preparar el pago de una factura
	$("table#tab_pedido").on('click', 'input.aprobar', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');  // Código del pedido
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'aprobar';
        }else{
			accion = 'activar';
		}
		
		//~ var padre = $(this).closest('tr');
		//~ var nRow  = padre[0];
		bootbox.confirm("¿Está seguro de "+accion+" el pedido?", function(result) {
			if (result) {
				var mensaje = "";
				if (accion == 'aprobar') {
					
					mensaje = "aprobado";
					
					$.post('<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos/aprobar/' + cod, {'accion':accion}, function(response) {
						bootbox.alert("El pedido fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos'
							location.reload();
						});
					})
				}else{
					mensaje = "activado";
					
					bootbox.alert("El pedido fue "+mensaje+", esto es un error", function () {
					}).on('hidden.bs.modal', function (event) {
					});
				}
				
			}
		}); 
	   
	});
	
	
	// Función para generar una factura en una modal
	$("table#tab_pedido").on('click', 'img.generar_pdf', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		
		//~ alert(cod);
		
		//~ return true;
		
		URL = '<?php echo base_url(); ?>index.php/pedidos/ControllersPedidos/pdf_pedido/' + cod + '';
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
                    
                    &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Pedido
                </button>
                </br>
                </br>
                <div class="page-header">
              <h3 id="tables" class="lista">Listado de Pedidos</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_pedido" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
							
                            <th style='text-align: center'>#</th>
                            <th style='text-align: center'>Cod Pedido</th>
                            <th style='text-align: center'>Cod Proveedor</th>
                            <th style='text-align: center'>Proveedor</th>
                            <th style='text-align: center'>Estatus</th>
                            <th style='text-align: center'>Observaciones</th>
                            <th style='text-align: center'>Motivo Anulación</th>
                            <th style='text-align: center'>Fecha de Emisión</th>
                            <th style='text-align: center'>Hora de Emisión</th>
                            <th style='text-align: center'>Firma almacen</th>
                            <th style='text-align: center'>Fecha de Ingreso</th>
                            <th style='text-align: center'>Hora de Ingreso</th>
                            <th style='text-align: center'>PDF</th>
                            <th style='text-align: center'>Aprobar</th>
                            <th style='text-align: center'>Editar</th>
                            <th style='text-align: center'>Anular</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar as $pedidos) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                           
                            <td>
                                <?php echo $pedidos->codpedido; ?>
                            </td>
                            <td>
                             <?php echo $pedidos->codproveedor; ?>
                            </td>
                            <td>
                             <?php echo $pedidos->proveedor; ?>
                            </td>
                             <td>
                               <?php 
								if ($pedidos->estado == '1'){
									echo "En proceso";
								}else if($pedidos->estado == '2'){
									echo "Aprobado";
								}else if($pedidos->estado == '3'){
									echo "Anulado";
								}else if($pedidos->estado == '4'){
									echo "Ingresado";
								}
                               ?>
                            </td>
                            <td>
                            <?php echo $pedidos->observaciones; ?> 
                            </td>
                            <td>
                            <?php echo $pedidos->motivo_anulacion; ?> 
                            </td>
                            <td>
                            <?php echo $pedidos->fecha_emision; ?> 
                            </td>
                            <td>
                            <?php echo $pedidos->hora_emision; ?> 
                            </td>
                            <td>
                            <?php echo $pedidos->firma_almacen; ?> 
                            </td>
                            <td>
                            <?php echo $pedidos->fecha_ingreso; ?> 
                            </td>
                            <td>
                            <?php echo $pedidos->hora_ingreso; ?> 
                            </td>
                            <td>
                            <img id="<?php echo $pedidos->codpedido; ?>" class='generar_pdf' style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/pdf.jpg"/>
                            </td>
                            <td style='text-align: center'>
								<?php if ($pedidos->estado == 2 || $pedidos->estado == 4) {?>
								<input class='aprobar' id='<?php echo $pedidos->codpedido; ?>' type="checkbox" checked="checked" disabled="disabled"/>
								<?php }else if ($pedidos->estado==3){ ?>
								<input class='aprobar' id='<?php echo $pedidos->codpedido; ?>' type="checkbox" disabled="disabled"/>
								<?php }else{ ?>
								<input class='aprobar' id='<?php echo $pedidos->codpedido ?>' type="checkbox" title='aprobar el pedido <?php echo $pedidos->codpedido;?>'/>
								<?php } ?> 
                            </td>
                            <td style='text-align: center'>
								<?php if ($pedidos->estado == 3 || $pedidos->estado == 2 || $pedidos->estado == 4) {?>
                                <img id="<?php echo $pedidos->codpedido; ?>" style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/block.png"/>
                                <?php }else{ ?>
								<a href="<?php echo base_url()?>index.php/pedidos/ControllersPedidos/editar/<?= $pedidos->codpedido; ?>"><i class="glyphicon glyphicon-edit"></i></a>
								<?php } ?> 
                            </td>
                            <td style='text-align: center'>
								<?php if ($pedidos->estado == 3) {?>
								<input class='anular' id='<?php echo $pedidos->codpedido; ?>' type="checkbox" checked="checked" disabled="disabled"/>
								<?php }else if ($pedidos->estado == 2 || $pedidos->estado == 4){ ?>
								<input class='anular' id='<?php echo $pedidos->codpedido; ?>' type="checkbox" disabled="disabled"/>
								<?php }else{ ?> 
								<input class='anular' id='<?php echo $pedidos->codpedido; ?>' type="checkbox" title='Anular el pedido <?php echo $pedidos->codpedido;?>'/>
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
									<input type="hidden" id="codpedido" name="codpedido"/>
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
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
