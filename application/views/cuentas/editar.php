<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
$tipouser = ($this->session->userdata['logged_in']['tipo_usuario']);
$id_user = ($this->session->userdata['logged_in']['id']);
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
                $('#cuenta').numeric(); //Solo permite números
                $('#monto_inicial').numeric({allow: "."}); //Solo permite números
                $('#monto_total').numeric({allow: "."}); //Solo permite números
                
                $("#cod_banco").select2('val',$("#id_banco").val());
                $("#tipo").select2('val',$("#id_tipo").val());
                //~ $("#tipo_usuario").select2('val',$("#id_tipo_usuario").val());

                $('#volver').click(function () {
                    url = '<?php echo base_url()?>index.php/cuentas/ControllersCuentas'
                    window.location = url
                })

                // Función para cargar el monto total automáticamente
                $("#monto_inicial").change(function (event) {
					$("#monto_total").val($(this).val());
                });

                $("#registrar").click(function (e) {
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    
                    if (($('#cod_banco').val().trim() == '')) {
                        bootbox.alert('Disculpe, debe seleccionar el banco', function() {
                        }).on('hidden.bs.modal', function (event) {
                            $('#cod_cuenta').parent('div').addClass('has-error');
                            $('#cod_cuenta').focus();
                        });
                    }
                    else if (($('#cuenta').val().trim() == '')) {
                        bootbox.alert('Disculpe, debe colocar el número de la cuenta', function() {
                        }).on('hidden.bs.modal', function (event) {
                            $('#cuenta').parent('div').addClass('has-error');
                            $('#cuenta').focus();
                        });
                    }
                    else if ($('#tipo').val().trim() == '') {
                        bootbox.alert('Disculpe, debe seleccionar el tipo de cuenta', function() {	    
                            $('#tipo').parent('div').addClass('has-error');
                            $('#tipo').focus();
                        });
                    }else if ($('#monto_inicial').val().trim() == '') {
                        bootbox.alert('Disculpe, debe indicar el monto inicial', function() {	    
                            $('#monto_inicial').parent('div').addClass('has-error');
                            $('#monto_inicial').focus();
                        });
                    }
                    //~ else if ($('#simbolo').val().trim() == '') {
                        //~ bootbox.alert('Disculpe, Debe colocar el simbolo de la unidad de medida', function() {	    
                            //~ $('#simbolo').parent('div').addClass('has-error');
                        //~ });
                    //~ }else if ($('#tipo').val().trim() == '') {
                        //~ bootbox.alert('Disculpe, Debe colocar el tipo de la unidad de medida', function() {	    
                            //~ $('#tipo').parent('div').addClass('has-error');
                        //~ });
                    //~ }
                    else {
                        
                         $.post('<?php echo base_url(); ?>index.php/cuentas/ControllersCuentas/actualizar', $('#form_cuenta').serialize(), function (response) {

                            bootbox.alert("Se actualizo con exito", function () {
                            }).on('hidden.bs.modal', function (event) {
                                url = '<?php echo base_url(); ?>index.php/cuentas/ControllersCuentas'
                                window.location = url
                            });

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

                                <form class="form-horizontal" id="form_cuenta">
                                    <fieldset>
                                        <legend>Editar Cuenta</legend>
                                           <br/>
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Código</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" autofocus="" value="<?php echo $editar[0]->codigo;?>" type='text' id="codigo" name="codigo" readonly="true"/>
                                            </div>
                                           <div class="col-xs-1" style="font-weight:bold" >Banco</div>
                                            <div class="col-lg-5">
                                               	<select id="cod_banco" name="cod_banco" class="form-control">
													<option value="0">Seleccione</option>
													<?php foreach ($list_bancos as $banco) { ?>
														<option value="<?php echo $banco->cod_banco?>"><?php echo $banco->banco?></option>
													<?php }?>
												</select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Cuenta</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" id="cuenta" maxlength="20" name="cuenta" value="<?php echo $editar[0]->cuenta ?>"/>
                                            </div>
                                           <div class="col-xs-1" style="font-weight:bold" >Tipo</div>
                                            <div class="col-lg-5">
                                               	<select id='tipo' name="tipo" class="form-control">
                                                    <option value='0'>Seleccione</option>
                                                    <option value='1'>Corriente</option>
                                                    <option value='2'>Corriente con intereses</option>
                                                    <option value='3'>Ahorro</option>
                                                    <option value='4'>En efectivo</option>
                                                </select>
                                            </div>
                                        </div> 
                                        
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Monto Inicial</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" type='text' id="monto_inicial" name="monto_inicial" value="<?php echo $editar[0]->monto_inicial ?>"/>
                                            </div>
                                           <div class="col-xs-1" style="font-weight:bold" >Monto Total</div>
                                            <div class="col-lg-5">
                                               	<input class="form-control" type='text' id="monto_total" name="monto_total" value="<?php echo $editar[0]->monto_total ?>" readonly="true"/>
                                            </div>
                                        </div>   
                                        
                                        <br/>

                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <input class="form-control"  type='hidden' placeholder="id" id="id" name="id" value="<?php echo $id ?>"/>
                                                <input class="form-control"  type='hidden' placeholder="banco" id="id_banco" value="<?php echo $editar[0]->cod_banco ?>"/>
                                                <input class="form-control"  type='hidden' placeholder="tipo" id="id_tipo" value="<?php echo $editar[0]->tipo ?>"/>
                                                <button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning" >
                                                    &nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
                                                </button>
                                                <button type="submit" id="registrar" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                                &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Actualizar
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
