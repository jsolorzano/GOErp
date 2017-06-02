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
    <?php 
		if ($tipouser == 'Administrador'){
	
		} else {
			redirect(base_url());
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

                $("select").select2();
                $("#desde").numeric({allow: "/"}); //Valida solo permite valores numéricos
                $("#hasta").numeric({allow: "/"}); //Valida solo permite valores numéricos
                //~ $('#email').alphanumeric({allow: "@-_."});
                
                $('#dia').datepicker({
					format: "dd/mm/yyyy",
					endDate: 'today',
					minDate: "-1D",
					maxDate: "-1D",
					language: "es",
					minViewMode: 0,  // Esto establece mostrar días, meses y años
					autoclose: true,
				})
				
				$('#mes').datepicker({
					format: "mm/yyyy",
					endDate: 'today',
					minDate: "-1D",
					maxDate: "-1D",
					language: "es",
					minViewMode: 1,  // Esto establece mostrar sólo los meses y años
					autoclose: true,
				})
				
				$('#anyo').datepicker({
					format: "yyyy",
					endDate: 'today',
					minDate: "-1D",
					maxDate: "-1D",
					language: "es",
					minViewMode: 2,  // Esto establece mostrar sólo los años
					autoclose: true,
				})
				
				// Función para validar el tipo de rango y según éste activar y desactivar los campos que correspondan al cargar la página
				if($("#rango").val() != ''){
					if(rango == '1'){
						$("#rango_fecha").css("display","block");
						$("#dia").css("display","block");
						$("#mes").css("display","none");
						$("#anyo").css("display","none");
					}else if(rango == '2'){
						$("#rango_fecha").css("display","block");
						$("#dia").css("display","none");
						$("#mes").css("display","block");
						$("#anyo").css("display","none");
					}else if(rango == '3'){
						$("#rango_fecha").css("display","block");
						$("#dia").css("display","none");
						$("#mes").css("display","none");
						$("#anyo").css("display","block");
					}
				}else{
					$("#rango_fecha").css("display","none");
					$("#dia").css("display","none");
					$("#mes").css("display","none");
					$("#anyo").css("display","none");
				}
                
                // Función para validar el tipo de rango y según éste activar y desactivar los campos que correspondan al cambiar de opción
                $("#rango").change(function (event) {
					event.preventDefault();  // Para evitar que se envíe por defecto
                    var rango = $(this).val();
                    
                    if(rango != ''){
						if(rango == '1'){
							$("#rango_fecha").css("display","block");
							$("#dia").css("display","block");
							$("#mes").css("display","none");
							$("#anyo").css("display","none");
						}else if(rango == '2'){
							$("#rango_fecha").css("display","block");
							$("#dia").css("display","none");
							$("#mes").css("display","block");
							$("#anyo").css("display","none");
						}else if(rango == '3'){
							$("#rango_fecha").css("display","block");
							$("#dia").css("display","none");
							$("#mes").css("display","none");
							$("#anyo").css("display","block");
						}
					}else{
						$("#rango_fecha").css("display","none");
						$("#dia").css("display","none");
						$("#mes").css("display","none");
						$("#anyo").css("display","none");
					}
                    
                });
                
				// Función para enviar los datos al controlador
                $("#generar").click(function (e) {
                    e.preventDefault();  // Para evitar que se envíe por defecto
                    
                    if($('#rango').val().trim() == ''){
						bootbox.alert("Seleccione el tipo de rango", function () {
						}).on('hidden.bs.modal', function (event) {
							$('#rango').parent('div').addClass('has-error');
							$('#rango').val('');
							$('#rango').focus();
						});
					}else if(($('#rango').val().trim() != '' && $('#rango').val().trim() == '1') && $('#dia').val().trim() == '' ){
						bootbox.alert("Debe indicar el día", function () {
						}).on('hidden.bs.modal', function (event) {
							$('#dia').parent('div').addClass('has-error');
							$('#dia').val('');
							$('#dia').focus();
						});
					}else if(($('#rango').val().trim() != '' && $('#rango').val().trim() == '2') && $('#mes').val().trim() == '' ){
						bootbox.alert("Debe indicar el mes", function () {
						}).on('hidden.bs.modal', function (event) {
							$('#mes').parent('div').addClass('has-error');
							$('#mes').val('');
							$('#mes').focus();
						});
					}else if(($('#rango').val().trim() != '' && $('#rango').val().trim() == '3') && $('#anyo').val().trim() == '' ){
						bootbox.alert("Debe indicar el año", function () {
						}).on('hidden.bs.modal', function (event) {
							$('#anyo').parent('div').addClass('has-error');
							$('#anyo').val('');
							$('#anyo').focus();
						});
					}else{
						
						var rango = $('#rango').val();
						var fecha = '';
						var nombre_rango = "";
						
						if (rango == "1"){
							fecha = $("#dia").val();
							nombre_rango = "día";
						}
						else if(rango == "2"){
							fecha = $("#mes").val();
							nombre_rango = "mes";
						}
						else if(rango == "3"){
							fecha = $("#anyo").val();
							nombre_rango = "año";
						}
						else{
							fecha = '';
						}
						//~ alert("Generar Gráficas");
						//~ alert("Rango: "+rango+", Fecha: "+fecha);
						
						// Función para validar si hay registros para la búsqueda especificada
						$.post('<?php echo base_url(); ?>index.php/estadisticas/ControllersEstadisticas/obtenerVentas/', {'rango':rango, 'fecha':fecha}, function (response) {
							var respuesta = response.split('<html>');
							if (respuesta[0].trim() == 'Vacio') {
								bootbox.alert("No hay registros para la consulta especificada", function () {
								}).on('hidden.bs.modal', function (event) {
									
								});
							}else{
								// Activamos la modal de las gráficas
								$("#graficas").modal('show');
								
								// Variable con los datos reales
								// Aunque los datos ya vienen en formato json desde el controlador, es necesario reformatearlos con jQuery del lado del cliente para que Highcharts los reconozca
								var datos_ps = $.parseJSON(respuesta[0]);
								
								// Generamos la gráfica con los datos en el contenedor preparado
								$('#container').highcharts({
									chart: {
										plotBackgroundColor: null,
										plotBorderWidth: null,
										plotShadow: false,
										type: 'pie'
									},
									title: {
										text: 'Porcentaje de ventas de productos del '+nombre_rango+' '+fecha
									},
									tooltip: {
										pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
									},
									plotOptions: {
										pie: {
											allowPointSelect: true,
											cursor: 'pointer',
											dataLabels: {
												enabled: true,
												formatter: function() {
													return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
												}
											},
											showInLegend: true
										}
									},
									series: [{
										name: 'Ventas',
										colorByPoint: true,
										data: datos_ps
									}]
								});
								
								// Armamos una cadena de texto con la lista de productos y sus respectivas unidades vendidas
								var cantidades = "";
								$.each(datos_ps,function(index, value){
									cantidades += value.name+":"+value.y+",   ";
								});
								// Cargamos los datos de las cantidades (unidades) vendidas de cada producto
								$("#cantidades").text("Unidades Vendidas = "+cantidades);
								
							}
						});
                        
                    } 
                });


			});


		</script>
    </head>
    <body>

        </br>
        </br>
        </br>
        </br>

        <div class="row-fluid text-center" >
            <div class="mainbody-section">

                <div class="container" style="width:100%;">
                    <div class="row">

                        </br>

                        <div class="col-lg-12">
                            <div class="well bs-component">

                                <form class="form-horizontal" id="form_tiposervicio">
                                    <fieldset>
                                        <legend>Generar Reporte de Estadísticas de Ventas</legend>
                                           <br/>
                                        <div class="form-group">
											<div class="col-xs-1"></div>
                                            <div class="col-xs-1">Rango</div>
                                            <div class="col-lg-3">
                                                <select class="form-control" autofocus="true" id="rango" name="rango">
													<option value="">Seleccione</option>
													<option value="1">Día</option>
													<option value="2">Mes</option>
													<option value="3">Año</option>
												</select>
                                            </div>
                                            <div class="col-xs-1"></div>
											<div class="col-xs-1" id="rango_fecha">Fecha</div>
                                            <div class="col-lg-3" >
                                               	<input class="form-control" type='text' placeholder="Día" id="dia" name="dia" maxlength="10"/>
                                               	<input class="form-control" type='text' placeholder="Mes" id="mes" name="mes" maxlength="10"/>
                                               	<input class="form-control" type='text' placeholder="Año" id="anyo" name="anyo" maxlength="10"/>
                                            </div>
                                        </div>
   
                                        <br/>

                                        <div class="form-group">
                                            <div class="col-lg-12">
												<!--<input class="form-control"  type='hidden' placeholder="user" id="user_create_id" name="user_create_id" value="<?php echo $id_user ?>"/>-->
<!--
												<input class="form-control"  type='hidden' id="rango" name="rango"/>
-->
                                                <button type="submit" id="generar" style="font-weight: bold;font-size: 13px" class="btn btn-success"/>
                                                &nbsp;<span class="glyphicon glyphicon-file"></span>&nbsp;Generar
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
        
        

<!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="graficas" role="document">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="text-align:center">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Estadísticas de Ventas</h4>
        </div>
        <div class="modal-body">
			<div id="container" style="text-align:center"></div>
			<div id="cantidades" style="text-align:center">Cantidades = Cable UTP:8 UPS:2</div>
        </div>
        <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<!--Fin del contenedor para gráficas-->

    </body>
</html>

<div id='footer' style='text-align:center; margin-left: 20%'>
    <img style="width: 100%;" src="<?= base_url() ?>/static/img/footer.png"/>
</div>
</body>
</html>
