<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsTipoCliente extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
	
	// Metodo público, forma de consultar los datos de todos los registros
    public function obtenerTiposClientes() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('tipo_cliente');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de insertar los datos de un nuevo registro
    public function insertarTipoCliente($datos)
    {

        $result = $this->db->insert("tipo_cliente", $datos);
        return $result;
    }
    
    // Metodo público, forma de consultar los datos de un registro
    public function obtenerTipoCliente($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('tipo_cliente');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    // Metodo público, forma de actualizar los datos de un registro
    public function actualizarTipoCliente($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('tipo_cliente', $datos);
        return $result;

    }

	// Metodo público, forma de eliminar un registro
    public function eliminarTipoCliente($id) {
	// Primero buscamos si existe un cliente asociado al código del tipo de cliente que queremos eliminar
	$result = $this->db->where('tipo_cliente =', $id);
        $result = $this->db->get('cliente');
        
        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
			// Procedemos a borrar el tipo de servicio si no está asociado a un servicio
            $result = $this->db->delete('tipo_cliente', array('cod_tipo'=>$id));
            return $result;
        }
    }
    
}
