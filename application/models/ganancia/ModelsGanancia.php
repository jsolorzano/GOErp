<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsGanancia extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

    public function obtenerGanancias() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('ganancia');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
     // Metodo publico, forma de insertar los datos
    public function insertarGanancia($datos)
    {
        $result = $this->db->where('valor =', $datos['valor']);
        $result = $this->db->get('ganancia');

        if ($result->num_rows() > 0) {
            #echo "CORRECTO";
            echo '1';
        } else {
            $result = $this->db->insert("ganancia", $datos);
            return $result;
        }

    }
    
    public function obtenerGanancia($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('ganancia');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    public function actualizarGanancia($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('ganancia', $datos);
        return $result;

    }


    public function eliminarGanancia($id) {
        
          // Primero buscamos si existe un cliente asociado a la factura que queremos eliminar
	$result = $this->db->where('ganancia =', $id);
        $result = $this->db->get('producto');
        
        if ($result->num_rows() > 0) {
            echo 'existe',$result->num_rows();
        } else {
			// Procedemos a borrar el tipo de servicio si no está asociado a un servicio
             $result = $this->db->delete('ganancia', array('id'=>$id));
 
            return $result;
        }

    }
    
    
}
