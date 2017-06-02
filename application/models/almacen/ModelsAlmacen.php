<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsAlmacen extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
    
    // Metodo publico, forma de obtener una lista de todas las facturas pagadas y entregadas
    public function obtenerFacturas()
    {
		$this->db->order_by("id", "desc");
		$this->db->where('estado', 2);
		$this->db->or_where('estado', 4);
        $query = $this->db->get('facturas');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener una lista de todos los pedidos aprobados e ingresados
    public function obtenerPedidos()
    {
		$this->db->order_by("id", "desc");
		$this->db->where('estado', 2);
		$this->db->or_where('estado', 4);
        $query = $this->db->get('pedidos');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Metodo publico, forma de obtener el número de facturas verificadas por entregar
    public function obtenerNumFacturas()
    {
		$this->db->where('estado', 2);
        $this->db->from('facturas');
		echo $this->db->count_all_results();
    }
    
    // Metodo publico, forma de obtener el número de pedidos aprobados por ingresar
    public function obtenerNumPedidos()
    {
		$this->db->where('estado', 2);
        $this->db->from('pedidos');
		echo $this->db->count_all_results();
    }
    
    // Método publico, forma de validar una factura por su número de control
    public function verificarFactura($num_control, $cod_factura)
    {
		$this->db->where('num_control', $num_control);
		$this->db->where('codfactura', $cod_factura);
        $query = $this->db->get('facturas');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return 0;
    }
    
    // Método publico, forma de validar un pedido por su número de control
    public function verificarPedido($num_control, $cod_pedido)
    {
		$this->db->where('num_control', $num_control);
		$this->db->where('codpedido', $cod_pedido);
        $query = $this->db->get('pedidos');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return 0;
    }

}
