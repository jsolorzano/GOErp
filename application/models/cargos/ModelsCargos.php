<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsCargos extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
	
	// Metodo público, forma de consultar los datos de todos los registros
    public function obtenerCargos() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('cargos');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de consultar los datos de todos los cargos activos
    public function obtenerCargosActivos() {
		$query = $this->db->where('estatus',1);
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('cargos');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de insertar los datos de un nuevo registro
    public function insertarCargo($datos)
    {

        $result = $this->db->insert("cargos", $datos);
        return $result;
    }
    
    // Metodo público, forma de consultar los datos de un registro
    public function obtenerCargo($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('cargos');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    // Metodo público, forma de actualizar los datos de un registro
    public function actualizarCargo($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('cargos', $datos);
        return $result;

    }

	// Metodo público, forma de eliminar un registro
    public function eliminarCargo($id) {
		// Primero buscamos si existe un empleado asociado al código del cargo que queremos eliminar
		$result = $this->db->where('cargo =', $id);
        $result = $this->db->get('empleados');
        
        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
			// Procedemos a borrar el tipo de servicio si no está asociado a un servicio
            $result = $this->db->delete('cargos', array('cod_cargo'=>$id));
            return $result;
        }
    }
    
}
