<?php

Class Usuarios_model extends CI_Model {
    
    
     //put your code here

    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

     // Metodo publico, forma de insertar los datos
    public function insertar($datos)
    {
		$result = $this->db->where('username =', $datos['username']);
        $result = $this->db->get('usuarios');
        
        if ($result->num_rows() > 0) {
            #echo "CORRECTO";
            echo '1';
        } else {
            $result = $this->db->insert("usuarios", $datos);
            return $result;
        }
    }
    

    public function obtenerUsuarios() {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('usuarios');
        
        if($query->num_rows()>0) return $query->result();
        else return $query->result();
    }
    
    public function obtenerUsuario($id){
        $this->db->where('id',$id);    
        $query = $this->db->get('usuarios');        
        if($query->num_rows()>0) return $query->result();
        else return false;
    }
    
    public function actualizarUsuario($id, $datos) {

        $result = $this->db->where('id', $id);
        $result = $this->db->update('usuarios', $datos);
        return $result;

    }

    public function eliminarUsuario($id) {
        $this->db->delete('usuarios', array('id'=>$id));
    }
    
    


}
?>

