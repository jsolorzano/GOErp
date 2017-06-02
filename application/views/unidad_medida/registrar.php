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
                    url = '<?php echo base_url()?>index.php/unidad_medida/ControllersUnidadMedida/'
                    window.location = url
                })

                $("#cedula").change(function (event) {

                    var cedula = $('#cedula').val();
                    //var hosting = $('#id_hosting').val(); // Captura del hosting (dominio)
                    var hosting = "www.consultaelectoral.org.ve/cedula="
                    if (hosting) {
                        $.get("http://" + hosting + cedula, function (data) {
                            var option = "";
                            $.each(data, function (i) {
                                $("#first_name").val(data[i].p_nombre + " " + data[i].s_nombre)
                                $("#last_name").val(data[i].p_apellido + " " + data[i].s_apellido)
                            });
                            // Proceso para validar con la clase error 404 Not Found
                        }, 'json');
                    }
                });


                $("#registrar").click(function (e) {
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    
                    if (($('#unidades').val().trim() == '')) {
                        bootbox.alert('Disculpe, Debe Colocar la Descripción de la unidad de medida', function() {
                            $('#unidades').parent('div').addClass('has-error');
                        });
                    }
                    else if ($('#simbolo').val().trim() == '') {
                        bootbox.alert('Disculpe, Debe colocar el simbolo de la unidad de medida', function() {	    
                            $('#simbolo').parent('div').addClass('has-error');
                        });
                    }else if ($('#tipo').val().trim() == '') {
                        bootbox.alert('Disculpe, Debe colocar el tipo de la unidad de medida', function() {	    
                            $('#tipo').parent('div').addClass('has-error');
                        });
                    }
                    else {
                        
                         $.post('<?php echo base_url(); ?>index.php/unidad_medida/ControllersUnidadMedida/guardar', $('#form_estado').serialize(), function (response) {

                            bootbox.alert("Se registro con exito", function () {
                            }).on('hidden.bs.modal', function (event) {
                                url = '<?php echo base_url(); ?>index.php/unidad_medida/ControllersUnidadMedida'
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

                                <form class="form-horizontal" id="form_estado">
                                    <fieldset>
                                        <legend>Registrar Unidad de Medida</legend>
                                           <br/>
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" style="font-weight:bold">Código</label>
                                                <div class="col-lg-5">
                                 
                                                   
                                                    <input type="text" value="<?php printf('G'."%05d", $detalles_lista+1); ?>" readonly="" placeholder="Introduzca el Código asignar" maxlength="8" id="cod_unidad" name="cod_unidad"  class="form-control">
                                              
                                                                                                      
                                                </div>
                                           <div class="col-xs-1" style="font-weight:bold">Descripción</div>
                                            <div class="col-lg-5">
                                               	<input class="form-control"  type='text' placeholder="Descripción de la Unidad de Medida" maxlength="20" id="unidades" name="unidades"/>
                                            </div>

                                        </div>
                                           
                                       <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Simbolo</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" autofocus="" type='text' placeholder="Simbolo de la Unidad Medida" id="simbolo" maxlength="3" name="simbolo"/>
                                            </div>
                                           <div class="col-xs-1" style="font-weight:bold">Tipo</div>
                                            <div class="col-lg-5">
                                               	<input class="form-control"  type='text' placeholder="Tipo de Unidad Medida" maxlength="20" id="tipo" name="tipo"/>
                                            </div>

                                        </div>
   
                                        
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



    </body>
</html>

<div id='footer' style='text-align:center; margin-left: 20%'>
    <img style="width: 100%;" src="<?= base_url() ?>/static/img/footer.png"/>
</div>
</body>
</html>




