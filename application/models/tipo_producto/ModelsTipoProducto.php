<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsTipoProducto extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
	
	// Metodo público, forma de consultar los datos de todos los registros
    public function obtenerTiposProductos() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('tipo_producto');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de insertar los datos de un nuevo registro
    public function insertarTipoProducto($datos)
    {

        $result = $this->db->insert("tipo_producto", $datos);
        return $result;
    }
    
    // Metodo público, forma de consultar los datos de un registro
    public function obtenerTipoProducto($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('tipo_producto');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    // Metodo público, forma de actualizar los datos de un registro
    public function actualizarTipoProducto($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('tipo_producto', $datos);
        return $result;

    }

	// Metodo público, forma de eliminar un registro
    public function eliminarTipoProducto($id) {
		// Primero buscamos si existe un producto asociado al código del tipo de producto que queremos eliminar
		$result = $this->db->where('tipoproducto =', $id);
        $result = $this->db->get('producto');
        
        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
			// Procedemos a borrar el tipo de servicio si no está asociado a un servicio
            $result = $this->db->delete('tipo_producto', array('id'=>$id));
            return $result;
        }
    }
    
}
