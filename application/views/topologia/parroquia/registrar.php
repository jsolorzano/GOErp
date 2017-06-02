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
        $('#municipio').alpha({allow:" "}); //Valida solo valores tipo texto
        $('#cod_municipio').numeric(); //Valida solo valores tipo texto
        
        $('#volver').click(function () {
            url = '<?php echo base_url() ?>index.php/topologia/ControllersParroquia/'
            window.location = url
        })



        $("#registrar").click(function (e) {
            e.preventDefault();  // Para evitar que se envíe por defecto

            if (($('#cod_parroquia').val().trim() == '')) {
                bootbox.alert('Disculpe, Debe Colocar el Código de la parroquia', function () {
                    $('#cod_parroquia').parent('div').addClass('has-error');
                });
            } else if (($('#parroquia').val().trim() == '')) {
                bootbox.alert('Disculpe, Debe Colocar el Nombre de la parroquia', function () {
                    $('#parroquia').parent('div').addClass('has-error');
                });
            } else if ($('#parroquia').val().length < 4) {
                bootbox.alert('Disculpe, Nombre de la parroquia invalido (Muy Corto)', function () {
                    $('#parroquia').parent('div').addClass('has-error');
                });
                
            } else if ($('#estado_id').val().trim() == '') {
                bootbox.alert('Disculpe, debe seleccionar el estado de la parroquia', function () {
                    $('#estado_id').parent('div').addClass('has-error');
                });
                
            } else if ($('#municipio').val().trim() == '') {
                bootbox.alert('Disculpe, debe seleccionar el municipio de la parroquia', function () {
                    $('#municipio').parent('div').addClass('has-error');
                });
            
            } else {

                $.post('<?php echo base_url(); ?>index.php/topologia/ControllersParroquia/guardar', $('#form_estado').serialize(), function (response) {

                    bootbox.alert("Se registro con exito", function () {
                    }).on('hidden.bs.modal', function (event) {
                        url = '<?php echo base_url(); ?>index.php/topologia/ControllersParroquia'
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

    <br/>

    <div class="row-fluid text-center" >
        <div class="mainbody-section">


            <div class="container" style="width:90%;">
                <div class="row">

                    </br>

                    <div class="col-lg-12">
                        <div class="well bs-component">

                            <form class="form-horizontal" id="form_estado">
                                <fieldset>
                                    <legend>Registrar Parroquias</legend>
                                    <br/>
                                    <div class="form-group">
                                        <div class="col-xs-1" style="font-weight:bold">Código</div>
                                        <div class="col-lg-5">
                                            <input class="form-control" autofocus="" type='text' id="cod_parroquia" name="cod_parroquia" value="<?php echo (int)$ultimo_id + (int)1; ?>" readonly="true"/>
                                        </div>

                                        <div class="col-xs-1" style="font-weight:bold">Parroquia</div>
                                        <div class="col-lg-5">
                                            <input class="form-control"  type='text' placeholder="Nombre del Parroquia" maxlength="20" id="parroquia" name="parroquia"/>
                                        </div>

                                    </div>


                                    <br/>
                                    <div class="form-group">
                                        <div class="col-xs-1" style="font-weight:bold">Estado</div>
                                        <div class="col-lg-5">
                                            <select id="estado_id" name="estado_id" class="form-control">
                                                <option value="">Seleccione</option>
                                                
                                                <?php foreach ($list_estado as $estado) { ?>
                                                    <option value="<?php echo $estado->cod_estado?>"><?php echo $estado->estado?></option>
                                                <?php }?>
                                                
                                            </select>
                                        </div>
                                        <div class="col-xs-1" style="font-weight:bold">Municipio</div>
                                        <div class="col-lg-5">
                                            <select id="municipio" name="municipio" class="form-control">
                                                <option value="">Seleccione</option>
                                                
                                                <?php foreach ($list_mun as $municipio) { ?>
                                                    <option value="<?php echo $municipio->cod_municipio?>"><?php echo $municipio->municipio?></option>
                                                <?php }?>
                                                
                                            </select>
                                        </div>

                                    </div>



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
