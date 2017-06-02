<html>
<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
} else {
header("location: ../../");
}
?>

        
 <script>
$(document).ready(function(){
    var Tusuarios = $('#tab_cliente').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5,10,15,20, 30],
        dom: 'Bfrtip',
        buttons: [
			{
				extend: 'print',
				text: 'Imprimir',
				autoPrint: true
			}
		],
        "oLanguage": {"sUrl": "<?= base_url() ?>static/js/es.txt"},
        "aoColumns": [
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro left","sWidth": "3%" },

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

                                        bootbox.alert("Disculpe, Se encuentra asociado a una Parroquia", function () {
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

                </br>
                </br>
                <div class="page-header">
              <h3 id="tables">Listado de Auditoria</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_cliente" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="danger">
							
                            <th style='text-align: center'>#</th>
                            <th style='text-align: center'>Módulo</th>
                            <th style='text-align: center'>Código</th>
                            <th style='text-align: center'>Usuario</th>
                            <th style='text-align: center'>Fecha</th>
                            <th style='text-align: center'>Hora</th>

                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar as $auditoria) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                           
                            <td>
                                <?php echo $auditoria->tabla; ?>
                            </td>
                            <td>
                             <?php echo $auditoria->codigo; ?>
                            </td>
                            <td>
                             <?php echo $auditoria->usuario; ?>
                            </td>
                            <td>
                            <?php echo $auditoria->fecha; ?> 
                            </td>
                             <td>
                               <?php echo $auditoria->hora; ?> 
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
