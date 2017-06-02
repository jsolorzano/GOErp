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
    var Tajustes = $('#tab_ajustes').dataTable({
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
            {"sClass": "registro center", "sWidth": "8%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            //~ {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
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
        url = '<?php echo base_url()?>index.php/ajustes/ControllersAjustes/ajuste'
        window.location = url
    })
	
	// Función para preparar la anulación de un autoconsumo
	$("table#tab_ajustes").on('click', 'input.anular', function (e) {
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
		bootbox.confirm("¿Desea "+accion+" el Ajuste?", function(result) {
			if (result) {
				
				if (accion == 'anular') {
					$("#codajuste").val(cod);
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
					
					//~ alert("código de la factura: "+$("#codfactura").val());
					//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
					
					$.post('<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes/anular/' + cod, {'accion':accion, 'motivo':$("#motivo_anulacion").val()}, function(response) {
						bootbox.alert("El ajuste fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							//~ url = '<?php echo base_url(); ?>index.php/autoconsumo/ControllersAutoconsumo'
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	   
	});
	
	
	// Función para aplicar la anulación de una factura
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
				mensaje = "anulado";
			}else{
				mensaje = "activado";
			}
			$.post('<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes/anular/' + $("#codajuste").val(), {'accion':$("#accion").val(), 'motivo':$("#motivo_anulacion").val()}, function(response) {
				bootbox.alert("El ajuste fue "+mensaje+" exitosamente", function () {
				}).on('hidden.bs.modal', function (event) {
					//~ url = '<?php echo base_url(); ?>index.php/autoconsumo/ControllersAutoconsumo'
					location.reload();
				});
			})
		}
	});
	
	
	// Función para preparar la ejecución de un autoconsumo o avería
	$("table#tab_ajustes").on('click', 'input.aplicar', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		//alert(id)
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'aplicar';
        }else{
			accion = 'activar';
		}
		
		//~ var padre = $(this).closest('tr');
		//~ var nRow  = padre[0];
		bootbox.confirm("¿Desea "+accion+" el ajuste?", function(result) {
			if (result) {
				
				if (accion == 'aplicar') {
					$("#codajuste").val(cod);
					$("#accion").val(accion);
					
					var mensaje = "";
					if ($("#accion").val() == 'aplicar'){
						mensaje = "Aplicado";
					}else{
						mensaje = "activado";
					}
					
					//~ alert("código de la factura: "+$("#codfactura").val());
					//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
					
					$.post('<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes/aplicar/' + cod, {'accion':accion}, function(response) {
						bootbox.alert("El ajuste fue "+mensaje+" exitosamente", function () {
						}).on('hidden.bs.modal', function (event) {
							location.reload();
						});
					})
				}
				
			}
		}); 
	   
	   
	});
	
	
	// Función para generar una factura en una modal
	$("table#tab_ajustes").on('click', 'img.generar_pdf', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		
		//~ alert(cod);
		
		//~ return true;
		
		URL = '<?php echo base_url(); ?>index.php/ajustes/ControllersAjustes/pdf_ajuste/' + cod + '';
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
                <button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar">
                    &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Ajuste
                </button>
                </br>
                </br>
                <div class="page-header">
              <h3 id="tables" class="lista">Listado de Ajustes</h3>
            </div>
                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_ajustes" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
                            <th style='text-align: center'>#</th>
                            <th style='text-align: center'>Cod Ajuste</th>
                            <th style='text-align: center'>Cod Factura</th>
                            <th style='text-align: center'>Rif Cliente</th>
                            <th style='text-align: center'>Cliente</th>
                            <th style='text-align: center'>BI</th>
                            <th style='text-align: center'>IVA</th>
                            <!--<th style='text-align: center'>Desct</th>-->
                            <th style='text-align: center'>Total</th>
                            <th style='text-align: center'>Estatus</th>
                            <th style='text-align: center'>Generar</th>
                            <th style='text-align: center'>Aplicar</th>
                            <th style='text-align: center'>Tipo de ajuste</th>
                            <th style='text-align: center'>concepto</th>
                            <th style='text-align: center'>Motivo Anulación</th>
                            <th style='text-align: center'>Fecha de Ajuste</th>
                            <th style='text-align: center'>Hora de Ajuste</th>
                            <th style='text-align: center'>Editar</th>
                            <th style='text-align: center'>Anular</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar as $ajuste) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                            <td>
                                <?php echo $ajuste->codajuste; ?>
                            </td>
                            <td>
                             <?php echo $ajuste->codfactura ?>
                            </td>
                            <td>
                             <?php echo $ajuste->rifcliente; ?>
                            </td>
                            <td>
                             <?php echo $ajuste->cliente; ?>
                            </td>
                            <td>
                            <?php echo $ajuste->base_imponible; ?> 
                            </td>
                            <td>
                            <?php echo $ajuste->monto_iva; ?> 
                            </td>
                            <!--<td>
                            <?php //echo $ajuste->descuento; ?> 
                            </td>-->
                            <td>
                            <?php echo $ajuste->totalajuste; ?> 
                            </td>
                            <td>
                               <?php 
								if ($ajuste->estado == '1'){
									echo "En proceso";
								}else if($ajuste->estado == '2'){
									echo "Aplicado";
								}else if($ajuste->estado == '3'){
									echo "Anulado";
								}else if($ajuste->estado == '4'){
									echo "Ejecutado";
								}
                               ?>
                            </td>
                            <td>
                            <img id="<?php echo $ajuste->codajuste; ?>" class='generar_pdf' style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/pdf.jpg"/>
                            </td>
                            <td style='text-align: center'>
								<?php if ($ajuste->estado == 2 || $ajuste->estado == 4) {?>
								<input class='aplicar' id='<?php echo $ajuste->codajuste; ?>' type="checkbox" checked="checked" disabled="disabled"/>
								<?php }else if ($ajuste->estado==3){ ?>
								<input class='aplicar' id='<?php echo $ajuste->codajuste; ?>' type="checkbox" disabled="disabled"/>
								<?php }else{ ?>
								<input class='aplicar' id='<?php echo $ajuste->codajuste; ?>' type="checkbox" title='aplicar el ajuste <?php echo $ajuste->codajuste;?>'/>
								<?php } ?> 
                            </td>
                            <td>
                            <?php 
								if ($ajuste->tipo_ajuste == '1'){
									echo "Nota de Credito";
								}else if($ajuste->tipo_ajuste == '2'){
									echo "Nota de Debito";
								}
							?> 
                            </td>
                            <td>
                            <?php echo $ajuste->concepto; ?> 
                            </td>
                            <td>
                            <?php echo $ajuste->motivo_anulacion; ?> 
                            </td>
                            <td>
                            <?php echo $ajuste->fecha_ajuste; ?> 
                            </td>
                            <td>
                            <?php echo $ajuste->hora_ajuste; ?> 
                            </td>
                            <td style='text-align: center'>
								<?php if ($ajuste->estado == 3 || $ajuste->estado == 2 || $ajuste->estado == 4) {?>
                                <img id="<?php echo $ajuste->codajuste; ?>" style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/block.png"/>
                                <?php }else{ ?>
								<a href="<?php echo base_url()?>index.php/ajustes/ControllersAjustes/editar/<?= $ajuste->codajuste; ?>"><i class="glyphicon glyphicon-edit"></i></a>
								<?php } ?> 
                            </td>
                            <td style='text-align: center'>
								<?php if ($ajuste->estado == 3) {?>
								<input class='anular' id='<?php echo $ajuste->codajuste; ?>' type="checkbox" title='Anular el ajuste <?php echo $ajuste->codajuste;?>' checked="checked" disabled="disabled"/>
								<?php }else if ($ajuste->estado == 2 || $ajuste->estado == 4){ ?>
								<input class='anular' id='<?php echo $ajuste->codajuste; ?>' type="checkbox" title='Anular el ajuste <?php echo $ajuste->codajuste;?>' disabled="disabled"/>
								<?php }else{ ?> 
								<input class='anular' id='<?php echo $ajuste->codajuste; ?>' type="checkbox" title='Anular el ajuste <?php echo $ajuste->codajuste;?>'/>
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
									<input type="hidden" id="codajuste" name="codajuste"/>
									<input type="hidden" id="accion" name="accion"/>
									<textarea style="width: 100%;" name="motivo_anulacion" id="motivo_anulacion" placeholder="Indique el motivo" class="form-control"></textarea>
									<button type="button" data-toggle="modal" data-target="#modal_cliente" class="btn btn-primary" id="anular" >
										<i class="fa fa-send fa-lg"></i>&nbsp;&nbsp;Anular
									</button>
								</div>
							</div>
						</div>
				   </div>
				</div>
				
				<!--Contenedor para pago-->
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
