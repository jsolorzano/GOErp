<?php

class ControllersBusqueda extends CI_Controller {
    
     public function __construct()
    {


        @parent::__construct();
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        $this->load->model('productos/ModelsProductos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');

    }
    
    // Método publico para traer las lineas estrategicas segun la asociacion con el plan de la nacion
    public function ajax_mun($id)
    {                                          #Campo         #Tabla                #ID
        $result = $this->ModelsBusqueda->search('estado_id', 'municipios', $id);
        echo json_encode($result);
    }
    
     public function ajax_contacto($id)
    {                                          #Campo         #Tabla                #ID
        $result = $this->ModelsBusqueda->search('idcontacto', 'contacto', $id);
        echo json_encode($result);
    }
    

    
     public function ajax_parr($id_est, $id_mun)
    {                                          #Campo         #Tabla                #ID
        $result = $this->ModelsBusqueda->search_multiple_two('estado_id', 'municipio', 'parroquias', $id_est, $id_mun);
        echo json_encode($result);
    }
    
	// Controlador para cargar lista general de productos
    public function producto()
    {
        $result = $this->ModelsProductos->obtenerProductos();
        echo json_encode($result);
    }
    
    // Controlador para cargar la lista de productos para terminales
    public function productos_terminales()
    {     
        $result = $this->ModelsProductos->obtenerProductosTerminales();
        echo json_encode($result);
    }
    
    // Controlador para cargar lista de productos con existencia
    public function productos_existencia()
    {                                       

        $result = $this->ModelsProductos->obtenerProductosExistencia();
        echo json_encode($result);
    }
    
    // Controlador para cargar lista general de servicios
     public function servicios()
    {                                       

        $result = $this->ModelsServicios->obtenerServicios();
        echo json_encode($result);
    }
    
    // Método para obtener los datos de un producto/servicio específico
    public function datos_ps()
    {                                       
		$tabla = $this->input->post('tabla'); 
		$campo = $this->input->post('campo'); 
		$valor = $this->input->post('valor');
		
		//~ echo "Tabla: ".$tabla;
		//~ echo "Campo: ".$campo;
		//~ echo "Valor: ".$valor;
		
        $result = $this->ModelsBusqueda->obtenerRegistro($tabla, $campo, $valor);
        echo json_encode($result);
    }
    

}
