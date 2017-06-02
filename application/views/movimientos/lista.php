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
    var TMovimientos = $('#tab_movimientos').dataTable({
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
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
        ]
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
              <h3 id="tables" class="lista">Listado de Movimientos</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_movimientos" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
							<th style='text-align: center'>Item</th>
							<th style='text-align: center'>Código</th>
							<th style='text-align: center'>Banco</th>
							<th style='text-align: center'>Cuenta</th>
							<th style='text-align: center'>Monto</th>
							<th style='text-align: center'>tipo</th>
							<th style='text-align: center'>Concepto</th>
							<th style='text-align: center'>Fecha</th>
							<th style='text-align: center'>Hora</th>
							<th style='text-align: center'>Usuario</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i = 1; ?>

						<?php foreach ($listar as $movimiento) { ?>
							<tr style="font-size: 16px;text-align: center" class="{% cycle 'impar' 'par' %}" >
								<td>
									<?php echo $i; ?>
								</td>
								<td>
									<?php echo $movimiento->codigo; ?>
								</td>
								<td>
									<?php 
									foreach ($list_bancos as $banco){
										if($movimiento->banco == $banco->cod_banco){
											echo $banco->banco;
										}else{
											echo "";
										}
									}
									?>
								</td>
								<td>
									<?php 
									foreach ($list_cuentas as $cuenta){
										if($movimiento->cuenta == $cuenta->codigo){
											echo $cuenta->cuenta;
										}else{
											echo "";
										}
									}
									?>
								</td>
								<td>
									<?php echo $movimiento->monto; ?>
								</td>
								<td>
									<?php 
									if($movimiento->tipo == '1'){
										echo "Ingreso";
									}else if($movimiento->tipo == '2'){
										echo "Deducción";
									}else{
										echo "";
									}
									?>
								</td>
								<td>
									<?php echo $movimiento->concepto; ?>
								</td>
								<td>
									<?php echo $movimiento->fecha; ?>
								</td>
								<td>
									<?php echo $movimiento->hora; ?>
								</td>
								<td>
									<?php 
									foreach ($list_usuarios as $usuario){
										if($usuario->id == $movimiento->user_create){
											echo $usuario->username;
										}else{
											echo "";
										}
									}
									?>
								</td>
							</tr>
							<?php $i++ ?>
						<?php } ?>
                        
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
