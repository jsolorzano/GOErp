<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsImpuesto extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

    public function obtenerImpuestos() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('impuesto');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
     // Metodo publico, forma de insertar los datos
    public function insertarImpuesto($datos)
    {
        
        $result = $this->db->where('valor =', $datos['valor']);
        $result = $this->db->get('impuesto');

        if ($result->num_rows() > 0) {
            #echo "CORRECTO";
            echo '1';
        } else {
            $result = $this->db->insert("impuesto", $datos);
            return $result;
        }
        

    }
    
    public function obtenerImpuesto($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('impuesto');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    public function actualizarImpuesto($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('impuesto', $datos);
        return $result;

    }


    public function eliminarImpuesto($id) {
        
          // Primero buscamos si existe un cliente asociado a la factura que queremos eliminar
	$result = $this->db->where('iva =', $id);
        $result = $this->db->get('producto');
        
        if ($result->num_rows() > 0) {
            echo 'existe',$result->num_rows();
        } else {
			// Procedemos a borrar el tipo de servicio si no está asociado a un servicio
            $result = $this->db->delete('impuesto', array('id'=>$id));
            return $result;
        }

        
        
        
       
    }
    
    
}
