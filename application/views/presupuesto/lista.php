<html>
<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
} else {
header("location: ../../");
}
?>

        
 <script>
$(document).ready(function(){
    var Tpresupuestos = $('#tab_presupuesto').dataTable({
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
            //~ {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "none", "sWidth": "8%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            //~ {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    
    $('#enviar').click(function(){
        url = '<?php echo base_url()?>index.php/presupuesto/ControllersPresupuesto/presupuesto'
        window.location = url
    })	
	
	$('#chequear').click(function(){
        url = '<?php echo base_url()?>index.php/presupuesto/ControllersPresupuesto/'
        window.location = url
    })
	
	// Función para generar un presupuesto en pdf en una modal
	$("table#tab_presupuesto").on('click', 'img.generar_pdf', function (e) {
		e.preventDefault();
		var cod = this.getAttribute('id');
		
		//~ alert(cod);
		
		//~ return true;
		
		URL = '<?php echo base_url(); ?>index.php/presupuesto/ControllersPresupuesto/pdf_presupuesto/' + cod + '';
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
                    &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Presupuesto
                </button>
                </br>
                </br>
                <div class="page-header">
              <h3 id="tables" class="lista">
				  Listado de Presupuestos
				  <button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white; text-align:center;" id="chequear">
					&nbsp;<span class="glyphicon glyphicon-share-alt"></span>&nbsp;Chequear Estatus
				  </button>
			  </h3>
              
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_presupuesto" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
							
                            <th style='text-align: center'>#</th>
                            <th style='text-align: center'>Cod Presupuesto</th>
                            <th style='text-align: center'>Cod Cliente</th>
                            <th style='text-align: center'>Cliente</th>
                            <th style='text-align: center'>BI</th>
                            <th style='text-align: center'>IVA</th>
                            <th style='text-align: center'>Desct</th>
                            <th style='text-align: center'>Total</th>
                            <th style='text-align: center'>Estatus</th>
                            <th style='text-align: center'>Generar</th>
<!--
                            <th style='text-align: center'>Condición de pago</th>
-->
                            <th style='text-align: center'>Observaciones</th>
                            <th style='text-align: center'>Fecha de Emisión</th>
                            <th style='text-align: center'>Hora de Emisión</th>
                            <th style='text-align: center'>Fecha de Vencimiento</th>
                            <th style='text-align: center'>Hora de Vencimiento</th>
                            <th style='text-align: center'>Editar</th>
<!--
                            <th style='text-align: center'>Anular</th>
-->
                        </tr>
                    </thead>
                    <tbody >    
                       <?php $i=1; ?>
                       <?php foreach ($listar as $presupuesto) { ?>
						<script>
							var cod_presupuesto = '<?php echo $presupuesto->codpresupuesto;?>';
							$.get('<?php echo base_url(); ?>index.php/presupuesto/ControllersPresupuesto/tiempo_emision/'+cod_presupuesto, function (data) {
								var resp = data.split('<html>');
								//~ alert(resp[0]);
								resp = resp[0].split(',')
								var anyo = resp[0];
								var mes = resp[1];
								var dia = resp[2];
								
								//~ if(anyo > 0 || mes > 0 || dia >5){
									//~ alert("Vencido");
								//~ }
							});
						</script>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                           
                            <td>
                                <?php echo $presupuesto->codpresupuesto; ?>
                            </td>
                            <td>
                             <?php echo $presupuesto->codcliente; ?>
                            </td>
                            <td>
                             <?php echo $presupuesto->cliente; ?>
                            </td>
                            <td>
                            <?php echo $presupuesto->base_imponible; ?> 
                            </td>
                            <td>
                            <?php echo $presupuesto->monto_iva; ?> 
                            </td>
                            <td>
                            <?php echo $presupuesto->descuento; ?> 
                            </td>
                            <td>
                            <?php echo $presupuesto->totalpresupuesto; ?> 
                            </td>
                            <td>
                               <?php 
								if ($presupuesto->estado == '1'){
									echo "Emitido";
								}else if($presupuesto->estado == '2'){
									echo "Vencido";
								}else{
									echo "Anulado";
								}
                               ?>
                            </td>
                            <td>
                            <img id="<?php echo $presupuesto->codpresupuesto; ?>" class='generar_pdf' style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/pdf.jpg"/>
                            </td>
<!--
                            <td>
                            <?php 
								//~ if ($presupuesto->condicion_pago == '1'){
									//~ echo "Cheque";
								//~ }else if($presupuesto->condicion_pago == '2'){
									//~ echo "Debito";
								//~ }else if($presupuesto->condicion_pago == '3'){
									//~ echo "Efectivo";
								//~ }else{
									//~ echo "";
								//~ }
							?> 
                            </td>
-->
                            <td>
                            <?php echo $presupuesto->observaciones; ?> 
                            </td>
                            <td>
                            <?php echo $presupuesto->fecha_emision; ?> 
                            </td>
                            <td>
                            <?php echo $presupuesto->hora_emision; ?> 
                            </td>
                            <td>
                            <?php echo $presupuesto->fecha_vencimiento; ?> 
                            </td>
                            <td>
                            <?php echo $presupuesto->hora_vencimiento; ?> 
                            </td>
                            <td style='text-align: center'>
                                <a href="<?php echo base_url()?>index.php/presupuesto/ControllersPresupuesto/editar/<?= $presupuesto->codpresupuesto; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td>
<!--
                            <td style='text-align: center'>
								<?php if ($presupuesto->estado == 3) {?>
								<input class='anular' id='<?php echo $presupuesto->codpresupuesto; ?>' type="checkbox" title='Activar la factura <?php echo $presupuesto->codpresupuesto;?>' checked="checked"/>
								<?php }else{ ?> 
								<input class='anular' id='<?php echo $presupuesto->codpresupuesto; ?>' type="checkbox" title='Anular la factura <?php echo $presupuesto->codpresupuesto;?>'/>
								<?php } ?> 
                            </td>
-->
                        </tr>
                        <?php $i++ ?>
                        <?php 
                        }
                        //~ echo "<script>
							//~ url = '".base_url()."index.php/presupuesto/ControllersPresupuesto';
							//~ window.location = url;
                        //~ </script>";
                        ?>
                        
                    </tbody>
                </table>                
                
                <div id="m_anulacion" style="width:100%;display:none;">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<span >Describa el motivo de la anulación</span>
								<div class="input-group mar-btm">
									<input type="hidden" id="codpresupuesto" name="codpresupuesto"/>
									<input type="hidden" id="accion" name="accion"/>
									<textarea style="width: 100%;" name="motivo_anulacion" id="motivo_anulacion" placeholder="Indique el motivo" class="form-control"></textarea>
									<button type="button" data-toggle="modal" data-target="#modal_cliente" class="btn btn-primary" id="anular" >
										<i class="fa fa-send fa-lg"></i>&nbsp;&nbsp;Enviar
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
