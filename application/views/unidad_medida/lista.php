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
            var TUnidad = $('#tab_unidad').dataTable({
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
                    {"sClass": "registro center", "sWidth": "15%"},
                    {"sClass": "registro center", "sWidth": "15%"},
                    {"sClass": "registro center", "sWidth": "15%"},
                    {"sClass": "registro left", },
                    {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
                    {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
                ]
            });
            
            // Validacion para borrar
            $("table#tab_unidad").on('click', 'a.borrar', function (e) {
                e.preventDefault();
                var id = this.getAttribute('id');
                //alert(id)

                bootbox.dialog({
                    message: "¿Desea eliminar la unidad de medida?",
                    title: "Borrar registro",
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
                                $.post('<?php echo base_url(); ?>index.php/unidad_medida/ControllersUnidadMedida/eliminar/' + id + '', function (response) {
                                    
                                    if (response[0] == "e") {

                                        bootbox.alert("Disculpe, la unidad de medida que desea eliminar se encuentra asociado a un producto", function () {
                                        }).on('hidden.bs.modal', function (event) {
                                        });

                                    } else {
                                        bootbox.alert("Se elimino con exito", function () {
                                        }).on('hidden.bs.modal', function (event) {
                                            url = '<?php echo base_url(); ?>index.php/unidad_medida/ControllersUnidadMedida';
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
                url = '<?php echo base_url() ?>index.php/unidad_medida/ControllersUnidadMedida/registrar';
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
                        &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Agregar Unidad de Medida
                    </button>
                    </br>
                    </br>
                    <div class="page-header">
                        <h3 id="tables" class="lista">Listado de Unidades de Medida</h3>
                    </div>

                    <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_unidad" align="center"
                           class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                        <thead style="font-size: 14px">
                            <tr class="info">
                                <th style='text-align: center'>Item</th>
                                <th style='text-align: center'>Descripción</th>
                                <th style='text-align: center'>Simbolo</th>
                                <th style='text-align: center'>Tipo</th>
                                <th style='text-align: center'>Editar</th>
                                <th style='text-align: center'>Borrar</th>
                            </tr>
                        </thead>
                        <tbody >    
                            <?php $i = 1; ?>

                            <?php foreach ($listar as $unidad_medida) { ?>
                                <tr style="font-size: 16px;text-align: center" class="{% cycle 'impar' 'par' %}" >
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $unidad_medida->unidades; ?>
                                    </td>
                                    <td>
                                        <?php echo $unidad_medida->simbolo; ?>
                                    </td>
                                    <td>
                                        <?php echo $unidad_medida->tipo; ?>
                                    </td>
                                    <td style='text-align: center'>
                                        <a title="Editar" href="<?php echo base_url() ?>index.php/unidad_medida/ControllersUnidadMedida/editar/<?= $unidad_medida->id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                                    </td>
                                    <td style='text-align: center'>

                                        <a class='borrar' id='<?php echo $unidad_medida->id; ?>' title="Borrar" href="<?php echo base_url() ?>index.php/unidad_medida/ControllersUnidadMedida/eliminar/<?= $unidad_medida->id; ?>"><i class="glyphicon glyphicon-trash"></i></a>
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
