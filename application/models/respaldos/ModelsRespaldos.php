<?php

Class ModelsRespaldos extends CI_Model {
    
    
     //put your code here

    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

     // Metodo publico, forma de insertar los datos
    public function insertar($datos)
    {
		$result = $this->db->insert("respaldos", $datos);
		return $result;
    }
    

    public function obtenerRespaldos() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('respaldos');
        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }

}
?>

