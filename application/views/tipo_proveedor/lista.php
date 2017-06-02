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
  
<?php if ($tipouser == 'Administrador'){
	
 } else {
    redirect(base_url());
 }?>  
    <script>
        $(document).ready(function () {
            var TTCliente = $('#tab_tipo_proveedor').dataTable({
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
                    {"sClass": "registro center", "sWidth": "20%"},
                    {"sClass": "registro center", "sWidth": "35%"},
                    {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
                    {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
                ]
            });
            
            // Validacion para borrar
            $("table#tab_tipo_proveedor").on('click', 'a.borrar', function (e) {
                e.preventDefault();
                var cod = this.getAttribute('id');
                //alert(id)

                bootbox.dialog({
                    message: "¿Desea borrar el registro?",
                    title: "Borrar registro Tipo Proveedor",
                    buttons: {
                        success: {
                            label: "Descartar",
                            className: "btn-primary",
                            callback: function () {

                            }
                        },
                        danger: {
                            label: "Procesar",
                            className: "btn-warning",
                            callback: function () {
                                //alert(id)
                                $.post('<?php echo base_url(); ?>index.php/tipo_proveedor/ControllersTipoProveedor/eliminar/' + cod + '', function (response) {
                                    
                                    if (response[0] == "e") {

                                        bootbox.alert("Disculpe, el registro se encuentra asociado a un Proveedor", function () {
                                        }).on('hidden.bs.modal', function (event) {
                                        });

                                    } else {
                                        bootbox.alert("Se eliminó con exito", function () {
                                        }).on('hidden.bs.modal', function (event) {
                                            url = '<?php echo base_url(); ?>index.php/tipo_proveedor/ControllersTipoProveedor';
                                            window.location = url;
                                        });
                                    }
                                });
                            }
                        }
                    }
                });
            });
            
            $('#enviar').click(function () {
                url = '<?php echo base_url() ?>index.php/tipo_proveedor/ControllersTipoProveedor/registrar';
                window.location = url;
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



                    <button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px" id="enviar" >
                        &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Agregar Tipo de Proveedor
                    </button>
                    </br>
                    </br>
                    <div class="page-header">
                        <h3 id="tables" class="lista">Listado de Tipos de Proveedor</h3>
                    </div>

                    <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_tipo_proveedor" align="center"
                           class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                        <thead style="font-size: 14px">
                            <tr class="info">
                                <th style='text-align: center'>Item</th>
                                <th style='text-align: center'>Código</th>
                                <th style='text-align: center'>Tipo Proveedor</th>
                                <th style='text-align: center'>Editar</th>
                                <th style='text-align: center'>Borrar</th>
                            </tr>
                        </thead>
                        <tbody >    
                            <?php $i = 1; ?>

                            <?php foreach ($listar as $tipo_proveedor) { ?>
                                <tr style="font-size: 16px;text-align: center" class="{% cycle 'impar' 'par' %}" >
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $tipo_proveedor->cod_tipo; ?>
                                    </td>
                                    <td>
                                        <?php echo $tipo_proveedor->tipo_proveedor; ?>
                                    </td>
                                    <td style='text-align: center'>
                                        <a title="Editar" href="<?php echo base_url() ?>index.php/tipo_proveedor/ControllersTipoProveedor/editar/<?= $tipo_proveedor->id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                                    </td>
                                    <td style='text-align: center'>
<!--
                                        <a class='borrar' id='<?php echo $tipo_proveedor->id; ?>' title="Borrar" href="<?php echo base_url() ?>index.php/tipo_proveedor/ControllersTipoProveedor/eliminar/<?= $tipo_proveedor->id; ?>"><i class="glyphicon glyphicon-trash"></i></a>
-->
										<a class='borrar' id='<?php echo $tipo_proveedor->cod_tipo; ?>' title="Borrar"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                            <?php } ?>

                        </tbody>
                    </table>



                </div>
            </div>
        </div>
    </div>
