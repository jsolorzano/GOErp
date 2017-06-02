<div class="modal-header" style="text-align:justify">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <center><h4 id="titulo" class="modal-title"><i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i> Pedidos</h4></center>
</div>
<div class="modal-body">
<img title="GOErp Logo" class='col-lg-4' style="" src="<?php echo base_url()?>static/img/default.png"/>
	<div id="container" style="text-align:justify">
	<p>En este módulo se dispone de una lista con los pedidos aprobados, donde cada registro posee funciones asociadas para realizar por el almacenista:</p>
	
	<p>
		-&nbsp;<b>PDF </b><img title="Generar" class='generar_pdf' style="width:30px;height: 30px" src="<?php echo base_url()?>static/img/pdf.jpg"/>:
		Para mostrar un reporte en PDF del pedido generado, donde se visualizan los detalles del mismo.
	</p>
	<p>
		-&nbsp;<b>Entregar </b><input class='aprobar' type="checkbox" title='ingresar el pedido 00000000'/>:
		Para certificar el ingreso de los productos asociados al pedido. En esta opción se debe indicar el código único del pedido para poder verificarlo y poder hacer el ingreso. 
	</p>
	</div>
</div><br><br><br><br><br>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
