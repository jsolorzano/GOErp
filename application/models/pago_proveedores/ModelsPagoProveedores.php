<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsPagoProveedores extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Metodo publico, forma de obtener una lista de todas las facturas
    public function obtenerPagos()
    {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('pago_proveedores');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

	// Metodo publico, forma de obtener una lista de todos los clientes
    public function obtenerProveedores()
    {                                                                                      
        $query = $this->db->get('proveedor');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener un proveedor por su código
    public function obtenerProveedorCod($cod)
    {
        $this->db->where('codigo', $cod);
        $query = $this->db->get('proveedor');
        return $query->row();
    }

    // Metodo publico, forma de insertar los datos
    //~ public function insertar($data,$datos)
    public function insertar($data)
    {
        //~ $result = $this->db->where('cirif =', $datos['cirif']);
        //~ $result = $this->db->get('cliente');

		$result = $this->db->insert("pago_proveedores", $data);
		//~ $result = $this->db->insert("cliente", $data);
		return $result;
        
    }
    
    public function obtenerPago($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('pago_proveedores');
        return $query->row();
    }
    
    public function obtenerPagoCod($cod)
    {
        $this->db->where('codpago', $cod);
        $query = $this->db->get('pago_proveedores');
        return $query->row();
    }
    
    // Metodo publico, forma de obtener la lista de pagos a un proveedor según su código
    public function obtenerPagoProveedor($codproveedor)
    {
		$result = $this->db->where('codproveedor', $codcliente);
        $query = $this->db->get('pago_proveedores');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    public function actualizarPago($datos)
    {
        
        $result = $this->db->where('codpago', $datos['codpago']);
        $result = $this->db->update('pago_proveedores', $datos);
        return $result;
    }

}
