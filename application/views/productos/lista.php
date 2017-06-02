<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
$tipouser = ($this->session->userdata['logged_in']['tipo_usuario']);
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
            var Tusuarios = $('#tab_cliente').dataTable({
                "iDisplayLength": 10,
                "iDisplayStart": 0,
                "sPaginationType": "full_numbers",
                "aLengthMenu": [6,8,10],
                //~ dom: 'Bfrtip',
				//~ buttons: [
					//~ {
						//~ extend: 'print',
						//~ text: 'Imprimir',
						//~ autoPrint: true
					//~ }
				//~ ],
                "oLanguage": {"sUrl": "<?= base_url() ?>static/js/es.txt"},
                "aoColumns": [
                    {"sClass": "registro center", "sWidth": "5%"},
                    {"sClass": "registro center", "sWidth": "5%"},
                    {"sClass": "registro center", "sWidth": "10%"},
                    {"sClass": "registro center", "sWidth": "20%"},
                    {"sClass": "registro center", "sWidth": "5%"},
                    {"sClass": "registro center", "sWidth": "5%"},
                    {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
                    {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
                    {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
                ]
            });
            
            $('#enviar').click(function(){
                url = '<?php echo base_url()?>index.php/productos/ControllersProductos/registrar';
                window.location = url
            })
            
            $('#cantidad').numeric(); //Solo permite texto numéricos"
            $('#n_precio').numeric({allow: "."}); //Solo permite texto numéricos"
            
            // Validacion para borrar
            $("table#tab_cliente").on('click', 'a.borrar', function (e) {
                e.preventDefault();
                var id = this.getAttribute('id');
                //alert(id)

                bootbox.dialog({
                    message: "¿Está seguro de borrar el Producto?",
                    title: "Borrar registro Producto",
                    buttons: {
                        danger: {
                            label: "Descartar",
                            className: "btn-primary",
                            callback: function () {

                            }
                        },
                        success: {
                            label: "Procesar",
                            className: "btn-success",
                            callback: function () {
                                //alert(id)
                                $.post('<?php echo base_url(); ?>index.php/productos/ControllersProductos/eliminar/' + id + '', function (response) {

                                    if (response[0] == "e") {

                                        bootbox.alert("Disculpe, el producto que desea eliminar se encuentra asociado a una o más factura ", function () {
                                        }).on('hidden.bs.modal', function (event) {
                                        });

                                    } else {
                                        bootbox.alert("Se elimino con exito", function () {
                                        }).on('hidden.bs.modal', function (event) {
                                            url = '<?php echo base_url(); ?>index.php/productos/ControllersProductos';
                                            window.location = url;
                                        });
                                    }
                                });
                            }
                        }
                    }
                });
            });
            
            
            // Función para preparar la compra de un producto
			$("table#tab_cliente").on('click', 'a.comprar', function (e) {
				e.preventDefault();
				var cod = this.getAttribute('id');
				//alert(cod)
				
				//~ var padre = $(this).closest('tr');
				//~ var nRow  = padre[0];
				bootbox.confirm("¿Desea agregar más existencia para este producto?", function(result) {
					if (result) {
						$("#codproducto").val(cod);
						//~ $("#accion").val(accion);
						$.fancybox({
							'autoScale': true, 'href': '#m_compra', 'type': 'inline', 'hideOnContentClick': true, 'openSpeed': 1000, 'closeSpeed': 1000, 'maxWidth': 960, 'maxHeight': 600, 'width': '1024%', 'height': '70%',
						});
					}
				}); 
			   
			   
			});
	
	
			// Función para ejecutar la compra de un producto
			$("#comprar").on('click', function (e) {
				e.preventDefault();
				
				//~ alert("código del producto: "+$("#codproducto").val());
				//~ alert("cantidad del producto: "+$("#cantidad").val());
				//~ alert("precio del producto: "+$("#n_precio").val());
				
				if ($("#cantidad").val() == ''){
					bootbox.alert("Indique la cantidad de existencia", function () {
					}).on('hidden.bs.modal', function (event) {
						$("#cantidad").parent('div').addClass('has-error')
						$("#cantidad").val('');
						$("#cantidad").focus();
					});
				}else{
					var mensaje = "";
					if ($("#accion_p").val() == 'pagar'){
						mensaje = "pagada";
					}else{
						mensaje = "activada";
					}
					$.post('<?php echo base_url(); ?>index.php/productos/ControllersProductos/comprar/' + $("#codproducto").val(), {'cantidad':$("#cantidad").val(), 'n_precio':$("#n_precio").val()}, function(response) {
						var respuesta = response.split('<html>');
						respuesta = respuesta[0].trim();
						//~ alert(respuesta);
						
						if(respuesta == 'stock superado'){
							bootbox.alert("La Cantidad Ingresada supera el Stock Máximo Permitido", function () {
							}).on('hidden.bs.modal', function (event) {
								$("#cantidad").parent('div').addClass('has-error')
								$("#cantidad").focus();
							});
						}else{
							bootbox.alert("El stock del producto fue actualizado exitosamente", function () {
							}).on('hidden.bs.modal', function (event) {
								//~ url = '<?php echo base_url(); ?>index.php/factura/ControllersFacturar'
								location.reload();
							});
						}
					})
				}
			});
            
        });
    </script>
</head>
<body>
    <br/>
    <div class="row-fluid text-center" >
        <div class="mainbody-section">
            <div class="container" style="width:90%;">
                <div class="row">                
                    <button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar"  >                  
                        &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Producto
                    </button>
                    </br>
                    </br>
                    <div class="page-header">
                        <h3 id="tables" class="lista">Listado de Productos</h3>
                    </div>
                    <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_cliente" align="center"
                           class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                        <thead style="font-size: 14px">
                            <tr class="info">
                                <th style='text-align: center'>#</th>

                                <th style='text-align: center'>Código</th>
                                <th style='text-align: center'>Nombre</th>
                                <th style='text-align: center'>Descripcion</th>
                                <th style='text-align: center'>Stock</th>
                                <th style='text-align: center'>Estatus</th>
                                <th style='text-align: center'>Comprar</th>
                                <th style='text-align: center'>Editar</th>
                                <th style='text-align: center'>Borrar</th>
                            </tr>
                        </thead>
                        <tbody >    
                            <?php $i = 1; ?>
                            <?php foreach ($listar as $productos) { ?>
                                <tr style="font-size: 16px;text-align: center">
                                    <td>
                                        <?php echo $i; ?>
                                    </td>

                                    <td>
                                        <?php echo $productos->codigo; ?>
                                    </td>
                                    <td>
                                        <?php echo $productos->nombre; ?>
                                    </td>
                                    <td>
                                        <?php echo $productos->descripcion; ?>
                                    </td>
                                    <td>
                                        <?php echo $productos->existencia; ?> 
                                    </td>
                                    <td>
										<?php if ($productos->existencia < $productos->stock_min && $productos->existencia > 0) {?>
										<img style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/warning.png"/>
										<?php }else if($productos->existencia > $productos->stock_min){ ?>
										<img style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/success.png"/>
										<?php }else if($productos->existencia <= 0){ ?>
										<img style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/danger.png"/>
										<?php } ?>
                                    </td>
                                    <td style='text-align: center'>
										<?php if ($tipouser == 'Administrador') {?>
											<a class="comprar" id='<?php echo $productos->codigo; ?>'><i class="glyphicon glyphicon-shopping-cart"></i></a>
										<?php }else{ ?>
											<img id="<?php echo $productos->codigo; ?>" style="width:20px;height: 20px" src="<?php echo base_url()?>static/img/block.png"/>
										<?php } ?> 
									</td>
                                    <td style='text-align: center'>
                                        <a href="<?php echo base_url() ?>index.php/productos/ControllersProductos/editar/<?= $productos->id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                                    </td>
                                    <td style='text-align: center'>

                                        <a class='borrar' id='<?php echo $productos->codigo; ?>'><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                    <!--Contenedor para compra-->
					<div id="m_compra" style="width:100%;display:none;">
						<div class="row">
							<div class="form-group">
								<div class="col-sm-12 text-center">
									<span style="font-weight:bold">NUEVA COMPRA</span>
								</div>
								</br>
								</br>
								</br>
								<label class="col-lg-3 control-label" style="font-weight:bold" >Cantidad:&nbsp;&nbsp;&nbsp;</label>
								<div class="col-lg-6">
									<input type="hidden" id="codproducto" name="codproducto"/>
									<input type="text" placeholder="Introduzca Cantidad" id="cantidad" name="cantidad" class="form-control">
								</div>
								</br>
								</br>
								</br>
								<label class="col-lg-3 control-label" style="font-weight:bold" >Nuevo precio:&nbsp;&nbsp;&nbsp;</label>
								<div class="col-lg-6">
									<input type="text" placeholder="Introduzca el nuevo precio" id="n_precio" name="n_precio" class="form-control">
								</div>
								</br>
								</br>
								</br>
								<div class="col-sm-12 text-center">
									<button type="button" data-toggle="modal" data-target="#modal_cliente" class="btn btn-primary" id="comprar" >
										<i class="fa fa-send fa-lg"></i>&nbsp;&nbsp;Comprar
									</button>
								</div>
							</div>
					   </div>
					</div>
				
                </div>
            </div>
        </div>
    </div>
