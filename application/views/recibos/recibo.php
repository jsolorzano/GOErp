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
<script type="text/javascript">


$(document).ready(function () {
        
	var Tpro = $('#tab_recibo').dataTable({
		"iDisplayLength": 5,
		"iDisplayStart": 0,
		"sPaginationType": "full_numbers",
		"aLengthMenu": [5, 10],
		"oLanguage": {"sUrl": "<?= base_url() ?>static/js/es2.txt"},
		"aoColumns": [
			{"sClass": "registro center", "sWidth": "8%"},
			//~ {"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "registro center", "sWidth": "15%"},
			{"sClass": "registro center", "sWidth": "15%"},
			{"sClass": "registro center", "sWidth": "15%"},
			{"sClass": "registro center", "sWidth": "15%"},
			{"sClass": "registro center", "sWidth": "15%"},
		]
	});

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
	
	// Validaciones de tipos de datos
	$('#empleado').alpha({allow: " "});
	$("#num_lunes").numeric({allow: "."});
	$('#concepto').alpha({allow: " "});
	$('#monto').numeric({allow: "."});
	$('#cantidad').numeric();

	$("select").select2();
	
	$('#volver').click(function () {
		url = '<?php echo base_url() ?>index.php/recibos/ControllersRecibo/'
		window.location = url
	})
	
	// Activamos la modal de conceptos
	$("#i_new_line").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		$("#modal_conceptos").modal('show');
	});
	
	// Activamos la modal de retenciones
	$("#calc_ret").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		$("#modal_retenciones").modal('show');
	});

	$("#agregar").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		//~ alert($('#id_servicio').val());
		if ($('#tipo').val() == '0' || $('#tipo').val() == null) {
			bootbox.alert("Seleccione el Tipo", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#tipo").parent('div').addClass('has-error')
				$('#tipo').select2('open');
				$("#tipo").focus();
			});
		} else if ($('#concepto').val().trim() == "") {
			bootbox.alert("Introdúzca el concepto", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#concepto").parent('div').addClass('has-error')
				$('#concepto').val('');
				$("#concepto").focus();
			});
		} else if ($('#monto').val().trim() == '' || $('#monto').val().trim() == 0) {
			bootbox.alert("Introdúzca el monto", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#monto").parent('div').addClass('has-error')
				$('#monto').val('')
				$("#monto").focus();
			});
		} else if ($('#cantidad').val().trim() == '' || $('#cantidad').val().trim() == 0) {
			bootbox.alert("Introdúzca la Cantidad", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#cantidad").parent('div').addClass('has-error')
				$('#cantidad').val('')
				$("#cantidad").focus();
			});

		} else {
			
			var tipo = $("#tipo").val();
			if(tipo == '1'){
				tipo = "Abono";
			}else{
				tipo = "Descuento";
			}
			var concepto = $("#concepto").val();
			var monto = parseFloat($("#monto").val());
			var cantidad = parseFloat($("#cantidad").val());
			var importe = parseFloat(cantidad) * parseFloat(monto);
	  
			if( tipo!='0' & concepto!='' & monto!='' & cantidad!='' ){
				var detalle = new Array();
				var obj = 	{
				'tipo':tipo,
				'concepto':concepto,
				'monto': monto.toFixed(2),
				'cantidad':cantidad.toFixed(2),
				'importe': importe
				};
				console.log(obj);
													
				var aPos = Tpro.fnGetPosition(this.parentNode.parentNode);
				
				var botonQuitar = "<a class='quitar'><i class='glyphicon glyphicon-trash'></i></a>";
							
				var newRow = Tpro.fnAddData([tipo,concepto,monto,cantidad,importe,botonQuitar]);
				
				// Ejecución de los cálculos de la recibo
				calculos(total_abonos(), total_descuentos());  // Cálculo de los abonos, descuentos y total del recibo

			}else{
				console.log("No se admite campos vacios");
			}

		   $("#modal_conceptos").modal('hide');
		   //~ $('#tipo').select2('val', '0');
		   $('#concepto').select2('val', '0');
		   $('#monto').val('');
		   $('#cantidad').val('');
	   }

	});
	
	// Función para quitar un elemento de la lista y recalcular los montos de la recibo
	$("table#tab_recibo").on('click', 'a.quitar', function (e) {
		//~ alert("alert");
		var aPos = Tpro.fnGetPosition(this.parentNode.parentNode);
		Tpro.fnDeleteRow(aPos);
		
		// Ejecución de los cálculos de la recibo
		calculos(total_abonos(), total_descuentos());  // Cálculo de los abonos, descuentos y total del recibo
	} );


	$("#facturar").click(function (e) {
		
		if ($('#empleado').val().trim() == '' || $('#empleado').val().trim() == 'Seleccione') {
			bootbox.alert("Seleccione o indique un empleado", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#empleado").parent('div').addClass('has-error')
				$('#empleado').val('')
				$("#empleado").focus();
			});
		}
		else if ($('#ret_faov').val().trim() == '' || $('#ret_faov').val().trim() == 0 || $('#ret_sso').val().trim() == '' || $('#ret_sso').val().trim() == 0) {
			bootbox.alert("Aún no se ha calculado las retenciones", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#calc_ret").parent('div').addClass('has-error')
				//~ $('#iva').val('');
				$("#calc_ret").focus();
			});
		}
		else if ($('#subtotal').val().trim() == '' || $("#totalrecibo").val().trim() == '' || $('#subtotal').val().trim() == 0 || $("#totalrecibo").val().trim() == 0) {
			bootbox.alert("Complete el recibo", function () {
			}).on('hidden.bs.modal', function (event) {
				
			});
		}else{		

			// Armar data de los abonos/descuentos
			var campos= "";
			var data = [];
			$("#tab_recibo tbody tr").each(function (index){
			var campo1, campo2, campo3, campo4, campo5;
			var campo6;
			campo6 = String($("#codrecibo").val().trim());
			
				$(this).children("td").each(function (index2) 
				{
					switch (index2) 
					{
						case 0: 
							campo1 = $(this).text();
							break;
						case 1: 
							campo2 = $(this).text();
							break;
						case 2:
							campo3 = $(this).text();
							break;
						case 3:
							campo4 = $(this).text();
							break;
						case 4:
							campo5 = $(this).text();
							break;
					}
					$(this).css("background-color", "#ECF8E0");
				})               

				campos = {"tipo" : campo1, "concepto" : campo2, "monto" : campo3, "cantidad" : campo4, "importe" : campo5, "cod_recibo" : campo6},
				data.push(campos);                
				
			})
			console.log(data);
			//~ $.post('<?php echo base_url(); ?>index.php/recibo/ControllersRecibo/guardar/',{data:data}, function (data) {
				//~ if (data.success) {
					//~ alert('Registro con exito');
					//~ location.reload();
				//~ } else {
					//~ alert('Ocurrio un error');
				//~ }
			//~ }, 'json')
		
			$.post('<?php echo base_url(); ?>index.php/recibos/ControllersRecibo/guardar/', $('#form_recibo').serialize()+'&'+$.param({'data':data}), function (response) {
				//~ alert($('#form_recibo').serialize());

				bootbox.alert("Se registró con exito", function () {
				}).on('hidden.bs.modal', function (event) {
					url = '<?php echo base_url(); ?>index.php/recibos/ControllersRecibo'
					window.location = url
				});

			});
		}
	});
	
	 $("#modal_empleado").modal('show');
	 
	 // Cargamos los datos del empleado incluyendo sus datos salariales
	 $("#hola").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto

		var empleado = $("#id_empleado").find('option').filter(':selected').text();
		$("#codempleado").val($('#id_empleado').val());
		$("#empleado").val(empleado);
		
		// Función para consultar los datos salariales del empleado
		//~ alert($("#codempleado").val());
		$.post('<?php echo base_url(); ?>index.php/recibos/ControllersRecibo/consultar_salario/'+$("#codempleado").val(), function (response) {
			var respuesta = response.split('<html>');
			//~ respuesta = $.parseJSON(respuesta[0]);
			respuesta = respuesta[0].split(';');
			var ret_faov = 0;
			var ret_sso = 0;
			var retenciones = 0;
			var totalrecibo = 0;
			
			// Área de datos salariales
			$("#salario").val(respuesta[0]);
			$("#salario_base").val(respuesta[1]);
			$("#escala").val(respuesta[2]);
			$("#monto_salario").val(respuesta[3]);
			
			//Área de totales
			$("#span_abonos").text('0');
			$("#abonos").val(0);
			$("#span_desc").text('0');
			$("#descuento").val(0);
			$("#span_ret_faov").text('0');
			$("#ret_faov").val(0);
			$("#span_ret_sso").text('0');
			$("#ret_sso").val(0);
			$("#span_sub_total").text(respuesta[3]);
			$("#subtotal").val(respuesta[3]);
			
			var abonos = parseFloat($("#abonos").val());
			var descuento = parseFloat($("#descuento").val());
			var retenciones = parseFloat($("#ret_faov").val()) + parseFloat($("#ret_sso").val());
			var totalrecibo = parseFloat($("#subtotal").val())+abonos-(retenciones+descuento);
			
			$("#span_total").text(totalrecibo);
			$("#totalrecibo").val(totalrecibo);
		});
			
		$("#modal_empleado").modal('hide');
	 });
	 
	 // Calculamos las retenciones y el resto de los montos
	 $("#calcular_ret").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		
		if($("#num_lunes").val() != '' && $("#num_lunes").val() != 0){
			var ret_faov = 0;
			var ret_sso = 0;
			var faov_patrono = 0;
			var sso_patrono = 0;
			var cant_meses = 12;
			var cant_semanas = 52;
			var porcentaje_retfaov = 1; // 1%
			var porcentaje_retsso = 4; // 4%
			var porcentaje_faovpatrono = 2; // 2%
			var porcentaje_ssopatrono = 11; // 11%
			
			//Área de cálculos
			sueldo_anual = parseFloat($("#monto_salario").val()) * cant_meses;
			sueldo_semanal = sueldo_anual/cant_semanas;
			// Retenciones al trabajador (mensual)
			ret_semanal_faov = sueldo_semanal * porcentaje_retfaov / 100;
			ret_faov = ret_semanal_faov * parseFloat($("#num_lunes").val());
			ret_semanal_sso = sueldo_semanal * porcentaje_retsso / 100;
			ret_sso = ret_semanal_sso * parseFloat($("#num_lunes").val());
			// Aportes del patrono (mensual)
			faov_patrono_semanal = sueldo_semanal * porcentaje_faovpatrono / 100;
			faov_patrono = faov_patrono_semanal * parseFloat($("#num_lunes").val());
			sso_patrono_semanal = sueldo_semanal * porcentaje_ssopatrono / 100;
			sso_patrono = sso_patrono_semanal * parseFloat($("#num_lunes").val());
			// Asignamos los valores a los campos y etiquetas
			$("#span_ret_faov").text(ret_faov.toFixed(2));
			$("#ret_faov").val(ret_faov.toFixed(2));
			$("#faov_patrono").val(faov_patrono.toFixed(2));
			$("#span_ret_sso").text(ret_sso.toFixed(2));
			$("#ret_sso").val(ret_sso.toFixed(2));
			$("#sso_patrono").val(sso_patrono.toFixed(2));
			
			// Nuevo calculo del total del recibo
			var abonos = parseFloat($("#abonos").val());
			var descuento = parseFloat($("#descuento").val());
			var retenciones = parseFloat($("#ret_faov").val()) + parseFloat($("#ret_sso").val());
			var totalrecibo = parseFloat($("#subtotal").val())+abonos-(retenciones+descuento);
			
			//~ alert(retenciones); 
			//~ alert(totalrecibo);
			$("#span_total").text((totalrecibo).toFixed(2));
			$("#totalrecibo").val((totalrecibo).toFixed(2));
			
			$("#modal_retenciones").modal('hide');
		}else{
			bootbox.alert("Seleccione el número de lunes del mes", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#num_lunes").parent('div').addClass('has-error')
				$('#num_lunes').val('')
				$("#num_lunes").focus();
			});
		}
			
	 });

});

	// FUNCIONES EXTRA
	function hora() {
		var fecha = new Date()
		var hora = fecha.getHours()
		var minuto = fecha.getMinutes()
		var segundo = fecha.getSeconds()
		if (hora < 10) {
			hora = "0" + hora
		}
		if (minuto < 10) {
			minuto = "0" + minuto
		}
		if (segundo < 10) {
			segundo = "0" + segundo
		}
		var horita = hora + ":" + minuto + ":" + segundo
		document.getElementById('hora').firstChild.nodeValue = horita
		tiempo = setTimeout('hora()', 1000)
	}
            
        
    function inicio() {
        document.write('<span id="hora">')
        document.write('000000</span>')
        hora()
    }

    function date() {
        document.write('<span id="fecha">')
        document.write('000000</span>')
        horita()
    }
    
	
	// Cálculo de los abonos
    function total_abonos(){
		
		var abonos = 0;
		var tipo = 'Abono';
		var sub_monto = 0;
		
		$("#tab_recibo tbody tr").each(function (index){
		
			$(this).children("td").each(function (index2) 
			{
				switch (index2) 
				{
					// Leer el campo de tipo
					case 0:
						tipo = $(this).text();
						break;
					// Leer el campo de importe
					case 4:
						sub_monto = parseFloat($(this).text());
						break;
				}
			})
			
			if(tipo == 'Abono'){
				abonos = abonos + sub_monto;
			}else{
				abonos = abonos + 0;
			}
			
		})
		
		//~ alert("Abonos: "+abonos);
		
		return abonos;
	}
	
	// Cálculo del descuento
    function total_descuentos(){
		
		var descuentos = 0;
		var tipo = 'Abono';
		var sub_monto = 0;
		
		$("#tab_recibo tbody tr").each(function (index){
		
			$(this).children("td").each(function (index2) 
			{
				switch (index2) 
				{
					// Leer el campo de tipo
					case 0:
						tipo = $(this).text();
						break;
					// Leer el campo de importe
					case 4:
						sub_monto = parseFloat($(this).text());
						break;
				}
			})
			
			if(tipo == 'Descuento'){
				descuentos = descuentos + sub_monto;
			}else{
				descuentos = descuentos + 0;
			}
			
		})
		
		//~ alert("Descuentos: "+descuentos);
		
		return descuentos;
	}
    
    // Función para la realización de los cálculos de Abonos, Descuentos y Total del recibo
    function calculos(abonos,descuentos) {
        var ab = abonos;
        var desc = descuentos;
        
        $("#abonos").val(ab);  // Cargamos los abonos en el campo oculto para guardarlo en base de datos
        $("#span_abonos").text(ab);  // Cargamos los abonos en la página sólo para visualización
        
        $("#descuento").val(desc);  // Cargamos los descuentos en el campo oculto para guardarlo en base de datos
        $("#span_desc").text(desc);  // Cargamos los descuentos en la página sólo para visualización
        
        // Cálculo del Total        
        var abonos = parseFloat($("#abonos").val());
		var descuento = parseFloat($("#descuento").val());
		var retenciones = parseFloat($("#ret_faov").val()) + parseFloat($("#ret_sso").val());
		var totalrecibo = parseFloat($("#subtotal").val())+abonos-(retenciones+descuento);
		
		$("#span_total").text(totalrecibo);
		$("#totalrecibo").val(totalrecibo);
    }
    
</script>
<div class="modal" id="modal_empleado">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
               <span class="glyphicon glyphicon-search"></span>
               &nbsp;  Busca y selecciona el empleado 
            </h4>
         </div>
         <div class="modal-body">
            <form name="f_nuevo_recibo" action="" method="post" class="form">
               <input type="hidden" name="empleado"/>
               <div class="form-group">
                  <div class="input-group">
                    <select id="id_empleado" name="empleado" class="form-control select2 input-sm" >
						<!--<option value="">Seleccione</option>-->

						<?php foreach ($listar as $empleado) { ?>
							<option value="<?php echo $empleado->codigo?>"><?php echo $empleado->nombre?></option>
						<?php }?>

					</select>
         <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="hola">
                           <span class="glyphicon glyphicon-share-alt"></span>
                        </button>
                     </span>
                  </div>
               </div>
            </form>
         </div>
         
      </div>
   </div>
</div>


<div class="modal" id="modal_conceptos">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <span class="glyphicon glyphicon-search"></span>
                    &nbsp;  Nuevo Abono/Descuento
                </h4>
            </div>
            <div class="modal-body">
                <form name="f_nuevo_recibo" action="" method="post" class="form">
                    <p>
                        <label>Tipo:&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                        <select id="tipo" name="tipo" class="form-control" >
                            <option value="0" selected="">Seleccione</option>
                            <option value="1">Abono</option>
                            <option value="2">Descuento</option>
                        </select>
                    </p>
                    <p>
                        <label>Concepto:</label><br clear="all"> <input type="text" style="width:100%" class="form-control" id="concepto" name="concepto">
                    </p>
                    <p>
                        <label>Monto:</label><br clear="all"> <input type="text" style="width:100%" class="form-control" id="monto" name="monto">
                    </p>
                    <p>
                        <label>Cantidad:</label><br clear="all"> <input type="text" style="width:100%" class="form-control" id="cantidad" name="cantidad">
                    </p>

                    <div class="input-group">

                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" id="agregar">
                                Aceptar
                            </button>
                        </span>
                    </div>
                   </form>
            </div>
        </div>

    </div>
</div>

<div class="modal" id="modal_retenciones">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
               <span class="glyphicon glyphicon-search"></span>
               &nbsp;  Seleccione la cantidad de lunes del mes 
            </h4>
         </div>
         <div class="modal-body">
            <form name="f_nuevo_recibo" action="" method="post" class="form">
               <div class="form-group">
                    <select id="num_lunes" name="num_lunes" class="form-control">
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
                    </br></br>
					<button class="btn btn-primary" type="button" id="calcular_ret">
					   <span>Calcular</span>
					</button>
               </div>
            </form>
         </div>
         
      </div>
   </div>
</div>

<div class="container" style="width:90%;">
    <div class="row">
        <div class="col-lg-12">
            <div class="well bs-component">
                <form id="form_recibo" class="form" method="post">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <legend>Recibo de Pago (
                                <?php
									// Construcción del código de la recibo para mostrar en el encabezado (Todo lo comentado es la versión anterior)
									//~ $pre_cod = "00";
									if((int)$ultimo_id + (int)1 == 1){
										//~ printf("$pre_cod %08d", (int)$ultimo_id + (int)1);
										printf("%08d", (int)$ultimo_id + (int)1);
									}else{
										//~ $c_recibo = (int)substr($cod_recibo->codrecibo, 2);
										$c_recibo = (int)$cod_recibo->codrecibo;
										//~ $pre_cod = $cod_recibo->pre_cod_factura;
										
										if ($c_recibo + (int)1 > 99999999){
											//~ printf("%02d %08d",($pre_cod+1), 1);
											printf("%08d", 1);
										}else{
											printf("%08d", $c_recibo + (int)1);
										}
									}
								?>
                                )</legend>
                                <input type="hidden" id="codrecibo" name="codrecibo" value="
                                <?php
									// Construcción del código de la recibo para mostrar en el campo de cod_recibo (Todo lo comentado es la versión anterior)
									//~ $pre_cod = "00";
									if((int)$ultimo_id + (int)1 == 1){
										//~ printf(trim("$pre_cod%08d"), (int)$ultimo_id + (int)1);
										printf(trim("%08d"), (int)$ultimo_id + (int)1);
									}else{
										//~ $cod_recibo = (int)$cod_recibo->codrecibo;
										if ($c_recibo + (int)1 > 99999999){
											//~ $pre_cod = (int)$cod_recibo->pre_cod_factura + (int)1;
											//~ printf("%02d%08d",$pre_cod, 1);
											printf("%08d", 1);
										}else{
											//~ $pre_cod = (int)$cod_recibo->pre_cod_factura;
											//~ printf("%02d%08d",$pre_cod, $c_recibo + (int)1);
											printf("%08d", $c_recibo + (int)1);
										}
									}
								?>
                                ">
                                <input type="hidden" id="pre_cod_factura" name="pre_cod_factura" value="
                                <?php
									// Construcción del pre-código de la recibo para mostrar en el campo de pre_cod_factura (Todo lo comentado es la versión anterior)
									//~ $pre_cod = "00";
									//~ if((int)$ultimo_id + (int)1 == 1){
										//~ print trim($pre_cod);
									//~ }else{
										//~ if ((int)$c_recibo + (int)1 > 99999999){
											//~ $pre_cod = (int)$cod_recibo->pre_cod_factura + (int)1;
											//~ printf("%02d", $pre_cod);
										//~ }else{
											//~ $pre_cod = (int)$cod_recibo->pre_cod_factura;
											//~ printf("%02d", $pre_cod);
										//~ }
									//~ }
								?>
                                ">
                                
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-4">
								<div style="margin-left: 0.2%;margin-bottom: -1%;" class="form-group">
									<span >Código y nombre del empleado</span>
									<div class="input-group mar-btm">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" style="width: 27%;" readonly="true" name="codempleado" id="codempleado" class="form-control">
										<input type="text" style="width: 73%;" name="empleado" id="empleado" placeholder="empleado" class="form-control" readonly="true">
										<span class="input-group-btn">
											<button type="button" data-toggle="modal" data-target="#modal_empleado" class="btn btn-primary" type="submit" id="modal_empleado">
												<i class="glyphicon glyphicon-search"></i>
											</button>
										</span>
									</div>
								</div>
                            </div>
                            <div class="col-sm-2">
								<span >Salario</span>
								<div class="input-group mar-btm">
									<input type="text" data-original-title="Salario" data-toggle="tooltip" data-placement="bottom" readonly="true" name="salario" id="salario" placeholder="Salario" class="form-control add-tooltip" readonly="true">
								</div>
                            </div>
                            <div class="col-sm-2">
								<span id="span_salario">Monto Salario</span>
								<div class="input-group mar-btm">
									<input type="text" style="width:100%;" data-toggle="tooltip" data-placement="bottom" id="salario_base" placeholder="0.00" class="form-control add-tooltip" readonly="true">
								</div>
                            </div>
							<div class="col-sm-2">
								<span id="span_salario">Escala</span>
								<div class="input-group mar-btm">
									<input type="text" style="width:100%;" data-toggle="tooltip" data-placement="bottom" id="escala" placeholder="0.00" class="form-control add-tooltip" readonly="true">
								</div>
                            </div>
                            <div class="col-sm-2">
								<span id="span_salario">Total Salario</span>
								<div class="input-group mar-btm">
									<input type="text" style="width:100%;" data-original-title="Descuento" data-toggle="tooltip" data-placement="bottom" name="monto_salario" id="monto_salario" placeholder="0.00" class="form-control add-tooltip" readonly="true">
								</div>
                            </div>
                            </br>
                            <div class="col-sm-3">
								<span >Condición de pago</span>
								<div class="input-group mar-btm">
									<select id="condicion_pago" name="condicion_pago" style="width:100%;" class="form-control">
										<option value="1">Cheque</option>
<!--
										<option value="2">Debito</option>
-->
										<option value="3">Efectivo</option>
										<option value="4">Transferencia</option>
										<option value="5">Deposito</option>
									</select>
								</div>
                            </div>
                            
                        </div>
                        
                    </div>
                    <br/>
                    
                    <div role="tabpanel">
                        <ul class="nav nav-tabs" role="tablist">
                        </ul>
                        <br/>
                        <div class="tab-content">
                            <div class="row"></div>
                            <div class="form-group col-xs-3">
                                <button class="btn btn-primary btn-labeled" id="i_new_line"><i class="glyphicon glyphicon-plus"></i>&nbsp;Agregar Abono / Descuento</button>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="form-group col-xs-3">
                                <button class="btn btn-primary btn-labeled" id="calc_ret"><i class=""></i>&nbsp;Calcular Retenciones</button>
                            </div>
                            <br/>
                            <br/>
                            <br/>

                            <input type="hidden" id="cant_row" value="1"/>
                            
                             <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_recibo" align="center"
								   class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
								<thead style="font-size: 14px">
									<tr class="info">
										<th style='text-align: center'>Tipo</th>
										<!--<th style='text-align: center'>Tipo</th>-->
										<th style='text-align: center'>Concepto</th>
										<th style='text-align: center'>Monto</th>
										<th style='text-align: center'>Cantidad</th>
										<th style='text-align: center'>Importe/Desc</th>
										<th style='text-align: center'>Quitar</th>
									</tr>
								</thead>
								<tbody >    
								  
								</tbody>
							</table>

                        </div>
                    </div>
                    </br>
                    
                    <div class="row" style="text-align: center">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-3">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                ABONOS: <span id="span_abonos"></span>
                            </h4>   
                        </div>
                        <div class="col-sm-3">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                DESC: <span id="span_desc"></span>
                            </h4>   
                        </div>
                    </div>
                    
                    <div class="row" style="text-align: center">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-3">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                RET FAOV: <span id="span_ret_faov"></span>
                            </h4>   
                        </div>
                        <div class="col-sm-3">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                RET SSO: <span id="span_ret_sso"></span>
                            </h4>   
                        </div>
                    </div>
                    
                    <div class="row" style="text-align: center">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-3">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                SUB-TOTAL: <span id="span_sub_total"></span>
                            </h4>   
                        </div>
                        <div class="col-sm-3">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                TOTAL: <span id="span_total"></span>
                            </h4>   
                        </div>
                    </div>

                    <div class="container-fluid" style="margin-top: 10px;">
                        <div class="row">
							<input type="hidden" id="abonos" name="abonos" placeholder="Abonos">
							<input type="hidden" id="descuento" name="descuento" placeholder="Descuento">
							<input type="hidden" id="ret_faov" name="ret_faov" placeholder="Retención FAOV">
							<input type="hidden" id="ret_sso" name="ret_sso" placeholder="Retención SSO">
							<input type="hidden" id="faov_patrono" name="faov_patrono" placeholder="Aporte FAOV">
							<input type="hidden" id="sso_patrono" name="sso_patrono" placeholder="Aporte SSO">
							<input type="hidden" id="subtotal" name="subtotal" placeholder="Sub-Total recibo">
							<input type="hidden" id="totalrecibo" name="totalrecibo" placeholder="Total recibo">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    Observaciones:
                                    <textarea class="form-control" name="observaciones" rows="3"></textarea>
                                </div>
                            </div>
                            <br/>
                            <div class="col-sm-12 text-center">
								<button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning" >
								&nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
								</button>
                                <button type="button" id="facturar" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Guardar
                                </button>

                            </div>

                        </div>
                    </div>
				
				</form>
       
            </div>
        </div>
    </div>
</div>
</body>
</html>
