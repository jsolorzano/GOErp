<div class="modal-header" style="text-align:justify">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <center><h4 id="titulo" class="modal-title"><i class="glyphicon glyphicon-th-list"></i><i class="glyphicon glyphicon-question-sign"></i> Productos</h4></center>
</div>
<div class="modal-body">
<img title="GOErp Logo" class='col-lg-4' style="" src="<?php echo base_url()?>static/img/default.png"/>
	<div id="container" style="text-align:justify">
	<p>En este módulo se dispone de las funciones para gestionar los productos de la empresa. Entre las principales funciones que provee tenemos las siguientes:</p>
	
	<p>
		<button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar" title="Nuevo Producto">
			<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Producto
		</button>: Para llamar al formulario de registro de nuevo producto, desde el cual se podrá indicar los datos básicos de un producto y aquellos concernientes a su existencia y costo de venta.
	</p>
	<p>
		-&nbsp;<b>Comprar</b> <i class="glyphicon glyphicon-shopping-cart"></i>:
		Para añadir nueva existencia y nuevo costo, si se desea, al producto seleccionado. Esto actualizará el stock requerido para el producto.
	</p>
	<p>
		-&nbsp;<b>Editar</b> <i class="glyphicon glyphicon-edit"></i>:
		Para modificar los datos de un producto, bien sean los datos básicos, existencia o costo del mismo.
	</p>
	<p>
		-&nbsp;<b>Borrar</b> <i class="glyphicon glyphicon-trash"></i>:
		Para eliminar un producto de la lista. 
	</p>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
