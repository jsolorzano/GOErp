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
    var Trespaldos = $('#tab_respaldos').dataTable({
		
        "iDisplayLength": 10,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5,8,10],
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
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ],
        
    });
    
    $('#generar').click(function(){
		
		bootbox.confirm("¿Desea continuar con el respaldo de la base de datos?", function(result) {
			if (result) {
				alert('SYS: Procesando Respaldo');
				$.post('<?php echo base_url(); ?>index.php/respaldos/ControllersRespaldos/respaldar/', function(response) {
					respuesta = response.split('<html>');
					//~ alert(respuesta[0].trim());
					var fecha_resp = new Date();
					var dia = fecha_resp.getDate();
					var mes = fecha_resp.getMonth();
					var anyo = fecha_resp.getFullYear();
					
					if (parseInt(dia) < 10){
						dia = "0"+dia;
					}
					if (parseInt(mes) < 10){
						mes = "0"+(parseInt(mes)+1);
					}
					
					fecha_resp = dia+''+mes+''+anyo;
					//~ alert(fecha_resp);
					//~ alert(respuesta[0].trim());
					url = '<?php echo base_url(); ?>respaldos/'+respuesta[0].trim()
					
					if(window.open(url)){
						URL = '<?php echo base_url(); ?>index.php/respaldos/ControllersRespaldos';
						window.location = URL;
					}
					
				})
				
			}
		});
    })
    
    // Función para descarga de respaldos generados un usuario
	$("table#tab_respaldos").on('click', 'a.generar', function (e) {
		e.preventDefault();
		var id = this.getAttribute('id');
		
		bootbox.confirm("¿Desea descargar el respaldo '"+id+"'?", function(result) {
			if (result) {
				
				url = '<?php echo base_url(); ?>/respaldos/'+id;
				window.open(url);
				
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
                
                <button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="generar"  >
                    &nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Respaldo
                </button>
                </br>
                </br>
                <div class="page-header">
              <h3 id="tables" class="lista">Listado de Respaldos</h3>
            </div>

                <table style="width:100%;" border="0" align="center" cellspacing="1" id="tab_respaldos" align="center"
                       class="panel table table-bordered table-striped table-hover table-condensed dt-responsive table-responsive" style="width:30%">
                    <thead style="font-size: 14px">
                        <tr class="info">
                            <th style='text-align: center'>#</th>
                            <th style='text-align: center'>Usuario</th>
                            <th style='text-align: center'>Cédula</th>
                            <th style='text-align: center'>Nombre y Apellido</th>
                            <th style='text-align: center'>Tipo respaldo</th>
                            <th style='text-align: center'>Fecha</th>
                            <th style='text-align: center'>Hora</th>
                            <th style='text-align: center'>Descarga</th>
                        </tr>
                    </thead>
                    <tbody >    
                        <?php $i=1; ?>
                       <?php foreach ($listar as $respaldo) { ?>
                        <tr style="font-size: 16px;text-align: center">
                            <td>
                             <?php echo $i;?>
                            </td>
                            <td>
                                <?php echo $respaldo->usuario; ?>
                            </td>
                            <td>
                                <?php echo $respaldo->cedula; ?>
                            </td>
                            <td>
                             <?php echo $respaldo->nombre; ?>
                            </td>
                            <td>
                             <?php echo $respaldo->tipo_usuario; ?> 
                            </td>
                            <td>
								<?php echo $respaldo->fecha; ?> 
                            </td>
                            <td>
								<?php echo $respaldo->hora; ?> 
                            </td>
                            <td style='text-align: center'>
                                <a class='generar' id="<?php echo $respaldo->archivo;?>" title="Descargar <?php echo $respaldo->archivo;?>"><i class="glyphicon glyphicon-floppy-save"></i></a>
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
