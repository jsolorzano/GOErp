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
  
<?php if ($tipouser == 'Almacen' || $tipouser == 'Administrador' || $tipouser == 'Comercializacion'){
	
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
        $("#cantidad,#ganancia, #stock_max,#stock_min, #stock_req").numeric(); //Valida solo permite valores numericos
        $('#tipoproducto').alpha({allow: " "}); //Solo permite texto
        $('#descripcion').alphanumeric({allow: " ,.-#"}); //Solo permite texto numericos"
        $('#precio_unitario').numeric({allow: " .,"}); //Solo permite texto numericos"

        $('#tiempo_utilidad').datepicker({
            format: "mm/yyyy",
            language: "es",
            minViewMode: 1,
            autoclose: true,
        })
        
        // Función para calcular el monto total
		$('#precio_unitario,#ganancia').change(function () {

            var precio_unitario = $('#precio_unitario').val();
            var ganancia = $('#ganancia').find('option').filter(':selected').text();
			ganancia = ganancia.split("%");
			ganancia = ganancia[0];
			var monto_ganancia = 0;
			var precio_total = 0;
			var iva = $('#iva').find('option').filter(':selected').text();
            var monto_iva = 0;
			iva = iva.split("%");
			iva = iva[0];
			
			//~ alert("Precio unitario: "+precio_unitario);
			//~ alert("%Ganancia: "+ganancia);
			
			if(precio_unitario != '' && ganancia != 'Seleccione'){
				monto_ganancia = (parseFloat(precio_unitario)*parseFloat(ganancia)/100);
				precio_total = (parseFloat(precio_unitario)+parseFloat(monto_ganancia));
				
				$("#precio_total").val(precio_total);
			}else{
				$("#precio_total").val('');
			}
			
			if($("#iva").val() != ""){
				if($("#precio_total").val() == ""){
					$("#monto_iva").val(0);
				}else{
					$("#monto_iva").val(parseFloat($("#precio_total").val())*parseFloat(iva)/100);
				}
			}

        });
        

		// Función para calcular el monto del iva
		$('#precio_total,#iva').change(function () {
			
			var precio_unitario = $('#precio_unitario').val();
            var iva = $('#iva').find('option').filter(':selected').text();
            var monto_iva = 0;
			iva = iva.split("%");
			iva = iva[0];
			
			//~ alert(iva);
			
			if(precio_unitario != '' && iva != 'Seleccione'){
				monto_iva = (parseFloat(precio_unitario)*parseFloat(iva)/100);
				$("#monto_iva").val(monto_iva);
			}else{
				$("#monto_iva").val('');
			}

        });
        

        $('#volver').click(function () {
            url = '<?php echo base_url() ?>index.php/productos/ControllersProductos/'
            window.location = url
        })
        
        $("#tipoproducto").select2('val', $("#id_tipoproducto").val());
        $("#proveedor").select2('val', $("#id_proveedor").val());
        $("#ganancia").select2('val', $("#id_ganancia").val());
        $("#unidad_medida").select2('val', $("#id_unidad_medida").val());
        $("#iva").select2('val', $("#id_iva").val());

        $("#registrar").click(function (e) {
            e.preventDefault();  // Para evitar que se envíe por defecto

            if ($('#tipoproducto').val().trim() == '') {
                bootbox.alert("Rellene el tipo de producto", function () {
                }).on('hidden.bs.modal', function (event) {
                    $("#tipoproducto").parent('div').addClass('has-error')
                    $('#tipoproducto').val('')
                    $("#tipoproducto").focus();
                });


            } else if ($('#nombre').val() == '') {

                bootbox.alert("Ingrese Nombre del producto", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#nombre").parent('div').addClass('has-error')
                    $('#nombre').val('')
                    $("#nombre").focus();
                });

            } else if ($('#descripcion').val().trim() == '') {

                bootbox.alert("Ingrese la descipción del producto", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#descripcion").parent('div').addClass('has-error')
                    $('#descripcion').val('')
                    $("#descripcion").focus();
                });


            } else if ($('#proveedor').val().trim() == '' || $('#proveedor').val().trim() == 0) {

                bootbox.alert("Seleccione el Proveedor del Producto", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#proveedor").parent('div').addClass('has-error')
                    $('#proveedor').val('')
                    $("#proveedor").focus();
                });

            } else if ($('#cantidad').val().trim() == '' || $('#cantidad').val().trim() == 0) {

                bootbox.alert("Ingrese la Cantidad", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#cantidad").parent('div').addClass('has-error')
                    $('#cantidad').val('')
                    $("#cantidad").focus();
                });

            } else if ($('#precio_unitario').val() == '' || $('#precio_unitario').val().trim() == 0) {

                bootbox.alert("Ingrese el Precio unitario del Producto", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#precio_unitario").parent('div').addClass('has-error')
                    $('#precio_unitario').val('')
                    $("#precio_unitario").focus();
                });


            } else if ($('#ganancia').val() == '' || $('#ganancia').val().trim() == 0) {

                bootbox.alert("Ingrese la ganancia del producto", function () {

                }).on('hidden.bs.modal', function (event) {
                    $('.nav-tabs a[href=#tabs_B]').tab('show');
                    $("#ganancia").parent('div').addClass('has-error')
                    $('#ganancia').val('')
                    $("#ganancia").focus();
                });

            } else if ($('#stock_max').val() == '' || $('#stock_max').val().trim() == 0) {

                bootbox.alert("Ingrese el Stock Maximo", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#stock_max").parent('div').addClass('has-error')
                    $('#stock_max').val('')
                    $("#stock_max").focus();
                });

            } else if ($('#stock_min').val() == '' || $('#stock_min').val().trim() == 0) {

                bootbox.alert("Ingrese el Stock Minimo", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#stock_min").parent('div').addClass('has-error')
                    $('#stock_min').val('')
                    $("#stock_min").focus();
                });

            } /*else if ($('#stock_req').val() == '' || $('#stock_req').val().trim() == 0) {

                bootbox.alert("Ingrese el Stock de Requerimiento", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#stock_req").parent('div').addClass('has-error')
                    $('#stock_req').val('')
                    $("#stock_req").focus();
                });

            }*/ else if ($('#unidad_medida').val().trim() == '' || $('#unidad_medida').val().trim() == 0) {

                bootbox.alert("Seleccione la unidad de medida", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#unidad_medida").parent('div').addClass('has-error')
                    $('#unidad_medida').val('')
                    $("#unidad_medida").focus();
                });


            } else if ($('#iva').val().trim() == '' || $('#iva').val().trim() == 0) {

                bootbox.alert("Seleccione el iva correspondiente al producto", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#iva").parent('div').addClass('has-error')
                    $('#iva').val('')
                    $("#iva").focus();
                });



            } /*else if ($('#tiempo_utilidad').val().trim() == '' || $('#tiempo_utilidad').val().trim() == 0) {

                bootbox.alert("Ingrese el Tiempo util del producto", function () {

                }).on('hidden.bs.modal', function (event) {
                    $("#tiempo_utilidad").parent('div').addClass('has-error')
                    $('#tiempo_utilidad').val('')
                    $("#tiempo_utilidad").focus();
                });

            }*/ else {

                $.post('<?php echo base_url(); ?>index.php/productos/ControllersProductos/actualizar', $('#form_usuarios').serialize(), function (response) {
                    bootbox.alert("Se Actualizo con exito", function () {
                    }).on('hidden.bs.modal', function (event) {
                        url = '<?php echo base_url(); ?>index.php/productos/ControllersProductos'
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


                            <form class="form-horizontal" id="form_usuarios">
                                <div class="tab-content">
                                    <fieldset>
                                        <h3 id="nav-tabs">Editar Producto <?php echo $editar[0]->codigo ?></h3>

                                        <br/>
                                        <br/>
                                        <div class="tab-content">
                                            <div id="tabs_A" class="tab-pane fade  in active">

                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Código</label>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">

                                                            <input type="text" value="<?php echo $editar[0]->codigo ?>" readonly="" placeholder="Introduzca el Código asignar" maxlength="8" id="codigo" name="codigo"  class="form-control">
                                                        </div>

                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Tipo</label>
                                                    <div class="col-lg-3">
                                                        <select id="tipoproducto" name="tipoproducto" class="form-control select2 input-sm" >
                                                            <option value="">Seleccione</option>
                                                            <?php foreach ($list_prod as $producto) { ?>
                                                                <option value="<?php echo $producto->id ?>"><?php echo $producto->tipo_producto ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Nombre</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" value="<?php echo $editar[0]->nombre ?>" placeholder="Introduzca nombre del producto " maxlength="20" id="nombre" name="nombre"  class="form-control">
                                                    </div>

                                                </div>

                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Descripción</label>
                                                    <div class="col-lg-7">
                                                        <input type="text" value="<?php echo $editar[0]->descripcion ?>" placeholder="Introduzca Descripción del Producto" id="descripcion" maxlength="70" name="descripcion" class="form-control">

                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Proveedor</label>
                                                    <div class="col-lg-3"> 
                                                        <select id="proveedor" name="proveedor" class="form-control select2 input-sm" >
                                                            <option value="0">Seleccione</option>

                                                            <?php foreach ($list_prov as $proveedor) { ?>
                                                                <option value="<?php echo $proveedor->id ?>"><?php echo $proveedor->nombre ?></option>
                                                            <?php } ?>

                                                        </select>
                                                        <br>

                                                    </div>
                                                </div>
                                                <div class="form-group">




                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Cantidad</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" value="<?php echo $editar[0]->cantidad ?>" placeholder="Introduzca Cantidad" id="cantidad" maxlength="5" name="cantidad" class="form-control" readonly="true">
                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Precio</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" value="<?php echo $editar[0]->precio_unitario ?>" placeholder="Introduzca Precio Unitario" id="precio_unitario" maxlength="20" name="precio_unitario" class="form-control">

                                                    </div>

                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >% ganancia</label>
                                                    <div class="col-lg-3">
<!--
                                                        <input type="text" placeholder="Porcentaje de Ganancia" id="ganancia" maxlength="5" name="ganancia" class="form-control">
-->
							<select id="ganancia" name="ganancia" class="form-control select2 input-sm" >
                                                              <option value="0">Seleccione</option>

                                                            <?php foreach ($ganancia as $ganancia) { ?>
                                                                <option value="<?php echo $ganancia->id ?>"><?php echo $ganancia->valor ?></option>
                                                            <?php } ?>

                                                        </select>

                                                    </div>
                                                    <br/>

                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold">Stock Max</label>
                                                    <div class="col-lg-3"> 
                                                        <input type="text" value="<?php echo $editar[0]->stock_max ?>" placeholder="Introduzca Stock Maximo" id="stock_max" maxlength="10"  name="stock_max" class="form-control">

                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Stock Min</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" value="<?php echo $editar[0]->stock_min ?>" placeholder="Introduzca Stock Minimo" id="stock_min" maxlength="10" name="stock_min" class="form-control">

                                                    </div>


                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Stock Req.</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" value="<?php echo $editar[0]->stock_req ?>" placeholder="Introduzca Stock Requerimiento" id="stock_req" maxlength="10" name="stock_req" class="form-control" readonly="true">

                                                    </div>
                                                    <br/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" title="Unidad de Medida (Seleccione la unidad de medida aplicable para el producto a actualizar)">UDM</label>
                                                    <div class="col-lg-3"> 
                                                        <select id="unidad_medida" name="unidad_medida" class="form-control select2 input-sm" >
                                                            <option value="">Seleccione Unidad de Medida</option>

                                                            <?php foreach ($list_um as $unidad) { ?>
                                                                <option value="<?php echo $unidad->id ?>"><?php echo $unidad->simbolo ?> - <?php echo $unidad->unidades ?></option>
                                                            <?php } ?>

                                                        </select>
                                                        <br>

                                                    </div>
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >IVA</label>
                                                    <div class="col-lg-3">
                                                        <select id="iva" name="iva" class="form-control select2 input-sm" >
                                                            <option value="">Seleccione</option>

                                                            <?php foreach ($list_iva as $iva) { ?>
                                                                <option value="<?php echo $iva->id ?>"><?php echo $iva->valor ?>%</option>
                                                            <?php } ?>

                                                        </select>
                                                    </div>
<!--
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Tiempo</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" value="<?php echo $editar[0]->tiempo_utilidad ?>"  placeholder="Tiempo de Utilidad" id="tiempo_utilidad" maxlength="20" name="tiempo_utilidad" class="form-control">

                                                    </div>
-->
                                                    <br/>
                                                    <br/>
                                                    <br/>  
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Existencia</label>
                                                    <div class="col-lg-3">
														<input type="text" value="<?php echo $editar[0]->existencia ?>" placeholder="Existencia" id="existencia" name="existencia" class="form-control" readonly="true">
                                                    </div>
                                                    
                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Monto IVA</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" value="<?php echo $editar[0]->monto_iva ?>" placeholder="Monto del iva" id="monto_iva" name="monto_iva" class="form-control" readonly="true">
                                                    </div>


                                                    <label class="col-lg-1 control-label" style="font-weight:bold" >Precio total</label>
                                                    <div class="col-lg-3">
                                                        <input type="text" value="<?php echo $editar[0]->precio_total ?>" placeholder="Monto total" id="precio_total" name="precio_total" class="form-control" readonly="true">

                                                    </div>
                                                    <br/>
                                                    <br/>
                                                    <br/>  
                                                </div>

                                            </div>


                                            <div class="form-group">
                                                <div class="col-lg-12">
													<input class="form-control" type='hidden' id="id_ganancia" value="<?php echo $editar[0]->ganancia ?>"/>
                                                    <input class="form-control" type='hidden' placeholder="user" id="id" name="id" value="<?php echo $editar[0]->id ?>"/>
                                                    <button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning" >
                                                        &nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
                                                    </button>

                                                    <button type="submit" id="registrar" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                                    &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Guardar
                                                    </button>
                                                </div>
                                            </div>
                                            <input id="id_tipoproducto" type="hidden" value="<?php echo $editar[0]->tipoproducto ?>"/>
                                            <input id="id_proveedor" type="hidden" value="<?php echo $editar[0]->proveedor ?>"/>
                                            <input id="id_unidad_medida" type="hidden" value="<?php echo $editar[0]->unidad_medida ?>"/>
                                            <input id="id_iva" type="hidden" value="<?php echo $editar[0]->iva ?>"/>

                                    </fieldset>
                                </div>
                            </form>

                            <div class="btn btn-primary btn-xs" id="source-button" style="display: none;">&lt; &gt;</div></div>
                    </div>

                </div>
            </div>

        </div>
    </div>
