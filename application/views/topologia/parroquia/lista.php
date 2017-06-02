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
            var TParr = $('#tab_parr').dataTable({
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
                    {"sClass": "registro center", "sWidth": "10%"},
                    {"sClass": "registro center", "sWidth": "30%"},
                    {"sClass": "registro left", "sWidth": "30%"},
                    {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
                    {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
                ]
            });
            
            // Validacion para borrar
            $("table#tab_parr").on('click', 'a.borrar', function (e) {
                e.preventDefault();
                var id = this.getAttribute('id');
                //alert(id)

                bootbox.dialog({
                    message: "¿Está seguro de borrar la Parroquia?",
                    title: "Borrar registro Parroquia",
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
                                $.post('<?php echo base_url(); ?>index.php/topologia/ControllersParroquia/eliminar/' + id + '', function (response) {
                                    
                                    if (response[0] == "e") {

                                        bootbox.alert("Disculpe, Se encuentra asociado a una Parroquia", function () {
                                        }).on('hidden.bs.modal', function (event) {
                                        });

                                    } else {
                                        bootbox.alert("Se elimino con exito", function () {
                                        }).on('hidden.bs.modal', function (event) {
                                            url = '<?php echo base_url(); ?>index.php/topologia/ControllersParroquia';
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
                url = '<?php echo base_url() ?>index.php/topologia/ControllersParroquia/registrar';
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

<!--                    <button role="button" class="btn" style="font-weight: bold;font-size: 13px; background: linear-gradient(#CEBEA4, #C3AB8B); color: white "  id="data" >
                        &nbsp;<i class="fa fa-upload"></i>&nbsp;Importar Parroquias
                    </button>-->

                    <button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px" id="enviar" >
                        &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Agregar Parroquia
                    </button>
                    </br>
                    </br>
                    <div class="page-header">
                        <h3 id="tables" class="lista">Listado de Parroquias</h3>
                    </div>

                    <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_parr" align="center"
                           class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                        <thead style="font-size: 14px">
                            <tr class="info">
                                <th style='text-align: center'>Item</th>
                                <th style='text-align: center'>Estado</th>
                                <th style='text-align: center'>Municipio</th>
                                <th style='text-align: center'>Parroquia</th>
                                <th style='text-align: center'>Editar</th>
                                <th style='text-align: center'>Borrar</th>
                            </tr>
                        </thead>
                        <tbody >    
                            <?php $i = 1; ?>

                            <?php foreach ($listar as $parroquia) { ?>
                                <tr style="font-size: 16px;text-align: center" class="{% cycle 'impar' 'par' %}" >
                                    <td>
                                        <?php echo $i; ?>
                                    </td>

                                    <td>
                                        <?php foreach ($list_estado as $estado) { ?>
                                            <?php if ($estado->cod_estado == $parroquia->estado_id): ?>
                                    <option value="<?php echo $estado->cod_estado ?>"><?php echo $estado->estado ?></option>
                                <?php endif; ?>
                            <?php } ?>
                            </td>
                            <td>
                                <?php foreach ($list_mun as $municipio) { ?>
                                    <?php if ($municipio->cod_municipio == $parroquia->municipio): ?>
                                    <option value="<?php echo $municipio->cod_municipio ?>"><?php echo $municipio->municipio ?></option>
                                <?php endif; ?>
                            <?php } ?>
                            </td>
                            <td>
                                <?php echo $parroquia->parroquia; ?>
                            </td>
                            <td style='text-align: center'>
                                <a href="<?php echo base_url() ?>index.php/topologia/ControllersParroquia/editar/<?= $parroquia->id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            </td>
                            <td style='text-align: center'>

                                <a class='borrar' id='<?php echo $parroquia->cod_parroquia; ?>'><i class="glyphicon glyphicon-trash"></i></a>
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
