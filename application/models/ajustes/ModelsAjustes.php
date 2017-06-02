<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsAjustes extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Metodo publico, forma de obtener una lista de todos los ajustes
    public function obtenerAjustes()
    {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('ajustes');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener una lista de todas las facturas pagadas
    public function obtenerFacturas()
    {                                                                                      
        $query = $this->db->where('estado', 2);
		//~ $query = $this->db->where('codfactura like', '%ADM%');
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('facturas');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener una lista de todos los productos/servicios asociados a una factura
    public function obtenerProductosServicios($cod_autoconsumo)
    {                                                                                      
        $result = $this->db->where('codautoconsumo', $cod_autoconsumo);
        $result = $this->db->get('autoconsumo_ps');
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

		$result = $this->db->insert("ajustes", $data);
		//~ $result = $this->db->insert("cliente", $data);
		return $result;
        
    }
    
    // Metodo publico, forma de insertar los datos
    //~ public function insertar($data,$datos)
    public function insertar_ps($data)
    {
        //~ $result = $this->db->where('cirif =', $datos['cirif']);
        //~ $result = $this->db->get('cliente');

		$result = $this->db->insert("autoconsumo_ps", $data);
		//~ $result = $this->db->insert("cliente", $data);
		return $result;
        
    }    
    
    // Método público para consultar los datos de un ajuste según su id
    public function obtenerAjuste($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('ajustes');
        return $query->row();
    }
    
    // Método público para obtener los datos de una factura según su código
    public function obtenerAjusteCod($cod)
    {
        $this->db->where('codajuste', $cod);
        $query = $this->db->get('ajustes');
        return $query->row();
    }
    
    // Método público para consultar los datos de uno o más ajustes a una factura
    public function obtenerAjusteFactura($codfactura)
    {
		$this->db->where('estado', 2);
        $this->db->where('codfactura', $codfactura);
        $query = $this->db->get('ajustes');
        
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    public function actualizarAjuste($datos)
    {
        $result = $this->db->where('codajuste', $datos['codajuste']);
        $result = $this->db->update('ajustes', $datos);
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
        $result = $this->db->delete('autoconsumo_ps', array('codautoconsumops'=>$cod));
        return $result;
    }

}
