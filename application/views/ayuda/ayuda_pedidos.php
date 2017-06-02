<div class="modal-header" style="text-align:justify">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <center><h4 id="titulo" class="modal-title"><i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i> Pedidos</h4></center>
</div>
<div class="modal-body">
<img title="GOErp Logo" class='col-lg-4' style="" src="<?php echo base_url()?>static/img/default.png"/>
	<div id="container" style="text-align:justify">
	<p>En este módulo se dispone de las funciones para realizar pedidos a proveedores registrados en el sistema. Entre las principales funciones que provee tenemos las siguientes:</p>
	
	<p>
		<button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar" title="Nuevo Pedido">
			&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Pedido
		</button>: Para llamar al formulario de registro de nuevo pedido, desde el cual se podrá seleccionar el tipo de proveedor y los productos a solicitar a éste.
	</p>
	<p>
		-&nbsp;<b>PDF</b> <img title="Generar" class='generar_pdf' style="width:30px;height: 30px" src="<?php echo base_url()?>static/img/pdf.jpg"/>:
		Para generar un reporte en PDF del pedido generado, donde se visualizan los detalles del mismo.
	</p>
	<p>
		-&nbsp;<b>Aprobar</b> <input class='aprobar' type="checkbox" title='aprobar el ajuste 00000000'/>:
		Para certificar la aprobación de un pedido por parte del proveedor. Esta opción sólo estará activa si el pedido no ha sido anulado.
	</p>
	<p>
		-&nbsp;<b>Editar</b> <i class="glyphicon glyphicon-edit"></i>:
		Para modificar los datos del pedido seleccionado, bien sea el proveedor o la lista de productos solicitados. Esta opción sólo estará activa si el pedido no ha sido anulado ni aprobado.
	</p>
	<p>
		-&nbsp;<b>Anular</b> <input class='anular' type="checkbox" title='anular la factura 00000000'/>:
		Para anular un pedido y desactivar toda operación sobre él. Esta opción no estará disponible si el pedido ha sido aprobado.
	</p>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
