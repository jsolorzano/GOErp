<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsCuentas extends CI_Model {
    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
	
	// Metodo público, forma de consultar los datos de todos los registros
    public function obtenerCuentas() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('cuentas');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de consultar los datos de todos los bancos activos
    public function obtenerBancosActivos() {
		$query = $this->db->where('estatus', 1);
        $query = $this->db->get('bancos');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de consultar los datos de todas las cuentas activas
    public function obtenerCuentasActivas() {
		$query = $this->db->where('estatus', 1);
        $query = $this->db->get('cuentas');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de consultar los datos de todas las cuentas activas excluyendo alguna específica
    public function obtenerCuentasActivasFil($cod) {
		$query = $this->db->where('codigo !=', $cod);
		$query = $this->db->where('cuenta !=', '00000000000000000000');
		$query = $this->db->where('estatus', 1);
        $query = $this->db->get('cuentas');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de consultar los datos de todas las cuentas activas excluyendo alguna específica
    public function obtenerCuentasActivasFil2() {
		$query = $this->db->where('cuenta !=', '00000000000000000000');
		$query = $this->db->where('estatus', 1);
        $query = $this->db->get('cuentas');
        
        if($query->num_rows()>0) return $query->result();
         else return $query->result();
    }
    
    // Metodo público, forma de insertar los datos de un nuevo registro
    public function insertarCuenta($datos)
    {

        $result = $this->db->insert("cuentas", $datos);
        return $result;
    }
    
    // Metodo público, forma de consultar los datos de un registro
    public function obtenerCuenta($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('cuentas');        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    // Metodo público, forma de actualizar los datos de un registro
    public function actualizarCuenta($datos) {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('cuentas', $datos);
        return $result;

    }

	// Metodo público, forma de eliminar un registro
    public function eliminarCuenta($id) {
		// Procedemos a borrar la cuenta
		$result = $this->db->delete('cuentas', array('codido'=>$id));
		return $result;
    }
    
    // Método público para consultar si ya existe una determinada cuenta en una tabla específica a través de su número y banco
    public function existe_cuenta($table, $camp_nom, $camp_nom2, $nom, $nom2)  // Argumentos: tabla, campo, valor
    {
		$result = $this->db->where(''.$camp_nom2.' =', $nom2);
        $result = $this->db->where(''.$camp_nom.' =', $nom);
        $result = $this->db->get($table);
        
        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
			echo 'no existe';
        }
        
    }
    
}
