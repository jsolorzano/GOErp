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
                $("#valor").numeric(); //Valida solo permite valores numericos
                $('#nombre').alphanumeric({allow: "@-_."});
                $('#siglas').alpha({allow: " "}); //Solo permite texto
                $('#direccion').alphanumeric({allow: " ,.-#"}); //Solo permite texto numericos"

                $('#volver').click(function () {
                    url = '<?php echo base_url()?>index.php/ganancia/ControllersGanancia/'
                    window.location = url
                })


                $("#registrar").click(function (e) {
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    
                    if (($('#valor').val().trim() == '')) {
                        bootbox.alert('Disculpe, Debe Colocar el valor del porcentaje de ganancia', function() {
                            $('#valor').parent('div').addClass('has-error');
                        });
                    }else {
                        
                         $.post('<?php echo base_url(); ?>index.php/ganancia/ControllersGanancia/guardar', $('#form_ganancia').serialize(), function (response) {
                           if (response[0] == '1') {
                                bootbox.alert("Disculpe, El Porcentaje de ganancia que desea registrar ya se encuentra registrado", function () {
                                }).on('hidden.bs.modal', function (event) {
                                    $("#valor").parent('div').addClass('has-error')
                                    $('#valor').val('')
                                    $("#valor").focus();

                                });

                            }
                            else { 
                                bootbox.alert("Se registro con exito", function () {
                            }).on('hidden.bs.modal', function (event) {
                                url = '<?php echo base_url(); ?>index.php/ganancia/ControllersGanancia'
                                window.location = url
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

                                <form class="form-horizontal" id="form_ganancia">
                                    <fieldset>
                                        <legend>Registrar Porcentaje de Ganancia</legend>
                                           <br/>
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" style="font-weight:bold">Código</label>
                                                <div class="col-lg-5">
                                 
                                                   
                                                    <input type="text" value="<?php printf('G'."%05d", $detalles_lista+1); ?>" readonly="" placeholder="Introduzca el Código asignar" maxlength="8" id="codigo" name="codigo"  class="form-control">
                                              
                                                                                                      
                                                </div>
                                           <div class="col-xs-1" style="font-weight:bold" >Valor % Ganancia</div>
                                            <div class="col-lg-5">
                                               	<input class="form-control"  type='text' placeholder="Introduzca el Valor de la Ganancia" maxlength="2" id="valor" name="valor"/>
                                            </div>

                                        </div>
   
                                        
                                        <br/>



                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <input class="form-control"  type='hidden' placeholder="user" id="user_create_id" name="user_create_id" value="<?php echo $id_user ?>"/>
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


