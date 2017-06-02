<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsFacturar extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Metodo publico, forma de obtener una lista de todas las facturas
    public function obtenerFacturas()
    {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('facturas');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener una lista de todos los productos/servicios asociados a una factura
    public function obtenerProductosServicios($cod_factura)
    {                                                                                      
        $result = $this->db->where('codfactura', $cod_factura);
        $result = $this->db->get('facturas_ps');
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

		$result = $this->db->insert("facturas", $data);
		//~ $result = $this->db->insert("cliente", $data);
		return $result;
        
    }
    
    // Metodo publico, forma de insertar los datos
    //~ public function insertar($data,$datos)
    public function insertar_ps($data)
    {
        //~ $result = $this->db->where('cirif =', $datos['cirif']);
        //~ $result = $this->db->get('cliente');

		$result = $this->db->insert("facturas_ps", $data);
		//~ $result = $this->db->insert("cliente", $data);
		return $result;
        
    }    
    
    public function obtenerFactura($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('facturas');
        return $query->row();
    }
    
    public function obtenerFacturaCod($cod)
    {
        $this->db->where('codfactura', $cod);
        $query = $this->db->get('facturas');
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

    public function actualizarFactura($datos)
    {
        
        $result = $this->db->where('codfactura', $datos['codfactura']);
        $result = $this->db->update('facturas', $datos);
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
