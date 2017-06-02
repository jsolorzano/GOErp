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
            url = '<?php echo base_url() ?>index.php/topologia/ControllersEstado/'
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

            if (($('#cod_estado').val().trim() == '')) {
                bootbox.alert('Disculpe, Debe Colocar el Código del Estado', function () {
                    $('#cod_estado').parent('div').addClass('has-error');
                });
            } else if (($('#estado').val().trim() == '')) {
                bootbox.alert('Disculpe, Debe Colocar el Nombre del Estado', function () {
                    $('#estado').parent('div').addClass('has-error');
                });
            } else if ($('#estado').val().length < 4) {
                bootbox.alert('Disculpe, Nombre de estado invalido (Muy Corto)', function () {
                    $('#estado').parent('div').addClass('has-error');
                });
            } else {

                $.post('<?php echo base_url(); ?>index.php/topologia/ControllersEstado/actualizar', $('#form_estado').serialize(), function (response) {

                    bootbox.alert("Se actualizo con exito", function () {
                    }).on('hidden.bs.modal', function (event) {
                        url = '<?php echo base_url(); ?>index.php/topologia/ControllersEstado'
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
                                    <legend>Editar Estado</legend>
                                    <br/>
                                    <div class="form-group">
                                        <div class="col-xs-1" style="font-weight:bold" >Código</div>
                                        <div class="col-lg-5">
                                            <input class="form-control" autofocus="" readonly="true" type='text' id="cod_estado" value="<?php echo $editar[0]->cod_estado; ?>" name="cod_estado"/>
                                        </div>
                                        <div class="col-xs-1" style="font-weight:bold">Estado</div>
                                        <div class="col-lg-5">
                                            <input class="form-control"  type='text' placeholder="Nombre del Estado" maxlength="20" value="<?php echo $editar[0]->estado ?>" id="estado" name="estado"/>
                                        </div>

                                    </div>


                                    <br/>



                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <input class="form-control"  type='hidden' placeholder="user" id="id" name="id" value="<?php echo $id ?>"/>
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
