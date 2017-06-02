<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsRecibo extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Metodo publico, forma de obtener una lista de todas las Recibos
    public function obtenerRecibos()
    {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('recibos');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener una lista de todos los productos/servicios asociados a una Recibo
    public function obtenerAbonosDescuentos($cod_recibo)
    {                                                                                      
        $result = $this->db->where('codrecibo', $cod_recibo);
        $result = $this->db->get('recibos_ps');
        return $result->result();
    }

	// Metodo publico, forma de obtener una lista de todos los Empleados
    public function obtenerEmpleados()
    {                                                                                      
        $query = $this->db->get('empleados');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener un empleados por su código
    public function obtenerEmpleadoCod($cod)
    {
        $this->db->where('codigo', $cod);
        $query = $this->db->get('empleados');
        return $query->row();
    }

    // Metodo publico, forma de insertar los datos
    //~ public function insertar($data,$datos)
    public function insertar($data)
    {
        //~ $result = $this->db->where('cirif =', $datos['cirif']);
        //~ $result = $this->db->get('empleados');

		$result = $this->db->insert("recibos", $data);
		//~ $result = $this->db->insert("empleados", $data);
		return $result;
        
    }
    
    // Metodo publico, forma de insertar los datos
    //~ public function insertar($data,$datos)
    public function insertar_ps($data)
    {
        //~ $result = $this->db->where('cirif =', $datos['cirif']);
        //~ $result = $this->db->get('empleados');

		$result = $this->db->insert("recibos_ps", $data);
		//~ $result = $this->db->insert("empleados", $data);
		return $result;
        
    }    
    
    public function obtenerRecibo($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('recibos');
        return $query->row();
    }
    
    public function obtenerReciboCod($cod)
    {
        $this->db->where('codrecibo', $cod);
        $query = $this->db->get('recibos');
        return $query->row();
    }
    
    // Metodo publico, forma de obtener la lista de Recibos de un empleado según su código
    public function obtenerRecibosEmpleado($codempleado)
    {
		$result = $this->db->where('codempleado', $codempleado);
        $query = $this->db->get('recibos');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    public function actualizarRecibo($datos)
    {
        
        $result = $this->db->where('codrecibo', $datos['codrecibo']);
        $result = $this->db->update('recibos', $datos);
        return $result;
    }
    
    public function actualizarContacto($datos)
    {

        $result = $this->db->where('idcontacto', $datos['idcontacto']);
        $result = $this->db->update('contacto', $datos);
        return $result;
    }

    public function eliminarAbonoDescuento($cod)
    {
        $result = $this->db->delete('recibos_ps', array('codrecibops'=>$cod));
        return $result;
    }

}
