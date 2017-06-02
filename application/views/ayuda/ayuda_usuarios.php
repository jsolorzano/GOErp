<div class="modal-header" style="text-align:justify">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <center><h4 id="titulo" class="modal-title"><i class="glyphicon glyphicon-user"></i><i class="glyphicon glyphicon-question-sign"></i> Gestión de Usuarios</h4></center>
</div>
<div class="modal-body">
<img title="GOErp Logo" class='col-lg-4' style="" src="<?php echo base_url()?>static/img/default.png"/>
	<div id="container" style="text-align:justify">
	<p>En este módulo se dispone de las funciones para gestionar los usuarios del sistema. Entre las principales funciones que provee tenemos las siguientes:</p>
	
	<p>
		<button role="button" class="btn btn-primary" style="font-weight: bold;font-size: 13px; color: white " id="enviar" title="Nuevo Usuario">
			&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;Nuevo Usuario
		</button>: Para llamar al formulario de registro de nuevo usuario, desde el cual se podrá indicar los datos básicos más la contraseña y el tipo de usuario.
	</p>
	<p>
		-&nbsp;<b>Editar</b> <i class="glyphicon glyphicon-edit"></i>:
		Para modificar los datos de un usuario, bien sean los datos básicos, la contraseña o el tipo de usuario. Cabe destacar que en está opción se debe ingresar obligatoriamente una contraseña, sea la misma o una nueva.
	</p>
	<p>
		-&nbsp;<b>Activar/Desactivar</b> <i class="glyphicon glyphicon-trash"></i>:
		Para habilitar o inhabilitar un usuario de la lista. Tenga en cuenta que si desactiva un usuario, éste no podrá iniciar sesión. 
	</p>
	</div>
</div><br>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
