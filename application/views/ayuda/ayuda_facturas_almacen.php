<div class="modal-header" style="text-align:justify">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <center><h4 id="titulo" class="modal-title"><i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i> Facturas</h4></center>
</div>
<div class="modal-body">
<img title="GOErp Logo" class='col-lg-4' style="" src="<?php echo base_url()?>static/img/default.png"/>
	<div id="container" style="text-align:justify">
	<p>En este módulo se dispone de una lista con las facturas verificadas, donde cada registro posee funciones asociadas para realizar por el almacenista:</p>
	
	<p>
		-&nbsp;<b>PDF</b> <img title="Generar" class='generar_pdf' style="width:30px;height: 30px" src="<?php echo base_url()?>static/img/pdf.jpg"/>:
		Para mostrar un reporte en PDF de la factura generada, donde se visualizan los detalles de la misma.
	</p>
	<p>
		-&nbsp;<b>Entregar</b> <input class='aprobar' type="checkbox" title='entregar la factura 00000000'/>:
		Para certificar la entrega de los productos asociados a la factura. En esta opción se debe indicar el código único de la factura para poder verificarla y poder hacer la entrega.
	</p>
	</div>
</div><br><br><br><br><br>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
