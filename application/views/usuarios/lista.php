<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
$tipouser = ($this->session->userdata['logged_in']['tipo_usuario']);
$id = ($this->session->userdata['logged_in']['id']);
} else {
redirect(base_url());
}
?>
  
<?php if ($tipouser == 'Administrador'){
	
 } else {
    redirect(base_url());
 }?>   
 <script>
$(document).ready(function(){
    var Tusuarios = $('#tab_usuarios').dataTable({
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
            {"sClass": "registro center", "sWidth": "8%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro left","sWidth": "5%" },
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    $('#enviar').click(function(){
        url = '<?php echo base_url()?>index.php/usuarios/usuarios/registrar'
        window.location = url
    })
	
	
	// Función para activar/desactivar un usuario
	$("table#tab_usuarios").on('click', 'input.activar_desactivar', function (e) {
		e.preventDefault();
		var id = this.getAttribute('id');
		//alert(id)
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'activar';
        }else{
			accion = 'desactivar';
		}
		
		//~ var padre = $(this).closest('tr');
		//~ var nRow  = padre[0];
		bootbox.confirm("¿Desea "+accion+" el Usuario?", function(result) {
			if (result) {
				$("#motivo_anulacion").val('');
				$("#accion").val(accion);
				
				var mensaje = "";
				if (accion == 'desactivar'){
					mensaje = "desactivado";
				}else{
					mensaje = "activado";
				}
				
				//~ alert("código de la factura: "+$("#codfactura").val());
				//~ alert("motivo de la anulación: "+$("#motivo_anulacion").val());
				
				$.post('<?php echo base_url(); ?>index.php/usuarios/Usuarios/activar_desactivar/' + id, {'accion':accion}, function(response) {
					bootbox.alert("El usuario fue "+mensaje+" exitosamente", function () {
					}).on('hidden.bs.modal', function (event) {
						location.reload();
					});
				})
				
			}
		}); 
	   
	   
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
                
                <button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar"  >
                    &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Usuario
                </button>
                </br>
                </br>
                <div class="page-header">
              <h3 id="tables" class="lista">Listado de Usuarios</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_usuarios" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
                            <th style='text-align: center'>#</th>
                            <th style='text-align: center'>Usuario</th>
                            <th style='text-align: center'>Cedula</th>
                            <th style='text-align: center'>Nombre y Apellido</th>
                            <th style='text-align: center'>Tipo Usuario</th>
                            <th style='text-align: center'>Estatus</th>
                            <th style='text-align: center'>Editar</th>
                            <th style='text-align: center'>Activar/Desactivar</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar as $usuario) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                            <td>
                                <?php echo $usuario->username; ?>
                            </td>
                            <td>
                                <?php echo $usuario->cedula; ?>
                            </td>
                            <td>
                             <?php echo $usuario->first_name; ?> <?php echo $usuario->last_name; ?>
                            </td>
                            <td>
                             <?php echo $usuario->tipo_usuario; ?> 
                            </td>
                            <td>
                           <?php if ($usuario->estatus == 1): ?>

                              <img style="width:15px;height: 15px" src="<?php echo base_url()?>static/img/yes.png"/>


                             <?php else: ?>

                                <img style="width:15px;height: 15px" src="<?php echo base_url()?>static/img/no.png"/>

                             <?php endif; ?>
                            </td>


                            <td style='text-align: center'>
                                <a href="<?php echo base_url()?>index.php/usuarios/usuarios/editar/<?= $usuario->id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td>
                            <td style='text-align: center'>
                                <?php if ($usuario->estatus == 1) {?>
								<input class='activar_desactivar' id='<?php echo $usuario->id; ?>' type="checkbox" title='Desactivar el usuario <?php echo $usuario->id;?>' checked="checked"/>
								<?php }else if ($usuario->estatus == 0){ ?>
								<input class='activar_desactivar' id='<?php echo $usuario->id; ?>' type="checkbox" title='Activar el usuario <?php echo $usuario->id;?>'/>
								<?php } ?>
                            </td>
                        </tr>
                        <?php $i++ ?>
                        <?php }?>
                        
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>
