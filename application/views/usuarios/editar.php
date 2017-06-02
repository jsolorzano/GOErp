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
                
                $("#tipo_usuario").select2('val',$("#id_tipo_usuario").val());
                
                //~ if ($("#id_estatus").val() == "t"){
                    //~ $("#estatus").select2('val',"TRUE");
                //~ }else{
                    //~ $("#estatus").select2('val',"FALSE");
                //~ }
                
                $("#estatus").select2('val',$("#id_estatus").val());
                
                $('#cedula').numeric(); //Valida solo permite valores numericos
                $('#email').alphanumeric({allow: "@-_."});
                $('#siglas').alpha({allow: " "}); //Solo permite texto
                $('#direccion').alphanumeric({allow: " ,.-#"}); //Solo permite texto numericos"
                
                $('#volver').click(function(){
                    url = '<?php echo base_url()?>index.php/usuarios/usuarios/'
                    window.location = url
                })
                
                
                $('#registrar').click(function(e){
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    //~ var RegExPattern = /(?!^[0-9]*$)(?!^[a-zA-Z]*$)^([a-zA-Z0-9]{8,12})$/;  // Expresión regular para validar contraseña segura (primera opción: Entre 8 y 10 caracteres, por lo menos un digito y un alfanumérico, y no puede contener caracteres espaciales)
                    var RegExPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,12}/;  // Nueva opción 1 (Minimo 8 caracteres, maximo 12, al menos una letra mayúscula, al menos una letra minucula, al menos un dígito, no espacios en blanco, al menos 1 caracter especial)
                    //~ var RegExPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&#.$($)$-$_‌​])[A-Za-z\d$@$!%*?&a‌​mp;#.$($)$-$_]{8,12}‌​$/;  // Nueva opción 2 con más caracteres especiales(Minimo 8 caracteres, maximo 12, al menos una letra mayúscula, al menos una letra minucula, al menos un dígito, no espacios en blanco, al menos 1 caracter especial)
                    
                    if ($('#username').val().trim() == '') {

                        bootbox.alert("Rellene el campo de usuario", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $("#username").parent('div').addClass('has-error')
                            $('#username').val('')
                            $("#username").focus();
                        });

                    } else if ($('#password').val() == '') {

                        bootbox.alert("El campo clave no puede estar en blanco", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $("#password").parent('div').addClass('has-error')
                            $('#password').val('')
                            $("#password").focus();
                        });
                        
                    } else if (/\s/.test($('#password').val().trim())) {

                        bootbox.alert("Disculpe, la clave debe cumplir la siguiente política de seguridad:\n-Minimo 8 caracteres\n-Maximo 12\n-Al menos una letra mayúscula\n-Al menos una letra minúscula\n-Al menos un dígito\n-No poseer espacios en blanco\n-Tener al menos 1 caracter especial", function () {
                        }).on('hidden.bs.modal', function (event) {
                            $("#password").parent('div').addClass('has-error')
                            $('#password').val('')
                            $("#password").focus();
                        });
                        
                    } else if (!($('#password').val().trim().match(RegExPattern))) {

                        bootbox.alert("Disculpe, la clave debe cumplir la siguiente política de seguridad:\n-Minimo 8 caracteres\n-Maximo 12\n-Al menos una letra mayúscula\n-Al menos una letra minúscula\n-Al menos un dígito\n-No poseer espacios en blanco\n-Tener al menos 1 caracter especial", function () {
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
                        
                         $.post('<?php echo base_url(); ?>index.php/usuarios/usuarios/actualizar', $('#form_usuarios').serialize(), function (response) {

                            bootbox.alert("Se actualizo con exito", function () {
                            }).on('hidden.bs.modal', function (event) {
                                url = '<?php echo base_url(); ?>index.php/usuarios/usuarios'
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

                                <form class="form-horizontal" id="form_usuarios">
                                    <fieldset>
                                        <h3>Editar Usuario <?php echo $editar[0]->username?> </h3>
                                        <br/>
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="inputEmail" style="font-weight:bold">Usuario</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su Usuario" id="username" value="<?php echo $editar[0]->username?>" name="username" class="form-control">
                                            </div>
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Clave</label>
                                            <div class="col-lg-5">
                                                <input type="password"  placeholder="Introduzca su Contraseña" id="password" name="password" class="form-control">
                                            </div>

                                        </div>
                                  
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Cédula</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su Cédula" value="<?php echo $editar[0]->cedula?>" id="cedula" name="cedula" class="form-control">

                                            </div>
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Nombre</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su Nombre" id="first_name" value="<?php echo $editar[0]->first_name?>" name="first_name" class="form-control">

                                            </div>
                                        </div>
                          
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Apellido</label>
                                            <div class="col-lg-5"> 
                                                <input type="text" placeholder="Introduzca su Apellido" value="<?php echo $editar[0]->last_name?>" id="last_name" name="last_name" class="form-control">

                                            </div>
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Email</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su Email" id="email" value="<?php echo $editar[0]->email?>" name="email" class="form-control">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Télefono</label>
                                            <div class="col-lg-5"> 
                                                <input type="text" placeholder="Introduzca su Télefono" value="<?php echo $editar[0]->telefono?>" id="telefono" name="telefono" class="form-control">

                                            </div>
                                            <label class="col-lg-1 control-label" for="inputPassword" style="font-weight:bold">Cargo</label>
                                            <div class="col-lg-5">
                                                <input type="text" placeholder="Introduzca su Cargo" id="cargo" value="<?php echo $editar[0]->cargo?>" name="cargo" class="form-control">

                                            </div>
                                        </div>
                        
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="select" style="font-weight:bold">Tipo Usuario</label>
                                            <div class="col-lg-5">
                                                <select id="tipo_usuario" name="tipo_usuario" class="form-control">
                                                    
                                                    <option value='0'>Seleccione</option>
                                                    <option value='Administrador'>Administrador</option>
                                                    <!--<option value='Ventas'>Ventas</option>
                                                    <option value='Auditor'>Auditor</option>-->
                                                    <option value='Almacen'>Almacen</option>
                                                    <!--<option value='Vendedor'>Vendedor</option>
                                                    <option value='Comercializacion'>Comercialización</option>-->
                                                </select>
                                   
                                            </div>
                                            <label class="col-lg-1 control-label" for="select" style="font-weight:bold">&nbsp;Estátus</label>

                                            <div class="col-lg-5">
                                                <select id='estatus' name="estatus" class="form-control">
                                                    <!--<option value='0'>Seleccione</option>-->
                                                    <option value='1'>Activo</option>
                                                    <option value='0'>Inactivo</option>
                                                </select>
                           

                                            </div>

                                        </div>
                                        <br/>
                                        <br/>


                                        <div class="form-group">
                                            <div class="col-lg-12">
<!--
                                                <input class="form-control"  type='hidden' placeholder="user" id="user_create_id" name="user_create_id" value="<?php echo $id_user ?>"/>
-->
                                                <input class="form-control"  type='hidden' placeholder="user" id="id" name="id" value="<?php echo $id ?>"/>
                                                <button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning " >
                                                    &nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
                                                </button>
                                                 <button type="submit" id="registrar" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                                &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Actualizar
                                                </button>
                                                <input id="id_tipo_usuario" type="hidden" value="<?php echo $editar[0]->tipo_usuario?>"/>
                                                <input id="id_estatus" type="hidden" value="<?php echo $editar[0]->estatus?>"/>
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
