<div class="modal-header" style="text-align:justify">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <center><h4 id="titulo" class="modal-title"><i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i> Ajustes</h4></center>
</div>
<div class="modal-body">
<img title="GOErp Logo" class='col-lg-4' style="" src="<?php echo base_url()?>static/img/default.png"/>
	<div id="container" style="text-align:justify">
	<p>En este módulo se dispone de las funciones para realizar ajustes sobre facturas generadas. Entre las principales funciones que provee tenemos las siguientes:</p>
	
	<p>
		<button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar" title="Nuevo Ajuste">
			&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Ajuste
		</button>: Para llamar al formulario de registro de nuevo ajuste, desde el cual se podrá seleccionar el tipo de ajuste, que puede ser una nota de crédito o de débito. También permite indicar el monto y el concepto del ajuste.
	</p>
	<p>
		-&nbsp;<b>Generar</b> <img title="Generar" class='generar_pdf' style="width:30px;height: 30px" src="<?php echo base_url()?>static/img/pdf.jpg"/>:
		Para generar un reporte en PDF del ajuste generado, donde se visualizan los detalles del mismo. 
	</p>
	<p>
		-&nbsp;<b>Aplicar</b> <input class='aplicar' type="checkbox" title='aplicar el ajuste 00000000'/>:
		Para certificar la aplicación de un ajuste a la siguiente factura del cliente relacionado. Es decir, al generar una nueva factura para un cliente, éste tendrá un recargo o descuento si se le ha aplicado un ajuste a alguna factura anterior. Esta opción sólo estará activa si el ajuste no ha sido anulado.
	</p>
	<p>
		-&nbsp;<b>Editar</b> <i class="glyphicon glyphicon-edit"></i>:
		Para modificar los datos de un ajuste, bien sea la factura, monto o concepto del mismo. Esta opción sólo estará activa si el ajuste no ha sido anulado ni aplicado.
	</p>
	<p>
		-&nbsp;<b>Anular</b> <input class='anular' type="checkbox" title='anular la factura 00000000'/>:
		Para anular un ajuste y desactivar toda operación sobre él y así no tenga efecto en posteriores facturas. 
	</p>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
