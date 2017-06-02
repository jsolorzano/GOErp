<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsTransferencias extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Metodo público, forma de consultar los datos de todas las transferencias
    public function obtenerTransferencias() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('transferencias');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de consultar los datos de un registro
    public function obtenerTransferencia($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('transferencias');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    // Metodo publico, forma de insertar los datos
    public function insertar($data)
    {
		$result = $this->db->insert("transferencias", $data);
		return $result;
    }
    
    // Metodo público, forma de actualizar los datos de un registro
    public function actualizar($datos) {
        
        $result = $this->db->where('codigo', $datos['codigo']);
        $result = $this->db->update('transferencias', $datos);
        return $result;

    }
    
}
