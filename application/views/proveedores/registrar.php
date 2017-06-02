<!--// si el usuario no esta logueado lo envia al logueo //-->
<?php
if (isset($this->session->userdata['logged_in'])) {
    $username = ($this->session->userdata['logged_in']['username']);
    $email = ($this->session->userdata['logged_in']['email']);
    $id = ($this->session->userdata['logged_in']['id']);
} else {
    header("location: ../../");
}
?>

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

        $('#venc_solvencia_laboral,#venc_solvencia_ince,#venc_solvencia_sso,#venc_cirif,#venc_rcn').datepicker({
            format: "dd/mm/yyyy",
            //~ startDate: 'today',
            minDate: "-1D",
            maxDate: "-1D",
            language: "es",
            autoclose: true,
        })

        $("select").select2();
        $("#cedula,#telefono,#tlf,#cirif").numeric(); //Valida solo permite valores numericos
        $('#email').alphanumeric({allow: "@-_."});
        $('#siglas').alpha({allow: " "}); //Solo permite texto
        $('#direccion').alphanumeric({allow: " ,.-#"}); //Solo permite texto numericos"

        $('#volver').click(function () {
            url = '<?php echo base_url() ?>index.php/proveedores/ControllersProveedores/'
            window.location = url
        })

        ////////////////// CONSULTA DE CÉDULA A BASE DE DATOS ////////////////////
        $("#cirif").change(function (event) {
            var cedula = $('#cirif').val();
            var tipo = $('#tipoproveedor').val();
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
        
        ////////////////// CONSULTA DE CEDULA A BASE DE DATOS ////////////////////
        //~ $("#cedula").change(function (event) {
            //~ var cedula = $('#cedula').val();
            //~ var tipo = $('#nacionalidad').val();
            //~ if (tipo == 'V') {
                //~ var hosting = "consultaelectoral.bva.org.ve/cedula="
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

            if ($('#codigo').val().trim() == '') {

                bootbox.alert("Rellene el campo de codigo", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_A"]').tab('show');
                    $("#codigo").parent('div').addClass('has-error')
                    $('#codigo').val('')
                    $("#codigo").focus();
                });

            } else if ($('#t_proveedor').val().trim() == '' || $('#t_proveedor').val().trim() == 0) {
                bootbox.alert("Rellene el tipo de proveedor", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_A"]').tab('show');
                    $("#t_proveedor").parent('div').addClass('has-error')
                    $('#t_proveedor').val('')
                    $("#t_proveedor").focus();
                });

            } else if ($('#tipoproveedor').val().trim() == '' || $('#tipoproveedor').val().trim() == 0) {
                bootbox.alert("Rellene el tipo de identificación", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_A"]').tab('show');
                    $("#tipoproveedor").parent('div').addClass('has-error')
                    $('#tipoproveedor').val('')
                    $("#tipoproveedor").focus();
                });

            } else if ($('#cirif').val().trim() == '' || $('#cirif').val().trim() == 0) {
                bootbox.alert("Rellene el campo de cédula", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_A"]').tab('show');
                    $("#cirif").parent('div').addClass('has-error')
                    $('#cirif').val('')
                    $("#cirif").focus();
                });

            } else if ($('#nombre').val() == '') {

                bootbox.alert("Ingrese Nombre o Razón Social", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_A"]').tab('show');
                    $("#nombre").parent('div').addClass('has-error')
                    $('#nombre').val('')
                    $("#nombre").focus();
                });

            } else if ($('#tlf').val().trim() == '') {

                bootbox.alert("Rellene campo del télefono", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_A"]').tab('show');
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

                bootbox.alert("Rellene el campo Email", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href="#tabs_A"]').tab('show');
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
                bootbox.alert("Rellene el tipo de identificación", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#nacionalidad").parent('div').addClass('has-error')
                    $('#nacionalidad').val('')
                    $("#nacionalidad").focus();
                });

            } else if ($('#cedula').val().trim() == '' || $('#cedula').val().trim() == 0) {
                bootbox.alert("Rellene el campo de cédula", function () {
                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#cedula").parent('div').addClass('has-error')
                    $('#cedula').val('')
                    $("#cedula").focus();
                });

            } else if ($('#nombres').val() == '') {

                bootbox.alert("Ingrese Nombre", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#nombres").parent('div').addClass('has-error')
                    $('#nombres').val('')
                    $("#nombres").focus();
                });

            } else if ($('#apellidos').val() == '') {

                bootbox.alert("Ingrese Apellido", function () {

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

                $.post('<?php echo base_url(); ?>index.php/proveedores/ControllersProveedores/guardar', $('#form_usuarios').serialize(), function (response) {
                    if (response[0] == '1') {
                        bootbox.alert("Disculpe, El Proveedor que desea registrar ya existe con el número de identificación asignado", function () {
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
                            url = '<?php echo base_url(); ?>index.php/proveedores/ControllersProveedores'
                            window.location = url
                        });
                    }

                });
            }

        });

    });
    
    // Función para validar si la fecha actual es mayor a una fecha de vencimiento
	function validate_fechaMayorQue(fecha_actual,fechaVencimiento){

		valuesStart=fecha_actual.split("/");

		valuesEnd=fechaVencimiento.split("/");

		// Verificamos que la fecha no sea posterior a la actual

		var dateStart=new Date(valuesStart[2],(valuesStart[1]-1),valuesStart[0]);

		var dateEnd=new Date(valuesEnd[2],(valuesEnd[1]-1),valuesEnd[0]);

		if(dateStart>=dateEnd)
		{
			return 0;
		}else{
			return 1;
		}

	}
	
	// Función para chequear si hay documentos vencidos
	function validar_docs(fecha_v){

		// Obtenemos la fecha actual del sistema
		var fecha_actual=new Date();
		
		var mes = parseInt(fecha_actual.getMonth()+1);
		// Completamos el mes con un cero si es necesario 
		if (mes < 10){
			mes="0"+mes;
		}else{
			mes = mes;
		}
		
		fecha_actual=fecha_actual.getDate()+"/"+mes+"/"+fecha_actual.getFullYear();
		var fechaVencimiento=fecha_v;
		
		alert("Fecha actual: "+String(fecha_actual)+", Fecha de Vencimineto: "+fechaVencimiento);
		
		if(fechaVencimiento != ''){
			vencido = validate_fechaMayorQue(String(fecha_actual),fechaVencimiento);
			//~ alert(vencido);
		
			if(vencido == 1){
				//~ document.write("La fecha "+fechaVencimiento+" es superior a la fecha "+fecha_actual);
				alert("El documento es válido...");
			}else{
				//~ document.write("La fecha "+fechaVencimiento+" NO es superior a la fecha "+fecha_actual);
				alert("El documento está vencido...");
			}
		}
	}

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

                            <form class="form-horizontal" id="form_usuarios">
                                <div class="tab-content">
                                    <fieldset>
                                        <h3 id="nav-tabs">Registro de Nuevo Proveedor</h3>
                                        <!-- Apertura de Tabs (Secciones) -->
                                        <br/>
                                        <ul class="nav nav-tabs">
                                            <li class="active" id="#tabs_2" data-toggle="popover" style="font-weight:bold" data-trigger="focus" title="Información Básica" data-content='En este panel se ingresan la Información Básica del proveedor' data-placement="top">
                                                <a data-toggle="tab" href="#tabs_A"><i class="fa fa-file-text-o"></i>&nbsp;Información Básica</a>
                                            </li>
                                            <li  data-toggle="popover" data-trigger="focus" title="Dirección" style="font-weight:bold" data-content="En este panel se ingresan la Dirección del proveedor" data-placement="top">
                                                <a data-toggle="tab" href="#tabs_C"><i class="fa fa-map-marker"></i>&nbsp;Dirección</a>
                                            </li>
                                            <!--<li  data-toggle="popover" data-trigger="focus" title="Datos del Contacto" style="font-weight:bold" data-content="En este panel se ingresan los datos del contacto del proveedor" data-placement="top">
                                                <a data-toggle="tab" href="#tabs_B"><i class="fa fa-user"></i>&nbsp;Datos del Contacto</a>
                                            </li>
                                            <li  data-toggle="popover" data-trigger="focus" title="Documentos" style="font-weight:bold" data-content="En este panel se ingresan los documuentos del proveedor" data-placement="top">
                                                <a data-toggle="tab" href="#tabs_D"><i class="fa fa-file"></i>&nbsp;Documentos </a>
                                            </li>-->
                                        </ul>
                                        <!-- Cierre de Tabs (Secciones) -->
                                        <br/>
                                        <br/>
                                        <div class="tab-content">
                                            <div id="tabs_A" class="tab-pane fade  in active">
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Código</label>
                                                    <div class="col-lg-2">
                                                        <div class="input-group">

                                                            <input type="text" value="<?php printf('P' . "%05d", $detalles_lista + 1); ?>" readonly="" placeholder="Introduzca el Código asignar" maxlength="8" id="codigo" name="codigo"  class="form-control">
                                                        </div>
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Tipo</label>
                                                    <div class="col-lg-4">
                                                        <select id="t_proveedor" name="t_proveedor" class="form-control">
                                                            <option selected="" value="0">--</option>
                                                            <?php foreach ($list_tipo as $tipo) { ?>
                                                                <option value="<?php echo $tipo->cod_tipo ?>"><?php echo $tipo->tipo_proveedor ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Cédula / RIF</label>
                                                    <div class="col-lg-1">
                                                        <select id="tipoproveedor" name="tipoproveedor" class="form-control">
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
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Nombre o Razón Social</label>
                                                    <div class="col-lg-11">
                                                        <input type="text" placeholder="Introdúzca Nombre o Razón Social" id="nombre" maxlength="70" name="nombre" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Télefono Local</label>
                                                    <div class="col-lg-3"> 
                                                        <input type="text" placeholder="Introdúzca su télefono" id="tlf" maxlength="11"  name="tlf" class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Télefono Móvil</label>
                                                    <div class="col-lg-3"> 
                                                        <input type="text" placeholder="Introdúzca su télefono" id="tlf_movil" maxlength="11"  name="tlf_movil" class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Email</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" placeholder="Introdúzca su Email" id="email" maxlength="75" name="email" class="form-control">
                                                    </div>
                                                    <br/>
                                                    <br/>
                                                    <br/>
                                                </div>
                                                <div class="form-group">
													<label class="col-lg-1 control-label" style="font-weight:bold" >Fax</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" placeholder="Introdúzca su Email" id="fax" maxlength="75" name="fax" class="form-control">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label style="font-weight:bold">Activo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                        <input type="checkbox" id="estatus" name="estatus" />
                                                        <label>&nbsp;&nbsp;&nbsp; **¿Proveedor Activo?</label>
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
                                                        <textarea id="direccion" maxlength="300" name="direccion" placeholder="Introdúzca la Dirección" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <br/>
                                                <br/>
                                                <br/>
                                            </div>
                                            
                                            <!--<div id="tabs_D" class="tab-pane fade">
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="checkbox" id="rif" name="rif" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight:bold">Registro de Información Fiscal (RIF)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                    </div>
                                                    <div class="col-lg-6">   
                                                        <label style="font-weight:bold">Fecha Vencimiento&nbsp;</label>
                                                        <input id="venc_cirif" style="width: 50%" placeholder="Fecha de Vencimiento RIF" name="venc_cirif" type="text" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="checkbox" id="solvencia_laboral" name="solvencia_laboral" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight:bold">Solvencia Laboral&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                    </div>
                                                    <div class="col-lg-6">  
                                                        <label style="font-weight:bold">Fecha Vencimiento&nbsp;</label>
                                                        <input id="venc_solvencia_laboral"  style="width: 50%" placeholder="Fecha de Vencimiento Solvencia Laboral" name="venc_solvencia_laboral" type="text" onChange="validar_docs($('#venc_solvencia_laboral').val())"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="checkbox" id="solvencia_ince" name="solvencia_ince" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight:bold">Solvencia INCE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                    </div>
                                                    <div class="col-lg-6">  
                                                        <label style="font-weight:bold">Fecha Vencimiento&nbsp;</label>
                                                        <input id="venc_solvencia_ince"  style="width: 50%" placeholder="Fecha de Vencimiento Solvencia INCE" name="venc_solvencia_ince" type="text" onChange="validar_docs($('#venc_solvencia_ince').val())"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="checkbox" id="solvencia_sso" name="solvencia_sso" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight:bold">Solvencia SSO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                    </div>
                                                    <div class="col-lg-6">  
                                                        <label style="font-weight:bold">Fecha Vencimiento&nbsp;</label>
                                                        <input id="venc_solvencia_sso"  style="width: 50%" placeholder="Fecha de Vencimiento Solvencia SSO" name="venc_solvencia_sso" type="text" onChange="validar_docs($('#venc_solvencia_sso').val())"/>
                                                    </div>
                                                </div>
                                                <div class="form-group"> 
                                                    <div class="col-lg-6">
                                                        <input type="checkbox" id="rcn" name="rcn" />
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight:bold">Registro Nacional de Contratistas (RCN)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label style="font-weight:bold">Fecha Vencimiento&nbsp;</label>
                                                        <input id="venc_rcn" style="width: 50%"  placeholder="Fecha de Vencimiento RCN" name="venc_rcn" type="text" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="checkbox" id="cedula_represen" name="cedula_represen" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight:bold">Cédula de identidad del Representante Legal</label>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <input type="checkbox" id="autorizacion_represen" name="autorizacion_represen" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight:bold">Autorización Representante Legal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="checkbox" id="acta_constitutiva" name="acta_constitutiva" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight:bold">Registro y Modificaciones Acta Constitutiva&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                    </div>
                                                    <div class="col-lg-5">  
                                                        <input type="checkbox" id="snc" name="snc" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight:bold">Superintendencia Nacional de Cooperativas</label>
                                                    </div>
                                                </div>
                                            </div>-->
                                            
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
                                                        <input type="text" placeholder="Introdúzca Cédula o RIF " maxlength="9" id="cedula" name="cedula"  class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Nombre</label>
                                                    <div class="col-lg-5">
                                                        <input type="text" placeholder="Introdúzca Nombre " id="nombres" name="nombres"  class="form-control">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Apellido</label>
                                                    <div class="col-lg-5">
                                                        <input type="text" placeholder="Introdúzca Apellido " id="apellidos" name="apellidos"  class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Télefono</label>
                                                    <div class="col-lg-5"> 
                                                        <input type="text" placeholder="Introdúzca su télefono" id="telefono" maxlength="11"  name="telefono" class="form-control">
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
                                                    <input class="form-control"  type='hidden' placeholder="user" id="user_create_id" name="user_create_id" value="<?php echo"$id" ?>"/>
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


