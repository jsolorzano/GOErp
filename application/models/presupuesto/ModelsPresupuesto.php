<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsPresupuesto extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Metodo publico, forma de obtener una lista de todos los presupuestos
    public function obtenerPresupuestos()
    {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('presupuesto');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener una lista de todos los productos/servicios asociados a un presupuesto
    public function obtenerProdustosServicios($cod_presupuesto)
    {                                                                                      
        $result = $this->db->where('codpresupuesto', $cod_presupuesto);
        $result = $this->db->get('presupuesto_ps');
        return $result->result();
    }

	// Metodo publico, forma de obtener una lista de todos los clientes
    public function obtenerClientes()
    {                                                                                      
        $query = $this->db->get('cliente');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener un cliente por su código
    public function obtenerClienteCod($cod)
    {
        $this->db->where('codigo', $cod);
        $query = $this->db->get('cliente');
        return $query->row();
    }

    // Metodo publico, forma de insertar los datos
    //~ public function insertar($data,$datos)
    public function insertar($data)
    {
        //~ $result = $this->db->where('cirif =', $datos['cirif']);
        //~ $result = $this->db->get('cliente');

		$result = $this->db->insert("presupuesto", $data);
		//~ $result = $this->db->insert("cliente", $data);
		return $result;
        
    }
    
    // Metodo publico, forma de insertar los datos
    //~ public function insertar($data,$datos)
    public function insertar_ps($data)
    {
        //~ $result = $this->db->where('cirif =', $datos['cirif']);
        //~ $result = $this->db->get('cliente');

		$result = $this->db->insert("presupuesto_ps", $data);
		//~ $result = $this->db->insert("cliente", $data);
		return $result;
        
    }    
    
    public function obtenerPresupuesto($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('presupuesto');
        return $query->row();
    }
    
    public function obtenerPresupuestoCod($cod)
    {
        $this->db->where('codpresupuesto', $cod);
        $query = $this->db->get('presupuesto');
        return $query->row();
    }   

    public function actualizarPresupuesto($datos)
    {
        
        $result = $this->db->where('codpresupuesto', $datos['codpresupuesto']);
        $result = $this->db->update('presupuesto', $datos);
        return $result;
    }
    
    public function actualizarContacto($datos)
    {

        $result = $this->db->where('idcontacto', $datos['idcontacto']);
        $result = $this->db->update('contacto', $datos);
        return $result;
    }
    
	public function eliminarProductoServicio($cod)
    {
        $result = $this->db->delete('presupuesto_ps', array('codpresupuestops'=>$cod));
        return $result;
    }

}
