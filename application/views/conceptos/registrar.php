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
                $('#concepto').alpha({allow:" "}); //Solo permite texto
                $('#monto').numeric({allow: "."}); //Solo permite números

                $('#volver').click(function () {
                    url = '<?php echo base_url()?>index.php/conceptos/ControllersConceptos'
                    window.location = url
                })
                
                // Función para validar por banco y concepto si ya existe la concepto
                $("#concepto").change(function (event) {

                    var banco = $('#banco').val();
                    $.post('<?php echo base_url(); ?>index.php/conceptos/ControllersConceptos/consultar/', $('#form_concepto').serialize(), function (response) {
						//~ alert(response[0])
						if (response[0] == 'e') {
							bootbox.alert("El concepto ya existe", function () {
							}).on('hidden.bs.modal', function (event) {
								$('#concepto').parent('div').addClass('has-error');
								$('#concepto').val('');
								$('#concepto').focus();
							});
						}

					});
                });

                $("#registrar").click(function (e) {
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    
                    if (($('#concepto').val().trim() == '')) {
                        bootbox.alert('Disculpe, debe colocar el nombre del concepto', function() {
                        }).on('hidden.bs.modal', function (event) {
                            $('#concepto').parent('div').addClass('has-error');
                            $('#concepto').focus();
                        });
                    }
                    else if ($('#tipo').val().trim() == '') {
                        bootbox.alert('Disculpe, debe seleccionar el tipo de concepto', function() {	    
                            $('#tipo').parent('div').addClass('has-error');
                            $('#tipo').focus();
                        });
                    }else if ($('#monto').val().trim() == '') {
                        bootbox.alert('Disculpe, debe indicar el monto', function() {	    
                            $('#monto').parent('div').addClass('has-error');
                            $('#monto').focus();
                        });
                    }
                    else {
                        
                         $.post('<?php echo base_url(); ?>index.php/conceptos/ControllersConceptos/guardar', $('#form_concepto').serialize(), function (response) {

                            bootbox.alert("Se registro con exito", function () {
                            }).on('hidden.bs.modal', function (event) {
                                url = '<?php echo base_url(); ?>index.php/conceptos/ControllersConceptos'
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

                                <form class="form-horizontal" id="form_concepto">
                                    <fieldset>
                                        <legend>Registrar Salario</legend>
                                           <br/>
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Código</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" autofocus="" value="<?php printf('CP' . "%05d", (int)$ultimo_id + (int)1); ?>" type='text' placeholder="CP00001" id="codigo" name="codigo" readonly="true"/>
                                            </div>
                                           <div class="col-xs-1" style="font-weight:bold" >Salario</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" id="concepto" name="concepto" placeholder="Nombre Salario"/>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Tipo</div>
                                            <div class="col-lg-5">
                                               	<select id='tipo' name="tipo" class="form-control">
                                                    <option value='0'>Seleccione</option>
                                                    <option value='1'>Ingreso</option>
                                                    <!--<option value='2'>Deducción</option>-->
                                                </select>
                                            </div>
											<div class="col-xs-1" style="font-weight:bold" >Monto</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" type='text' id="monto" name="monto" placeholder="00000.00"/>
                                            </div>
                                        </div>  
                                        
                                        <br/>

                                        <div class="form-group">
                                            <div class="col-lg-12">
<!--                                            <input class="form-control"  type='hidden' placeholder="user" id="user_create_id" name="user_create_id" value="<?php echo $id_user ?>"/>-->
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
