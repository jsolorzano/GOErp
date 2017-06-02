<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsConceptos extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
	
	// Metodo público, forma de consultar los datos de todos los registros
    public function obtenerConceptos() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('conceptos');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de consultar los datos de todos los conceptos activos
    public function obtenerConceptosActivos() {
		$query = $this->db->where('estatus',1);
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('conceptos');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de insertar los datos de un nuevo registro
    public function insertarConcepto($datos)
    {

        $result = $this->db->insert("conceptos", $datos);
        return $result;
    }
    
    // Metodo público, forma de consultar los datos de un registro
    public function obtenerConcepto($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('conceptos');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    // Metodo público, forma de actualizar los datos de un registro
    public function actualizarConcepto($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('conceptos', $datos);
        return $result;

    }

	// Metodo público, forma de eliminar un registro
    public function eliminarConcepto($id) {
		// Procedemos a borrar la cuenta
		$result = $this->db->delete('conceptos', array('codigo'=>$id));
		return $result;
    }
    
}
