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
        $(document).ready(function () {
            var TTCuenta = $('#tab_cuentas').dataTable({
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
					{"sClass": "registro center", "sWidth": "8%"},
					{"sClass": "registro center", "sWidth": "10%"},
					{"sClass": "registro center", "sWidth": "20%"},
					{"sClass": "registro center", "sWidth": "10%"},
					{"sClass": "registro left","sWidth": "5%" },
					{"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
					{"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
                ]
            });
            
            // Función para activar/desactivar un cargo
			$("table#tab_cuentas").on('click', 'input.activar_desactivar', function (e) {
				e.preventDefault();
				var id = this.getAttribute('id');
				//alert(id)
				
				var check = $(this);
				
				//~ alert(check.prop('checked'));
				
				var accion = '';
				if (check.is(':checked')) {
					accion = 'activar';
				}else{
					accion = 'desactivar';
				}
				
				//~ var padre = $(this).closest('tr');
				//~ var nRow  = padre[0];
				bootbox.confirm("¿Desea "+accion+" la cuenta?", function(result) {
					if (result) {
						$("#accion").val(accion);
						
						var mensaje = "";
						if (accion == 'desactivar'){
							mensaje = "desactivada";
						}else{
							mensaje = "activada";
						}
						
						//~ alert("código de la factura: "+$("#codfactura").val());
						//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
						
						$.post('<?php echo base_url(); ?>index.php/cuentas/ControllersCuentas/activar_desactivar/' + id, {'accion':accion}, function(response) {
							bootbox.alert("La cuenta fue "+mensaje+" exitosamente", function () {
							}).on('hidden.bs.modal', function (event) {
								location.reload();
							});
						})
						
					}
				}); 
		   
			});
			
            
            $('#enviar').click(function () {
                url = '<?php echo base_url() ?>index.php/cuentas/ControllersCuentas/registrar';
                window.location = url;
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
                        &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Agregar cuenta
                    </button>
                    </br>
                    </br>
                    <div class="page-header">
                        <h3 id="tables" class="lista">Listado de cuentas</h3>
                    </div>

                    <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_cuentas" align="center"
                           class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                        <thead style="font-size: 14px">
                            <tr class="info">
                                <th style='text-align: center'>Item</th>
                                <th style='text-align: center'>Código</th>
                                <th style='text-align: center'>Banco</th>
                                <th style='text-align: center'>Cuenta</th>
                                <th style='text-align: center'>Tipo</th>
                                <th style='text-align: center'>Estatus</th>
                                <th style='text-align: center'>Editar</th>
                                <th style='text-align: center'>Activar/Desactivar</th>
                            </tr>
                        </thead>
                        <tbody >    
                            <?php $i = 1; ?>

                            <?php foreach ($listar as $cuenta) { if($cuenta->cuenta != "00000000000000000000"){?>
                                <tr style="font-size: 16px;text-align: center" class="{% cycle 'impar' 'par' %}" >
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $cuenta->codigo; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        foreach ($list_bancos as $banco){
											if($cuenta->cod_banco == $banco->cod_banco){
												echo $banco->banco;
											}else{
												echo "";
											}
										}
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $cuenta->cuenta; ?>
                                    </td>
                                    <td>
                                        <?php
											if($cuenta->tipo == 1){
												echo "Corriente";
											}else if($cuenta->tipo == 2){
												echo "Corriente con intereses";
											}else if($cuenta->tipo == 3){
												echo "Ahorro";
											}else if($cuenta->tipo == 4){
												echo "En efectivo";
											}
                                        ?>
                                    </td>
                                    <td>
									<?php if ($cuenta->estatus == 1): ?>
										<img style="width:15px;height: 15px" src="<?php echo base_url()?>static/img/yes.png"/>
									<?php else: ?>
										<img style="width:15px;height: 15px" src="<?php echo base_url()?>static/img/no.png"/>
									<?php endif; ?>
									</td>
                                    <td style='text-align: center'>
                                        <a title="Editar" href="<?php echo base_url() ?>index.php/cuentas/ControllersCuentas/editar/<?= $cuenta->id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                                    </td>
                                    <td style='text-align: center'>
										<?php if ($cuenta->estatus == 1) {?>
										<input class='activar_desactivar' id='<?php echo $cuenta->id; ?>' type="checkbox" title='Desactivar el cuenta <?php echo $cuenta->id;?>' checked="checked"/>
										<?php }else if ($cuenta->estatus == 0){ ?>
										<input class='activar_desactivar' id='<?php echo $cuenta->id; ?>' type="checkbox" title='Activar el cuenta <?php echo $cuenta->id;?>'/>
										<?php } ?>
									</td>
                                </tr>
                                <?php $i++ ?>
                            <?php } }?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
