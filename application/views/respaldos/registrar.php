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
                $("#cedula,#telefono").numeric(); //Valida solo permite valores numericos
                $('#email').alphanumeric({allow: "@-_."});
                $('#siglas').alpha({allow: " "}); //Solo permite texto
                $('#direccion').alphanumeric({allow: " ,.-#"}); //Solo permite texto numericos"

                $('#volver').click(function () {
                    url = '<?php echo base_url()?>index.php/usuarios/usuarios/'
                    window.location = url
                })

                $("#cedula").change(function (event) {

                    var cedula = $('#cedula').val();
                    //var hosting = $('#id_hosting').val(); // Captura del hosting (dominio)
                    var hosting = "consultaelectoral.bva.org.ve/cedula="
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
                    
                    if ($('#username').val().trim() == '') {

                        bootbox.alert("Rellene el campo de usuario", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $("#username").parent('div').addClass('has-error')
                            $('#username').val('')
                            $("#username").focus();
                        });

                    } else if ($('#password').val() == '') {

                        bootbox.alert("El campo contraseña no puede estar en blanco", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $("#password").parent('div').addClass('has-error')
                            $('#password').val('')
                            $("#password").focus();
                        });
                        
                    } else if ($('#cedula').val().trim() == '' || $('#cedula').val().trim() == 0) {
                        bootbox.alert("Rellene el campo de cédula", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $("#cedula").parent('div').addClass('has-error')
                            $('#cedula').val('')
                            $("#cedula").focus();
                        });
                    
                    
                    } else if ($('#cedula').val().length < 6) {
	    
	    
                        bootbox.alert("La cédula está incompleta", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $("#cedula").parent('div').addClass('has-error')
                            $('#cedula').val('')
                            $("#cedula").focus();
                        });

                    } else if ($('#first_name').val().trim() == '') {

                        bootbox.alert("Rellene el campo de nombres", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $("#first_name").parent('div').addClass('has-error')
                            $('#first_name').val('')
                            $("#first_name").focus();
                        });

                    } else if ($('#last_name').val().trim() == '') {

                        bootbox.alert("Rellene el campo de apellidos", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $("#last_name").parent('div').addClass('has-error')
                            $('#last_name').val('')
                            $("#last_name").focus();
                        });

                                         
                    }else {
                        
                         $.post('<?php echo base_url(); ?>index.php/usuarios/usuarios/guardar', $('#form_usuarios').serialize(), function (response) {
							 
							 //~ alert(response[4]);
							 
							if (response[4] == '1') {
								bootbox.alert("Disculpe, El Usuario ya se encuentra registrado", function () {
								}).on('hidden.bs.modal', function (event) {
									$("#valor").parent('div').addClass('has-error')
									$('#valor').val('')
									$("#valor").focus();

								});

							}else {
								bootbox.alert("Se registro con exito", function () {
								}).on('hidden.bs.modal', function (event) {
									url = '<?php echo base_url(); ?>index.php/usuarios/usuarios'
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

                                <form class="form-horizontal" id="form_usuarios">
                                    <fieldset>
                                        <h3>Registro de Nuevo Usuario</h3>
                                        <br/>
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="inputEmail" style="font-weight:bold">Usuario</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su Usuario" id="username" name="username" class="form-control">
                                            </div>
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Contraseña</label>
                                            <div class="col-lg-5">
                                                <input type="password" placeholder="Introduzca su Contraseña" id="password" name="password" class="form-control">
                                            </div>

                                        </div>
   
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Cédula</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su Cédula" maxlength="8" id="cedula" name="cedula"  class="form-control">

                                            </div>
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Nombre</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su Nombre" id="first_name" maxlength="70" name="first_name" class="form-control">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Apellido</label>
                                            <div class="col-lg-5"> 
                                                <input type="text" placeholder="Introduzca su Apellido" id="last_name" maxlength="70"  name="last_name" class="form-control">

                                            </div>
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Email</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su Email" id="email" maxlength="75" name="email" class="form-control">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Télefono</label>
                                            <div class="col-lg-5"> 
                                                <input type="text" placeholder="Introduzca su telefono" id="telefono" maxlength="11"  name="telefono" class="form-control">

                                            </div>
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Cargo</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su cargo" id="cargo" name="cargo" class="form-control">

                                            </div>
                                        </div>
                                        

                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="select" style="font-weight:bold">Tipo usuario</label>
                                            <div class="col-lg-5">
                                                <select id="tipo_usuario" name="tipo_usuario" class="form-control">
                                                    <option value='0'>Seleccione</option>
                                                    <option value='Administrador'>Administrador</option>
                                                    <!--<option value='Ventas'>Ventas</option>-->
                                                    <!--<option value='Auditor'>Auditor</option>-->
                                                    <option value='Almacen'>Almacen</option>
                                                    <!--<option value='Vendedor'>Vendedor</option>
                                                    <option value='Comercializacion'>Comercialización</option>-->
                                                </select>
        

                                            </div>
                                            <label class="col-lg-1 control-label" for="select" style="font-weight:bold">&nbsp;Estátus</label>

                                            <div class="col-lg-5">
                                                <select id='estatus' name="estatus" class="form-control" name='is_active'>
                                                    <!--<option value='0'>Seleccione</option>-->
                                                    <option value='1'>Activo</option>
                                                    <option value='0'>Inactivo</option>
                                                </select>
                                                <br>

                                            </div>

                                        </div>
                                        <br/>



                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <input class="form-control"  type='hidden' placeholder="user" id="user_create_id" name="user_create_id" value="<?php echo"$id_user" ?>"/>
                                                <button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning " >
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
