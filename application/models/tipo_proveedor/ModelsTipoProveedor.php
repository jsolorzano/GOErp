<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsTipoProveedor extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
	
	// Metodo público, forma de consultar los datos de todos los registros
    public function obtenerTiposProveedores() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('tipo_proveedor');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de insertar los datos de un nuevo registro
    public function insertarTipoProveedor($datos)
    {

        $result = $this->db->insert("tipo_proveedor", $datos);
        return $result;
    }
    
    // Metodo público, forma de consultar los datos de un registro
    public function obtenerTipoProveedor($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('tipo_proveedor');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    // Metodo público, forma de actualizar los datos de un registro
    public function actualizarTipoProveedor($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('tipo_proveedor', $datos);
        return $result;

    }

	// Metodo público, forma de eliminar un registro
    public function eliminarTipoProveedor($cod) {
		// Primero buscamos si existe un Proveedor asociado al código del tipo de proveedor que queremos eliminar
		$result = $this->db->where('tipo_proveedor =', $cod);
        $result = $this->db->get('proveedor');
        
        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
			// Procedemos a borrar el tipo de proveedor si no está asociado a un proveedor
            $result = $this->db->delete('tipo_proveedor', array('cod_tipo'=>$cod));
            return $result;
        }
    }
    
}
