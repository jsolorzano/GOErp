<html>
<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
$tipouser = ($this->session->userdata['logged_in']['tipo_usuario']);
} else {
redirect(base_url());
}
?>
  
<?php if ($tipouser == 'Ventas' || $tipouser == 'Administrador'){
	
 } else {
    redirect(base_url());
 }?>     
        
        
 <script>
$(document).ready(function(){
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
            {"sClass": "none", "sWidth": "8%"},
            {"sClass": "registro left","sWidth": "3%" },
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    $('#enviar').click(function(){
        url = '<?php echo base_url()?>index.php/clientes/ControllersClientes/registrar'
        window.location = url
    })
    
    // Validacion para borrar
            $("table#tab_cliente").on('click', 'a.borrar', function (e) {
                e.preventDefault();
                var id = this.getAttribute('id');
                //alert(id)

                bootbox.dialog({
                    message: "¿Está seguro de borrar el Cliente?",
                    title: "Borrar registro Cliente",
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
                                $.post('<?php echo base_url(); ?>index.php/clientes/ControllersClientes/eliminar/' + id + '', function (response) {
                                   
                                    if (response[0] == "e") {

                                        bootbox.alert("Disculpe, el cliente que desea eliminar se encuentra asociado a una factura", function () {
                                        }).on('hidden.bs.modal', function (event) {
											
                                        });

                                    } else {
                                        bootbox.alert("Se elimino con exito", function () {
                                        }).on('hidden.bs.modal', function (event) {
                                            url = '<?php echo base_url(); ?>index.php/clientes/ControllersClientes';
                                            window.location = url;
                                        });
                                    }
                                });
                            }
                        }
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
                
                <button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar">
                    &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Cliente
                </button>
                </br>
                </br>
                <div class="page-header">
              <h3 id="tables" class="lista">Listado de Clientes</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_cliente" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
							
                            <th style='text-align: center'>#</th>
                            <th style='text-align: center'>Código</th>
                            <th style='text-align: center'>Número de Identificación</th>
                            <th style='text-align: center'>Nombre ó Razón Social</th>
                            <th style='text-align: center'>Télefono</th>
                            <th style='text-align: center'>Tipo de Cliente</th>
                            <th style='text-align: center'>Estatus</th>
                            <th style='text-align: center'>Editar</th>
                            <th style='text-align: center'>Borrar</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar as $clientes) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                           
                            <td>
                                <?php echo $clientes->codigo; ?>
                            </td>
                            <td>
                             <?php echo $clientes->tipocliente; ?>-<?php echo $clientes->cirif; ?>
                            </td>
                            <td>
                             <?php echo $clientes->nombre; ?>
                            </td>
                            <td>
                            <?php echo $clientes->tlf; ?> 
                            </td>
                            <td>
                            <?php echo $clientes->tipo_cliente; ?> 
                            </td>
                             <td>
                             <?php if ($clientes->estatus == 1): ?>
                              <img style="width:15px;height: 15px" src="<?php echo base_url()?>static/img/yes.png"/>
                             <?php else: ?>
                              <img style="width:15px;height: 15px" src="<?php echo base_url()?>static/img/no.png"/>
                             <?php endif; ?>
                            </td>
                            <td style='text-align: center'>
                                <a href="<?php echo base_url()?>index.php/clientes/ControllersClientes/editar/<?= $clientes->id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td>
                            <td style='text-align: center'>
                                <a class='borrar' id='<?php echo $clientes->codigo; ?>'><i class="glyphicon glyphicon-trash"></i></a>
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
</body>
</html>
