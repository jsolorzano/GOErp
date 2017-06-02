<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsPedidos extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Metodo publico, forma de obtener una lista de todos los pedidos
    public function obtenerPedidos()
    {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('pedidos');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener una lista de todos los productos/servicios asociados a una factura
    public function obtenerProductosServicios($cod_factura)
    {                                                                                      
        $result = $this->db->where('codpedido', $cod_factura);
        $result = $this->db->get('pedidos_ps');
        return $result->result();
    }

	// Metodo publico, forma de obtener una lista de todos los proveedores
    public function obtenerProveedores()
    {                                                                                      
        $query = $this->db->get('proveedor');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener un cliente por su código
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

		$result = $this->db->insert("pedidos", $data);
		//~ $result = $this->db->insert("cliente", $data);
		return $result;
        
    }
    
    // Metodo publico, forma de insertar los datos
    //~ public function insertar($data,$datos)
    public function insertar_ps($data)
    {

		$result = $this->db->insert("pedidos_ps", $data);
		//~ $result = $this->db->insert("cliente", $data);
		return $result;
        
    }    
    
    public function obtenerPedido($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('pedidos');
        return $query->row();
    }
    
    public function obtenerPedidoCod($cod)
    {
        $this->db->where('codpedido', $cod);
        $query = $this->db->get('pedidos');
        return $query->row();
    }
    
    // Metodo publico, forma de obtener la lista de facturas de un cliente según su código
    public function obtenerFacturasCliente($codcliente)
    {
		$result = $this->db->where('codcliente', $codcliente);
        $query = $this->db->get('facturas');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    public function actualizarPedido($datos)
    {
        
        $result = $this->db->where('codpedido', $datos['codpedido']);
        $result = $this->db->update('pedidos', $datos);
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
        $result = $this->db->delete('facturas_ps', array('codfacturaps'=>$cod));
        return $result;
    }

}
