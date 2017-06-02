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

                $('select').on({
                    change: function () {
                        $(this).parent('div').removeClass('has-error');
                    }
                });
                $('input').on({
                    keypress: function () {
                        $(this).parent('div').removeClass('has-error');
                    }
                });

                $("select").select2();
                $('#monto').numeric({allow: "."}); //Solo permite números

                $('#volver').click(function () {
                    url = '<?php echo base_url()?>index.php/transferencias/ControllersTransferencias'
                    window.location = url
                })
                
                // Función para validar si la cuenta de origen dispone del monto a transferir
                $("#origen,#monto").change(function (event) {
					event.preventDefault();
					if($("#origen").val() != '' && $("#monto").val() != ''){
						$.post('<?php echo base_url(); ?>index.php/transferencias/ControllersTransferencias/consultar/', $('#form_transferencia').serialize(), function (response) {
							var respuesta = response.split('<html>');
							//~ alert(respuesta[0]);
							if (parseFloat(respuesta[0]) < $("#monto").val()) {
								bootbox.alert("El monto es superior al disponible en la cuenta", function () {
								}).on('hidden.bs.modal', function (event) {
									$('#saldo_origen').val(respuesta[0]);
									$('#monto').parent('div').addClass('has-error');
									$('#monto').val('');
									$('#monto').focus();
								});
							}else{
								$('#saldo_origen').val(respuesta[0]);
							}
						});
					}else if($("#origen").val() != '' && $("#monto").val() == ''){
						$.post('<?php echo base_url(); ?>index.php/transferencias/ControllersTransferencias/consultar/', $('#form_transferencia').serialize(), function (response) {
							var respuesta = response.split('<html>');
							$('#saldo_origen').val(respuesta[0]);
						});
					}
                });
                
                
                $("#registrar").click(function (e) {
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    
                    if (($('#monto').val().trim() == '')) {
                        bootbox.alert('Disculpe, debe indicar el monto', function() {
                        }).on('hidden.bs.modal', function (event) {
                            $('#monto').parent('div').addClass('has-error');
                            $('#monto').focus();
                        });
                    }
                    else if (($('#origen').val().trim() == '')) {
                        bootbox.alert('Disculpe, debe seleccionar la cuenta de origen', function() {
                        }).on('hidden.bs.modal', function (event) {
                            $('#origen').parent('div').addClass('has-error');
                            $('#origen').focus();
                        });
                    }
                    else if ($('#destino').val().trim() == '') {
                        bootbox.alert('Disculpe, debe seleccionar la cuenta de destino', function() {	    
                            $('#destino').parent('div').addClass('has-error');
                            $('#destino').focus();
                        });
                    }
                    else {
						
						var respuesta = 0;
						// Primero verificamos si hay disponibilidad de saldo en la cuenta seleccionada
						$.post('<?php echo base_url(); ?>index.php/transferencias/ControllersTransferencias/consultar/', $('#form_transferencia').serialize(), function (response) {
							var respuesta = response.split('<html>');
							//~ alert(respuesta[0]);
							if (parseFloat(respuesta[0]) < $("#monto").val()) {
								bootbox.alert("El monto es superior al disponible en la cuenta", function () {
								}).on('hidden.bs.modal', function (event) {
									$('#monto').parent('div').addClass('has-error');
									$('#monto').val('');
									$('#monto').focus();
								});
							}else{
								// Si hay disponibilidad en la cuenta, registramos la transferencia
								$.post('<?php echo base_url(); ?>index.php/transferencias/ControllersTransferencias/guardar', $('#form_transferencia').serialize(), function (response) {
									bootbox.alert("Se registró con exito", function () {
									}).on('hidden.bs.modal', function (event) {
										url = '<?php echo base_url(); ?>index.php/transferencias/ControllersTransferencias'
										window.location = url
									});
								});
							}
						});
                    }
                });

			});


        </script>
    </head>
    <body>
        <?php
//            echo "<div class='alert alert-dismissible alert-success' style='text-align: center'>";
//            echo "<button type='button' class='close' data-dismiss='alert'>X</button>";
//            echo validation_errors();;
//            echo "</div>";
        ?>

        </br>

        <div class="row-fluid text-center" >
            <div class="mainbody-section">


                <div class="container" style="width:90%;">
                    <div class="row">

                        </br>

                        <div class="col-lg-12">
                            <div class="well bs-component">

                                <form class="form-horizontal" id="form_transferencia">
                                    <fieldset>
                                        <legend>Registrar transferencia</legend>
                                           <br/>
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Código</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" autofocus="" value="<?php printf('TR' . "%05d", (int)$ultimo_id + (int)1); ?>" type='text' id="codigo" name="codigo" readonly="true"/>
                                            </div>
                                            <div class="col-xs-1" style="font-weight:bold">Monto</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" type='text' id="monto" name="monto"/>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Origen</div>
                                            <div class="col-lg-5">
                                                <select id='origen' name="origen" class="form-control">
                                                    <option value=''>Seleccione</option>
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
                                           <div class="col-xs-1" style="font-weight:bold" >Destino</div>
                                            <div class="col-lg-5">
                                               	<select id='destino' name="destino" class="form-control">
                                                    <option value=''>Seleccione</option>
                                                    <?php foreach ($cuentas_activas as $cuenta_act) { ?>
														<option value="<?php echo $cuenta_act->codigo?>">
														<?php 
														foreach ($bancos as $banco) {
															if($banco->cod_banco == $cuenta_act->cod_banco){
																echo $banco->banco;
															}
														}
														?>
														 - <?php echo $cuenta_act->cuenta?>
														</option>
													<?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Saldo origen</div>
                                            <div class="col-lg-5">
                                                <input id='saldo_origen' class="form-control" readonly="true">
                                            </div>
                                            <div class="col-xs-1" style="font-weight:bold" > </div>
                                            <div class="col-lg-5">
												
                                            </div>
                                        </div>
                                        
                                        <br/>

                                        <div class="form-group">
                                            <div class="col-lg-12">
												<input class="form-control"  type='hidden' id="estatus" name="estatus" value="1"/>
                                                <button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning" >
                                                    &nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
                                                </button>
                                                <button type="reset" id="limpiar" style="font-weight: bold;font-size: 13px; color: white " class="btn btn-default"/>
                                                &nbsp;<span class="glyphicon glyphicon-retweet"></span>&nbsp;&nbsp;Limpiar
                                                </button>
                                                <button type="submit" id="registrar" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                                &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Registrar
                                                </button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>

                                <div class="btn btn-primary btn-xs" id="source-button" style="display: none;">&lt; &gt;</div></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
