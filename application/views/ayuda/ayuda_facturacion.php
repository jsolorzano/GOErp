
<div class="modal-header" style="text-align:justify">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <center><h4 id="titulo" class="modal-title"><i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i> Facturación</h4></center>
</div>
<div class="modal-body">
<img title="GOErp Logo" class='col-lg-4' style="" src="<?php echo base_url()?>static/img/default.png"/>

	<div id="container" style="text-align:justify">
	<p>&nbsp;En este módulo se dispone de las funciones para generar facturas de clientes. Entre las principales funciones que provee tenemos las siguientes:</p>
	
	<p>
		<button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar" title="Nueva Factura">
			&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nueva Factura
		</button>: Para llamar al formulario de registro de nueva factura, desde el cual se podrá seleccionar el cliente y los productos a asociar en la factura. 
		Realizando automáticamente los cálculos de los montos pertinentes.
	</p>
	<p>
		-&nbsp;<b>Generar</b> <img title="Generar" class='generar_pdf' style="width:30px;height: 30px" src="<?php echo base_url()?>static/img/pdf.jpg"/>:
		Para generar un reporte en PDF de la factura generada, donde se visualizan los detalles de la misma. 
	</p>
	<p>
		-&nbsp;<b>Verificar</b> <input class='verificar' type="checkbox" title='verificar la factura 00000000'/>:
		Para indicar cuando una factura ya ha sido pagada por el cliente y así proceder a acreditar el monto a las cuentas de la empresa. Esta opción sólo estará activa si la factura no ha sido anulada.
	</p>
	<p>
		-&nbsp;<b>Editar</b> <i class="glyphicon glyphicon-edit"></i>:
		Para modificar los datos de una factura, bien sea el cliente o la lista de productos a incluir. Esta opción sólo estará activa si la factura no ha sido anulada ni verificada.
	</p>
	<p>
		-&nbsp;<b>Anular</b> <input class='anular' type="checkbox" title='anular la factura 00000000'/>:
		Para anular una factura y desactivar toda operación sobre élla. 
	</p>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
