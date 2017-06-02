<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsMovimientos extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Metodo público, forma de consultar los datos de todos los movimientos
    public function obtenerMovimientos() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('movimientos');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo publico, forma de insertar los datos
    public function insertar($data)
    {
		$result = $this->db->insert("movimientos", $data);
		return $result;
    }
}
