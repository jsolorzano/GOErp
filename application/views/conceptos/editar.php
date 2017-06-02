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
                $('#concepto').alpha(); //Solo permite texto
                $('#monto').numeric({allow: "."}); //Solo permite números
                
                $("#tipo").select2('val',$("#id_tipo").val());

                $('#volver').click(function () {
                    url = '<?php echo base_url()?>index.php/conceptos/ControllersConceptos'
                    window.location = url
                })

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
                        
                         $.post('<?php echo base_url(); ?>index.php/conceptos/ControllersConceptos/actualizar', $('#form_concepto').serialize(), function (response) {

                            bootbox.alert("Se actualizo con exito", function () {
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
                                        <legend>Editar Salario</legend>
                                           <br/>
                                        <div class="form-group">
                                            <div class="col-xs-1" style="font-weight:bold">Código</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" autofocus="" value="<?php echo $editar[0]->codigo; ?>" type='text' placeholder="CP00001" id="codigo" name="codigo" readonly="true"/>
                                            </div>
                                           <div class="col-xs-1" style="font-weight:bold" >Salario</div>
                                            <div class="col-lg-5">
                                                <input class="form-control" id="concepto" name="concepto" value="<?php echo $editar[0]->concepto; ?>" placeholder="Nombre Salario"/>
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
                                                <input class="form-control" type='text' id="monto" name="monto" value="<?php echo $editar[0]->monto; ?>" placeholder="00000.00"/>
                                            </div>
                                        </div>   
                                        
                                        <br/>

                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <input class="form-control"  type='hidden' placeholder="user" id="id" name="id" value="<?php echo $id ?>"/>
                                                <input class="form-control"  type='hidden' placeholder="user" id="id_tipo" value="<?php echo $editar[0]->tipo ?>"/>
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
