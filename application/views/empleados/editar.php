<!--// si el usuario no esta logueado lo envia al logueo //-->   
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
  
<?php if ($tipouser == 'Ventas' || $tipouser == 'Administrador'){
	
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
        $("#cedula,#telefono,#tlf,#cirif").numeric(); //Valida solo permite valores numericos
        $('#email').alphanumeric({allow: "@-_."});
        $('#siglas').alpha({allow: " "}); //Solo permite texto
        $('#direccion').alphanumeric({allow: " ,.-#"}); //Solo permite texto numericos"
        $("#tipodoc").select2('val', $("#id_tipodoc").val());
        //~ alert($("#id_tipo_cliente").val().length);
        if ($("#id_cargo").val().length == 0) {
			$("#cargo").select2('val', '0');
		}else{
			$("#cargo").select2('val', $("#id_cargo").val());
		}
        $("#id_cod_estado").select2('val', $("#id_estado").val());
        $("#id_cod_municipio").select2('val', $("#id_municipio").val());
        $("#id_cod_parroquia").select2('val', $("#id_parroquia").val());
        $("#salario").select2('val', $("#id_salario").val());
        
        $('#volver').click(function () {
            url = '<?php echo base_url() ?>index.php/empleados/ControllersEmpleados/'
            window.location = url
        })

        // Función para cargar los datos del salario seleccionado y calcular el monto que le corresponde al empleado teniendo en cuenta la escala salarial indicada
		$("#salario,#escala").change(function (event) {
			event.preventDefault();
			if($("#salario").val() != '' && $("#escala").val() != ''){
				$.post('<?php echo base_url(); ?>index.php/empleados/ControllersEmpleados/consultar/', $('#form_empleado').serialize(), function (response) {
					var respuesta = response.split('<html>');
					//~ alert(respuesta[0]);
					$('#monto').val(parseFloat(respuesta[0])*parseFloat($("#escala").val()));
				});
			}
		});

		////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Al cambiar de opción en el combo de municipio dependientes de los estados
		////////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#id_cod_estado').change(function () {

            var id_est = $('#id_cod_estado').val();
            //~ alert(id_est)
            $('#id_cod_municipio').find('option:gt(0)').remove().end().select2('val', '0');
            if (id_est > 0) {
                $.get('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/ajax_mun/' + id_est + '', function (data) {
                    var option = "";
                    //~ alert(data);
                    $.each(data, function (i) {
                        option += "<option value=" + data[i]['cod_municipio'] + ">" + data[i]['municipio'] + "</option>";
                    });
                    $('#id_cod_municipio').append(option);
                }, 'json');
            }
        });

		////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Al cambiar de option en el combo parroquia dependientes de los municipios
		////////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#id_cod_municipio').change(function () {
            var id_est = $('#id_cod_estado').val();
            var id_mun = $("#id_cod_municipio").val();
            var id_parr = $('#id_cod_parroquia').val();

            $('#id_cod_parroquia').find('option:gt(0)').remove().end().select2('val', '0');
            if (id_est > 0 && id_mun > 0) {

                $.get('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/ajax_parr/' + id_est + '/' + id_mun + '', function (data) {
                    var option = "";
                    $.each(data, function (i) {
                        option += "<option value=" + data[i]['cod_parroquia'] + ">" + data[i]['parroquia'] + "</option>";
                    });
                    $('#id_cod_parroquia').append(option);
                }, 'json');
            }
        });

        $("#registrar").click(function (e) {
            e.preventDefault();  // Para evitar que se envíe por defecto
            
            //~ alert($("#id_tipo_cliente").val().length);

            if ($('#codigo').val().trim() == '') {

                bootbox.alert("Ingrese el campo de codigo", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#codigo").parent('div').addClass('has-error')
                    $('#codigo').val('')
                    $("#codigo").focus();
                });

            } else if ($('#tipodoc').val().trim() == '' || $('#tipodoc').val().trim() == 0) {
                bootbox.alert("Ingrese el tipo de identificación", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#tipocliente").parent('div').addClass('has-error')
                    $('#tipocliente').val('')
                    $("#tipocliente").focus();
                });


            } else if ($('#cirif').val().trim() == '' || $('#cirif').val().trim() == 0) {
                bootbox.alert("Ingrese el campo de cédula", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#cirif").parent('div').addClass('has-error')
                    $('#cirif').val('')
                    $("#cirif").focus();
                });


            } else if ($('#cargo').val().trim() == '' || $('#cargo').val().trim() == 0) {
                bootbox.alert("Ingrese el cargo", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#cargo").parent('div').addClass('has-error')
                    $('#cargo').val('0')
                    $("#cargo").focus();
                });


            } else if ($('#nombre').val() == '') {

                bootbox.alert("Ingrese Nombres y Apellidos", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#nombre").parent('div').addClass('has-error')
                    $('#nombre').val('')
                    $("#nombre").focus();
                });

            } else if ($('#tlf').val().trim() == '') {

                bootbox.alert("Ingrese campo del télefono", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#tlf").parent('div').addClass('has-error')
                    $('#tlf').val('')
                    $("#tlf").focus();
                });

            } else if ($('#email').val().trim() == '') {

                bootbox.alert("Ingrese el campo Email", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#email").parent('div').addClass('has-error')
                    $('#email').val('')
                    $("#email").focus();
                });

            } else if ($('#id_cod_estado').val().trim() == '' || $('#id_cod_estado').val().trim() == 0) {

                bootbox.alert("Seleccione el estado", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_C]').tab('show');
                    $("#id_cod_estado").parent('div').addClass('has-error')
                    $('#id_cod_estado').val('')
                    $("#id_cod_estado").focus();
                });


            } else if ($('#id_cod_municipio').val().trim() == '' || $('#id_cod_municipio').val().trim() == 0) {

                bootbox.alert("Seleccione el Municipio", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_C]').tab('show');
                    $("#id_cod_municipio").parent('div').addClass('has-error')
                    $('#id_cod_municipio').val('')
                    $("#id_cod_municipio").focus();
                });


            } else if ($('#id_cod_parroquia').val().trim() == '' || $('#id_cod_parroquia').val().trim() == 0) {

                bootbox.alert("Seleccione la Parroquia", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_C]').tab('show');
                    $("#id_cod_parroquia").parent('div').addClass('has-error')
                    $('#id_cod_parroquia').val('')
                    $("#id_cod_parroquia").focus();
                });

            } else if ($('#direccion').val().trim() == '') {

                bootbox.alert("Ingrese la dirección", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_C]').tab('show');
                    $("#direccion").parent('div').addClass('has-error')
                    $('#direccion').val('')
                    $("#direccion").focus();
                });


            } else if ($('#salario').val().trim() == '') {

                bootbox.alert("Seleccione el salario", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#salario").parent('div').addClass('has-error')
                    $("#salario").focus();
                });


            } else if ($('#escala').val().trim() == '' || $('#escala').val().trim() == 0) {

                bootbox.alert("indique la escala salarial", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#escala").parent('div').addClass('has-error')
                    $("#escala").val('');
                    $("#escala").focus();
                });


            } else {

                $.post('<?php echo base_url(); ?>index.php/empleados/ControllersEmpleados/actualizar', $('#form_empleado').serialize(), function (response) {

                    bootbox.alert("Se Actualizó con exito", function () {
                    }).on('hidden.bs.modal', function (event) {
                        url = '<?php echo base_url(); ?>index.php/empleados/ControllersEmpleados'
                        window.location = url
                    });

                });
            }

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

                    </br>

                    <div class="col-lg-12">
                        <div class="well bs-component">
                            <form class="form-horizontal" id="form_empleado">
                                <div class="tab-content">
                                    <fieldset>
                                        <h3 id="nav-tabs">Editar Empleado <?php echo $editar[0]->codigo;?></h3>
                                        <!-- Apertura de Tabs (Secciones) -->
                                        <ul class="nav nav-tabs">
                                            <li class="active" id="#tabs_2" data-toggle="popover" style="font-weight:bold" data-trigger="focus" title="Información Básica" data-content='En este panel se ingresan la Información Básica del Cliente' data-placement="top">
                                                <a data-toggle="tab" href="#tabs_A"><i class="fa fa-file-text-o"></i>&nbsp;Información Básica</a>
                                            </li>
                                            <li  data-toggle="popover" data-trigger="focus" title="Dirección" style="font-weight:bold" data-content="En este panel se ingresan la Dirección del Cliente" data-placement="top">
                                                <a data-toggle="tab" href="#tabs_C"><i class="fa fa-map-marker"></i>&nbsp;Dirección</a>
                                            </li>
                                            <li data-toggle="popover" data-trigger="focus" title="Salario" style="font-weight:bold" data-content="En este panel se ingresan los datos del contacto del cliente" data-placement="top">
                                                <a data-toggle="tab" href="#tabs_B"><i class="fa fa-user"></i>&nbsp;Salario</a>
                                            </li>
                                        </ul>
                                        <!-- Cierre de Tabs (Secciones) -->
                                        <br/>
                                        <br/>
                                        <div class="tab-content">
                                            <div id="tabs_A" class="tab-pane fade  in active">
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Código</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" placeholder="Introduzca el Código asignar" maxlength="8" id="codigo" readonly="" name="codigo" value="<?php echo $editar[0]->codigo ?>" class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Cédula / RIF</label>
                                                    <div class="col-lg-1">
                                                        <select id="tipodoc" name="tipodoc" class="form-control">
                                                            <option selected="" value="0">--</option>
                                                            <option &nbsp;="" value="V">V</option>
                                                            <option &nbsp;="" value="E">E</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <input type="text" placeholder="Introduzca Cédula o RIF " maxlength="9" value="<?php echo $editar[0]->cirif ?>" id="cirif" name="cirif"  class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Cargo</label>
                                                    <div class="col-lg-3">
                                                        <select id="cargo" name="cargo" class="form-control">
                                                            <option selected="" value="0">Seleccione</option>
                                                            <?php foreach ($cargos as $cargo) { ?>
																<option value="<?php echo $cargo->cod_cargo?>"><?php echo $cargo->cargo?></option>
															<?php }?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Nombres y Apellidos</label>
                                                    <div class="col-lg-11">
                                                        <input type="text" placeholder="Introduzca Nombres y Apellidos" id="nombre" value="<?php echo $editar[0]->nombre ?>" maxlength="70" name="nombre" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Télefono</label>
                                                    <div class="col-lg-3"> 
                                                        <input type="text" placeholder="Introduzca su télefono" id="tlf" maxlength="11" value="<?php echo $editar[0]->tlf ?>"  name="tlf" class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Email</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" placeholder="Introduzca su Email" id="email" maxlength="75" name="email" value="<?php echo $editar[0]->email ?>" class="form-control">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label style="font-weight:bold">Activo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                        <input type="checkbox" id="estatus" name="estatus" style='cursor :pointer;'
                                                        <?php if ($editar[0]->estatus == 1) {
                                                            ?>
                                                                   checked='checked'
                                                               <?php } ?>>
                                                        <label>&nbsp;&nbsp;&nbsp; **¿Empleado Activo?</label>
                                                    </div>
                                                    <br/>
                                                    <br/>
                                                    <br/>

                                                </div>
                                            </div>
                                            
                                            <div id="tabs_C" class="tab-pane fade">
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Estado</label>
                                                    <div class="col-lg-3">
                                                        <select id="id_cod_estado" name="estado" class="form-control select2 input-sm" >
                                                            <option value="0">Seleccione</option>
                                                            <?php foreach ($list_estado as $estado) { ?>
                                                                <option value="<?php echo $estado->cod_estado ?>"><?php echo $estado->estado ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Municipio</label>
                                                    <div class="col-lg-3">
                                                        <select id="id_cod_municipio" class="form-control select2 input-sm" name="municipio" >
                                                            <option value="0">Seleccione</option>
                                                            <?php foreach ($list_municipio as $municipio) { ?>
                                                                <option value="<?php echo $municipio->cod_municipio ?>"><?php echo $municipio->municipio ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <label class="col-lg-1 control-label"style="font-weight:bold">Parroquia</label>
                                                    <div class="col-lg-3">
                                                        <select id="id_cod_parroquia" class="form-control select2 input-sm" name="parroquia" >
                                                            <option value="0">Seleccione</option>
                                                            <?php foreach ($list_parroquia as $parroquia) { ?>
                                                                <option value="<?php echo $parroquia->cod_parroquia ?>"><?php echo $parroquia->parroquia ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Dirección</label>
                                                    <div class="col-lg-11">
                                                        <textarea id="direccion" maxlength="300" name="direccion" placeholder="Introduzca la Dirección" class="form-control"><?php echo $editar[0]->direccion ?></textarea>
                                                    </div>
                                                </div>
                                                <br/>
                                                <br/>
                                                <br/>
                                            </div>
                                            <div id="tabs_B" class="tab-pane fade">
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Salario</label>
                                                    <div class="col-lg-3">
                                                        <select id="salario" name="salario" class="form-control select2 input-sm" >
                                                            <option value="">Seleccione</option>
                                                            <?php foreach ($salarios as $salario) { ?>
                                                                <option value="<?php echo $salario->codigo; ?>"><?php echo $salario->concepto; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Escala</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" placeholder="Ejem: 1.5" id="escala"  name="escala" class="form-control" value="<?php echo $editar[0]->escala; ?>">
                                                    </div>
                                                    <label class="col-lg-1 control-label"style="font-weight:bold">Monto</label>
                                                    <div class="col-lg-3">
                                                       <input type="text" id="monto" name="monto" class="form-control" readonly="true" value="<?php echo $editar[0]->monto; ?>">
                                                    </div>
                                                </div>
                                                <br/>
                                                <br/>
                                                <br/>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="col-lg-12">
                                                    <input class="form-control" type='hidden' id="id" name="id" value="<?php echo $id; ?>"/>
                                                    <button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning" >
                                                        &nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
                                                    </button>
                                                    <button type="submit" id="registrar" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                                    &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Guardar
                                                    </button>
                                                </div>
                                            </div>
                                            <input id="id_tipodoc" type="hidden" value="<?php echo $editar[0]->tipodoc ?>"/>
                                            <input id="id_cargo" type="hidden" value="<?php echo $editar[0]->cargo ?>"/>
                                            <input id="id_estado" type="hidden" value="<?php echo $editar[0]->estado ?>"/> 
                                            <input id="id_municipio" type="hidden" value="<?php echo $editar[0]->municipio ?>"/> 
                                            <input id="id_parroquia" type="hidden" value="<?php echo $editar[0]->parroquia ?>"/> 
                                            <input id="id_salario" type="hidden" value="<?php echo $editar[0]->salario ?>"/> 
                                    </fieldset>
                                </div>
                            </form>

                            <div class="btn btn-primary btn-xs" id="source-button" style="display: none;">&lt; &gt;</div></div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</body>
</html>



