   
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

        $('#volver').click(function () {
            url = '<?php echo base_url() ?>index.php/clientes/ControllersClientes/'
            window.location = url
        })

        ////////////////// CONSULTA DE CEDULA A BASE DE DATOS ////////////////////
        $("#cirif").change(function (event) {
            var cedula = $('#cirif').val();
            var tipo = $('#tipocliente').val();
            if (tipo == 'V') {
                //var hosting = $('#id_hosting').val(); // Captura del hosting (dominio)
                //~ var hosting = "www.consultaelectoral.org.ve/cedula="
                var hosting = "consultaelectoral.bva.org.ve/cedula="
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

		////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Al cambiar de opción en el combo de estados cargamos los municipios dependientes
		////////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#id_cod_estado').change(function () {

            var id_est = $('#id_cod_estado').val();
            $('#id_cod_municipio').find('option:gt(0)').remove().end().select2('val', '0');
            if (id_est > 0) {
                $.get('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/ajax_mun/' + id_est + '', function (data) {
                    var option = "";
                    $.each(data, function (i) {

                        option += "<option value=" + data[i]['cod_municipio'] + ">" + data[i]['municipio'] + "</option>";
                    });
                    $('#id_cod_municipio').append(option);
                }, 'json');
            }
        });

		////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Al cambiar de option en el combo municipios cargamos las parroquias dependientes 
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

            if ($('#codigo').val().trim() == '') {

                bootbox.alert("Ingrese el campo de codigo", function () {
                }).on('hidden.bs.modal', function (event) {
                    //~ $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#codigo").parent('div').addClass('has-error')
                    $('#codigo').val('')
                    $("#codigo").focus();
                });

            } else if ($('#tipocliente').val().trim() == '' || $('#tipocliente').val().trim() == 0) {
                bootbox.alert("Seleccione el tipo de identificación", function () {
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
                bootbox.alert("Seleccione el tipo de cliente", function () {
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
                    //~ $('.nav-tabs a[href=#tabs_A]').tab('show');
                    $("#tlf_movil").parent('div').addClass('has-error')
                    $('#tlf_movil').val('')
                    $("#tlf_movil").focus();
                });

            } else if ($('#email').val().trim() == '') {

                bootbox.alert("Ingrese el campo Email", function () {

                }).on('hidden.bs.modal', function (event) {
                    //~ $('.nav-tabs a[href=#tabs_A]').tab('show');
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

                $.post('<?php echo base_url(); ?>index.php/clientes/ControllersClientes/guardar', $('#form_cliente').serialize(), function (response) {
                    if (response[0] == '1') {
                        bootbox.alert("Disculpe, El Cliente que desea registrar ya existe con el número de identificación asignado", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $('.nav-tabs a[href=#tabs_A]').tab('show');
                            $("#cirif").parent('div').addClass('has-error')
                            $('#cirif').val('')
                            $('#nombre').val('')
                            $("#cirif").focus();

                        });

                    } else {
                        bootbox.alert("Se registro con exito", function () {
                        }).on('hidden.bs.modal', function (event) {
                            url = '<?php echo base_url(); ?>index.php/clientes/ControllersClientes'
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
                                        <h3 id="nav-tabs">Registro de Nuevo Cliente</h3>
                                        <!-- Apertura de Tabs (Secciones) -->
                                        <ul class="nav nav-tabs">
                                            <li class="active" id="#tabs_2" data-toggle="popover" style="font-weight:bold" data-trigger="focus" title="Información Básica" data-content='En este panel se ingresan la Información Básica del Cliente' data-placement="top">
                                                <a data-toggle="tab" href="#tabs_A"><i class="fa fa-file-text-o"></i>&nbsp;Información Básica</a>
                                            </li>
                                            <li data-toggle="popover" data-trigger="focus" title="Dirección" style="font-weight:bold" data-content="En este panel se ingresan la Dirección del Cliente" data-placement="top">
                                                <a data-toggle="tab" href="#tabs_C"><i class="fa fa-map-marker"></i>&nbsp;Dirección</a>
                                            </li>
                                            <!--<li data-toggle="popover" data-trigger="focus" title="Datos del Contacto" style="font-weight:bold" data-content="En este panel se ingresan los datos del contacto del cliente" data-placement="top">
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
                                                        <div class="input-group">

                                                            <input type="text" value="<?php printf('C' . "%05d", $detalles_lista + 1); ?>" readonly="" placeholder="Introduzca el Código asignar" maxlength="8" id="codigo" name="codigo"  class="form-control">
                                                        </div>
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
                                                        <input type="text" placeholder="Introduzca Cédula o RIF " maxlength="9" id="cirif" name="cirif"  class="form-control">
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
                                                        <input type="text" placeholder="Introduzca Nombre o Razón Social" id="nombre" maxlength="70" name="nombre" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Télefono Local</label>
                                                    <div class="col-lg-3"> 
                                                        <input type="text" placeholder="Introduzca su télefono local" id="tlf" maxlength="11"  name="tlf" class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Télefono Móvil</label>
                                                    <div class="col-lg-3"> 
                                                        <input type="text" placeholder="Introduzca su télefono móvil" id="tlf_movil" maxlength="11"  name="tlf_movil" class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Email</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" placeholder="Introduzca su Email" id="email" maxlength="75" name="email" class="form-control">
                                                    </div>
                                                    <br/>
                                                    <br/>
                                                    <br/>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-3">
                                                        <label style="font-weight:bold">Activo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                        <input type="checkbox" id="estatus" name="estatus" />
                                                        <label>&nbsp;&nbsp;&nbsp; **¿Cliente Activo?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="tabs_C" class="tab-pane fade">
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Estado</label>
                                                    <div class="col-lg-3">
                                                        <select id="id_cod_estado" name="estado" class="form-control select2 input-sm" >
                                                            <option value="">Seleccione</option>
                                                            <?php foreach ($list_estado as $estado) { ?>
                                                                <option value="<?php echo $estado->cod_estado ?>"><?php echo $estado->estado ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Municipio</label>
                                                    <div class="col-lg-3">
                                                        <select id="id_cod_municipio" class="form-control select2 input-sm" name="municipio" >
                                                            <option value="0" selected="selected">Seleccione</option>
                                                        </select>
                                                    </div>
                                                    <label class="col-lg-1 control-label"style="font-weight:bold">Parroquia</label>
                                                    <div class="col-lg-3">
                                                        <select id="id_cod_parroquia" name="parroquia" class="form-control">
                                                            <option selected="" value="0">Seleccione</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Dirección</label>
                                                    <div class="col-lg-11">
                                                        <textarea id="direccion" maxlength="300" name="direccion" placeholder="Introduzca la Dirección" class="form-control"></textarea>
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
                                                    <input class="form-control"  type='hidden' placeholder="user" id="user_create_id" name="user_create_id" value="<?php echo"$id_user" ?>"/>
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
