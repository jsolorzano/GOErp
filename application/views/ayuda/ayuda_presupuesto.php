<div class="modal-header" style="text-align:justify">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <center><h4 id="titulo" class="modal-title"><i class="glyphicon glyphicon-file"></i><i class="glyphicon glyphicon-question-sign"></i> Presupuesto</h4></center>
</div>
<div class="modal-body">
<img title="GOErp Logo" class='col-lg-4' style="" src="<?php echo base_url()?>static/img/default.png"/>
	<div id="container" style="text-align:justify">
	<p>En este módulo se dispone de las funciones para generar presupuestos o cotizaciones para clientes. Entre las principales funciones que provee tenemos las siguientes:</p>
	
	<p>
		<button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar" tilte="Nuevo Presupuesto">
			&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Presupuesto
		</button>: Para llamar al formulario de registro de nuevo presupuesto, desde el cual se podrá seleccionar el cliente y los productos a asociar al presupuesto o cotización. 
		Realizando automáticamente los cálculos de los montos pertinentes.
	</p>
	<p>
		-&nbsp;<b>Generar</b> <img title="Generar" class='generar_pdf' style="width:30px;height: 30px" src="<?php echo base_url()?>static/img/pdf.jpg"/>:
		Para generar un reporte en PDF del presupuesto generado, donde se visualizan los detalles del mismo. 
	</p>
	<p>
		-&nbsp;<b>Editar</b> <i class="glyphicon glyphicon-edit"></i>:
		Para modificar los datos de un presupuesto, bien sea el cliente o la lista de productos a incluir.
	</p>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
