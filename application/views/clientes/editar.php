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
        $("#tipocliente").select2('val', $("#id_tipocliente").val());
        //~ alert($("#id_tipo_cliente").val().length);
        if ($("#id_tipo_cliente").val().length == 0) {
			$("#tipo_cliente").select2('val', '0');
		}else{
			$("#tipo_cliente").select2('val', $("#id_tipo_cliente").val());
		}
        $("#id_cod_estado").select2('val', $("#id_estado").val());
        $("#id_cod_municipio").select2('val', $("#id_municipio").val());
        $("#id_cod_parroquia").select2('val', $("#id_parroquia").val());
        $('#volver').click(function () {
            url = '<?php echo base_url() ?>index.php/clientes/ControllersClientes/'
            window.location = url
        })
        var codigo = $('#codigo').val();
        $.get('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/ajax_contacto/' + codigo + '', function (data) {
            $('#nacionalidad').select2('val', data[0]['nacionalidad']);
            $('#cedula').val(data[0]['cedula']);

            $('#nombres').val(data[0]['nombres']);
            $('#apellidos').val(data[0]['apellidos']);
            $('#telefono').val(data[0]['telefono']);
            $('#correo').val(data[0]['correo']);

        }, 'json');

        ////////////////// CONSULTA DE CEDULA A BASE DE DATOS ////////////////////
        $("#cirif").change(function (event) {
            var cedula = $('#cirif').val();
            var tipo = $('#tipocliente').val();
            if (tipo == 'V') {
                //var hosting = $('#id_hosting').val(); // Captura del hosting (dominio)
                var hosting = "www.consultaelectoral.org.ve/cedula="
                if (hosting) {
                    $.get("http://" + hosting + cedula, function (data) {
                        var option = "";
                        $.each(data, function (i) {
                            $("#nombre").val(data[i].p_nombre + " " + data[i].s_nombre + " " + data[i].p_apellido + " " + data[i].s_apellido)
                        });
                        // Proceso para validar con la clase error 404 Not Found
                    }, 'json');
                }
            }
        });

        ////////////////// CONSULTA DE CEDULA A BASE DE DATOS ////////////////////
        //~ $("#cedula").change(function (event) {
            //~ var cedula = $('#cedula').val();
            //~ var tipo = $('#nacionalidad').val();
            //~ if (tipo == 'V') { (dominio)
                //~ var hosting = "www.consultaelectoral.org.ve/cedula="
                //~ if (hosting) {
                    //~ $.get("http://" + hosting + cedula, function (data) {
                        //~ var option = "";
                        //~ $.each(data, function (i) {
                            //~ $("#nombres").val(data[i].p_nombre + " " + data[i].s_nombre)
                            //~ $("#apellidos").val(data[i].p_apellido + " " + data[i].s_apellido)
                        //~ });
                    //~ }, 'json');
                //~ }
            //~ }
        //~ });

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
                    //~ $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#codigo").parent('div').addClass('has-error')
                    $('#codigo').val('')
                    $("#codigo").focus();
                });

            } else if ($('#tipocliente').val().trim() == '' || $('#tipocliente').val().trim() == 0) {
                bootbox.alert("Ingrese el tipo de identificación", function () {
                }).on('hidden.bs.modal', function (event) {
                    //~ $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#tipocliente").parent('div').addClass('has-error')
                    $('#tipocliente').val('')
                    $("#tipocliente").focus();
                });


            } else if ($('#cirif').val().trim() == '' || $('#cirif').val().trim() == 0) {
                bootbox.alert("Ingrese el campo de cédula", function () {
                }).on('hidden.bs.modal', function (event) {
                    //~ $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#cirif").parent('div').addClass('has-error')
                    $('#cirif').val('')
                    $("#cirif").focus();
                });


            } else if ($('#tipo_cliente').val().trim() == '' || $('#tipo_cliente').val().trim() == 0) {
                bootbox.alert("Ingrese el tipo de cliente", function () {
                }).on('hidden.bs.modal', function (event) {
                    //~ $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#tipo_cliente").parent('div').addClass('has-error')
                    $('#tipo_cliente').val('0')
                    $("#tipo_cliente").focus();
                });


            } else if ($('#nombre').val() == '') {

                bootbox.alert("Ingrese Nombre o Razón Social", function () {

                }).on('hidden.bs.modal', function (event) {
                    //~ $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#nombre").parent('div').addClass('has-error')
                    $('#nombre').val('')
                    $("#nombre").focus();
                });

            } else if ($('#tlf').val().trim() == '') {

                bootbox.alert("Ingrese campo del télefono local", function () {

                }).on('hidden.bs.modal', function (event) {
                    //~ $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#tlf").parent('div').addClass('has-error')
                    $('#tlf').val('')
                    $("#tlf").focus();
                });

            } else if ($('#tlf_movil').val().trim() == '') {

                bootbox.alert("Ingrese campo del télefono móvil", function () {

                }).on('hidden.bs.modal', function (event) {
                    //~ $('a[href=#tabs_A]').tab('show');
                    $("#tlf_movil").parent('div').addClass('has-error')
                    $('#tlf_movil').val('')
                    $("#tlf_movil").focus();
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
                    $('.nav-tabs a[href="#tabs_C"]').tab('show');
                    $("#id_cod_estado").parent('div').addClass('has-error')
                    $('#id_cod_estado').val('')
                    $("#id_cod_estado").focus();
                });


            } else if ($('#id_cod_municipio').val().trim() == '' || $('#id_cod_municipio').val().trim() == 0) {

                bootbox.alert("Seleccione el Municipio", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_C"]').tab('show');
                    $("#id_cod_municipio").parent('div').addClass('has-error')
                    $('#id_cod_municipio').val('')
                    $("#id_cod_municipio").focus();
                });


            } else if ($('#id_cod_parroquia').val().trim() == '' || $('#id_cod_parroquia').val().trim() == 0) {

                bootbox.alert("Seleccione la Parroquia", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_C"]').tab('show');
                    $("#id_cod_parroquia").parent('div').addClass('has-error')
                    $('#id_cod_parroquia').val('')
                    $("#id_cod_parroquia").focus();
                });

            } else if ($('#direccion').val().trim() == '') {

                bootbox.alert("Ingrese la dirección", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_C"]').tab('show');
                    $("#direccion").parent('div').addClass('has-error')
                    $('#direccion').val('')
                    $("#direccion").focus();
                });


            } /*else if ($('#nacionalidad').val().trim() == '' || $('#nacionalidad').val().trim() == 0) {
                bootbox.alert("Ingrese el tipo de identificación", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#nacionalidad").parent('div').addClass('has-error')
                    $('#nacionalidad').val('')
                    $("#nacionalidad").focus();
                });


            } else if ($('#cedula').val().trim() == '' || $('#cedula').val().trim() == 0) {
                bootbox.alert("Ingrese el campo de cédula", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#cedula").parent('div').addClass('has-error')
                    $('#cedula').val('')
                    $("#cedula").focus();
                });


            } else if ($('#nombres').val() == '') {

                bootbox.alert("Ingrese el Nombre", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#nombres").parent('div').addClass('has-error')
                    $('#nombres').val('')
                    $("#nombres").focus();
                });

            } else if ($('#apellidos').val() == '') {

                bootbox.alert("Ingrese el Apellido", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#apellidos").parent('div').addClass('has-error')
                    $('#apellidos').val('')
                    $("#apellidos").focus();
                });
            } else if ($('#telefono').val().trim() == '') {

                bootbox.alert("Ingrese campo del télefono", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#telefono").parent('div').addClass('has-error')
                    $('#telefono').val('')
                    $("#telefono").focus();
                });

            } else if ($('#correo').val().trim() == '') {

                bootbox.alert("Ingrese el campo Email", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#correo").parent('div').addClass('has-error')
                    $('#correo').val('')
                    $("#correo").focus();
                });
            }*/ else {

                $.post('<?php echo base_url(); ?>index.php/clientes/ControllersClientes/actualizar', $('#form_cliente').serialize(), function (response) {

                    bootbox.alert("Se Actualizó con exito", function () {
                    }).on('hidden.bs.modal', function (event) {
                        url = '<?php echo base_url(); ?>index.php/clientes/ControllersClientes'
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
                            <form class="form-horizontal" id="form_cliente">
                                <div class="tab-content">
                                    <fieldset>
                                        <h3 id="nav-tabs">Editar Cliente <?php echo $editar[0]->codigo;?></h3>
                                        <!-- Apertura de Tabs (Secciones) -->
                                        <ul class="nav nav-tabs">
                                            <li class="active" id="#tabs_2" data-toggle="popover" style="font-weight:bold" data-trigger="focus" title="Información Básica" data-content='En este panel se ingresan la Información Básica del Cliente' data-placement="top">
                                                <a data-toggle="tab" href="#tabs_A"><i class="fa fa-file-text-o"></i>&nbsp;Información Básica</a>
                                            </li>
                                            <li  data-toggle="popover" data-trigger="focus" title="Dirección" style="font-weight:bold" data-content="En este panel se ingresan la Dirección del Cliente" data-placement="top">
                                                <a data-toggle="tab" href="#tabs_C"><i class="fa fa-map-marker"></i>&nbsp;Dirección</a>
                                            </li>
                                            <!--<li  data-toggle="popover" data-trigger="focus" title="Datos del Contacto" style="font-weight:bold" data-content="En este panel se ingresan los datos del contacto del cliente" data-placement="top">
                                                <a data-toggle="tab" href="#tabs_B"><i class="fa fa-user"></i>&nbsp;Datos del Contacto</a>
                                            </li>-->
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
                                                        <select id="tipocliente" name="tipocliente" class="form-control">
                                                            <option selected="" value="0">--</option>
                                                            <option &nbsp;="" value="V">V</option>
                                                            <option &nbsp;="" value="M">M</option>
                                                            <option &nbsp;="" value="P">P</option>
                                                            <option &nbsp;="" value="R">R</option>
                                                            <option &nbsp;="" value="E">E</option>
                                                            <option &nbsp;="" value="J">J</option>
                                                            <option &nbsp;="" value="I">I</option>
                                                            <option &nbsp;="" value="G">G</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <input type="text" placeholder="Introduzca Cédula o RIF " maxlength="9" value="<?php echo $editar[0]->cirif ?>" id="cirif" name="cirif"  class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Tipo Cliente</label>
                                                    <div class="col-lg-3">
                                                        <select id="tipo_cliente" name="tipo_cliente" class="form-control">
                                                            <option selected="" value="0">Seleccione</option>
                                                            <?php foreach ($list_tipos_clientes as $tipo_cliente) { ?>
																<option value="<?php echo $tipo_cliente->cod_tipo?>"><?php echo $tipo_cliente->tipo_cliente?></option>
															<?php }?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Nombre o Razón Social</label>
                                                    <div class="col-lg-11">
                                                        <input type="text" placeholder="Introduzca Nombre o Razón Social" id="nombre" value="<?php echo $editar[0]->nombre ?>" maxlength="70" name="nombre" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Télefono Local</label>
                                                    <div class="col-lg-3"> 
                                                        <input type="text" placeholder="Introduzca su télefono" id="tlf" maxlength="11" value="<?php echo $editar[0]->tlf ?>"  name="tlf" class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Télefono Móvil</label>
                                                    <div class="col-lg-3"> 
                                                        <input type="text" placeholder="Introduzca su télefono móvil" id="tlf_movil" maxlength="11" value="<?php echo $editar[0]->tlf_movil ?>"  name="tlf_movil" class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Email</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" placeholder="Introduzca su Email" id="email" maxlength="75" name="email" value="<?php echo $editar[0]->email ?>" class="form-control">
                                                    </div>
                                                    <br/>
                                                    <br/>
                                                    <br/>

											<!--<div class="col-lg-2">
													<form>
														<label style="font-weight:bold"> Puntuación:</label>
														<p class="clasificacion">
															<input id="radio1" name="puntuacion" value="5" type="radio">
															<label for="radio1">★</label>
															<input id="radio2" name="puntuacion" value="4" type="radio">
															<label for="radio2">★</label>
															<input id="radio3" name="puntuacion" value="3" type="radio">
															<label for="radio3">★</label>
															<input id="radio4" name="puntuacion" value="2" type="radio">
															<label for="radio4">★</label>
															<input id="radio5" name="puntuacion" value="1" type="radio">
															<label for="radio5">★</label>
														</p>
													</form>
												</div>-->

                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-3">
                                                        <label style="font-weight:bold">Activo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                        <input type="checkbox" id="estatus" name="estatus" style='cursor :pointer;'
                                                        <?php if ($editar[0]->estatus == 1) {
                                                            ?>
                                                                   checked='checked'
                                                               <?php } ?>>
                                                        <label>&nbsp;&nbsp;&nbsp; **¿Cliente Activo?</label>
                                                    </div>
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

                                            <!--<div id="tabs_B" class="tab-pane fade">
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Tipo</label>
                                                    <div class="col-lg-5">
                                                        <select id="nacionalidad" name="nacionalidad" class="form-control">
                                                            <option selected="" value="--">--</option>
                                                            <option &nbsp;="" value="V">V</option>
                                                            <option &nbsp;="" value="M">M</option>
                                                            <option &nbsp;="" value="P">P</option>
                                                            <option &nbsp;="" value="R">R</option>
                                                            <option &nbsp;="" value="E">E</option>
                                                            <option &nbsp;="" value="J">J</option>
                                                            <option &nbsp;="" value="I">I</option>
                                                            <option &nbsp;="" value="G">G</option>
                                                        </select>

                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Cédula / RIF</label>
                                                    <div class="col-lg-5">
                                                        <input type="text" placeholder="Introduzca Cédula o RIF " maxlength="9" id="cedula" name="cedula"  class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">

                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Nombre</label>
                                                    <div class="col-lg-5">
                                                        <input type="text" placeholder="Introduzca Nombre " maxlength="8" id="nombres" name="nombres"  class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Apellido</label>
                                                    <div class="col-lg-5">
                                                        <input type="text" placeholder="Introduzca Apellido " maxlength="8" id="apellidos" name="apellidos"  class="form-control">
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Télefono</label>
                                                    <div class="col-lg-5"> 
                                                        <input type="text" placeholder="Introduzca su télefono" id="telefono" maxlength="11"  name="telefono" class="form-control">

                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Email</label>
                                                    <div class="col-lg-5">
                                                        <input type="text" placeholder="Introduzca su Email" id="correo" maxlength="75" name="correo" class="form-control">

                                                    </div>

                                                </div>
                                                <br/>
                                                <br/>
                                            </div>-->
                                            
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
                                            <input id="id_tipocliente" type="hidden" value="<?php echo $editar[0]->tipocliente ?>"/>
                                            <input id="id_tipo_cliente" type="hidden" value="<?php echo $editar[0]->tipo_cliente ?>"/>
                                            <input id="id_estado" type="hidden" value="<?php echo $editar[0]->estado ?>"/> 
                                            <input id="id_municipio" type="hidden" value="<?php echo $editar[0]->municipio ?>"/> 
                                            <input id="id_parroquia" type="hidden" value="<?php echo $editar[0]->parroquia ?>"/> 
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



