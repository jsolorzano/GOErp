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

<?php if ($tipouser == 'Almacen' || $tipouser == 'Administrador'){
	
 } else {
	 redirect(base_url());
 }?>     
        
 <script>
$(document).ready(function(){
    var Tpedidos = $('#tab_pedido').dataTable({
        "iDisplayLength": 15,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5,8,10],
        dom: 'Bfrtip',
        buttons: [
			{
				extend: 'print',
				text: 'Imprimir',
				autoPrint: true
			}
		],
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
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    
    // Ejecutamos el contador de pedidos aprobados por ingresar
	$.post('<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/contar_pedidos/', function(response) {
		var respuesta = response.split('<html>');
		//~ alert(respuesta[0]);
		if(respuesta[0] > 0){
			bootbox.alert("Hay "+respuesta[0]+" pedido(s) aprobado(s) por ingresar", function () {
			}).on('hidden.bs.modal', function (event) {
				
			});
		}
	});
	
	// Función para preparar el ingreso de un pedido
	$("table#tab_pedido").on('click', 'input.ingresar', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		//alert(id)
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'ingresar';
        }else{
			accion = 'activar';
		}
		
		//~ var padre = $(this).closest('tr');
		//~ var nRow  = padre[0];
		bootbox.confirm("¿Desea "+accion+" el Pedido?", function(result) {
			if (result) {
				
				if (accion == 'ingresar') {
					$("#codpedido_i").val(cod);
					$("#accion_i").val(accion);
					$.fancybox({
						'autoScale': true, 'href': '#m_ingreso', 'type': 'inline', 'hideOnContentClick': true, 'openSpeed': 1000, 'closeSpeed': 1000, 'maxWidth': 960, 'maxHeight': 600, 'width': '1024%', 'height': '70%',
					});
				}else{
					$("#accion_i").val(accion);
					
					var mensaje = "";
					if ($("#accion_i").val() == 'ingresar'){
						mensaje = "ingresado";
					}else{
						mensaje = "activado";
					}
					
					//~ alert("código del pedido: "+$("#codfactura").val());
					
					$.post('<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/ingresar/' + cod, {'accion':accion}, function(response) {
						bootbox.alert("El Pedido fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	   
	});	
	
	// Función para ejecutar la entrega de una factura
	$("#ingresar").on('click', function (e) {
		e.preventDefault();
		
		//~ alert("código del pedido: "+$("#codpedido_i").val());
		
		if ($("#num_control").val() == ''){
			bootbox.alert("Indique el número de control", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#num_control").parent('div').addClass('has-error')
				$("#num_control").val('');
				$("#num_control").focus();
			});
		}else{
			var mensaje = "";
			if ($("#accion_i").val() == 'ingresar'){
				mensaje = "ingresado";
			}else{
				mensaje = "activado";
			}
			$.post('<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/ingresar/' + $("#codpedido_i").val(), {'accion':$("#accion_i").val(), 'num_control':$("#num_control").val(), 'firma_almacen':$("#firma_almacen").val()}, function(response) {
				//~ alert(response[0]);
				if(response[0] == '0'){
					bootbox.alert("El número de control no coincide con el pedido", function () {
					}).on('hidden.bs.modal', function (event) {
						
					});
				}else{
					bootbox.alert("El Pedido fue "+mensaje+" exitosamente", function () {
					}).on('hidden.bs.modal', function (event) {
						
						location.reload();
					});
				}
			})
		}
	});
	
	
	// Función para generar un pedido en una modal
	$("table#tab_pedido").on('click', 'img.generar_pdf', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		
		//~ alert(cod);
		
		//~ return true;
		
		URL = '<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/pdf_pedido/' + cod + '';
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
                            <th style='text-align: center'>Ingresado por</th>
                            <th style='text-align: center'>Fecha de Ingreso</th>
                            <th style='text-align: center'>Hora de Ingreso</th>
                            <th style='text-align: center'>PDF</th>
                            <th style='text-align: center'>ingresar</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar_pedidos as $pedido) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                           
                            <td>
                                <?php echo $pedido->codpedido; ?>
                            </td>
                            <td>
                             <?php echo $pedido->codproveedor; ?>
                            </td>
                            <td>
                             <?php echo $pedido->proveedor; ?>
                            </td>
                             <td>
                               <?php 
								if ($pedido->estado == '1'){
									echo "En proceso";
								}else if($pedido->estado == '2'){
									echo "Aprobado";
								}else if($pedido->estado == '3'){
									echo "Anulado";
								}else if($pedido->estado == '4'){
									echo "Ingresado";
								}
                               ?>
                            </td>
                            <td>
                            <?php echo $pedido->observaciones; ?> 
                            </td>
                            <td>
                            <?php echo $pedido->motivo_anulacion; ?> 
                            </td>
                            <td>
                            <?php echo $pedido->fecha_emision; ?> 
                            </td>
                            <td>
                            <?php echo $pedido->hora_emision; ?> 
                            </td>
                            <td>
                            <?php echo $pedido->firma_almacen; ?> 
                            </td>
                            <td>
                            <?php echo $pedido->fecha_ingreso; ?> 
                            </td>
                            <td>
                            <?php echo $pedido->hora_ingreso; ?> 
                            </td>
                            <td>
                            <img id="<?php echo $pedido->codpedido; ?>" class='generar_pdf' style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/pdf.jpg"/>
                            </td>
                            <td style='text-align: center'>
								<?php if ($pedido->estado == 4) {?>
								<input class='ingresar' id='<?php echo $pedido->codpedido; ?>' type="checkbox" checked="checked" disabled="disabled"/>
								<?php }else if ($pedido->estado==3){ ?>
								<input class='ingresar' id='<?php echo $pedido->codpedido; ?>' type="checkbox" disabled="disabled"/>
								<?php }else{ ?>
								<input class='ingresar' id='<?php echo $pedido->codpedido ?>' type="checkbox" title='ingresar el pedido <?php echo $pedido->codpedido;?>'/>
								<?php } ?> 
                            </td>
                        </tr>
                        <?php $i++ ?>
                        <?php }?>
                        
                    </tbody>
                </table>
                
                <!--Contenedor para ingreso-->
				<div id="m_ingreso" style="width:100%;display:none;">
					<div class="row">
						<div class="form-group">
							<div class="col-sm-12 text-center">
								<span style="font-weight:bold">INGRESO</span>
							</div>
							</br>
							</br>
							</br>
							<label class="l_num_control col-lg-5 control-label" style="font-weight:bold">Código Único:&nbsp;&nbsp;&nbsp;</label>
							<div class="col-lg-6" id="id_num_control">
								<input type="text" placeholder="Código Único" id="num_control" name="num_control" class="form-control">
							</div>
							</br>
							<label class="col-lg-5 control-label" style="font-weight:bold;display:none;" >Firma almacen</label>
							<div class="col-lg-6" style="display:none;">
								<input type="hidden" id="codpedido_i" name="codpedido_i"/>
								<input type="hidden" id="accion_i" name="accion_i"/>
								<input type="text" placeholder="Nombre" id="firma_almacen" name="firma_almacen" class="form-control">
							</div>
							</br>
							</br>
							</br>
							<div class="col-sm-12 text-center">
								<button type="button" data-toggle="modal" data-target="#modal_cliente" class="btn btn-primary" id="ingresar" >
									<i class="fa fa-send fa-lg"></i>&nbsp;&nbsp;Ingresar
								</button>
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
