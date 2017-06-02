<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsUnidadMedida extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

    public function obtenerUnidadMedidas() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('unidad_medida');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
     // Metodo publico, forma de insertar los datos
    public function insertarUnidadMedida($datos)
    {

        $result = $this->db->insert("unidad_medida", $datos);
        return $result;
    }
    
    public function obtenerUnidadMedida($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('unidad_medida');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    public function actualizarUnidadMedida($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('unidad_medida', $datos);
        return $result;

    }


    public function eliminarUnidadMedida($id) {
        
           // Primero buscamos si existe un cliente asociado a la factura que queremos eliminar
	$result = $this->db->where('unidad_medida =', $id);
        $result = $this->db->get('producto');
        
        if ($result->num_rows() > 0) {
            echo 'existe',$result->num_rows();
        } else {
			// Procedemos a borrar el tipo de servicio si no está asociado a un servicio
            $result = $this->db->delete('unidad_medida', array('id'=>$id));
            return $result;
        }

        
        
        
    }
    
    
}
