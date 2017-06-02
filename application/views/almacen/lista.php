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
            {"sClass": "none", "sWidth": "8%"}
            //~ {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            //~ {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    
    $('#num_control').numeric(); //Solo permite números
    $('#firma_almacen').alpha({allow: " "}); //Solo permite texto
    
    // Ejecutamos el contador de facturas verificadas por entregar
    $.post('<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/contar/', function(response) {
		var respuesta = response.split('<html>');
		//~ alert(respuesta[0]);
		if(respuesta[0] > 0){
			bootbox.alert("Existen "+respuesta[0]+" Factura(s) Verificada(s) por Entregar", function () {
			}).on('hidden.bs.modal', function (event) {
				
			});
		}
	});
	
	// Función para preparar la entrega de una factura
	$("table#tab_factura").on('click', 'input.entregar', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		//alert(id)
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'entregar';
        }else{
			accion = 'activar';
		}
		
		//~ var padre = $(this).closest('tr');
		//~ var nRow  = padre[0];
		bootbox.confirm("¿Desea "+accion+" la Factura?", function(result) {
			if (result) {
				
				if (accion == 'entregar') {
					$("#codfactura_e").val(cod);
					$("#accion_e").val(accion);
					$.fancybox({
						'autoScale': true, 'href': '#m_entrega', 'type': 'inline', 'hideOnContentClick': true, 'openSpeed': 1000, 'closeSpeed': 1000, 'maxWidth': 960, 'maxHeight': 600, 'width': '1024%', 'height': '70%',
					});
				}else{
					$("#accion_e").val(accion);
					
					var mensaje = "";
					if ($("#accion_e").val() == 'entregar'){
						mensaje = "entregada";
					}else{
						mensaje = "activada";
					}
					
					//~ alert("código de la factura: "+$("#codfactura").val());
					
					$.post('<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/entregar/' + cod, {'accion':accion}, function(response) {
						bootbox.alert("La factura fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen'
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	   
	});	
	
	// Función para ejecutar la entrega de una factura
	$("#entregar").on('click', function (e) {
		e.preventDefault();
		
		//~ alert("código de la factura: "+$("#codfactura").val());
		//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
		
		if ($("#num_control").val() == ''){
			bootbox.alert("Indique el número de control", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#num_control").parent('div').addClass('has-error')
				$("#num_control").val('');
				$("#num_control").focus();
			});
		}else{
			var mensaje = "";
			if ($("#accion_e").val() == 'entregar'){
				mensaje = "entregada";
			}else{
				mensaje = "activada";
			}
			$.post('<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/entregar/' + $("#codfactura_e").val(), {'accion':$("#accion_e").val(), 'num_control':$("#num_control").val(), 'firma_almacen':$("#firma_almacen").val()}, function(response) {
				//~ alert(response[0]);
				if(response[0] == '0'){
					bootbox.alert("El número de control no coincide con la factura", function () {
					}).on('hidden.bs.modal', function (event) {
						
					});
				}else{
					bootbox.alert("La factura fue "+mensaje+" exitosamente", function () {
					}).on('hidden.bs.modal', function (event) {
						
						location.reload();
					});
				}
			})
		}
	});
	
	
	// Función para generar una factura en una modal
	$("table#tab_factura").on('click', 'img.generar_pdf', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		
		//~ alert(cod);
		
		//~ return true;
		
		URL = '<?php echo base_url(); ?>index.php/almacen/ControllersAlmacen/pdf_factura/' + cod + '';
		$.fancybox.open({ padding : 0, href: URL, type: 'iframe',width: 1024, height: 860, });
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
                            <th style='text-align: center'>PDF</th>
                            <th style='text-align: center'>Entregar</th>
                            <th style='text-align: center'>Condición de pago</th>
                            <th style='text-align: center'>Observaciones</th>
                            <th style='text-align: center'>Motivo Anulación</th>
                            <th style='text-align: center'>Fecha de Emisión</th>
                            <th style='text-align: center'>Hora de Emisión</th>
                            <th style='text-align: center'>Entregado por</th>
                            <th style='text-align: center'>Fecha de Entrega</th>
                            <th style='text-align: center'>Hora de Entrega</th>
                            <!--<th style='text-align: center'>Editar</th>
                            <th style='text-align: center'>Anular</th>-->
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar_facturas as $factura) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                           
                            <td>
                                <?php echo $factura->codfactura; ?>
                            </td>
                            <td>
                             <?php echo $factura->codcliente; ?>
                            </td>
                            <td>
                             <?php echo $factura->cliente; ?>
                            </td>
                            <td>
                            <?php echo $factura->base_imponible; ?> 
                            </td>
                            <td>
                            <?php echo $factura->monto_iva; ?> 
                            </td>
                            <td>
                            <?php echo $factura->descuento; ?> 
                            </td>
                            <td>
                            <?php echo $factura->totalfactura; ?> 
                            </td>
                             <td>
                               <?php 
								if ($factura->estado == '1'){
									echo "En Proceso";
								}else if($factura->estado == '2'){
									echo "Por Entregar";
								}else if($factura->estado == '3'){
									echo "Anulado";
								}else if($factura->estado == '4'){
									echo "Entregado";
								}
                               ?>
                            </td>
                            <td>
                            <img id="<?php echo $factura->codfactura; ?>" class='generar_pdf' style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/pdf.jpg"/>
                            </td>
                            <td style='text-align: center'>
								<?php if ($factura->estado == 4) {?>
								<input class='entregar' id='<?php echo $factura->codfactura; ?>' type="checkbox" checked="checked" disabled="disabled"/>
								<?php }else if ($factura->estado==3){ ?>
								<input class='entregar' id='<?php echo $factura->codfactura; ?>' type="checkbox" disabled="disabled"/>
								<?php }else{ ?>
								<input class='entregar' id='<?php echo $factura->codfactura; ?>' type="checkbox" title='entregar la factura <?php echo $factura->codfactura;?>'/>
								<?php } ?> 
                            </td>
                            <td>
                            <?php 
								if ($factura->condicion_pago == '1'){
									echo "Cheque ($factura->num_cheque)";
								}else if($factura->condicion_pago == '2'){
									echo "Debito ($factura->num_recibo)";
								}else if($factura->condicion_pago == '3'){
									echo "Efectivo";
								}else if($factura->condicion_pago == '4'){
									echo "Transferencia ($factura->num_transf)";
								}else if($factura->condicion_pago == '5'){
									echo "Depósito ($factura->num_deposito)";
								}else{
									echo "";
								}
							?> 
                            </td>
                            <td>
                            <?php echo $factura->observaciones; ?> 
                            </td>
                            <td>
                            <?php echo $factura->motivo_anulacion; ?> 
                            </td>
                            <td>
                            <?php echo $factura->fecha_emision; ?> 
                            </td>
                            <td>
                            <?php echo $factura->hora_emision; ?> 
                            </td>
                            <td>
                            <?php echo $factura->firma_almacen; ?> 
                            </td>
                            <td>
                            <?php echo $factura->fecha_entrega; ?> 
                            </td>
                            <td>
                            <?php echo $factura->hora_entrega; ?> 
                            </td>
                        </tr>
                        <?php $i++ ?>
                        <?php }?>
                        
                    </tbody>
                </table>
				
				<!--Contenedor para entrega-->
				<div id="m_entrega" style="width:100%;display:none;">
					<div class="row">
						<div class="form-group">
							<div class="col-sm-12 text-center">
								<span style="font-weight:bold">ENTREGA</span>
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
								<input type="hidden" id="codfactura_e" name="codfactura_e"/>
								<input type="hidden" id="accion_e" name="accion_e"/>
								<input type="text" placeholder="Nombre" id="firma_almacen" name="firma_almacen" class="form-control">
							</div>
							</br>
							</br>
							</br>
							<div class="col-sm-12 text-center">
								<button type="button" data-toggle="modal" data-target="#modal_cliente" class="btn btn-primary" id="entregar" >
									<i class="fa fa-send fa-lg"></i>&nbsp;&nbsp;Entregar
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
