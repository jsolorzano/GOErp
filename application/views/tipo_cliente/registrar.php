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
                $("#cedula").numeric(); //Valida solo permite valores numericos
                $('#email').alphanumeric({allow: "@-_."});
                $('#siglas').alpha({allow: " "}); //Solo permite texto
                $('#direccion').alphanumeric({allow: " ,.-#"}); //Solo permite texto numericos"

                $('#volver').click(function () {
                    url = '<?php echo base_url()?>index.php/tipo_cliente/ControllersTipoCliente'
                    window.location = url
                })

                //~ $("#cedula").change(function (event) {
//~ 
                    //~ var cedula = $('#cedula').val();
                    //~ //var hosting = $('#id_hosting').val(); // Captura del hosting (dominio)
                    //~ var hosting = "www.consultaelectoral.org.ve/cedula="
                    //~ if (hosting) {
                        //~ $.get("http://" + hosting + cedula, function (data) {
                            //~ var option = "";
                            //~ $.each(data, function (i) {
                                //~ $("#first_name").val(data[i].p_nombre + " " + data[i].s_nombre)
                                //~ $("#last_name").val(data[i].p_apellido + " " + data[i].s_apellido)
                            //~ });
                            //~ // Proceso para validar con la clase error 404 Not Found
                        //~ }, 'json');
                    //~ }
                //~ });
                
                // Función para validar por nombre si ya existe el tipo de cliente
                $("#tipo_cliente").change(function (event) {

                    var tipo_cliente = $('#tipo_cliente').val();
                    $.post('<?php echo base_url(); ?>index.php/tipo_cliente/ControllersTipoCliente/consultar/', $('#form_tipocliente').serialize(), function (response) {
						//~ alert(response[0])
						if (response[0] == 'e') {
							bootbox.alert("El tipo de cliente ya existe", function () {
							}).on('hidden.bs.modal', function (event) {
								$('#tipo_cliente').parent('div').addClass('has-error');
								$('#tipo_cliente').val('');
								$('#tipo_cliente').focus();
							});
						}

					});
                });

                $("#registrar").click(function (e) {
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    
                    if (($('#cod_tipo').val().trim() == '')) {
                        bootbox.alert('Disculpe, debe colocar el Código del tipo de cliente', function() {
                        }).on('hidden.bs.modal', function (event) {
                            $('#cod_tipo').parent('div').addClass('has-error');
                            $('#cod_tipo').focus();
                        });
                    }
                    else if (($('#tipo_cliente').val().trim() == '')) {
                        bootbox.alert('Disculpe, debe colocar la Descripción del tipo de cliente', function() {
                        }).on('hidden.bs.modal', function (event) {
                            $('#tipo_cliente').parent('div').addClass('has-error');
                            $('#tipo_cliente').focus();
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
                        
                         $.post('<?php echo base_url(); ?>index.php/tipo_cliente/ControllersTipoCliente/guardar', $('#form_tipocliente').serialize(), function (response) {

                            bootbox.alert("Se registro con exito", function () {
                            }).on('hidden.bs.modal', function (event) {
                                url = '<?php echo base_url(); ?>index.php/tipo_cliente/ControllersTipoCliente'
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

                                <form class="form-horizontal" id="form_tipocliente">
                                    <fieldset>
                                        <legend>Registrar Tipo de Cliente</legend>
                                           <br/>
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Código</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" autofocus="" value="<?php printf('TC' . "%05d", (int)$ultimo_id + (int)1); ?>" type='text' placeholder="TC00001" id="cod_tipo" maxlength="7" name="cod_tipo" readonly="true"/>
                                            </div>
                                           <div class="col-xs-1" style="font-weight:bold" >Descripción</div>
                                            <div class="col-lg-5">
                                               	<input class="form-control" type='text' placeholder="Descripción del Tipo de Cliente" maxlength="100" id="tipo_cliente" name="tipo_cliente"/>
                                            </div>

                                        </div>
                                           
<!--
                                        <div class="form-group">
                                            <div class="col-xs-1">Simbolo</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" autofocus="" type='text' placeholder="Simbolo de la Unidad Medida" id="simbolo" maxlength="2" name="simbolo"/>
                                            </div>
                                           <div class="col-xs-1" >Tipo</div>
                                            <div class="col-lg-5">
                                               	<input class="form-control"  type='text' placeholder="Tipo de Unidad Medida" maxlength="20" id="tipo" name="tipo"/>
                                            </div>

                                        </div>
-->
   
                                        
                                        <br/>



                                        <div class="form-group">
                                            <div class="col-lg-12">
<!--                                                <input class="form-control"  type='hidden' placeholder="user" id="user_create_id" name="user_create_id" value="<?php echo $id_user ?>"/>-->
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
