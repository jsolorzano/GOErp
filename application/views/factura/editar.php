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
        
	var Tpro = $('#tab_factura').dataTable({
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
	$('#cliente').alpha({allow: " "});
	$('#descuento').numeric({allow: "."});
	$('#cantidad').numeric();

	$("select").select2();
	
	//~ $('#fecha_vencimiento').datepicker({
		//~ format: "dd/mm/yyyy",
		//~ startDate: 'today',
		//~ minDate: "-1D",
		//~ maxDate: "-1D",
		//~ language: "es",
		//~ autoclose: true,
	//~ })
	
	// Carga de datos de los campos de selección
	$("#iva").select2('val', $("#id_iva").val());
	
	// Armamos la lista de valores para el campo de condicón de pago
	var condiciones = $("#condicion_pago").val()
	condiciones = condiciones.split(',');  // Dividimos la lista de ids por cada coma
	conds = new Array();  // Preparamos un nuevo arreglo 
	$.each(condiciones, function (i) {
		//~ alert(condiciones[i]);
		conds.push(condiciones[i]);  // Rellenamos el arreglo
	});
	$("#condiciones").select2('val', conds);  // Asignamos las condiciones que vienen de base de datos al select múltiple
	
	$('#volver').click(function () {
		url = '<?php echo base_url() ?>index.php/factura/ControllersFacturar/'
		window.location = url
	})


	////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Al cargar la página, mostrar en la lista de productos aquellos con existencia en su stock.
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//~ $('#tipo').change(function () {

		//~ if ($('#tipo').val() == '1') {
			$('#id_servicio').find('option:gt(0)').remove().end().select2('val', '0');
			$.get('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/productos_existencia/', function (data) {
				var option = "";
				$.each(data, function (i) {
					option += "<option value=" + data[i]['codigo'] + ">" + data[i]['nombre'] + "</option>";
				});
				$('#id_servicio').append(option);

			}, 'json');

		//~ } else {
			//~ $('#id_servicio').find('option:gt(0)').remove().end().select2('val', '0');
			//~ $.get('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/servicios/', function (data) {
				//~ var option = "";
				//~ $.each(data, function (i) {
					//~ option += "<option value=" + data[i]['codigo'] + ">" + data[i]['descripcion'] + "-" + data[i]['tipo_servicio'] + "</option>";
				//~ });
				//~ $('#id_servicio').append(option);
//~ 
			//~ }, 'json');
//~ 
		//~ }
	//~ });


	$('#id_servicio').change(function () {

		//~ if ($('#tipo').val() == '1') {
			$.post('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/datos_ps/', {'tabla':'producto', 'campo':'codigo', 'valor':$("#id_servicio").val()}, function (data) {
				//~ console.log(data)
				//~ alert(data);
				//  Esta forma es para varios registros
				//~ $.each(data, function (i) {
					//~ $('#precio').val(data[i]['precio_unitario']);
					//~ alert(data[i]['precio_unitario']);
				//~ });
				
				// Verificamos si la disponibilidad del producto es menor al mínimo establecido para alertar al usuario
				if (parseInt(data['existencia']) < parseInt(data['stock_min'])){
					bootbox.alert("La disponibilidad del producto es menor al mínimo estipulado", function () {
					}).on('hidden.bs.modal', function (event) {
					});
				}
				
				// Cargamos los datos necesarios del producto
				$('#precio').val(data['precio_total']);  // Para un registro
				$('#iva').val(data['monto_iva']);  // Para un registro
				$('#exist').val(data['existencia']);  // Para un registro
				$('#span_exist').text("Disp. "+data['existencia']);  // Para un registro
				$('#min').val(data['stock_min']);  // Para un registro

			}, 'json');

		//~ } else {
			//~ $('#precio').find('option:gt(0)').remove();
			//~ $.post('<?php echo base_url(); ?>index.php/busquedas_ajax/ControllersBusqueda/datos_ps/', {'tabla':'servicio', 'campo':'codigo', 'valor':$("#id_servicio").val()}, function (data) {
				//~ var option = "";
				//~ console.log(data)
				//~ alert(data);
				//~ //  Esta forma es para varios registros
				//~ $.each(data, function (i) {
					//~ $('#precio').val(data[i]['precio_unitario']);
					//~ alert(data[i]['precio_unitario']);
				//~ });
				//~ $('#precio').val(data['precio_unitario']);  // Para un registro
				//~ $('#iva').val(data['monto_iva']);  // Para un registro
				//~ $('#exist').val('');  // Para un registro
				//~ $('#span_exist').text('');  // Para un registro
				//~ $('#min').val('');  // Para un registro
//~ 
			//~ }, 'json');
//~ 
		//~ }

	});


	$("#i_new_line").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		$("#modal_servicios").modal('show');
	});

	$("#agregar").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
//            alert($('#id_servicio').val());
		/*if ($('#tipo').val() == '0' || $('#tipo').val() == null) {

			bootbox.alert("Seleccione el Tipo", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#tipo").parent('div').addClass('has-error')
				$('#tipo').select2('val', '0');
				$("#tipo").focus();
			});
		}*/ 
		if ($('#id_servicio').val() == null || $('#id_servicio').val() == 0) {
			bootbox.alert("Seleccione el Producto", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#id_servicio").parent('div').addClass('has-error')
				  $('#id_servicio').select2('val', '0');
				$("#id_servicio").focus();
			});
		} else if ($('#precio').val().trim() == '' || $('#precio').val().trim() == 0) {
			bootbox.alert("Introduzca el Precio", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#precio").parent('div').addClass('has-error')
				$('#precio').val('')
				$("#precio").focus();
			});
		} else if ($('#cantidad').val().trim() == '' || $('#cantidad').val().trim() == 0) {
			bootbox.alert("Introduzca la Cantidad", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#cantidad").parent('div').addClass('has-error')
				$('#cantidad').val('')
				$("#cantidad").focus();
			});

		} else if (parseInt($('#cantidad').val().trim()) > parseInt($('#exist').val())) {
			bootbox.alert("Está excediendo la disponibilidad del producto", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#cantidad").parent('div').addClass('has-error')
				$('#cantidad').val('')
				$("#cantidad").focus();
			});

		} else {
			
			var id = $("#id_servicio").val();
			//~ var nombre = $("#tipo").val();
			//~ var tipo = $("#tipo").val();
			//~ if (tipo == '2'){
				//~ tipo = "Servicio";
			//~ }else{
				//~ tipo = "Producto";
			//~ }
			var tipo = "Producto";
			var producto = $("#id_servicio").find('option').filter(':selected').text();
			var precioventa = parseFloat($("#precio").val());
			var iva = parseFloat($("#iva").val());
			var cantidad = parseFloat($("#cantidad").val());
			var importe = parseFloat(cantidad) * parseFloat(precioventa);
	  
			if( id!='' & precioventa!='' & producto!='' & cantidad!='' ){
				var detalle = new Array();
				var obj = 	{
				'id':id,
				'tipo':tipo,
				'producto':producto,
				'preciounidad': precioventa.toFixed(2),
				'cantidad':cantidad.toFixed(2),
				'importe': importe
				};
				console.log(obj);
													
				var aPos = Tpro.fnGetPosition(this.parentNode.parentNode);
				
				var botonQuitar = "<a class='quitar'><i class='glyphicon glyphicon-trash'></i></a>";
							
				var newRow = Tpro.fnAddData([id,producto,precioventa,iva,cantidad,importe,botonQuitar]);
				
				// Ejecución de los cálculos de la factura
				calculos(base_imponible(), base_exenta(), iva_total());  // Cálculo del descuento, IVA y Total

			}else{
				console.log("No se admite campos vacios");
			}

		   $("#modal_servicios").modal('hide');
		   //~ $('#tipo').select2('val', '0');
		   $('#id_servicio').select2('val', '0');
		   $('#precio').val('');
		   $('#cantidad').val('');
	   }


	});
	
	
	// Función para quitar un registro de la tabla de Productos y recalcular los montos de la factura
	$("table#tab_factura").on('click', 'a.quitar', function (e) {
		//~ alert("alert");
		var cod_reg = '';
		
		// 
		if ($(this).attr('id') != undefined) {
			
			cod_reg = $(this).attr('id');
			
			if($("#codigos_des").val() == ''){
				$("#codigos_des").val(cod_reg);
			}else{
				$("#codigos_des").val($("#codigos_des").val()+','+cod_reg);
			}
			
		}
		
		//~ alert("Código del registro: "+cod_reg);
		//~ 
		//~ alert($("#codigos_des").val());
		
		var aPos = Tpro.fnGetPosition(this.parentNode.parentNode);
		Tpro.fnDeleteRow(aPos);
		
		// Ejecución de los cálculos de la factura
		calculos(base_imponible(), base_exenta(), iva_total());  // Cálculo del descuento, IVA y Total
	} );
	

	$("#facturar").click(function (e) {
		
		if ($('#cliente').val().trim() == '' || $('#cliente').val().trim() == 'Seleccione') {
			bootbox.alert("Seleccione o indique un cliente", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#cliente").parent('div').addClass('has-error')
				$('#cliente').val('')
				$("#cliente").focus();
			});
		}/*else if ($('#fecha_vencimiento').val().trim() == '') {
			bootbox.alert("Indique la fecha de vencimiento", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#fecha_vencimiento").parent('div').addClass('has-error')
				$('#fecha_vencimiento').val('')
				$("#fecha_vencimiento").focus();
			});
		}else if ($('#iva').val().trim() == '') {
			bootbox.alert("Seleccione el IVA", function () {
			}).on('hidden.bs.modal', function (event) {
				$("#iva").parent('div').addClass('has-error')
				//~ $('#iva').val('');
				$("#iva").focus();
			});
		}*/
		else if ($('#subtotal').val().trim() == '' || $("#totalfactura").val().trim() == '' || $('#subtotal').val().trim() == 0 || $("#totalfactura").val().trim() == 0) {
			bootbox.alert("Complete la carga de productos", function () {
			}).on('hidden.bs.modal', function (event) {
				
			});
		}else{		

		// Armar data de los productos/servicios
		var campos= "";
		var data = [];
		$("#tab_factura tbody tr").each(function (index){
		var campo0, campo1, campo2, campo3, campo4, campo5, campo6;
		var campo7;
		campo7 = String($("#codfactura").val().trim());
		
			$(this).children("td").each(function (index2) 
			{
				switch (index2) 
				{
					case 0:
						campo0 = $(this).attr('id');  // Código correlativo del registro en la tabla facturas_ps
						campo1 = $(this).text();  // Código de correlativo del producto/servicio
						break;
					case 1: 
						campo2 = $(this).text();  // Nombre del producto/servicio
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
					case 5:
						campo6 = $(this).text();
						break;
				}
				$(this).css("background-color", "#ECF8E0");
			})

			campos = {"cod_f_ps":campo0, "cod_ps" : campo1, "nom_ps" : campo2, "precio" : campo3, "monto_iva" : campo4, "cantidad" : campo5, "importe" : campo6, "cod_factura" : campo7},
			data.push(campos);
			
		})
		console.log(data);
		//~ $.post('<?php echo base_url(); ?>index.php/factura/ControllersFacturar/guardar/',{data:data}, function (data) {
			//~ if (data.success) {
				//~ alert('Registro con exito');
				//~ location.reload();
			//~ } else {
				//~ alert('Ocurrio un error');
			//~ }
		//~ }, 'json')
		
			$("#condicion_pago").val(String($("#condiciones").val()));
		
			$.post('<?php echo base_url(); ?>index.php/factura/ControllersFacturar/actualizar/', $('#form_factura').serialize()+'&'+$.param({'data':data}), function (response) {
				//~ alert($('#form_factura').serialize());

				bootbox.alert("Se Actualizó con exito", function () {
				}).on('hidden.bs.modal', function (event) {
					url = '<?php echo base_url(); ?>index.php/factura/ControllersFacturar'
					window.location = url
				});

			});
		}
	});
	
	
	$("#modal_cliente").modal('hide');
	 
	$("#hola").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto

		var cliente = $("#id_cliente").find('option').filter(':selected').text();
		$("#codcliente").val($('#id_cliente').val());
		$("#cliente").val(cliente);
		$("#modal_cliente").modal('hide');
		
		// Función para consultar los ajustes pendientes de las facturas del cliente
		//~ alert($("#codcliente").val());
		$.post('<?php echo base_url(); ?>index.php/factura/ControllersFacturar/consultar_ajustes/'+$("#codcliente").val(), function (response) {
			var respuesta = response.split('<html>');
			respuesta = respuesta[0].split(';');
			//~ alert("Nota de crédito: "+respuesta[0]+" Nota de débito: "+respuesta[1]);
			$("#span_credito").text(respuesta[0]);
			$("#nota_credito").val(respuesta[0]);
			$("#span_debito").text(respuesta[1]);
			$("#nota_debito").val(respuesta[1]);
			$("#ajustes").val(respuesta[2]);
		});
		
	});
        
	$('#btn-agregar-detalle').click(function(event) {
		
	});
       

	// Función para el cálculo de la factura al cambiar el descuento       
	$('#descuento').change(function () {
		//~ var bi = $("#base_imponible").val();
		var bi = base_imponible();
		var be = base_exenta();
		//~ var iva = $('#iva').find('option').filter(':selected').text();
        //~ iva = iva.split("%");
        //~ iva = iva[0];
        var iva = iva_total;
		
		if ($("#subtotal").val() != '') {
			// Cálculo del descuento
			var descuento = $("#descuento").val();
			
			//~ alert("IVA: "+iva+", Descuento: "+descuento);
			
			if(descuento != ''){
				descuento = (parseFloat(bi+be)*parseFloat(descuento)/100);
				$("#monto_desc").val(descuento);
				$("#span_desc").text(descuento);
			}else{
				descuento = 0;
				$("#span_desc").text(descuento);
				$("#monto_desc").val(descuento);
			}
			
			$("#subtotal").val(parseFloat(bi+be)-descuento);  // Cargamos el nuevo subtotal en el campo oculto para guardarlo en base de datos
			$("#span_sub_total").text(parseFloat(bi+be)-descuento);  // Cargamos el nuevo subtotal en la página sólo para visualización
			
			// Cálculo del IVA
			//~ $("#monto_iva").val((parseFloat($("#base_imponible").val())*parseFloat(iva))/100);  // Cargamos el iva en el campo oculto para guardarlo en base de datos
			//~ $("#span_iva").text((parseFloat($("#base_imponible").val())*parseFloat(iva))/100);  // Cargamos el iva en la página sólo para visualización
			$("#monto_iva").val(iva);
			$("#span_iva").text(iva);
			
			// Cálculo del Total
			$("#totalfactura").val(parseFloat($("#subtotal").val())+parseFloat($("#monto_iva").val()));  // Cargamos el total en el campo oculto para guardarlo en base de datos
			$("#span_total").text(parseFloat($("#subtotal").val())+parseFloat($("#monto_iva").val()));  //Cargamos el total en la página sólo para visualización
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
    
    // Cálculo de la base imponible
    function base_imponible(){
		
		var base_imponible = 0;
		var sub_iva = 0;
		var sub_monto = 0;
		
		$("#tab_factura tbody tr").each(function (index){
		
			$(this).children("td").each(function (index2) 
			{
				//~ base_imponible = base_imponible + parseFloat($(this).text());
				switch (index2) 
				{
					// Leer el campo de iva
					case 3:
						sub_iva = parseFloat($(this).text());
						break;
					// Leer el campo de importe
					case 5:
						sub_monto = parseFloat($(this).text());
						break;
				}
			})
			
			if(sub_iva > 0){
				base_imponible = base_imponible + sub_monto;
			}else{
				base_imponible = base_imponible + 0;
			}
		})
		
		//~ alert(base_imponible);
		
		return base_imponible;
	}
	
	// Cálculo de la base exenta
    function base_exenta(){
		
		var base_exenta = 0;
		var sub_iva = 0;
		var sub_monto = 0;
		
		$("#tab_factura tbody tr").each(function (index){
		
			$(this).children("td").each(function (index2) 
			{
				//~ base_imponible = base_imponible + parseFloat($(this).text());
				switch (index2) 
				{
					// Leer el campo de iva
					case 3:
						sub_iva = parseFloat($(this).text());
						break;
					// Leer el campo de importe
					case 5:
						sub_monto = parseFloat($(this).text());
						break;
				}
			})
			
			if(sub_iva > 0){
				base_exenta = base_exenta + 0;
			}else{
				base_exenta = base_exenta + sub_monto;
			}
			
		})
		
		//~ alert(base_imponible);
		
		return base_exenta;
	}
	
	// Cálculo del iva total
    function iva_total(){
		
		var total_iva = 0;
		var sub_iva = 0;
		var cantidad = 0;
		
		$("#tab_factura tbody tr").each(function (index){
		
			$(this).children("td").each(function (index2) 
			{
				//~ base_imponible = base_imponible + parseFloat($(this).text());
				switch (index2) 
				{
					// Leer el campo de iva
					case 3:
						sub_iva = parseFloat($(this).text());
						break;
					// Leer el campo de cantidad
					case 4:
						cantidad = parseFloat($(this).text());
						break;
				}
			})
			
			total_iva = total_iva + (sub_iva*cantidad);
		})
		
		//~ alert(total_iva);
		
		return total_iva;
	}
    
    // Función para la realización de los cálculos de Base Imponible, IVA y Total de la factura
    function calculos(base_imponible,base_exenta,iva_total) {
        //~ var bi = $("#base_imponible").val();
        var bi = base_imponible;
        var be = base_exenta;
        //~ alert(bi)
        //~ var iva = $('#iva').find('option').filter(':selected').text();
        //~ iva = iva.split("%");
        //~ iva = iva[0]
        //~ var total = $("#base_imponible").val();
        var iva = iva_total;
        //~ alert("Base imponible: "+bi);
        //~ alert("Base exenta: "+be);
        //~ alert("IVA: "+iva);
        
        // Cáculo de la Base Imponible
        if(bi == ''){
			bi = 0;
		}
        
        $("#base_imponible").val(bi);  // Cargamos la base imponible en el campo oculto para guardarlo en base de datos
        $("#span_bi").text(bi);  // Cargamos la base imponible en la página sólo para visualización
        
        $("#monto_exento").val(be);  // Cargamos la base exenta en el campo oculto para guardarlo en base de datos
        $("#span_bi_e").text(be);  // Cargamos la base exenta en la página sólo para visualización
        
        $("#subtotal").val(parseFloat($("#base_imponible").val())+parseFloat($("#monto_exento").val()));  // Cargamos el subtotal en el campo oculto para guardarlo en base de datos
        $("#span_sub_total").text(parseFloat($("#base_imponible").val())+parseFloat($("#monto_exento").val()));  // Cargamos el subtotal en la página sólo para visualización
        
        // Cálculo del descuento
		var descuento = $("#descuento").val();
		
		//~ alert(descuento);
		
		if(parseFloat(descuento) > 0){
			//~ alert("Hay descuento");
			descuento = (parseFloat(bi+be)*parseFloat(descuento)/100);
			//~ alert("Monto del descuento: "+descuento)
			$("#monto_desc").val(descuento);
			$("#span_desc").text(descuento);
			$("#subtotal").val(parseFloat(bi+be)-descuento);  // Cargamos el nuevo subtotal en el campo oculto para guardarlo en base de datos
			$("#span_sub_total").text(parseFloat(bi+be)-descuento);  // Cargamos el nuevo subtotal en la página sólo para visualización
		}else{
			$("#span_desc").text(0);
			$("#monto_desc").val(0);
		}
        
        // Cálculo del IVA
        //~ $("#monto_iva").val((parseFloat($("#base_imponible").val())*parseFloat(iva))/100);  // Cargamos el iva en el campo oculto para guardarlo en base de datos
        //~ $("#span_iva").text((parseFloat($("#base_imponible").val())*parseFloat(iva))/100);  // Cargamos el iva en la página sólo para visualización
        $("#monto_iva").val(iva);
        $("#span_iva").text(iva);
        
        // Cálculo del Total
        $("#totalfactura").val(parseFloat($("#subtotal").val())+parseFloat($("#monto_iva").val()));  // Cargamos el total en el campo oculto para guardarlo en base de datos
        $("#span_total").text(parseFloat($("#subtotal").val())+parseFloat($("#monto_iva").val()));  //Cargamos el total en la página sólo para visualización
    }
    
</script>
<div class="modal" id="modal_cliente">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
               <span class="glyphicon glyphicon-search"></span>
               &nbsp;  Busca y selecciona el cliente 
            </h4>
         </div>
         <div class="modal-body">
            <form name="f_nueva_venta" action="" method="post" class="form">
               <input type="hidden" name="cliente"/>
               <div class="form-group">
                  <div class="input-group">
                    <select id="id_cliente" name="cliente" class="form-control select2 input-sm" >
						<!--<option value="">Seleccione</option>-->

						<?php foreach ($listar as $cliente) { ?>
							<option value="<?php echo $cliente->codigo?>"><?php echo $cliente->nombre?></option>
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


<div class="modal" id="modal_servicios">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <span class="glyphicon glyphicon-search"></span>
                    &nbsp;  Buscar Producto
                </h4>
            </div>
            <div class="modal-body">
                <form name="f_nueva_venta" action="" method="post" class="form">
                    <!--<p>
                        <label>Tipo:&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                        <select id="tipo" name="tipo" class="form-control" >
                            <option value="0" selected="">Seleccione</option>
                            <option value="1">Producto</option>
                            <option value="2">Servicio</option>
                        </select>
                    </p>-->
                    <p>
                        <label>Producto:</label>  
                        <select id="id_servicio" name="id_servicio" class="form-control" >
                            <option value="0" selected="">Seleccione</option>
                        </select>
                    </p>
                    <p>
                        <label>Precio:</label><br clear="all"> <input type="text" style="width:100%" class="form-control" id="precio" name="precio">
                    </p>
                    <p>
                        <label>Monto IVA:</label><br clear="all"> <input type="text" style="width:100%" class="form-control" id="iva" name="iva" readonly="true">
                    </p>
                    <p>
                        <label>Cantidad: (<span id='span_exist' style="width:20%"></span>)</label><br clear="all"> <input type="text" style="width:100%"  class="form-control" id="cantidad" name="cantidad">
                        <input type="hidden" style="width:100%"  class="form-control" id="exist">
                        <input type="hidden" style="width:100%"  class="form-control" id="min">
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



<div class="container" style="width:90%;">
    <div class="row">
        <div class="col-lg-12">
            <div class="well bs-component">
                <form id="form_factura" class="form" method="post">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
<!--
                                <legend>Factura de Venta (
                                <?php echo $editar->codfactura;?>
                                )</legend>
-->
                                <input type="hidden" id="codfactura" name="codfactura" value="<?php echo $editar->codfactura;?>">
                                <input type="hidden" id="pre_cod_factura" name="pre_cod_factura">
                                
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-5">
								<div style="margin-left: 0.2%;margin-bottom: -1%;" class="form-group">
									<span >Código y nombre del cliente</span>
									<div class="input-group mar-btm">
										<span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
										<input type="text" style="width: 20%;" readonly="true" name="codcliente" id="codcliente" class="form-control" value="<?php echo $editar->codcliente;?>">
										<input type="text" style="width: 80%;" name="cliente" id="cliente" placeholder="Cliente" class="form-control" value="<?php echo $editar->cliente;?>">
										<span class="input-group-btn">
											<button type="button" data-toggle="modal" data-target="#modal_cliente" class="btn btn-primary" type="submit" id="modal_cliente">
												<i class="glyphicon glyphicon-search"></i>
											</button>

										</span>
									</div>
								</div>
                            </div>
                            <div class="col-sm-2">
								<span >Fecha de emisión</span>
								<div class="input-group mar-btm">
										<span class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></span>
										<input type="text" data-original-title="Fecha Emision" value="<?php echo $editar->fecha_emision ?>" data-toggle="tooltip" data-placement="bottom" readonly="true" name="fecha_emision" id="fecha_emision" placeholder="Fecha Emisión" class="form-control add-tooltip">
								</div>
                            </div>
<!--
                            <div class="col-sm-3">
								<span >Fecha de vencimiento</span>
								<div class="input-group mar-btm">
									<span class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></span>
									<input type="text" data-original-title="Fecha Vencimiento" data-toggle="tooltip" data-placement="bottom" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="Fecha Emisión" class="form-control add-tooltip">
									<input type="text" data-original-title="Fecha Emision" value="<?php echo  date("h:i:s a"); ?>" data-toggle="tooltip" data-placement="bottom" readonly="true" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="Fecha Vencimiento" class="form-control add-tooltip">
								</div>
                            </div>
-->
<!--
							<div class="col-sm-1">
								<span >IVA</span>
								<div class="input-group mar-btm">
									<select id="iva" name="iva" class="form-control select2 input-sm">
										<?php foreach ($list_iva as $iva) { ?>
											<option value="<?php echo $iva->id?>"><?php echo $iva->valor?>%</option>
										<?php }?>
									</select>
								</div>
                            </div>
-->
                            <div class="col-sm-2">
								<span >Descuento</span>
								<div class="input-group mar-btm">
									<input type="text" style="width:100%;" data-original-title="Descuento" data-toggle="tooltip" data-placement="bottom" name="descuento" id="descuento" placeholder="0.00" class="form-control add-tooltip" value="<?php echo $editar->descuento;?>" maxlength="3">
									<span class="input-group-addon"><i>%</i></span>
								</div>
                            </div>
                            <div class="col-sm-3">
								<span >Condición de pago</span>
								<select id="condiciones" multiple="multiple" class="form-control">
									<option value="1">Cheque</option>
									<option value="2">Debito</option>
									<option value="3">Efectivo</option>
									<option value="4">Transferencia</option>
									<option value="5">Depósito</option>
								</select>
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
                            <div class="form-group col-xs-1">

                                <button  class="btn btn-primary btn-labeled" id="i_new_line"><i class="fa fa-plus"></i>&nbsp;Agregar Producto</button>
                            </div>
                            <br/>
                            <br/>
                            <br/>

                            <input type="hidden" id="cant_row" value="1"/>
                            
                             <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_factura" align="center"
								   class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
								<thead style="font-size: 14px">
									<tr class="info">
										<th style='text-align: center'>Item</th>
										<!--<th style='text-align: center'>Tipo</th>-->
										<th style='text-align: center'>Producto</th>
										<th style='text-align: center'>Precio</th>
										<th style='text-align: center'>Monto IVA</th>
										<th style='text-align: center'>Cantidad</th>
										<th style='text-align: center'>Importe</th>
										<th style='text-align: center'>Quitar</th>
									</tr>
								</thead>
								<tbody >    
									<?php foreach ($listar_ps as $ps) { ?>
									<tr>
										<td style='text-align: center' id="<?php echo $ps->codfacturaps;?>"><?php echo $ps->cod_producto_servicio;?></td>
										<!--<td style='text-align: center'>
										<?php 
										//~ if ($ps->tipo == 2){
											//~ echo "Servicio";
										//~ }else{
											//~ echo "Producto";
										//~ }
										?>
										</td>-->
										<td style='text-align: center'><?php echo $ps->producto_servicio;?></td>
										<td style='text-align: center'><?php echo $ps->precio;?></td>
										<td style='text-align: center'><?php echo $ps->monto_iva;?></td>
										<td style='text-align: center'><?php echo $ps->cantidad;?></td>
										<td style='text-align: center'><?php echo $ps->importe;?></td>
										<td style='text-align: center'><a class='quitar' id="<?php echo $ps->codfacturaps;?>"><i class='glyphicon glyphicon-trash'></i></a></td>
									</tr>
									<?php }?>
								</tbody>
							</table>

                        </div>
                    </div>
                    </br>
                    
                    <div class="row" style="text-align: center">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                BI G: <span id="span_bi"><?php echo $editar->base_imponible;?></span>
                            </h4>   
                        </div>
                        <div class="col-sm-2">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                BI E: <span id="span_bi_e"><?php echo $editar->monto_exento;?></span>
                            </h4>   
                        </div>
                    </div>
                    
                    <div class="row" style="text-align: center">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                DESC: <span id="span_desc"><?php echo $editar->monto_desc;?></span>
                            </h4>   
                        </div>
                        <div class="col-sm-2">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                IVA G: <span id="span_iva"><?php echo $editar->monto_iva;?></span>
                            </h4>   
                        </div>
                    </div>
                    
                    <div class="row" style="text-align: center">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                Nota de Crédito: <span id="span_credito"><?php echo $editar->nota_credito;?></span>
                            </h4>   
                        </div>
                        <div class="col-sm-2">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                Nota de Débito: <span id="span_debito"><?php echo $editar->nota_debito;?></span>
                            </h4>   
                        </div>
                    </div>
                    
                    <div class="row" style="text-align: center">
                        <div class="col-sm-6">

                        </div>
                        
                        <div class="col-sm-2">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                SUB-TOTAL: <span id="span_sub_total"><?php echo $editar->subtotal;?></span>
                            </h4>   
                        </div>
                        <div class="col-sm-2">
                            <h4 style="margin-top: 5px;color:#D3220E">
                                TOTAL: <span id="span_total"><?php echo $editar->totalfactura;?></span>
                            </h4>   
                        </div>
                    </div>

                    <div class="container-fluid" style="margin-top: 10px;">
                        <div class="row">
							<input type="hidden" id="base_imponible" name="base_imponible" placeholder="Base Imponible" value="<?php echo $editar->base_imponible;?>">
							<input type="hidden" id="monto_exento" name="monto_exento" placeholder="Exento" value="<?php echo $editar->monto_exento;?>">
							<input type="hidden" id="monto_desc" name="monto_desc" placeholder="Descuento" value="<?php echo $editar->monto_desc;?>">
							<input type="hidden" id="monto_iva" name="monto_iva" placeholder="IVA" value="<?php echo $editar->monto_iva;?>">
							<input type="hidden" id="nota_credito" name="nota_credito" placeholder="Nota de crédito" value="<?php echo $editar->nota_credito;?>">
							<input type="hidden" id="nota_debito" name="nota_debito" placeholder="Nota de débito" value="<?php echo $editar->nota_debito;?>">
							<input type="hidden" id="subtotal" name="subtotal" placeholder="Sub-Total Factura" value="<?php echo $editar->subtotal;?>">
							<input type="hidden" id="totalfactura" name="totalfactura" placeholder="Total Factura" value="<?php echo $editar->totalfactura;?>">
							<input type="hidden" id="ajustes" name="ajustes" placeholder="Ajustes" value="<?php echo $editar->ajustes;?>">
							<input type="hidden" id="condicion_pago" name="condicion_pago" placeholder="Condiciones" value="<?php echo $editar->condicion_pago;?>">
							<!--Campo para almacenar los códigos de los registros a desasociar-->
							<input type="hidden" id="codigos_des" name="codigos_des" placeholder="Códigos">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    Observaciones:
                                    <textarea class="form-control" name="observaciones" rows="3"><?php echo $editar->observaciones;?></textarea>
                                </div>
                            </div>
                            <br/>
                            <div class="col-sm-12 text-center">
								<button type="button" id="volver" style="font-weight: bold;font-size: 13px" class="btn btn-warning">
								&nbsp;<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Volver
								</button>
                                <button type="button" id="facturar" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                &nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Guardar
                                </button>

                            </div>
							<input id="id_iva" type="hidden" value="<?php echo $editar->iva; ?>"/>
							<input id="id_condicion_pago" type="hidden" value="<?php echo $editar->condicion_pago; ?>"/>

                        </div>
                    </div>
				
				</form>
       
            </div>
        </div>
    </div>
</div>
</body>
</html>
