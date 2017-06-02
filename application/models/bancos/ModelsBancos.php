<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsBancos extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
	
	// Metodo público, forma de consultar los datos de todos los registros
    public function obtenerBancos() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('bancos');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de insertar los datos de un nuevo registro
    public function insertarBanco($datos)
    {

        $result = $this->db->insert("bancos", $datos);
        return $result;
    }
    
    // Metodo público, forma de consultar los datos de un registro
    public function obtenerBanco($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('bancos');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    // Metodo público, forma de actualizar los datos de un registro
    public function actualizarBanco($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('bancos', $datos);
        return $result;

    }

	// Metodo público, forma de eliminar un registro
    public function eliminarBanco($id) {
		// Primero buscamos si existe una cuenta asociada al código del banco que queremos eliminar
		$result = $this->db->where('banco =', $id);
        $result = $this->db->get('cuentas');
        
        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
			// Procedemos a borrar el tipo de servicio si no está asociado a un servicio
            $result = $this->db->delete('bancos', array('cod_banco'=>$id));
            return $result;
        }
    }
    
}
