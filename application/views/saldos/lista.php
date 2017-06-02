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
    var TCuentas = $('#tab_cuentas').dataTable({
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
			{"sClass": "registro center", "sWidth": "20%"},
			{"sClass": "registro center", "sWidth": "20%"},
			{"sClass": "registro center", "sWidth": "10%"},
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
              <h3 id="tables" class="lista">Listado de Cuentas</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_cuentas" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
							<th style='text-align: center'>Item</th>
							<th style='text-align: center'>Banco</th>
							<th style='text-align: center'>Cuenta</th>
							<th style='text-align: center'>Saldo</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i = 1; ?>

						<?php foreach ($listar as $cuenta) { if($cuenta->cuenta != "00000000000000000000"){ ?>
							<tr style="font-size: 16px;text-align: center" class="{% cycle 'impar' 'par' %}" >
								<td>
									<?php echo $i; ?>
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
									<?php echo $cuenta->monto_total; ?>
								</td>
							</tr>
							<?php $i++ ?>
						<?php }} ?>
                        
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
